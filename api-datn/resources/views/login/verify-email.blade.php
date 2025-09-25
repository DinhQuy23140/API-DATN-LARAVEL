<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác thực Email | TLU Graduation Project</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen flex items-center justify-center bg-gradient-to-br from-sky-200 to-sky-400">
    <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md text-center">
        <h1 class="text-2xl font-bold text-slate-700 mb-3">Xác thực Email</h1>
        <p class="text-slate-600 mb-4">
            Chúng tôi đã gửi link xác thực đến email của bạn.<br>
            Vui lòng kiểm tra hộp thư <span class="font-medium text-sky-600">{{ auth()->user()->email }}</span>.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="bg-green-100 text-green-700 py-2 px-3 rounded mb-3">
                Link xác thực mới đã được gửi đến email của bạn!
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
            @csrf
            <button type="submit"
                class="w-full py-2 px-4 rounded-lg bg-sky-600 hover:bg-sky-700 text-white font-semibold transition">
                Gửi lại link xác thực
            </button>
        </form>

        <form method="POST" action="{{ route('web.auth.logout') }}">
            @csrf
            <button type="submit"
                class="w-full py-2 px-4 rounded-lg bg-gray-500 hover:bg-gray-600 text-white font-semibold transition">
                Đăng xuất
            </button>
        </form>
    </div>
</body>
</html>
