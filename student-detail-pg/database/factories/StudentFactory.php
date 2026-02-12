<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rollno' => $this->faker->unique()->numberBetween(1, 9999),
            'name'   => $this->faker->name(),
            'marks'  => $this->faker->numberBetween(0, 100),
        ];
    }
}
