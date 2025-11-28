<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
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
            'user_id' => 'string|exists:users,id',
            'student_id' => 'nullable|string|max:100',
            'student_code' => 'nullable|string|max:100',
            'full_name' => 'nullable|string|max:255',
            'dob' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'class_code' => 'nullable|string|max:100',
            'marjor_id' => 'nullable|integer',
        ]);
        $student->student_code = $data['student_code'] ?? $student->student_code;
        $student->class_code = $data['class_code'] ?? $student->class_code;
        $student->marjor_id = $data['marjor_id'] ?? $student->marjor_id;
        $student->save();

        $user = User::find($student->user_id);
        if ($user) {
            $user->fullname = $data['full_name'] ?? $user->full_name;
            $user->dob = $data['dob'] ?? $user->dob;
            $user->phone = $data['phone'] ?? $user->phone;
            $user->address = $data['address'] ?? $user->address;
            $user->save();
        }
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
