<?php

namespace Services\Files\Domain\Interfaces;

use \DateTimeInterface;
use Services\Core\Domain\Interfaces\TimestampingDomainEntityInterface;
use Services\Core\Domain\Interfaces\DomainEntityInterface;

interface ManagedFileInterface extends TimestampingDomainEntityInterface, DomainEntityInterface
{
    public function setID(int $id);

    public function getID() : ?int;

    public function setTitle(string $title);

    public function getTitle() : string;

    public function setURL(string $url);

    public function getURL() : string;

    public function setFileType(string $fileType);

    public function getFileType() : string;

    public function setCreatedAt(DateTimeInterface $createdAt);

    public function getCreatedAt() : DateTimeInterface;

    public function setUpdatedAt(DateTimeInterface $updatedAt);

    public function getUpdatedAt() : DateTimeInterface;

    public function setAuthorUID(int $authorUID);

    public function getAuthorUID() : int;

    public function setLastEditorUID(int $lastEditorUID);

    public function getLastEditorUID() : int;

    public function getPublicUrl() : ? string;

    public function getFileName() : string;

    public function setFileName(string $fileName);

    public function getHRTypeName() : string;

    public function getFileSystem() : string;

    public function setFileSystem(string $fileSystem);
}
