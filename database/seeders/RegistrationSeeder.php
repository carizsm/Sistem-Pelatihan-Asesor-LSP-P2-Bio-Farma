<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\User;
use App\Models\Tna;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user trainee
        $trainees = User::where('role', UserRole::TRAINEE)->get();
        $tnas = Tna::all();

        if ($trainees->isEmpty()) {
            $this->command->warn('No trainee users found. Please create users first.');
            return;
        }

        if ($tnas->isEmpty()) {
            $this->command->warn('No TNAs found. Please run TNASeeder first.');
            return;
        }

        $registrationCount = 0;

        // Daftarkan beberapa trainee ke setiap TNA
        foreach ($tnas as $tna) {
            // Ambil 3-5 trainee random per TNA
            $selectedTrainees = $trainees->random(min(5, $trainees->count()));

            foreach ($selectedTrainees as $trainee) {
                Registration::factory()->create([
                    'user_id' => $trainee->id,
                    'tna_id' => $tna->id,
                ]);
                $registrationCount++;
            }
        }

        $this->command->info("Registration seeder completed: {$registrationCount} registrations created.");
    }
}
