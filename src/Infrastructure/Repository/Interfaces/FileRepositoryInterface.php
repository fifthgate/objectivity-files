<?php

namespace Services\Files\Infrastructure\Repository\Interfaces;

use Services\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;
use Services\Content\Domain\Core\Interfaces\ContentTypeDefinitionInterface;
use Services\Files\Domain\Interfaces\ManagedFileInterface;
use Services\Content\Domain\Core\Interfaces\ContentManageableDomainEntityInterface;
use Services\Core\Infrastructure\Repository\Interfaces\DomainEntityRepositoryInterface;

interface FileRepositoryInterface extends DomainEntityRepositoryInterface
{
    public function findManyByContentTypeMachineNameAndID(string $contentTypeMachineName, int $id) : ManagedFileCollectionInterface;
  
    public function findByUrl(string $url) : ? ManagedFileInterface;

    public function associateFileWithContent(ManagedFileInterface $file, string $contentTypeMachineName, int $contentID);

    public function updateFilesForContent(ContentManageableDomainEntityInterface $contentItem);
}
