<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Registration;
use App\Models\FeedbackResult;

class FeedbackResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua registrasi yang sudah ada
        // Kita wajib 'with('tna')' untuk mengambil end_date
        $registrations = Registration::with('tna')->get();

        if ($registrations->isEmpty()) {
            $this->command->warn('No registrations found. Run RegistrationSeeder first.');
            return;
        }

        $this->command->info('Creating Feedback Results...');

        // 2. Loop setiap registrasi
        foreach ($registrations as $registration) {
            
            // 3. Hitung tanggal feedback yang realistis
            $feedbackDate = $registration->tna->end_date->addDays(rand(1, 3));

            // 4. Panggil factory dan timpa (override) datanya
            FeedbackResult::factory()->create([
                'registration_id' => $registration->id,
                'feedback_date'   => $feedbackDate,
            ]);
        }
        
        $this->command->info('Feedback Results created successfully.');
    }
}