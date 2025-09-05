<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ProjectTerm;
use App\Models\stage_timeline;
use App\Models\StageTimeLine; // đổi sang App\Models\StageTimeline nếu model bạn đặt khác
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StageTimeLineController extends Controller
{
    // Tạo/cập nhật nhiều mốc timeline cho 1 đợt
    public function storeBulk(Request $request, ProjectTerm $projectTerm)
    {
        $data = $request->validate([
            'stages'                  => ['required','array','min:1'],
            'stages.*.order'          => ['required','integer','min:1','max:50'],
            'stages.*.name'           => ['nullable','string','max:255'],
            'stages.*.start_date'     => ['required','date'],
            'stages.*.end_date'       => ['required','date','after_or_equal:stages.*.start_date'],
        ]);

        DB::transaction(function () use ($data, $projectTerm) {
            foreach ($data['stages'] as $it) {
                stage_timeline::updateOrCreate(
                    [
                        'project_term_id' => $projectTerm->id,
                        'order'           => (int) $it['order'],
                    ],
                    [
                        'name'       => $it['name'] ?? null,
                        'start_date' => $it['start_date'],
                        'end_date'   => $it['end_date'],
                    ]
                );
            }
        });

        return back()->with('success', 'Cập nhật timeline thành công.');
    }
}
