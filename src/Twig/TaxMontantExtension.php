<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TaxMontantExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return array(
            new TwigFunction('taxMontant', array($this, 'calculateTax'))
        );
    }

    public function calculateTax($priceHT, $tax)
    {
        return round((($priceHT*$tax)/100),2);
    }
}
