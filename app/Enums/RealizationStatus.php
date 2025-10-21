<?php

namespace App\Enums;

/**
 * Mendefinisikan status realisasi TNA yang disimpan di database.
 * Ini hanya menyimpan status yang tidak bisa disimpulkan oleh sistem.
 */
enum RealizationStatus: string
{
    /**
     * Status default saat TNA dibuat dan belum dimulai.
     */
    case BELUM_TEREALISASI = 'belum terealisasi';

    /**
     * Status jika TNA dibatalkan secara manual oleh Admin.
     */
    case TIDAK_TEREALISASI = 'tidak terealisasi';
}