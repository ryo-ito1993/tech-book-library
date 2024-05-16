<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Prefecture;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prefecture_id' => Prefecture::factory(),
            'city_code' => $this->faker->unique()->numberBetween(1, 100),
            'name' => $this->faker->name(),
        ];
    }
}
