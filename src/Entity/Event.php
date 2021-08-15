<?php

namespace App\Entity;

use App\Entity\Traits\EnabledTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\MediaTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\EventRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 *
 * @Vich\Uploadable
 */
class Event
{
    use IdTrait;
    use MediaTrait;
    use EnabledTrait;
    use TimestampableTrait;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"}, unique=true)
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $slug;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $partialDescription;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var DateTime
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $eventDateAt;

    /**
     * @var DateTime
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="time", nullable=true)
     */
    protected $eventHour;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @var float
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $price;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address;

    /**
     * @var EventDemand
     *
     * @ORM\OneToMany(targetEntity=EventDemand::class, mappedBy="event")
     */
    protected $demands;

    /**
     * @var File
     *
     * @Assert\File(maxSize="10M")
     *
     * @Vich\UploadableField(
     *     mapping="event_images",
     *     fileNameProperty="fileName",
     *     size="fileSize",
     *     mimeType="fileMimeType",
     *     originalName="fileOriginalName"
     * )
     */
    protected $file;

    public function __construct()
    {
        $this->demands = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEventDateAt(): ?DateTime
    {
        return $this->eventDateAt;
    }

    /**
     * @param DateTime $eventDateAt
     */
    public function setEventDateAt(?DateTime $eventDateAt): self
    {
        $this->eventDateAt = $eventDateAt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEventHour(): ?DateTime
    {
        return $this->eventHour;
    }

    /**
     * @param DateTime $eventHour
     */
    public function setEventHour(?DateTime $eventHour): self
    {
        $this->eventHour = $eventHour;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

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

    /**
     * @return string
     */
    public function getPartialDescription(): ?string
    {
        return $this->partialDescription;
    }

    /**
     * @param string $partialDescription
     */
    public function setPartialDescription(?string $partialDescription): self
    {
        $this->partialDescription = $partialDescription;

        return $this;
    }

    /**
     * @return File
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File $image
     */
    public function setFile(?File $image = null): void
    {
        $this->file = $image;

        if (null !== $image) {
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    /**
     * @return Collection|EventDemand[]
     */
    public function getDemands(): Collection
    {
        return $this->demands;
    }

    public function addDemand(EventDemand $demand): self
    {
        if (!$this->demands->contains($demand)) {
            $this->demands[] = $demand;
        }

        return $this;
    }

    public function removeDemand(EventDemand $demand): self
    {
        if ($this->demands->contains($demand)) {
            $this->demands->removeElement($demand);
        }

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
