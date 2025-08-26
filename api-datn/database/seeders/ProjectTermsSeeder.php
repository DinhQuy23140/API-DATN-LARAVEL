<?php

namespace Database\Seeders;

use App\Models\ProjectTerm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project_terms;

class ProjectTermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectTerm::factory()->count(5)->create();
    }
}
