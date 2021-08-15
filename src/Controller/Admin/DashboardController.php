<?php

namespace App\Controller\Admin;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Commande;
use App\Entity\Demand;
use App\Entity\Event;
use App\Entity\Review;
use App\Entity\User;
use App\Service\SettingsManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractController
{
    use ControllerTrait;

    public function index(EntityManagerInterface $em, SettingsManager $manager)
    {
        $meets = $em->getRepository(Demand::class)->getNumber();
        $events = $em->getRepository(Event::class)->getNumber();
        $orders = $em->getRepository(Commande::class)->getNumber();
        $users   = $em->getRepository(User::class)->getUserNumber();
        $reviews = $em->getRepository(Review::class)->getNumber();
        $admins  = $em->getRepository(User::class)->getAdminNumber();
        $lastClients  = $em->getRepository(User::class)->getLastClients();
        $lastOrders  = $em->getRepository(Commande::class)->getLastOrders();

        return $this->render('admin/dashboard/index.html.twig', [
            'settings' => $this->getSettings($manager),
            'meets' => $meets,
            'orders' => $orders,
            'events' => $events,
            'users' => $users,
            'reviews' => $reviews,
            'admins' => $admins,
            'lastClients' => $lastClients,
            'lastOrders' => $lastOrders,
        ]);
    }
}

