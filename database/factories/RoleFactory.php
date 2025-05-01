<?php

namespace Database\Factories;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => $this->faker->randomElement(['Adminitrateur','Coordinateur d\'axe','Responsable d\'action','Consultant']),
            'description' => $this->faker->sentence(),
        ];
    }
}
