<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Supervisor;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    public function index(){
        $supervisors = Supervisor::with('teacher.user')->latest('id')->paginate(15);
        return view('supervisors.index', compact('supervisors'));
    }
    public function create(){return view('supervisors.create',['supervisor'=>new Supervisor(),'teachers'=>Teacher::with('user')->get()]);}
    public function store(Request $request){
        $data=$request->validate([
            'teacher_id'=>'required|exists:teachers,id',
            'max_students'=>'required|integer|min:1',
            'expertise'=>'nullable|string'
        ]);
        $sp=Supervisor::create($data);
        return redirect()->route('web.supervisors.show',$sp)->with('status','Tạo thành công');
    }
    public function show(Supervisor $supervisor){$supervisor->load('teacher.user'); return view('supervisors.show', compact('supervisor'));}
    public function edit(Supervisor $supervisor){return view('supervisors.edit',['supervisor'=>$supervisor,'teachers'=>Teacher::with('user')->get()]);}
    public function update(Request $request, Supervisor $supervisor){
        $data=$request->validate([
            'teacher_id'=>'sometimes|exists:teachers,id',
            'max_students'=>'sometimes|integer|min:1',
            'expertise'=>'nullable|string'
        ]);
        $supervisor->update($data);
        return redirect()->route('web.supervisors.show',$supervisor)->with('status','Cập nhật thành công');
    }
    public function destroy(Supervisor $supervisor){$supervisor->delete(); return redirect()->route('web.supervisors.index')->with('status','Đã xóa');}
}
