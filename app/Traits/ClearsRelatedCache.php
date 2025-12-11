<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsRelatedCache
{
    /**
     * Clear specific cache keys based on patterns.
     * Supports wildcard '*' for paginated caches.
     * * @param array $patterns List of cache keys (e.g., ['admin_users_*', 'dashboard_stats'])
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
     * Helper to clear first 50 pages of a paginated cache key.
     * Bruteforce strategy for File Cache Driver.
     */
    protected function clearPaginatedCache(string $pattern): void
    {
        $baseKey = str_replace('*', '', $pattern);
        
        for ($page = 1; $page <= 50; $page++) {
            Cache::forget($baseKey . $page);
        }
    }

    /**
     * MASTER CLEANER FOR USER ACTIONS
     * Menghapus cache sisi Peserta DAN cache sisi Laporan Admin yang terdampak.
     * * @param int $userId ID User yang datanya berubah
     * @param int|null $tnaId (Optional) ID TNA terkait. Wajib diisi jika aksi user berdampak ke Laporan Admin.
     */
    protected function clearUserCaches(int $userId, ?int $tnaId = null): void
    {
        // ==========================================
        // 1. BERSIHKAN SISI PESERTA (Frontend)
        // ==========================================
        $userKeys = [
            "dashboard_registrations_user_{$userId}", // Jadwal di Dashboard
            "presence_registrations_user_{$userId}",  // List Menu Presensi
            "evaluasi1_registrations_user_{$userId}", // List Menu Feedback
            "evaluasi2_registrations_user_{$userId}", // List Menu Quiz
            "dashboard_stats",                        // Statistik Global (Opsional)
        ];

        foreach ($userKeys as $key) {
            Cache::forget($key);
        }

        // ==========================================
        // 2. BERSIHKAN SISI ADMIN (Laporan/Rekap)
        // ==========================================
        // Hanya dijalankan jika $tnaId dikirim (artinya user berinteraksi dengan TNA spesifik)
        if ($tnaId) {
            $adminKeys = [
                "admin_participants_tna_{$tnaId}",
                "admin_feedback_report_tna_{$tnaId}", 
                "admin_quiz_pretest_tna_{$tnaId}",    
                "admin_quiz_posttest_tna_{$tnaId}",
                "admin_feedback_results_index",
                "admin_quiz_results_index",
            ];

            foreach ($adminKeys as $key) {
                Cache::forget($key);
            }
        }
    }
}