<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Chi tiết nhật ký tuần</title>
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
@php
  use Carbon\Carbon;
  $user = auth()->user();
  $userName = $user->fullname ?? $user->name ?? 'Giảng viên';
  $email = $user->email ?? '';
  // Tùy mô hình dữ liệu, thay các field bên dưới cho khớp
  $dept = $user->department_name ?? optional($user->teacher)->department ?? '';
  $faculty = $user->faculty_name ?? optional($user->teacher)->faculty ?? '';
  $subtitle = trim(($dept ? "Bộ môn $dept" : '') . (($dept && $faculty) ? ' • ' : '') . ($faculty ? "Khoa $faculty" : ''));
  $degree = $user->teacher->degree ?? '';
  $teacherId = $user->teacher->id ?? null;
  $avatarUrl = $user->avatar_url
    ?? $user->profile_photo_url
    ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
  $departmentRole = $user->teacher->departmentRoles->where('role', 'head')->first() ?? null;
  $departmentId = $departmentRole?->department_id ?? 0;
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
            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100" data-skip-active="1">
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
          @if ($departmentRole)
          <a href="{{ route('web.teacher.all_thesis_rounds', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold {{ $isThesisRoundsActive ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
            @if($isThesisRoundsActive) aria-current="page" @endif>
            <i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span>
          </a>
          @else
          <a href="{{ route('web.teacher.thesis_rounds', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold {{ $isThesisRoundsActive ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
            @if($isThesisRoundsActive) aria-current="page" @endif>
            <i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span>
          </a>
          @endif
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
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i
              class="ph ph-list"></i></button>
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
          <div id="profileMenu"
            class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông
              tin</a>
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i
                class="ph ph-sign-out"></i>Đăng xuất</a>
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </header>

<main class="flex-1 overflow-y-auto px-4 md:px-8 py-10 bg-gradient-to-b from-slate-50 to-slate-100">
  <div class="max-w-6xl mx-auto space-y-8">

    <!-- Hero: student + week -->
    <div class="rounded-3xl overflow-hidden shadow-lg bg-gradient-to-r from-indigo-600 via-sky-500 to-emerald-400 text-white p-6 flex flex-col md:flex-row items-center gap-6">
      @php
        $student = $progress_log->project->assignment->student;
        $mssv = $student->student_code ?? 'N/A';
        $fullname = $student->user->fullname ?? 'Sinh viên';
      @endphp
      <div class="flex items-center gap-4 w-full md:w-auto">
        <img src="{{ $student->user->avatar_url ?? ($student->user->profile_photo_url ?? ('https://ui-avatars.com/api/?name=' . urlencode($fullname) . '&background=ffffff&color=000')) }}"
          alt="avatar" class="h-20 w-20 rounded-full ring-4 ring-white object-cover shadow-md" />
        <div>
          <div class="text-xs uppercase tracking-wide opacity-90">Sinh viên hướng dẫn</div>
          <div class="text-2xl font-bold leading-tight">{{ $fullname }}</div>
          <div class="text-sm opacity-90 mt-1">MSSV: <span class="font-medium">{{ $mssv }}</span></div>
        </div>
      </div>

      <div class="ml-auto flex items-center gap-4 w-full md:w-auto justify-between">
        <div class="text-center bg-white/10 rounded-lg px-4 py-3">
          <div class="text-xs uppercase opacity-90">Tuần</div>
          <div class="mt-1 text-xl font-semibold">#1</div>
        </div>
        <div class="text-center bg-white/10 rounded-lg px-4 py-3 hidden sm:block">
          <div class="text-xs uppercase opacity-90">Giảng viên</div>
          <div class="mt-1 text-sm font-medium">{{ $userName }}</div>
        </div>
      </div>
    </div>

    <!-- Overview (title/description + meta) -->
    @php
      $titleProgress = $progress_log->title ?? 'Chưa có tiêu đề';
      $description = $progress_log->description ?? 'Chưa có mô tả';

      $start_date = Carbon::parse($progress_log->start_date_time)->format('H:i d/m/Y');
      $end_date   = Carbon::parse($progress_log->end_date_time)->format('H:i d/m/Y');
    @endphp
