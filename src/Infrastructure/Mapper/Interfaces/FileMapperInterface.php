<?php

namespace Fifthgate\Objectivity\Files\Infrastructure\Mapper\Interfaces;

use Fifthgate\Objectivity\Repositories\Infrastructure\Mapper\Interfaces\DomainEntityMapperInterface;
use Fifthgate\Objectivity\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;
use Fifthgate\Objectivity\Files\Domain\Interfaces\ManagedFileInterface;
use Fifthgate\Objectivity\Content\Domain\Core\Interfaces\ContentManageableDomainEntityInterface;

interface FileMapperInterface extends DomainEntityMapperInterface
{
    public function findManyByContentTypeMachineNameAndID(string $contentTypeMachineName, int $id) : ManagedFileCollectionInterface;

    public function getJoinTableName() : string;

    public function associateFileWithContent(ManagedFileInterface $file, string $contentTypeMachineName, int $contentID);

    public function updateFilesForContent(ContentManageableDomainEntityInterface $contentItem);
}
