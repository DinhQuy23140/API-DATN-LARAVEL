<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AcademyYear;
use App\Models\Assignment;
use App\Models\AssignmentSupervisor;
use App\Models\Council;
use App\Models\CouncilProjects;
use App\Models\Department;
use App\Models\ProjectTerm;
use App\Models\stage_timeline; // dùng đúng Model bạn đang có
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectTermsController extends Controller
{
    public function index(){ $terms=ProjectTerm::with('academy_year')->latest('id')->paginate(15); return view('project_terms.index',compact('terms')); }
    public function create(){ return view('project_terms.create',['term'=>new ProjectTerm(),'years'=>AcademyYear::all()]); }
    // Tạo mới 1 ProjectTerm + (tuỳ chọn) StageTimeLine
    public function store(Request $request)
    {
        // Chuẩn hoá tên field từ modal
        $request->merge([
            'academy_year_id' => $request->input('academy_year_id') ?: $request->input('schoolYear'),
            'stage'           => $request->input('stage') ?: $request->input('roundTerm'),
            'start_date'      => $request->input('start_date') ?: $request->input('roundStart'),
            'end_date'        => $request->input('end_date') ?: $request->input('roundEnd'),
        ]);

        $request->validate([
            'academy_year_id' => ['required','exists:academy_years,id'],
            'stage'           => ['required','string','max:50'],
            'start_date'      => ['nullable','date'],
            'end_date'        => ['nullable','date','after_or_equal:start_date'],
            'description'     => ['nullable','string','max:255'],
            'status'          => ['nullable'], // status cho ProjectTerm
        ]);

        // Thu thập timeline theo đúng cột của DB
        $timelineRows = [];
        for ($i = 1; $i <= 8; $i++) {
            $s = $request->input("stage_{$i}_start");
            $e = $request->input("stage_{$i}_end");
            if ($s && $e) {
                $timelineRows[] = [
                    'number_of_rounds' => $i,
                    'start_date'       => $s,
                    'end_date'         => $e,
                    'description'      => $request->input("stage_{$i}_desc") ?: "Stage {$i}", // mô tả mốc
                    // BỎ 'status' để dùng default của DB
                ];
            }
        }

        DB::transaction(function () use ($request, $timelineRows) {
            $status = $request->input('status', 0); // mặc định 0 cho ProjectTerm

            // 1) Lưu ProjectTerm trước
            $term = ProjectTerm::create([
                'academy_year_id' => (int) $request->input('academy_year_id'),
                'stage'           => $request->input('stage'),
                'start_date'      => $request->input('start_date'),
                'end_date'        => $request->input('end_date'),
                'term_name'       => null,
                'description'     => $request->input('description'),
                'status'          => $status,
            ]);

            // 2) Dùng id vừa tạo để lưu StageTimeLine
            if (!empty($timelineRows)) {
                $now = now();
                foreach ($timelineRows as &$row) {
                    $row['project_term_id'] = $term->id;
                    $row['created_at']      = $now;
                    $row['updated_at']      = $now;
                }
                stage_timeline::insert($timelineRows);
            }
        });

        return redirect()->route('web.assistant.rounds')->with('success', 'Tạo đợt đồ án thành công.');
    }

    public function show(ProjectTerm $project_term){ return view('project_terms.show',compact('project_term')); }
    public function edit(ProjectTerm $project_term){ return view('project_terms.edit',['term'=>$project_term,'years'=>AcademyYear::all()]); }
    public function update(Request $request, ProjectTerm $project_term){ $data=$request->validate(['academy_year_id'=>'sometimes|exists:academy_years,id','term_name'=>'sometimes|string|max:255','start_date'=>'sometimes|date','end_date'=>'sometimes|date|after_or_equal:start_date']); $project_term->update($data); return redirect()->route('web.project_terms.show',$project_term)->with('status','Cập nhật thành công'); }
    public function destroy(ProjectTerm $project_term){ $project_term->delete(); return redirect()->route('web.project_terms.index')->with('status','Đã xóa'); }

    public function loadRounds() {
        $years  = AcademyYear::orderByDesc('year_name')->get();
        $stages = ProjectTerm::select('stage')->distinct()->pluck('stage')->filter()->values();
        $terms  = ProjectTerm::with('academy_year')->latest('id')->get();

        return view('assistant-ui.rounds', compact('years', 'stages', 'terms'));
    }

    public function loadRoundDetail($round_id) {
        $round_detail = ProjectTerm::with(
 'academy_year', 
            'stageTimelines', 
            'councils.department', 
            'councils.council_members.supervisor.teacher.user', 
            'supervisors.assignment_supervisors.assignment',
            'assignments.student.user',
            'assignments.student.marjor',
            'assignments.project.reportFiles',
            'assignments.assignment_supervisors.supervisor.teacher.user',
            'assignments.project.progressLogs.attachments'
            )->findOrFail($round_id);
        $departments = Department::get();
        return view('assistant-ui.round-detail', compact('round_detail', 'departments'));
    }

    // Trích các mốc timeline từ request theo đúng cấu trúc bảng stage_timelines
    protected function extractStageTimelines(Request $request): array
    {
        $rows = [];

        // Case 1: Client gửi mảng stages sẵn (ưu tiên)
        $stagesArray = $request->input('stages', []);
        if (is_array($stagesArray) && count($stagesArray)) {
            foreach ($stagesArray as $it) {
                if (empty($it['start_date']) || empty($it['end_date'])) continue;
                $rows[] = [
                    'number_of_rounds' => (int) ($it['number_of_rounds'] ?? 0),
                    'start_date'       => $it['start_date'],
                    'end_date'         => $it['end_date'],
                    'description'      => $it['description'] ?? null,
                    'status'           => (int) ($it['status'] ?? 0),
                ];
            }
            return $rows;
        }

        // Case 2: Từ các input stage_{i}_start/end (+ optional _desc/_status)
        for ($i = 1; $i <= 50; $i++) {
            $s = $request->input("stage_{$i}_start");
            $e = $request->input("stage_{$i}_end");
            if (!$s && !$e) {
                if ($i > 8) break; // dừng sớm sau 8 mốc mặc định
                continue;
            }
            $rows[] = [
                'number_of_rounds' => $i,
                'start_date'       => $s,
                'end_date'         => $e,
                'description'      => $request->input("stage_{$i}_desc") ?: "Stage {$i}",
                'status'           => (int) $request->input("stage_{$i}_status", 0),
            ];
        }

        return $rows;
    }

    // public function getProjectTermByTeacherId($teacherId)
    // {
    //     $rows = ProjectTerm::whereHas('supervisors', function ($query) use ($teacherId) {
    //             $query->where('teacher_id', $teacherId);
    //         })
    //         ->with([
    //             'academy_year',
    //             'supervisors' => function ($query) use ($teacherId) {
    //                 $query->where('teacher_id', $teacherId)
    //                     ->with([
    //                         'assignment_supervisors.assignment.student.user'
    //                     ]);
    //             }
    //         ])
    //         ->get();

    //     return view('lecturer-ui.thesis-rounds', compact('rows'));
    // }

    public function getProjectTermByTeacherId($teacherId)
{
    $rows = ProjectTerm::whereHas('supervisors', function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })
        ->with([
            'academy_year',
            'assignments' => function ($q) use ($teacherId) {
                $q->whereHas('assignment_supervisors.supervisor', function ($qq) use ($teacherId) {
                    $qq->where('teacher_id', $teacherId);
                })
                ->with([
                    'student.user',
                    'project.reportFiles',
                    'assignment_supervisors' => function ($sub) use ($teacherId) {
                        $sub->whereHas('supervisor', function ($ss) use ($teacherId) {
                            $ss->where('teacher_id', $teacherId);
                        });
                    }
                ]);
            },
            'supervisors' => function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            }
        ])
        ->get();

    return view('lecturer-ui.thesis-rounds', compact('rows'));
}


    public function getAllProjectTerms()
    {
        $rows = ProjectTerm::with('supervisors.assignment_supervisors', 'academy_year', 'assignments.student.user')->latest('id')->get();
        return view('head-ui.thesis-rounds', compact('rows'));
    }

    public function getDetailProjectTermByTeacherId($termId, $supervisorId)
    {
        $rows = ProjectTerm::with([
            'academy_year',
            'stageTimelines',
            'assignments.council_project.council_project_defences',
            'assignments' => function ($query) use ($supervisorId) {
                $query->whereHas('assignment_supervisors', function ($q) use ($supervisorId) {
                    $q->where('supervisor_id', $supervisorId);
                    $q->where('status', '!=', 'pending');
                })->with([
                    'student.user',
                    'project.progressLogs.attachments',
                    'project.reportFiles',
                    'council_project.council',
                    'council_project.council_member.supervisor.teacher.user',
                    'assignment_supervisors' => function ($q) use ($supervisorId) {
                        $q->where('supervisor_id', $supervisorId);
                    }
                ]);
            }
        ])->findOrFail($termId);

        $councils = Council::with('council_members')
            ->whereHas('council_members', function ($q) use ($supervisorId) {
                $q->where('supervisor_id', $supervisorId);
            })
            ->whereHas('project_term', function ($q) use ($termId) {
                $q->where('id', $termId);
            })
            ->get();

        return view('lecturer-ui.thesis-round-detail', compact('rows', 'supervisorId', 'councils'));
    }

    public function studentReviews($termId, $supervisorId)
    {
        $rows = ProjectTerm::with([
            'academy_year',
            'stageTimelines',
            'assignments' => function ($query) use ($supervisorId) {
                $query->whereHas('assignment_supervisors', function ($q) use ($supervisorId) {
                    $q->where('supervisor_id', $supervisorId);
                })->with([
                    'student.user',
                    'project.progressLogs.attachments',
                    'project.reportFiles',
                    'council_project.council',
                    'council_project.council.council_members.supervisor.teacher.user',
                    'assignment_supervisors' => function ($q) use ($supervisorId) {
                        $q->where('supervisor_id', $supervisorId);
                    }
                ]);
            }
        ])->findOrFail($termId);

        return view('lecturer-ui.student-reviews', compact('rows', 'supervisorId'));
    }

    public function studentCouncil($termId, $supervisorId)
    {
        $rows = ProjectTerm::with([
            'academy_year',
            'stageTimelines',
            'assignments' => function ($query) use ($supervisorId) {
                $query->whereHas('assignment_supervisors', function ($q) use ($supervisorId) {
                    $q->where('supervisor_id', $supervisorId);
                })->with([
                    'student.user',
                    'project.progressLogs.attachments',
                    'project.reportFiles',
                    'council_project.council',
                    'council_project.council.council_members.supervisor.teacher.user',
                    'assignment_supervisors' => function ($q) use ($supervisorId) {
                        $q->where('supervisor_id', $supervisorId);
                    }
                ]);
            }
        ])->findOrFail($termId);

        return view('lecturer-ui.student_council', compact('rows', 'supervisorId'));
    }

    public function reviewAssignment($supervisorId, $councilId, $termId){
        $projectTerm = ProjectTerm::with('academy_year')->findOrFail($termId);
        $council = $projectTerm->councils()->where('id', $councilId)->first();
        $council_projects = CouncilProjects::with(['assignment.student.user', 'assignment.project.reportFiles', 'assignment.assignment_supervisors.supervisor.teacher.user', 'council', 'council.council_members.supervisor.teacher.user'])
        ->where('council_id', $councilId)
        ->whereHas('council_member', function($q) use ($supervisorId) {
            $q->where('supervisor_id', $supervisorId);
        })
        ->get();
        return view('lecturer-ui.review-assignments', compact('council_projects', 'supervisorId', 'council', 'projectTerm', 'councilId'));
    }

    public function scoringCouncilDetail($supervisorId, $councilId, $termId) {
        $projectTerm = ProjectTerm::with('academy_year')->findOrFail($termId);
        $council = $projectTerm->councils()->where('id', $councilId)->with('council_members.supervisor.teacher.user')->first();
        $council_projects = CouncilProjects::with(['assignment.student.user', 'assignment.project.reportFiles', 'assignment.assignment_supervisors.supervisor.teacher.user', 'council', 'council.council_members.supervisor.teacher.user', 'council_project_defences'])
        ->where('council_id', $councilId)
        ->whereHas('council_member', function($q) use ($supervisorId) {
            $q->where('supervisor_id', $supervisorId);
        })
        ->get();
        return view('lecturer-ui.council-scoring-detail', compact('council_projects', 'supervisorId', 'council', 'projectTerm', 'councilId'));
    }

    public function studentCommitee($supervisorId, $termId)
    {
        $rows = ProjectTerm::with([
            'academy_year',
            'stageTimelines',
            'assignments' => function ($query) use ($supervisorId) {
                $query->whereHas('assignment_supervisors', function ($q) use ($supervisorId) {
                    $q->where('supervisor_id', $supervisorId);
                })->with([
                    'student.user',
                    'project.progressLogs.attachments',
                    'project.reportFiles',
                    'council_project.council',
                    'council_project.council.council_members.supervisor.teacher.user',
                    'assignment_supervisors' => function ($q) use ($supervisorId) {
                        $q->where('supervisor_id', $supervisorId);
                    }
                ]);
            }
        ])->findOrFail($termId);

        return view('lecturer-ui.student-committees', compact('rows', 'supervisorId'));
    }

    public function getProjectTermBtId($termId)
    {
        $rows = ProjectTerm::with('supervisors.assignment_supervisors', 'academy_year', 'assignments.student.user', 'assignments.project')->findOrFail($termId);
        return view('head-ui.blind-review-lecturer', compact('rows'));
    }

    public function getDetailProjectTermBySupervisorId($supervisorId, $termId)
    {
        $rows = ProjectTerm::where('id', $termId)
            ->whereHas('supervisors', function ($query) use ($supervisorId) {
                $query->where('id', $supervisorId);
            })
            ->with([
                'academy_year',
                'stageTimelines',
                'assignments' => function ($query) use ($supervisorId) {
                    $query->whereHas('assignment_supervisors', function ($q) use ($supervisorId) {
                        $q->where('supervisor_id', $supervisorId);
                    })
                    ->with([
                        'assignment_supervisors' => function ($q) use ($supervisorId) {
                            $q->where('supervisor_id', $supervisorId);
                        },
                        'student.user',
                        'project.progressLogs.attachments',
                        'project.reportFiles'
                    ]);
                }
            ])
            ->firstOrFail();

        return view('lecturer-ui.supervised-outline-reports', compact('rows', 'supervisorId'));
    }

    public function assignmentSupervisor($termId)
    {
        $projectTerm = ProjectTerm::with([
            'academy_year',
            'assignments.student.user',
            'supervisors.assignment_supervisors' => function ($q) use ($termId) {
                $q->whereHas('assignment', function ($sub) use ($termId) {
                    $sub->where('project_term_id', $termId);
                })->with('assignment.student.user');
            }
        ])->findOrFail($termId);

        $unassignedAssignments = Assignment::with('student.user', 'project')
            ->where('project_term_id', $termId)
            ->whereDoesntHave('assignment_supervisors')
            ->get();

        return view('head-ui.assign-supervisors', compact('projectTerm', 'unassignedAssignments'));
    }

    public function loadHeadRoundDetail($termId) {
        $projectTerm = ProjectTerm::with('supervisors.assignment_supervisors', 'academy_year', 'assignments.student.user')->findOrFail($termId);
        return view('head-ui.thesis-round-detail', compact('projectTerm'));
    }
}
