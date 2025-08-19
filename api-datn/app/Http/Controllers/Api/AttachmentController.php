<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\ProgressLog;
use App\Http\Resources\AttachmentResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class AttachmentController extends Controller
{
    public function index(ProgressLog $progressLog)
    {
        return AttachmentResource::collection($progressLog->attachments);
    }

    public function getAllAttachment(Request $request) {
        return AttachmentResource::collection(Attachment::all());
    }

    public function store(Request $request, ProgressLog $progressLog)
    {
        $uploadTime = $request->filled('upload_time')
            ? Carbon::parse($request->input('upload_time'))->timestamp
            : now()->timestamp;

        $attachment = $progressLog->attachments()->create([
            'progress_log_id' => $progressLog->id,
            'file_name'       => $request->input('file_name', 'no-name.pdf'),
            'file_url'        => $request->input('file_url', 'attachments/unknown.pdf'),
            'file_type'       => $request->input('file_type', 'application/octet-stream'),
            'upload_time'     => $uploadTime,
            'uploader_id'     => $request->input('uploader_id', auth()->id() ?? 0),
        ]);

        return (new AttachmentResource($attachment))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Attachment $attachment)
    {
        return new AttachmentResource($attachment);
    }

    public function update(Request $request, ProgressLog $progressLog, Attachment $attachment)
    {
        $uploadTime = $request->filled('upload_time')
            ? Carbon::parse($request->input('upload_time'))->timestamp
            : $attachment->upload_time;

        $attachment->update([
            'file_name'       => $request->input('file_name', $attachment->file_name),
            'file_url'        => $request->input('file_url', $attachment->file_url),
            'file_type'       => $request->input('file_type', $attachment->file_type),
            'upload_time'     => $uploadTime,
            'uploader_id'     => $request->input('uploader_id', $attachment->uploader_id),
        ]);

        return (new AttachmentResource($attachment))
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(Attachment $attachment)
    {
        $attachment->delete();
        return response()->json(['message' => 'Attachment deleted.'], Response::HTTP_OK);
    }
}
