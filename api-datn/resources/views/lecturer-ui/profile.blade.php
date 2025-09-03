<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hồ sơ Giảng viên</title>
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
  <body class="bg-slate-50 text-slate-800">
    @php
      $userName = $user->fullname;
          $avatarUrl = $user->avatar_url
          ?? $user->profile_photo_url
          ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    @endphp
    <div class="flex min-h-screen">
      <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
        <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
          <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-chalkboard-teacher"></i></div>
          <div class="sidebar-label">
            <div class="font-semibold">Lecturer</div>
            <div class="text-xs text-slate-500">Bảng điều khiển</div>
          </div>
        </div>
        @php
          $isThesisOpen = request()->routeIs('web.teacher.thesis_internship') || request()->routeIs('web.teacher.thesis_rounds');
        @endphp
        <nav class="flex-1 overflow-y-auto p-3">
          <a href="{{ route('web.teacher.overview') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.overview') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-gauge"></i><span class="sidebar-label">Tổng quan</span>
          </a>

          <a href="{{ route('web.teacher.profile') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.profile') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-user"></i><span class="sidebar-label">Hồ sơ</span>
          </a>

          <a href="{{ route('web.teacher.research') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.research') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-flask"></i><span class="sidebar-label">Nghiên cứu</span>
          </a>

          <a href="{{ route('web.teacher.students', ['supervisorId' => $user->teacher->supervisor->id]) }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.students') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span>
          </a>

          <button type="button" id="toggleThesisMenu"
                  class="w-full flex items-center justify-between px-3 py-2 rounded-lg mt-3
                         {{ $isThesisOpen ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <span class="flex items-center gap-3">
              <i class="ph ph-graduation-cap"></i>
              <span class="sidebar-label">Học phần tốt nghiệp</span>
            </span>
            <i id="thesisCaret" class="ph ph-caret-down transition-transform {{ $isThesisOpen ? 'rotate-180' : '' }}"></i>
          </button>

          <div id="thesisSubmenu" class="mt-1 pl-3 space-y-1 {{ $isThesisOpen ? '' : 'hidden' }}">
            <a href="{{ route('web.teacher.thesis_internship') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_internship') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
              <i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span>
            </a>
            <a href="{{ route('web.teacher.thesis_rounds') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_rounds') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
              <i class="ph ph-calendar"></i><span class="sidebar-label">Học phần tốt nghiệp</span>
            </a>
          </div>
        </nav>
        <div class="p-3 border-t border-slate-200">
          <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
        </div>
      </aside>

  <div class="flex-1">
        <header class="fixed left-0 md:left-[260px] right-0 top-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-20">
          <div class="flex items-center gap-3 flex-1">
            <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
            <div>
              <h1 class="text-lg md:text-xl font-semibold">Hồ sơ cá nhân</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Giảng viên / Hồ sơ cá nhân</nav>
            </div>
          </div>
          <div class="relative">
            <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
              <img class="h-9 w-9 rounded-full object-cover" src="{{$avatarUrl}}}" alt="avatar" />
              <span class="hidden sm:block text-sm">{{ $user->email }}</span>
              <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
            </button>
            <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
              <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông tin</a>
              <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
            </div>
          </div>
        </header>

  <main class="pt-20 px-4 md:px-6 pb-10">
          <div class="max-w-6xl mx-auto">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sidebar card -->
            <aside class="bg-white rounded-xl border border-slate-200 p-5">
              <div class="grid place-items-center">
                <img id="avatarPreview" class="h-24 w-24 rounded-full object-cover" src="{{ $avatarUrl }}" alt="avatar" />
                <div class="mt-3 text-center">
                  <div class="font-semibold">{{ $user->fullname }}</div>
                  <div class="text-sm text-slate-500">{{ $user->teacher->position }}</div>
                </div>
              </div>
            </aside>

            <!-- Form -->
            <section class="bg-white rounded-xl border border-slate-200 p-5 lg:col-span-2">
              <form class="space-y-4 max-w-2xl" onsubmit="event.preventDefault(); alert('Đã cập nhật!');">
                <div class="grid sm:grid-cols-2 gap-4">
                  <div><label class="text-sm font-medium">Họ tên</label><input class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600" value="{{ $user->fullname }}"/></div>
                  <div><label class="text-sm font-medium">Email</label><input type="email" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" value="{{ $user->email }}"/></div>
                </div>
                <div class="grid sm:grid-cols-3 gap-4">
                  <div><label class="text-sm font-medium">Số điện thoại</label><input class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" placeholder="09xxxxxxxx" value="{{ $user->phone }}"/></div>
                  <div>
                    <label class="text-sm font-medium">Học hàm</label>
                    <select class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200">
                      <option value="Thạc sĩ" {{ $user->teacher->degree == 'Thạc sĩ' ? 'selected' : '' }}>Thạc sĩ</option>
                      <option value="Phó Giáo sư" {{ $user->teacher->degree == 'Phó Giáo sư' ? 'selected' : '' }}>Phó Giáo sư</option>
                      <option value="Cử nhân" {{ $user->teacher->degree == 'Cử nhân' ? 'selected' : '' }}>Cử nhân</option>
                      <option value="Giáo sư" {{ $user->teacher->degree == 'Giáo sư' ? 'selected' : '' }}>Giáo sư</option>
                      <option value="Tiến sĩ" {{ $user->teacher->degree == 'Tiến sĩ' ? 'selected' : '' }}>Tiến sĩ</option>
                    </select>
                  </div>
                  <div>
                    <label class="text-sm font-medium">Học vị</label>
                    <select class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200">
                      <option value="Thạc sĩ" {{ $user->teacher->degree == 'Thạc sĩ' ? 'selected' : '' }}>Thạc sĩ</option>
                      <option value="Phó Giáo sư" {{ $user->teacher->degree == 'Phó Giáo sư' ? 'selected' : '' }}>Phó Giáo sư</option>
                      <option value="Cử nhân" {{ $user->teacher->degree == 'Cử nhân' ? 'selected' : '' }}>Cử nhân</option>
                      <option value="Giáo sư" {{ $user->teacher->degree == 'Giáo sư' ? 'selected' : '' }}>Giáo sư</option>
                      <option value="Tiến sĩ" {{ $user->teacher->degree == 'Tiến sĩ' ? 'selected' : '' }}>Tiến sĩ</option>
                    </select>
                  </div>
                </div>
                <div>
                  <label class="text-sm font-medium">Ảnh đại diện</label>
                  <input id="avatarInput" type="file" accept="image/*" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                </div>
                <div class="pt-2">
                  <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Cập nhật</button>
                </div>
              </form>
            </section>
          </div>
        </div>
        </main>
      </div>
    </div>

    <script>
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
  function setCollapsed(c){const h=document.querySelector('header');const m=document.querySelector('main'); if(c){html.classList.add('sidebar-collapsed');h.classList.add('md:left-[72px]');h.classList.remove('md:left-[260px]');m.classList.add('md:pl-[72px]');m.classList.remove('md:pl-[260px]');} else {html.classList.remove('sidebar-collapsed');h.classList.remove('md:left-[72px]');h.classList.add('md:left-[260px]');m.classList.remove('md:pl-[72px]');m.classList.add('md:pl-[260px]');}}
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed');setCollapsed(c);localStorage.setItem('lecturer_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      // Avatar preview
      const input=document.getElementById('avatarInput'); const preview=document.getElementById('avatarPreview');
      input?.addEventListener('change', (e)=>{const file=e.target.files?.[0]; if(!file) return; const reader=new FileReader(); reader.onload=()=>{preview.src=reader.result;}; reader.readAsDataURL(file);});

  // profile dropdown
  const profileBtn=document.getElementById('profileBtn');
  const profileMenu=document.getElementById('profileMenu');
  profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
  document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

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
    </script>
  </body>
</html>
