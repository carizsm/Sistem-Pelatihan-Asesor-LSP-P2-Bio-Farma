<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opsi String Manual
        $positions = ['Kepala Divisi', 'Kepala Departemen', 'Kepala Bagian', 'Manajer', 'Kasubbag', 'Staf'];
        $units = ['Human Capital', 'Keuangan', 'QA', 'QC', 'Produksi', 'LSP'];

        $users = [
            [
                'name' => 'Ahmad Fauzi',
                'nik' => '10012345',
                'email' => 'ahmad.fauzi@gmail.com',
                'position' => 'Staf',
                'unit' => 'LSP',
                'role' => UserRole::ADMIN,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'nik' => '10012346',
                'email' => 'siti.nurhaliza@gmail.com',
                'position' => 'Staf',
                'unit' => 'LSP',
                'role' => UserRole::ADMIN,
            ],
            [
                'name' => 'Aceng Nurahman',
                'nik' => '10012347',
                'email' => 'aceng@gmail.com',
                'position' => 'Manajer',
                'unit' => 'Keuangan',
                'role' => UserRole::TRAINEE,
            ],
        ];

        foreach ($users as $userData) {
            User::factory()->create($userData);
        }

        $this->command->info('User seeder: ' . count($users) . ' admins created.');
        
        User::factory()
            ->count(30)
            ->create([
                'position' => fn() => $positions[array_rand($positions)],
                'unit' => fn() => $units[array_rand($units)],
            ]);

        $this->command->info('User seeder: 30 trainee users created with random positions and units.');
        $this->command->info('User seeder completed: Total ' . (count($users) + 30) . ' trainees created.');
    }
}
