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

        // 1. Buat user khusus untuk Unit LSP
        $this->command->info('👤 Step 1: Creating LSP unit users...');
        $this->call([
            UserSeeder::class,
        ]);

        // 2. Buat Admin user
        $this->command->info('👤 Step 2: Creating admin user...');

        // 3. Buat Trainee users
        $this->command->info('👥 Step 3: Creating trainee users...');

        $posOptions = ['Kepala Divisi', 'Kepala Departemen', 'Kepala Bagian', 'Manajer', 'Supervisor', 'Staf'];
        $unitOptions = ['Human Capital', 'Keuangan', 'QA', 'QC', 'Produksi', 'LSP'];

        User::factory()
            ->count(15)
            ->create([
                'position' => fn() => $posOptions[array_rand($posOptions)],
                'unit' => fn() => $unitOptions[array_rand($unitOptions)],
            ]);

        // 4. Seed TNA (tanpa Quiz)
        $this->command->info('📚 Step 4: Creating TNAs...');
        $this->call([
            TNASeeder::class,
        ]);

        // 5. Seed Quiz Questions & Answers
        $this->command->info('❓ Step 5: Creating Quiz Questions & Answers...');
        $this->call([
            QuizSeeder::class,
        ]);

        // 6. Seed Registrations
        $this->command->info('📝 Step 6: Creating registrations...');
        $this->call([
            RegistrationSeeder::class,
        ]);

        // 7. Seed Quiz Attempts (Pre-Test & Post-Test)
        $this->command->info('✍️  Step 7: Creating quiz attempts (Pre-Test & Post-Test)...');
        $this->call([
            QuizAttemptSeeder::class,
        ]);

        // 8. Seed Presences
        $this->command->info('⏰ Step 8: Creating presences based on TNA schedule...');
        $this->call([
            PresenceSeeder::class,
        ]);

        // 9. Seed Feedback Results (YANG HILANG)
        $this->command->info('💬 Step 9: Creating feedback results...');
        $this->call([
            FeedbackResultSeeder::class,
        ]);
        
        $this->command->newLine();
        $this->command->info('✅ All seeders completed successfully!');
    }
}
