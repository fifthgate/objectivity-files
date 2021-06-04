<?php

namespace Services\Files\Infrastructure\Mapper\Interfaces;

use Services\Core\Infrastructure\Mapper\Interfaces\DomainEntityMapperInterface;
use Services\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;
use Services\Files\Domain\Interfaces\ManagedFileInterface;
use Services\Content\Domain\Core\Interfaces\ContentManageableDomainEntityInterface;

interface FileMapperInterface extends DomainEntityMapperInterface
{
    public function findManyByContentTypeMachineNameAndID(string $contentTypeMachineName, int $id) : ManagedFileCollectionInterface;

    public function getJoinTableName() : string;

    public function associateFileWithContent(ManagedFileInterface $file, string $contentTypeMachineName, int $contentID);

    public function updateFilesForContent(ContentManageableDomainEntityInterface $contentItem);
}
