<?php

namespace Services\Files\Infrastructure\Repository;

use Services\Files\Infrastructure\Repository\Interfaces\FileRepositoryInterface;
use Services\Files\Infrastructure\Mapper\Interfaces\FileMapperInterface;
use Services\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;
use Services\Content\Domain\Core\Interfaces\ContentTypeDefinitionInterface;
use Services\Files\Domain\Interfaces\ManagedFileInterface;
use Services\Content\Domain\Core\Interfaces\ContentManageableDomainEntityInterface;
use Services\Core\Infrastructure\Repository\AbstractDomainEntityRepository;

class FileRepository extends AbstractDomainEntityRepository implements FileRepositoryInterface
{
    protected $mapper;

    public function __construct(FileMapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    public function findManyByContentTypeMachineNameAndID(string $contentTypeMachineName, int $id) : ManagedFileCollectionInterface
    {
        return $this->mapper->findManyByContentTypeMachineNameAndID($contentTypeMachineName, $id);
    }

    public function findByUrl(string $url) : ? ManagedFileInterface
    {
        return $this->mapper->queryOne(['url' => $url]);
    }


    public function associateFileWithContent(ManagedFileInterface $file, string $contentTypeMachineName, int $contentID)
    {
        $this->mapper->associateFileWithContent($file, $contentTypeMachineName, $contentID);
    }

    public function updateFilesForContent(ContentManageableDomainEntityInterface $contentItem)
    {
        return $this->mapper->updateFilesForContent($contentItem);
    }

    public function findByFileName(string $fileName) : ? ManagedFileInterface
    {
        return $this->mapper->queryOne(['filename' => $fileName]);
    }
}
