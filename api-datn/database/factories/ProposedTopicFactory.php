<?php

namespace Database\Factories;

use App\Models\Supervisor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProposedTopic>
 */
class ProposedTopicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'supervisor_id' => Supervisor::inRandomOrder()->first()->id ?? Supervisor::factory()->create()->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'proposed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
