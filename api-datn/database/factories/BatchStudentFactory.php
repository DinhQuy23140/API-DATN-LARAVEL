<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BatchStudent>
 */
class BatchStudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'student_id' => \App\Models\Student::inRandomOrder()->value('id'),
            'project_terms_id' => \App\Models\ProjectTerm::inRandomOrder()->value('id'),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
