<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Models\Department;
use App\Models\departmentRoles;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DepartmentRolesController extends Controller
{
    public function loadDepartmentRoles(){
        $teachers = Teacher::with('user')
        ->whereDoesntHave('departmentRoles')
        ->get();
        $departmentsNotHead = Department::whereDoesntHave('departmentRoles')->get();
        $departments = departmentRoles::with('department', 'teacher.user')->get();
        return view('assistant-ui.assign-head', compact('teachers', 'departments', 'departmentsNotHead'));
    }

    public function delete($id) {
        $departmentRole = departmentRoles::findOrFail($id);
        $departmentRole->delete();
        return response()->json(['ok'=>true]);
    }

    public function assignHead(Request $request)
    {
        $data = $request->validate([
            'teacher_id'    => ['required', 'integer', 'exists:teachers,id'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'role'          => ['nullable', 'in:head'], // optional, mặc định 'head'
        ]);

        $teacherId    = (int) $data['teacher_id'];
        $departmentId = (int) $data['department_id'];

        try {
            $role = DB::transaction(function () use ($teacherId, $departmentId) {
                // Một bộ môn chỉ có 1 head: cập nhật nếu đã tồn tại, ngược lại tạo mới
                $role = departmentRoles::updateOrCreate(
                    ['department_id' => $departmentId, 'role' => 'head'],
                    ['teacher_id' => $teacherId]
                );

                // Nạp quan hệ để trả về cho FE
                $role->load(['teacher.user', 'department']);

                return $role;
            });

            return response()->json([
                'ok'   => true,
                'data' => $role,
                'message' => 'Gán Trưởng bộ môn thành công',
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Assign head failed', [
                'department_id' => $departmentId,
                'teacher_id' => $teacherId,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'ok' => false,
                'message' => 'Không thể gán Trưởng bộ môn',
            ], 500);
        }
    }
}
