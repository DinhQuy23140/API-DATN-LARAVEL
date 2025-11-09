<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\AssignmentSupervisor;
use App\Models\ProposedTopic;
use Illuminate\Http\JsonResponse;

class ProposedTopicController extends Controller
{
    /**
     * Return proposed topics related to an assignment.
     * It finds supervisors linked to the assignment via assignment_supervisors
     * and returns proposed topics authored by those supervisors.
     *
     * @param Request $request
     * @param int $assignmentId
     * @return JsonResponse
     */
    public function forAssignment(Request $request, $assignmentId): JsonResponse
    {
        // basic validation - ensure assignment exists
        $assignment = Assignment::find($assignmentId);
        if (!$assignment) {
            return response()->json(['ok' => false, 'message' => 'Assignment not found'], 404);
        }

        // get supervisor ids linked to the assignment
        $supervisorIds = AssignmentSupervisor::where('assignment_id', $assignmentId)
            ->pluck('supervisor_id')
            ->unique()
            ->filter()
            ->values()
            ->all();

        if (empty($supervisorIds)) {
            return response()->json(['ok' => true, 'topics' => []]);
        }

        // load proposed topics authored by those supervisors
        $topics = ProposedTopic::with(['supervisor.teacher.user'])
            ->whereIn('supervisor_id', $supervisorIds)
            ->orderByDesc('proposed_at')
            ->get();

        return response()->json($topics);
    }
}