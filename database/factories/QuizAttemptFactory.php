<?php

namespace Database\Factories;

use App\Enums\QuizAttemptType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizAttempt>
 */
class QuizAttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => QuizAttemptType::PRE_TEST,
            'score' => $this->faker->numberBetween(0, 100),

            'registration_id' => null,
        ];
    }

    /**
     * State untuk Post-Test, bisa dipanggil di Seeder.
     */
    public function postTest(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => QuizAttemptType::POST_TEST,
        ]);
    }
}
