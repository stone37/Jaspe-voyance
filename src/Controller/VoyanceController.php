<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Demand;
use App\Entity\Settings;
use App\Form\MeetMailType;
use App\Form\MeetPhoneType;
use App\Form\MeetType;
use App\Mailer\Mailer;
use App\Service\PriceService;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use ReCaptcha\ReCaptcha;
use Sonata\SeoBundle\Seo\SeoPageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class VoyanceController extends AbstractController
{
    use ControllerTrait;

    public function index(
        Breadcrumbs $breadcrumbs,
        SettingsManager $manager,
        SeoPageInterface $seoPage)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveVoyance()) throw $this->createNotFoundException('Page introuvable');

        $seoPage->setTitle('Voyances | Japse voyance')
            ->addMeta('name', 'keywords', 'voyance par mail, voyance par mail à paris, voyance en cabinet, voyance en cabinet à paris, voyance par téléphone à paris, voyance par téléphone, voyance precise, voyance de qualité')
            ->addMeta('name', 'description', 'Service de voyance en ligne par mail, téléphone ou en cabinet. Des consultations précises, des resultats rapides et de qualité.')
            ->addMeta('property', 'og:title', 'Voyances | Japse voyance')
            ->addMeta('property', 'og:type', 'service')
            ->addMeta('property', 'og:url',  $this->generateUrl('app_voyance_index'))
            ->addMeta('property', 'og:description', 'Service de voyance en ligne par mail, téléphone ou en cabinet. Des consultations précises, des resultats rapides et de qualité.\'');

        $this->breadcrumb($breadcrumbs)->addItem('Voyances');

        return $this->render('site/voyance/index.html.twig', [
            'settings' => $setting,
        ]);
    }

    public function mail(
        Breadcrumbs $breadcrumbs,
        SeoPageInterface $seoPage,
        SettingsManager $manager)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveVoyance())  throw $this->createNotFoundException('Page introuvable');
        if (!$setting->isActiveMailV())  throw $this->createNotFoundException('Page introuvable');

        $seoPage->setTitle('Voyance par mail | Japse voyance')
            ->addMeta('name', 'keywords', 'voyance par mail, voyance par mail à paris, voyance precise, voyance de qualité')
            ->addMeta('name', 'description', 'Nous vous proposons donc de bénéficier simplement de prédictions de voyance par mail. Il vous suffit pour cela de rédiger, en quelques lignes, les questions qui vous tourmentent, quel que soit le sujet (travail, amour, famille, finance…). Nous les étudierons calmement et de façon très sérieuse, en m’appuyant sur nos dons et sur nos supports de voyance')
            ->addMeta('property', 'og:title', 'Voyance par mail | Japse voyance')
            ->addMeta('property', 'og:type', 'service')
            ->addMeta('property', 'og:url',  $this->generateUrl('app_voyance_mail'))
            ->addMeta('property', 'og:description', 'Nous vous proposons donc de bénéficier simplement de prédictions de voyance par mail. Il vous suffit pour cela de rédiger, en quelques lignes, les questions qui vous tourmentent, quel que soit le sujet (travail, amour, famille, finance…). Nous les étudierons calmement et de façon très sérieuse, en m’appuyant sur nos dons et sur nos supports de voyance');

        $this->breadcrumb($breadcrumbs)
            ->addItem('Voyances', $this->generateUrl('app_voyance_index'))
            ->addItem('Voyance par mail');

        return $this->render('site/voyance/mail.html.twig', [
            'settings' => $setting,
        ]);
    }

    public function mailContact(
        Request $request,
        EntityManagerInterface $em,
        Breadcrumbs $breadcrumbs,
        SettingsManager $manager,
        SessionInterface $session,
        Mailer $mailer,
        ReCaptcha $reCaptcha)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveVoyance())  throw $this->createNotFoundException('Page introuvable');
        if (!$setting->isActiveMailV())  throw $this->createNotFoundException('Page introuvable');

        $this->breadcrumb($breadcrumbs)
            ->addItem('Voyances', $this->generateUrl('app_voyance_index'))
            ->addItem('Voyance par mail', $this->generateUrl('app_voyance_mail'))
            ->addItem('prendre rendez-vous');

        $demand = new Demand();
        $demand->setType(1);

        if ($this->getUser()) {
            $demand->setEmail($this->getUser()->getEmail());
            $demand->setFirstName($this->getUser()->getFirstName());
            $demand->setLastName($this->getUser()->getLastName());
            $demand->setBirthDay($this->getUser()->getBirthDay());
        }

        $form = $this->createForm(MeetMailType::class, $demand);
        $form->handleRequest($request);

        if ($form->isSubmitted() &&
            $form->isValid() /*&&
            $reCaptcha->verify($form['recaptchaToken']->getData())->isSuccess()*/) {

            $price = $setting->getVMailPrice()-(($setting->getVMailPrice()*$setting->getVMailRemise())/100);

            if ($price > 0) {
                $session->set('app_demand_birthday', $demand->getBirthDay());
                $session->set('app_demand_city_of_birth', $demand->getCityOfBirth());
                $session->set('app_demand_field', $demand->getField());
                $session->set('app_demand_comments', $demand->getComments());

                return $this->redirectToRoute('app_cart_demand_validate');
            } else {
                $em->persist($demand);
                $em->flush();

                $mailer->sendMeetEmailMessage($form->getData());
                $mailer->sendUserMeetEmailMessage($form->getData());

                $this->addFlash('success', 'Votre demande a été transmise, nous vous répondrons dans les meilleurs délais.');

                return $this->redirectToRoute('app_voyance_mail');
            }
        }

        return $this->render('site/voyance/mailContact.html.twig', [
            'form' => $form->createView(),
            'settings' => $setting,
        ]);
    }

    public function cabinet(Breadcrumbs $breadcrumbs, SeoPageInterface $seoPage, SettingsManager $manager)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveVoyance()) throw $this->createNotFoundException('Page introuvable');
        if (!$setting->isActiveCabinetV()) throw $this->createNotFoundException('Page introuvable');

        $seoPage->setTitle('Voyance en cabinet | Japse voyance')
            ->addMeta('name', 'keywords', 'voyance en cabinet, voyance en cabinet à paris, voyance precise, voyance de qualité')
            ->addMeta('name', 'description', 'Réalisée sur rendez-vous dans le calme et la confiance, la consultation en cabinet constitue une formule de voyance confortable et idéale. Le contact direct permet de mieux vous connaître et d’échanger avec vous de façon précise')
            ->addMeta('property', 'og:title', 'Voyance en cabinet | Japse voyance')
            ->addMeta('property', 'og:type', 'service')
            ->addMeta('property', 'og:url',  $this->generateUrl('app_voyance_cabinet'))
            ->addMeta('property', 'og:description', 'Réalisée sur rendez-vous dans le calme et la confiance, la consultation en cabinet constitue une formule de voyance confortable et idéale. Le contact direct permet de mieux vous connaître et d’échanger avec vous de façon précise');

        $this->breadcrumb($breadcrumbs)
            ->addItem('Voyances', $this->generateUrl('app_voyance_index'))
            ->addItem('Voyance en cabinet');

        return $this->render('site/voyance/cabinet.html.twig', [
            'settings' => $setting,
        ]);
    }

    public function cabinetContact(
        Request $request,
        EntityManagerInterface $em,
        Breadcrumbs $breadcrumbs,
        SettingsManager $manager,
        Mailer $mailer,
        ReCaptcha $reCaptcha)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveVoyance())  throw $this->createNotFoundException('Page introuvable');
        if (!$setting->isActiveCabinetV())  throw $this->createNotFoundException('Page introuvable');

        $this->breadcrumb($breadcrumbs)
            ->addItem('Voyances', $this->generateUrl('app_voyance_index'))
            ->addItem('Voyance en cabinet', $this->generateUrl('app_voyance_cabinet'))
            ->addItem('prendre rendez-vous');

        $demand = new Demand();
        $demand->setType(3);

        if ($this->getUser()) {
            $demand->setEmail($this->getUser()->getEmail());
            $demand->setFirstName($this->getUser()->getFirstName());
            $demand->setLastName($this->getUser()->getLastName());
            $demand->setPhone($this->getUser()->getPhone());
        }

        $form = $this->createForm(MeetType::class, $demand);
        $form->handleRequest($request);

        if ($form->isSubmitted() &&
            $form->isValid() /*&&
            $reCaptcha->verify($form['recaptchaToken']->getData())->isSuccess()*/) {

            $em->persist($demand);
            $em->flush();

            $mailer->sendMeetEmailMessage($form->getData());
            $mailer->sendUserMeetEmailMessage($form->getData());

            $this->addFlash('success', 'Votre demande a été transmise, nous vous répondrons dans les meilleurs délais.');

            return $this->redirectToRoute('app_voyance_cabinet');
        }

        return $this->render('site/voyance/cabinetContact.html.twig', [
            'form' => $form->createView(),
            'settings' => $setting,
        ]);
    }

    public function phone(Breadcrumbs $breadcrumbs, SeoPageInterface $seoPage, SettingsManager $manager)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveVoyance())  throw $this->createNotFoundException('Page introuvable');
        if (!$setting->isActivePhoneV())  throw $this->createNotFoundException('Page introuvable');

        $seoPage->setTitle('Voyance par téléphone | Japse voyance')
            ->addMeta('name', 'keywords', 'voyance par téléphone à paris, voyance par téléphone, voyance precise, voyance de qualité')
            ->addMeta('name', 'description', 'Totalement privée et personnalisée, la voyance par téléphone doit être réalisée dans le calme et la sérénité. Réalisé dans de bonnes conditions, cet entretien nous permet d’obtenir une vision précise de votre futur, au même titre que la voyance en cabinet ou la voyance par mail')
            ->addMeta('property', 'og:title', 'Voyance par téléphone | Japse voyance')
            ->addMeta('property', 'og:type', 'service')
            ->addMeta('property', 'og:url',  $this->generateUrl('app_voyance_phone'))
            ->addMeta('property', 'og:description', 'Totalement privée et personnalisée, la voyance par téléphone doit être réalisée dans le calme et la sérénité. Réalisé dans de bonnes conditions, cet entretien nous permet d’obtenir une vision précise de votre futur, au même titre que la voyance en cabinet ou la voyance par mail');


        $this->breadcrumb($breadcrumbs)
            ->addItem('Voyances', $this->generateUrl('app_voyance_index'))
            ->addItem('Voyance par téléphone');

        return $this->render('site/voyance/phone.html.twig', [
            'settings' => $setting,
        ]);
    }

    public function phoneContact(
        Request $request,
        EntityManagerInterface $em,
        Breadcrumbs $breadcrumbs,
        SettingsManager $manager,
        Mailer $mailer,
        ReCaptcha $reCaptcha
    )
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveVoyance())  throw $this->createNotFoundException('Page introuvable');
        if (!$setting->isActivePhoneV())  throw $this->createNotFoundException('Page introuvable');

        $this->breadcrumb($breadcrumbs)
            ->addItem('Voyances', $this->generateUrl('app_voyance_index'))
            ->addItem('Voyance par téléphone', $this->generateUrl('app_voyance_phone'))
            ->addItem('prendre rendez-vous');

        $demand = new Demand();
        $demand->setType(2);

        if ($this->getUser()) {
            $demand->setEmail($this->getUser()->getEmail());
            $demand->setFirstName($this->getUser()->getFirstName());
            $demand->setLastName($this->getUser()->getLastName());
            $demand->setPhone($this->getUser()->getPhone());
            $demand->setBirthDay($this->getUser()->getBirthDay());
        }

        $form = $this->createForm(MeetPhoneType::class, $demand);
        $form->handleRequest($request);

        if ($form->isSubmitted() &&
            $form->isValid() &&
            $reCaptcha->verify($form['recaptchaToken']->getData())->isSuccess()) {

            $em->persist($demand);
            $em->flush();

            $mailer->sendMeetEmailMessage($form->getData());
            $mailer->sendUserMeetEmailMessage($form->getData());

            $this->addFlash('success', 'Votre demande a été transmis, nous vous répondrons dans les meilleurs délais.');

            return $this->redirectToRoute('app_voyance_phone');
        }

        return $this->render('site/voyance/phoneContact.html.twig', [
            'form' => $form->createView(),
            'settings' => $setting,
        ]);
    }

    public function home(SettingsManager $manager)
    {
        return $this->render('site/voyance/partial/home.html.twig', [
            'settings' => $this->getSettings($manager),
        ]);
    }
}


