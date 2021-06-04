<?php

namespace Fifthgate\Objectivity\Files\Infrastructure\Repository;

use Fifthgate\Objectivity\Files\Infrastructure\Repository\Interfaces\FileRepositoryInterface;
use Fifthgate\Objectivity\Files\Infrastructure\Mapper\Interfaces\FileMapperInterface;
use Fifthgate\Objectivity\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;
use Fifthgate\Objectivity\Content\Domain\Core\Interfaces\ContentTypeDefinitionInterface;
use Fifthgate\Objectivity\Files\Domain\Interfaces\ManagedFileInterface;
use Fifthgate\Objectivity\Content\Domain\Core\Interfaces\ContentManageableDomainEntityInterface;
use Fifthgate\Objectivity\Repositories\Infrastructure\Repository\AbstractDomainEntityRepository;

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
