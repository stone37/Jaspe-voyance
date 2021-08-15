<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\NewsletterDataRepository;

/**
 * Class NewsletterData
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=NewsletterDataRepository::class)
 */
class NewsletterData
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $email;

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
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
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
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}

