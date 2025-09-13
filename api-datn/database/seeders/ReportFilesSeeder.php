<?php

namespace Database\Seeders;

use App\Models\ReportFiles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ReportFiles::factory()->count(50)->create();
    }
}
