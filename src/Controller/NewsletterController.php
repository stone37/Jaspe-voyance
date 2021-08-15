<?php

namespace App\Controller;

use App\Entity\NewsletterData;
use App\Handler\NewsletterSubscriptionHandler;
use App\Validator\NewsletterValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsletterController extends AbstractController
{

    /**
     * @param Request $request
     * @param NewsletterSubscriptionHandler $handler
     * @param NewsletterValidator $newsletterValidator
     * @return JsonResponse
     */
    public function subscribe(
        Request $request,
        NewsletterSubscriptionHandler $handler,
        NewsletterValidator $newsletterValidator)
    {
        $email = $request->request->get('email');
        $firstName = $request->request->get('firstName');

        $errors = $newsletterValidator->validate($email);

        if (!$this->isCsrfTokenValid('newsletter', $request->request->get('_token'))) {
            $errors[] = 'Le jeton CSRF est invalide.';
        }

        if (!$firstName) {
            $errors[] = 'Veuillez remplir le champ de prénom.';
        }

        if (count($errors) === 0) {
            $handler->subscribe($email, $firstName);

            return new JsonResponse([
                'success' => true,
                'message' => 'Vous êtes bien inscrit à notre newsletter.',
            ]);
        }

        return new JsonResponse(['success' => false, 'errors' => json_encode($errors)]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param NewsletterSubscriptionHandler $handler
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unsubscribe(
        Request $request,
        EntityManagerInterface $em,
        NewsletterSubscriptionHandler $handler)
    {
        $email = $request->query->get('email');
        $data = $em->getRepository(NewsletterData::class)->findOneBy(['email' => $email]);

        if (!$data) {
            $this->addFlash('error', 'Cette adresse n\'est pas notre base de donnée');
            return $this->redirectToRoute('app_home');
        }

        $handler->unsubscribe($data);

        $this->addFlash('success', 'Votre adresse mail a été supprimer de notre newsletter');

        return $this->redirectToRoute('app_home');
    }
}
