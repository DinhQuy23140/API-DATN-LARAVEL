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

        $termId      = (int) $data['project_term_id'];
        $projectId = $data['project_id'] ?? null;
        $status      = $data['status'] ?? 'active';
        $inputs      = collect($data['students'])->filter()->values();

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
}
