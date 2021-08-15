<?php

namespace App\Entity;

use App\Entity\Traits\EnabledTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PaymentRepository;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
{
    use IdTrait;
    use TimestampableTrait;
    use EnabledTrait;

    /**
     * @var Commande
     *
     * @ORM\OneToOne(targetEntity=Commande::class, mappedBy="shipment")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $order;

    /**
     * @return Commande
     */
    public function getOrder(): ?Commande
    {
        return $this->order;
    }

    /**
     * @param Commande $order
     */
    public function setOrder(Commande $order): self
    {
        $this->order = $order;

        return $this;
    }
}


