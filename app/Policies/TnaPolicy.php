<?php

namespace App\Policies;

use App\Models\Tna;
use App\Models\User;
use App\Enums\UserRole;

class TnaPolicy
{
    public function manageParticipants(User $user, Tna $tna): bool
    {
        if ($user->role !== UserRole::ADMIN) {
            return false;
        }
        
        return in_array($tna->status, ['Dijadwalkan', 'Sedang Berlangsung']);
    }
}
