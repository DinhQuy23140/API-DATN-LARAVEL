<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BatchStudent;
use Illuminate\Http\Request;

class BatchStudentController extends Controller
{
    public function index(Request $request)
    {
        $items = BatchStudent::with(['student.user', 'assignments', 'project_term.academy_year'])
            ->latest('id')->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'project_term_id' => 'required|integer|exists:project_terms,id',
            'status' => 'required|string|max:100',
        ]);
        $item = BatchStudent::create($data);
        return response()->json($item->load(['student.user','project_term.academy_year']),201);
    }

    public function show(BatchStudent $batch_student)
    {
        return response()->json($batch_student->load(['student.user','project_term.academy_year']));
    }

    public function update(Request $request, BatchStudent $batch_student)
    {
        $data = $request->validate([
            'student_id' => 'sometimes|integer|exists:students,id',
            'project_term_id' => 'sometimes|integer|exists:project_terms,id',
            'status' => 'sometimes|string|max:100',
        ]);
        $batch_student->update($data);
        return response()->json($batch_student->load(['student.user','project_term.academy_year']));
    }

    public function destroy(BatchStudent $batch_student)
    {
        $batch_student->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
