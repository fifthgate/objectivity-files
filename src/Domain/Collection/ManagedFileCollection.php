<?php

namespace Services\Files\Domain\Collection;

use Services\Core\Domain\Collection\AbstractDomainEntityCollection;
use Services\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;

class ManagedFileCollection extends AbstractDomainEntityCollection implements ManagedFileCollectionInterface
{
    public function filterByMimeTypes(array $mimeTypes) : ? ManagedFileCollectionInterface
    {
        $collection = new self;
        foreach ($this->collection as $item) {
            if (in_array($item->getFileType(), $mimeTypes)) {
                $collection->add($item);
            }
        }
        return $collection;
    }
}
