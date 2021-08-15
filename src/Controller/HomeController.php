<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    use ControllerTrait;

    public function index()
    {


        return $this->render('site/home/index.html.twig', []);
    }
}
