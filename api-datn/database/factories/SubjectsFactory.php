<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subjects>
 */
class SubjectsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $subjects = [
            'Cơ sở dữ liệu',
            'Cấu trúc dữ liệu & Giải thuật',
            'Lập trình Java',
            'Lập trình Web',
            'Mạng máy tính',
            'Trí tuệ nhân tạo',
            'Phân tích thiết kế hệ thống',
            'Hệ điều hành',
            'Kinh tế vi mô',
            'Nguyên lý kế toán',
            'Luật thương mại',
            'Tiếng Anh chuyên ngành'
        ];

        return [
            'code' => $this->faker->unique()->bothify('SUBJ###'),
            'name' => $this->faker->randomElement($subjects),
            'description' => $this->faker->sentence(12),
            'number_of_credits' => $this->faker->numberBetween(2, 5),
            'department_id' => Department::query()->inRandomOrder()->value('id')
                ?? Department::factory()->create()->id,
        ];
    }
}
