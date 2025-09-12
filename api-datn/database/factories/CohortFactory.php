<?php

namespace Database\Factories;

use App\Models\Marjor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cohort>
 */
class CohortFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'number_course' => $this->faker->numberBetween(60, 67), // số khóa học
            'year_of_admission' => $this->faker->year(), // năm nhập học
            'number_students' => $this->faker->numberBetween(400, 600), // số lượng sinh viên
            'marjor_id' => Marjor::inRandomOrder()->first()->id ?? Marjor::factory(), 
        ];
    }
}
