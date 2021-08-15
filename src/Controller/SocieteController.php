<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class SocieteController extends AbstractController
{
    use ControllerTrait;

    public function mention(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumb($breadcrumbs)->addItem('Mentions légales');

        return $this->render('site/societe/mention.html.twig', []);
    }

    public function confidentialite(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumb($breadcrumbs)->addItem('Confidentialité');

        return $this->render('site/societe/confidentialite.html.twig', []);
    }

    public function cgv(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumb($breadcrumbs)->addItem('Conditions générales de vente');

        return $this->render('site/societe/cgv.html.twig', []);
    }
}
