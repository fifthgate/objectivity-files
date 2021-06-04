<?php

namespace Services\Files\Service\Interfaces;

use Services\Files\Domain\Interfaces\ManagedFileInterface;
use Services\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;
use Illuminate\Http\UploadedFile;
use Services\Content\Domain\Core\Interfaces\ContentManageableDomainEntityInterface;
use Services\Core\Service\Interfaces\DomainEntityManagementServiceInterface;

interface FileServiceInterface extends DomainEntityManagementServiceInterface
{
    public function findByFileName(string $fileName) : ? ManagedFileInterface;

    public function findManyByContentTypeMachineNameAndID(string $contentTypeMachineName, int $id) : ManagedFileCollectionInterface;

    public static function sanitiseFileName(string $fileName) : string;

    public function storeFile(ManagedFileInterface $file, UploadedFile $payload) : ? ManagedFileInterface;

    public function associateFileWithContent(ManagedFileInterface $file, string $contentTypeMachineName, int $contentID);

    public function updateFilesForContent(ContentManageableDomainEntityInterface $contentItem);
}
