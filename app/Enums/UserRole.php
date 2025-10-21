<?php

namespace App\Enums;

/**
 * Mendefinisikan peran pengguna (role) dalam sistem.
 */
enum UserRole: string
{
    case ADMIN = 'admin';
    case TRAINEE = 'trainee';
}