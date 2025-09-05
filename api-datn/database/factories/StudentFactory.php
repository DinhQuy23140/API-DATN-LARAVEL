<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
                return [
            // Liên kết user
            'user_id' => User::factory()->state([
                'role' => 'student',
            ]),

            // Sinh mã SV ngẫu nhiên (ví dụ: STU12345)
            'student_code' => 'STU' . $this->faker->unique()->numerify('#####'),

            // Mã lớp giả định
            'class_code' => strtoupper($this->faker->bothify('CSE###')),

            // Major & Department chưa có thì random trong khoảng (ví dụ: 1-5)
            'major_id' => $this->faker->numberBetween(1, 5),
            'department_id' => $this->faker->numberBetween(1, 5),

            // Năm khóa học
            'course_year' => $this->faker->numberBetween(2018, 2025),
        ];
        // return [
        //     // Liên kết với user (mỗi student phải có 1 user)
        //     'user_id' => User::factory(),

        //     // Sinh mã SV ngẫu nhiên (ví dụ: STU12345)
        //     'student_code' => 'STU' . $this->faker->unique()->numerify('#####'),

        //     // Mã lớp giả định
        //     'class_code' => strtoupper($this->faker->bothify('CSE###')),

        //     // Liên kết major & department (có thể dùng factory nếu đã có)
        //     'major_id' => Major::factory(),
        //     'department_id' => Department::factory(),

        //     // Năm khóa học (ví dụ: 2020, 2021,...)
        //     'course_year' => $this->faker->numberBetween(2018, 2025),
        // ];
    }
}
