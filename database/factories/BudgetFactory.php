<?php

namespace Database\Factories;

use App\Models\Axe;
use App\Models\Budget;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Budget>
 */
class BudgetFactory extends Factory
{
    protected $model = Budget::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'axe_id' => Axe::inRandomOrder()->first()->id,
            'montant' => $this->faker->numberBetween(10000, 500000),
            'date_allocation' => $this->faker->date(),
        ];
    }
}
