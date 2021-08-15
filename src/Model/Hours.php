<?php

namespace App\Model;

use DateTime;

class Hours
{
    /**
     * @var string
     */
    protected $days;

    /**
     * @var DateTime
     */
    protected $openHour;

    /**
     * @var DateTime
     */
    protected $closeHour;

    /**
     * @var bool
     */
    protected $enabled;

    public function __construct()
    {
        $this->enabled = true;
    }

    /**
     * @return string
     */
    public function getDays(): string
    {
        return $this->days;
    }

    /**
     * @param string $days
     */
    public function setDays(string $days): self
    {
        $this->days = $days;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getOpenHour(): ?DateTime
    {
        return $this->openHour;
    }

    /**
     * @param DateTime $openHour
     */
    public function setOpenHour(?DateTime $openHour): self
    {
        $this->openHour = $openHour;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCloseHour(): ?DateTime
    {
        return $this->closeHour;
    }

    /**
     * @param DateTime $closeHour
     */
    public function setCloseHour(?DateTime $closeHour): self
    {
        $this->closeHour = $closeHour;

        return $this;
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
    public function setEnabled(?bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

}
