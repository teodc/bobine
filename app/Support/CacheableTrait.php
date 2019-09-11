<?php

namespace App\Support;

use App\Services\Cacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait CacheableTrait
{
    /**
     * Get the cache key of the model.
     *
     * @return string
     */
    public function getCacheKey(): string
    {
        return self::makeCacheKey($this->{$this->getKeyName()});
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @return Model|null
     */
    public function resolveRouteBinding($value): ?Model
    {
        return Cacher::find(self::class, $value);
    }

    /**
     * Generate a cache key for the model with the given ID value.
     *
     * @param string $id
     * @return string
     */
    public static function makeCacheKey(string $id): string
    {
        return Str::lower(class_basename(self::class).':'.$id);
    }

    /**
     * Generate the cache key to hold the collection of resources of the model.
     *
     * @return string
     */
    public static function makeCollectionCacheKey(): string
    {
        return Str::plural(Str::lower(class_basename(self::class)));
    }
}
