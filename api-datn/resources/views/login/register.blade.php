<?php ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng ký | TLU Graduation Project</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-sky-200 via-sky-300 to-sky-400 flex items-center justify-center p-4">

  {{-- Toast trạng thái --}}
  @if (session('status'))
    <div id="alertSuccess"
         class="fixed top-4 right-4 z-50 flex items-start gap-3 bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl shadow-lg">
      <div>✅</div>
      <div class="pr-6">{{ session('status') }}</div>
      <button type="button" onclick="document.getElementById('alertSuccess').classList.add('hidden')"
              class="ml-auto text-green-700 hover:text-green-900">✖</button>
    </div>
  @endif

  @if ($errors->any())
    <div id="alertError"
         class="fixed top-4 right-4 z-50 flex items-start gap-3 bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-xl shadow-lg">
      <div>⚠️</div>
      <div class="pr-6">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      <button type="button" onclick="document.getElementById('alertError').classList.add('hidden')"
              class="ml-auto text-red-700 hover:text-red-900">✖</button>
    </div>
  @endif

  <div class="w-full max-w-2xl"> <!-- tăng max width -->
    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-2xl overflow-hidden">
      <div class="px-8 pt-8 text-center">
        <img src="{{ asset('images/logo_tlu.png') }}" alt="TLU" class="mx-auto h-16 w-16 rounded-full shadow mb-3">
        <h1 class="text-2xl font-bold text-slate-800">Đăng ký tài khoản</h1>
        <p class="text-slate-500 text-sm mt-1">Tạo tài khoản để sử dụng hệ thống</p>
      </div>

      <div class="p-8">
        <form method="POST" action="{{ route('register.post') }}" class="space-y-5" id="registerForm">
          @csrf

          <!-- Fullname + Email -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="fullname" class="block text-sm font-medium text-slate-700 mb-1">Họ và tên *</label>
              <div class="relative">
                <i class="ph ph-user text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-lg"></i>
                <input
                  type="text"
                  id="fullname"
                  name="fullname"
                  value="{{ old('fullname') }}"
                  placeholder="Nguyễn Văn A"
                  required
                  class="w-full pl-11 pr-3 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400"
                />
              </div>
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email *</label>
              <div class="relative">
                <i class="ph ph-envelope text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-lg"></i>
                <input
                  type="email"
                  id="email"
                  name="email"
                  value="{{ old('email') }}"
                  autocomplete="email"
                  placeholder="you@example.com"
                  required
                  class="w-full pl-11 pr-3 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400"
                />
              </div>
            </div>
          </div>

          <!-- Password + Confirm -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Mật khẩu *</label>
              <div class="relative">
                <i class="ph ph-lock-key text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-lg"></i>
                <input
                  type="password"
                  id="password"
                  name="password"
                  autocomplete="new-password"
                  placeholder="••••••••"
                  required
                  class="w-full pl-11 pr-11 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400"
                />
                <button
                  type="button"
                  id="togglePassword"
                  class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700"
                  aria-label="Hiện/ẩn mật khẩu"
                >
                  <i id="toggleIcon" class="ph ph-eye text-xl"></i>
                </button>
              </div>
            </div>
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Xác nhận mật khẩu *</label>
              <div class="relative">
                <i class="ph ph-lock text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-lg"></i>
                <input
                  type="password"
                  id="password_confirmation"
                  name="password_confirmation"
                  autocomplete="new-password"
                  placeholder="••••••••"
                  required
                  class="w-full pl-11 pr-3 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400"
                />
              </div>
            </div>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            class="w-full py-3 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-semibold shadow-lg shadow-sky-200 transition"
          >
            Đăng ký
          </button>

          <!-- Link -->
          <div class="text-center text-sm">
            <p class="text-slate-600">Đã có tài khoản?
              <a href="{{ route('web.auth.login') }}" class="text-sky-700 hover:text-sky-900 font-medium">Đăng nhập</a>
            </p>
          </div>
        </form>
      </div>
    </div>

    <p class="text-center text-xs text-white/90 mt-4">
      © {{ date('Y') }} TLU. All rights reserved.
    </p>
  </div>

  <script>
    setTimeout(() => {
      document.getElementById('alertSuccess')?.classList.add('hidden');
      document.getElementById('alertError')?.classList.add('hidden');
    }, 4000);

    const toggleBtn = document.getElementById('togglePassword');
    const pwdInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    toggleBtn?.addEventListener('click', () => {
      const isHidden = pwdInput.type === 'password';
      pwdInput.type = isHidden ? 'text' : 'password';
      toggleIcon.className = isHidden ? 'ph ph-eye-slash text-xl' : 'ph ph-eye text-xl';
    });
  </script>
</body>
</html>
