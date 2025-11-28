<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Trợ lý khoa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <style>
      :root { --primary:#2563eb; }
      body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
      .sidebar-collapsed .sidebar-label { display:none; }
      .sidebar-collapsed .sidebar { width:72px; }
      .sidebar { width:260px; }
      .submenu {
        display: none;
      }
      .submenu.hidden {
        display: none;
      }
      .submenu:not(.hidden) {
        display: block;
      }
    </style>
  </head>
  <body class="bg-slate-50 text-slate-800">
    @php
      $user = auth()->user();
      $userName = $user->fullname ?? $user->name ?? 'Giảng viên';
      $email = $user->email ?? '';
      // Tùy mô hình dữ liệu, thay các field bên dưới cho khớp
      $dept = $user->department_name ?? optional($user->teacher)->department ?? '';
      $faculty = $user->faculty_name ?? optional($user->teacher)->faculty ?? '';
      $subtitle = trim(($dept ? "Bộ môn $dept" : '') . (($dept && $faculty) ? ' • ' : '') . ($faculty ? "Khoa $faculty" : ''));
      $degree = $user->teacher->degree ?? '';
      $avatarUrl = $user->avatar_url
        ?? $user->profile_photo_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    @endphp
    <div class="flex min-h-screen">
      <!-- Sidebar -->
      <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
        <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
          <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-buildings"></i></div>
          <div class="sidebar-label">
            <div class="font-semibold">Assistant</div>
            <div class="text-xs text-slate-500">Quản trị khoa</div>
          </div>
        </div>
        <nav class="flex-1 overflow-y-auto p-3">
          <a href="{{ route('web.assistant.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 text-slate-900 font-semibold"><i class="ph ph-gauge"></i><span class="sidebar-label">Bảng điều khiển</span></a>
          <a href="{{ route('web.assistant.manage_departments') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-buildings"></i><span class="sidebar-label">Bộ môn</span></a>
          <a href="{{ route('web.assistant.manage_majors') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
          <a href="{{ route('web.assistant.manage_staffs') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
          <a href="{{ route('web.assistant.assign_head') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Phân trưởng bộ môn</span></a>
          <div class="graduation-item">
            <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button">
              <span class="flex items-center gap-3">
                <i class="ph ph-graduation-cap"></i>
                <span class="sidebar-label">Học phần tốt nghiệp</span>
              </span>
              <i class="ph ph-caret-down"></i>
            </div>
            <div class="submenu hidden pl-6">
              <a href="internship.html" class="block px-3 py-2 hover:bg-slate-100"><i class="ph ph-briefcase"></i> Thực tập tốt nghiệp</a>
              <a href="{{ route('web.assistant.rounds') }}" class="block px-3 py-2 hover:bg-slate-100"><i class="ph ph-calendar"></i> Đồ án tốt nghiệp</a>
            </div>
          </div>
        </nav>
        <div class="p-3 border-t border-slate-200">
          <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
        </div>
      </aside>

      <!-- Main area -->
      <div class="flex-1 h-screen overflow-hidden flex flex-col">
        <!-- Topbar -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
          <div class="flex items-center gap-3 flex-1">
            <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
            <div>
              <h1 class="text-lg md:text-xl font-semibold">Bảng điều khiển trợ lý khoa</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Dashboard</nav>
            </div>
          </div>
          <div class="relative">
            <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
              <img class="h-9 w-9 rounded-full object-cover" src="{{ $avatarUrl }}" alt="avatar" />
              <div class="hidden sm:block text-left">
                <div class="text-sm font-semibold leading-4">{{ $userName }}</div>
                <div class="text-xs text-slate-500">{{ $email }}</div>
              </div>
              <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
            </button>
            <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
              <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông tin</a>
              <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
              <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
                @csrf
              </form>
            </div>
          </div>
        </header>

        <!-- Content -->
        <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 space-y-6">
          <div class="max-w-6xl mx-auto space-y-6">
          <!-- Stats cards -->
          <section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
              <div class="h-12 w-12 rounded-lg bg-blue-50 text-blue-600 grid place-items-center"><i class="ph ph-buildings text-xl"></i></div>
              <div><div class="text-sm text-slate-500">Bộ môn</div><div class="text-2xl font-semibold">{{ $marjorCount }}</div></div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
              <div class="h-12 w-12 rounded-lg bg-blue-50 text-blue-600 grid place-items-center"><i class="ph ph-book-open-text text-xl"></i></div>
              <div><div class="text-sm text-slate-500">Ngành</div><div class="text-2xl font-semibold">{{ $departmentCount }}</div></div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
              <div class="h-12 w-12 rounded-lg bg-blue-50 text-blue-600 grid place-items-center"><i class="ph ph-chalkboard-teacher text-xl"></i></div>
              <div><div class="text-sm text-slate-500">Giảng viên</div><div class="text-2xl font-semibold">{{ $countTeachers }}</div></div>
            </div>
          </section>

          <section class="grid grid-cols-1 gap-6">

            <!-- Latest lecturers -->
            <div class="bg-white rounded-xl border border-slate-200 p-5">
              <div class="flex items-center justify-between"><h2 class="font-semibold">Giảng viên mới thêm</h2>
                <div class="relative">
                  <input id="teacherSearch" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tên" />
                  <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
              </div>
              <div class="mt-4 overflow-x-auto">
                <table class="w-full text-sm">
                  <thead>
                    <tr class="text-left text-slate-500">
                      <th class="py-2.5 border-b w-10"><input id="chkAll" type="checkbox" class="h-4 w-4" /></th>
                      <th class="py-2.5 border-b">Mã GV</th>
                      <th class="py-2.5 border-b">Tên</th>
                      <th class="py-2.5 border-b">Bộ môn</th>
                      <th class="py-2.5 border-b">Ngày thêm</th>
                      <th class="py-2.5 border-b text-right">Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($teachers as $teacher)
                        <tr class="hover:bg-slate-50">
                        <td class="py-3"><input type="checkbox" class="rowChk h-4 w-4" /></td>
                        <td class="py-3">{{ $teacher->id }}</td>
                        <td class="py-3">{{ $teacher->user->fullname }}</td>
                        <td class="py-3">CNTT</td>
                        <td class="py-3">{{ $teacher->created_at->format('d/m/Y') }}</td>
                        <td class="py-3 text-right"><a href="{{ route('web.users.show', $teacher->id) }}" class="text-blue-600 hover:underline">Xem chi tiết</a></td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </section>
          <!-- Project rounds overview -->
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between">
              <h2 class="font-semibold">Đợt đồ án hiện tại & sắp tới</h2>
              <div class="flex items-center gap-2">
                <a href="{{ route('web.assistant.rounds') }}" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm">Xem tất cả</a>
              </div>
            </div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
              @foreach ($projectTerms as $prj)
                <div class="border border-slate-200 rounded-lg p-4">
                  <div class="flex items-center justify-between">
                    @php
                      $name = "Đợt " . $prj->stage . " " . $prj->academy_year->year_name;
                      if($prj->start_date < now() && $prj->end_date > now()) {
                        $statusName = 'Đang diễn ra';
                        $statusBackground = 'bg-emerald-50';
                        $statusText = 'text-emerald-700';
                      } else {
                        $statusName = 'Sắp diễn ra';
                        $statusBackground = 'bg-yellow-50';
                        $statusText = 'text-yellow-700';
                      }
                    @endphp
                    <div class="font-medium">{{ $name }}</div>
                    <span class="px-2 py-1 rounded-full text-xs {{ $statusBackground }} {{ $statusText }}">{{ $statusName }}</span>
                  </div>
                  <div class="text-sm text-slate-600 mt-1">{{ Carbon\Carbon::parse($prj->start_date)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($prj->end_date)->format('d/m/Y') }} • {{ $prj->councils->count() . " Hội đồng" }}</div>
                  <div class="mt-3 flex items-center gap-2">
                    <a class="text-blue-600 hover:underline text-sm" href="{{ route('web.assistant.round_detail', ['round_id' => $prj->id]) }}">Chi tiết</a>
                  </div>
                </div>
              @endforeach
            </div>
          </section>
        </div>
        </main>
      </div>
    </div>

    <script>
      const html = document.documentElement;
      const sidebar = document.getElementById('sidebar');
      const toggleSidebar = document.getElementById('toggleSidebar');
      const openSidebar = document.getElementById('openSidebar');
      function setCollapsed(c){
        const mainArea = document.querySelector('.flex-1');
        if(c){
          html.classList.add('sidebar-collapsed');
          mainArea.classList.add('md:pl-[72px]');
          mainArea.classList.remove('md:pl-[260px]');
        } else {
          html.classList.remove('sidebar-collapsed');
          mainArea.classList.remove('md:pl-[72px]');
          mainArea.classList.add('md:pl-[260px]');
        }
      }
      toggleSidebar?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed');setCollapsed(c);localStorage.setItem('assistant_sidebar',''+(c?1:0));});
      openSidebar?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      // checkbox all
      document.getElementById('chkAll')?.addEventListener('change', (e)=>{
        document.querySelectorAll('.rowChk').forEach(chk=> chk.checked = e.target.checked);
      });

      // profile dropdown
      const profileBtn=document.getElementById('profileBtn');
      const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

      // Local teacher table search (immediate, no debounce)
      (function(){
        const searchInput = document.getElementById('teacherSearch');
        const tbody = document.querySelector('table tbody');
        if(!searchInput || !tbody) return;
        searchInput.addEventListener('input', (e)=>{
          const q = String(e.target.value||'').toLowerCase().trim();
          tbody.querySelectorAll('tr').forEach(row=>{
            const cells = row.querySelectorAll('td');
            const id = (cells[1]?.textContent||'').toLowerCase();
            const name = (cells[2]?.textContent||'').toLowerCase();
            const matches = !q || id.includes(q) || name.includes(q);
            row.style.display = matches ? '' : 'none';
          });
        });
      })();

      // auto active nav highlight
      (function(){
        const current = location.pathname.split('/').pop();
        document.querySelectorAll('aside nav a').forEach(a=>{
          const href=a.getAttribute('href')||'';
          const active=href.endsWith(current);
          a.classList.toggle('bg-slate-100', active);
          a.classList.toggle('font-semibold', active);
          if(active) a.classList.add('text-slate-900');
        });
      })();

      document.addEventListener('DOMContentLoaded', () => {
        const graduationItem = document.querySelector('.graduation-item');
        const toggleButton = graduationItem.querySelector('.toggle-button');
        const submenu = graduationItem.querySelector('.submenu');

        if (toggleButton && submenu) {
          toggleButton.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default link behavior
            submenu.classList.toggle('hidden');
          });
        }
      });
    </script>
  </body>
</html>
