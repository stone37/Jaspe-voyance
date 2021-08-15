<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TaxExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return array(
            new TwigFunction('tax', array($this, 'calculateTax'))
        );
    }

    public function calculateTax($priceHT, $tax)
    {
        return round((($priceHT*$tax)/100)+$priceHT,2);
    }
}
