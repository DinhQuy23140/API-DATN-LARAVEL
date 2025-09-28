<?php

namespace Database\Factories;

use App\Models\Faculties;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FacultyRoles>
 */
class FacultyRolesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'faculty_id' => Faculties::factory(),
            'user_id' => User::factory(),
            'role' => $this->faker->randomElement(['assistant', 'dean', 'vice_dean', 'head']),
        ];
    }
}
