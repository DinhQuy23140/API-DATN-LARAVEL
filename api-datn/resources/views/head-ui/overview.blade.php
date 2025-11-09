<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Trưởng bộ môn - Tổng quan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
    .sidebar-collapsed .sidebar-label { display:none; }
    .sidebar-collapsed .sidebar { width:72px; }
    .sidebar { width:260px; }
  </style>
</head>
  @php
    $user = auth()->user();
    $userName = $user->fullname ?? $user->name ?? 'Giảng viên';
    $email = $user->email ?? '';
    // Tùy mô hình dữ liệu, thay các field bên dưới cho khớp
    $dept = $user->department_name ?? optional($user->teacher)->department ?? '';
    $faculty = $user->faculty_name ?? optional($user->teacher)->faculty ?? '';
    $subtitle = trim(($dept ? "Bộ môn $dept" : '') . (($dept && $faculty) ? ' • ' : '') . ($faculty ? "Khoa $faculty" : ''));
    $degree = $user->teacher->degree ?? '';
    $supervisorId = $user->teacher->supervisor->id ?? 0;
    $teacherId = $user->teacher->id ?? 0;
    $avatarUrl = $user->avatar_url
      ?? $user->profile_photo_url
      ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
  @endphp
<body class="bg-slate-50 text-slate-800">
  <div class="flex min-h-screen">
    <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-indigo-600 text-white"><i class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Head</div>
          <div class="text-xs text-slate-500">Bảng điều khiển</div>
        </div>
      </div>
      @php
        $isThesisOpen = request()->routeIs('web.head.thesis_internship') || request()->routeIs('web.head.thesis_rounds');
      @endphp
      <nav class="flex-1 overflow-y-auto p-3">
        <a href="{{ route('web.head.overview') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.head.overview') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <i class="ph ph-gauge"></i><span class="sidebar-label">Tổng quan</span>
        </a>
        <a href="{{ route('web.head.profile', ['teacherId' => $teacherId]) }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.head.profile') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <i class="ph ph-user"></i><span class="sidebar-label">Hồ sơ</span>
        </a>
        <a href="{{ route('web.head.research') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.head.research') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <i class="ph ph-flask"></i><span class="sidebar-label">Nghiên cứu</span>
        </a>
        <a href="{{ route('web.head.students') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.head.students') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span>
        </a>

        <!-- Mục cha: Học phần tốt nghiệp -->
        <button type="button" id="toggleThesisMenu"
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg mt-3
                       {{ $isThesisOpen ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <span class="flex items-center gap-3">
            <i class="ph ph-graduation-cap"></i>
            <span class="sidebar-label">Học phần tốt nghiệp</span>
          </span>
          <i id="thesisCaret" class="ph ph-caret-down transition-transform {{ $isThesisOpen ? 'rotate-180' : '' }}"></i>
        </button>

        <!-- Submenu -->
        <div id="thesisSubmenu" class="mt-1 pl-3 space-y-1 {{ $isThesisOpen ? '' : 'hidden' }}">
          <a href="{{ route('web.head.thesis_internship') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.head.thesis_internship') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span>
          </a>
          <a href="{{ route('web.head.thesis_rounds') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.head.thesis_rounds') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span>
          </a>
        </div>
      </nav>
      <div class="p-3 border-t border-slate-200">
        <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
      </div>
    </aside>

    <div class="flex-1 h-screen overflow-hidden flex flex-col">
      <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Tổng quan</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <span class="text-slate-500">Trang chủ</span>
            </nav>
          </div>
        </div>
        <div class="relative">
          <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
            <img class="h-9 w-9 rounded-full object-cover" src="{{$avatarUrl}}" alt="avatar" />
            <div class="hidden sm:block text-left">
              <div class="text-sm font-semibold leading-4">{{ $userName }}</div>
              <div class="text-xs text-slate-500">{{ $email }}</div>
            </div>
            <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
          </button>
          <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Hồ sơ</a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 space-y-6">
        <div class="max-w-7xl mx-auto space-y-6">
          <section class="bg-white border rounded-xl p-5">
            <h2 class="font-semibold mb-4">Tổng quan nhanh</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div class="p-4 border rounded-lg bg-gradient-to-br from-indigo-50 to-white">
                <div class="text-xs text-slate-500">Giảng viên</div>
                <div class="text-2xl font-semibold mt-1">34</div>
              </div>
              <div class="p-4 border rounded-lg bg-gradient-to-br from-emerald-50 to-white">
                <div class="text-xs text-slate-500">Sinh viên</div>
                <div class="text-2xl font-semibold mt-1">512</div>
              </div>
              <div class="p-4 border rounded-lg bg-gradient-to-br from-amber-50 to-white">
                <div class="text-xs text-slate-500">Đợt đang mở</div>
                <div class="text-2xl font-semibold mt-1">2</div>
              </div>
              <div class="p-4 border rounded-lg bg-gradient-to-br from-rose-50 to-white">
                <div class="text-xs text-slate-500">Hội đồng</div>
                <div class="text-2xl font-semibold mt-1">8</div>
              </div>
            </div>
          </section>

          <section class="bg-white border rounded-xl p-5">
            <h2 class="font-semibold mb-4">Hoạt động gần đây</h2>
            <ul class="space-y-2 text-sm text-slate-600" id="recentActivities"></ul>
          </section>
        </div>
      </main>
    </div>
  </div>

  <script>
    (function(){
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const h=document.querySelector('header'); const m=document.querySelector('main');
        if(c){ html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); }
        else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]'); }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('head_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('head_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
      const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    })();

    // Toggle submenu Học phần tốt nghiệp
    (function () {
      const btn = document.getElementById('toggleThesisMenu');
      const menu = document.getElementById('thesisSubmenu');
      const caret = document.getElementById('thesisCaret');
      btn?.addEventListener('click', () => {
        menu?.classList.toggle('hidden');
        caret?.classList.toggle('rotate-180');
        btn?.classList.toggle('bg-slate-100');
        btn?.classList.toggle('font-semibold');
      });
    })();

    // Mock recent activities
    const act=[
      'Giảng viên Nguyễn A cập nhật điểm phản biện','Tạo hội đồng CNTT-09','Phê duyệt đề cương mới','Cập nhật danh sách sinh viên xét tốt nghiệp'
    ];
    document.getElementById('recentActivities').innerHTML = act.map(a=>`<li class='pl-3 relative before:content-["•"] before:absolute before:left-0 before:text-indigo-500'>${a}</li>`).join('');
  </script>
</body>
</html>
