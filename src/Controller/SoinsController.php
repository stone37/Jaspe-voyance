<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Settings;
use App\Form\SoinsContactType;
use App\Mailer\Mailer;
use App\Service\SettingsManager;
use ReCaptcha\ReCaptcha;
use Sonata\SeoBundle\Seo\SeoPageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class SoinsController extends AbstractController
{
    use ControllerTrait;

    public function index(Breadcrumbs $breadcrumbs, SeoPageInterface $seoPage, SettingsManager $manager)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveSoins())  throw $this->createNotFoundException('Page introuvable');

        $seoPage->setTitle('Soins énergétiques | Japse voyance')
            ->addMeta('name', 'keywords', 'soins énergétiques, soins énergétiques à paris, renforcement du système immunitaire, amélioration de la vitalité')
            ->addMeta('name', 'description', 'Les soins et thérapies énergétiques contribuent à apaiser le stress, cause de mal-être et de nombreuses maladies')
            ->addMeta('property', 'og:title', 'Soins énergétiques | Japse voyance')
            ->addMeta('property', 'og:type', 'service')
            ->addMeta('property', 'og:url',  $this->generateUrl('app_soins_index'))
            ->addMeta('property', 'og:description', 'Les soins et thérapies énergétiques contribuent à apaiser le stress, cause de mal-être et de nombreuses maladies');

        $this->breadcrumb($breadcrumbs)->addItem('Soins énergétiques');

        return $this->render('site/soins/index.html.twig', [
            'settings' => $setting,
        ]);
    }

    public function soinsContact(
        Request $request,
        SettingsManager $manager,
        Breadcrumbs $breadcrumbs,
        Mailer $mailer,
        ReCaptcha $reCaptcha)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveSoins())  throw $this->createNotFoundException('Page introuvable');

        $this->breadcrumb($breadcrumbs)
            ->addItem('Soins énergétiques', $this->generateUrl('app_soins_index'))
            ->addItem('prendre rendez vous');

        $form = $this->createForm(SoinsContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() &&
            $form->isValid() &&
            $reCaptcha->verify($form['recaptchaToken']->getData())->isSuccess()) {

            $mailer->sendSoinsEmailMessage($form->getData());
            $mailer->sendUserSoinsEmailMessage($form->getData());

            $this->addFlash('success', 'Votre demande a été transmise, nous vous répondrons dans les meilleurs délais.');

            return $this->redirectToRoute('app_soins_index');
        }

        return $this->render('site/soins/contact.html.twig', [
            'form' => $form->createView(),
            'settings' => $setting,
        ]);
    }
}

