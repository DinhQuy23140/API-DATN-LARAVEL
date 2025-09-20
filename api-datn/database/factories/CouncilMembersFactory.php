<?php

namespace Database\Factories;

use App\Models\Council;
use App\Models\Supervisor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CouncilMembers>
 */
class CouncilMembersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'council_id' => Council::inRandomOrder()->first()->id ?? Council::factory()->create()->id,
            'supervisor_id' => Supervisor::inRandomOrder()->first()->id ?? Supervisor::factory()->create()->id,
            'role' => $this->faker->numberBetween(1, 5),
        ];
    }
}
