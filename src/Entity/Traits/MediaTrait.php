<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait MediaTrait
 * @package App\Entity\Traits
 */
trait MediaTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", nullable=true)
     */
    protected $fileName;

    /**
     * @var integer
     *
     * @ORM\Column(name="file_size", type="integer", nullable=true)
     */
    protected $fileSize;

    /**
     * @var string
     *
     * @ORM\Column(name="file_mime_type", type="string", nullable=true)
     */
    protected $fileMimeType;

    /**
     * @var string
     *
     * @ORM\Column(name="file_original_name", type="string", nullable=true)
     */
    protected $fileOriginalName;

    /**
     * @return string
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(?string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return int
     */
    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    /**
     * @param int $fileSize
     */
    public function setFileSize(?int $fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return string
     */
    public function getFileMimeType(): ?string
    {
        return $this->fileMimeType;
    }

    /**
     * @param string $fileMimeType
     */
    public function setFileMimeType(?string $fileMimeType)
    {
        $this->fileMimeType = $fileMimeType;
    }

    /**
     * @return string
     */
    public function getFileOriginalName(): ?string
    {
        return $this->fileOriginalName;
    }

    /**
     * @param string $fileOriginalName
     */
    public function setFileOriginalName(?string $fileOriginalName)
    {
        $this->fileOriginalName = $fileOriginalName;
    }
}
