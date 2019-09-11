<?php

namespace App\Contracts;

interface CacheableContract
{
    /**
     * Get the cache key for the model.
     *
     * @return string
     */
    public function getCacheKey(): string;

    /**
     * Generate a cache key for the model with the given ID value.
     *
     * @param string $id
     * @return string
     */
    public static function makeCacheKey(string $id): string;

    /**
     * Generate the cache key to hold the collection of resources of the model.
     *
     * @return string
     */
    public static function makeCollectionCacheKey(): string;
}
