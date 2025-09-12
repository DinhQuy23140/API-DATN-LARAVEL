<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faculties>
 */
class FacultiesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => 'CNTT',
            'name' => 'Khoa Công nghệ thông tin',
            'description' => 'Khoa đào tạo ngành Công nghệ thông tin',
        ];
    }
}
