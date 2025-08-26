<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BatchStudent;
use App\Models\ProjectTerm;
use App\Models\Student;
use App\Models\Project_terms;
use Illuminate\Http\Request;

class BatchStudentController extends Controller
{
    public function index(){ $items=BatchStudent::with(['student.user','project_term.academy_year'])->latest('id')->paginate(15); return view('batch_students.index',compact('items')); }
    public function create(){ return view('batch_students.create',['item'=>new BatchStudent(),'students'=>Student::with('user')->get(),'terms'=>ProjectTerm::with('academy_year')->get()]); }
    public function store(Request $request){ $data=$request->validate(['student_id'=>'required|exists:students,id','project_terms_id'=>'required|exists:project_terms,id','status'=>'required|string|max:100']); $i=BatchStudent::create($data); return redirect()->route('web.batch_students.show',$i)->with('status','Tạo thành công'); }
    public function show(BatchStudent $batch_student){ return view('batch_students.show',['item'=>$batch_student->load(['student.user','project_term.academy_year'])]); }
    public function edit(BatchStudent $batch_student){ return view('batch_students.edit',['item'=>$batch_student,'students'=>Student::with('user')->get(),'terms'=>ProjectTerm::with('academy_year')->get()]); }
    public function update(Request $request, BatchStudent $batch_student){ $data=$request->validate(['student_id'=>'sometimes|exists:students,id','project_terms_id'=>'sometimes|exists:project_terms,id','status'=>'sometimes|string|max:100']); $batch_student->update($data); return redirect()->route('web.batch_students.show',$batch_student)->with('status','Cập nhật thành công'); }
    public function destroy(BatchStudent $batch_student){ $batch_student->delete(); return redirect()->route('web.batch_students.index')->with('status','Đã xóa'); }
}
