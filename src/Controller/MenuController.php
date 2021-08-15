<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    use ControllerTrait;

    public function dropdownMenu(EntityManagerInterface $em, SettingsManager $sm)
    {
        if ($this->getUser())
            $user = $this->getUsers($em, $this->getUser()->getId());
        else
            $user = null;

        return $this->render('site/menu/dropdown.html.twig', [
            'settings' => $this->getSettings($sm),
            'user' => $user,
        ]);
    }
}

