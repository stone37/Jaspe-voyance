<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Form\ContactType;
use App\Mailer\Mailer;
use App\Service\SettingsManager;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class ContactController extends AbstractController
{
    use ControllerTrait;

    public function index(
        Request $request,
        Breadcrumbs $breadcrumbs,
        Mailer $mailer,
        ReCaptcha $reCaptcha,
        SettingsManager $manager)
    {
        $this->breadcrumb($breadcrumbs)->addItem('Contact');

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() &&
            $form->isValid() &&
            $reCaptcha->verify($form['recaptchaToken']->getData())->isSuccess()) {
            $contact = $form->getData();

            $mailer->sendContactEmailMessage($contact);
            $mailer->sendUserContactEmailMessage($contact);

            $this->addFlash('success', 'Votre message a été transmis, nous vous répondrons dans les meilleurs délais.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('site/contact/index.html.twig', [
            'form' => $form->createView(),
            'settings' => $this->getSettings($manager),
        ]);
    }
}
