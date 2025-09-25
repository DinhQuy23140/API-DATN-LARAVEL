<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('login.register');
    }
    public function showVerifySuccess()
    {
        return view('login.verify-success');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                // function ($attribute, $value, $fail) {
                //     if (!str_ends_with($value, '@e.tlu.com.vn')) {
                //         $fail('Email phải có đuôi @e.tlu.com.vn');
                //     }
                // }
            ],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $userId = $user->id;
        $teacher = Teacher::create([
            'user_id' => $userId,
            'teacher_code' => 'T' . str_pad($userId, 5, '0', STR_PAD_LEFT),
        ]);

        event(new Registered($user));

        auth()->login($user);

        return redirect()->route('verification.notice');
    }
}
