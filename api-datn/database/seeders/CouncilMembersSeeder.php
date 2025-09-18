<?php

namespace Database\Seeders;

use App\Models\CouncilMembers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouncilMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        CouncilMembers::factory(50)->create();
    }
}
