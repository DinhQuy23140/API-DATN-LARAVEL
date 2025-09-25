<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    // Click link verify email
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill(); // đánh dấu verified
        return redirect()->route('verification.success'); // redirect sang verify-success
    }

    // Gửi lại link xác thực
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Link xác thực đã được gửi!');
    }
}
