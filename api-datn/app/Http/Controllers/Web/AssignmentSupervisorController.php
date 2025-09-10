<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSupervisor;
use App\Models\ProjectTerm;
use App\Models\stage_timeline;
use Illuminate\Http\Request;

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
        $items = Assignment::with('student.user', 'assignment_supervisors')
            ->whereHas('assignment_supervisors', function($query) use ($supervisorId, $termId) {
                $query->where('supervisor_id', $supervisorId)
                      ->where('project_term_id', $termId);
            })
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
}
