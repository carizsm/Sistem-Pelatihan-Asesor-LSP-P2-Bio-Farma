<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PresenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registrations = Registration::with('tna')->get();

        if ($registrations->isEmpty()) {
            $this->command->warn('No registrations found. Please run RegistrationSeeder first.');
            return;
        }

        $presenceCount = 0;

        foreach ($registrations as $registration) {
            $tna = $registration->tna;

            if (!$tna || !$tna->start_date || !$tna->end_date) {
                continue;
            }

            // Parse tanggal TNA
            $startDate = Carbon::parse($tna->start_date);
            $endDate = Carbon::parse($tna->end_date);

            // Clock in: sekitar waktu start TNA (dengan random 0-30 menit setelah start)
            $clockIn = (clone $startDate)->addMinutes(rand(0, 30));

            // Clock out: sekitar waktu end TNA (dengan random 0-15 menit sebelum atau setelah end)
            $clockOut = (clone $endDate)->addMinutes(rand(-15, 15));

            // 90% chance trainee hadir
            if (rand(1, 100) <= 90) {
                Presence::factory()->create([
                    'registration_id' => $registration->id,
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                ]);

                $presenceCount++;
            }
        }

        $this->command->info("Presence seeder completed: {$presenceCount} presences created based on TNA schedule.");
    }
}
