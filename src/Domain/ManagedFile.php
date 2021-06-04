<?php

namespace Services\Files\Domain;

use Services\Files\Domain\Interfaces\ManagedFileInterface;
use \DateTimeInterface;
use Services\Core\Domain\AbstractDomainEntity;

class ManagedFile extends AbstractDomainEntity implements ManagedFileInterface
{
    protected string $title;

    protected string $url;

    protected string $fileType;

    protected int $authorUID;

    protected int $lastEditorUID;

    protected bool $isPermanent;

    protected string $fileName;

    protected string $fileSystem;

    protected array $hrFileTypeIndices = [
        'application/pdf' => 'PDF Document',
        'audio/mpeg' => 'MP3 Audio',
        'image/jpeg' => 'JPEG Image',
        'image/png' => 'PNG Image'
    ];

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function setURL(string $url)
    {
        $this->url = $url;
    }

    public function getURL() : string
    {
        return $this->url;
    }

    public function setFileType(string $fileType)
    {
        $this->fileType = $fileType;
    }


    public function getFileType() : string
    {
        return $this->fileType;
    }

    public function setAuthorUID(int $authorUID)
    {
        $this->authorUID = $authorUID;
    }

    public function getAuthorUID() : int
    {
        return $this->authorUID;
    }

    public function setLastEditorUID(int $lastEditorUID)
    {
        $this->lastEditorUID = $lastEditorUID;
    }

    public function getLastEditorUID() : int
    {
        return $this->lastEditorUID;
    }

    public function getFileName() : string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function getHRTypeName() : string
    {
        return isset($this->hrFileTypeIndices[$this->getFileType()]) ? $this->hrFileTypeIndices[$this->getFileType()] : $this->getFileType();
    }

    public function getFileSystem() : string
    {
        return $this->fileSystem;
    }

    public function setFileSystem(string $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    public function getPublicUrl() : ? string
    {
        return str_replace('public/', '/storage/', $this->getUrl());
    }
}
