<?php

declare(strict_types=1);

namespace Fifthgate\Objectivity\Files\Domain\Interfaces;

use \DateTimeInterface;
use Fifthgate\Objectivity\Core\Domain\Interfaces\TimestampingDomainEntityInterface;
use Fifthgate\Objectivity\Core\Domain\Interfaces\DomainEntityInterface;

interface ManagedFileInterface extends TimestampingDomainEntityInterface, DomainEntityInterface
{
    public function setTitle(string $title): void;

    public function getTitle() : string;

    public function setURL(string $url): void;

    public function getURL() : string;

    public function setFileType(string $fileType): void;

    public function getFileType() : string;

    public function setAuthorUID(int $authorUID): void;

    public function getAuthorUID() : int;

    public function setLastEditorUID(int $lastEditorUID): void;

    public function getLastEditorUID() : int;

    public function getPublicUrl() : ? string;

    public function getFileName() : string;

    public function setFileName(string $fileName);

    public function getHRTypeName() : string;

    public function getFileSystem() : string;

    public function setFileSystem(string $fileSystem): void;
}
