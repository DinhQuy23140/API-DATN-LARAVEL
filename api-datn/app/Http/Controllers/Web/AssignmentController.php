<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\Project;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(){
        $assignments = Assignment::with(['student.user','supervisor.teacher','project'])->latest('id')->paginate(15);
        return view('assignments.index', compact('assignments'));
    }
    public function create(){
        return view('assignments.create', [
            'assignment'=> new Assignment(),
            'students'=>Student::with('user')->get(),
            'supervisors'=>Supervisor::with('teacher')->get(),
            'projects'=>Project::all()
        ]);
    }
    public function store(Request $request){
        $data=$request->validate([
            'student_id'=>'required|exists:students,id',
            'supervisor_id'=>'required|exists:supervisors,id',
            'project_id'=>'required|exists:projects,id',
            'status'=>'required|string|max:100'
        ]);
        $a=Assignment::create($data);
        return redirect()->route('web.assignments.show',$a)->with('status','Tạo thành công');
    }
    public function show(Assignment $assignment){$assignment->load(['student.user','supervisor.teacher','project']); return view('assignments.show', compact('assignment'));}
    public function edit(Assignment $assignment){
        return view('assignments.edit', [
            'assignment'=>$assignment,
            'students'=>Student::with('user')->get(),
            'supervisors'=>Supervisor::with('teacher')->get(),
            'projects'=>Project::all()
        ]);
    }
    public function update(Request $request, Assignment $assignment){
        $data=$request->validate([
            'student_id'=>'sometimes|exists:students,id',
            'supervisor_id'=>'sometimes|exists:supervisors,id',
            'project_id'=>'sometimes|exists:projects,id',
            'status'=>'sometimes|string|max:100'
        ]);
        $assignment->update($data);
        return redirect()->route('web.assignments.show',$assignment)->with('status','Cập nhật thành công');
    }
    public function destroy(Assignment $assignment){$assignment->delete(); return redirect()->route('web.assignments.index')->with('status','Đã xóa');}
}
