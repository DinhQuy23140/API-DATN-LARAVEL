<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProgressLog;
use App\Models\Attachment;

class ProgressLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProgressLog::factory()
            ->count(12)
            ->create()
            ->each(function ($log) {
                // Gán 2–5 attachment cho mỗi log
                Attachment::factory()->count(rand(2, 5))->create([
                    'progress_log_id' => $log->id,
                ]);
            });
    }
}
