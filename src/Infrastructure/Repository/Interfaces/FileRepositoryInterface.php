<?php

namespace Fifthgate\Objectivity\Files\Infrastructure\Repository\Interfaces;

use Fifthgate\Objectivity\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;
use Fifthgate\Objectivity\Content\Domain\Core\Interfaces\ContentTypeDefinitionInterface;
use Fifthgate\Objectivity\Files\Domain\Interfaces\ManagedFileInterface;
use Fifthgate\Objectivity\Content\Domain\Core\Interfaces\ContentManageableDomainEntityInterface;
use Fifthgate\Objectivity\Repositories\Infrastructure\Repository\Interfaces\DomainEntityRepositoryInterface;

interface FileRepositoryInterface extends DomainEntityRepositoryInterface
{
    public function findManyByContentTypeMachineNameAndID(string $contentTypeMachineName, int $id) : ManagedFileCollectionInterface;
  
    public function findByUrl(string $url) : ? ManagedFileInterface;

    public function associateFileWithContent(ManagedFileInterface $file, string $contentTypeMachineName, int $contentID);

    public function updateFilesForContent(ContentManageableDomainEntityInterface $contentItem);
}
