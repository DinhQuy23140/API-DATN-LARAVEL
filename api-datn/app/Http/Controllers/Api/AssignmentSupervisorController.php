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

    public function store(Request $request) {
        $data = $request->validate([
            'assignment_id' => 'required|integer|exists:assignments,id',
            'supervisor_id' => 'required|integer|exists:supervisors,id',
            'role' => 'required|string|max:255',
        ]);

        if(empty($data['role'])) $data['role'] = 'main';

        $assignmentSupervisor = AssignmentSupervisor::create($data);
        return response()->json($assignmentSupervisor->load('assignment'), 201);
    }
}
