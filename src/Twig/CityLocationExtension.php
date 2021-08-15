<?php

namespace App\Twig;

use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CityLocationExtension extends AbstractExtension
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('app_city', array($this, 'get'))
        );
    }

    public function get($city)
    {
        return $this->em->getRepository(City::class)->find((int)$city);
    }
}

