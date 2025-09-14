<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReportFiles;
use Illuminate\Http\Request;

class ReportFilesController extends Controller
{
    public function index() {
        $data = ReportFiles::all();
        return response()->json($data);
    }

    public function show($id) {
        $data = ReportFiles::find($id);
        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['message' => 'Report file not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file_name'   => 'required|string|max:255',
            'file_url'    => 'required|string|max:255', // khớp schema (không phải file_path)
            'file_type'   => 'nullable|string|max:255',
            'type_report' => 'required|string|max:255',
            'project_id'  => 'required|integer|exists:projects,id',
        ]);

        $reportFile = ReportFiles::create($validated);

        return response()->json($reportFile, 201);
    }


    public function update(Request $request, $id) {
        $reportFile = ReportFiles::find($id);
        if (!$reportFile) {
            return response()->json(['message' => 'Report file not found'], 404);
        }

        $request->validate([
            'file_name' => 'sometimes|required|string|max:255',
            'file_path' => 'sometimes|required|string|max:255',
            'uploaded_by' => 'sometimes|required|integer',
        ]);

        $reportFile->update($request->all());
        return response()->json($reportFile);
    }

    public function destroy($id) {
        $reportFile = ReportFiles::find($id);
        if (!$reportFile) {
            return response()->json(['message' => 'Report file not found'], 404);
        }

        $reportFile->delete();
        return response()->json(['message' => 'Report file deleted successfully']);
    }

    public function getFilesByProject($projectId) {
        $files = ReportFiles::where('project_id', $projectId)->latest('created_at')->get();
        return response()->json($files);
    }

    public function getReportFileByProjectAndType($projectId, $typeReport) {
        $file = ReportFiles::where('project_id', $projectId)
            ->where('type_report', $typeReport)
            ->latest('created_at')
            ->get();

        if ($file) {
            return response()->json($file);
        } else {
            return response()->json(['message' => 'No report file found for the specified project and type'], 404);
        }
    }
}
