<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Council;
use App\Models\CouncilMembers;
use App\Models\Department;
use App\Models\ProjectTerm;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CouncilController extends Controller
{
    // Tạo hội đồng (role = số 5..1)
    public function store(Request $request)
    {
        $request->validate([
            'term_id' => ['required','integer','exists:project_terms,id'],
            'code'    => [
                'required','string','max:100',
                Rule::unique('councils','code')->where(fn($q)=>$q->where('project_term_id', $request->input('term_id')))
            ],
            'name'        => ['nullable','string','max:255'],
            'dept'        => ['nullable','integer','exists:departments,id'],
            'room'        => ['nullable','string','max:100'],
            'date'        => ['nullable','date'],
            'description' => ['nullable','string','max:2000'],
            'chutich' => ['nullable','integer', Rule::exists('supervisors','id')->where(fn($q)=>$q->where('project_term_id',$request->input('term_id')))],

            // Thành viên (tuỳ chọn) và phải thuộc đúng term
            'thuki'   => ['nullable','integer', Rule::exists('supervisors','id')->where(fn($q)=>$q->where('project_term_id',$request->input('term_id')))],
            'uyvien1' => ['nullable','integer', Rule::exists('supervisors','id')->where(fn($q)=>$q->where('project_term_id',$request->input('term_id')))],
            'uyvien2' => ['nullable','integer', Rule::exists('supervisors','id')->where(fn($q)=>$q->where('project_term_id',$request->input('term_id')))],
            'uyvien3' => ['nullable','integer', Rule::exists('supervisors','id')->where(fn($q)=>$q->where('project_term_id',$request->input('term_id')))],
        ]);

        // Chống trùng giảng viên giữa các vai trò
        $picked = collect([$request->chutich,$request->thuki,$request->uyvien1,$request->uyvien2,$request->uyvien3])->filter()->values();
        if ($picked->count() !== $picked->unique()->count()) {
            return response()->json(['ok'=>false,'message'=>'Các vai trò không được trùng giảng viên.'], 422);
        }

        $council = null;
        $memberCount = 0;

        DB::transaction(function() use ($request, &$council, &$memberCount) {
            // Tạo hội đồng
            $council = Council::create([
                'project_term_id' => (int)$request->input('term_id'),
                'code'            => (string)$request->input('code'),
                'name'            => $request->input('name') ?: null,
                'department_id'   => $request->input('dept') ?: null,
                'address'         => $request->input('room') ?: null,
                'date'            => $request->input('date') ?: null,
                'description'     => $request->input('description') ?: null,
            ]);

            // Thành viên (vai trò số: 5=Chủ tịch, 4=Thư ký, 3=UV1, 2=UV2, 1=UV3)
            $roles = [
                5 => $request->input('chutich'),
                4 => $request->input('thuki'),
                3 => $request->input('uyvien1'),
                2 => $request->input('uyvien2'),
                1 => $request->input('uyvien3'),
            ];

            foreach ($roles as $roleNum => $sid) {
                if (!$sid) continue;
                CouncilMembers::create([
                    'council_id'    => $council->id,
                    'supervisor_id' => (int)$sid,
                    'role'          => $roleNum,
                ]);
                $memberCount++;
            }
        });

        return response()->json([
            'ok'           => true,
            'council_id'   => $council->id,
            'member_count' => $memberCount,
            'message'      => $memberCount ? 'Đã tạo hội đồng và thêm thành viên.' : 'Đã tạo hội đồng (chưa có thành viên).',
        ], 201);
    }

    // Cập nhật vai trò cho hội đồng (role = số)
    public function updateRoles(Request $request, $councilId)
    {
        $payload = $request->validate([
            'chairman'  => 'nullable|integer|exists:supervisors,id',
            'secretary' => 'nullable|integer|exists:supervisors,id',
            'member1'   => 'nullable|integer|exists:supervisors,id',
            'member2'   => 'nullable|integer|exists:supervisors,id',
            'member3'   => 'nullable|integer|exists:supervisors,id',
        ]);

        $vals = array_values(array_filter($payload, fn($v)=>!empty($v)));
        if (count($vals) !== count(array_unique($vals))) {
            return response()->json(['ok'=>false,'message'=>'Các vai trò không được trùng giảng viên.'], 422);
        }

        DB::transaction(function() use ($payload, $councilId){
            // Xóa các role số 1..5 trong hội đồng hiện tại
            DB::table('council_members')
                ->where('council_id', $councilId)
                ->whereIn('role', [1,2,3,4,5])
                ->delete();

            // Thêm lại theo map số
            $rows = [];
            $map = [
                5 => $payload['chairman']  ?? null,
                4 => $payload['secretary'] ?? null,
                3 => $payload['member1']   ?? null,
                2 => $payload['member2']   ?? null,
                1 => $payload['member3']   ?? null,
            ];
            foreach ($map as $roleNum => $sid) {
                if (!$sid) continue;
                $rows[] = [
                    'council_id'    => (int)$councilId,
                    'supervisor_id' => (int)$sid,
                    'role'          => $roleNum,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }

            if (!empty($rows)) {
                DB::table('council_members')->insert($rows);
            }
        });

        return response()->json(['ok'=>true,'message'=>'Đã cập nhật vai trò hội đồng.']);
    }

    // Cập nhật thông tin hội đồng
    public function update(Request $request, Council $council)
    {
        $request->validate([
            'code' => [
                'required','string','max:100',
                Rule::unique('councils','code')
                    ->where(fn($q)=>$q->where('project_term_id', $council->project_term_id))
                    ->ignore($council->id)
            ],
            'name'        => ['nullable','string','max:255'],
            'dept'        => ['nullable','integer','exists:departments,id'],
            'room'        => ['nullable','string','max:100'],
            'date'        => ['nullable','date'],
            'description' => ['nullable','string','max:2000'],
        ]);

        $council->update([
            'code'          => (string)$request->input('code'),
            'name'          => $request->input('name') ?: null,
            'department_id' => $request->input('dept') ?: null,
            'address'       => $request->input('room') ?: null,
            'date'          => $request->input('date') ?: null,
            'description'   => $request->input('description') ?: null,
        ]);

        $deptName = $request->filled('dept')
            ? DB::table('departments')->where('id',(int)$request->input('dept'))->value('name')
            : null;

        return response()->json([
            'ok'=>true,
            'data'=>[
                'id'          => $council->id,
                'code'        => (string)$council->code,
                'name'        => $council->name,
                'department'  => ['id'=>$council->department_id,'name'=>$deptName],
                'address'     => $council->address,
                // Tránh gọi ->format() trên string
                'date'        => $council->date
                    ? (is_object($council->date) && method_exists($council->date, 'format')
                        ? $council->date->format('Y-m-d')
                        : (string)$council->date)
                    : null,
                'description' => $council->description,
            ]
        ]);
    }

    // Lưu thành viên hội đồng (role = số, xử lý đổi người/xóa/tạo)
    public function saveMembers(Request $request, int $councilId)
    {
        $data = $request->validate([
            'chairman'  => 'nullable|integer|exists:supervisors,id',
            'secretary' => 'nullable|integer|exists:supervisors,id',
            'member1'   => 'nullable|integer|exists:supervisors,id',
            'member2'   => 'nullable|integer|exists:supervisors,id',
            'member3'   => 'nullable|integer|exists:supervisors,id',
        ]);

        $vals = array_values(array_filter($data, fn($v)=>!empty($v)));
        if (count($vals) !== count(array_unique($vals))) {
            return response()->json(['ok'=>false,'message'=>'Các vai trò không được trùng giảng viên.'], 422);
        }

        $roleMap = ['chairman'=>5,'secretary'=>4,'member1'=>3,'member2'=>2,'member3'=>1];
        $inserted=0; $updated=0; $deleted=0;

        // DB::transaction(function() use ($councilId, $data, $roleMap, &$inserted, &$updated, &$deleted) {
        //     foreach ($roleMap as $key => $roleNum) {
        //         $sid = $data[$key] ?? null;

        //         if (empty($sid)) {
        //             $deleted += DB::table('council_members')
        //                 ->where('council_id',$councilId)
        //                 ->where('role',$roleNum)
        //                 ->delete();
        //             continue;
        //         }

        //         // Xóa record cũ khác supervisor cho đúng role số
        //         $deleted += DB::table('council_members')
        //             ->where('council_id',$councilId)
        //             ->where('role',$roleNum)
        //             ->where('supervisor_id','!=',$sid)
        //             ->delete();

        //         // Tìm bản ghi (role số, supervisor)
        //         $existing = DB::table('council_members')
        //             ->where('role',$roleNum)
        //             ->where('supervisor_id',$sid)
        //             ->first();

        //         if ($existing) {
        //             if ((int)$existing->council_id !== (int)$councilId) {
        //                 DB::table('council_members')->where('id',$existing->id)->update([
        //                     'council_id'=>$councilId,
        //                     'updated_at'=>now()
        //                 ]);
        //                 $updated++;
        //             } else {
        //                 DB::table('council_members')->where('id',$existing->id)->update(['updated_at'=>now()]);
        //             }
        //         } else {
        //             DB::table('council_members')->insert([
        //                 'council_id'    => $councilId,
        //                 'supervisor_id' => (int)$sid,
        //                 'role'          => $roleNum,
        //                 'created_at'    => now(),
        //                 'updated_at'    => now(),
        //             ]);
        //             $inserted++;
        //         }
        //     }
        // });

        DB::transaction(function() use ($councilId, $data, $roleMap, &$inserted, &$updated, &$deleted) {
            foreach ($roleMap as $key => $roleNum) {
                $sid = $data[$key] ?? null;

                if (empty($sid)) {
                    $deleted += DB::table('council_members')
                        ->where('council_id',$councilId)
                        ->where('role',$roleNum)
                        ->delete();
                    continue;
                }

                // Kiểm tra trong cùng hội đồng: giảng viên này đã có vai trò nào chưa
                $existingSameCouncil = DB::table('council_members')
                    ->where('council_id',$councilId)
                    ->where('supervisor_id',$sid)
                    ->first();

                if ($existingSameCouncil) {
                    // Nếu role khác thì cập nhật sang role mới
                    if ((int)$existingSameCouncil->role !== $roleNum) {
                        DB::table('council_members')
                            ->where('id',$existingSameCouncil->id)
                            ->update([
                                'role'       => $roleNum,
                                'updated_at' => now()
                            ]);
                        $updated++;
                    } else {
                        // Đúng role rồi thì chỉ update timestamp
                        DB::table('council_members')
                            ->where('id',$existingSameCouncil->id)
                            ->update(['updated_at'=>now()]);
                    }
                } else {
                    // Nếu chưa có trong hội đồng thì insert
                    DB::table('council_members')->insert([
                        'council_id'    => $councilId,
                        'supervisor_id' => (int)$sid,
                        'role'          => $roleNum,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                    $inserted++;
                }

                // Đảm bảo không có nhiều người cùng role trong council này:
                DB::table('council_members')
                    ->where('council_id',$councilId)
                    ->where('role',$roleNum)
                    ->where('supervisor_id','!=',$sid)
                    ->delete();
            }
        });

        return response()->json(['ok'=>true,'inserted'=>$inserted,'updated'=>$updated,'deleted'=>$deleted,'message'=>'Đã lưu thành viên hội đồng.']);
    }

    /**
     * Return JSON list of students assigned to a council (for assistant UI modal)
     * GET /assistant/councils/{council}
     */
    public function show(Council $council)
    {
        $council->load([
            'council_projects.assignment.student.user',
            'council_projects.assignment.project',
            'council_projects.assignment.assignment_supervisors.supervisor.teacher.user'
        ]);

        $students = [];
        foreach ($council->council_projects as $cp) {
            $assignment = $cp->assignment;
            if (!$assignment) continue;
            $student = $assignment->student;
            $user = $student->user ?? null;

            // pick first accepted supervisor as display name (if any)
            $supName = null;
            if ($assignment->assignment_supervisors) {
                $accepted = $assignment->assignment_supervisors->firstWhere('status', 'accepted');
                if ($accepted && $accepted->supervisor && $accepted->supervisor->teacher && $accepted->supervisor->teacher->user) {
                    $supName = $accepted->supervisor->teacher->user->fullname ?? null;
                }
            }

            $students[] = [
                'council_project_id' => $cp->id,
                'assignment_id'   => $assignment->id,
                'student_code'    => $student->student_code ?? null,
                'student_name'    => $user->fullname ?? $user->name ?? null,
                'topic'           => $assignment->project->name ?? null,
                'supervisor_name' => $supName,
            ];
        }

        return response()->json(['ok' => true, 'students' => $students]);
    }

    // Trang phân công vai trò (đổi mapping role -> số)
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
            $membersByCouncil[$m->council_id][(int)$m->role] = [
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
                    'chairman' => $roles[5] ?? null,
                    'secretary'=> $roles[4] ?? null,
                    'member1'  => $roles[3] ?? null,
                    'member2'  => $roles[2] ?? null,
                    'member3'  => $roles[1] ?? null,
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

    public function getCouncilByTermId($termId) {
        $councils = Council::with('council_members.supervisor.teacher.user')
            ->where('project_term_id', $termId)
            ->latest('created_at')
            ->get();

        $departments = Department::get();

        $supervisors = Supervisor::with('teacher.user')
        ->where('project_term_id', $termId)
        ->get();

        return view ('assistant-ui.council-roles', compact('councils', 'supervisors', 'departments', 'termId'));
    }

    public function getCouncilAndAssignmentByTermId($termId) {
        // $councils = Council::with('council_members.supervisor.teacher.user')
        //     ->where('project_term_id', $termId)
        //     ->latest('created_at')
        //     ->get();
        $projectTerm = ProjectTerm::with('academy_year', 'councils.council_members.supervisor.teacher.user', 'councils.council_projects')->find($termId);

        $assignments = Assignment::with('student.user', 'student.marjor.department.faculties', 'project', 'assignment_supervisors.supervisor.teacher.user')
            ->where('project_term_id', $termId)
            ->whereDoesntHave('council_project') // loại assignment đã gán hội đồng
            ->get();

        $departments = Department::get();

        return view ('assistant-ui.council-assign-students', compact('projectTerm', 'assignments', 'departments', 'termId'));
    }

    // PATCH /assistant/councils/{council}/members
    public function updateMembers(Request $request, Council $council)
    {
        $data = $request->validate([
            'chairman'  => 'nullable|integer|exists:supervisors,id',
            'secretary' => 'nullable|integer|exists:supervisors,id',
            'member1'   => 'nullable|integer|exists:supervisors,id',
            'member2'   => 'nullable|integer|exists:supervisors,id',
            'member3'   => 'nullable|integer|exists:supervisors,id',
        ]);

        // Chống trùng GV
        $vals = array_values(array_filter($data, fn($v)=>!empty($v)));
        if (count($vals) !== count(array_unique($vals))) {
            return response()->json(['ok'=>false,'message'=>'Các vai trò không được trùng giảng viên.'], 422);
        }

        // Giống updateRoles: xóa hết 1..5 và chèn lại theo map số
        DB::transaction(function () use ($council, $data) {
            DB::table('council_members')
                ->where('council_id', $council->id)
                ->whereIn('role', [1,2,3,4,5])
                ->delete();

            $map = [
                5 => $data['chairman']  ?? null,
                4 => $data['secretary'] ?? null,
                3 => $data['member1']   ?? null,
                2 => $data['member2']   ?? null,
                1 => $data['member3']   ?? null,
            ];

            $rows = [];
            foreach ($map as $roleNum => $sid) {
                if (!$sid) continue;
                $rows[] = [
                    'council_id'    => (int)$council->id,
                    'supervisor_id' => (int)$sid,
                    'role'          => (int)$roleNum,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }
            if ($rows) {
                DB::table('council_members')->insert($rows);
            }
        });

        return response()->json(['ok'=>true,'message'=>'Đã cập nhật vai trò hội đồng.']);
    }

    public function getCouncilDetail($councilId, $termId, $supervisorId) {
        $council = Council::with('project_term.academy_year', 'council_members.supervisor.teacher.user', 'council_projects.council_project_defences', 'council_projects.assignment.project', 'council_projects.assignment.student.user', 'council_projects.assignment.assignment_supervisors.supervisor.teacher.user')->find($councilId);
        return view ('lecturer-ui.committee-detail', compact('council', 'supervisorId'));
    }
}
