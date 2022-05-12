<?php

namespace Fifthgate\Objectivity\Files\Infrastructure\Mapper;

use Fifthgate\Objectivity\Files\Infrastructure\Mapper\Interfaces\FileMapperInterface;
use Fifthgate\Objectivity\Repositories\Infrastructure\Mapper\AbstractDomainEntityMapper;
use Fifthgate\Objectivity\Core\Domain\Interfaces\DomainEntityInterface;
use Fifthgate\Objectivity\Core\Domain\Collection\Interfaces\DomainEntityCollectionInterface;
use Fifthgate\Objectivity\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;
use Illuminate\Database\DatabaseManager as DB;
use Fifthgate\Objectivity\Content\Domain\Core\Interfaces\ContentTypeDefinitionInterface;
use Fifthgate\Objectivity\Files\Domain\Collection\ManagedFileCollection;
use Fifthgate\Objectivity\Files\Domain\ManagedFile;
use Carbon\Carbon;
use Fifthgate\Objectivity\Files\Domain\Interfaces\ManagedFileInterface;
use Fifthgate\Objectivity\Content\Domain\Core\Interfaces\ContentManageableDomainEntityInterface;

class FileMapper extends AbstractDomainEntityMapper implements FileMapperInterface
{
    protected string $tableName = 'files_managed';

    protected bool $publishes = false;

    protected bool $softDeletes = false;

    protected $idColumnName = 'fid';

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
        $file = new ManagedFile($result['filename'], $result['filesystem']);
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
}
