<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RegisterProjectTerm;
use Illuminate\Http\Request;
use App\Models\Assignment;
use Illuminate\Support\Facades\DB;

class RegisterProjectTermController extends Controller
{
    //

    public function getRegiterProjectTermByTermId($termId) {
        $registerProjectTerms = RegisterProjectTerm::with(['student.user','projectTerm.academy_year'])
            ->where('project_term_id', $termId)
            ->get();
            return view('assistant-ui.assignment-registration', compact('registerProjectTerms'));
    }

    /**
     * Approve a registerProjectTerm: update status and create Assignment if not exists
     */
    public function approve(Request $request, RegisterProjectTerm $registerProjectTerm)
    {
        try {
            $result = DB::transaction(function () use ($registerProjectTerm) {
                // update register status
                $registerProjectTerm->status = 'approved';
                $registerProjectTerm->save();

                // create assignment if not exists for same student + term
                $exists = Assignment::where('student_id', $registerProjectTerm->student_id)
                    ->where('project_term_id', $registerProjectTerm->project_term_id)
                    ->exists();

                if (!$exists) {
                    $assignment = Assignment::create([
                        'student_id' => $registerProjectTerm->student_id,
                        'project_id' => null,
                        'project_term_id' => $registerProjectTerm->project_term_id,
                        'status' => 'actived',
                    ]);
                    return ['ok' => true, 'assignment_id' => $assignment->id];
                }

                return ['ok' => true, 'assignment_id' => null];
            });

            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(array_merge($result, ['message' => 'Đã duyệt đăng ký.']));
            }

            return back()->with('status', 'Đã duyệt đăng ký.');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['ok' => false, 'message' => 'Lỗi khi duyệt đăng ký', 'error' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Lỗi khi duyệt đăng ký');
        }
    }

    /**
     * Reject a registerProjectTerm: update status only
     */
    public function reject(Request $request, RegisterProjectTerm $registerProjectTerm)
    {
        try {
            $registerProjectTerm->status = 'rejected';
            $registerProjectTerm->save();

            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['ok' => true, 'message' => 'Đã từ chối đăng ký.']);
            }

            return back()->with('status', 'Đã từ chối đăng ký.');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['ok' => false, 'message' => 'Lỗi khi từ chối đăng ký', 'error' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Lỗi khi từ chối đăng ký');
        }
    }
}
