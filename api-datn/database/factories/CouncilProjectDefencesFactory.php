<?php

namespace Database\Factories;

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
            'council_id' => Council::inRandomOrder()->first()->id ?? Council::create()->id,
            'assignment_id' => Assignment::inRandomOrder()->first()->id ?? Assignment::create()->id,
            'reviewer_id' => Supervisor::inRandomOrder()->first()->id ?? Supervisor::create()->id,
            'room' => $this->faker->bothify('Room ??-###'),
            'date' => $this->faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'time' => $this->faker->dateTimeBetween('08:00', '17:00')->format('H:i:s'),
        ];
    }
}
