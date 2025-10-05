<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BatchStudent;
use App\Models\ProjectTerm;

class AssignmentController extends Controller
{
    public function index(){
        $assignments = Assignment::with(['student.user','supervisor.teacher','project'])->latest('id')->paginate(15);
        return view('assignments.index', compact('assignments'));
    }
    public function create(){
        return view('assignments.create', [
            'assignment'=> new Assignment(),
            'students'=>Student::with('user')->get(),
            'supervisors'=>Supervisor::with('teacher')->get(),
            'projects'=>Project::all()
        ]);
    }
    public function store(Request $request){
        $data=$request->validate([
            'student_id'=>'required|exists:students,id',
            'supervisor_id'=>'required|exists:supervisors,id',
            'project_id'=>'required|exists:projects,id',
            'status'=>'required|string|max:100'
        ]);
        $a=Assignment::create($data);
        return redirect()->route('web.assignments.show',$a)->with('status','Tạo thành công');
    }
    public function show(Assignment $assignment){$assignment->load(['student.user','supervisor.teacher','project']); return view('assignments.show', compact('assignment'));}
    public function edit(Assignment $assignment){
        return view('assignments.edit', [
            'assignment'=>$assignment,
            'students'=>Student::with('user')->get(),
            'supervisors'=>Supervisor::with('teacher')->get(),
            'projects'=>Project::all()
        ]);
    }
    public function update(Request $request, Assignment $assignment){
        $data=$request->validate([
            'student_id'=>'sometimes|exists:students,id',
            'supervisor_id'=>'sometimes|exists:supervisors,id',
            'project_id'=>'sometimes|exists:projects,id',
            'status'=>'sometimes|string|max:100'
        ]);
        $assignment->update($data);
        return redirect()->route('web.assignments.show',$assignment)->with('status','Cập nhật thành công');
    }
    public function destroy(Assignment $assignment){$assignment->delete(); return redirect()->route('web.assignments.index')->with('status','Đã xóa');}

    public function getStudentNotInProjectTerm($termId)
    {
        $projectTerm = ProjectTerm::with('academy_year')->find($termId);
        $items = Student::with('user')
            ->whereDoesntHave('assignment', function ($query) use ($termId) {
                $query->where('project_term_id', $termId);
            })
            ->get();
        return view('assistant-ui.students-import', compact('items', 'projectTerm'));
    }

    // Bulk tạo Assignment cho danh sách sinh viên trong 1 đợt
    public function storeBulk(Request $request)
    {
        $data = $request->validate([
            'project_term_id' => 'required|exists:project_terms,id',
            'project_id'      => 'nullable|exists:projects,id',
            'students'        => 'required|array|min:1',
            'students.*'      => 'required', // id hoặc student_code
            'status'          => 'nullable|string|max:100',
        ]);

        $termId   = (int) $data['project_term_id'];
        $projectId = $data['project_id'] ?? null;

        // Chuẩn hoá status và đổi 'active' -> 'actived'
        $statusRaw = $data['status'] ?? 'inactive';
        $status = is_string($statusRaw) ? strtolower(trim($statusRaw)) : 'inactive';
        if ($status === 'active') {
            $status = 'actived';
        }

        $inputs = collect($data['students'])->filter()->values();

        // Chuẩn hoá danh sách sinh viên: chấp nhận id hoặc student_code
        $idList   = $inputs->filter(fn($v) => is_numeric($v))->map(fn($v) => (int) $v)->values();
        $codeList = $inputs->filter(fn($v) => !is_numeric($v))->map(fn($v) => (string) $v)->values();

        $students = Student::query()
            ->when($idList->isNotEmpty(), fn($q) => $q->orWhereIn('id', $idList))
            ->when($codeList->isNotEmpty(), fn($q) => $q->orWhereIn('student_code', $codeList))
            ->get(['id','student_code']);

        $resolvedIds = $students->pluck('id')->unique()->values();
        $notFound = $inputs->reject(function ($v) use ($students) {
            if (is_numeric($v)) return $students->contains('id', (int) $v);
            return $students->contains('student_code', (string) $v);
        })->values();

        if ($resolvedIds->isEmpty()) {
            return $request->expectsJson()
                ? response()->json(['added' => 0, 'skipped' => 0, 'not_found' => $notFound], 422)
                : back()->with('error', 'Không tìm thấy sinh viên hợp lệ để phân công.');
        }

        // Bỏ những sinh viên đã có Assignment trong đợt này
        $existing = Assignment::query()
            ->where('project_term_id', $termId)
            ->whereIn('student_id', $resolvedIds)
            ->pluck('student_id');

        $toAssignIds = $resolvedIds->diff($existing)->values();

        $now = now();
        $rows = $toAssignIds->map(fn($sid) => [
            'student_id'      => $sid,
            'project_id'      => $projectId,
            'project_term_id' => $termId,
            'status'          => $status,
            'created_at'      => $now,
            'updated_at'      => $now,
        ])->all();

        $added = 0;
        DB::transaction(function () use ($rows, &$added) {
            if (empty($rows)) return;
            // insertOrIgnore để tránh trùng do race condition
            $added = DB::table('assignments')->insertOrIgnore($rows);
        });

        $skipped = $resolvedIds->count() - $toAssignIds->count();

        if ($request->expectsJson()) {
            return response()->json([
                'added'     => $added,
                'skipped'   => $skipped,
                'not_found' => $notFound,
            ]);
        }

        return back()->with('status', "Đã tạo {$added} phân công. Bỏ qua {$skipped} (đã tồn tại).")
                     ->with('not_found', $notFound);
    }

