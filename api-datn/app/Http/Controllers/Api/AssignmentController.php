<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $assignments = Assignment::with([
            'student.user',
            'project_term.academy_year',
            'assignment_supervisors.supervisor.teacher.user',
            'project.progressLogs.attachments',
        ])
        ->when($request->query('student_id'), function($q, $sid){
            $q->whereHas('student', fn($qb)=>$qb->where('id', $sid));
        })
        ->when($request->query('project_term_id'), function($q, $ptid){
            $q->whereHas('project_term', fn($qb)=>$qb->where('id', $ptid));
        })
        ->latest('id')
        ->get();
        return response()->json($assignments);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'project_id' => 'required|integer|exists:projects,id',
            'status' => 'required|string|max:100',
            'supervisor_ids' => 'sometimes|array',
            'supervisor_ids.*' => 'integer|exists:supervisors,id',
        ]);

        $assignment = Assignment::create([
            'student_id' => $data['student_id'],
            'project_id' => $data['project_id'],
            'status' => $data['status'],
        ]);

        if (!empty($data['supervisor_ids'])) {
            $assignment->supervisors()->sync($data['supervisor_ids']);
        }

        return response()->json(
            $assignment->load([
                'student.user',
                'project_term.academy_year',
                'assignment_supervisors.supervisor.teacher.user',
                'project.progressLogs.attachments',
            ]),
            201
        );
    }

    public function getAssignmentByStudentId($studentId)
    {
        $assignment = Assignment::with([
                'student.user',
                'assignment_supervisors.supervisor.teacher.user',
                'project.progressLogs.attachments',
            ])
            ->whereHas('student', function ($query) use ($studentId) {
                $query->where('id', $studentId);
            })
            ->first();

        return response()->json($assignment);
    }


    public function getAssignmentByStudentIdAndProjectTermId($studentId, $projectTermId)
    {
        $assignment = Assignment::with([
            'student.user',
            'project_term.academy_year',
            'project_term.stageTimelines',
            'assignment_supervisors.supervisor.teacher.user',
            'project.progressLogs.attachments',
            'project.reportFiles',
            'council_project.council_project_defences.council_member.supervisor.teacher.user',
            'council_project.council.council_members',
            'council_project.council.council_members.supervisor.teacher.user',
            ])
            ->where('student_id', $studentId)
            ->where('project_term_id', $projectTermId)
            ->first();

        return response()->json($assignment);
    }


    public function show(Assignment $assignment)
    {
        return response()->json(
            $assignment->load([
                'student.user',
                'project_term.academy_year',
                'assignment_supervisors.supervisor.teacher.user',
                'project.progressLogs.attachments',
            ])
        );
    }

    public function update(Request $request, Assignment $assignment)
    {
        $data = $request->validate([
            'student_id' => 'sometimes|integer|exists:students,id',
            'project_id' => 'sometimes|integer|exists:projects,id',
            'status' => 'sometimes|string|max:100',
            'supervisor_ids' => 'sometimes|array',
            'supervisor_ids.*' => 'integer|exists:supervisors,id',
        ]);

        $assignment->update(collect($data)->only(['student_id','project_id', 'project_term_id','status'])->toArray());

        if (array_key_exists('supervisor_ids', $data)) {
            $assignment->supervisors()->sync($data['supervisor_ids'] ?? []);
        }

        return response()->json(
            $assignment->load([
                'student.user',
                'assignment_supervisors.supervisor.teacher.user',
                'project.progressLogs.attachments',
            ])
        );
    }

    public function updateProjectIdAssignmentByAssIdAndProId($assignmentId, $projectId) {
        $assignment = Assignment::findOrFail($assignmentId);
        $assignment->update(['project_id' => $projectId]);
        return response()->json($assignment);
    }

    public function destroy(Assignment $assignment)
    {
        // detach any linked supervisors in pivot before delete
        $assignment->supervisors()->detach();
        $assignment->delete();
        return response()->json(['message'=>'Deleted']);
    }

    public function getRecentAssignmentByStudentId(String $studentId) {
        $assignment = Assignment::with([
            'student.user',
            'project_term.academy_year',
            'project_term.stageTimelines',
            'assignment_supervisors.supervisor.teacher.user',
            'project.progressLogs.attachments',
            'project.reportFiles',
            'council_project.council_project_defences.council_member.supervisor.teacher.user',
            'council_project.council.council_members',
            'council_project.council.council_members.supervisor.teacher.user',
        ])
        ->where('student_id', $studentId)
        ->latest('id')
        ->first();
        return response()->json($assignment);
    }

    public function getAssignmentById($assignmentId) {
        $assignment = Assignment::with([
            'student.user',
            'project_term.academy_year',
            'project_term.stageTimelines',
            'assignment_supervisors.supervisor.teacher.user',
            'project.progressLogs.attachments',
            'project.reportFiles',
            'council_project.council_project_defences.council_member.supervisor.teacher.user',
            'council_project.council.council_members',
            'council_project.council.council_members.supervisor.teacher.user',
        ])->findOrFail($assignmentId);
        return response()->json($assignment);
    }
}
