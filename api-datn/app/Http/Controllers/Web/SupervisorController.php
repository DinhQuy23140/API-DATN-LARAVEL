<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AcademyYear;
use App\Models\AssignmentSupervisor;
use App\Models\ProjectTerm;
use App\Models\Supervisor;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SupervisorController extends Controller
{
    public function index(){
        $supervisors = Supervisor::with('teacher.user')->latest('id')->paginate(15);
        return view('supervisors.index', compact('supervisors'));
    }
    public function create(){return view('supervisors.create',['supervisor'=>new Supervisor(),'teachers'=>Teacher::with('user')->get()]);}
    public function store(Request $request){
        $data=$request->validate([
            'teacher_id'=>'required|exists:teachers,id',
            'max_students'=>'required|integer|min:1',
            'expertise'=>'nullable|string'
        ]);
        $sp=Supervisor::create($data);
        return redirect()->route('web.supervisors.show',$sp)->with('status','Tạo thành công');
    }
    public function show(Supervisor $supervisor){$supervisor->load('teacher.user'); return view('supervisors.show', compact('supervisor'));}
    public function edit(Supervisor $supervisor){return view('supervisors.edit',['supervisor'=>$supervisor,'teachers'=>Teacher::with('user')->get()]);}
    public function update(Request $request, Supervisor $supervisor){
        $data=$request->validate([
            'teacher_id'=>'sometimes|exists:teachers,id',
            'max_students'=>'sometimes|integer|min:1',
            'expertise'=>'nullable|string'
        ]);
        $supervisor->update($data);
        return redirect()->route('web.supervisors.show',$supervisor)->with('status','Cập nhật thành công');
    }
    public function destroy(Supervisor $supervisor){$supervisor->delete(); return redirect()->route('web.supervisors.index')->with('status','Đã xóa');}

// WebSupervisorController.php
    // public function getStudentBySupervisor($supervisorId)
    // {
    //     $idUser = Auth::id();
    //     $user = User::with('teacher.supervisor')
    //     ->with('teacher.supervisor.assignment_supervisors.assignment.student.marjor')
    //     ->findOrFail(Auth::id());
    //     $years = AcademyYear::orderBy('year_name', 'desc')->get();

    //     $terms = ProjectTerm::select('stage')->distinct()->pluck('stage');

    //     $assignmentSupervisors = AssignmentSupervisor::with(['assignment.student.marjor', 'assignment.project', 'assignment.project_term'])
    //         ->whereHas('assignment', function($query) use ($supervisorId) {
    //             $query->where('supervisor_id', $supervisorId);
    //         })
    //         ->get();
    //     return view('lecturer-ui.students', compact( 'user', 'years', 'terms', 'assignmentSupervisors'));
    // }
    public function getStudentBySupervisor($teacherId)
    {
        $idUser = Auth::id();
        $user = User::with('teacher.supervisor')
        ->with('teacher.supervisor.assignment_supervisors.assignment.student.marjor')
        ->findOrFail(Auth::id());
        $years = AcademyYear::orderBy('year_name', 'desc')->get();

        $terms = ProjectTerm::select('stage')->distinct()->pluck('stage');

        $assignmentSupervisors = AssignmentSupervisor::with(['assignment.student.marjor', 'assignment.project', 'assignment.project_term'])
            ->whereHas('supervisor', function($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->get();
        return view('lecturer-ui.students', compact( 'user', 'years', 'terms', 'assignmentSupervisors'));
    }

    public function getAllSupervisorsByTerm($termId){
        $projectTerm = ProjectTerm::with('academy_year')->find($termId);
        $items = Supervisor::with(['teacher.user', 'assignment_supervisors'])
            ->whereHas('project_term', function($query) use ($termId) {
                $query->where('id', $termId);
            })
            ->get();
        return view('assistant-ui.supervisors-detail', compact('items', 'projectTerm'));
    }

    public function getSupervisorNotInProjectTerm($termId)
    {
        $projectTerm = ProjectTerm::with('academy_year')->find($termId);
        $items = Teacher::with('user')
            ->whereDoesntHave('supervisor', function ($query) use ($termId) {
                $query->where('project_term_id', $termId);
            })
            ->get();
        return view('assistant-ui.supervisors-import', compact('items', 'projectTerm'));
    }
    // Thêm danh sách giảng viên vào đợt (bulk)
    public function storeBulk(Request $request)
    {
        $data = $request->validate([
            'project_term_id' => 'required|exists:project_terms,id',
            // Accept array of objects: [{ email: string, max_students?: int }, ...]
            'supervisors'         => 'required|array|min:1',
            'supervisors.*.email' => 'nullable|string',
            'supervisors.*.max_students' => 'nullable|integer|min:0',
            'max_students'    => 'nullable|integer|min:1',
            'expertise'       => 'nullable|string',
            'status'          => 'nullable|string|max:100',
        ]);

        $termId   = (int) $data['project_term_id'];
        $globalMax = $data['max_students'] ?? 5;
        $expertise= $data['expertise'] ?? null;
        $status   = $data['status'] ?? 'active';

        $supItems = collect($data['supervisors']);
        $emails = $supItems->pluck('email')->filter()->map(fn($e) => strtolower(trim($e)))->unique()->values();

        if ($emails->isEmpty()) {
            return $request->expectsJson()
                ? response()->json(['added' => 0, 'skipped' => 0, 'not_found' => []], 422)
                : back()->with('error', 'Danh sách giảng viên trống.');
        }

        // Resolve teacher_id từ email user
        $teachers = Teacher::query()
            ->with(['user:id,email'])
            ->whereHas('user', fn($q) => $q->whereIn('email', $emails))
            ->get(['id','user_id']);

        $mapEmailToTeacher = [];
        foreach ($teachers as $t) {
            $mail = strtolower($t->user?->email ?? '');
            if ($mail) $mapEmailToTeacher[$mail] = $t->id;
        }

        $resolvedIds = collect($mapEmailToTeacher)->values()->unique()->values();
        $notFound = $emails->reject(fn($e) => array_key_exists($e, $mapEmailToTeacher))->values();

        if ($resolvedIds->isEmpty()) {
            return $request->expectsJson()
                ? response()->json(['added' => 0, 'skipped' => 0, 'not_found' => $notFound], 422)
                : back()->with('error', 'Không tìm thấy giảng viên hợp lệ.');
        }

        // Bỏ giảng viên đã có trong đợt
        $existing = Supervisor::query()
            ->where('project_term_id', $termId)
            ->whereIn('teacher_id', $resolvedIds)
            ->pluck('teacher_id');

        $toInsertIds = $resolvedIds->diff($existing)->values();

        // prepare email -> max map from incoming items (use globalMax when not provided)
        $emailToMax = $supItems->mapWithKeys(function($it) use ($globalMax) {
            $email = strtolower(trim($it['email'] ?? ''));
            $m = $it['max_students'] ?? null;
            return [$email => is_null($m) ? null : (int)$m];
        })->all();

        // invert mapEmailToTeacher to lookup email by teacher_id
        $mapTeacherToEmail = array_flip($mapEmailToTeacher);

        $now = now();
        $rows = $toInsertIds->map(function($tid) use ($termId, $mapTeacherToEmail, $emailToMax, $globalMax, $expertise, $status, $now) {
            $email = $mapTeacherToEmail[$tid] ?? null;
            $maxFor = $email ? ($emailToMax[$email] ?? null) : null;
            $quota = $maxFor ?? $globalMax;
            return [
                'teacher_id'      => $tid,
                'project_term_id' => $termId,
                'max_students'    => $quota,
                'expertise'       => $expertise,
                'status'          => $status,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        })->all();

        $added = 0;
        DB::transaction(function () use ($rows, &$added) {
            if (empty($rows)) return;
            $added = DB::table('supervisors')->insertOrIgnore($rows);
        });

        $skipped = $resolvedIds->count() - $toInsertIds->count();

        if ($request->expectsJson()) {
            return response()->json([
                'added'     => $added,
                'skipped'   => $skipped,
                'not_found' => $notFound,
            ]);
        }

        return back()->with('status', "Đã thêm {$added} giảng viên. Bỏ qua {$skipped}.")
                     ->with('not_found', $notFound);
    }
}
