<?php

namespace Database\Seeders;

use App\Models\CouncilProjects;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouncilProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        CouncilProjects::factory()->count(20)->create();
    }
}
