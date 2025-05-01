<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Action>
 */
class ActionFactory extends Factory
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
            'sous_axe_id' => \App\Models\SousAxe::factory(),
            'statut' => fake()->randomElement(['En cours', 'Terminée', 'A venir', 'En attente', 'Annulée', 'En retard']),
            'date_debut' => fake()->dateTimeBetween('-1 year', '+3 year'),
            'date_fin' => fake()->dateTimeBetween('+1 year', '+4 year'),
        ];
    }
}
