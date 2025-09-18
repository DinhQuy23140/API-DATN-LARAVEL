<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đồ án tốt nghiệp - Giảng viên</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <style>
    body {
      font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
    }

    .sidebar-collapsed .sidebar-label {
      display: none;
    }

    .sidebar-collapsed .sidebar {
      width: 72px;
    }

    .sidebar {
      width: 260px;
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
    $expertise = $user->teacher->supervisor->expertise ?? 'null';
    $data_assignment_supervisors = $user->teacher->supervisor->assignment_supervisors ?? "null";
    $supervisorId = $user->teacher->supervisor->id ?? 0;
    $teacherId = $user->teacher->id ?? 0;
    $avatarUrl = $user->avatar_url
      ?? $user->profile_photo_url
      ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
  @endphp
  <div class="flex min-h-screen">
    <aside id="sidebar"
      class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i
            class="ph ph-chalkboard-teacher"></i></div>
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

        @if ($user->teacher && $user->teacher->supervisor)
          <a href="{{ route('web.teacher.students', ['supervisorId' => $user->teacher->supervisor->id]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.students') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span>
          </a>
        @else
          <span class="text-slate-400">Chưa có supervisor</span>
        @endif

        @php
          $isThesisOpen = request()->routeIs('web.teacher.thesis_internship') || request()->routeIs('web.teacher.thesis_rounds');
        @endphp
        <button
          type="button"
          id="toggleThesisMenu"
          aria-controls="thesisSubmenu"
          aria-expanded="{{ $isThesisOpen ? 'true' : 'false' }}"
          class="w-full flex items-center justify-between px-3 py-2 rounded-lg mt-3 {{ $isThesisOpen ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <span class="flex items-center gap-3">
            <i class="ph ph-graduation-cap"></i>
            <span class="sidebar-label">Học phần tốt nghiệp</span>
          </span>
          <i id="thesisCaret" class="ph ph-caret-down transition-transform {{ $isThesisOpen ? 'rotate-180' : '' }}"></i>
        </button>

        <div id="thesisSubmenu" class="mt-1 pl-3 space-y-1 {{ $isThesisOpen ? '' : 'hidden' }}">
          <a href="{{ route('web.teacher.thesis_internship') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_internship') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
             @if(request()->routeIs('web.teacher.thesis_internship')) aria-current="page" @endif>
            <i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span>
          </a>
          <a href="{{ route('web.teacher.thesis_rounds', ['teacherId' => $teacherId]) }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_rounds') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
             @if(request()->routeIs('web.teacher.thesis_rounds')) aria-current="page" @endif>
            <i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span>
          </a>
        </div>
      </nav>
      <div class="p-3 border-t border-slate-200">
        <button id="toggleSidebar"
          class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i
            class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
      </div>
    </aside>

    <div class="flex-1 h-screen overflow-hidden flex flex-col">
      <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i
              class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Đồ án tốt nghiệp</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Học phần tốt nghiệp / Đồ án tốt nghiệp</span>
            </nav>
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
          <div id="profileMenu"
            class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông
              tin</a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
              class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i
                class="ph ph-sign-out"></i>Đăng xuất</a>
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 space-y-6">
        <div class="max-w-6xl mx-auto space-y-6">

          <!-- Actions -->
          <div
            class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="relative w-full sm:max-w-sm">
              <input id="searchInput"
                class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm"
                placeholder="Tìm theo tên đợt" />
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <div class="text-sm text-slate-500">
              Hiển thị các đợt đồ án mà bạn tham gia hướng dẫn hoặc chấm thi
            </div>
          </div>

          <!-- Rounds List -->  
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          @foreach ($rows as $row)
                      <!-- Round 1 -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 hover:shadow-md transition-shadow">
              <div class="flex items-start justify-between mb-4">
                <div>
                  <h3 class="font-semibold text-lg">{{ "Đợt " . $row->stage . " " . $row->academy_year->year_name }}</h3>
                  <p class="text-sm text-slate-600">{{ $row->start_date }} - {{ $row->end_date }}</p>
                  <p class="text-xs text-slate-500 mt-1">Mã đợt: {{ $row->id }}</p>
                </div>
                <span class="px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-600">Đang diễn ra</span>
              </div>

              <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-center p-3 bg-slate-50 rounded-lg">
                  <div class="text-lg font-semibold text-blue-600">{{ $row->assignments->count() }}</div>
                  <div class="text-xs text-slate-600">SV hướng dẫn</div>
                </div>
                <div class="text-center p-3 bg-slate-50 rounded-lg">
                  <div class="text-lg font-semibold text-green-600">3</div>
                  <div class="text-xs text-slate-600">Hội đồng tham gia</div>
                </div>
              </div>

              <div class="flex items-center justify-between">
                <div class="text-xs text-slate-500">
                  Cập nhật: 2 giờ trước
                </div>
                <a href="{{ route('web.teacher.thesis_round_detail', ['termId' => $row->id, 'supervisorId'=>$row->supervisors[0]->id]) }}"
                  class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                  Xem chi tiết
                </a>
              </div>
            </div>
          @endforeach

          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    const html = document.documentElement, sidebar = document.getElementById('sidebar');
    function setCollapsed(c) {
      const mainArea = document.querySelector('.flex-1');
      if (c) {
        html.classList.add('sidebar-collapsed');
        mainArea.classList.add('md:pl-[72px]');
        mainArea.classList.remove('md:pl-[260px]');
      } else {
        html.classList.remove('sidebar-collapsed');
        mainArea.classList.remove('md:pl-[72px]');
        mainArea.classList.add('md:pl-[260px]');
      }
    }
    
    document.getElementById('toggleSidebar')?.addEventListener('click', () => { const c = !html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar', '' + (c ? 1 : 0)); });
    document.getElementById('openSidebar')?.addEventListener('click', () => sidebar.classList.toggle('-translate-x-full'));
    if (localStorage.getItem('lecturer_sidebar') === '1') setCollapsed(true);
    sidebar.classList.add('md:translate-x-0', '-translate-x-full', 'md:static');

    // Search functionality
    document.getElementById('searchInput')?.addEventListener('input', (e) => {
      const q = e.target.value.toLowerCase();
      document.querySelectorAll('.grid > div').forEach(card => {
        const text = card.innerText.toLowerCase();
        card.style.display = text.includes(q) ? '' : 'none';
      });
    });

    // Profile dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    profileBtn?.addEventListener('click', () => profileMenu.classList.toggle('hidden'));
    document.addEventListener('click', (e) => { if (!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

    // Toggle submenu "Học phần tốt nghiệp"
    const toggleBtn = document.getElementById('toggleThesisMenu');
    const thesisMenu = document.getElementById('thesisSubmenu');
    const thesisCaret = document.getElementById('thesisCaret');
    toggleBtn?.addEventListener('click', () => {
      const isHidden = thesisMenu?.classList.toggle('hidden');
      const expanded = !isHidden;
      toggleBtn?.setAttribute('aria-expanded', expanded ? 'true' : 'false');
      thesisCaret?.classList.toggle('rotate-180', expanded);
    });

    // Auto active nav highlight (bỏ qua link đã có aria-current)
    (function () {
      const current = location.pathname.split('/').pop();
      document.querySelectorAll('aside nav a').forEach(a => {
        if (a.hasAttribute('aria-current')) return;
        const href = a.getAttribute('href') || '';
        const active = href.endsWith(current);
        a.classList.toggle('bg-slate-100', active);
        a.classList.toggle('font-semibold', active);
      });
    })();
  </script>
</body>

</html>