    public function getAssignmentByStudentIdAndTermId($studentId, $termId, $supervisorId)
    {
        $assignment = Assignment::with(['student.user','assignment_supervisors.supervisor.teacher.user','project.progressLogs.attachments','project.reportFiles'])
                        ->where('student_id', $studentId)
                        ->where('project_term_id', $termId)
                        ->first();
        // if (!$assignment) {
        //     return redirect()->back()->with('error', 'Không tìm thấy phân công phù hợp.');
        // }
        return view('lecturer-ui.supervised-student-detail', compact('assignment', 'supervisorId'));
    }

    public function assign(Request $request)
    {
        $data = $request->validate([
            'termId'        => 'required|integer|exists:project_terms,id',
            'supervisorId'  => 'required|integer|exists:supervisors,id',
            'assignments'   => 'required|array|min:1',
            'assignments.*' => 'required|integer|exists:assignments,id',
        ]);

        $termId = (int) $data['termId'];
        $supervisorId = (int) $data['supervisorId'];
        $ids = collect($data['assignments'])->map(fn($v)=>(int)$v)->values();

        // Chỉ cập nhật các assignment thuộc đúng đợt và chưa có phản biện
        $updated = Assignment::query()
            ->where('project_term_id', $termId)
            ->whereIn('id', $ids)
            ->whereNull('counter_argument_id')
            ->update([
                'counter_argument_id' => $supervisorId,
                'updated_at' => now(),
            ]);

        $skipped = $ids->count() - $updated;

        return response()->json([
            'updated' => $updated,
            'skipped' => $skipped,
            'message' => "Đã phân công {$updated} đề cương, bỏ qua {$skipped}.",
        ]);
    }

    public function outlineReviewAssignments($termId, $supervisorId)
    {
        $rows = Assignment::with(['student.user','project.reportFiles'])
            ->where('project_term_id', $termId)
            ->where('counter_argument_id', $supervisorId)
            ->get();

        return view('lecturer-ui.outline-review-assignments', compact('rows', 'termId', 'supervisorId'));
    }

    public function setCounterStatus(Request $request, Assignment $assignment)
    {
        $payload = $request->validate([
            'status' => 'required|string|in:approved,rejected,pending,todo,progress,done',
        ]);

        $assignment->counter_argument_status = $payload['status'];
        $assignment->save();

        $map = [
            'approved' => ['label' => 'Đã duyệt',  'class' => 'bg-emerald-100 text-emerald-700'],
            'rejected' => ['label' => 'Từ chối',   'class' => 'bg-rose-100 text-rose-700'],
            'pending'  => ['label' => 'Chưa chấm', 'class' => 'bg-slate-100 text-slate-600'],
            'todo'     => ['label' => 'Cần chấm',  'class' => 'bg-amber-100 text-amber-700'],
            'progress' => ['label' => 'Đang chấm', 'class' => 'bg-blue-100 text-blue-700'],
            'done'     => ['label' => 'Hoàn tất',  'class' => 'bg-emerald-100 text-emerald-700'],
        ];
        $ui = $map[$assignment->counter_argument_status] ?? $map['pending'];

        return response()->json([
            'id'     => $assignment->id,
            'status' => $assignment->counter_argument_status,
            'label'  => $ui['label'],
            'class'  => $ui['class'],
            'ok'     => true,
        ]);
    }
}
