<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class TaskCache
{
    private const VERSION_KEY = 'tasks:version';

    /**
     * Get the current cache version.
     */
    public static function version(): int
    {
        return Cache::rememberForever(self::VERSION_KEY, fn () => 1);
    }

    /**
     * Bump the cache version.
     */
    public static function bump(): void
    {
        if (! Cache::has(self::VERSION_KEY)) {
            Cache::forever(self::VERSION_KEY, 1);
        }
        Cache::increment(self::VERSION_KEY);
    }

    /**
     * Generate a cache key for task search.
     */
    public static function keyForSearch(User $user, array $filters): string
    {
        $scope = $user->hasRole('manager') ? 'manager' : 'user:'.$user->id;
        $payload = json_encode($filters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return 'v'.self::version().":tasks:search:$scope:".sha1($payload);
    }
}
