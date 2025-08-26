<?php

namespace Database\Seeders;

use App\Models\Batch_student;
use App\Models\BatchStudent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BatchStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BatchStudent::factory()->count(5)->create();
    }
}
