<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Log setiap query yang memakan waktu lebih dari 50ms
        DB::listen(function ($query) {
            // $query->time adalah waktu dalam milidetik (ms)
            if ($query->time > 100) { 
                Log::warning('Long Query Detected:', [
                    'sql' => $query->sql,
                    'time_ms' => $query->time,
                    'bindings' => $query->bindings
                ]);
            }
        });
    }
}
