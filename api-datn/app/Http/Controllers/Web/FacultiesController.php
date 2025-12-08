<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Faculties;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\FacultyRoles;

class FacultiesController extends Controller
{
    public function index()
    {
        $faculties = Faculties::with(['facultyRoles.user'])->latest('id')->paginate(15);
        return view('faculties.index', compact('faculties'));
    }

    public function load_dashboard()
    {
        $faculties = Faculties::with(['facultyRoles.user'])->latest('id')->get();
        $teachers  = Teacher::with('user')->get();

        return view('admin-ui.manage-faculties', compact('faculties','teachers'));
    }

    private function rules(?Faculties $faculty = null): array
    {
        $id = $faculty?->id;

        return [
            'code'         => ['required','string','max:50', Rule::unique('faculties','code')->ignore($id)],
            'name'         => ['required','string','max:255'],
            'short_name'   => ['required','string','max:100', Rule::unique('faculties','short_name')->ignore($id)],
            'description'  => ['nullable','string'],
            'dean_id'      => 'nullable|exists:users,id',
            'vice_dean_id' => 'nullable|exists:users,id',
            'assistant_id' => 'nullable|exists:users,id',
            'phone'        => ['nullable','string','max:50'],
            'email'        => ['nullable','email','max:255'],
            'address'      => ['nullable','string','max:255'],
        ];
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validate($this->rules());

            $rolesData = [
                'dean'      => $data['dean_id']      ?? null,
                'vice_dean' => $data['vice_dean_id'] ?? null,
                'assistant' => $data['assistant_id'] ?? null,
            ];
            unset($data['dean_id'], $data['vice_dean_id'], $data['assistant_id']);

            $faculty = Faculties::create($data);

            foreach ($rolesData as $roleType => $userId) {
                if ($userId) {
                    FacultyRoles::create([
                        'faculty_id' => $faculty->id,
                        'role'       => $roleType,
                        'user_id'    => $userId,
                    ]);
                    User::where('id', $userId)->update(['role' => $roleType]);
                }
            }

            DB::commit();

            return response()->json([
                'ok'      => true,
                'message' => 'Đã tạo khoa mới',
                'data'    => $faculty->load('facultyRoles.user'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Store faculty error', ['err' => $e->getMessage()]);
            return response()->json(['ok' => false, 'message' => 'Không thể tạo khoa'], 500);
        }
    }

    public function update(Request $request, Faculties $faculty)
    {
        DB::beginTransaction();
        try {
            $data = $request->validate($this->rules($faculty));

            $rolesData = [
                'dean'      => $data['dean_id']      ?? null,
                'vice_dean' => $data['vice_dean_id'] ?? null,
                'assistant' => $data['assistant_id'] ?? null,
            ];
            unset($data['dean_id'], $data['vice_dean_id'], $data['assistant_id']);

            $faculty->update($data);

            foreach ($rolesData as $roleType => $userId) {
                $oldRole = FacultyRoles::where('faculty_id', $faculty->id)
                    ->where('role', $roleType)
                    ->first();

                // Nếu thay user khác → reset role user cũ về teacher
                if ($oldRole && $oldRole->user_id != $userId) {
                    User::where('id', $oldRole->user_id)->update(['role' => 'teacher']);
                }

                if ($userId) {
                    FacultyRoles::updateOrCreate(
                        ['faculty_id' => $faculty->id, 'role' => $roleType],
                        ['user_id'    => $userId]
                    );
                    User::where('id', $userId)->update(['role' => $roleType]);
                } else {
                    // Nếu bỏ trống -> xóa role cũ và reset user
                    if ($oldRole) {
                        User::where('id', $oldRole->user_id)->update(['role' => 'teacher']);
                        $oldRole->delete();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'ok'      => true,
                'message' => 'Đã cập nhật khoa',
                'data'    => $faculty->load('facultyRoles.user'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Update faculty error', ['id' => $faculty->id, 'err' => $e->getMessage()]);
            return response()->json(['ok' => false, 'message' => 'Không thể cập nhật khoa'], 500);
        }
    }

    public function destroy(Faculties $faculty)
    {
        try {
            // Reset role user về teacher trước khi xóa
            foreach ($faculty->facultyRoles as $role) {
                User::where('id', $role->user_id)->update(['role' => 'teacher']);
            }

            $faculty->delete();

            return response()->json(['ok'=>true,'message'=>'Đã xóa khoa']);
        } catch (\Throwable $e) {
            Log::error('Delete faculty error', ['id'=>$faculty->id,'err'=>$e]);
            return response()->json(['ok'=>false,'message'=>'Không thể xóa khoa'], 500);
        }
    }

    public function getAssistants() {

        // teachers used for the select (all teachers who are not assistants)
        $userTeachers = User::where('role', '!=' , 'assistant')
        ->where('role', '!=' , 'admin')
        ->where('role', '!=' , 'head')->with('teacher')->get();
        // Build a consistent assistants collection shaped as simple arrays so the view
        // can render name/email/phone/dob/faculty reliably regardless of model shapes.
        $faculties = Faculties::with('facultyRoles.user')->latest()->get();

        $assistants = User::where('role', '=' , 'assistant')->with('teacher')->get();

        return view('admin-ui.manage-assistants', compact('faculties', 'userTeachers', 'assistants'));
    }


    /**
     * Assign a teacher as the assistant for a faculty.
     * Expects: teacher_id (required), faculty_id (optional)
     * Returns JSON { ok: true, assistant: { id, fullname, email }, faculty_id }
     */
    public function assignAssistant(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required','exists:users,id']
        ]);

            DB::beginTransaction();
            try {
                $userId = $data['user_id'];

                User::where('id', $userId)->update(['role' => 'assistant']);

                DB::commit();

                return response()->json(['ok' => true, 'message' => 'Đã phân trợ lý']);
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error('Remove assistant error', ['err' => $e->getMessage(), 'payload' => $request->all()]);
                return response()->json(['ok' => false, 'message' => 'Không thể phân trợ lý'], 500);
            }
    }

    /**
     * Remove assistant role from a user.
     * Expects: user_id (required)
     */
    public function removeAssistant(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required','exists:users,id']
        ]);

        DB::beginTransaction();
        try {
            $userId = $data['user_id'];
            User::where('id', $userId)->update(['role' => 'teacher']);
            DB::commit();
            return response()->json(['ok' => true, 'message' => 'Đã huỷ phân trợ lý']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Remove assistant error', ['err' => $e->getMessage(), 'payload' => $request->all()]);
            return response()->json(['ok' => false, 'message' => 'Không thể huỷ phân trợ lý'], 500);
        }
    }
}
