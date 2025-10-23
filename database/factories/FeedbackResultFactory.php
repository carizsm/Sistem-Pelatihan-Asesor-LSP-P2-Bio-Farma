<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeedbackResult>
 */
class FeedbackResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'feedback_date' => $this->faker->date(),
            'score_01' => $this->faker->numberBetween(1, 4),
            'score_02' => $this->faker->numberBetween(1, 4),
            'score_03' => $this->faker->numberBetween(1, 4),
            'score_04' => $this->faker->numberBetween(1, 4),
            'score_05' => $this->faker->numberBetween(1, 4),
            'score_06' => $this->faker->numberBetween(1, 4),
            'score_07' => $this->faker->numberBetween(1, 4),
            'score_08' => $this->faker->numberBetween(1, 4),
            'score_09' => $this->faker->numberBetween(1, 4),
            'score_10' => $this->faker->numberBetween(1, 4),
            'score_11' => $this->faker->numberBetween(1, 4),
            'score_12' => $this->faker->numberBetween(1, 4),
            'score_13' => $this->faker->numberBetween(1, 4),
            'score_14' => $this->faker->numberBetween(1, 4),
            'score_15' => $this->faker->numberBetween(1, 4),

            'registration_id' => null,
        ];
    }
}
