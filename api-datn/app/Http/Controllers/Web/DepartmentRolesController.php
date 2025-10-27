<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Models\Department;
use App\Models\departmentRoles;
use App\Models\Teacher;
use App\Models\User;
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

    public function delete($id)
    {
        try {
            $departmentRole = departmentRoles::findOrFail($id);

            // Lấy teacher_id trước khi xóa
            $teacherId = $departmentRole->teacher_id;

            // Xóa bản ghi trong bảng department_roles
            $departmentRole->delete();

            // Nếu có teacher_id, cập nhật role trong bảng users về mặc định
            if ($teacherId) {
                $teacher = Teacher::find($teacherId);
                if ($teacher && $teacher->user_id) {
                    User::where('id', $teacher->user_id)->update(['role' => 'teacher']);
                }
            }

            return response()->json([
                'ok' => true,
                'message' => 'Đã xóa trưởng bộ môn và cập nhật lại vai trò người dùng.',
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Delete department head failed', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Không thể xóa trưởng bộ môn.',
            ], 500);
        }
    }

    public function assignHead(Request $request)
    {
        $data = $request->validate([
            'teacher_id'    => ['required', 'integer', 'exists:teachers,id'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'role'          => ['nullable', 'in:head'], // optional, mặc định 'head'
            'user_id'      => ['required', 'integer', 'exists:users,id'],
        ]);

        $teacherId    = (int) $data['teacher_id'];
        $departmentId = (int) $data['department_id'];
        $userId = (int) $data['user_id'];

        try {
            $role = DB::transaction(function () use ($teacherId, $departmentId, $userId) {
                // Xóa quyền head của người đang giữ chức vụ này (nếu có)
                $oldRole = departmentRoles::where('department_id', $departmentId)
                    ->where('role', 'head')
                    ->first();

                if ($oldRole && $oldRole->teacher_id !== $teacherId) {
                    User::where('id', $oldRole->teacher->user_id ?? null)
                        ->update(['role' => 'teacher']);
                }

                // Gán trưởng bộ môn mới
                $role = departmentRoles::updateOrCreate(
                    ['department_id' => $departmentId, 'role' => 'head'],
                    ['teacher_id' => $teacherId]
                );

                // Cập nhật role user mới
                User::where('id', $userId)->update(['role' => 'head']);

                $role->load(['teacher.user', 'department']);
                return $role;
            });

            return response()->json([
                'ok'   => true,
                'data' => $role,
                'message' => 'Gán Trưởng bộ môn thành công và cập nhật role giáo viên',
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
