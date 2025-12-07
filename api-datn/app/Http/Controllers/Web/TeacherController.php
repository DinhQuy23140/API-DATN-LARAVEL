<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Marjor;
use App\Models\ProjectTerm;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(){
        $teachers = Teacher::with('user', 'department')->latest('id')->paginate(15);
        $departments = Department::all();
        return view('admin-ui.manage-lecturers', compact('teachers', 'departments'));
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
        ]);
        $t=Teacher::create($data);
        return redirect()->route('web.teachers.show',$t)->with('status','Tạo thành công');
    }
    public function show(Teacher $teacher){$teacher->load('user'); return view('teachers.show', compact('teacher'));}
    public function edit(Teacher $teacher){return view('teachers.edit',['teacher'=>$teacher,'users'=>User::all()]);}
    public function update(Request $request, Teacher $teacher){
        // Validate teacher fields and optional nested user fields
        $user = $teacher->user;

        $data = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'teacher_code' => 'sometimes|string|max:100',
            'degree' => 'nullable|string|max:100',
            'department_id' => 'nullable|integer',
            'position' => 'nullable|string|max:100',

            // optional user fields
            'fullname' => ['sometimes','required','string','max:255'],
            'email' => ['sometimes','required','email','max:255', Rule::unique('users','email')->ignore($user->id ?? null)],
            'dob' => ['nullable','date'],
            'address' => ['nullable','string','max:500'],
            'password' => ['nullable','string','min:6'],
        ]);

        try {
            DB::transaction(function() use ($teacher, $data, $user) {
                // Teacher updates
                $teacherUpdates = array_intersect_key($data, array_flip(['teacher_code','degree','department_id','position','user_id']));
                if (!empty($teacherUpdates)) {
                    $teacher->update($teacherUpdates);
                }

                // Update related user if exists
                if ($user) {
                    $userUpdates = array_intersect_key($data, array_flip(['fullname','email','dob','address','password']));
                    if (!empty($userUpdates)) {
                        if (!empty($userUpdates['password'])) {
                            $userUpdates['password'] = Hash::make($userUpdates['password']);
                        } else {
                            unset($userUpdates['password']);
                        }
                        $user->update($userUpdates);
                    }
                }
            });
        } catch (\Throwable $e) {
            \Log::error('Failed to update teacher/user: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['ok' => false, 'message' => 'Cập nhật thất bại'], 500);
            }
            return redirect()->back()->withErrors('Cập nhật thất bại');
        }

        $teacher->load('user','department');

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'teacher' => $teacher]);
        }

        return redirect()->route('web.teachers.show',$teacher)->with('status','Cập nhật thành công');
    }
    public function destroy(Teacher $teacher){$teacher->delete(); return redirect()->route('web.teachers.index')->with('status','Đã xóa');}

    public function loadDashboardAssistant(){
        $countTeachers = Teacher::count();
        $departmentCount = Department::count();
        $majorCount = Marjor::count();
        $projectTerms = ProjectTerm::with('academy_year', 'councils')->where(function ($q) {
        // Đang diễn ra
        $q->where('start_date', '<=', now())
          ->where('end_date', '>=', now());
        })
        ->orWhere(function ($q) {
            // Sắp diễn ra
            $q->where('start_date', '>', now());
        })
        ->orderBy('start_date', 'asc')
        ->get();
        $teachers = Teacher::with('user')->latest('created_at')->paginate(7);
        return view('assistant-ui.dashboard', compact('countTeachers', 'teachers', 'departmentCount', 'majorCount', 'projectTerms'));
    }

    public function loadProfile($teacherId){
        $teacher = Teacher::with('user')->findOrFail($teacherId);
        return view('head-ui.profile', compact('teacher'));
    }
}
