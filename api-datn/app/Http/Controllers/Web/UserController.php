<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSupervisor;
use App\Models\Research;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class UserController extends Controller
{
    // Hiển thị form đăng nhập (Web)
    public function showLoginForm()
    {
        // kết nối tới resources/views/login/login.blade.php
        return view('login.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
        ]);

        $user = User::where('email', $data['email'])->first();
        //     return back()
        //         ->withErrors(['email' => 'Thông tin đăng nhập không đúng'])
        //         ->onlyInput('email');
        // Sai email hoặc mật khẩu
        if (!$user || $user->password !== $data['password']) {
            return back()
                ->withErrors(['email' => 'Thông tin đăng nhập không đúng'])
                ->onlyInput('email');
        }
        // if (!$user || !Hash::check($data['password'], $user->password)) {
        //     return back()
        //         ->withErrors(['email' => 'Thông tin đăng nhập không đúng'])
        //         ->onlyInput('email');
        // }

        // Nếu user cần verify email và chưa verify
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            // Đăng nhập tạm để gửi mail (hoặc không cần nếu muốn)
            Auth::login($user);
            // Gửi lại email xác thực (có throttle ở route)
            try {
                $user->sendEmailVerificationNotification();
            } catch (\Throwable $e) {
                // im lặng
            }
            // Đăng xuất để tránh vào được khu vực authenticated

            return redirect()
                ->route('verification.notice')
                ->with('status', 'Vui lòng kiểm tra email để xác thực trước khi đăng nhập.');
        }

        // Đăng nhập chính thức
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $user->loadMissing([
            'teacher.supervisor.assignment_supervisors.assignment.student',
            'teacher.departmentRoles',
        ]);

        switch ($user->role) {
            case 'teacher':
            case 'dean':
            case 'head':
            case 'vice_dean':
                return redirect()->route('web.teacher.overview')->with('status','Đăng nhập thành công');
            case 'admin':
                return redirect()->route('web.admin.dashboard')->with('status','Đăng nhập thành công');
            case 'assistant':
                return redirect()->route('web.assistant.dashboard')->with('status','Đăng nhập thành công');
        }

        return redirect()->intended('/')->with('status','Đăng nhập thành công');
    }

    public function showOverView()
    {
        $id = Auth::id();
        $user = User::with('teacher.supervisor', 'userResearches.research', 'teacher.departmentRoles')
        ->with('teacher.supervisor.assignment_supervisors.assignment.project_term.academy_year')
        ->findOrFail(Auth::id());
        $assignmentSupervisors = AssignmentSupervisor::with(['assignment.student.marjor', 'assignment.project', 'assignment.project_term'])
        ->whereHas('supervisor', fn($query) => $query->where('teacher_id', $user->teacher->id))
        ->get();
        return view('lecturer-ui.overview', compact('user', 'assignmentSupervisors'));
    }

    public function loadResearch() {
        $listResearch = Research::all();
        $userResearch = User::with('userResearches.research', 'teacher.departmentRoles')->findOrFail(Auth::id());
        return view('lecturer-ui.research', compact('userResearch', 'listResearch'));
    }

    public function showProfile()
    {
        $id = Auth::id();
        $user = User::with('teacher.supervisor', 'teacher.departmentRoles')->findOrFail($id);
        return view('lecturer-ui.profile', compact('user'));
    }
    
    //login 
    // public function login(Request $request)
    // {
    //     $data = $request->validate([
    //         'email' => ['required','email'],
    //         'password' => ['required','string'],
    //     ]);

    //     // Tìm user theo email
    //     $user = User::where('email', $data['email'])->first();

    //     // Kiểm tra user tồn tại và password có khớp không (dùng bcrypt hash check)
    //     if (!$user || !Hash::check($data['password'], $user->password)) {
    //         return back()
    //             ->withErrors(['email' => 'Thông tin đăng nhập không đúng'])
    //             ->onlyInput('email');
    //     }

    //     // Đăng nhập user
    //     Auth::login($user, $request->boolean('remember'));
    //     $request->session()->regenerate();

    //     // Load thêm quan hệ cần thiết (nếu có)
    //     $user->loadMissing('teacher', 'supervisor');

    //     // Nếu là giáo viên → chuyển tới giao diện giảng viên
    //     if ($user->role === 'teacher') {
    //         return redirect()->route('web.teacher.overview')
    //             ->with('status', 'Đăng nhập thành công');
    //     }

    //     // Mặc định
    //     return redirect()->intended(route('web.users.index'))
    //         ->with('status', 'Đăng nhập thành công');
    // }


    // Đăng xuất (Web)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('web.auth.login')
            ->with('status', 'Đã đăng xuất');
    }

    // Danh sách người dùng
    public function index(Request $request)
    {
        $q = $request->query('q');
        $users = User::query()
            ->when($q, function ($query) use ($q) {
                $query->where('fullname', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
            })
            ->latest('id')
            ->paginate(15)
            ->appends($request->query());

        return view('users.index', compact('users', 'q'));
    }

    // Form tạo
    public function create()
    {
        $user = new User();
        return view('users.create', compact('user'));
    }

    // Lưu người dùng mới
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
        return redirect()->route('web.teacher.show', $user)->with('status', 'Tạo người dùng thành công');
    }

    // Chi tiết
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Form sửa
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Cập nhật
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'fullname' => ['sometimes', 'required', 'string', 'max:255'],
            'email'    => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users','email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'dob'      => ['sometimes', 'required', 'date'],
            'gender'   => ['sometimes', 'required', 'string', 'in:male,female,other'],
            'image'    => ['nullable', 'string', 'max:500'],
            'role'     => ['sometimes', 'required', 'string', 'in:student,teacher,admin'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('web.users.show', $user)->with('status', 'Cập nhật thành công');
    }

    // Xóa
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('web.users.index')->with('status', 'Đã xóa người dùng');
    }
}
