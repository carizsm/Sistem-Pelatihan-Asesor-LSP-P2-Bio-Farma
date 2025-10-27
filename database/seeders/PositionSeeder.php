<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['position_name' => 'Manager'],
            ['position_name' => 'Supervisor'],
            ['position_name' => 'Staff'],
            ['position_name' => 'Senior Staff'],
            ['position_name' => 'Junior Staff'],
            ['position_name' => 'Operator'],
            ['position_name' => 'Technician'],
            ['position_name' => 'Quality Control'],
            ['position_name' => 'Quality Assurance'],
            ['position_name' => 'Research & Development'],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }

        $this->command->info('Position seeder completed: ' . count($positions) . ' positions created.');
    }
}
