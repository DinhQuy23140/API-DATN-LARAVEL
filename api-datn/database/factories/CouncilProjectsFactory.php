<?php

namespace Database\Factories;

use App\Models\CouncilMembers;
use App\Models\Council;
use App\Models\Assignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CouncilProjects>
 */
class CouncilProjectsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'council_id' => Council::inRandomOrder()->first()->id ?? Council::create()->id,
            'assignment_id' => Assignment::inRandomOrder()->first()->id ?? Assignment::create()->id,
            'council_member_id' => CouncilMembers::inRandomOrder()->first()->id ?? CouncilMembers::create()->id,
            'room' => $this->faker->bothify('Room ??-###'),
            'date' => $this->faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'time' => $this->faker->dateTimeBetween('08:00', '17:00')->format('H:i:s'),
            'review_score' => $this->faker->randomFloat(2, 0, 10),
            'comments' => $this->faker->optional()->sentence(),
        ];
    }
}
