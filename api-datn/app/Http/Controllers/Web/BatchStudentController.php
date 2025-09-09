<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BatchStudent;
use App\Models\Assignment;
use App\Models\ProjectTerm;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchStudentController extends Controller
{
    public function index(){ $items=BatchStudent::with(['student.user','project_term.academy_year'])->latest('id')->paginate(15); return view('batch_students.index',compact('items')); }
    public function create(){ return view('batch_students.create',['item'=>new BatchStudent(),'students'=>Student::with('user')->get(),'terms'=>ProjectTerm::with('academy_year')->get()]); }
    public function store(Request $request){ $data=$request->validate(['student_id'=>'required|exists:students,id','project_term_id'=>'required|exists:project_terms,id','status'=>'required|string|max:100']); $i=BatchStudent::create($data); return redirect()->route('web.batch_students.show',$i)->with('status','Tạo thành công'); }
    public function show(BatchStudent $batch_student){ return view('batch_students.show',['item'=>$batch_student->load(['student.user','project_term.academy_year'])]); }
    public function edit(BatchStudent $batch_student){ return view('batch_students.edit',['item'=>$batch_student,'students'=>Student::with('user')->get(),'terms'=>ProjectTerm::with('academy_year')->get()]); }
    public function update(Request $request, BatchStudent $batch_student){ $data=$request->validate(['student_id'=>'sometimes|exists:students,id','project_term_id'=>'sometimes|exists:project_terms,id','status'=>'sometimes|string|max:100']); $batch_student->update($data); return redirect()->route('web.batch_students.show',$batch_student)->with('status','Cập nhật thành công'); }
    public function destroy(BatchStudent $batch_student){ $batch_student->delete(); return redirect()->route('web.batch_students.index')->with('status','Đã xóa'); }

    public function getAllBatchStudentsByTerm($termId){
        $projectTerm = ProjectTerm::with('academy_year')->find($termId);
        $items = Assignment::with(['student.user'])
            ->whereHas('project_term', function($query) use ($termId) {
                $query->where('id', $termId);
            })
            ->get();
        return view('assistant-ui.students-detail', compact('items', 'projectTerm'));
    }
    
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

    // Thêm danh sách sinh viên vào đợt (bulk)
    public function storeBulk(Request $request)
    {
        $data = $request->validate([
            'project_term_id' => 'required|exists:project_terms,id',
            'students' => 'required|array|min:1',
            'students.*' => 'required', // id hoặc student_code
            'status' => 'nullable|string|max:100',
        ]);

        $termId = (int) $data['project_term_id'];
        $status = $data['status'] ?? 'active';
        $inputs = collect($data['students'])->filter()->values();

        // Tách id số và mã student_code
        $idList = $inputs->filter(fn($v) => is_numeric($v))->map(fn($v) => (int) $v)->values();
        $codeList = $inputs->filter(fn($v) => !is_numeric($v))->map(fn($v) => (string) $v)->values();

        // Resolve về student_id
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
                : back()->with('error', 'Không tìm thấy sinh viên hợp lệ để thêm.');
        }

        // Loại bỏ sinh viên đã có trong đợt (kiểm tra trên bảng batch_students)
        $existing = BatchStudent::query()
            ->where('project_term_id', $termId)
            ->whereIn('student_id', $resolvedIds)
            ->pluck('student_id');

        $toInsertIds = $resolvedIds->diff($existing)->values();

        $now = now();
        $rows = $toInsertIds->map(fn($sid) => [
            'student_id' => $sid,
            'project_term_id' => $termId,
            'status' => $status,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        $added = 0;
        DB::transaction(function () use ($rows, &$added) {
            if (empty($rows)) return;
            // insertOrIgnore để an toàn với race condition
            $added = DB::table('batch_students')->insertOrIgnore($rows);
        });

        $skipped = $resolvedIds->count() - $toInsertIds->count();

        if ($request->expectsJson()) {
            return response()->json([
                'added' => $added,
                'skipped' => $skipped,
                'not_found' => $notFound,
            ]);
        }

        return back()->with('status', "Đã thêm {$added} sinh viên. Bỏ qua {$skipped}.")
            ->with('not_found', $notFound);
    }
}
