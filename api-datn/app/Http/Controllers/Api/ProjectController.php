<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Assignment;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $projects = Project::query()
            ->when($q, fn($query)=>$query->where('name','like',"%{$q}%"))
            ->latest('id')
            ->paginate(15);
        return response()->json($projects);
    }

    public function store($assignmentId, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $assignment = Assignment::find($assignmentId);

        if (!$assignment) {
            return response()->json(['message' => 'Assignment not found.'], 404);
        }

        // Nếu assignment chưa có project → tạo mới
        if (!$assignment->project_id) {
            $project = Project::create($data);

            // Gán project_id cho assignment và lưu lại
            $assignment->project_id = $project->id;
            $assignment->save();

            $message = 'New project created and linked to assignment.';
        } 
        // Nếu đã có project → cập nhật project đó
        else {
            $project = $assignment->project;
            $project->update($data);

            $message = 'Existing project updated.';
        }

        return response()->json([
            $assignment
        ], 201);
    }

    public function updateOrCreateProject($assignmentId, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $assignment = Assignment::find($assignmentId);

        if (!$assignment) {
            return response()->json(['message' => 'Assignment not found.'], 404);
        }

        // Nếu assignment chưa có project → tạo mới
        if (!$assignment->project_id) {
            $project = Project::create($data);
            $assignment->project_id = $project->id;
            $assignment->save();
        } 
        // Nếu đã có project → cập nhật project đó
        else {
            $project = $assignment->project;
            $project->update($data);
        }

        // 🔹 Trả về JSON của assignment (kèm quan hệ project nếu có)
        $assignment->load('project');

        return response()->json($assignment, 201);
    }

    public function show(Project $project)
    {
        return response()->json($project);
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string'
        ]);
        $project->update($data);
        return response()->json($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