<!-- SECTION 1: TỔNG QUAN TUẦN -->
<section class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <!-- Tổng quan -->
  <div class="md:col-span-2 bg-gradient-to-br from-indigo-50 via-white to-slate-50 border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-semibold flex items-center gap-2 text-slate-800">
        <span class="p-2 rounded-lg bg-indigo-100 text-indigo-600">
          <i class="ph ph-clipboard-text text-xl"></i>
        </span>
        Tổng quan tuần
      </h3>
      <div class="flex items-center gap-2 text-sm text-slate-600 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
        <i class="ph ph-calendar text-slate-500"></i>
        <span>Từ <span class="font-medium text-slate-800">{{ $start_date }}</span> đến <span class="font-medium text-slate-800">{{ $end_date }}</span></span>
      </div>
    </div>

    <div class="mt-6 space-y-2">
      <div class="text-sm font-medium text-slate-500 flex items-center gap-1">
        <i class="ph ph-bookmark-simple text-indigo-500"></i>
        Tiêu đề
      </div>
      <div class="text-2xl font-bold text-slate-800 leading-tight">
        {{ $titleProgress }}
      </div>
    </div>

    <div class="mt-6">
      <div class="text-sm font-medium text-slate-500 flex items-center gap-1 mb-1">
        <i class="ph ph-text-align-left text-indigo-500"></i>
        Mô tả
      </div>
      <div class="bg-white border border-slate-100 rounded-xl p-4 text-sm text-slate-700 leading-relaxed shadow-inner">
        {{ $description }}
      </div>
    </div>
  </div>
</section>


<!-- SECTION 2: CÔNG VIỆC & TỆP ĐÍNH KÈM -->
<section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
  <!-- Công việc trong tuần -->
  <div class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-200">
    <h4 class="font-semibold text-slate-800 flex items-center gap-2">
      <div class="h-8 w-8 rounded-lg bg-emerald-50 text-emerald-600 grid place-items-center">
        <i class="ph ph-list-checks text-lg"></i>
      </div>
      Công việc trong tuần
    </h4>

    <ul class="mt-5 space-y-3 text-sm text-slate-700">
      @php
        $tasks = preg_split("/\r\n|\n|\r/", $progress_log->content ?? '');
      @endphp

      @php $hasTask = false; @endphp
      @foreach ($tasks as $task)
        @if (trim($task) !== '')
          @php $hasTask = true; @endphp
          <li class="flex items-start gap-3 group animate-fade-in">
            <div class="h-6 w-6 rounded-full bg-emerald-100 text-emerald-600 grid place-items-center group-hover:bg-emerald-200 transition">
              <i class="ph ph-check-circle"></i>
            </div>
            <div class="flex-1 leading-relaxed text-slate-700">{{ trim($task) }}</div>
          </li>
        @endif
      @endforeach

      @if (!$hasTask)
        <li class="text-sm text-slate-400 italic">Chưa có công việc nào được ghi chú.</li>
      @endif
    </ul>
  </div>

  <!-- Báo cáo & Tệp đính kèm -->
  <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-200">
    <h4 class="font-semibold text-slate-800 flex items-center gap-2">
      <div class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 grid place-items-center">
        <i class="ph ph-file-text text-lg"></i>
      </div>
      Báo cáo & tệp đính kèm
    </h4>

    @php
      $attachments = $progress_log->attachments ?? [];
      $latestAttachment = $attachments->last() ?? null;
    @endphp

    <div class="mt-5 space-y-3">
      @if ($latestAttachment)
        <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-3 text-sm text-indigo-700 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <i class="ph ph-star text-indigo-600"></i>
            <span class="font-medium">Báo cáo mới nhất:</span>
            <a href="{{ $latestAttachment->file_url ?? '#' }}" target="_blank" class="underline hover:text-indigo-800 transition">
              {{ $latestAttachment->file_name ?? 'Không có tên' }}
            </a>
          </div>
          <i class="ph ph-arrow-up-right text-indigo-600"></i>
        </div>
      @endif

      @if (count($attachments) > 0)
        <ul class="space-y-2 mt-4">
          @foreach ($attachments as $att)
            @php
              $attName = $att->file_name ?? ($att->name ?? 'Tệp đính kèm');
              $attUrl = $att->file_url ?? ($att->url ?? '#');
            @endphp
            <li class="flex items-center justify-between group">
              <a href="{{ $attUrl }}" target="_blank" class="flex items-center gap-2 text-indigo-600 font-medium group-hover:text-indigo-700 transition">
                <i class="ph ph-download-simple text-lg"></i>
                <span>{{ $attName }}</span>
              </a>
            </li>
          @endforeach
        </ul>
      @else
        <div class="text-sm text-slate-400 italic mt-2">Chưa có báo cáo hoặc tệp đính kèm.</div>
      @endif
    </div>
  </div>
