<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $assignments = Assignment::with(['student','supervisor','project'])->paginate(15);
        return response()->json($assignments);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'supervisor_id' => 'required|integer|exists:supervisors,id',
            'project_id' => 'required|integer|exists:projects,id',
            'status' => 'required|string|max:100'
        ]);
        $assignment = Assignment::create($data);
        return response()->json($assignment->load(['student','supervisor','project']),201);
    }

    public function show(Assignment $assignment)
    {
        return response()->json($assignment->load(['student','supervisor','project']));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $data = $request->validate([
            'student_id' => 'sometimes|integer|exists:students,id',
            'supervisor_id' => 'sometimes|integer|exists:supervisors,id',
            'project_id' => 'sometimes|integer|exists:projects,id',
            'status' => 'sometimes|string|max:100'
        ]);
        $assignment->update($data);
        return response()->json($assignment->load(['student','supervisor','project']));
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
