<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Assignment;
use App\Models\Supervisor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssignmentSupervisor>
 */
class AssignmentSupervisorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'assignment_id' => Assignment::inRandomOrder()->first()->id ?? Assignment::factory(),
            'supervisor_id' => Supervisor::inRandomOrder()->first()->id ?? Supervisor::factory(),
            'role' => $this->faker->randomElement(['main', 'coo']),
            'status' => $this->faker->randomElement(['approved', 'pending', 'rejected']),
        ];
    }
}
