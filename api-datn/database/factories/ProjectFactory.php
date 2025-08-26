<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Models\ProgressLog;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3), // ví dụ "Smart Farming System"
            'description' => $this->faker->paragraph(4), // mô tả ngẫu nhiên
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Project $project) {
            // Mỗi Project sẽ có 3-7 ProgressLog
            ProgressLog::factory()
                ->count(rand(3, 7))
                ->create([
                    'project_id' => $project->id, // Gắn vào Project vừa tạo
                ]);
        });
    }
}
