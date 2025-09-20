<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Council;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouncilProjectsController extends Controller
{
    // Gán nhiều SV vào 1 hội đồng: tạo bản ghi council_projects với council_member_id = null
    public function assignStudents(Request $request, Council $council)
    {
        $validated = $request->validate([
            'assignment_ids'   => 'required|array|min:1',
            'assignment_ids.*' => 'integer|exists:students,id',
        ]);

        $assignmentIds = array_values(array_unique($validated['assignment_ids']));
        $now = now();

        // Chuẩn bị dữ liệu chèn
        $rows = array_map(function ($sid) use ($council, $now) {
            return [
                'council_id'        => (int)$council->id,
                'assignment_id'        => (int)$sid,
                'council_member_id' => null,
                'created_at'        => $now,
                'updated_at'        => $now,
            ];
        }, $assignmentIds);

        // Tránh trùng: upsert theo (council_id, assignment_id)
        // Nếu DB chưa có unique index, cân nhắc thêm index unique cho 2 cột này.
        DB::table('council_projects')->upsert(
            $rows,
            ['council_id', 'assignment_id'],
            ['council_member_id', 'updated_at']
        );

        return response()->json([
            'ok' => true,
            'message' => 'Đã gán sinh viên vào hội đồng.',
            'count' => count($rows),
        ]);
    }
}
