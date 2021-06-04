<?php

namespace Services\Files\Infrastructure\Mapper;

use Services\Files\Infrastructure\Mapper\Interfaces\FileMapperInterface;
use Services\Core\Infrastructure\Mapper\AbstractDomainEntityMapper;
use Services\Core\Domain\Interfaces\DomainEntityInterface;
use Services\Core\Domain\Collection\Interfaces\DomainEntityCollectionInterface;
use Services\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;
use Illuminate\Database\DatabaseManager as DB;
use Services\Content\Domain\Core\Interfaces\ContentTypeDefinitionInterface;
use Services\Files\Domain\Collection\ManagedFileCollection;
use Services\Files\Domain\ManagedFile;
use Carbon\Carbon;
use Services\Files\Domain\Interfaces\ManagedFileInterface;
use Services\Content\Domain\Core\Interfaces\ContentManageableDomainEntityInterface;

class FileMapper extends AbstractDomainEntityMapper implements FileMapperInterface
{
    protected string $tableName = 'files_managed';

    protected bool $publishes = false;

    protected bool $softDeletes = false;

    protected $idColumnName = 'fid';

    public function getJoinTableName() : string
    {
        return 'content_files';
    }

    public function mapEntity(array $result) : DomainEntityInterface
    {
        return self::staticMap($result);
    }

    public function makeCollection() : DomainEntityCollectionInterface
    {
        return new ManagedFileCollection;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function staticMapMany(array $results) : DomainEntityCollectionInterface
    {
        $collection = new ManagedFileCollection;
        foreach ($results as $result) {
            $collection->add(self::staticMap((array) $result));
        }
        return $collection;
    }

    public static function staticMap(array $result) : DomainEntityInterface
    {
        $file = new ManagedFile;
        $file->setID($result['fid']);
        $file->setTitle($result['title']);
        $file->setURL($result['url']);
        $file->setFileType($result['filetype']);
        $file->setCreatedAt(new Carbon($result['created_at']));
        $file->setUpdatedAt(new Carbon($result['updated_at']));
        $file->setAuthorUID($result['author_uid']);
        $file->setLastEditorUID($result['last_editor_uid']);
        $file->setFileName($result['filename']);
        $file->hashSelf();
        return $file;
    }

    public function delete(DomainEntityInterface $domainEntity)
    {
        $this->db->table($this->getJoinTableName())
            ->where('fid', '=', $domainEntity->getID())
            ->delete();
        parent::delete($domainEntity);
    }

    public function findManyByContentTypeMachineNameAndID(string $contentTypeMachineName, int $id) : ManagedFileCollectionInterface
    {
        $files = new ManagedFileCollection;
        $results = $this->db->table($this->getJoinTableName())
            ->select('fid')
            ->where([
                ['content_id', '=', $id],
                ['contentTypeMachineName', '=', $contentTypeMachineName]
            ])
            ->get()
            ->toArray();
        foreach ($results as $result) {
            $files->add($this->find($result->fid));
        }
        return $files;
    }

    protected function update(DomainEntityInterface $domainEntity) : DomainEntityInterface
    {
        $this->db->table($this->getTableName())->where('fid', '=', $domainEntity->getID())
            ->update([
                'title' => $domainEntity->getTitle(),
                'url' => $domainEntity->getURL(),
                'filetype' => $domainEntity->getFileType(),
                'created_at' => $domainEntity->getCreatedAt()->format($this->mysqlDateFormat),
                'updated_at' => $domainEntity->getUpdatedAt()->format($this->mysqlDateFormat),
                'author_uid' => $domainEntity->getAuthorUID(),
                'last_editor_uid' => $domainEntity->getLastEditorUID(),
                'filename' => $domainEntity->getFileName()
            ]);
        return $domainEntity;
    }

    protected function create(DomainEntityInterface $domainEntity) : DomainEntityInterface
    {
        $id = $this->db->table($this->getTableName())->insertGetId(
            [
                'title' => $domainEntity->getTitle(),
                'url' => $domainEntity->getURL(),
                'filetype' => $domainEntity->getFileType(),
                'created_at' => $domainEntity->getCreatedAt()->format($this->mysqlDateFormat),
                'updated_at' => $domainEntity->getUpdatedAt()->format($this->mysqlDateFormat),
                'author_uid' => $domainEntity->getAuthorUID(),
                'last_editor_uid' => $domainEntity->getLastEditorUID(),
                'filename' => $domainEntity->getFileName()
            ]
        );
        $domainEntity->setID($id);
        return $domainEntity;
    }

    public function associateFileWithContent(ManagedFileInterface $file, string $contentTypeMachineName, int $contentID)
    {
        $this->db->table($this->getJoinTableName())->insert([
            'contentTypeMachineName' => $contentTypeMachineName,
            'fid' => $file->getID(),
            'content_id' => $contentID
        ]);
    }

    public function updateFilesForContent(ContentManageableDomainEntityInterface $contentItem)
    {
        $this->wipeFilesForContent($contentItem->getContentTypeMachineName(), $contentItem->getID());
        if ($contentItem->getFiles() && !$contentItem->getFiles()->isEmpty()) {
            foreach ($contentItem->getFiles() as $file) {
                $this->associateFileWithContent($file, $contentItem->getContentTypeMachineName(), $contentItem->getID());
            }
        }
    }

    private function wipeFilesForContent(string $contentTypeMachineName, int $contentID)
    {
        $this->db->table($this->getJoinTableName())
            ->where([
                ['content_id', '=', $contentID],
                ['contentTypeMachineName', '=', $contentTypeMachineName]
            ])
            ->delete();
    }
}
