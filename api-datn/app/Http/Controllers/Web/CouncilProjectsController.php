<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Council;
use App\Models\CouncilProjects;
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
            'comments'  => $data['comment'] ?? null,
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

    public function show(CouncilProjects $council_project)
    {
        $cp = $council_project->load([
            // SV, lớp, đề tài
            'assignment.student.user',
            'assignment.student.classroom',
            'assignment.project',
            // GV hướng dẫn
            'assignment.assignment_supervisors.supervisor.teacher.user',
            // Hội đồng + thành viên để map vai trò
            'council.council_members.supervisor.teacher.user',
            // Điểm/nhận xét theo từng thành viên (nếu có)
            'reviews.council_member.supervisor.teacher.user',
        ]);

        $student     = optional(optional($cp->assignment)->student);
        $studentUser = optional($student->user);
        $project     = optional(optional($cp->assignment)->project);

        $data = [
            'student_code' => $student->student_code ?? null,
            'student_name' => $studentUser->fullname ?? null,
            'class_name'   => $student->classroom->name ?? ($student->class_name ?? null),
            'topic'        => $project->name ?? null,
            'advisors'     => optional(optional($cp->assignment)->assignment_supervisors)
                                ->map(function ($asv) {
                                    return optional(optional(optional($asv->supervisor)->teacher)->user)->fullname;
                                })
                                ->filter()
                                ->values()
                                ->all(),
        ];

        // Chuẩn bị khung điểm/nhận xét theo vai trò
        $scores = [
            'chair'     => ['name' => null, 'score' => null, 'comment' => null],
            'secretary' => ['name' => null, 'score' => null, 'comment' => null],
            'member1'   => ['name' => null, 'score' => null, 'comment' => null],
            'member2'   => ['name' => null, 'score' => null, 'comment' => null],
            'member3'   => ['name' => null, 'score' => null, 'comment' => null],
        ];
        $roleKey = function ($role) {
            return match ((string)$role) {
                '5' => 'chair',
                '4' => 'secretary',
                '3' => 'member1',
                '2' => 'member2',
                '1' => 'member3',
                default => null,
            };
        };

        // Gắn tên theo thành viên hội đồng
        foreach (optional(optional($cp->council)->council_members) ?? [] as $cm) {
            $key = $roleKey($cm->role);
            if (!$key) continue;
            $scores[$key]['name'] = optional(optional(optional($cm->supervisor)->teacher)->user)->fullname;
        }

        // Gắn điểm/nhận xét nếu có quan hệ reviews
        $reviews = collect($cp->reviews ?? []);
        if ($reviews->isNotEmpty()) {
            foreach ($reviews as $rev) {
                $cm = optional($rev->council_member);
                $key = $roleKey($cm->role ?? null);
                if (!$key) continue;
                $scores[$key]['name']    = $scores[$key]['name'] ?: optional(optional(optional($cm->supervisor)->teacher)->user)->fullname;
                $scores[$key]['score']   = $rev->score ?? null;
                $scores[$key]['comment'] = $rev->comment ?? null;
            }
        }

        return response()->json([
            'ok'   => true,
            'data' => array_merge($data, ['scores' => $scores]),
        ]);
    }

    /**
     * Remove a council_project (remove a student from a council)
     */
    public function destroy(CouncilProjects $council_project)
    {
        try {
            $council_project->delete();
            return response()->json(['ok' => true, 'message' => 'Đã xóa sinh viên khỏi hội đồng.']);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => 'Xóa thất bại.'], 500);
        }
    }
}
