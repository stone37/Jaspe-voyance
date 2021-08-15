<?php

namespace App\Entity;

use App\Entity\Traits\EnabledTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\DemandRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DemandRepository::class)
 */
class Demand
{
    use IdTrait;
    use TimestampableTrait;
    use EnabledTrait;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $type;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @Assert\Length(min="8", max="180", groups={"Phone"})
     * @Assert\NotBlank(groups={"Phone"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank(groups={"Email"})
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $birthDay;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Email"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $cityOfBirth;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Email"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $field;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $daysWeek;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $timeDay;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $comments;


    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
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
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
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
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

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
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return array
     */
    public function getDaysWeek(): ?array
    {
        return $this->daysWeek;
    }

    /**
     * @param string $daysWeek
     */
    public function setDaysWeek(?array $daysWeek): self
    {
        $this->daysWeek = $daysWeek;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimeDay(): ?array
    {
        return $this->timeDay;
    }

    /**
     * @param array $timeDay
     */
    public function setTimeDay(array $timeDay): self
    {
        $this->timeDay = $timeDay;

        return $this;
    }

    /**
     * @return string
     */
    public function getComments(): ?string
    {
        return $this->comments;
    }

    /**
     * @param string $comments
     */
    public function setComments(string $comments): self
    {
        $this->comments = $comments;

        return $this;
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
    public function setBirthDay(?DateTime $birthDay): self
    {
        $this->birthDay = $birthDay;

        return $this;
    }

    /**
     * @return string
     */
    public function getCityOfBirth(): ?string
    {
        return $this->cityOfBirth;
    }

    /**
     * @param string $cityOfBirth
     */
    public function setCityOfBirth(?string $cityOfBirth): self
    {
        $this->cityOfBirth = $cityOfBirth;

        return $this;
    }

    /**
     * @return string
     */
    public function getField(): ?string
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField(?string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function name()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function disponible()
    {
        return 'Les ' . $this->daysWeek . ' ' . $this->timeDay;
    }
}
