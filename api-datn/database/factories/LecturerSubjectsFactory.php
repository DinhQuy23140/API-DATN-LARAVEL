<?php

namespace Database\Factories;

use App\Models\Subjects;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\lecturerSubjects>
 */
class LecturerSubjectsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'subject_id' => Subjects::inRandomOrder()->first()->id ?? Subjects::factory(),
            'semester' => $this->faker->numberBetween(1, 8),
            'year' => $this->faker->year(),
            'teacher_id' => Teacher::inRandomOrder()->first()->id ?? Teacher::factory(),
        ];
    }
}
