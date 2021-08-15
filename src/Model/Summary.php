<?php

namespace App\Model;

use App\Entity\Commande;

final class Summary
{
    /**
     * @var Commande
     */
    private $order;

    /**
     * Summary constructor.
     *
     * @param Commande $order
     */
    public function __construct(Commande $order)
    {
        $this->order = $order;
    }

    /**
     * Return
     *
     * @return float
     */
    public function getPriceTotal(): float
    {
        return $this->order->getPriceTotal();
    }
}

