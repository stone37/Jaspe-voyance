<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait SettingsTrait
 * @package App\Entity\Traits
 */
trait SettingsModuleTrait
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="active_voyance", type="boolean", nullable=true)
     */
    protected $activeVoyance;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $activePhoneV;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $vPhoneTime;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $vPhonePrice;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $activeMailV;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $vMailPrice;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $vMailDelai;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $activeCabinetV;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $vCabinetTime;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $vCabinetPrice;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active_soins", type="boolean", nullable=true)
     */
    protected $activeSoins;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $soinsTime;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $soinsPrice;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active_temoignages", type="boolean", nullable=true)
     */
    protected $activeTemoignages;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active_shop", type="boolean", nullable=true)
     */
    protected $activeShop;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active_blog", type="boolean", nullable=true)
     */
    protected $activeBlog;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $nbreArticlePage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active_rencontre", type="boolean", nullable=true)
     */
    protected $activeRencontre;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $vMailRemise;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $vPhoneRemise;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $vCabinetRemise;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $soinsRemise;

    /**
     * @return bool
     */
    public function isActiveVoyance(): ?bool
    {
        return $this->activeVoyance;
    }

    /**
     * @param bool $activeVoyance
     */
    public function setActiveVoyance(?bool $activeVoyance): void
    {
        $this->activeVoyance = $activeVoyance;
    }

    /**
     * @return bool
     */
    public function isActiveSoins(): ?bool
    {
        return $this->activeSoins;
    }

    /**
     * @param bool $activeSoins
     */
    public function setActiveSoins(?bool $activeSoins): void
    {
        $this->activeSoins = $activeSoins;
    }

    /**
     * @return bool
     */
    public function isActiveTemoignages(): ?bool
    {
        return $this->activeTemoignages;
    }

    /**
     * @param bool $activeTemoignages
     */
    public function setActiveTemoignages(bool $activeTemoignages): void
    {
        $this->activeTemoignages = $activeTemoignages;
    }

    /**
     * @return bool
     */
    public function isActiveShop(): ?bool
    {
        return $this->activeShop;
    }

    /**
     * @param bool $activeShop
     */
    public function setActiveShop(bool $activeShop): void
    {
        $this->activeShop = $activeShop;
    }

    /**
     * @return bool
     */
    public function isActiveBlog(): ?bool
    {
        return $this->activeBlog;
    }

    /**
     * @param bool $activeBlog
     */
    public function setActiveBlog(bool $activeBlog): void
    {
        $this->activeBlog = $activeBlog;
    }

    /**
     * @return bool
     */
    public function isActiveRencontre(): ?bool
    {
        return $this->activeRencontre;
    }

    /**
     * @param bool $activeRencontre
     */
    public function setActiveRencontre(?bool $activeRencontre): self
    {
        $this->activeRencontre = $activeRencontre;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActiveCabinetV(): ?bool
    {
        return $this->activeCabinetV;
    }

    /**
     * @param bool $activeCabinetV
     */
    public function setActiveCabinetV(?bool $activeCabinetV): self
    {
        $this->activeCabinetV = $activeCabinetV;

        return $this;
    }

    /**
     * @return int
     */
    public function getVCabinetTime(): ?int
    {
        return $this->vCabinetTime;
    }

    /**
     * @param int $vCabinetTime
     */
    public function setVCabinetTime(int $vCabinetTime): self
    {
        $this->vCabinetTime = $vCabinetTime;

        return $this;
    }

    /**
     * @return float
     */
    public function getVCabinetPrice(): ?float
    {
        return $this->vCabinetPrice;
    }

    /**
     * @param float $vCabinetPrice
     */
    public function setVCabinetPrice(?float $vCabinetPrice): self
    {
        $this->vCabinetPrice = $vCabinetPrice;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActiveMailV(): ?bool
    {
        return $this->activeMailV;
    }

    /**
     * @param bool $activeMailV
     */
    public function setActiveMailV(?bool $activeMailV): self
    {
        $this->activeMailV = $activeMailV;

        return $this;
    }

    /**
     * @return float
     */
    public function getVMailPrice(): ?float
    {
        return $this->vMailPrice;
    }

    /**
     * @param float $vMailPrice
     */
    public function setVMailPrice(?float $vMailPrice): self
    {
        $this->vMailPrice = $vMailPrice;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActivePhoneV(): ?bool
    {
        return $this->activePhoneV;
    }

    /**
     * @param bool $activePhoneV
     */
    public function setActivePhoneV(bool $activePhoneV): self
    {
        $this->activePhoneV = $activePhoneV;

        return $this;
    }

    /**
     * @return int
     */
    public function getVPhoneTime(): ?int
    {
        return $this->vPhoneTime;
    }

    /**
     * @param int $vPhoneTime
     */
    public function setVPhoneTime(int $vPhoneTime): self
    {
        $this->vPhoneTime = $vPhoneTime;

        return $this;
    }

    /**
     * @return float
     */
    public function getVPhonePrice(): ?float
    {
        return $this->vPhonePrice;
    }

    /**
     * @param float $vPhonePrice
     */
    public function setVPhonePrice(?float $vPhonePrice): self
    {
        $this->vPhonePrice = $vPhonePrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getSoinsPrice(): ?float
    {
        return $this->soinsPrice;
    }

    /**
     * @param float $soinsPrice
     */
    public function setSoinsPrice(?float $soinsPrice): self
    {
        $this->soinsPrice = $soinsPrice;

        return $this;
    }

    /**
     * @return int
     */
    public function getSoinsTime(): ?int
    {
        return $this->soinsTime;
    }

    /**
     * @param int $soinsTime
     */
    public function setSoinsTime(?int $soinsTime): self
    {
        $this->soinsTime = $soinsTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getNbreArticlePage(): ?int
    {
        return $this->nbreArticlePage;
    }

    /**
     * @param int $nbreArticlePage
     */
    public function setNbreArticlePage(?int $nbreArticlePage): self
    {
        $this->nbreArticlePage = $nbreArticlePage;

        return $this;
    }

    /**
     * @return int
     */
    public function getVMailDelai(): ?int
    {
        return $this->vMailDelai;
    }

    /**
     * @param int $vMailDelai
     */
    public function setVMailDelai(?int $vMailDelai): self
    {
        $this->vMailDelai = $vMailDelai;

        return $this;
    }

    /**
     * @return float
     */
    public function getVMailRemise(): ?float
    {
        return $this->vMailRemise;
    }

    /**
     * @param float $vMailRemise
     */
    public function setVMailRemise(?float $vMailRemise): void
    {
        $this->vMailRemise = $vMailRemise;
    }

    /**
     * @return float
     */
    public function getVPhoneRemise(): ?float
    {
        return $this->vPhoneRemise;
    }

    /**
     * @param float $vPhoneRemise
     */
    public function setVPhoneRemise(?float $vPhoneRemise): void
    {
        $this->vPhoneRemise = $vPhoneRemise;
    }

    /**
     * @return float
     */
    public function getVCabinetRemise(): ?float
    {
        return $this->vCabinetRemise;
    }

    /**
     * @param float $vCabinetRemise
     */
    public function setVCabinetRemise(?float $vCabinetRemise): void
    {
        $this->vCabinetRemise = $vCabinetRemise;
    }

    /**
     * @return float
     */
    public function getSoinsRemise(): ?float
    {
        return $this->soinsRemise;
    }

    /**
     * @param float $soinsRemise
     */
    public function setSoinsRemise(?float $soinsRemise): void
    {
        $this->soinsRemise = $soinsRemise;
    }
}


