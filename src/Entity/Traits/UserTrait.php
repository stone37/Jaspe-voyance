<?php

namespace App\Entity\Traits;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait UserTrait
 * @package App\Entity\Traits
 */
trait UserTrait
{
    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Entrez un prénom s'il vous plait.",
     *     groups={"AppRegistration", "AppProfile"}
     * )
     *
     * @Assert\Length(
     *     min="2",
     *     max="180",
     *     minMessage="Le prénom est trop court.",
     *     maxMessage="Le prénom est trop long.",
     *     groups={"AppRegistration", "AppProfile"}
     * )
     *
     * @ORM\Column(name="first_name", type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Entrez un prénom s'il vous plait.",
     *     groups={"AppRegistration", "AppProfile"}
     * )
     *
     * @Assert\Length(
     *     min="2",
     *     max="180",
     *     minMessage="Le prénom est trop court.",
     *     maxMessage="Le prénom est trop long.",
     *     groups={"AppRegistration", "AppProfile"}
     * )
     *
     * @ORM\Column(name="last_name", type="string", nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min="8",
     *     max="180",
     *     minMessage="Le numéro de téléphone est trop court.",
     *     maxMessage="Le numéro de téléphone est trop long.",
     *     groups={"AppRegistration", "Profile"}
     * )
     *
     * @ORM\Column(name="phone", type="string", nullable=true)
     */
    protected $phone;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="birth_day", type="date", nullable=true)
     */
    protected $birthDay;

    /**
     * @var File
     *
     * @Assert\File(maxSize="10M")
     *
     * @Vich\UploadableField(
     *     mapping="user_image",
     *     fileNameProperty="fileName",
     *     size="fileSize",
     *     mimeType="fileMimeType",
     *     originalName="fileOriginalName"
     * )
     */
    protected $file;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Entrez un code postal s''il vous plait.",
     *     groups={"AppRegistration", "Profile"}
     * )
     *
     * @ORM\Column(name="code", type="string", nullable=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Entrez une civilité s''il vous plait.",
     *     groups={"AppRegistration", "Profile"}
     * )
     *
     * @ORM\Column(name="civilite", type="string", nullable=true)
     */
    protected $civilite;

    /**
     * @var boolean
     *
     * @Assert\NotBlank(
     *     groups={"AppRegistration"}
     * )
     *
     * @ORM\Column(name="accept_condition", type="boolean", nullable=true)
     */
    protected $acceptCondition;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Cart"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $address;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Cart"})
     *
     * @ORM\Column(type="string", length=225, nullable=true)
     */
    protected $pays;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Cart"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $ville;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=true)
     */
    protected $deleted;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $subscribedToNewsletter = false;

    public function __constructUser()
    {
        $this->acceptCondition = false;
        $this->createdAt = new DateTime();
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return DateTime
     */
    public function getBirthDay(): ?DateTime
    {
        return $this->birthDay;
    }

    /**
     * @param DateTime $birthDay
     */
    public function setBirthDay(?DateTime $birthDay): void
    {
        $this->birthDay = $birthDay;
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
     * @return DateTime
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    /**
     * @param string $civilite
     */
    public function setCivilite(string $civilite): void
    {
        $this->civilite = $civilite;
    }

    /**
     * @return bool
     */
    public function isAcceptCondition(): ?bool
    {
        return $this->acceptCondition;
    }

    /**
     * @param bool $acceptCondition
     */
    public function setAcceptCondition(?bool $acceptCondition): void
    {
        $this->acceptCondition = $acceptCondition;
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
    public function getPays(): ?string
    {
        return $this->pays;
    }

    /**
     * @param string $pays
     */
    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * @return string
     */
    public function getVille(): ?string
    {
        return $this->ville;
    }

    /**
     * @param string $ville
     */
    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @param DateTime $deletedAt
     */
    public function setDeletedAt(?DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSubscribedToNewsletter(): ?bool
    {
        return $this->subscribedToNewsletter;
    }


    /**
     * @param bool $subscribedToNewsletter
     */
    public function setSubscribedToNewsletter(?bool $subscribedToNewsletter): self
    {
        $this->subscribedToNewsletter = $subscribedToNewsletter;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getFirstName() . ' ' . $this->getLastName();
    }
}


