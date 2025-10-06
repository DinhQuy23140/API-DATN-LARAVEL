<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Danh sách sinh viên hướng dẫn</title>
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
      $expertise = $user->teacher->supervisor->expertise ?? 'null';
      $data_assignment_supervisors = $user->teacher->supervisor->assignment_supervisors ?? "null";
      $teacherId = $user->teacher->id ?? null;
      $avatarUrl = $user->avatar_url
        ?? $user->profile_photo_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    @endphp
<body class="bg-slate-50 text-slate-800">
  <div class="flex min-h-screen">
    <aside class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all"
      id="sidebar">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i
            class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Lecturer</div>
          <div class="text-xs text-slate-500">Bảng điều khiển</div>
        </div>
      </div>
      @php
        // Luôn mở nhóm "Học phần tốt nghiệp"
        $isThesisOpen = true;
        // Active item "Đồ án tốt nghiệp" trong submenu (giữ logic cũ)
        $isThesisRoundsActive = request()->routeIs('web.teacher.thesis_rounds')
          || request()->routeIs('web.teacher.thesis_round_detail');
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
          <a id="menuStudents"
            href="{{ route('web.teacher.students', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"
            data-skip-active="1">
             <i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span>
           </a>
        @else
          <span class="text-slate-400">Chưa có supervisor</span>
        @endif

        @php $isThesisOpen = true; @endphp
        <button type="button" id="toggleThesisMenu" aria-controls="thesisSubmenu"
          aria-expanded="{{ $isThesisOpen ? 'true' : 'false' }}"
          class="w-full flex items-center justify-between px-3 py-2 rounded-lg mt-3 {{ $isThesisOpen ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <span class="flex items-center gap-3">
            <i class="ph ph-graduation-cap"></i>
            <span class="sidebar-label">Học phần tốt nghiệp</span>
          </span>
          <i id="thesisCaret" class="ph ph-caret-down transition-transform {{ $isThesisOpen ? 'rotate-180' : '' }}"></i>
        </button>

        <div id="thesisSubmenu" class="mt-1 pl-3 space-y-1">
          <a href="{{ route('web.teacher.thesis_internship') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_internship') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
            @if(request()->routeIs('web.teacher.thesis_internship')) aria-current="page" @endif>
            <i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span>
          </a>
          <a href="{{ route('web.teacher.thesis_rounds', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold {{ $isThesisRoundsActive ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
            @if($isThesisRoundsActive) aria-current="page" @endif>
            <i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span>
          </a>
        </div>
      </nav>
      <div class="p-3 border-t border-slate-200">
        <button
          class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"
          id="toggleSidebar"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
      </div>
    </aside>

    <div class="flex-1 h-screen overflow-hidden flex flex-col">
      <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Danh sách sinh viên hướng dẫn</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đồ án tốt nghiệp</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">SV hướng dẫn</span>
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
          <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông tin</a>
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
        <div class="max-w-6xl mx-auto">
          <div class="flex items-center justify-between mb-4">
            <a href="#" onclick="window.history.back(); return false;" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại</a>
          </div>
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
            <input class="px-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tên/MSSV/đề tài" />
            <div class="flex items-center gap-2">
              <button class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm"><i class="ph ph-plus"></i> Thêm SV</button>
              <button class="px-3 py-1.5 border border-slate-200 rounded text-sm"><i class="ph ph-export"></i> Xuất danh sách</button>
            </div>
          </div>
          <div class="overflow-x-auto bg-white border rounded-xl shadow-sm">
            <table class="w-full text-sm">
              <thead class="bg-slate-50">
                <tr class="text-left text-slate-600 border-b">
                  <th class="py-3 px-3 font-medium">Sinh viên</th>
                  <th class="py-3 px-3 font-medium">MSSV</th>
                  <th class="py-3 px-3 font-medium">Lớp</th>
                  <th class="py-3 px-3 font-medium">Đề tài</th>
                  <th class="py-3 px-3 font-medium">Ngày bắt đầu</th>
                  <th class="py-3 px-3 font-medium text-center">Trạng thái</th>
                  <th class="py-3 px-3 font-medium text-center">Hành động</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-200">
                @foreach ($items as $item)
                  @php
                    $student   = $item->student;
                    $user      = $student->user;
                    $name      = $user->fullname;
                    $mssv      = $student->student_code;
                    $studentId = $student->id;
                    $class     = $student->class_code;
                    $topic     = $item->project_id ? $item->project->name : 'Chưa có đề tài';
                    $startDate = $item->created_at->format('H:i:s d/m/Y');
                    $statusRaw = $item->status ?? null;

                    // Gom nhóm trạng thái
                    switch ($statusRaw) {
                        case 'actived':
                            $statusText  = 'Đang hoạt động';
                            $statusClass = 'bg-emerald-100 text-emerald-700';
                            break;

                        case 'pending':
                            $statusText  = 'Chờ xử lý';
                            $statusClass = 'bg-amber-100 text-amber-700';
                            break;

                        case 'cancelled':
                            $statusText  = 'Đã hủy';
                            $statusClass = 'bg-rose-100 text-rose-700';
                            break;

                        case 'stopped':
                            $statusText  = 'Đã dừng';
                            $statusClass = 'bg-slate-100 text-slate-700';
                            break;

                        default:
                            $statusText  = 'Chưa xác định';
                            $statusClass = 'bg-slate-100 text-slate-600';
                            break;
                    }
                  @endphp

                  <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-3 px-3">
                      <a class="text-blue-600 hover:underline font-medium"
                        href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $termId, 'supervisorId' => $supervisorId]) }}">
                        {{ $name }}
                      </a>
                    </td>
                    <td class="py-3 px-3">{{ $mssv }}</td>
                    <td class="py-3 px-3">{{ $class }}</td>
                    <td class="py-3 px-3">{{ $topic }}</td>
                    <td class="py-3 px-3 text-slate-600">{{ $startDate }}</td>
                    <td class="py-3 px-3 text-center">
                      <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                        {{ $statusText }}
                      </span>
                    </td>
                    <td class="py-3 px-3 text-center">
                      <div class="flex items-center justify-center gap-2">
                        <a class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 hover:bg-slate-50 transition"
                          href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $termId, 'supervisorId' => $supervisorId]) }}">
                          Xem chi tiết
                        </a>
                        <button class="px-3 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-medium hover:bg-rose-700 transition">
                          Gỡ
                        </button>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>

    <script>
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const h=document.querySelector('header'); const m=document.querySelector('main');
        if(c){ html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); }
        else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]'); }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
      const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    </script>
  </div>
</body>
</html>
