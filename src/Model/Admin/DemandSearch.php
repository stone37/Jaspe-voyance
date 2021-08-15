<?php

namespace App\Model\Admin;

class DemandSearch
{
    /**
     * @var boolean
     */
    protected $enabled;

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
}