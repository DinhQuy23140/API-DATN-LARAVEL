<?php

namespace Database\Factories;

use App\Models\ProgressLog;
use App\Models\Supervisor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentLog>
 */
class CommentLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'progress_log_id' => ProgressLog::inRandomOrder()->first()->id ?? ProgressLog::factory()->create()->id,
            'supervisor_id' => Supervisor::inRandomOrder()->first()->id ?? Supervisor::factory()->create()->id,
            'content' => $this->faker->sentence(),
        ];
    }
}
