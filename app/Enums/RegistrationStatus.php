<?php

namespace App\Enums;

/**
 * Mendefinisikan status kelulusan peserta pada sebuah TNA.
 */
enum RegistrationStatus: string
{
    case TERDAFTAR = 'terdaftar';
    case LULUS = 'lulus';
    case TIDAK_LULUS = 'tidak lulus';
}