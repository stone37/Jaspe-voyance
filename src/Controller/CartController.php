<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Commande;
use App\Entity\Product;
use App\Entity\Settings;
use App\Entity\ShipmentMethod;
use App\Form\UserAddressType;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class CartController extends AbstractController
{
    use ControllerTrait;
   
    public function index(
        EntityManagerInterface $em,
        Breadcrumbs $breadcrumbs,
        SessionInterface $session)
    {
        $this->breadcrumb($breadcrumbs);

        if (!$session->has('app_cart')) $session->set('app_cart', []);

        $products = $em->getRepository(Product::class)
                        ->findArray(array_keys($session->get('app_cart')));
        $method = $em->getRepository(ShipmentMethod::class)->getMethod();

        return $this->render('site/cart/index.html.twig', [
            'products' => $products,
            'shipmentMethod' => $method,
        ]);
    }

    public function add(Request $request, SessionInterface $session, $id)
    {
        if (!$session->has('app_cart')) $session->set('app_cart', []);
        $cart = $session->get('app_cart');

        if (array_key_exists($id, $cart)) {
            if ($request->query->get('qte') != null) $cart[$id] = $request->query->get('qte');
            $this->addFlash('success', 'Quantité modifié avec succès');
        } else {
            if ($request->query->get('qte') != null)
                $cart[$id] = $request->query->get('qte');
            else
                $cart[$id] = 1;

            $this->addFlash('success', 'Article ajouté avec succès');
        }

        $session->set('app_cart', $cart);

        return $this->redirectToRoute('app_cart_index');
    }

    public function delete(SessionInterface $session, $id)
    {
        $cart = $session->get('app_cart');

        if (array_key_exists($id, $cart))
        {
            unset($cart[$id]);
            $session->set('app_cart', $cart);
            $this->addFlash('success', 'Article supprimé avec succès');
        }

        return $this->redirectToRoute('app_cart_index');
    }

    public function clear(SessionInterface $session)
    {
        $session->remove('app_cart');

        $this->addFlash('success', 'Votre panier est vide');

        return $this->redirectToRoute('app_product_index');
    }

    public function shipment(
        Request $request,
        EntityManagerInterface $em,
        ReCaptcha $reCaptcha,
        Breadcrumbs $breadcrumbs)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $this->breadcrumb($breadcrumbs)->addItem('Adresse de livraison');

        $user = $this->getUser();
        $form = $this->createForm(UserAddressType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()
            /*&& $reCaptcha->verify($form['recaptchaToken']->getData())->isSuccess()*/) {

            $em->flush();

            $this->addFlash('info', 'Votre adresse a été validée');

            return $this->redirectToRoute('app_cart_validate');
        }

        return $this->render('site/cart/shipment.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    public function validate(
        EntityManagerInterface $em,
        Breadcrumbs $breadcrumbs
    )
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $this->breadcrumb($breadcrumbs)->addItem('Valider mon panier');

        $prepareOrder = $this->forward('App\Controller\OrderController::prepareOrder');
        $order = $em->getRepository(Commande::class)->find($prepareOrder->getContent());

        return $this->render('site/cart/validate.html.twig', [
            'order' => $order,
        ]);
    }

    public function validateDemand(
        EntityManagerInterface $em,
        SettingsManager $manager,
        SessionInterface $session,
        Breadcrumbs $breadcrumbs)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $this->breadcrumb($breadcrumbs)->addItem('Valider ma demande de consultation');

        /** @var Settings $setting */
        $settings = $this->getSettings($manager);

        $price = $settings->getVMailPrice() - (($settings->getVMailPrice()*$settings->getVMailRemise())/100);

        if (!$session->has('demand_order')) {
            $order = new Commande();
            $order->setUser($this->getUser());
            $order->setAddress($this->getUser()->getAddress());
            $order->setValidate(false);
            $order->setReference(false);
            $order->setPriceTotal($price);
            $order->setTotalTva(0);
            $order->setPriceTotalTva($price);

            $em->persist($order);
            $em->flush();
        }
        else {
            $order = $em->getRepository(Commande::class)->find($session->get('demand_order'));
        }

        $session->set('demand_order', $order->getId());

        return $this->render('site/cart/demand_validate.html.twig', [
            'order' => $order
        ]);
    }


    /**
     * @param Breadcrumbs $breadcrumbs
     * @return Breadcrumbs
     */
    public function breadcrumb(Breadcrumbs $breadcrumbs)
    {
        $breadcrumbs->addItem('Acceuil', $this->generateUrl('app_home'))
            ->addItem('Votre panier', $this->generateUrl('app_cart_index'));

        return $breadcrumbs;
    }
}
