<?php

namespace Fifthgate\Objectivity\Files\Domain;

use Fifthgate\Objectivity\Files\Domain\Interfaces\ManagedFileInterface;
use \DateTimeInterface;
use Fifthgate\Objectivity\Core\Domain\AbstractDomainEntity;

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

    protected const HR_FILE_TYPE_INDICES = [
        'application/pdf' => 'PDF Document',
        'audio/mpeg' => 'MP3 Audio',
        'image/jpeg' => 'JPEG Image',
        'image/png' => 'PNG Image'
    ];

    public function __construct(
        string $fileName,
        string $fileSystem
    ) {
        $this->fileName = $fileName;
        $this->fileSystem = $fileSystem;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function setURL(string $url): void
    {
        $this->url = $url;
    }

    public function getURL() : string
    {
        return $this->url;
    }

    public function setFileType(string $fileType): void
    {
        $this->fileType = $fileType;
    }


    public function getFileType() : string
    {
        return $this->fileType;
    }

    public function setAuthorUID(int $authorUID): void
    {
        $this->authorUID = $authorUID;
    }

    public function getAuthorUID() : int
    {
        return $this->authorUID;
    }

    public function setLastEditorUID(int $lastEditorUID): void
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

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getHRTypeName() : string
    {
        return self::HR_FILE_TYPE_INDICES[$this->getFileType()] ?? $this->getFileType();
    }

    public function getFileSystem() : string
    {
        return $this->fileSystem;
    }

    public function setFileSystem(string $fileSystem): void
    {
        $this->fileSystem = $fileSystem;
    }

    public function getPublicUrl() : ? string
    {
        return str_replace('public/', '/storage/', $this->getUrl());
    }
}
