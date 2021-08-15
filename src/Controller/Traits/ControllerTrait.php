<?php

namespace App\Controller\Traits;

use App\Entity\User;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * Trait ControllerTrait
 * @package App\Controller\Traits
 */
Trait ControllerTrait
{
    public function getUsers(EntityManagerInterface $manager, $id)
    {
        return $manager->getRepository(User::class)->getUser($id);
    }

    public function getSettings(SettingsManager $manager)
    {
        return $manager->get();
    }

    public function breadcrumb(Breadcrumbs $breadcrumbs)
    {
        $breadcrumbs->addItem('Acceuil', $this->generateUrl('app_home'));

        return $breadcrumbs;
    }
}

