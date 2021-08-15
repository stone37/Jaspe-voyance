<?php

namespace App\Model\Admin;

class CommerceSearch
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var boolean
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var array
     */
    protected $type;

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
     * @return bool
     */
    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(?bool $enabled): void
    {
        $this->enabled = $enabled;
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
     * @return array
     */
    public function getType(): ?array
    {
        return $this->type;
    }

    /**
     * @param array  $type
     */
    public function setType(?array $type): void
    {
        $this->type = $type;
    }
}

