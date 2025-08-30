<?php

namespace Database\Seeders;

use App\Models\AssignmentSupervisor;
use Illuminate\Database\Seeder;

class AssignmentSupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AssignmentSupervisor::factory(50)->create();
    }
}
