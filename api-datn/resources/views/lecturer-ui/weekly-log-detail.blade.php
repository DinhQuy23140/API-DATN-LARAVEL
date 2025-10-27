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
  $departmentRole = $user->teacher->departmentRoles->where('role', 'head')->first() ?? null;
  $departmentId = $departmentRole->department_id;
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

<main class="flex-1 overflow-y-auto px-4 md:px-8 py-8 bg-gradient-to-b from-slate-50 to-slate-100">
  <div class="max-w-6xl mx-auto space-y-8">

    <!-- 🧑‍🎓 Thông tin sinh viên & tuần -->
    <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 flex flex-col md:flex-row md:items-center md:justify-between hover:shadow-md transition">
      <div>
        @php
          $student = $progress_log->project->assignment->student;
          $mssv = $student->student_code ?? 'N/A';
          $fullname = $student->user->fullname ?? 'Sinh viên';
        @endphp
        <div class="text-sm text-slate-500">MSSV: 
          <span class="font-semibold text-slate-800">{{ $mssv }}</span>
        </div>
        <h2 class="font-bold text-2xl text-slate-800 mt-1 flex items-center gap-2">
          <i class="ph ph-student text-blue-600"></i>
          {{ $fullname }}
        </h2>
      </div>

      <div class="mt-4 md:mt-0 text-right">
        <div class="text-sm text-slate-500">Tuần hiện tại</div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-200">
          <i class="ph ph-calendar text-blue-600"></i>
          <span class="font-semibold text-blue-700 text-lg">#1</span>
        </div>
      </div>
    </div>

    <!-- 📅 Tổng quan tuần -->
    @php
      $titleProgress = $progress_log->title ?? 'Chưa có tiêu đề';
      $description = $progress_log->description ?? 'Chưa có mô tả';
      $start_date = $progress_log->start_date_time ? date('d/m/Y', strtotime($progress_log->start_date)) : 'N/A';
      $end_date = $progress_log->end_date_time ? date('d/m/Y', strtotime($progress_log->end_date)) : 'N/A';
    @endphp
    <section class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 hover:shadow-md transition">
      <div class="flex items-center justify-between mb-5 border-b pb-3">
        <h3 class="font-semibold text-lg text-slate-800 flex items-center gap-2">
          <i class="ph ph-clipboard-text text-emerald-600"></i> Tổng quan tuần
        </h3>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-3">
          <div>
            <div class="text-sm text-slate-500 mb-1">Tiêu đề</div>
            <div class="font-semibold text-slate-800 text-base">{{ $titleProgress }}</div>
          </div>

          <div>
            <div class="text-sm text-slate-500 mb-1">Mô tả</div>
            <div class="text-slate-700 leading-relaxed text-sm bg-slate-50 border border-slate-100 rounded-lg p-3">
              {{ $description }}
            </div>
          </div>
        </div>

        <div class="space-y-3">
          <div>
            <div class="text-sm text-slate-500 mb-1">Thời gian bắt đầu</div>
            <div class="font-medium text-slate-800 flex items-center gap-1">
              <i class="ph ph-clock text-emerald-600"></i> {{ $start_date }}
            </div>
          </div>
          <div>
            <div class="text-sm text-slate-500 mb-1">Thời gian kết thúc</div>
            <div class="font-medium text-slate-800 flex items-center gap-1">
              <i class="ph ph-clock text-rose-500"></i> {{ $end_date }}
            </div>
          </div>
          <div>
            <div class="text-sm text-slate-500 mb-1">Tệp đính kèm</div>
            @php
              $latestAttachment = $progress_log->attachments->last() ?? null;
              $latestAttachmentId = $latestAttachment->id ?? null;
              $latestAttachmentName = $latestAttachment->file_name ?? ($latestAttachment->name ?? 'Tệp đính kèm');
              $latestAttachmentUrl = $latestAttachment->file_url ?? ($latestAttachment->url ?? '#');
            @endphp
            @if($latestAttachmentId)
              <a href="{{ $latestAttachmentUrl }}" target="_blank"
                class="inline-flex items-center gap-2 text-blue-600 hover:underline text-sm font-medium">
                <i class="ph ph-paperclip"></i>{{ $latestAttachmentName }}
              </a>
              <input type="hidden" id="attachmentId" value="{{ $latestAttachmentId }}">
            @else
              <div class="text-slate-400 text-sm">Chưa có tệp đính kèm.</div>
            @endif
          </div>
        </div>
      </div>
    </section>

    <!-- ✅ Công việc trong tuần -->
    <section class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 hover:shadow-md transition">
      <h3 class="font-semibold text-lg mb-4 flex items-center gap-2 text-slate-800">
        <i class="ph ph-list-checks text-blue-600"></i> Công việc trong tuần
      </h3>
      <ul class="space-y-2 text-sm text-slate-700">
        <li class="flex items-center gap-2"><i class="ph ph-check-circle text-emerald-600"></i> Khảo sát yêu cầu</li>
        <li class="flex items-center gap-2"><i class="ph ph-check-circle text-emerald-600"></i> Phân tích use case</li>
        <li class="flex items-center gap-2 text-slate-400"><i class="ph ph-circle text-slate-400"></i> Thiết kế ERD</li>
      </ul>
    </section>

    <!-- 🧾 Báo cáo tuần -->
    <section class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 hover:shadow-md transition">
      <h3 class="font-semibold text-lg mb-4 flex items-center gap-2 text-slate-800">
        <i class="ph ph-file-text text-indigo-600"></i> Các báo cáo trong tuần
      </h3>
      <div class="space-y-3 text-sm text-slate-700">
        <div class="border border-slate-200 rounded-xl p-4 bg-slate-50">
          <div class="text-xs text-slate-500 mb-1">01/10/2025</div>
          <div>Hoàn thành khảo sát nghiệp vụ và phác thảo ERD sơ bộ.</div>
          @php
            $attachments = $progress_log->attachments ?? [];
          @endphp
          <div class="mt-1 text-blue-600 hover:underline font-medium">
            Tệp đính kèm: 
            @if (count($attachments) > 0)
              @foreach ($attachments as $att)
                @php
                  $attName = $att->file_name ?? ($att->name ?? 'Tệp đính kèm');
                  $attUrl = $att->file_url ?? ($att->url ?? '#');
                @endphp
                <a href="{{ $attUrl }}" target="_blank" class="mr-2">{{ $attName }}</a>
              @endforeach
            @else
              <span class="text-slate-400">Chưa có tệp đính kèm.</span>
            @endif
          </div>
        </div>
      </div>
    </section>

    <!-- 💬 Nhận xét gửi sinh viên -->
    <section class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 hover:shadow-md transition">
      <h3 class="font-semibold text-lg mb-4 flex items-center gap-2 text-slate-800">
        <i class="ph ph-chat-circle-text text-emerald-600"></i> Nhận xét gửi sinh viên
      </h3>
      <textarea id="commentText" rows="3"
        class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        placeholder="Viết nhận xét cho sinh viên..."></textarea>
      <div class="mt-4 flex items-center justify-between">
        <div id="commentStatus" class="text-sm text-slate-500">Chưa có nhận xét.</div>
        <button id="btnSendComment"
          class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-medium hover:bg-emerald-500 transition">
          <i class="ph ph-paper-plane-tilt"></i> Gửi nhận xét
        </button>
      </div>
    </section>

    <!-- ⭐ Đánh giá tuần -->
    <section class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 hover:shadow-md transition">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 border-b pb-3">
        <h3 class="font-semibold text-lg flex items-center gap-2 text-slate-800">
          <i class="ph ph-star text-amber-500"></i> Đánh giá tuần
        </h3>
        <div class="text-sm text-slate-600 mt-2 md:mt-0">Khoảng thời gian:
          <span id="weekRange" class="font-medium text-slate-800">-</span>
        </div>
      </div>
      @php
      $listStatuses = ['approved', 'not_achieved', 'need_editing'];
      $listStatusLabels = ['approved' => 'Đạt', 'not_achieved' => 'Chưa đạt', 'need_editing' => 'Cần chỉnh sửa'];
      $listColorsBackground = ['approved' => 'bg-emerald-50', 'not_achieved' => 'bg-rose-50', 'need_editing' => 'bg-amber-50'];
      $listColorsBorder = ['approved' => 'border-emerald-200', 'not_achieved' => 'border-rose-200', 'need_editing' => 'border-amber-200'];
      $listColorsText = ['approved' => 'text-emerald-700', 'not_achieved' => 'text-rose-700', 'need_editing' => 'text-amber-700'];
      $currentStatus = in_array($progress_log->instructor_status, $listStatuses) ? $progress_log->instructor_status : ''; 
      @endphp
      <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        <select id="selWeeklyStatus"
          class="px-4 py-2.5 border border-slate-200 rounded-xl w-full sm:w-64 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
          <option value="">-- Chọn trạng thái --</option>
          @foreach ($listStatuses as $status)
            <option value="{{ $status }}" {{ $currentStatus === $status ? 'selected' : '' }}>
              {{ $listStatusLabels[$status] ?? $status }}
            </option>
          @endforeach
        </select>
        <button id="btnConfirmStatus"
          class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-500 transition text-sm font-medium">
          <i class="ph ph-check-circle"></i> Xác nhận
        </button>
      </div>
      <div class="mt-3 text-sm text-slate-600">
        Trạng thái hiện tại:
        <span id="currentStatus"
          class="inline-block px-2.5 py-0.5 rounded-full border {{ $listColorsBorder[$currentStatus] ?? '' }} {{ $listColorsBackground[$currentStatus] ?? '' }} {{ $currentStatus ? ($listColorsText[$currentStatus] ?? '-') : '-' }} text-sm font-medium ">
          {{ $currentStatus ? ($listStatusLabels[$currentStatus] ?? '-') : '-' }}
        </span>
      </div>
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
      const idEl = document.getElementById('attachmentId');

      function render(val) {
        const m = STATUS_MAP[val];
        curr.textContent = m ? m.label : '-';
        curr.className = `inline-block px-2 py-0.5 rounded border ${m ? m.cls : 'border-slate-200 bg-slate-50 text-slate-700'}`;
      }

      btn?.addEventListener('click', async () => {
        const attachmentId = idEl?.value || '';
        const status = sel?.value || '';
        if (!attachmentId) { alert('Không xác định được tệp đính kèm để cập nhật.'); return; }
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
            // Gửi kèm attachment_id nếu backend cần
            body: JSON.stringify({ status, attachment_id: attachmentId })
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
  </script>
</body>

</html>