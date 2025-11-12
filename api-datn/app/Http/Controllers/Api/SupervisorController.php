<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supervisor;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    public function index(Request $request)
    {
        $supervisors = Supervisor::with(['teacher.user.userResearches.research','project_term.academy_year'])->get();
        return response()->json($supervisors);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => 'required|integer|exists:teachers,id',
            'project_term_id' => 'required|integer|exists:project_terms,id',
            'max_students' => 'required|integer|min:1',
            'expertise' => 'nullable|string',
            'status' => 'required|string|max:255'
        ]);
    $supervisor = Supervisor::create($data);
    return response()->json($supervisor->load(['teacher.user','project_term.academy_year']),201);
    }

    public function show(Supervisor $supervisor)
    {
    return response()->json($supervisor->load(['teacher.user','project_term.academy_year']));
    }

    public function update(Request $request, Supervisor $supervisor)
    {
        $data = $request->validate([
            'teacher_id' => 'sometimes|integer|exists:teachers,id',
            'project_term_id' => 'sometimes|integer|exists:project_terms,id',
            'max_students' => 'sometimes|integer|min:1',
            'expertise' => 'nullable|string',
            'status' => 'sometimes|string|max:255'
        ]);
        $supervisor->update($data);
        return response()->json($supervisor->load(['teacher.user','project_term.academy_year']));
    }

    public function destroy(Supervisor $supervisor)
    {
        $supervisor->delete();
        return response()->json(['message'=>'Deleted']);
    }

    public function getSupervisorsByProjectTerm($projectTermId) {
        $supervisors = Supervisor::with(['teacher.user'])
            ->where('project_term_id', $projectTermId)
            ->get();
        return response()->json($supervisors);
    }
}
