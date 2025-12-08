<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Marjor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index(){
        $students = Student::with('user', 'marjor')->latest('id')->get();
        $majors = Marjor::all();
        return view('admin-ui.manage-students', compact('students', 'majors'));
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
        // Validate both student fields and optional nested user fields
        $validated = $request->validate([
            'user_id'=>'sometimes|exists:users,id',
            'student_code'=>'sometimes|string|max:100',
            'class_code'=>'nullable|string|max:100',
            'marjor_id'=>'nullable|integer',
            'course_year'=>'nullable|integer',

            // optional user fields
            'fullname' => 'sometimes|string|max:255',
            'email' => ['sometimes','email','max:255', Rule::unique('users','email')->ignore($student->user->id ?? null)],
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:6'
        ]);

        try {
            $updated = DB::transaction(function() use ($validated, $student, $request) {
                // Update student fields
                $studentData = array_filter($validated, function($k){
                    return in_array($k, ['user_id','student_code','class_code','marjor_id','course_year']);
                }, ARRAY_FILTER_USE_KEY);
                if(!empty($studentData)){
                    $student->update($studentData);
                }

                // Update related user if present
                $userFields = array_filter($validated, function($k){
                    return in_array($k, ['fullname','email','dob','phone','password']);
                }, ARRAY_FILTER_USE_KEY);

                if($userFields && $student->user){
                    if(isset($userFields['password']) && $userFields['password']){
                        $userFields['password'] = Hash::make($userFields['password']);
                    } else {
                        unset($userFields['password']);
                    }
                    $student->user->update($userFields);
                }

                // reload relations for response
                $student->load('user','marjor');
                return $student;
            });

            if ($request->wantsJson()) {
                return response()->json(['ok'=>true,'student'=>$updated], 200);
            }

            return redirect()->route('web.students.show',$student)->with('status','Cập nhật thành công');
        } catch (\Exception $e) {
            Log::error('Student update failed: '.$e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['ok'=>false,'message'=>'Lỗi khi cập nhật sinh viên'], 500);
            }
            return back()->with('error','Lỗi khi cập nhật sinh viên');
        }
    }
    public function destroy(Student $student){$student->delete(); return redirect()->route('web.students.index')->with('status','Đã xóa');}
}
