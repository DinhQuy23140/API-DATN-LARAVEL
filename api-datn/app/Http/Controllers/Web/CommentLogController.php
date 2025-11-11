<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommentLog;
use App\Models\ProgressLog;
use Illuminate\Support\Facades\Auth;

class CommentLogController extends Controller
{
    /**
     * Store a new comment for a progress log.
     * Accepts JSON or form POST with 'content'.
     */
    public function store(Request $request, ProgressLog $progress_log)
    {
        $data = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
            'supervisor_id' => ['required', 'integer', 'exists:supervisors,id'],
        ]);

        $supervisorId = $data['supervisor_id'];

        // Ensure that the supervisor_id belongs to the assignment related to this progress log
        $assignment = optional($progress_log->project)->assignment;
        if (!$assignment) {
            return response()->json(['ok' => false, 'message' => 'Không tìm thấy assignment cho nhật ký này.'], 400);
        }

        $isLinked = $assignment->assignment_supervisors()->where('supervisor_id', $supervisorId)->exists();
        if (!$isLinked) {
            return response()->json(['ok' => false, 'message' => 'Supervisor không được liên kết với assignment này.'], 403);
        }

        $comment = CommentLog::create([
            'progress_log_id' => $progress_log->id,
            'supervisor_id' => $supervisorId,
            'content' => $data['content'],
        ]);

        return response()->json([
            'ok' => true,
            'comment' => $comment,
        ], 201);
    }
}
