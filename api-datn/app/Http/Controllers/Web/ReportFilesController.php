<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ReportFiles;
use Illuminate\Http\Request;

class ReportFilesController extends Controller
{
    public function setStatus(Request $request, ReportFiles $reportFile)
    {
        $data = $request->validate([
            'status' => 'required|string|in:approved,rejected,submitted,none',
        ]);

        $reportFile->status = $data['status'];
        $reportFile->save();

        $map = [
            'none'      => ['label' => 'Chưa có',  'class' => 'bg-slate-100 text-slate-600'],
            'submitted' => ['label' => 'Đã nộp',   'class' => 'bg-amber-100 text-amber-700'],
            'approved'  => ['label' => 'Đã duyệt', 'class' => 'bg-emerald-100 text-emerald-700'],
            'rejected'  => ['label' => 'Bị từ chối','class' => 'bg-rose-100 text-rose-700'],
        ];
        $ui = $map[$reportFile->status] ?? $map['none'];

        return response()->json([
            'ok'     => true,
            'id'     => $reportFile->id,
            'status' => $reportFile->status,
            'label'  => $ui['label'],
            'class'  => $ui['class'],
        ]);
    }
}
