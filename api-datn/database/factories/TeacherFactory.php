<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Faculties;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // Liên kết với user có role = teacher
            'user_id' => User::factory()->state(['role' => 'teacher']),

            // Sinh mã giảng viên ngẫu nhiên: GV + số random
            'teacher_code' => 'GV' . $this->faker->unique()->numerify('###'),

            // Chọn ngẫu nhiên học vị
            'degree' => $this->faker->randomElement([
                'Cử nhân', 'Thạc sĩ', 'Tiến sĩ', 'Phó Giáo sư', 'Giáo sư'
            ]),


            // Giả định department_id, faculties_id là số nguyên
            'department_id' => Department::inRandomOrder()->first()->id ?? Department::factory()->create()->id,

            // Chọn chức vụ
            'position' => $this->faker->randomElement([
                'Giảng viên', 'Trưởng bộ môn', 'Phó khoa', 'Trưởng khoa'
            ]),
        ];
    }
}
