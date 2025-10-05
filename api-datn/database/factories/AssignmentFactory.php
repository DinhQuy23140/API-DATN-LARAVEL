<?php

namespace Database\Factories;

use App\Models\ProjectTerm;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Models\Student;

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
            'student_id' => Student::inRandomOrder()->first()->id ?? Student::factory(),
            'project_id' => Project::inRandomOrder()->first()->id ?? Project::factory(),
            'project_term_id' => ProjectTerm::inRandomOrder()->first()->id ?? null,
            'status' => $this->faker->randomElement(['pending', 'cancelled', 'actived', 'stopped']),
            'role' => $this->faker->randomElement(['leader', 'member', null]),
        ];
    }
}
