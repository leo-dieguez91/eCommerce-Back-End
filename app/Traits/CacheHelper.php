<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheHelper
{
    protected function getCacheKey(string $model, $id = null): string
    {
        return $id ? "{$model}_{$id}" : "{$model}_all";
    }

    protected function getFromCache(string $model, $id = null)
    {
        return Cache::get($this->getCacheKey($model, $id));
    }

    protected function saveToCache(string $model, $data, $id = null, $minutes = 60)
    {
        Cache::put($this->getCacheKey($model, $id), $data, now()->addMinutes($minutes));
        return $data;
    }

    protected function clearCache(string $model, $id = null): void
    {
        if ($id) {
            Cache::forget($this->getCacheKey($model, $id));
        }
        Cache::forget($this->getCacheKey($model));
    }

    protected function rememberCache(string $model, $id = null, $minutes = 60, \Closure $callback)
    {
        return Cache::remember($this->getCacheKey($model, $id), now()->addMinutes($minutes), $callback);
    }
} 