<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait SettingsMetaTrait
 * @package App\Entity\Traits
 */
trait SettingsMetaTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $descriptionM;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $keywords;

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
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescriptionM(): ?string
    {
        return $this->descriptionM;
    }

    /**
     * @param string $descriptionM
     */
    public function setDescriptionM(?string $descriptionM): void
    {
        $this->descriptionM = $descriptionM;
    }

    /**
     * @return string
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords
     */
    public function setKeywords(?string $keywords): void
    {
        $this->keywords = $keywords;
    }
}



