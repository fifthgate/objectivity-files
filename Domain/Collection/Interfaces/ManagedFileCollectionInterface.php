<?php

namespace Services\Files\Domain\Collection\Interfaces;

interface ManagedFileCollectionInterface
{
    public function filterByMimeTypes(array $mimeTypes) : ? ManagedFileCollectionInterface;
}
