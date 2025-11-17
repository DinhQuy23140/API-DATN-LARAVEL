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
use Illuminate\Support\Facades\Schema;

class AssignmentSupervisorController extends Controller
{
    //
    public function getRequestManagementPage($supervisorId, $termId)
    {
        $numberStage = 1;

        $timeStage = stage_timeline::where('project_term_id', $termId)
            ->where('number_of_rounds', $numberStage)
            ->first();

        $rows = ProjectTerm::where('id', $termId)
            ->with([
                'academy_year',
                'stageTimelines',
                'assignments' => function ($query) use ($supervisorId, $termId) {
                    $query->where('project_term_id', $termId) // assignments trong term này
                        ->whereHas('assignment_supervisors', function ($q) use ($supervisorId) {
                            $q->where('supervisor_id', $supervisorId);
                        })
                        ->with([
                            'student.user',
                            'project.progressLogs.attachments',
                            'project.reportFiles',
                            'assignment_supervisors' => function ($q) use ($supervisorId) {
                                $q->where('supervisor_id', $supervisorId)
                                    ->with('supervisor.teacher.user');
                            }
                        ]);
                }
            ])
            ->firstOrFail();

        return view('lecturer-ui.requests-management', compact('rows', 'timeStage'));
    }

    public function getProposeBySupervisor($supervisorId){
        return view('lecturer-ui.proposed-topics');
    }

    public function getStudentBySupervisorAndTermId($supervisorId, $termId)
    {
        $items = Assignment::with([
                'student.user',
                'project.reportFiles',
                'assignment_supervisors' => function ($q) use ($supervisorId) {
                    $q->where('supervisor_id', $supervisorId);
                }
            ])
            ->where('project_term_id', $termId)
            ->whereHas('assignment_supervisors', function ($q) use ($supervisorId) {
                $q->where('supervisor_id', $supervisorId);
            })
            ->get();

        $projectTerm = ProjectTerm::with('academy_year')->find($termId);

        return view('lecturer-ui.supervised-students', compact('items', 'termId', 'supervisorId', 'projectTerm'));
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
        if (array_key_exists('note', $validated) && Schema::hasColumn($assignmentSupervisor->getTable(), 'note')) {
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
                    'status' => 'actived',
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

    // POST: web.teacher.assignment_supervisors.report_score
    public function updateReportScore(Request $request, AssignmentSupervisor $assignmentSupervisor)
    {
        // Bỏ kiểm tra supervisor hiện tại theo yêu cầu

        $data = $request->validate([
            'score_report' => ['required', 'numeric', 'min:0', 'max:10'],
            'note'         => ['nullable', 'string', 'max:2000'],
        ]);

        // $assignmentSupervisor->score_report = (float) $data['score_report'];
        // if (array_key_exists('note', $data)) {
        //     $assignmentSupervisor->note = $data['note'];
        // }
        // $assignmentSupervisor->save();

        // return response()->json([
        //     'ok'     => true,
        //     'data'   => $assignmentSupervisor->only(['id', 'assignment_id', 'supervisor_id', 'score_report', 'note']),
        //     'message'=> 'Lưu điểm thành công.',
        // ]);

        $assignmentSupervisor->score_report = (float) $data['score_report'];
        $assignmentSupervisor->comments = $data['note'] ?? null;
        $assignmentSupervisor->save();

        return response()->json([
            'ok'      => true,
            'data'    => $assignmentSupervisor->only(['id', 'assignment_id', 'supervisor_id', 'score_report']),
            'message' => 'Lưu điểm thành công.',
        ]);

    }

    /**
     * Return JSON list of assignments supervised by a given supervisor in a specific project term.
     * GET /assignments/supervisor/{supervisorId}/term/{termId}/list
     */
    public function listBySupervisorTerm($supervisorId, $termId)
    {
        // Find AssignmentSupervisor rows for this supervisor where the related assignment belongs to the term
        $rows = AssignmentSupervisor::with(['assignment.student.user'])
            ->where('supervisor_id', $supervisorId)
            ->whereHas('assignment', function($q) use ($termId){
                $q->where('project_term_id', $termId);
            })
            ->get();

        $data = $rows->map(function($r){
            return [
                'id' => $r->id,
                'assignment_id' => $r->assignment_id,
                'status' => $r->status,
                'score_report' => $r->score_report,
                'comments' => $r->comments,
                'student' => $r->assignment ? (
                    [
                        'id' => $r->assignment->student->id ?? null,
                        'name' => $r->assignment->student->user->fullname ?? ($r->assignment->student->user->name ?? ''),
                        'code' => $r->assignment->student->student_code ?? '',
                        'email' => $r->assignment->student->user->email ?? '',
                        'class' => $r->assignment->student->class_code ?? ''
                    ]
                ) : null
            ];
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Delete an AssignmentSupervisor record.
     * DELETE /head/assignment-supervisors/{assignmentSupervisor}
     */
    public function destroy(AssignmentSupervisor $assignmentSupervisor)
    {
        // Optionally, you can check permissions/ownership here.
        try {
            $assignmentSupervisor->delete();
            return response()->json(['ok' => true, 'message' => 'Xóa phân công thành công', 'id' => $assignmentSupervisor->id]);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'message' => 'Không thể xóa phân công', 'error' => $e->getMessage()], 500);
        }
    }
}
