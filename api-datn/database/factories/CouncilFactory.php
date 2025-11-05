<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\ProjectTerm;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Models\Student;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Council>
 */
class CouncilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'code' => strtoupper($this->faker->bothify('C??###')),
            'description' => $this->faker->optional()->sentence(),
            'project_term_id' => ProjectTerm::inRandomOrder()->first()->id ?? ProjectTerm::factory()->create()->id,
            'department_id' => Department::inRandomOrder()->first()->id ?? Department::factory()->create()->id,
            'address' => $this->faker->optional()->address(),
            'date' => $this->faker->optional()->date(),
            'time' => $this->faker->optional()->time(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
