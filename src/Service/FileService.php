<?php

namespace Fifthgate\Objectivity\Files\Service;

use Fifthgate\Objectivity\Files\Service\Interfaces\FileServiceInterface;
use Fifthgate\Objectivity\Files\Domain\Interfaces\ManagedFileInterface;
use Fifthgate\Objectivity\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;
use Fifthgate\Objectivity\Files\Infrastructure\Repository\Interfaces\FileRepositoryInterface;
//use Fifthgate\Objectivity\Content\Domain\Core\Interfaces\ContentTypeDefinitionInterface;
use Illuminate\Http\UploadedFile;
use Storage;
//use Fifthgate\Objectivity\Content\Domain\Core\Interfaces\ContentManageableDomainEntityInterface;
use Carbon\Carbon;
use Fifthgate\Objectivity\Repositories\Service\AbstractRepositoryDrivenDomainEntityManagementService;
use Fifthgate\Objectivity\Core\Domain\Interfaces\DomainEntityInterface;

class FileService extends AbstractRepositoryDrivenDomainEntityManagementService implements FileServiceInterface
{
    public function __construct(
        FileRepositoryInterface $fileRepository
    ) {
        parent::__construct($fileRepository);
    }

    //@codeCoverageIgnoreStart
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
    //@codeCoverageIgnoreEnd

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
