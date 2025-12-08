<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProgressLog;
use Illuminate\Http\Request;

class ProgressLogController extends Controller
{
    public function index()
    {
        $logs = ProgressLog::with('attachments')->get();
        return response()->json($logs);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|string|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date_time' => 'required|date|string',
            'end_date_time' => 'required|date|string|after_or_equal:start_date_time',
            'student_status' => 'required|string',
            'instructor_status' => 'nullable|string',
            'instructor_comment' => 'nullable|string',
            'content' => 'nullable|string',
        ]);

        $log = ProgressLog::create($data);
        return response()->json($log, 201);
    }

    public function show(ProgressLog $progressLog)
    {
        return response()->json($progressLog->load('attachments'));
    }

    // Lấy danh sách progress logs theo project_id
    public function getProjectLogByIdProject($projectId)
    {
        $logs = ProgressLog::where('project_id', $projectId)
            ->with('attachments', 'commentLogs.supervisor.teacher.user')
            ->get();
        return response()->json($logs);
    }

    public function update(Request $request, ProgressLog $progressLog)
    {
        $data = $request->validate([
            'project_id' => 'sometimes|integer|exists:projects,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_date_time' => 'sometimes|date',
            'end_date_time' => 'sometimes|date|after_or_equal:start_date_time',
            'student_status' => 'sometimes|string',
            'instructor_status' => 'nullable|string',
            'instructor_comment' => 'nullable|string',
        ]);

        $progressLog->update($data);

        return response()->json($progressLog->load('attachments'));
    }

    public function destroy(ProgressLog $progressLog)
    {
        $progressLog->delete();
        return response()->json(['message' => 'Deleted successfully.']);
    }
}
