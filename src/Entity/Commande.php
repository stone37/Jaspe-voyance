<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * @var OrderItem
     *
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="order", cascade={"ALL"})
     */
    protected $items;

    /**
     * @var Shipment
     *
     * @ORM\OneToOne(targetEntity=Shipment::class, inversedBy="order", cascade={"ALL"})
     */
    protected $shipment;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $validate;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $reference;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $priceTotal;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $priceTotalTva;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $totalTva;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $shipmentPrice;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
        }

        return $this;
    }

    public function removeItem(OrderItem $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
        }

        return $this;
    }

    /**
     * @return Shipment
     */
    public function getShipment(): ?Shipment
    {
        return $this->shipment;
    }

    /**
     * @param Shipment $shipment
     */
    public function setShipment(Shipment $shipment): self
    {
        $this->shipment = $shipment;
        $shipment->setOrder($this);

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        $user->addOrder($this);

        return $this;
    }

    /**
     * @return bool
     */
    public function isValidate(): ?bool
    {
        return $this->validate;
    }

    /**
     * @param bool $validate
     */
    public function setValidate(?bool $validate): self
    {
        $this->validate = $validate;

        return $this;
    }

    /**
     * @return int
     */
    public function getReference(): ?int
    {
        return $this->reference;
    }


    /**
     * @param int $reference
     */
    public function setReference(?int $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return float
     */
    public function getPriceTotal(): ?float
    {
        return $this->priceTotal;
    }

    /**
     * @param float $priceTotal
     */
    public function setPriceTotal(?float $priceTotal): self
    {
        $this->priceTotal = $priceTotal;

        return $this;
    }

    /**
     * @return float
     */
    public function getPriceTotalTva(): ?float
    {
        return $this->priceTotalTva;
    }

    /**
     * @param float $priceTotalTva
     */
    public function setPriceTotalTva(?float $priceTotalTva): self
    {
        $this->priceTotalTva = $priceTotalTva;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalTva(): ?float
    {
        return $this->totalTva;
    }

    /**
     * @param float $totalTva
     */
    public function setTotalTva(?float $totalTva): self
    {
        $this->totalTva = $totalTva;

        return $this;
    }

    /**
     * @return float
     */
    public function getShipmentPrice(): ?float
    {
        return $this->shipmentPrice;
    }

    /**
     * @param float $shipmentPrice
     */
    public function setShipmentPrice(?float $shipmentPrice): self
    {
        $this->shipmentPrice = $shipmentPrice;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getClient()
    {
        return $this->getUser()->getName() . '\n' . $this->getUser()->getEmail();
    }

    public function __toString()
    {
        return (string)$this->reference;
    }
}
