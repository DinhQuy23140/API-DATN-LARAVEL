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
        ]);

        $user = Auth::user();
        $supervisorId = null;
        // Try to resolve supervisor id from authenticated user if available
        if ($user) {
            // common pattern: $user->teacher->supervisor
            if (isset($user->teacher) && isset($user->teacher->supervisor) && isset($user->teacher->supervisor->id)) {
                $supervisorId = $user->teacher->supervisor->id;
            } elseif (isset($user->supervisor_id)) {
                $supervisorId = $user->supervisor_id;
            }
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
