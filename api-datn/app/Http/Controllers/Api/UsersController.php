<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class UsersController extends Controller
{
    /**
     * Đăng nhập và trả về user + token.
     * POST /api/auth/login
     * Body: { email, password, device_name? }
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string']
        ]);

        // $user = User::where('email', $data['email'])->first();
        // if (!$user || !Hash::check($data['password'], $user->password)) {
        //         'success' => false,
        //         'message' => 'Sai email hoặc mật khẩu'
        //     ], 401);
        // }

        $user = User::where('email', $data['email'])->first();

        if (!$user || $data['password'] !== $user->password) {
            return response()->json([
                'success' => false,
                'message' => 'Sai email hoặc mật khẩu'
            ], 401);
        }


        // Xóa tất cả token cũ để đảm bảo chỉ duy nhất 1 phiên đăng nhập
        $user->tokens()->delete();

        // Tạo token mới
        $token = $user->createToken('api-token')->plainTextToken;

        // Load thông tin student nếu có
        $user->loadMissing('student');

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'phone' => $user->phone,
                'dob' => $user->dob,
                'gender' => $user->gender,
                'image' => $user->image,
                'address' => $user->address,
                'role' => $user->role,
                'student' => $user->student ? [
                    'id' => $user->student->id,
                    'student_code' => $user->student->student_code,
                    'class_code' => $user->student->class_code,
                    'major_id' => $user->student->major_id,
                    'department_id' => $user->student->department_id,
                    'course_year' => $user->student->course_year,
                ] : null,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:6'],
        ]);

        DB::beginTransaction();

        try {
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);

            Student::create([
                'user_id' => $user->id,
                'student_code' => 'CLS' . strtoupper(substr(uniqid(), -6)),
            ]);

            DB::commit();

            // Tạo token đăng nhập ngay sau khi đăng ký
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công',
                'token_type' => 'Bearer',
                'access_token' => $token,
                'user' => [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Register failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đăng ký thất bại, vui lòng thử lại sau.',
            ], 500);
        }
    }

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

    /**
     * Đăng xuất: thu hồi token hiện tại (Bearer token gửi kèm request)
     * POST /api/auth/logout  (middleware: auth:sanctum)
     */
    public function logout(Request $request)
    {
        if ($request->user() && $request->user()->currentAccessToken()) {
            $current = $request->user()->currentAccessToken();
            $tokenId = method_exists($current, 'getKey') ? $current->getKey() : null;
            if ($tokenId) {
                $request->user()->tokens()->where('id', $tokenId)->delete();
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Đã đăng xuất'
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $data = $request->validate(['email' => ['required','email']]);

        $status = Password::sendResetLink($data);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => trans($status)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => trans($status)
        ], 400);
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => ['required','string'],
            'password_confirmation' => ['required','string'],
        ]);

        $status = Password::reset(
            [
                'email' => $data['email'],
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
                'token' => $data['token'],
            ],
            function ($user, $password) {
                // Hash the password and save
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['success' => true, 'message' => trans($status)]);
        }

        return response()->json(['success' => false, 'message' => trans($status)], 400);
    }

    /**
     * Change password for authenticated API user.
     * POST /api/auth/change-password (auth:sanctum)
     * Body: { current_password, password, password_confirmation }
     */
    // public function changePassword(Request $request)
    // {
    //     $data = $request->validate([
    //         'current_password' => ['required','string'],
    //         'password' => ['required','string','min:8','confirmed'],
    //     ]);

    //     $user = $request->user();
    //     if (!$user) {
    //         return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
    //     }

    //     $currentOk = false;
    //     if (\Illuminate\Support\Facades\Hash::check($data['current_password'], $user->password)) {
    //         $currentOk = true;
    //     } elseif ($user->password === $data['current_password']) {
    //         // legacy plain-text support
    //         $currentOk = true;
    //     }

    //     if (!$currentOk) {
    //         return response()->json(['success' => false, 'message' => 'Mật khẩu hiện tại không đúng'], 422);
    //     }

    //     // Update password (hash) and revoke existing tokens
    //     $user->password = \Illuminate\Support\Facades\Hash::make($data['password']);
    //     $user->save();

    //     // revoke all previous tokens to force re-login (we'll return a fresh token)
    //     try {
    //         $user->tokens()->delete();
    //     } catch (\Throwable $e) {
    //         // ignore token deletion errors
    //     }

    //     $newToken = $user->createToken('api-token')->plainTextToken;

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Đã đổi mật khẩu thành công',
    //         'access_token' => $newToken,
    //         'token_type' => 'Bearer'
    //     ]);
    // }

public function changePassword(Request $request)
{
    $data = $request->validate([
        'current_password' => ['required','string'],
        'password' => ['required','string'],
    ]);

    $user = $request->user();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
    }

    // ❗❗ BỎ Hash::check – so sánh trực tiếp mật khẩu nhập vào với mật khẩu trong DB
    if ($user->password !== $data['current_password']) {
        return response()->json([
            'success' => false,
            'message' => 'Mật khẩu hiện tại không đúng (so sánh trực tiếp DB)'
        ], 422);
    }

    // ✔ Đổi mật khẩu (nhớ hash để lưu lại)
    $user->password = \Illuminate\Support\Facades\Hash::make($data['password']);
    $user->save();

    try {
        $user->tokens()->delete();
    } catch (\Throwable $e) {
        // ignore errors
    }

    $newToken = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Đổi mật khẩu thành công (bản test – compare trực tiếp)',
        'access_token' => $newToken,
        'token_type' => 'Bearer'
    ]);
}

}
