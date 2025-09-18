<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouncilController extends Controller
{
    // Tạo hội đồng (đã gửi trước, giữ nguyên nếu có)
    public function store(Request $request)
    {
        $data = $request->validate([
            'term_id'     => 'required|integer|exists:project_terms,id',
            'code'        => 'required|string|max:100',
            'name'        => 'nullable|string|max:255',
            'dept'        => 'nullable|string|max:255',
            'room'        => 'nullable|string|max:100',
            'date'        => 'nullable|date',
            'description' => 'nullable|string|max:2000',
            'chutich'     => 'nullable|integer|exists:supervisors,id',
            'thuki'       => 'nullable|integer|exists:supervisors,id',
            'uyvien1'     => 'nullable|integer|exists:supervisors,id',
            'uyvien2'     => 'nullable|integer|exists:supervisors,id',
            'uyvien3'     => 'nullable|integer|exists:supervisors,id',
        ]);

        $councilId = null;
        $memberCount = 0;

        DB::transaction(function () use ($data, &$councilId, &$memberCount) {
            $councilId = DB::table('councils')->insertGetId([
                'project_term_id' => $data['term_id'],
                'code'            => $data['code'],
                'name'            => $data['name'] ?? null,
                'department'      => $data['dept'] ?? null,
                'room'            => $data['room'] ?? null,
                'defense_date'    => $data['date'] ?? null,
                'description'     => $data['description'] ?? null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            $rawMembers = [
                ['supervisor_id' => $data['chutich'] ?? null, 'role' => 'chairman'],
                ['supervisor_id' => $data['thuki']   ?? null, 'role' => 'secretary'],
                ['supervisor_id' => $data['uyvien1'] ?? null, 'role' => 'member1'],
                ['supervisor_id' => $data['uyvien2'] ?? null, 'role' => 'member2'],
                ['supervisor_id' => $data['uyvien3'] ?? null, 'role' => 'member3'],
            ];

            $members = collect($rawMembers)
                ->filter(function($m){ return !empty($m['supervisor_id']); })
                ->unique('supervisor_id')
                ->values()
                ->all();

            if (!empty($members)) {
                $rows = array_map(function ($m) use ($councilId) {
                    return [
                        'council_id'    => $councilId,
                        'supervisor_id' => $m['supervisor_id'],
                        'role'          => $m['role'],
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }, $members);

                DB::table('council_members')->insert($rows);
                $memberCount = count($rows);
            }
        });

        return response()->json([
            'ok'           => true,
            'council_id'   => $councilId,
            'member_count' => $memberCount,
            'message'      => $memberCount > 0 ? 'Đã tạo hội đồng và thêm thành viên.' : 'Đã tạo hội đồng (chưa có thành viên).',
        ], 201);
    }

    // Trang phân công vai trò
    public function rolesPage(Request $request, $term)
    {
        $termId = (int)$term;

        // Lấy danh sách hội đồng theo đợt
        $councils = DB::table('councils')
            ->select('id','code','name','room','defense_date')
            ->where('project_term_id', $termId)
            ->orderBy('code')
            ->get();

        // Đếm thành viên + map vai trò
        $members = DB::table('council_members as cm')
            ->join('supervisors as s', 's.id', '=', 'cm.supervisor_id')
            ->join('teachers as t', 't.id', '=', 's.teacher_id')
            ->join('users as u', 'u.id', '=', 't.user_id')
            ->whereIn('cm.council_id', $councils->pluck('id')->all() ?: [0])
            ->get(['cm.council_id','cm.role','cm.supervisor_id','u.fullname']);

        $membersByCouncil = [];
        foreach ($members as $m) {
            $membersByCouncil[$m->council_id][$m->role] = [
                'id' => $m->supervisor_id,
                'name' => $m->fullname,
            ];
        }

        $councilsOut = $councils->map(function($c) use ($membersByCouncil){
            $roles = $membersByCouncil[$c->id] ?? [];
            $count = count($roles);
            return [
                'id' => $c->id,
                'code' => $c->code,
                'name' => $c->name,
                'room' => $c->room,
                'defense_date' => $c->defense_date,
                'member_count' => $count,
                'roles' => [
                    'chairman' => $roles['chairman'] ?? null,
                    'secretary'=> $roles['secretary'] ?? null,
                    'member1'  => $roles['member1'] ?? null,
                    'member2'  => $roles['member2'] ?? null,
                    'member3'  => $roles['member3'] ?? null,
                ],
            ];
        })->values();

        // Danh sách giảng viên theo đợt (supervisors)
        $supervisors = DB::table('supervisors as s')
            ->join('teachers as t', 't.id', '=', 's.teacher_id')
            ->join('users as u', 'u.id', '=', 't.user_id')
            ->where('s.project_term_id', $termId)
            ->orderBy('u.fullname')
            ->get(['s.id','u.fullname']);

        return view('assistant-ui.council-roles', [
            'termId' => $termId,
            'councils' => $councilsOut,
            'supervisors' => $supervisors,
        ]);
    }

    // Cập nhật vai trò cho hội đồng
    public function updateRoles(Request $request, $councilId)
    {
        $payload = $request->validate([
            'chairman'  => 'nullable|integer|exists:supervisors,id',
            'secretary' => 'nullable|integer|exists:supervisors,id',
            'member1'   => 'nullable|integer|exists:supervisors,id',
            'member2'   => 'nullable|integer|exists:supervisors,id',
            'member3'   => 'nullable|integer|exists:supervisors,id',
        ]);

        // Không cho trùng GV giữa các vai trò
        $vals = array_values(array_filter($payload, fn($v)=>!empty($v)));
        if (count($vals) !== count(array_unique($vals))) {
            return response()->json(['ok'=>false,'message'=>'Các vai trò không được trùng giảng viên.'], 422);
        }

        DB::transaction(function() use ($payload, $councilId){
            // Xóa các vai trò cũ (5 vai trò chuẩn)
            DB::table('council_members')
                ->where('council_id', $councilId)
                ->whereIn('role', ['chairman','secretary','member1','member2','member3'])
                ->delete();

            // Thêm lại vai trò đang set
            $rows = [];
            foreach (['chairman','secretary','member1','member2','member3'] as $role) {
                $sid = $payload[$role] ?? null;
                if ($sid) {
                    $rows[] = [
                        'council_id'    => (int)$councilId,
                        'supervisor_id' => (int)$sid,
                        'role'          => $role,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }
            }
            if (!empty($rows)) {
                DB::table('council_members')->insert($rows);
            }
        });

        return response()->json(['ok'=>true,'message'=>'Đã cập nhật vai trò hội đồng.']);
    }
}
