<?php

namespace App\Factory;

use App\Entity\Commande;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\ShipmentMethod;
use App\Model\Summary;
use App\Storage\OrderSessionStorage;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class OrderFactory
{
    /**
     * @var OrderSessionStorage
     */
    private $storage;

    /**
     * @var Commande
     */
    private $order;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(OrderSessionStorage $storage, EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->storage = $storage;
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->order = $this->getCurrent();
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrent(): Commande
    {
        $order = $this->storage->getOrderById();

        if ($order !== null) {
            return $order;
        }

        return new Commande();
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(Product $product, int $quantity): void
    {
        $orderBeforeId = $this->order->getId();

        if (!$this->containsProduct($product)) {
            $orderItem = new OrderItem();
            $orderItem->setOrder($this->order);
            $orderItem->setProduct($product);
            $orderItem->setQuantity($quantity);

            $this->order->addItem($orderItem);
        } else {
            $key = $this->indexOfProduct($product);
            $item = $this->order->getItems()->get($key);
            $quantity = $this->order->getItems()->get($key)->getQuantity() + 1;
            $this->setItemQuantity($item, $quantity);
        }

        $this->em->persist($this->order);

        // Run events
        if ($orderBeforeId === null) {
            $event = new GenericEvent($this->order);
            $this->eventDispatcher->dispatch(Events::ORDER_CREATED, $event);
        } else {
            $event = new GenericEvent($this->order);
            $this->eventDispatcher->dispatch(Events::ORDER_UPDATED, $event);
        }

        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function containsProduct(Product $product): bool
    {
        foreach ($this->order->getItems() as $item) {

            if ($item->getProduct() === $product) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function indexOfProduct(Product $product): ?int
    {
        foreach ($this->order->getItems() AS $key => $item) {
            if ($item->getProduct() === $product) {
                return $key;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setItemQuantity(OrderItem $item, int $quantity): void
    {
        if ($this->order && $this->order->getItems()->contains($item)) {
            $key = $this->order->getItems()->indexOf($item);

            $item->setQuantity($quantity);

            $this->order->getItems()->set($key, $item);

            // Run events
            $event = new GenericEvent($this->order);
            $this->eventDispatcher->dispatch(Events::ORDER_UPDATED, $event);

            $this->em->persist($this->order);
            $this->em->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem(OrderItem $item): void
    {
        if ($this->order && $this->order->getItems()->contains($item)) {
            $this->order->removeItem($item);

            // Run events
            $event = new GenericEvent($this->order);
            $this->eventDispatcher->dispatch(Events::ORDER_UPDATED, $event);

            $this->em->persist($this->order);
            $this->em->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function items(): Collection
    {
        return $this->order->getItems();
    }

    /*public function setPayment(Payment $payment): void
    {
        if ($this->order) {

            $this->order->setPayment($payment);

            // Run events
            $event = new GenericEvent($this->order);
            $this->eventDispatcher->dispatch(Events::ORDER_UPDATED, $event);

            $this->entityManager->persist($this->order);
            $this->entityManager->flush();
        }
    }*/

    /**
     * {@inheritdoc}
     */
    public function setShipment(ShipmentMethod $shipment): void
    {
        if ($this->order) {

            $this->order->setShipmentMethod($shipment);

            // Run events
            $event = new GenericEvent($this->order);
            $this->eventDispatcher->dispatch(Events::ORDER_UPDATED, $event);

            $this->em->persist($this->order);
            $this->em->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->em->remove($this->order);
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return !$this->order->getItems();
    }

    /**
     * {@inheritdoc}
     */
    public function summary(): Summary
    {
        return new Summary($this->order);
    }
}
