<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'birth_date' => fake()->dateTimeBetween('-100 years')->format('Y-m-d'),
            'registration_number' => fake()->unique()->randomNumber(),
            'company_id' => fake()->numberBetween(1, 10)
        ];
    }
}