</section>

    <!-- Comments -->
    <section class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
      <div class="flex items-start gap-4">
        <img src="{{ $avatarUrl }}" alt="you" class="h-12 w-12 rounded-full object-cover ring-2 ring-slate-100" />
        <div class="flex-1">
          <label for="commentText" class="text-sm font-medium text-slate-700">Nhận xét gửi sinh viên</label>
          <div class="relative mt-2">
            <textarea id="commentText" rows="3" placeholder="Viết nhận xét cho sinh viên..."
              class="w-full pr-28 px-4 py-3 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"></textarea>
            <div class="absolute right-3 bottom-3">
              <button id="btnSendComment" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition text-sm">
                <i class="ph ph-paper-plane-tilt"></i> Gửi
              </button>
            </div>
          </div>
          <div id="commentStatus" class="mt-2 text-sm text-slate-500"></div>
        </div>
      </div>

      @php
        // Load existing comments safely - fallbacks in case relation name varies
        $comments = $progress_log->commentLogs ?? $progress_log->comments ?? collect();
      @endphp

      <div class="mt-6">
        <h4 class="text-sm font-medium text-slate-700 mb-3 flex items-center gap-2"><i class="ph ph-clipboard-text text-indigo-600"></i> Danh sách nhận xét</h4>
        <div id="commentsContainer">
        @if($comments && count($comments) > 0)
          <ul id="commentsList" class="space-y-3">
            @foreach($comments as $c)
              @php
                $author = optional(optional($c->supervisor)->teacher->user)->fullname ?? optional($c->supervisor)->fullname ?? ($c->supervisor_id ? 'Giảng viên' : ($c->author_name ?? 'Giảng viên'));
                $time = $c->created_at ? $c->created_at->format('d/m/Y H:i') : '-';
              @endphp
              <li class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex gap-3 items-start">
                <div class="shrink-0 mt-1">
                  <div class="h-10 w-10 grid place-items-center rounded-full bg-indigo-50 text-indigo-700"><i class="ph ph-chat-text"></i></div>
                </div>
                <div class="flex-1">
                  <div class="text-sm text-slate-700 leading-relaxed">{{ $c->content }}</div>
                  <div class="mt-2 text-xs text-slate-500 flex items-center gap-3">
                    <span class="inline-flex items-center gap-2"><i class="ph ph-user-circle text-slate-400"></i> <span>{{ $author }}</span></span>
                    <span class="inline-flex items-center gap-2"><i class="ph ph-clock text-slate-400"></i> <span>{{ $time }}</span></span>
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
        @else
          <div id="commentsEmpty" class="text-sm text-slate-400">Chưa có nhận xét nào.</div>
        @endif
        </div>
      </div>
    </section>

    <!-- Rating -->
    <section class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
      <div class="flex items-center justify-between">
        <h3 class="font-semibold text-lg flex items-center gap-2 text-slate-800"><i class="ph ph-star text-amber-500"></i> Đánh giá tuần</h3>
        <div class="text-sm text-slate-600">Khoảng thời gian: <span id="weekRange" class="font-medium text-slate-800">-</span></div>
      </div>
      @php
      $listStatuses = ['approved', 'not_achieved', 'need_editing'];
      $listStatusLabels = ['approved' => 'Đạt', 'not_achieved' => 'Chưa đạt', 'need_editing' => 'Cần chỉnh sửa'];
      $listColorsBackground = ['approved' => 'bg-emerald-50', 'not_achieved' => 'bg-rose-50', 'need_editing' => 'bg-amber-50'];
      $listColorsBorder = ['approved' => 'border-emerald-200', 'not_achieved' => 'border-rose-200', 'need_editing' => 'border-amber-200'];
      $listColorsText = ['approved' => 'text-emerald-700', 'not_achieved' => 'text-rose-700', 'need_editing' => 'text-amber-700'];
      $currentStatus = in_array($progress_log->instructor_status, $listStatuses) ? $progress_log->instructor_status : ''; 
      @endphp
      <div class="mt-4 flex flex-col sm:flex-row sm:items-center gap-3">
        <select id="selWeeklyStatus" class="px-4 py-2.5 border border-slate-200 rounded-xl w-full sm:w-64 focus:ring-2 focus:ring-indigo-400 text-sm">
          <option value="">-- Chọn trạng thái --</option>
          @foreach ($listStatuses as $status)
            <option value="{{ $status }}" {{ $currentStatus === $status ? 'selected' : '' }}>{{ $listStatusLabels[$status] ?? $status }}</option>
          @endforeach
        </select>
        <button id="btnConfirmStatus" class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-500 transition text-sm font-medium"><i class="ph ph-check-circle"></i> Xác nhận</button>
      </div>
      <div class="mt-3 text-sm text-slate-600">Trạng thái hiện tại: <span id="currentStatus" class="inline-block px-2.5 py-0.5 rounded-full border {{ $listColorsBorder[$currentStatus] ?? '' }} {{ $listColorsBackground[$currentStatus] ?? '' }} {{ $currentStatus ? ($listColorsText[$currentStatus] ?? '-') : '-' }} text-sm font-medium">{{ $currentStatus ? ($listStatusLabels[$currentStatus] ?? '-') : '-' }}</span></div>
    </section>

  </div>
