<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AcademyYear;
use App\Models\ProjectTerm;
use Illuminate\Http\Request;

class ProjectTermsController extends Controller
{
    public function index(){ $terms=ProjectTerm::with('academy_year')->latest('id')->paginate(15); return view('project_terms.index',compact('terms')); }
    public function create(){ return view('project_terms.create',['term'=>new ProjectTerm(),'years'=>AcademyYear::all()]); }
    public function store(Request $request){ $data=$request->validate(['academy_year_id'=>'required|exists:academy_years,id','term_name'=>'required|string|max:255','start_date'=>'required|date','end_date'=>'required|date|after_or_equal:start_date']); $t=ProjectTerm::create($data); return redirect()->route('web.project_terms.show',$t)->with('status','Tạo thành công'); }
    public function show(ProjectTerm $project_term){ return view('project_terms.show',compact('project_term')); }
    public function edit(ProjectTerm $project_term){ return view('project_terms.edit',['term'=>$project_term,'years'=>AcademyYear::all()]); }
    public function update(Request $request, ProjectTerm $project_term){ $data=$request->validate(['academy_year_id'=>'sometimes|exists:academy_years,id','term_name'=>'sometimes|string|max:255','start_date'=>'sometimes|date','end_date'=>'sometimes|date|after_or_equal:start_date']); $project_term->update($data); return redirect()->route('web.project_terms.show',$project_term)->with('status','Cập nhật thành công'); }
    public function destroy(ProjectTerm $project_term){ $project_term->delete(); return redirect()->route('web.project_terms.index')->with('status','Đã xóa'); }
}
