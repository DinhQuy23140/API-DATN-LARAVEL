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
}
