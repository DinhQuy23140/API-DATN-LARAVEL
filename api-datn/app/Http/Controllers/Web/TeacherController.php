<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(){
        $teachers = Teacher::with('user', 'department')->latest('id')->paginate(15);
        return view('admin-ui.manage-lecturers', compact('teachers'));
    }
    public function loadTeachers(){
        $teachers = Teacher::with('user.facultyRoles', 'departmentRoles')->latest('id')->get();
        return view('assistant-ui.manage-staff', compact('teachers'));
    }
    public function create(){return view('teachers.create',['teacher'=>new Teacher(),'users'=>User::all()]);}
    public function store(Request $request){
        $data=$request->validate([
            'user_id'=>'required|exists:users,id',
            'teacher_code'=>'required|string|max:100',
            'degree'=>'nullable|string|max:100',
            'department_id'=>'nullable|integer',
            'position'=>'nullable|string|max:100',
            'faculties_id'=>'nullable|integer'
        ]);
        $t=Teacher::create($data);
        return redirect()->route('web.teachers.show',$t)->with('status','Tạo thành công');
    }
    public function show(Teacher $teacher){$teacher->load('user'); return view('teachers.show', compact('teacher'));}
    public function edit(Teacher $teacher){return view('teachers.edit',['teacher'=>$teacher,'users'=>User::all()]);}
    public function update(Request $request, Teacher $teacher){
        $data=$request->validate([
            'user_id'=>'sometimes|exists:users,id',
            'teacher_code'=>'sometimes|string|max:100',
            'degree'=>'nullable|string|max:100',
            'department_id'=>'nullable|integer',
            'position'=>'nullable|string|max:100',
            'faculties_id'=>'nullable|integer'
        ]);
        $teacher->update($data);
        return redirect()->route('web.teachers.show',$teacher)->with('status','Cập nhật thành công');
    }
    public function destroy(Teacher $teacher){$teacher->delete(); return redirect()->route('web.teachers.index')->with('status','Đã xóa');}

    public function loadDashboardAssistant(){
        $countTeachers = Teacher::count();
        $teachers = Teacher::with('user')->latest('created_at')->paginate(7);
        return view('assistant-ui.dashboard', compact('countTeachers', 'teachers'));
    }

    public function loadProfile($teacherId){
        $teacher = Teacher::with('user')->findOrFail($teacherId);
        return view('head-ui.profile', compact('teacher'));
    }
}
