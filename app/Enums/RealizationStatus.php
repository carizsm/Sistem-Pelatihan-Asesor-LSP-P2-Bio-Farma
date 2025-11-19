<?php

namespace App\Enums;

/**
 * Mendefinisikan status realisasi TNA yang disimpan di database.
 * Status ini menunjukkan apakah pelatihan benar-benar dilaksanakan atau tidak.
 */
enum RealizationStatus: string
{
    /**
     * Status default saat TNA dibuat dan belum dimulai.
     */
    case BELUM_TEREALISASI = 'belum terealisasi';

    /**
     * Status ketika TNA sudah dilaksanakan/diimplementasikan.
     */
    case TEREALISASI = 'terealisasi';

    /**
     * Status jika TNA dibatalkan secara manual oleh Admin.
     */
    case TIDAK_TEREALISASI = 'tidak terealisasi';
}