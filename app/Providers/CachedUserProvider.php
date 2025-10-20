<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;

class CachedUserProvider extends EloquentUserProvider
{
    public function retrieveById($identifier)
    {
        return Cache::remember("user_$identifier", now()->addMinutes(config('cache.session_auth_cache_ttl')), function () use ($identifier) {
            return parent::retrieveById($identifier); // panggil bawaan Eloquent
        });
    }
}
