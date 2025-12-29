<?php

namespace Database\Seeders;

use App\Models\Tna;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\Sequence; // <--- WAJIB IMPORT INI
use Illuminate\Database\Seeder;

class TNASeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user admin untuk dijadikan creator TNA
        $admin = User::where('role', UserRole::ADMIN)->first();

        if (!$admin) {
            $this->command->warn('No admin user found. Creating TNA without user_id.');
        }

        $year = now()->year;

        // Buat TNA dengan nomor urut
        $tnas = Tna::factory()
            ->count(5)
            ->sequence(fn (Sequence $sequence) => [
                'tna_code' => 'TNA.' . $year . '.' . str_pad($sequence->index + 1, 1, '0', STR_PAD_LEFT),
                'period' => (string)$year,
            ])
            ->create([
                'user_id' => $admin?->id,
            ]);

        $this->command->info('TNA seeder completed: ' . $tnas->count() . ' TNAs created with sequential codes.');
    }
}