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
            'content' => 'nullable|string',
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

    public function getProgressLogById($supervisorId, $progressLogId)
    {
        $progress_log = ProgressLog::with([
            'attachments',
            'commentLogs' => function ($query) {
                $query->orderBy('created_at', 'desc'); // 🔹 sắp xếp giảm dần
            },
            'project.assignment' => function ($query) {
                $query->with([
                    'project_term.academy_year',
                    'student.user',
                    'assignment_supervisors.supervisor.teacher.user'
                ]);
            }
        ])->findOrFail($progressLogId);

        return view('lecturer-ui.weekly-log-detail', compact('progress_log', 'supervisorId'));
    }

    /**
     * Update only the instructor_status of a ProgressLog (AJAX from lecturer UI)
     * Expects JSON: { status: 'approved'|'not_achieved'|'need_editing' }
     */
    public function updateInstructorStatus(Request $request, ProgressLog $progress_log)
    {
        $this->middleware('auth');

        $data = $request->validate([
            'status' => ['required','string','in:approved,not_achieved,need_editing']
        ]);

        // Basic authorization: only authenticated teachers can update instructor status.
        $user = auth()->user();
        if (!$user || !$user->teacher) {
            return response()->json(['ok' => false, 'message' => 'Không có quyền.'], 403);
        }

        $progress_log->instructor_status = $data['status'];
        $progress_log->save();

        return response()->json(['ok' => true, 'status' => $progress_log->instructor_status]);
    }

}
