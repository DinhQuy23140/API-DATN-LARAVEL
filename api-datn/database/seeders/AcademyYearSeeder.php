<?php

namespace Database\Seeders;

use App\Models\Academy_year;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PhpParser\Node\Stmt\Foreach_;
use App\Models\AcademyYear;

class AcademyYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(range(2010, 2025) as $year) {
            AcademyYear::create(['year_name' => $year . '-' . ($year + 1)]);
        }
    }
}
