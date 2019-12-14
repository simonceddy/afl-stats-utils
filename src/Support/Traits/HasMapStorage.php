<?php
namespace AflUtils\Support\Traits;

use Ds\Map;

trait HasMapStorage
{
    /**
     * The storage Map
     */
    protected Map $storage;

    /**
     * Get the storage Map.
     * 
     * @return Map
     */ 
    public function getStorage()
    {
        isset($this->storage) ?: $this->storage = new Map();
        return $this->storage;
    }
}
