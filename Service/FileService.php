<?php

namespace Services\Files\Service;

use Services\Files\Service\Interfaces\FileServiceInterface;
use Services\Files\Domain\Interfaces\ManagedFileInterface;
use Services\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;
use Services\Files\Infrastructure\Repository\Interfaces\FileRepositoryInterface;
use Services\Content\Domain\Core\Interfaces\ContentTypeDefinitionInterface;
use Illuminate\Http\UploadedFile;
use Storage;
use Services\Content\Domain\Core\Interfaces\ContentManageableDomainEntityInterface;
use Carbon\Carbon;
use Services\Core\Service\AbstractRepositoryDrivenDomainEntityManagementService;
use Services\Core\Domain\Interfaces\DomainEntityInterface;

class FileService extends AbstractRepositoryDrivenDomainEntityManagementService implements FileServiceInterface
{
    public function __construct(
        FileRepositoryInterface $fileRepository
    ) {
        parent::__construct($fileRepository);
    }

    public function getEntityInfo() : array
    {
        return [
            'managedFile' => [
                'name' => 'Managed File',
                'softDeletes' => false,
                'publishes' => false,
                'timestamps' => true
            ]
        ];
    }

    public function findManyByContentTypeMachineNameAndID(string $contentTypeMachineName, int $id) : ManagedFileCollectionInterface
    {
        return $this->repository->findManyByContentTypeMachineNameAndID($contentTypeMachineName, $id);
    }

    public function findByUrl(string $url) : ? ManagedFileInterface
    {
        return $this->repository->findByUrl($url);
    }

    public function storeFile(ManagedFileInterface $file, UploadedFile $payload) : ? ManagedFileInterface
    {
        //TODO - Add switch for non public filesystems.
        $fileURL = $payload->storePubliclyAs('/public/files', $file->getFileName());
        if ($fileURL) {
            $existingFile = $this->findByUrl($fileURL);
            //Update the old file for the new file.
            if ($existingFile) {
                $existingFile->setTitle($file->getTitle());
                $existingFile->setFileType($file->getFileType());
                $existingFile->setFileName($file->getFileName());
                $existingFile->setUpdatedAt(new Carbon);
                $existingFile->setLastEditorUID($file->getLastEditorUID());
                return $this->save($existingFile);
            }
            $file->setUrl($fileURL);
            return $this->save($file);
        }
        // @codeCoverageIgnoreStart
        return null;
        // @codeCoverageIgnoreEnd
    }

    public static function sanitiseFileName(string $fileName) : string
    {
        return preg_replace("/[^a-z0-9\.\-\_]/", "", strtolower($fileName));
    }


    public function associateFileWithContent(ManagedFileInterface $file, string $contentTypeMachineName, int $contentID)
    {
        return $this->repository->associateFileWithContent($file, $contentTypeMachineName, $contentID);
    }

    public function updateFilesForContent(ContentManageableDomainEntityInterface $contentItem)
    {
        return $this->repository->updateFilesForContent($contentItem);
    }

    public function findByFileName(string $fileName) : ? ManagedFileInterface
    {
        return $this->repository->findByFileName($fileName);
    }

    public function delete(DomainEntityInterface $domainEntity)
    {
        Storage::delete($domainEntity->getUrl());
        return parent::delete($domainEntity);
    }
}
