<?php

namespace App\Model\Admin;

class ReviewSearch
{
    /**
     * @var boolean
     */
    protected $enabled;

    /**
     * @var boolean
     */
    protected $home;

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
     * @return bool
     */
    public function isHome(): ?bool
    {
        return $this->home;
    }

    /**
     * @param bool $home
     */
    public function setHome(?bool $home): void
    {
        $this->home = $home;
    }
}

