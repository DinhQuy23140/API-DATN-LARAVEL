<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSupervisor;
use App\Models\ProjectTerm;
use App\Models\stage_timeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AssignmentSupervisorController extends Controller
{
    //
    public function getRequestManagementPage($supervisorId, $termId){
        $numberStage = 1;
        $timeStage = stage_timeline::where('project_term_id', $termId)
            ->where('number_of_rounds', $numberStage)
            ->first();
        // $items = AssignmentSupervisor::with('assignment.student.user')
        //     ->where('supervisor_id', $supervisorId)
        //     ->whereHas('assignment', function ($query) use ($termId) {
        //         $query->where('project_term_id', $termId);
        //     })
        //     ->get();
        $rows = ProjectTerm::whereHas('supervisors', function ($query) use ($supervisorId) {
                $query->where('id', $supervisorId);
            })
            ->with([
                'academy_year',
                'stageTimelines',
                'supervisors' => function ($query) use ($supervisorId) {
                    $query->where('id', $supervisorId)
                        ->with([
                            'assignment_supervisors.assignment.student.user'
                        ]);
                }
            ])
            ->get();
        return view('lecturer-ui.requests-management', compact('rows', 'timeStage'));
    }

    public function getProposeBySupervisor($supervisorId){
        return view('lecturer-ui.proposed-topics');
    }

    public function getStudentBySupervisorAndTermId($supervisorId, $termId){
        $items = AssignmentSupervisor ::with('assignment.student.user', 'supervisor.teacher.user')
            ->where('supervisor_id', $supervisorId)
            ->get();
        return view('lecturer-ui.supervised-students', compact('items'));
    }

    public function updateStatus(Request $request, AssignmentSupervisor $assignmentSupervisor)
    {
        // Suy ra action từ route hoặc nhận từ payload
        $routeIsAccept = $request->routeIs('web.teacher.requests.accept');
        $validated = $request->validate([
            'status' => 'nullable|in:accepted,rejected',
        ]);
        $status = $validated['status'] ?? ($routeIsAccept ? 'accepted' : 'rejected');

        $assignmentSupervisor->status = $status;
        // Nếu có cột note/remark, lưu lại:
        if (array_key_exists('note', $validated) && \Schema::hasColumn($assignmentSupervisor->getTable(), 'note')) {
            $assignmentSupervisor->note = $validated['note'];
        }
        $assignmentSupervisor->save();

        if ($request->expectsJson()) {
            return response()->json([
                'id'     => $assignmentSupervisor->id,
                'status' => $assignmentSupervisor->status,
                'message'=> 'Cập nhật trạng thái thành công'
            ]);
        }
        return back()->with('status', 'Cập nhật trạng thái thành công');
    }

    /**
     * Bulk tạo danh sách AssignmentSupervisor
     * Body JSON:
     * {
     *   "supervisor_id": 12,
     *   "project_term_id": 5,
     *   "assignment_ids": [101,102,103],
     *   "status": "pending" // optional
     * }
     */
    public function storeBulk(Request $request)
    {
        $data = $request->validate([
            'supervisor_id'    => ['required','integer','exists:supervisors,id'],
            'project_term_id'  => ['required','integer','exists:project_terms,id'],
            'assignment_ids'   => ['required','array','min:1'],
            'assignment_ids.*' => ['integer','distinct','exists:assignments,id'],
            'status'           => ['nullable', Rule::in(['pending','accepted','rejected'])]
        ]);

        $status        = $data['status'] ?? 'accepted';
        $supervisorId  = $data['supervisor_id'];
        $termId        = $data['project_term_id'];
        $assignmentIds = $data['assignment_ids'];

        // Đếm số đã được gán trong term qua quan hệ assignment
        $supervisor = \App\Models\Supervisor::withCount([
            'assignment_supervisors as current_assigned' => function($q) use ($termId){
                $q->whereHas('assignment', function($aq) use ($termId){
                    $aq->where('project_term_id', $termId);
                });
            }
        ])->findOrFail($supervisorId);

        $max = (int)($supervisor->max_students ?? 0);
        $cur = (int)($supervisor->current_assigned ?? 0);

        // Lấy assignment hợp lệ thuộc term
        $validAssignments = Assignment::whereIn('id', $assignmentIds)
            ->where('project_term_id', $termId)
            ->pluck('id')
            ->all();

        $invalidCount = count($assignmentIds) - count($validAssignments);
        if ($invalidCount > 0) {
            return response()->json([
                'message' => 'Một số assignment không thuộc đợt / không tồn tại',
                'invalid_count' => $invalidCount
            ], 422);
        }

        // Những assignment đã gán cho supervisor này (lọc qua assignment.project_term_id)
        $already = AssignmentSupervisor::where('supervisor_id', $supervisorId)
            ->whereIn('assignment_id', $validAssignments)
            ->whereHas('assignment', function($q) use ($termId){
                $q->where('project_term_id', $termId);
            })
            ->pluck('assignment_id')
            ->all();

        $toInsert = array_values(array_diff($validAssignments, $already));
        if (empty($toInsert)) {
            return response()->json([
                'message' => 'Tất cả assignments đã được gán trước đó cho supervisor này',
                'inserted' => 0,
                'skipped_existing' => count($already)
            ], 200);
        }

        // Kiểm tra chỉ tiêu
        if ($max > 0 && ($cur + count($toInsert)) > $max) {
            return response()->json([
                'message' => 'Vượt quá chỉ tiêu của giảng viên',
                'current' => $cur,
                'max'     => $max,
                'requested_new' => count($toInsert),
                'available_slots' => max(0, $max - $cur)
            ], 422);
        }

        $created = [];
        DB::transaction(function() use ($toInsert, $supervisorId, $status, &$created) {

            $now = now();
            $rows = [];
            foreach ($toInsert as $aid) {
                $rows[] = [
                    'assignment_id' => $aid,
                    'supervisor_id' => $supervisorId,
                    'status'        => $status,
                    'created_at'    => $now,
                    'updated_at'    => $now
                ];
            }
            AssignmentSupervisor::insert($rows);
            $created = $rows;

            // Cập nhật status cho các Assignment vừa gán
            Assignment::whereIn('id', $toInsert)
                ->update([
                    'status' => 'active',
                    'updated_at' => $now
                ]);
        });

        return response()->json([
            'message'          => 'Tạo phân công thành công',
            'inserted'         => count($created),
            'skipped_existing' => count($already),
            'data'             => $created
        ], 201);
    }
}
