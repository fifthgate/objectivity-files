<?php

namespace Fifthgate\Objectivity\Files\Domain\Collection\Interfaces;

interface ManagedFileCollectionInterface
{
    public function filterByMimeTypes(array $mimeTypes) : ? ManagedFileCollectionInterface;
}
