<?php

namespace Database\Seeders;

use App\Models\UserResearch;
use Illuminate\Database\Seeder;

class UserReseachSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserResearch::factory()->count(50)->create();
    }
}
