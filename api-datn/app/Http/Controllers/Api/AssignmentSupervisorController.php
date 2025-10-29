<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSupervisor;
use Illuminate\Http\Request;

class AssignmentSupervisorController extends Controller
{
    //
    public function index() {
        $data = AssignmentSupervisor::with('supervisor.teacher.user')->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'assignment_id' => 'required|integer|exists:assignments,id',
            'supervisor_id' => 'required|integer|exists:supervisors,id',
            'role' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        // Mặc định role là 'main' nếu không gửi
        if (empty($data['role'])) {
            $data['role'] = 'main';
        }

        // Cập nhật nếu tồn tại, tạo mới nếu chưa
        $assignmentSupervisor = AssignmentSupervisor::updateOrCreate(
            [
                'assignment_id' => $data['assignment_id'],
                'supervisor_id' => $data['supervisor_id'],
            ],
            [
                // 'role' => $data['role'],
                'status' => $data['status'] ?? 'pending', // gán mặc định nếu cần
            ]
        );

        return response()->json($assignmentSupervisor->load('assignment'), 201);
    }

    public function getAssignmentSupervisorsByTeacherId($teacher_id) {
        $data = AssignmentSupervisor::whereHas('supervisor.teacher', function($q) use ($teacher_id) {
            $q->where('id', $teacher_id);
        })->with([
            'assignment.student.user',
            'assignment.project_term.academy_year',
            'assignment.assignment_supervisors.supervisor.teacher.user',
            'assignment.project.progressLogs.attachments',
            'assignment.project.reportFiles',
            'assignment.council_project.council_project_defences.council_member.supervisor.teacher.user',
            'assignment.council_project.council.department',
            'assignment.council_project.council_member.supervisor.teacher.user',
            'assignment.council_project.council.council_members.supervisor.teacher.user',
        ])->get();

        return response()->json($data);
    }
}
