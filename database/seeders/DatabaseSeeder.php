<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Position;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting database seeding...');
        $this->command->newLine();

        // 1. Seed master data terlebih dahulu
        $this->command->info('📋 Step 1: Seeding master data...');
        $this->call([
            PositionSeeder::class,
            UnitSeeder::class,
        ]);

        // 2. Buat user khusus untuk Unit LSP
        $this->command->info('👤 Step 2: Creating LSP unit users...');
        $this->call([
            UserSeeder::class,
        ]);

        // 3. Buat Admin user
        $this->command->info('👤 Step 3: Creating admin user...');
        $position = Position::first();
        $unit = Unit::first();

        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@biofarma.com',
            'nik' => '1234567890',
            'position_id' => $position?->id,
            'unit_id' => $unit?->id,
        ]);

        // 4. Buat Trainee users
        $this->command->info('👥 Step 4: Creating trainee users...');
        User::factory()
            ->count(15)
            ->create([
                'position_id' => Position::inRandomOrder()->first()?->id,
                'unit_id' => Unit::inRandomOrder()->first()?->id,
            ]);

        // 5. Seed TNA (tanpa Quiz)
        $this->command->info('📚 Step 5: Creating TNAs...');
        $this->call([
            TNASeeder::class,
        ]);

        // 6. Seed Quiz Questions & Answers
        $this->command->info('❓ Step 6: Creating Quiz Questions & Answers...');
        $this->call([
            QuizSeeder::class,
        ]);

        // 7. Seed Registrations
        $this->command->info('📝 Step 7: Creating registrations...');
        $this->call([
            RegistrationSeeder::class,
        ]);

        // 8. Seed Quiz Attempts (Pre-Test & Post-Test)
        $this->command->info('✍️  Step 8: Creating quiz attempts (Pre-Test & Post-Test)...');
        $this->call([
            QuizAttemptSeeder::class,
        ]);

        // 9. Seed Presences
        $this->command->info('⏰ Step 9: Creating presences based on TNA schedule...');
        $this->call([
            PresenceSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ All seeders completed successfully!');
    }
}
