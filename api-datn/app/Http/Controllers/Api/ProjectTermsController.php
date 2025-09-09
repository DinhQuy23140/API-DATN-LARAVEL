<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectTerm;
use Illuminate\Http\Request;

class ProjectTermsController extends Controller
{
    public function index(Request $request)
    {
        $terms = ProjectTerm::with('academy_year')
            ->latest('id')
            ->get();
        return response()->json($terms);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'academy_year_id' => 'required|integer|exists:academy_years,id',
            'stage' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string|max:255',
        ]);
        $term = ProjectTerm::create($data);
        return response()->json($term->load('academy_year'),201);
    }

    public function show(ProjectTerm $project_term)
    {
        return response()->json($project_term->load('academy_year'));
    }

    public function update(Request $request, ProjectTerm $project_term)
    {
        $data = $request->validate([
            'academy_year_id' => 'sometimes|integer|exists:academy_years,id',
            'stage' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'status' => 'sometimes|string|max:255',
        ]);
        $project_term->update($data);
        return response()->json($project_term->load('academy_year'));
    }

    public function destroy(ProjectTerm $project_term)
    {
        $project_term->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function getProjectTermbyStudentId($studentId) {
    $terms = ProjectTerm::with([
            'academy_year',                // load năm học
            'assignments' => function($q) use ($studentId) {
                $q->where('student_id', $studentId) // lọc assignment đúng student_id
                  ->with('student');               // load luôn thông tin student
            }
        ])
        ->whereHas('assignments', function($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })
        ->get();
        return response()->json($terms);
    }
}
