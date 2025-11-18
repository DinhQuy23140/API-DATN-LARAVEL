<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegisterProjectTerm;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RegisterProjectTermController extends Controller
{
    /**
     * List registrations with optional filters.
     */
    public function index(Request $request)
    {
        $q = RegisterProjectTerm::query();
        if ($request->filled('student_id')) {
            $q->where('student_id', $request->input('student_id'));
        }
        if ($request->filled('project_term_id')) {
            $q->where('project_term_id', $request->input('project_term_id'));
        }
        if ($request->filled('status')) {
            $q->where('status', $request->input('status'));
        }

        $perPage = (int) $request->input('per_page', 20);
        $data = $q->orderBy('id', 'desc')->paginate($perPage);
        return response()->json($data);
    }

    /**
     * Create a new registration
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'project_term_id' => 'required|exists:project_terms,id',
            'status' => 'nullable|string|in:pending,approved,rejected',
        ]);

        DB::beginTransaction();
        try {
            $reg = RegisterProjectTerm::create([
                'student_id' => $data['student_id'],
                'project_term_id' => $data['project_term_id'],
                'status' => $data['status'] ?? 'pending',
            ]);
            DB::commit();
            return response()->json($reg, 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('RegisterProjectTerm store error', ['err' => $e->getMessage(), 'payload' => $request->all()]);
            return response()->json(['message' => 'Không thể lưu đăng ký'], 500);
        }
    }

    /**
     * Show a registration
     */
    public function show(RegisterProjectTerm $register_project_term)
    {
        return response()->json($register_project_term);
    }

    /**
     * Update a registration
     */
    public function update(Request $request, RegisterProjectTerm $register_project_term)
    {
        $data = $request->validate([
            'status' => 'sometimes|string|in:pending,approved,rejected',
            'student_id' => 'sometimes|exists:students,id',
            'project_term_id' => 'sometimes|exists:project_terms,id',
        ]);

        try {
            $register_project_term->update($data);
            return response()->json($register_project_term);
        } catch (\Throwable $e) {
            Log::error('RegisterProjectTerm update error', ['err' => $e->getMessage(), 'id' => $register_project_term->id]);
            return response()->json(['message' => 'Không thể cập nhật đăng ký'], 500);
        }
    }

    /**
     * Delete a registration
     */
    public function destroy(RegisterProjectTerm $register_project_term)
    {
        try {
            $register_project_term->delete();
            return response()->json(['ok' => true]);
        } catch (\Throwable $e) {
            Log::error('RegisterProjectTerm destroy error', ['err' => $e->getMessage(), 'id' => $register_project_term->id]);
            return response()->json(['message' => 'Không thể xóa đăng ký'], 500);
        }
    }

    public function getRegisterProjectTermByStudentId($studentId)
    {
        $result = RegisterProjectTerm::with('projectTerm.academy_year', 'projectTerm.registerProjectTerms')
            ->where('student_id', $studentId)
            ->get();

        return response()->json($result);
    }
}
