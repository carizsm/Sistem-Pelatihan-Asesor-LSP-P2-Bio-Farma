<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['unit_name' => 'Human Resources'],
            ['unit_name' => 'Finance & Accounting'],
            ['unit_name' => 'Production'],
            ['unit_name' => 'Quality Control'],
            ['unit_name' => 'Quality Assurance'],
            ['unit_name' => 'Research & Development'],
            ['unit_name' => 'Engineering'],
            ['unit_name' => 'Warehouse'],
            ['unit_name' => 'Procurement'],
            ['unit_name' => 'IT & Digital'],
            ['unit_name' => 'Marketing'],
            ['unit_name' => 'LSP'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }

        $this->command->info('Unit seeder completed: ' . count($units) . ' units created.');
    }
}
