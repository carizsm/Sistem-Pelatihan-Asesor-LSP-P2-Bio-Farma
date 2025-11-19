<?php

namespace App\Policies;

use App\Models\Tna;
use App\Models\User;
use App\Enums\UserRole; // Pastikan Anda mengimpor Enum UserRole Anda

class TnaPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tna $tna): bool
    {
        // Admin can update any TNA
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        // Creator can update their own TNA
        return $user->id === $tna->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tna $tna): bool
    {
        // Only admin can delete TNAs
        return $user->role === UserRole::ADMIN;
    }

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
}