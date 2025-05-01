<?php

namespace Database\Factories;

use App\Models\Axe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TauxExecution>
 */
class TauxExecutionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'axe_id' => Axe::inRandomOrder()->first()->id,
            'taux' => $this->faker->numberBetween(0, 100),
        ];
    }
}
