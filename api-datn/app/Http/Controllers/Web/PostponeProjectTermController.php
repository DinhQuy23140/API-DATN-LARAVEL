<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Models\Assignment;
use App\Models\PostponeProjectTerm;
use Illuminate\Http\Request;

class PostponeProjectTermController extends Controller
{
    //
    public function getPostponeProjectTermsByTermId($termId)
    {
        $postponeProjectTerms = PostponeProjectTerm::where('project_term_id', $termId)->with('assignment.student.user', 'postponeProjectTermFiles')->get();
        return view("assistant-ui.deferments", ['postponeProjectTerms' => $postponeProjectTerms, 'termId' => $termId]);
    }

    public function approveDeferment(PostponeProjectTerm $postponeProjectTerm, Request $request)
    {
        $data = $request->validate([
            'assignment_id' => 'required|integer|exists:assignments,id',
            'note' => 'nullable|string',
            'status' => 'nullable|string|in:pending,approved,rejected',
        ]);

        $postponeProjectTerm->status = $data['status'] ?? 'approved';
        $postponeProjectTerm->save();

        // If an assignment id was provided, remove that assignment (business logic kept from original)
        Assignment::where('id', $data['assignment_id'])->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã phê duyệt đơn xin hoãn thành công.']);
        }

        return redirect()->back()->with('status', 'Đã phê duyệt đơn xin hoãn thành công.');
    }

    public function rejectDeferment(PostponeProjectTerm $postponeProjectTerm, Request $request)
    {
        $data = $request->validate([
            'note' => 'nullable|string',
            'status' => 'nullable|string|in:pending,approved,rejected',
        ]);

        $postponeProjectTerm->status = $data['status'] ?? 'rejected';
        $postponeProjectTerm->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã từ chối đơn xin hoãn thành công.']);
        }

        return redirect()->back()->with('status', 'Đã từ chối đơn xin hoãn thành công.');
    }
}
