<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Council;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CouncilProjectsController extends Controller
{
    // Gán nhiều SV vào 1 hội đồng: tạo bản ghi council_projects với council_member_id = null
    public function assignStudents(Request $request, Council $council)
    {
        $validated = $request->validate([
            'assignment_ids'   => 'required|array|min:1',
            'assignment_ids.*' => 'integer|exists:assignments,id',
            'room'             => 'nullable|string|max:255',
            'date'             => 'nullable|date_format:Y-m-d',
            'time'             => 'nullable|date_format:H:i',
        ]);

        $assignmentIds = array_values(array_unique($validated['assignment_ids']));
        $now = now();

        $defaultRoom = $request->input('room', $council->address ?? null);
        $defaultDate = $request->input('date'); // có thể null
        $defaultTime = $request->input('time'); // có thể null

        $rows = array_map(function ($aid) use ($council, $now, $defaultRoom, $defaultDate, $defaultTime) {
            return [
                'council_id'        => (int)$council->id,
                'assignment_id'     => (int)$aid,
                'council_member_id' => null,
                'room'              => $defaultRoom,   // đảm bảo có room nếu DB NOT NULL
                'date'              => $defaultDate,
                'time'              => $defaultTime,
                'created_at'        => $now,
                'updated_at'        => $now,
            ];
        }, $assignmentIds);

        DB::table('council_projects')->upsert(
            $rows,
            ['council_id', 'assignment_id'],
            ['council_member_id', 'room', 'date', 'time', 'updated_at']
        );

        return response()->json(['ok'=>true,'message'=>'Đã gán sinh viên vào hội đồng.','count'=>count($rows)]);
    }

    public function assign(Request $request, Council $council)
    {
        $validated = $request->validate([
            'council_member_id'     => [
                'required', 'integer',
                Rule::exists('council_members', 'id')->where(fn($q)=>$q->where('council_id',$council->id)),
            ],
            'council_project_ids'   => ['required','array','min:1'],
            'council_project_ids.*' => [
                'integer',
                Rule::exists('council_projects', 'id')->where(fn($q)=>$q->where('council_id',$council->id)),
            ],
            'date' => ['nullable', 'date_format:Y-m-d'],
            'time' => ['nullable', 'date_format:H:i'],
            'room' => ['nullable', 'string', 'max:255'],
        ]);

        $cmId = (int)$validated['council_member_id'];
        $cpIds = collect($validated['council_project_ids'])->map(fn($v)=>(int)$v)->unique()->values();
        if ($cpIds->isEmpty()) {
            return response()->json(['ok'=>false,'message'=>'Chưa chọn sinh viên.'], 422);
        }

        // Luôn cập nhật nếu client gửi key (kể cả "" -> đã được middleware convert null)
        $updates = [
            'council_member_id' => $cmId,
            'updated_at'        => now(),
        ];
        if ($request->exists('date')) $updates['date'] = $request->input('date'); // null/chuỗi hợp lệ
        if ($request->exists('time')) $updates['time'] = $request->input('time');
        if ($request->exists('room')) $updates['room'] = $request->input('room');

        $affected = DB::table('council_projects')
            ->where('council_id', $council->id)
            ->whereIn('id', $cpIds)
            ->update($updates);

        return response()->json(['ok'=>true,'message'=>'Đã phân công phản biện.','affected'=>$affected]);
    }

    public function update_review_score(Request $request, int $council_project)
    {
        $data = $request->validate([
            'score'   => 'required|numeric|min:0|max:10',
            'comment' => 'nullable|string|max:2000', // optional nếu có cột lưu nhận xét
        ]);

        $updates = [
            'review_score' => (float)$data['score'],
            'updated_at'   => now(),
        ];

        // Nếu DB có cột review_comment thì lưu kèm
        // Bỏ nếu DB không có cột này
        // use Illuminate\Support\Facades\Schema; (thêm nếu dùng đoạn dưới)
        // if (\Schema::hasColumn('council_projects', 'review_comment') && array_key_exists('comment', $data)) {
        //     $updates['review_comment'] = $data['comment'];
        // }

        $affected = DB::table('council_projects')
            ->where('id', $council_project)
            ->update($updates);

        if ($affected === 0) {
            return response()->json(['ok' => false, 'message' => 'Không tìm thấy bản ghi.'], 404);
        }

        return response()->json([
            'ok'      => true,
            'message' => 'Đã lưu điểm phản biện.',
            'data'    => ['council_project_id' => $council_project, 'review_score' => $updates['review_score']],
        ]);
    }
}
