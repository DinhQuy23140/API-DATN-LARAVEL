<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CouncilMembers;
use App\Models\CouncilProjectDefences;
use App\Models\CouncilProjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouncilProjectDefencesController extends Controller
{
    public function store(CouncilProjects $council_project, Request $request)
    {
        $validated = $request->validate([
            'score'              => 'required|numeric|min:0|max:10',
            'comment'            => 'nullable|string',
            'council_member_id'  => 'nullable|exists:council_members,id',
        ]);

        // Xác định council_member_id:
        // - Ưu tiên lấy từ request (nếu frontend gửi lên)
        // - Nếu không có, suy ra từ user hiện tại trong hội đồng của project này
        $cmId = $validated['council_member_id'] ?? null;
        if (!$cmId) {
            $userId = Auth::id();
            $member = CouncilMembers::query()
                ->where('council_id', $council_project->council_id)
                ->whereHas('supervisor.teacher.user', function ($q) use ($userId) {
                    $q->where('id', $userId);
                })
                ->first();
            if (!$member) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Không tìm thấy thành viên hội đồng tương ứng với tài khoản hiện tại.'
                ], 403);
            }
            $cmId = $member->id;
        }

        // Tìm hoặc tạo bản ghi theo cặp (council_project_id, council_member_id)
        $def = CouncilProjectDefences::firstOrNew([
            'council_project_id' => $council_project->id,
            'council_member_id'  => $cmId,
        ]);

        $def->score    = (float) $validated['score'];
        $def->comments = $validated['comment'] ?? null;
        $def->save();

        return response()->json([
            'ok' => true,
            'message' => 'Lưu điểm thành công.',
            'data' => [
                'id' => $def->id,
                'council_project_id' => $def->council_project_id,
                'council_member_id'  => $def->council_member_id,
                'score' => $def->score,
                'comments' => $def->comments,
            ],
        ]);
    }
}
