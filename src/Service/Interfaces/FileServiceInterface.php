<?php

namespace Fifthgate\Objectivity\Files\Service\Interfaces;

use Fifthgate\Objectivity\Files\Domain\Interfaces\ManagedFileInterface;
use Fifthgate\Objectivity\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;
use Illuminate\Http\UploadedFile;
use Fifthgate\Objectivity\Content\Domain\Core\Interfaces\ContentManageableDomainEntityInterface;
use Fifthgate\Objectivity\Repositories\Service\Interfaces\DomainEntityManagementServiceInterface;

interface FileServiceInterface extends DomainEntityManagementServiceInterface
{
    public function findByFileName(string $fileName): ?ManagedFileInterface;

    public static function sanitiseFileName(string $fileName): string;

    public function storeFile(ManagedFileInterface $file, UploadedFile $payload): ?ManagedFileInterface;
}
