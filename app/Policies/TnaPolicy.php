<?php

namespace App\Policies;

use App\Models\Tna;
use App\Models\User;
use App\Enums\UserRole; // Pastikan Anda mengimpor Enum UserRole Anda

class TnaPolicy
{
    /**
     * Determine whether the user can manage participants for the model.
     */
    public function manageParticipants(User $user, Tna $tna): bool
    {
        // REVISI: Izinkan jika user adalah Admin
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        // (Logika Anda sebelumnya, mungkin seperti ini)
        return $user->id === $tna->user_id;
    }

    // ... (method lain seperti 'update', 'delete', dll.)
}