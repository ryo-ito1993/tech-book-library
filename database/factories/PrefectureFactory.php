<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prefecture>
 */
class PrefectureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pref_code' => $this->faker->unique()->numberBetween(1, 47),
            'name' => $this->faker->unique()->prefecture,
        ];
    }
}
