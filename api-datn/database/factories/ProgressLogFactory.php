<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgressLog>
 */
class ProgressLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start = $this->faker->dateTimeBetween('-2 months', 'now');
        $end = (clone $start)->modify('+'.rand(1, 10).' days');

        return [
                'project_id' => Project::inRandomOrder()->value('id'),
                'title' => $this->faker->sentence(6),
                'description' => $this->faker->paragraph(),
                'start_date_time' => $start,
                'end_date_time' => $end,
                'instructor_comment' => $this->faker->optional()->sentence(),
                'student_status' => $this->faker->randomElement(['chua_bat_dau', 'dang_thuc_hien', 'chua_hoan_thanh', 'da_hoan_thanh']),
                'instructor_status' => $this->faker->randomElement(['chua_danh_gia', 'dat', 'chua_dat', 'can_chinh_sua']),
            ];
    }
}
