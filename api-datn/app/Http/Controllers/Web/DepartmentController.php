<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\departmentRoles;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    //
    public function loadDepartments() {
        $departments = Department::with('departmentRoles.teacher.user', 'teachers', 'subjects')->get();
        $teachers = Teacher::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'teacher');
            })
            ->latest('created_at')->get();
        return response()->view('assistant-ui.manage-departments', compact('departments', 'teachers'));
    }
    public function store(Request $request)
    {
        // Chuẩn hóa input (trim)
        $request->merge([
            'code' => strtoupper(trim((string) $request->input('code'))),
            'name' => trim((string) $request->input('name')),
        ]);

        $data = $request->validate([
            'code'        => ['required', 'string', 'max:50', Rule::unique('departments', 'code')],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            // Cho phép truyền faculty_id, nếu không có sẽ mặc định = 1
            'faculty_id'  => ['nullable', 'integer', 'exists:faculties,id'],
            // head_id: có thể là user_id hoặc teacher_id (sẽ resolve bên dưới)
            'head_id'     => ['nullable', 'integer'],
        ]);

        $facultyId = $data['faculty_id'] ?? 1;

        // Resolve head teacher id (chấp nhận user_id hoặc teacher_id)
        $headTeacherId = $this->resolveHeadTeacherId($data['head_id'] ?? null);

        try {
            DB::beginTransaction();

            $department = Department::create([
                'code'        => $data['code'],
                'name'        => $data['name'],
                'description' => $data['description'] ?? null,
                'faculty_id'  => $facultyId,
            ]);

            // Lưu/ cập nhật vai trò Trưởng bộ môn trong department_roles
            if ($headTeacherId) {
                departmentRoles::updateOrCreate(
                    ['department_id' => $department->id, 'role' => 'head'],
                    ['teacher_id' => $headTeacherId]
                );
            }

            $department->load([
                'departmentRoles.teacher.user',
                'subjects',
                'teachers',
            ]);

            DB::commit();

            return response()->json([
                'ok'   => true,
                'data' => $department,
                'message' => 'Tạo bộ môn thành công',
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Create department failed', ['err' => $e->getMessage()]);
            return response()->json([
                'ok'   => false,
                'message' => 'Không thể tạo bộ môn',
            ], 500);
        }
    }

    // Hỗ trợ: nhận head_id là user_id (ưu tiên) hoặc teacher_id
    protected function resolveHeadTeacherId(?int $headId): ?int
    {
        if (!$headId) return null;

        // Ưu tiên xem headId là user_id
        $teacherId = Teacher::where('user_id', $headId)->value('id');
        if ($teacherId) return $teacherId;

        // Nếu không phải user_id, thử coi như teacher_id hợp lệ
        $teacherId = Teacher::where('id', $headId)->value('id');
        return $teacherId ?: null;
    }

    public function update(Request $request, int $id)
    {
        $department = Department::findOrFail($id);

        // Chuẩn hóa input
        $request->merge([
            'code' => strtoupper(trim((string) $request->input('code', $department->code))),
            'name' => trim((string) $request->input('name', $department->name)),
        ]);

        $data = $request->validate([
            'code'        => [
                'required',
                'string',
                'max:50',
                Rule::unique('departments', 'code')->ignore($department->id),
            ],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'faculty_id'  => ['nullable', 'integer', 'exists:faculties,id'],
            'head_id'     => ['nullable', 'integer'],
        ]);

        // Resolve head teacher id
        $headTeacherId = null;
        $hasHeadField = $request->exists('head_id'); // phân biệt có gửi lên để cho phép clear
        if ($hasHeadField) {
            $headTeacherId = $this->resolveHeadTeacherId($data['head_id']);
            if (filled($data['head_id']) && !$headTeacherId) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Trưởng bộ môn không hợp lệ',
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            // Cập nhật departments
            $department->code        = $data['code'];
            $department->name        = $data['name'];
            $department->description = $data['description'] ?? $department->description;
            if (isset($data['faculty_id'])) {
                $department->faculty_id = $data['faculty_id'];
            }
            $department->save();

            // Cập nhật department_roles (role = head)
            if ($hasHeadField) {
                if ($headTeacherId) {
                    departmentRoles::updateOrCreate(
                        ['department_id' => $department->id, 'role' => 'head'],
                        ['teacher_id' => $headTeacherId]
                    );
                } else {
                    // Xóa head nếu client gửi head_id rỗng/null
                    departmentRoles::where('department_id', $department->id)
                        ->where('role', 'head')
                        ->delete();
                }
            }

            $department->load([
                'departmentRoles.teacher.user',
                'subjects',
                'teachers',
            ]);

            DB::commit();

            return response()->json([
                'ok'   => true,
                'data' => $department,
                'message' => 'Cập nhật bộ môn thành công',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Update department failed', [
                'department_id' => $id,
                'err' => $e->getMessage(),
            ]);
            return response()->json([
                'ok' => false,
                'message' => 'Không thể cập nhật bộ môn',
            ], 500);
        }
    }

    public function destroy(int $id)
    {
        $department = Department::findOrFail($id);

        try {
            DB::transaction(function () use ($department) {
                // Xóa vai trò liên quan (vd: head)
                departmentRoles::where('department_id', $department->id)->delete();

                // Nếu có các quan hệ ràng buộc khác, detach/xóa tại đây (tùy schema):
                // $department->subjects()->detach(); // nếu là many-to-many
                // $department->teachers()->detach(); // nếu là many-to-many

                $department->delete(); // soft delete hoặc hard delete tùy model
            });

            return response()->json([
                'ok' => true,
                'message' => 'Xóa bộ môn thành công',
            ]);
        } catch (\Throwable $e) {
            Log::error('Delete department failed', [
                'department_id' => $id,
                'error' => $e->getMessage(),
            ]);

            // Nếu vướng ràng buộc FK có thể trả 409
            return response()->json([
                'ok' => false,
                'message' => 'Không thể xóa bộ môn. Vui lòng kiểm tra ràng buộc dữ liệu.',
            ], 409);
        }
    }
}
