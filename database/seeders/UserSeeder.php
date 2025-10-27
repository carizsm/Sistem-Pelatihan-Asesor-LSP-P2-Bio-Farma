<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Unit;
use App\Models\Position;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari Unit LSP
        $lspUnit = Unit::where('unit_name', 'LSP')->first();
        $lspPosition = Position::where('position_name', 'Staff')->first();

        if (!$lspUnit) {
            $this->command->warn('Unit LSP not found. Please run UnitSeeder first.');
            return;
        }

        // Ambil position random untuk user
        $positions = Position::all();
        $units = Unit::all();

        if ($positions->isEmpty()) {
            $this->command->warn('No positions found. Please run PositionSeeder first.');
            return;
        }

        if ($units->isEmpty()) {
            $this->command->warn('No units found. Please run UnitSeeder first.');
            return;
        }

        // Buat 2 user khusus untuk Unit LSP
        $users = [
            [
                'name' => 'Ahmad Fauzi',
                'nik' => '1001234567',
                'email' => 'ahmad.fauzi@biofarma.com',
                'position_id' => $lspPosition->id,
                'unit_id' => $lspUnit->id,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'nik' => '1001234568',
                'email' => 'siti.nurhaliza@biofarma.com',
                'position_id' => $lspPosition->id,
                'unit_id' => $lspUnit->id,
            ],
        ];

        foreach ($users as $userData) {
            User::factory()->create($userData);
        }

        $this->command->info('User seeder: ' . count($users) . ' admins created.');

        // Buat 30 user trainee dengan unit dan position random
        User::factory()
            ->count(30)
            ->create([
                'position_id' => fn() => $positions->random()->id,
                'unit_id' => fn() => $units->random()->id,
            ]);

        $this->command->info('User seeder: 30 trainee users created with random positions and units.');
        $this->command->info('User seeder completed: Total ' . (count($users) + 30) . ' trainees created.');
    }
}
