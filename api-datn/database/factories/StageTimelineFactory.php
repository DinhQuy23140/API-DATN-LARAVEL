<?php

namespace Database\Factories;

use App\Models\ProjectTerm;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\stage_timeline>
 */
class StageTimelineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Tạo khoảng ngày hợp lệ ngẫu nhiên (nếu không override ở Seeder)
        $start = $this->faker->dateTimeBetween('-2 months', '+2 months');
        $end   = (clone $start)->modify('+' . $this->faker->numberBetween(3, 14) . ' days');
        return [
            // Nếu gọi factory độc lập, nó sẽ tự tạo ProjectTerm (fallback).
            // Khi dùng Seeder, ta sẽ override project_term_id = $term->id
            'project_term_id'   => ProjectTerm::factory(),
            'number_of_rounds'  => $this->faker->numberBetween(1, 8),
            'start_date'        => Carbon::instance($start)->toDateString(),
            'end_date'          => Carbon::instance($end)->toDateString(),
            'description'       => $this->faker->sentence(),
            'status'            => $this->faker->randomElement(['pending', 'active', 'completed']),
        ];
    }
}
