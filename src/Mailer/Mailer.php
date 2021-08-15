<?php

namespace App\Mailer;

use App\Entity\Commande;
use App\Entity\Demand;
use App\Entity\Email;
use App\Entity\Settings;
use App\Service\SettingsManager;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class Mailer
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * Mailer constructor.
     *
     * @param Swift_Mailer $mailer
     * @param UrlGeneratorInterface $router
     * @param Environment $twig
     * @param SettingsManager $settings
     */
    public function __construct(
        Swift_Mailer $mailer, UrlGeneratorInterface $router,
        EngineInterface $templating, SettingsManager $settings)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
        $this->settings = $settings->get();
    }

    /**
     * @param Commande $order
     */
    public function sendOrderValidateEmailMessage(Commande $order)
    {
        $template = 'emails/orderValidate.html.twig';
        $rendered = $this->templating->render($template, [
            'order' => $order,
            'settings' => $this->settings
        ]);

        $subject = 'Validation de votre commande';

        $this->sendMessage($rendered, $subject, 'noreply@jaspevoyance.com', $order->getUser()->getEmail());
    }

    public function sendContactEmailMessage($contact)
    {
        $template = 'emails/contact.html.twig';
        $rendered = $this->templating->render($template, [
            'contact' => $contact,
            'settings' => $this->settings
        ]);

        $subject = 'Nouveau contact';

        $this->sendMessage($rendered, $subject, 'noreply@jaspevoyance.com', $this->settings->getEmail());
    }

    public function sendUserContactEmailMessage($contact)
    {
        $template = 'emails/userContact.html.twig';
        $rendered = $this->templating->render($template, [
            'contact' => $contact,
            'settings' => $this->settings
        ]);

        $subject = 'Demande de contact transmise';

        $this->sendMessage($rendered, $subject, 'noreply@jaspevoyance.com', $contact['email']);
    }

    public function sendUserDeletedEmailMessage($contact)
    {
        $template = 'emails/userContact.html.twig';
        $rendered = $this->templating->render($template, [
            'contact' => $contact,
            'settings' => $this->settings
        ]);

        $subject = 'Suppression du compte';

        $this->sendMessage($rendered, $subject, $this->settings->getEmail(), $contact['email']);
    }

    public function sendMeetEmailMessage(Demand $demand)
    {
        $subject = 'Nouvelle consultation';

        $template = 'emails/meet.html.twig';
        $rendered = $this->templating->render($template, [
            'contact' => $demand,
            'settings' => $this->settings
        ]);

        $this->sendMessage($rendered, $subject, 'noreply@jaspevoyance.com', $this->settings->getEmail());
    }

    public function sendUserMeetEmailMessage(Demand $demand)
    {
        $template = 'emails/userMeet.html.twig';
        $rendered = $this->templating->render($template, [
            'contact' => $demand,
            'settings' => $this->settings
        ]);

        $subject = 'Demande de consultation transmise';

        $this->sendMessage($rendered, $subject, 'noreply@jaspevoyance.com', $demand->getEmail());
    }

    public function sendSoinsEmailMessage($contact)
    {
        $template = 'emails/soins.html.twig';
        $rendered = $this->templating->render($template, [
            'contact' => $contact,
            'settings' => $this->settings
        ]);

        $subject = 'Nouvelle demande';

        $this->sendMessage($rendered, $subject, 'noreply@jaspevoyance.com', $this->settings->getEmail());
    }

    public function sendUserSoinsEmailMessage($contact)
    {
        $template = 'emails/userSoins.html.twig';
        $rendered = $this->templating->render($template, [
            'contact' => $contact,
            'settings' => $this->settings
        ]);

        $subject = 'Demande de contact transmise';

        $this->sendMessage($rendered, $subject, 'noreply@jaspevoyance.com', $contact['email']);
    }

    public function sendEmailMessage(Email $email)
    {
        $template = 'emails/sender.html.twig';
        $rendered = $this->templating->render($template, [
            'data' => $email,
            'settings' => $this->settings
        ]);

        $this->sendMessage($rendered, $email->getSubject(), $this->settings->getEmail(), $email->getDestinataire());
    }

    public function sendGlobalMessage($data, $email)
    {
        $template = 'emails/senderGlobal.html.twig';
        $rendered = $this->templating->render($template, [
            'data' => $data,
            'settings' => $this->settings
        ]);

        $this->sendMessage($rendered, $data['subject'], $this->settings->getEmail(), $email);
    }

    /**
     * @param string       $rendered
     * @param string       $subject
     * @param array|string $fromEmail
     * @param array|string $toEmail
     */
    protected function sendMessage($renderedTemplate, $subject, $fromEmail, $toEmail)
    {
        $message = (new Swift_Message())
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($renderedTemplate, 'text/html');

        $this->mailer->send($message);
    }
}


