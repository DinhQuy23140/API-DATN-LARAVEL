<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(){
        $students = Student::with('user', 'marjor')->latest('id')->paginate(15);
        return view('admin-ui.manage-students', compact('students'));
    }
    public function create(){
        return view('students.create', ['student'=>new Student(),'users'=>User::all()]);
    }
    public function store(Request $request){
        $data=$request->validate([
            'user_id'=>'required|exists:users,id',
            'student_code'=>'required|string|max:100',
            'class_code'=>'nullable|string|max:100',
            'major_id'=>'nullable|integer',
            'department_id'=>'nullable|integer',
            'course_year'=>'nullable|integer'
        ]);
        $s=Student::create($data);
        return redirect()->route('web.students.show',$s)->with('status','Tạo thành công');
    }
    public function show(Student $student){$student->load('user'); return view('students.show', compact('student'));}
    public function edit(Student $student){return view('students.edit', ['student'=>$student,'users'=>User::all()]);}
    public function update(Request $request, Student $student){
        $data=$request->validate([
            'user_id'=>'sometimes|exists:users,id',
            'student_code'=>'sometimes|string|max:100',
            'class_code'=>'nullable|string|max:100',
            'major_id'=>'nullable|integer',
            'department_id'=>'nullable|integer',
            'course_year'=>'nullable|integer'
        ]);
        $student->update($data);
        return redirect()->route('web.students.show',$student)->with('status','Cập nhật thành công');
    }
    public function destroy(Student $student){$student->delete(); return redirect()->route('web.students.index')->with('status','Đã xóa');}
}
