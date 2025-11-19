<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\RealizationStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tna>
 */
class TnaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('+1 week', '+2 week');
        $endDate = clone $startDate;
        $hours = $this->faker->numberBetween(3, 8);
        $endDate->modify('+' . $hours . ' hours');
        $year = $startDate->format('Y');
        
        // Generate realistic method options
        $methods = ['In-House Training', 'Online Training', 'Workshop', 'Seminar', 'Coaching'];
        
        // Generate a unique training name for this factory run
        $trainingName = 'Pelatihan ' . $this->faker->unique()->words(3, true);

        return [
            'tna_code' => strtoupper($this->faker->unique()->bothify('TNA.'. $year .'.#%')),
            'name' => $trainingName,
            'method' => $this->faker->randomElement($methods),
            'passing_score' => $this->faker->numberBetween(60, 100),
            'period' => $year,
            'batch' => 1, // First batch for this training name
            'start_date' => $startDate,
            'end_date' => $endDate,
            'speaker' => $this->faker->name(),
            'spt_file_path' => null,
            'realization_status' => RealizationStatus::BELUM_TEREALISASI,
            
            // New required fields
            'reason' => 'Meningkatkan kompetensi karyawan dalam ' . $this->faker->words(3, true),
            'goal' => 'Peserta mampu ' . $this->faker->words(4, true),
            'before_status' => 'Karyawan masih memerlukan peningkatan dalam ' . $this->faker->words(3, true),
            'after_status' => 'Karyawan diharapkan dapat ' . $this->faker->words(4, true),

            'user_id' => null,
        ];
    }

    /**
     * State khusus untuk memperbarui status realisasi TNA.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'realization_status' => RealizationStatus::TIDAK_TEREALISASI,
        ]);
    }
}
