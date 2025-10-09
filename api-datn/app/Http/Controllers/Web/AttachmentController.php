<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\ProgressLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttachmentController extends Controller
{
    public function create(ProgressLog $progress_log)
    {
        $attachment = new Attachment();
        return view('attachments.create', compact('attachment','progress_log'));
    }

    public function store(Request $request, ProgressLog $progress_log)
    {
        $data = $request->validate([
            'file_name' => 'required|string|max:255',
            'file_url' => 'required|string|max:500',
            'file_type' => 'nullable|string|max:120',
            'upload_time' => 'nullable|date',
            'uploader_id' => 'nullable|integer'
        ]);
        $data['upload_time'] = isset($data['upload_time']) ? Carbon::parse($data['upload_time'])->timestamp : now()->timestamp;
        $data['progress_log_id'] = $progress_log->id;
        $attachment = Attachment::create($data);
        return redirect()->route('web.progress_logs.show', $progress_log)->with('status','Đã thêm tệp');
    }

    public function edit(Attachment $attachment)
    {
        $progress_log = $attachment->progressLog;
        return view('attachments.edit', compact('attachment','progress_log'));
    }

    public function update(Request $request, Attachment $attachment)
    {
        $data = $request->validate([
            'file_name' => 'sometimes|string|max:255',
            'file_url' => 'sometimes|string|max:500',
            'file_type' => 'nullable|string|max:120',
            'upload_time' => 'nullable|date',
            'uploader_id' => 'nullable|integer'
        ]);
        if(isset($data['upload_time'])){
            $data['upload_time'] = Carbon::parse($data['upload_time'])->timestamp;
        }
        $attachment->update($data);
        return redirect()->route('web.progress_logs.show', $attachment->progressLog)->with('status','Cập nhật tệp thành công');
    }

    public function destroy(Attachment $attachment)
    {
        $log = $attachment->progressLog;
        $attachment->delete();
        return redirect()->route('web.progress_logs.show', $log)->with('status','Đã xóa tệp');
    }

    public function updateStatus($progress_log, Request $request)
    {
        $data = $request->validate([
            'status' => 'required|in:approved,not_achieved,need_editing',
            'note'   => 'nullable|string|max:2000',
        ]);

        // Gợi ý: đổi 'attachments' thành tên bảng thực tế nếu khác (vd: progress_log_attachments)
        $row = DB::table('progress_logs')->where('id', $progress_log)->first();
        if (!$row) {
            return response()->json(['ok' => false, 'message' => 'Không tìm thấy tệp đính kèm.'], 404);
        }

        // TODO: kiểm tra quyền theo giảng viên hiện tại nếu cần

        // DB::table('progress_logs')
        //     ->where('id', $progress_log)
        //     ->update([
        //         'instructor_status'     => $data['status'],
        //         'note'       => $data['note'] ?? null,
        //         'updated_at' => now(),
        //     ]);

        DB::table('progress_logs')
            ->where('id', $progress_log)
            ->update([
                'instructor_status'     => $data['status'],
                'updated_at' => now(),
            ]);

        return response()->json([
            'ok' => true,
            'message' => 'Cập nhật trạng thái thành công.',
            'data' => [
                'id'     => (int) $progress_log,
                'status' => $data['status'],
                'note'   => $data['note'] ?? null,
            ],
        ]);
    }
}
