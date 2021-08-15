<?php

namespace App\Twig;

use App\Entity\District;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DistrictLocationExtension extends AbstractExtension
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('app_district', array($this, 'get'))
        );
    }

    public function get($district)
    {
        return $this->em->getRepository(District::class)->find((int)$district);
    }
}

