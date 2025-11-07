<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Marjor;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cohort;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Class_room>
 */
class Class_roomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'class_code' => strtoupper($this->faker->bothify('C###')), // ví dụ: C101, C202
            'class_name' => $this->faker->words(2, true),              // ví dụ: "CNTT 1", "Kỹ thuật 2"
            'number_students' => $this->faker->numberBetween(20, 80), // số SV trong lớp
            'admission_year' => $this->faker->year(),
            'cohort' => $this->faker->numberBetween(60, 67),
            'description' => $this->faker->sentence(),
            'marjor_id' => Marjor::inRandomOrder()->first()->id ?? Marjor::factory(),
            'department_id' => Department::inRandomOrder()->first()->id ?? Department::factory(),
        ];
    }
}
