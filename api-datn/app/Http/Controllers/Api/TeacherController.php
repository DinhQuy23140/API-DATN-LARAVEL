<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $teachers = Teacher::with('user')->paginate(15);
        return response()->json($teachers);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'teacher_code' => 'required|string|max:100',
            'degree' => 'nullable|string|max:100',
            'department_id' => 'nullable|integer',
            'position' => 'nullable|string|max:100',
            'faculties_id' => 'nullable|integer'
        ]);
        $teacher = Teacher::create($data);
        return response()->json($teacher->load('user'),201);
    }

    public function show(Teacher $teacher)
    {
        return response()->json($teacher->load('user'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'user_id' => 'sometimes|integer|exists:users,id',
            'teacher_code' => 'sometimes|string|max:100',
            'degree' => 'nullable|string|max:100',
            'department_id' => 'nullable|integer',
            'position' => 'nullable|string|max:100',
            'faculties_id' => 'nullable|integer'
        ]);
        $teacher->update($data);
        return response()->json($teacher->load('user'));
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
