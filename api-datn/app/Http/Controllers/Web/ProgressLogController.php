<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ProgressLog;
use App\Models\Student;
use Illuminate\Http\Request;

class ProgressLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ProgressLog::withCount('attachments')
            ->latest('id')
            ->paginate(15);
        return view('progress_logs.index', compact('logs'));
    }

    public function create()
    {
        $log = new ProgressLog();
        return view('progress_logs.create', compact('log'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date_time' => 'required|date',
            'end_date_time' => 'required|date|after_or_equal:start_date_time',
            'student_status' => 'required|string',
            'instructor_status' => 'nullable|string',
            'instructor_comment' => 'nullable|string',
        ]);
        $log = ProgressLog::create($data);
        return redirect()->route('web.progress_logs.show', $log)->with('status','Tạo thành công');
    }

    public function show(ProgressLog $progress_log)
    {
        $progress_log->load('attachments');
        return view('progress_logs.show', ['log' => $progress_log]);
    }

    public function edit(ProgressLog $progress_log)
    {
        return view('progress_logs.edit', ['log' => $progress_log]);
    }

    public function update(Request $request, ProgressLog $progress_log)
    {
        $data = $request->validate([
            'project_id' => 'sometimes|integer',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_date_time' => 'sometimes|date',
            'end_date_time' => 'sometimes|date|after_or_equal:start_date_time',
            'student_status' => 'sometimes|string',
            'instructor_status' => 'nullable|string',
            'instructor_comment' => 'nullable|string',
        ]);
        $progress_log->update($data);
        return redirect()->route('web.progress_logs.show', $progress_log)->with('status','Cập nhật thành công');
    }

    public function destroy(ProgressLog $progress_log)
    {
        $progress_log->delete();
        return redirect()->route('web.progress_logs.index')->with('status','Đã xóa');
    }

    public function getProgressLogByIdAndStudentId($id, $studentId)
    {
        $progress_log = ProgressLog::with('attachments')->find($id);
        $student = Student::with('user')->find($studentId);
        return view('lecturer-ui.weekly-log-detail', compact('progress_log', 'student'));
    }
}
