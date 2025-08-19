<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request){
        $q = $request->query('q');
        $projects = Project::when($q,fn($qr)=>$qr->where('name','like',"%{$q}%"))->latest('id')->paginate(15)->appends($request->query());
        return view('projects.index', compact('projects','q'));
    }
    public function create(){ $project = new Project(); return view('projects.create', compact('project')); }
    public function store(Request $request){
        $data=$request->validate(['name'=>'required|string|max:255','description'=>'nullable|string']);
        $p=Project::create($data);
        return redirect()->route('web.projects.show',$p)->with('status','Tạo thành công');
    }
    public function show(Project $project){return view('projects.show', compact('project'));}
    public function edit(Project $project){return view('projects.edit', compact('project'));}
    public function update(Request $request, Project $project){
        $data=$request->validate(['name'=>'sometimes|string|max:255','description'=>'nullable|string']);
        $project->update($data);
        return redirect()->route('web.projects.show',$project)->with('status','Cập nhật thành công');
    }
    public function destroy(Project $project){$project->delete(); return redirect()->route('web.projects.index')->with('status','Đã xóa');}
}
