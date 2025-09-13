<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportFiles>
 */
class ReportFilesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'project_id' => Project::inRandomOrder()->first()->id ?? Project::factory()->create()->id,
            'file_name' => $this->faker->word . '.pdf',
            'file_url' => $this->faker->url,
            'file_type' => $this->faker->randomElement(['pdf', 'docx', 'xlsx']),
            'type_report' => $this->faker->randomElement(['outline', 'report']),
        ];
    }   
}
