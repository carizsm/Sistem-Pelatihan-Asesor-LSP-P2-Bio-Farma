<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizAnswer>
 */
class QuizAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'answer' => $this->faker->sentence(4),
            'answer_order' => 1,
            'is_correct' => false, // Default false, akan di-set true untuk 1 jawaban benar

            'quiz_question_id' => null,
        ];
    }
}
