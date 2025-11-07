<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Marjor>
 */
class MarjorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => strtoupper($this->faker->bothify('MJ###')),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->optional()->sentence(),
            'department_id' => Department::inRandomOrder()->first()->id ?? Department::factory(),
        ];
    }
}
