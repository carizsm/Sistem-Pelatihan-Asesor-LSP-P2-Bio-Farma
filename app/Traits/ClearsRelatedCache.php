<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsRelatedCache
{
    /**
     * Clear specific cache keys related to the operation
     */
    protected function clearRelatedCaches(array $patterns): void
    {
        foreach ($patterns as $pattern) {
            // For paginated caches (e.g., admin_tnas_list_page_*)
            if (str_contains($pattern, '*')) {
                $this->clearPaginatedCache($pattern);
            } else {
                Cache::forget($pattern);
            }
        }
    }

    /**
     * Clear all pages of a paginated cache
     */
    protected function clearPaginatedCache(string $pattern): void
    {
        $baseKey = str_replace('*', '', $pattern);
        
        // Clear first 50 pages (adjust based on your needs)
        for ($page = 1; $page <= 50; $page++) {
            Cache::forget($baseKey . $page);
        }
    }

    /**
     * Clear all user-specific caches
     */
    protected function clearUserCaches(int $userId): void
    {
        $cacheKeys = [
            "dashboard_registrations_user_{$userId}",
            "presence_registrations_user_{$userId}",
            "evaluasi1_registrations_user_{$userId}",
            "evaluasi2_registrations_user_{$userId}",
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}
