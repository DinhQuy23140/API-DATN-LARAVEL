<?php

namespace Database\Seeders;

use App\Models\ProposedTopic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProposedTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProposedTopic::factory()->count(50)->create();
    }
}
