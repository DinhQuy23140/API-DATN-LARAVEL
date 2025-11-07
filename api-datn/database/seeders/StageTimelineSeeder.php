<?php

namespace Database\Seeders;

use App\Models\ProjectTerm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\stage_timeline;

class StageTimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
public function run(): void
    {
        // Nếu chưa có ProjectTerm, tạo tạm vài record (tuỳ bạn)
        if (ProjectTerm::count() === 0) {
            ProjectTerm::factory()->count(3)->create(); // hoặc tuỳ ý
        }

        $terms = ProjectTerm::all();

        foreach ($terms as $term) {
            // Nếu muốn ngày các vòng trải đều trong khoảng của ProjectTerm (nếu có cột start_date/end_date)
            $termStart = $term->start_date ? Carbon::parse($term->start_date) : Carbon::now();
            $termEnd   = $term->end_date   ? Carbon::parse($term->end_date)   : (clone $termStart)->addMonths(2);

            // Chia đều 8 vòng trong khoảng thời gian (đơn giản hoá)
            $totalDays = $termStart->diffInDays($termEnd) ?: 80; // fallback
            $chunk     = max(1, intdiv($totalDays, 8));

            foreach (range(1, 8) as $round) {
                $roundStart = (clone $termStart)->addDays(($round - 1) * $chunk);
                $roundEnd   = (clone $roundStart)->addDays($chunk - 1);

                // Tránh vượt quá termEnd
                if ($roundEnd->gt($termEnd)) {
                    $roundEnd = clone $termEnd;
                }

                stage_timeline::updateOrCreate(
                    [
                        'project_term_id'  => $term->id,
                        'number_of_rounds' => $round,
                    ],
                    [
                        'start_date'  => $roundStart->toDateString(),
                        'end_date'    => $roundEnd->toDateString(),
                        'description' => "Round {$round} of term {$term->id}",
                    ]
                );
            }
        }
    }
}
