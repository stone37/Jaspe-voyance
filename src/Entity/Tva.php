<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\TvaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TvaRepository::class)
 */
class Tva
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=125, nullable=true)
     */
    protected $name;

    /**
     * @var float
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $value;

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
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
