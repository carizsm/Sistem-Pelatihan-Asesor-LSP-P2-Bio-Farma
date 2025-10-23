<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\RegistrationStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'regist_date' => $this->faker->date(),
            'status' => RegistrationStatus::TERDAFTAR,

            'user_id' => null,
            'tna_id' => null,
        ];
    }
}
