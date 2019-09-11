<?php

namespace App\Services;

use App\Contracts\CacheableContract;
use App\Jobs\PersistResourceJob;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Cacher
{
    /**
     * The number of seconds the resource should stay cached.
     */
    private const CACHE_TTL = 600;

    /**
     * Retrieve the collection of resources of the given model.
     *
     * @param string $model
     * @return Collection
     */
    public static function collect(string $model): Collection
    {
        self::checkIfCacheable($model);

        // Retrieve the cached collection or fetch it directly from the database
        return Cache::remember(
            $model::makeCollectionCacheKey(),
            self::CACHE_TTL,
            function () use ($model)
            {
                return $model::query()->latest()->get();
            }
        );
    }

    /**
     * Delete the resource from the cache and the database.
     *
     * @param CacheableContract $model
     * @return void
     */
    public static function delete(CacheableContract $model): void
    {
        Cache::forget($model->getCacheKey());

        $model->delete();
    }

    /**
     * Retrieve a resource of the given model with the given ID.
     *
     * @param string $model
     * @param string $id
     * @return Model|null
     */
    public static function find(string $model, string $id): ?Model
    {
        self::checkIfCacheable($model);

        // Generate the key for the resource we are looking for
        $key = $model::makeCacheKey($id);

        // Retrieve the resource from the cache.
        // If it's not cached, retrieve it directly form the database instead.
        $resource = Cache::get(
            $key,
            function () use ($model, $id)
            {
                return $model::query()->where('id', $id)->first();
            }
        );

        if (! $resource)
        {
            return null;
        }

        // Cache the resource data.
        // If it was already cached, it'll update the TTL.
        Cache::put($key, $resource, self::CACHE_TTL);

        return $resource;
    }

    /**
     * Store the given resource.
     *
     * @param CacheableContract $model
     * @return Model
     */
    public static function store(CacheableContract $model): Model
    {
        // Cache the resource data
        Cache::put($model->getCacheKey(), $model, self::CACHE_TTL);

        // Add the new resource to the cached collection of the same type
        self::updateCachedCollection($model);

        // Queue the job in charge of persisting the resource in the database storage
        // TODO: When testing, using "dispatch" fails while "dispatch_now" works fine
        dispatch(new PersistResourceJob($model));

        return $model;
    }

    /**
     * @param string|Model $model
     * @throws Exception
     * @return void
     */
    private static function checkIfCacheable($model): void
    {
        $model = ($model instanceof Model) ? get_class($model) : $model;

        if (! in_array(CacheableContract::class, class_implements($model)))
        {
            throw new Exception('Model ['.$model.'] must implement the CacheableContract.');
        }
    }

    /**
     * @param string $model
     * @return void
     */
    private static function flushCollection(string $model): void
    {
        Cache::forget($model::makeCollectionCacheKey());
    }

    /**
     * Upsert the given resource to the cached collection of same type.
     *
     * @param Model $model
     * @return void
     */
    private static function updateCachedCollection(Model $model): void
    {
        $key = $model::makeCollectionCacheKey();

        /** @var Collection $collection */
        $collection = Cache::get(
            $key,
            function () use ($model)
            {
                return $model::query()->latest()->get();
            }
        );

        // Find out if the resource already exists in the cached collection or not
        // TODO: Check if we can use a LazyCollection here
        $cachedResourceKey = $collection->search(
            function ($resource, $key) use ($model)
            {
                return $resource->id == $model->id;
            }
        );

        // TODO: Should be extracted to avoid using ugly "else"
        if ($cachedResourceKey)
        {
            $collection = $collection->replace([$cachedResourceKey => $model]);
        }
        else
        {
            $collection->prepend($model);
        }

        // Store the updated collection in the cache
        Cache::put($model::makeCollectionCacheKey(), $collection, self::CACHE_TTL);
    }
}
