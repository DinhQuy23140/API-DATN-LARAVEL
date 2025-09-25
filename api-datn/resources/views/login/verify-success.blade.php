<!-- resources/views/auth/verify-success.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Xác thực email thành công</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
@php
  $user = auth()->user();
  // Điều hướng theo vai trò (tùy biến role field)
  $role = $user->role ?? null;
  $roleRouteMap = [
    'admin'     => 'web.admin.dashboard',
    'teacher'   => 'web.teacher.overview',
    'assistant' => 'web.assistant.dashboard',
    'head'      => 'web.head.overview',
  ];
  $targetRoute = $role && isset($roleRouteMap[$role])
      ? route($roleRouteMap[$role])
      : route('web.admin-ui.dashboard'); // fallback
@endphp
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-200 via-green-300 to-green-400 p-4">
  <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full text-center">
    <h1 class="text-2xl font-bold text-green-700 mb-4">🎉 Email đã được xác thực!</h1>
    <p class="text-green-600 mb-6">
      @if($user)
        Bạn đã sẵn sàng sử dụng hệ thống.
      @else
        Bạn có thể đăng nhập và sử dụng hệ thống ngay bây giờ.
      @endif
    </p>

    @if($user)
      <a href="{{ $targetRoute }}"
         class="block w-full mb-3 px-6 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold">
        Vào hệ thống
      </a>
      <form method="POST" action="{{ route('web.auth.logout') }}">
        @csrf
        <button class="w-full px-6 py-3 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium">
          Đăng xuất
        </button>
      </form>
      <p class="mt-4 text-xs text-slate-500">Tự động chuyển sau <span id="sec">5</span>s...</p>
    @else
      <a href="{{ route('web.auth.login') }}"
         class="inline-block px-6 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold">
        Đăng nhập
      </a>
    @endif
  </div>

  @if($user)
  <script>
    let left = 5;
    const el = document.getElementById('sec');
    const timer = setInterval(()=>{
      left--; if(el) el.textContent = left;
      if(left<=0){
        clearInterval(timer);
        window.location.href = @json($targetRoute);
      }
    },1000);
  </script>
  @endif
</body>
</html>
