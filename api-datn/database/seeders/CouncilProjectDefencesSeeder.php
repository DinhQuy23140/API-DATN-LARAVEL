<?php

namespace Database\Seeders;

use App\Models\CouncilProjectDefences;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouncilProjectDefencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        CouncilProjectDefences::factory()->count(10)->create();
    }
}
