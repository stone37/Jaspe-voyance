<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Review;
use App\Entity\Settings;
use App\Form\ReviewType;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class ReviewController extends AbstractController
{
    use ControllerTrait;

    public function index(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        SettingsManager $manager,
        ReCaptcha $reCaptcha,
        Breadcrumbs $breadcrumbs)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveTemoignages())  throw $this->createNotFoundException('Page introuvable');

        $this->breadcrumb($breadcrumbs)->addItem('Témoignages');

        $review = new Review();

        if ($this->getUser()) {
            $review->setEmail($this->getUser()->getEmail());
            $review->setName($this->getUser()->getFirstName());
        }

        $form = $this->createForm(ReviewType::class, $review);

        $qb = $em->getRepository(Review::class)->getEnabled();
        $reviews = $paginator->paginate($qb, $request->query->getInt('page', 1), 5);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()
            && $reCaptcha->verify($form['recaptchaToken']->getData())->isSuccess()) {
            $em->persist($review);
            $em->flush();

            $this->addFlash('info', 'Merci pour votre témoignage');

            return $this->redirectToRoute('app_review_index');
        }

        return $this->render('site/review/index.html.twig', [
            'form' => $form->createView(),
            'reviews' => $reviews,
            'settings' => $setting
        ]);
    }

    public function home(SettingsManager $manager, EntityManagerInterface $em)
    {
        $reviews = $em->getRepository(Review::class)->getHomeReviews();

        return $this->render('site/review/partial/home.html.twig', [
            'reviews' => $reviews,
            'settings' => $this->getSettings($manager),
        ]);
    }

}
