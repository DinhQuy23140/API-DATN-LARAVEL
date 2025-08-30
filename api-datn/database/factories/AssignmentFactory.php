<?php

namespace Database\Factories;

use App\Models\BatchStudent;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Batch_student;
use App\Models\Supervisor;
use App\Models\Project;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'batch_student_id' => BatchStudent::factory(),
            'project_id' => Project::factory(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'role' => $this->faker->randomElement(['leader', 'member', null]),
        ];
    }
}
