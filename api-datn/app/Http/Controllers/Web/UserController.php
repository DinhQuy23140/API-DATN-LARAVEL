<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
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
        return redirect()->route('web.users.show', $user)->with('status', 'Tạo người dùng thành công');
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
