<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Commande;
use App\Entity\Demand;
use App\Entity\OrderItem;
use App\Entity\Payment;
use App\Entity\Product;
use App\Entity\Settings;
use App\Entity\Shipment;
use App\Entity\ShipmentMethod;
use App\Mailer\Mailer;
use App\Service\SettingsManager;
use App\Service\UniqueSuiteNumberGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OrderController extends AbstractController
{
    use ControllerTrait;

    public function invoice(EntityManagerInterface $em, SettingsManager $manager, $id)
    {
        $order = $em->getRepository(Commande::class)->find($id);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);

        /** @var Settings $settings */
        $settings = $this->getSettings($manager);

        $html = $this->renderView('site/order/invoice.html.twig', [
            'settings' => $settings,
            'order' => $order
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("jvfacture.pdf", ["Attachment" => false]);
    }

    public function prepareOrder(EntityManagerInterface $em, SessionInterface $session)
    {
        if (!$session->has('order')) {
            $order = new Commande();
            $shipment = new Shipment();

            $order->setShipment($shipment);
        }
        else {
            $order = $em->getRepository(Commande::class)->find($session->get('order'));

            $order = $this->clearItems($order, $em);
        }

        /** @var ShipmentMethod $method */
        $method = $em->getRepository(ShipmentMethod::class)->getMethod();

        $order->setUser($this->getUser());
        $order->setAddress($this->getUser()->getAddress());
        $order->setShipmentPrice($method->getPrice());
        $order->setValidate(false);
        $order->setReference(false);

        $cart = $session->get('app_cart');
        $products = $em->getRepository(Product::class)->findArray(array_keys($cart));
        $totalHT = 0;
        $totalTVA = 0;

        /** @var Product $product */
        foreach ($products as $product) {
            $priceHT = ($product->getPrice() * $cart[$product->getId()]);
            $priceTTC = (((($product->getPrice() * $product->getTva()->getValue())/100)+$product->getPrice()) * $cart[$product->getId()]);
            $totalHT += $priceHT;
            $totalTVA += round($priceTTC - $priceHT,2);

            $item = new OrderItem(); 
            $item->setProduct($product);
            $item->setOrder($order);
            $item->setQuantity($cart[$product->getId()]);
            $item->setPriceTotal($priceTTC);

            $em->persist($item); 
        }

        $order->setPriceTotal($totalHT);
        $order->setTotalTva($totalTVA);
        $order->setPriceTotalTva($totalHT+$totalTVA+$method->getPrice());

        if (!$session->has('order')) {
            $em->persist($order);
        }

        $em->flush();

        $session->set('order', $order->getId());

        return new Response($order->getId());
    }

    private function clearItems(Commande $order, EntityManagerInterface $em)
    {
        foreach ($order->getItems() as $item) {
            $em->remove($item);
        }

        $em->flush();

        return $order;
    }


    public function validateOrder(EntityManagerInterface $em, SettingsManager $manager, $id)
    {
        $order = $em->getRepository(Commande::class)->find($id);
        $method = $em->getRepository(ShipmentMethod::class)->getMethod();
        /** @var Settings $settings */
        $settings = $this->getSettings($manager);

        $client = new PayPalHttpClient(new SandboxEnvironment($settings->getPaypalKey(), $settings->getPaypalSecret()));

        //$client = new PayPalHttpClient(new SandboxEnvironment(getenv('PAYPAL_SITE_KEY'), getenv('PAYPAL_SECRET')));

        $request = new OrdersCreateRequest();
        $request->headers["prefer"] = "return=representation";
        $request->body = $this->buildRequestBody($settings, $order, $method);

        $response = $client->execute($request);

        $url = $response->result->links['1']->href;

        if ($response) return $this->redirect($url);
        else return $this->redirect($this->generateUrl('app_cart_validate'));
    }

    public function validateDemandOrder(EntityManagerInterface $em, SettingsManager $manager, $id)
    {
        $order = $em->getRepository(Commande::class)->find($id);
        /** @var Settings $settings */
        $settings = $this->getSettings($manager);

        $client = new PayPalHttpClient(new SandboxEnvironment($settings->getPaypalKey(), $settings->getPaypalSecret()));

        $request = new OrdersCreateRequest();
        $request->headers["prefer"] = "return=representation";
        $request->body = $this->buildDemandRequestBody($settings, $order);

        $response = $client->execute($request);

        $url = $response->result->links['1']->href;

        if ($response) return $this->redirect($url);
        else return $this->redirect($this->generateUrl('app_cart_demand_validate'));
    }

    public function payment(
        Request $request,
        EntityManagerInterface $em,
        SessionInterface $session,
        Mailer $mailer,
        SettingsManager $manager,
        UniqueSuiteNumberGenerator $generator)
    {
        $request = new OrdersCaptureRequest($request->query->get('token'));
        /** @var Settings $settings */
        $settings = $this->getSettings($manager);

        $client = new PayPalHttpClient(new SandboxEnvironment($settings->getPaypalKey(), $settings->getPaypalSecret()));
        $response = $client->execute($request);

        $results = $response->result;
        $status = $results->status;
        $purchaseUnits = $results->purchase_units;

        if ($status === "COMPLETED") {
            $id = $purchaseUnits[0]->payments->captures[0]->custom_id;
            $order = $em->getRepository(Commande::class)->find($id);

            if (!$order || $order->isValidate())
                throw $this->createNotFoundException('La commande n\'existe pas');

            $order->setValidate(true);
            $order->setReference($generator->generate(9));

            $payment = new Payment();
            $payment->setOrder($order);
            $payment->setEnabled(true);

            $em->persist($payment);
            $em->flush();

            $mailer->sendOrderValidateEmailMessage($order);

            $session->remove('order');
            $session->remove('app_cart');

            $this->addFlash('success', 'Votre commande est validé avec succès');

            return $this->redirectToRoute('app_dashboard_order_index_active');
        } else {

            $this->addFlash('error', 'Désolé, votre commande n\'a pas pu être validée avec succès');

            return $this->redirectToRoute('app_cart_index');
        }
    }

    public function paymentD(
        Request $request,
        EntityManagerInterface $em,
        SessionInterface $session,
        Mailer $mailer,
        SettingsManager $manager,
        UniqueSuiteNumberGenerator $generator)
    {
        $request = new OrdersCaptureRequest($request->query->get('token'));
        /** @var Settings $settings */
        $settings = $this->getSettings($manager);

        $client = new PayPalHttpClient(new SandboxEnvironment($settings->getPaypalKey(), $settings->getPaypalSecret()));
        $response = $client->execute($request);

        $results = $response->result;
        $status = $results->status;
        $purchaseUnits = $results->purchase_units;

        if ($status === "COMPLETED") {
            $id = $purchaseUnits[0]->payments->captures[0]->custom_id;

            /** @var Commande $order */
            $order = $em->getRepository(Commande::class)->find($id);

            if (!$order || $order->isValidate())
                throw $this->createNotFoundException('La commande n\'existe pas');

            $order->setValidate(true);
            $order->setReference($generator->generate(9));

            $payment = new Payment();
            $payment->setOrder($order);
            $payment->setEnabled(true);

            $demand = new Demand();
            $demand->setType(1);
            $demand->setFirstName($order->getUser()->getFirstName());
            $demand->setLastName($order->getUser()->getLastName());
            $demand->setEmail($order->getUser()->getEmail());
            $demand->setBirthDay($session->get('app_demand_birthday'));
            $demand->setCityOfBirth($session->get('app_demand_city_of_birth'));
            $demand->setField($session->get('app_demand_field'));
            $demand->setComments($session->get('app_demand_comments'));

            $em->persist($demand);
            $em->persist($payment);
            $em->flush();

            $mailer->sendOrderValidateEmailMessage($order);
            $mailer->sendMeetEmailMessage($demand);
            $mailer->sendUserMeetEmailMessage($demand);

            $session->remove('demand_order');
            $session->remove('app_demand_birthday');
            $session->remove('app_demand_city_of_birth');
            $session->remove('app_demand_field');
            $session->remove('app_demand_comments');

            $this->addFlash('success', 'Votre commande est validé avec succès');
            $this->addFlash('success', 'Votre demande a été transmise, nous vous répondrons dans les meilleurs délais.');

            return $this->redirectToRoute('app_dashboard_order_index_active');
        } else {

            $this->addFlash('error', 'Désolé, votre commande n\'a pas pu être validée avec succès');

            return $this->redirectToRoute('app_voyance_mail_contact');
        }
    }

    private function buildRequestBody(
        Settings $settings,
        Commande $order,
        ShipmentMethod $method)
    {
        $name = strtoupper($settings->getName());
        $returnUrl = $this->generateUrl('app_order_pay', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $cancelUrl = $this->generateUrl('app_cart_index', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $products = [];

        foreach ($order->getItems() as $key => $item) {
            $products[$key] = [
                'name' => $item->getProduct()->getName(),
                'description' => $item->getProduct()->getDescription(),
                //'sku' => 'sku01',
                'unit_amount' => [
                    'currency_code' => 'EUR',
                    'value' => $item->getProduct()->getPrice(),
                ],
                'tax' => [
                    'currency_code' => 'EUR',
                    'value' => (($item->getProduct()->getPrice() * $item->getProduct()->getTva()->getValue())/ 100),
                ],
                'quantity' => $item->getQuantity(),
                'category' => 'PHYSICAL_GOODS',
            ];
        }

        return array(
            'intent' => 'CAPTURE',
            'application_context' =>
                array(
                    'brand_name' => $name,
                    'locale' => 'fr-FR',
                    'landing_page' => 'BILLING',
                    'shipping_preference' => 'SET_PROVIDED_ADDRESS',
                    'user_action' => 'PAY_NOW',
                    "cancel_url" => $cancelUrl,
                    "return_url" => $returnUrl,
                ),
            'purchase_units' =>
                array(
                    0 =>
                        array(
                            'reference_id' => 'PUHF',
                            'description' => 'Produits encens',
                            'custom_id' => $order->getId(),
                            'soft_descriptor' => 'HighFashions',
                            'amount' =>
                                array(
                                    'currency_code' => 'EUR',
                                    'value' => $order->getPriceTotalTva(),
                                    'breakdown' =>
                                        array(
                                            'item_total' =>
                                                array(
                                                    'currency_code' => 'EUR',
                                                    'value' => $order->getPriceTotal(),
                                                ),
                                            'shipping' =>
                                                array(
                                                    'currency_code' => 'EUR',
                                                    'value' => $order->getShipmentPrice(),
                                                ),
                                            'tax_total' =>
                                                array(
                                                    'currency_code' => 'EUR',
                                                    'value' => $order->getTotalTva(),
                                                ),
                                        ),
                                ),
                            'items' => $products,
                            'shipping' =>
                                array(
                                    'method' => $method->getName(),
                                    'address' =>
                                        array(
                                            'address_line_1' => $order->getAddress(),
                                            'address_line_2' => $order->getAddress(),
                                            'admin_area_2' => $order->getUser()->getVille(),
                                            'admin_area_1' => $order->getUser()->getVille(),
                                            'postal_code' => $order->getUser()->getCode(),
                                            'country_code' => 'FR',
                                        ),
                                ),
                        ),
                ),
        );
    }

    private function buildDemandRequestBody(Settings $settings, Commande $order)
    {
        $name = strtoupper($settings->getName());
        $returnUrl = $this->generateUrl('app_order_demand_pay', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $cancelUrl = $this->generateUrl('app_voyance_mail_contact', [], UrlGeneratorInterface::ABSOLUTE_URL);

        return array(
            'intent' => 'CAPTURE',
            'application_context' =>
                array(
                    'brand_name' => $name,
                    'locale' => 'fr-FR',
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                ),
            'purchase_units' =>
                array(
                    0 =>
                        array(
                            'reference_id' => 'PUHF',
                            'description' => 'Consultation de voyance par mail',
                            'custom_id' => $order->getId(),
                            'soft_descriptor' => 'HighFashions',
                            'amount' =>
                                array(
                                    'currency_code' => 'EUR',
                                    'value' => $order->getPriceTotalTva()
                                ),
                        )
                )
        );
    }


}
