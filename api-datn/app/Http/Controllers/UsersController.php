<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Hiển thị danh sách người dùng (có phân trang & optional include quan hệ).
     * GET /api/users
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $includes = collect(explode(',', (string) $request->query('include')))
            ->filter()
            ->intersect(['student', 'teacher'])
            ->all();

        $query = User::query();
        if (!empty($includes)) {
            $query->with($includes);
        }

        // Simple search theo fullname hoặc email nếu truyền tham số q
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate($perPage)->appends($request->query());

        return response()->json($users);
    }

    /**
     * Tạo mới một người dùng.
     * POST /api/users
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'dob'      => ['required', 'date'],
            'gender'   => ['required', 'string', 'in:male,female,other'],
            'image'    => ['nullable', 'string', 'max:500'],
            'role'     => ['required', 'string', 'in:student,teacher,admin'],
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json(['message' => 'User created', 'data' => $user], 201);
    }

    /**
     * Hiển thị chi tiết một người dùng.
     * GET /api/users/{user}
     */
    public function show(Request $request, User $user)
    {
        $includes = collect(explode(',', (string) $request->query('include')))
            ->filter()
            ->intersect(['student', 'teacher'])
            ->all();

        if (!empty($includes)) {
            $user->load($includes);
        }

        return response()->json($user);
    }

    /**
     * Cập nhật thông tin người dùng.
     * PUT/PATCH /api/users/{user}
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'fullname' => ['sometimes', 'string', 'max:255'],
            'email'    => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['sometimes', 'string', 'min:6'],
            'dob'      => ['sometimes', 'date'],
            'gender'   => ['sometimes', 'string', 'in:male,female,other'],
            'image'    => ['nullable', 'string', 'max:500'],
            'role'     => ['sometimes', 'string', 'in:student,teacher,admin'],
        ]);

        if (array_key_exists('password', $data)) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json(['message' => 'User updated', 'data' => $user]);
    }

    /**
     * Xóa người dùng.
     * DELETE /api/users/{user}
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