</main>

    </div>
  </div>

  <script>
    // Sidebar/header interactions
    (function () {
      const html = document.documentElement, sidebar = document.getElementById('sidebar');
      function setCollapsed(c) {
        const h = document.querySelector('header'); const m = document.querySelector('main');
        if (c) { html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); }
        else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]'); }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click', () => { const c = !html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar', '' + (c ? 1 : 0)); });
      document.getElementById('openSidebar')?.addEventListener('click', () => sidebar.classList.toggle('-translate-x-full'));
      if (localStorage.getItem('lecturer_sidebar') === '1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0', '-translate-x-full', 'md:static');
      const profileBtn = document.getElementById('profileBtn'); const profileMenu = document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', () => profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e) => { if (!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    })();

    function qs(k) { const p = new URLSearchParams(location.search); return p.get(k) || ''; }
    const studentId = qs('studentId');
    const name = decodeURIComponent(qs('name')) || 'Sinh viên';
    const weekNo = parseInt(qs('week'));
    const LS_KEY = `lecturer:student:${studentId}`;
    // Current user name (for author display when appending new comments client-side)
    const currentUserName = @json($userName);

    function formatNowToVN() {
      const d = new Date();
      const pad = (n) => String(n).padStart(2,'0');
      return `${pad(d.getDate())}/${pad(d.getMonth()+1)}/${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())}`;
    }

    // Đánh giá tuần: gọi API cập nhật status cho attachment
    (function () {
      // Đồng bộ với value trong <select> và backend (approved/not_achieved/need_editting)
      const STATUS_MAP = {
        approved:      { label: 'Đạt',          cls: 'border-emerald-200 bg-emerald-50 text-emerald-700' },
        not_achieved:  { label: 'Chưa đạt',     cls: 'border-rose-200 bg-rose-50 text-rose-700' },
        need_editing: { label: 'Cần chỉnh sửa',cls: 'border-amber-200 bg-amber-50 text-amber-700' }
      };
  const sel = document.getElementById('selWeeklyStatus');
  const btn = document.getElementById('btnConfirmStatus');
  const curr = document.getElementById('currentStatus');

      function render(val) {
        const m = STATUS_MAP[val];
        curr.textContent = m ? m.label : '-';
        curr.className = `inline-block px-2 py-0.5 rounded border ${m ? m.cls : 'border-slate-200 bg-slate-50 text-slate-700'}`;
      }

      btn?.addEventListener('click', async () => {
        const status = sel?.value || '';
        if (!status) { alert('Vui lòng chọn trạng thái.'); return; }
        const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
        // Tạo URL trực tiếp từ Blade, tránh dùng biến JS không tồn tại
        const url = `{{ route('web.teacher.attachments.update_status', ['progress_log' => $progress_log->id]) }}`;
        btn.disabled = true; const old = btn.innerHTML;
        btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> Đang cập nhật...';
        try {
            const res = await fetch(url, {
              method: 'PATCH',
              credentials: 'same-origin', // gửi cookie phiên
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token || ''
              },
              // Gửi kèm progress_log_id và token
              body: JSON.stringify({ status, progress_log_id: @json($progress_log->id), _token: token })
            });
          const js = await res.json().catch(() => ({}));
          if (!res.ok || js.ok === false) {
            alert(js.message || 'Cập nhật thất bại.');
            return;
          }
          render(status);
        } catch (e) {
          alert('Lỗi mạng, vui lòng thử lại.');
        } finally {
          btn.disabled = false; btn.innerHTML = old;
        }
      });
    })();

    // Gửi nhận xét: gửi POST tới backend
    (function () {
      const btn = document.getElementById('btnSendComment');
      const ta = document.getElementById('commentText');
      const statusEl = document.getElementById('commentStatus');
      const url = `{{ route('web.progress_logs.comments.store', ['progress_log' => $progress_log->id]) }}`;
      btn?.addEventListener('click', async () => {
        const content = (ta?.value || '').trim();
        if (!content) { alert('Vui lòng nhập nhận xét.'); return; }
        btn.disabled = true; const old = btn.innerHTML; btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> Đang gửi...';
        try {
          const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
          const res = await fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': token || ''
            },
            body: JSON.stringify({ content, supervisor_id: @json($supervisorId ?? null) })
          });
          const js = await res.json().catch(() => ({}));
          if (!res.ok || js.ok === false) {
            alert(js.message || 'Gửi nhận xét thất bại.');
            return;
          }
          // Success: clear textarea and update status
          ta.value = '';
          statusEl.textContent = 'Đã gửi nhận xét.';

          // Prepend the new comment to the comment list (create list if necessary)
          try {
            const container = document.getElementById('commentsContainer');
            if (container) {
              let list = document.getElementById('commentsList');
              // Remove empty placeholder if present
              const empty = document.getElementById('commentsEmpty');
              if (!list) {
                if (empty) empty.remove();
                list = document.createElement('ul');
                list.id = 'commentsList';
                list.className = 'space-y-3';
                container.appendChild(list);
              }

              const li = document.createElement('li');
              li.className = 'bg-slate-50 border border-slate-100 rounded-xl p-4 flex gap-3 items-start';

              const iconWrap = document.createElement('div'); iconWrap.className = 'shrink-0 mt-1';
              const iconInner = document.createElement('div'); iconInner.className = 'h-9 w-9 grid place-items-center rounded-full bg-indigo-50 text-indigo-700';
              iconInner.innerHTML = '<i class="ph ph-chat-text"></i>';
              iconWrap.appendChild(iconInner);

              const body = document.createElement('div'); body.className = 'flex-1';
              const contentDiv = document.createElement('div'); contentDiv.className = 'text-sm text-slate-700 leading-relaxed';
              // Use server-returned comment content if available, fallback to posted content
              contentDiv.textContent = (js.comment && js.comment.content) ? js.comment.content : content;

              const meta = document.createElement('div'); meta.className = 'mt-2 text-xs text-slate-500 flex items-center gap-3';
              const authorSpan = document.createElement('span'); authorSpan.className = 'inline-flex items-center gap-2';
              authorSpan.innerHTML = '<i class="ph ph-user-circle text-slate-400"></i> <span>' + (js.comment && js.comment.supervisor_id ? (js.comment.supervisor_name || currentUserName) : currentUserName) + '</span>';
              const timeSpan = document.createElement('span'); timeSpan.className = 'inline-flex items-center gap-2';
              const createdAt = js.comment && js.comment.created_at ? new Date(js.comment.created_at) : new Date();
              const pad = (n) => String(n).padStart(2,'0');
              const formatted = `${pad(createdAt.getDate())}/${pad(createdAt.getMonth()+1)}/${createdAt.getFullYear()} ${pad(createdAt.getHours())}:${pad(createdAt.getMinutes())}`;
              timeSpan.innerHTML = '<i class="ph ph-clock text-slate-400"></i> <span>' + formatted + '</span>';

              meta.appendChild(authorSpan); meta.appendChild(timeSpan);
              body.appendChild(contentDiv); body.appendChild(meta);

              li.appendChild(iconWrap); li.appendChild(body);
              // Prepend to the list
              list.prepend(li);
            }
          } catch (err) {
            console.error('Append comment failed', err);
          }
        } catch (err) {
          console.error(err);
          alert('Lỗi mạng, vui lòng thử lại.');
        } finally {
          btn.disabled = false; btn.innerHTML = old;
        }
      });
    })();
  </script>
</body>

</html>