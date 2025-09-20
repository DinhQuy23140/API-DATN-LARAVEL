<?php

namespace Database\Factories;

use App\Models\CouncilMembers;
use App\Models\CouncilProjects;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Council;
use App\Models\Assignment;
use App\Models\Supervisor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CouncilProjectDefences>
 */
class CouncilProjectDefencesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'council_project_id' => CouncilProjects::inRandomOrder()->first()->id ?? CouncilProjects::factory()->create()->id,
            'council_member_id' => CouncilMembers::inRandomOrder()->first()->id ?? CouncilMembers::factory()->create()->id,
            'score' => $this->faker->numberBetween(0, 10),
            'comments' => $this->faker->optional()->sentence(),
        ];
    }
}
