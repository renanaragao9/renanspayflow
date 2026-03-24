<?php

namespace Database\Factories;

use App\Models\CostCenter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CostCenter>
 */
class CostCenterFactory extends Factory
{
    protected $model = CostCenter::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'type' => $this->faker->randomElement(['Interno', 'Externo']),
            'due_date' => $this->faker->optional()->date(),
            'user_id' => User::factory(),
        ];
    }
}
