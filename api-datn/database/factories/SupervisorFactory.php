<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Teacher;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supervisor>
 */
class SupervisorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // lấy ngẫu nhiên 1 teacher_id từ bảng teachers
            'teacher_id' => Teacher::inRandomOrder()->first()->id ?? Teacher::factory(),
            'project_term_id' => \App\Models\ProjectTerm::inRandomOrder()->value('id'),
            'max_students' => $this->faker->numberBetween(3, 10),
            'expertise' => $this->faker->words(3, true), // ví dụ "AI Machine Learning"
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
