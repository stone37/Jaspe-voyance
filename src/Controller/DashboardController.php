<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Commande;
use App\Entity\Settings;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class DashboardController extends AbstractController
{
    use ControllerTrait;

    public function index(
        EntityManagerInterface $em,
        Breadcrumbs $breadcrumbs,
        SettingsManager $manager)
    {
        $this->breadcrumb($breadcrumbs)->addItem('Tableau de bord');

        $orders = $em->getRepository(Commande::class)->getUserOrderActiveNumber($this->getUser());

        return $this->render('site/dashboard/index.html.twig', [
            'settings' => $this->getSettings($manager),
            'user'     => $this->getUsers($em, $this->getUser()->getId()),
            'orderAN' => $orders,
        ]);
    }

    public function order(
        Request $request,
        EntityManagerInterface $em,
        Breadcrumbs $breadcrumbs,
        PaginatorInterface $paginator,
        SettingsManager $sm)
    {
        $this->breadcrumb($breadcrumbs)
            ->addItem('Tableau de bord', $this->generateUrl('app_dashboard_index'))
            ->addItem('Mes commandes');

        /** @var Settings  $settings */
        $settings = $this->getSettings($sm);
        $orderAN = $em->getRepository(Commande::class)->getUserOrderActiveNumber($this->getUser());
        $orderN  = $em->getRepository(Commande::class)->getUserOrderNumber($this->getUser());

        $data = $em->getRepository(Commande::class)->getUserOrder($this->getUser());
        $orders = $paginator->paginate($data, $request->query->getInt('page', 1), 10);

        return $this->render('site/dashboard/order/index.html.twig', [
            'settings' => $settings,
            'user'     => $this->getUsers($em, $this->getUser()->getId()),
            'orderN'  => $orderN,
            'orderAN' => $orderAN,
            'orders'  => $orders,
        ]);
    }

    public function orderA(
        Request $request,
        EntityManagerInterface $em,
        Breadcrumbs $breadcrumbs,
        PaginatorInterface $paginator,
        SettingsManager $sm)
    {
        $this->breadcrumb($breadcrumbs)
        ->addItem('Tableau de bord', $this->generateUrl('app_dashboard_index'))
        ->addItem('Mes commandes validées');

        /** @var Settings  $settings */
        $settings = $this->getSettings($sm);
        $orderAN = $em->getRepository(Commande::class)->getUserOrderActiveNumber($this->getUser());
        $orderN  = $em->getRepository(Commande::class)->getUserOrderNumber($this->getUser());

        $data = $em->getRepository(Commande::class)->getUserOrderActive($this->getUser());
        $orders = $paginator->paginate($data, $request->query->getInt('page', 1), 10);

        return $this->render('site/dashboard/order/validate.html.twig', [
            'settings' => $settings,
            'user'     => $this->getUsers($em, $this->getUser()->getId()),
            'orderN'  => $orderN,
            'orderAN' => $orderAN,
            'orders'  => $orders,
        ]);
    }

}
