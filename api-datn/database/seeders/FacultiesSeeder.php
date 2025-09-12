<?php

namespace Database\Seeders;

use App\Models\Faculties;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Faculties::factory()->create([
            'code' => 'CNTT',
            'name' => 'Khoa Công nghệ thông tin',
            'description' => 'Khoa đào tạo ngành Công nghệ thông tin',
        ]);
    }
}
