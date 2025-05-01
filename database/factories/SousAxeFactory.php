<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SousAxe>
 */
class SousAxeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom'=> fake()->sentence(),
            'description'=> fake()->realText(),
            'axe_id' => \App\Models\Axe::factory(),
        ];
    }
}
