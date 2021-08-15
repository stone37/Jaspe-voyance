<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Event;
use App\Entity\EventDemand;
use App\Entity\Settings;
use App\Form\EventDemandType;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use ReCaptcha\ReCaptcha;
use Sonata\SeoBundle\Seo\SeoPageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class EventController extends AbstractController
{
    use ControllerTrait;

    public function index(
        Request $request,
        EntityManagerInterface $em,
        SettingsManager $manager,
        PaginatorInterface $paginator,
        SeoPageInterface $seoPage,
        Breadcrumbs $breadcrumbs)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveRencontre())  throw $this->createNotFoundException('Page introuvable');

        $seoPage->setTitle('Rencontres | Japse voyance')
            ->addMeta('name', 'keywords', 'rencontre paris, rencontre amicale, rencontre amoureuse, rencontre amicale paris, rencontre amoureuse paris, trouver amour paris')
            ->addMeta('name', 'description', 'Notre vocation est de mettre en relation des personnes qui veulent vraiment sortir de la solitude affective. Plus rapides, plus authentiques et plus sûrs, les rencontres en petit comité vous permettront d\'échapper au stress du speed-dating.')
            ->addMeta('property', 'og:title', 'Rencontres | Japse voyance')
            ->addMeta('property', 'og:type', 'service')
            ->addMeta('property', 'og:url',  $this->generateUrl('app_event_index'))
            ->addMeta('property', 'og:description', 'Notre vocation est de mettre en relation des personnes qui veulent vraiment sortir de la solitude affective. Plus rapides, plus authentiques et plus sûrs, les rencontres en petit comité vous permettront d\'échapper au stress du speed-dating.');


        $this->breadcrumb($breadcrumbs);

        $data = $em->getRepository(Event::class)->getEventEnabled();

        $events = $paginator->paginate(
            $data, $request->query->getInt('page', 1), 8);

        return $this->render('site/event/index.html.twig', [
            'events' => $events,
            'settings' => $setting,
        ]);
    }

    public function show(
        Request $request,
        EntityManagerInterface $em,
        SettingsManager $manager,
        ReCaptcha $reCaptcha,
        Breadcrumbs $breadcrumbs,
        SeoPageInterface $seoPage,
        $slug)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveRencontre())  throw $this->createNotFoundException('Page introuvable');

        /** @var Event $event */
        $event = $em->getRepository(Event::class)->getEvent($slug);
        if(!$event) throw $this->createNotFoundException('L\'article n\'existe pas');

        $seoPage->setTitle($event->getTitle(). ' | Rencontres | Japse voyance')
            ->addMeta('name', 'description', substr($event->getDescription(), 0, 200))
            ->addMeta('property', 'og:title', $event->getTitle())
            ->addMeta('property', 'og:type', 'service')
            ->addMeta('property', 'og:url',  $this->generateUrl('app_event_show', ['slug' => $event->getSlug()], true))
            ->addMeta('property', 'og:description', substr($event->getDescription(), 0, 200));

        $this->breadcrumb($breadcrumbs)->addItem($event->getTitle());

        $demand = new EventDemand();
        $demand->setEvent($event);

        if ($this->getUser()) {
            $demand->setEmail($this->getUser()->getEmail());
            $demand->setFirstName($this->getUser()->getFirstName());
            $demand->setLastName($this->getUser()->getLastName());
            $demand->setPhone($this->getUser()->getPhone());
        }

        $form = $this->createForm(EventDemandType::class, $demand);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()
            && $reCaptcha->verify($form['recaptchaToken']->getData())->isSuccess()) {
            $em->persist($demand);
            $em->flush();

            $this->addFlash('success', 'Votre demande a été transmis, nous vous répondrons dans les meilleurs délais.');

            $this->redirectToRoute('app_event_show', ['slug' => $event->getSlug()]);
        }

        return $this->render('site/event/show.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
            'settings' => $setting
        ]);
    }

    public function home(EntityManagerInterface $em, SettingsManager $manager)
    {
        $events = $em->getRepository(Event::class)->getLastEvent();

        return $this->render('site/event/partial/index.html.twig', [
            'settings' => $this->getSettings($manager),
            'events' => $events,
        ]);
    }

    /**
     * @param Breadcrumbs $breadcrumbs
     * @return Breadcrumbs
     */
    public function breadcrumb(Breadcrumbs $breadcrumbs)
    {
        $breadcrumbs->addItem('Acceuil', $this->generateUrl('app_home'))
                ->addItem('Rencontres', $this->generateUrl('app_event_index'));

        return $breadcrumbs;
    }
}

