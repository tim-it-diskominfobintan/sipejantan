<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    public function updated(User $user) 
    {
        Cache::forget("user_$user->id");
        Cache::put("user_$user->id", $user->fresh(), now()->addMinutes(config('cache.session_auth_cache_ttl')));
    }
}
