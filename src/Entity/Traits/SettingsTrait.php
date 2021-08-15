<?php

namespace App\Entity\Traits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait SettingsTrait
 * @package App\Entity\Traits
 */
trait SettingsTrait
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @ORM\Column(name="email", type="string", nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", nullable=true)
     */
    protected $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", nullable=true)
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", nullable=true)
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_address", type="string", nullable=true)
     */
    protected $facebookAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_address", type="string", nullable=true)
     */
    protected $twitterAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram_address", type="string", nullable=true)
     */
    protected $instagramAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="youtube_address", type="string", nullable=true)
     */
    protected $youtubeAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="linkedin_address", type="string", nullable=true)
     */
    protected $linkedinAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $paypalKey;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $paypalSecret;

    /**
     * @var File
     *
     * @Assert\File(maxSize="10M")
     *
     * @Vich\UploadableField(
     *     mapping="settings_images",
     *     fileNameProperty="fileName",
     *     size="fileSize",
     *     mimeType="fileMimeType",
     *     originalName="fileOriginalName"
     * )
     */
    protected $file;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
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
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getFax(): ?string
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax(?string $fax): void
    {
        $this->fax = $fax;
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
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
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
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getFacebookAddress(): ?string
    {
        return $this->facebookAddress;
    }

    /**
     * @param string $facebookAddress
     */
    public function setFacebookAddress(?string $facebookAddress): void
    {
        $this->facebookAddress = $facebookAddress;
    }

    /**
     * @return string
     */
    public function getTwitterAddress(): ?string
    {
        return $this->twitterAddress;
    }

    /**
     * @param string $twitterAddress
     */
    public function setTwitterAddress(?string $twitterAddress): void
    {
        $this->twitterAddress = $twitterAddress;
    }

    /**
     * @return string
     */
    public function getInstagramAddress(): ?string
    {
        return $this->instagramAddress;
    }

    /**
     * @param string $instagramAddress
     */
    public function setInstagramAddress(?string $instagramAddress): void
    {
        $this->instagramAddress = $instagramAddress;
    }

    /**
     * @return string
     */
    public function getLinkedinAddress(): ?string
    {
        return $this->linkedinAddress;
    }

    /**
     * @param string $linkedinAddress
     */
    public function setLinkedinAddress(?string $linkedinAddress): void
    {
        $this->linkedinAddress = $linkedinAddress;
    }

    /**
     * @return string
     */
    public function getYoutubeAddress(): ?string
    {
        return $this->youtubeAddress;
    }

    /**
     * @param string $youtubeAddress
     */
    public function setYoutubeAddress(?string $youtubeAddress): void
    {
        $this->youtubeAddress = $youtubeAddress;
    }

    /**
     * @return string
     */
    public function getPaypalKey(): ?string
    {
        return $this->paypalKey;
    }

    /**
     * @param string $paypalKey
     */
    public function setPaypalKey(?string $paypalKey): self
    {
        $this->paypalKey = $paypalKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaypalSecret(): ?string
    {
        return $this->paypalSecret;
    }

    /**
     * @param string $paypalSecret
     */
    public function setPaypalSecret(?string $paypalSecret): self
    {
        $this->paypalSecret = $paypalSecret;

        return $this;
    }
}


