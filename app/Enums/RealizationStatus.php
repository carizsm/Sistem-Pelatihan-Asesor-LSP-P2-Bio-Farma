<?php

namespace App\Enums;

enum RealizationStatus: string
{
    // Database value
    case OPEN = 'open';
    case RUNNING = 'running';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match($this) {
            self::OPEN => 'Belum Terealisasi',
            self::RUNNING => 'Sedang Berjalan', 
            self::COMPLETED => 'Terealisasi',
            self::CANCELED => 'Tidak Terealisasi',
        };
    }
    
    // Color helper in TNA index page
    public function color(): string
    {
        return match($this) {
            self::OPEN => 'primary',
            self::RUNNING => 'warning',
            self::COMPLETED => 'success',
            self::CANCELED => 'danger',
        };
    }
}