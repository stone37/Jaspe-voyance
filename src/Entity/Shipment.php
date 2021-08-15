<?php

namespace App\Entity;

use App\Entity\Traits\EnabledTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\ShipmentRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShipmentRepository::class)
 */
class Shipment
{
    use IdTrait;
    use TimestampableTrait;
    use EnabledTrait;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $shippedAt;

    /**
     * @var Commande
     *
     * @ORM\OneToOne(targetEntity=Commande::class, mappedBy="shipment")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $order;

    /**
     * @return DateTime
     */
    public function getShippedAt(): ?DateTime
    {
        return $this->shippedAt;
    }

    /**
     * @param DateTime $shippedAt
     */
    public function setShippedAt(?DateTime $shippedAt): self
    {
        $this->shippedAt = $shippedAt;

        return $this;
    }

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


