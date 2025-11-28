<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đặt lại mật khẩu | TLU Graduation Project</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="min-h-screen bg-gradient-to-br from-sky-200 via-sky-300 to-sky-400 flex items-center justify-center p-4">

  <div class="w-full max-w-md">
    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-2xl overflow-hidden">
      <div class="px-8 pt-8 text-center">
        <img src="{{ asset('images/logo_tlu.png') }}" alt="TLU" class="mx-auto h-16 w-16 rounded-full shadow mb-3">
        <h1 class="text-2xl font-bold text-slate-800">Đặt lại mật khẩu</h1>
        <p class="text-slate-500 text-sm mt-1">Nhập mật khẩu mới cho tài khoản của bạn.</p>
      </div>

      <div class="p-8">
        @if ($errors->any())
          <div class="mb-4 text-red-700">
            <ul class="list-disc pl-5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
          @csrf
          <input type="hidden" name="token" value="{{ $token }}" />

          <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
            <div class="relative">
              <i class="ph ph-envelope text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-lg"></i>
              <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $email ?? '') }}"
                required
                class="w-full pl-11 pr-3 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400"
              />
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Mật khẩu mới</label>
            <input id="password" name="password" type="password" required class="w-full px-3 py-3 rounded-xl border border-slate-300" />
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Xác nhận mật khẩu</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full px-3 py-3 rounded-xl border border-slate-300" />
          </div>

          <button type="submit" class="w-full py-3 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-semibold shadow-lg transition">Đặt lại mật khẩu</button>
        </form>
      </div>
    </div>

    <p class="text-center text-xs text-white/90 mt-4">
      © {{ date('Y') }} TLU. All rights reserved.
    </p>
  </div>
</body>
</html>