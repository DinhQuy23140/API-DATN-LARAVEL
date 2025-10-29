<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $withBatch = filter_var($request->query('with_batch'), FILTER_VALIDATE_BOOL);
        $students = Student::with(array_filter([
            'user',
            $withBatch ? 'assignments.project_term.academy_year' : null,
        ]))->paginate(15);
        return response()->json($students);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'student_code' => 'required|string|max:100',
            'class_code' => 'nullable|string|max:100',
            'major_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'course_year' => 'nullable|integer'
        ]);
        $student = Student::create($data);
        return response()->json($student->load('user'),201);
    }

    public function show(Student $student)
    {
        return response()->json($student->load([
            'user','marjor.department.faculties']));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'user_id' => 'sometimes|integer|exists:users,id',
            'student_code' => 'sometimes|string|max:100',
            'class_code' => 'nullable|string|max:100',
            'major_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'course_year' => 'nullable|integer'
        ]);
        $student->update($data);
        return response()->json($student->load('user'));
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(['message'=>'Deleted']);
    }

    public function getProjectTermbyStudentId(String $studentId) {
    }
}
