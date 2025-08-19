<?php

namespace App\Http\Controllers;

use App\Models\ProgressLog;
use App\Http\Resources\ProgressLogResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProgressLogController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $logs = ProgressLog::with('attachments')->get();
        return response()->json($logs); // Không còn data:
    }


    /**
     * Store a newly created ProgressLog in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        \Log::info('Incoming request:', $request->all());
        $data = $request->validate([
            'process_id' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date_time' => 'required|date',
            'end_date_time' => 'required|date|after_or_equal:start_date_time',
            'student_status' => 'required|string',
            'instructor_status' => 'nullable|string',
            'instructor_comment' => 'nullable|string',
        ]);

        $log = ProgressLog::create($data);
        return response()->json($log->load('attachments'), 201);

    }


    /**
     * @return \App\Http\Resources\ProgressLogResource
     */
    public function show(ProgressLog $progressLog)
    {
        return response()->json($progressLog->load('attachments'));
    }

    /**
     * @return \App\Http\Resources\ProgressLogResource
     */
    public function update(Request $request, ProgressLog $progressLog)
    {
        $data = $request->validate([
        'process_id' => 'sometimes|string',
        'title' => 'sometimes|string|max:255',
        'description' => 'nullable|string',
        'start_date_time' => 'sometimes|date',
        'end_date_time' => 'sometimes|date|after_or_equal:start_date_time',
        'student_status' => 'sometimes|string',
        'instructor_status' => 'nullable|string',
        'instructor_comment' => 'nullable|string',
        ]);

        $progressLog->update($data);

        // return new ProgressLogResource($progressLog->load('attachments'));
        return response()->json($progressLog->load('attachments'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ProgressLog $progressLog)
    {
        $progressLog->delete();

        return response()->json(['message' => 'Deleted successfully.']);
    }
}
