<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chi tiết nhật ký tuần</title>
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
            href="{{ route('web.teacher.students', ['supervisorId' => $user->teacher->supervisor->id]) }}"
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
            <h1 class="text-lg md:text-xl font-semibold">Chi tiết nhật ký tuần</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đồ án tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="supervised-students.html" class="hover:underline text-slate-600">SV hướng dẫn</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Nhật ký tuần</span>
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

      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 bg-slate-50">
        <div class="max-w-5xl mx-auto space-y-6">

          <!-- Header sinh viên & tuần -->
          <div class="flex flex-col md:flex-row md:items-center md:justify-between bg-white shadow rounded-xl p-4">
            <div>
              @php
                $student = $progress_log->project->assignment->student;
                $mssv = $student->student_code ?? 'N/A';
                $fullname = $student->user->fullname ?? 'Sinh viên';
              @endphp
              <div class="text-sm text-slate-500">MSSV: <span class="font-medium text-slate-700">{{ $mssv }}</span></div>
              <h2 class="font-semibold text-xl mt-1">{{ $fullname }}</h2>
            </div>
            <div class="mt-3 md:mt-0 text-right">
              <div class="text-sm text-slate-500">Tuần</div>
              <div class="font-semibold text-blue-600 text-lg">#1</div>
            </div>
          </div>
          <!-- Tổng quan tuần -->
           @php
            $titleProgress = $progress_log->title ?? 'Chưa có tiêu đề';
            $description = $progress_log->description ?? 'Chưa có mô tả';
            $start_date = $progress_log->start_date_time ? date('d/m/Y', strtotime($progress_log->start_date)) : 'N/A';
            $end_date = $progress_log->end_date_time ? date('d/m/Y', strtotime($progress_log->end_date)) : 'N/A';
           @endphp
          <section class="bg-white shadow rounded-xl p-5">
            <h3 class="font-semibold text-lg mb-4">Tổng quan tuần</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div class="md:col-span-2 space-y-2">
                <div class="text-slate-500 text-sm">Tiêu đề</div>
                <div class="font-medium text-slate-800 text-base">{{ $titleProgress }}</div>

                <div class="text-slate-500 text-sm mt-2">Mô tả</div>
                <div class="text-slate-700 text-sm">
                  {{ $description }}
                </div>
              </div>

              <div class="space-y-3">
                <div>
                  <div class="text-slate-500 text-sm">Thời gian bắt đầu</div>
                  <div class="font-medium text-slate-800">{{ $start_date }}</div>
                </div>
                <div>
                  <div class="text-slate-500 text-sm">Thời gian kết thúc</div>
                  <div class="font-medium text-slate-800">{{ $end_date }}</div>
                </div>
                <div>
                  <div class="text-slate-500 text-sm">Tệp đính kèm</div>
                  @php
                    $attachments = $progress_log->attachments ?? [];
                    if(count($attachments) > 0) {
                      foreach($attachments as $file) {
                        echo '<div class="text-blue-600 hover:underline"><a href="#">' . htmlspecialchars($file) . '</a></div>';
                      }
                    } else {
                      echo '<div class="text-slate-500">Chưa có tệp đính kèm.</div>';
                    }
                  @endphp
                </div>
              </div>
            </div>
          </section>

          <!-- Công việc trong tuần -->
          <section class="bg-white shadow rounded-xl p-5">
            <h3 class="font-semibold text-lg mb-3">Công việc trong tuần</h3>
            <ul class="list-disc pl-5 space-y-1 text-sm text-slate-700">
              <li class="flex items-center gap-2"><span class="text-green-600">✅</span> Khảo sát yêu cầu</li>
              <li class="flex items-center gap-2"><span class="text-green-600">✅</span> Phân tích use case</li>
              <li class="flex items-center gap-2"><span class="text-gray-400">⬜</span> Thiết kế ERD</li>
            </ul>
          </section>

          <!-- Báo cáo tuần -->
          <section class="bg-white shadow rounded-xl p-5">
            <h3 class="font-semibold text-lg mb-3">Các báo cáo trong tuần</h3>
            <div class="space-y-3 text-sm text-slate-700">
              <div class="border border-slate-200 rounded-lg p-3 bg-slate-50 shadow-sm">
                <div class="text-xs text-slate-500 mb-1">—</div>
                <div>Hoàn thành khảo sát nghiệp vụ và phác thảo ERD sơ bộ.</div>
                <div class="mt-1 text-blue-600 hover:underline">
                  Tệp đính kèm: <a href="#">bao-cao-tuan-1.pdf</a>
                </div>
              </div>
            </div>
          </section>

          <!-- Nhận xét gửi sinh viên -->
          <section class="bg-white shadow rounded-xl p-5">
            <h3 class="font-semibold text-lg mb-3">Nhận xét gửi sinh viên</h3>
            <textarea id="commentText" rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Viết nhận xét..."></textarea>
            <div class="mt-3 flex items-center justify-between">
              <div id="commentStatus" class="text-sm text-slate-500">Chưa có nhận xét.</div>
              <button id="btnSendComment" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm hover:bg-emerald-500 transition">
                <i class="ph ph-paper-plane-tilt"></i> Gửi nhận xét
              </button>
            </div>
          </section>

          <!-- Chấm điểm tuần -->
          <section class="bg-white shadow rounded-xl p-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-3">
              <h3 class="font-semibold text-lg">Chấm điểm tuần</h3>
              <div class="text-sm text-slate-600 mt-2 md:mt-0">Khoảng thời gian: <span id="weekRange">-</span></div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
              <input id="inpScore" type="number" min="0" max="10" step="0.1" class="px-4 py-2 border border-slate-200 rounded-lg w-32 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Điểm" />
              <button id="btnSave" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition text-sm"><i class="ph ph-check"></i> Lưu điểm</button>
            </div>
            <div class="mt-2 text-sm text-slate-500">Điểm hiện tại: <span id="currentScore">-</span></div>
          </section>

        </div>
      </main>
    </div>
  </div>

  <script>
    // Sidebar/header interactions
    (function(){
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
    })();

    function qs(k){ const p = new URLSearchParams(location.search); return p.get(k) || ''; }
    const studentId = qs('studentId');
    const name = decodeURIComponent(qs('name')) || 'Sinh viên';
    const weekNo = parseInt(qs('week'));
    const LS_KEY = `lecturer:student:${studentId}`;
  </script>
</body>
</html>
