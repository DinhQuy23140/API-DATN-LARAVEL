<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Phân công giảng viên hướng dẫn</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <style>
    body { font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial; }
    html,body { height:100%; }
    body.lock-scroll { overflow:hidden; }
    .sidebar-collapsed .sidebar-label { display:none; }
    .sidebar-collapsed .sidebar { width:72px; }
    .sidebar { width:260px; }
    .table-wrap { overflow:auto; }
  </style>
</head>
<body class="bg-slate-50 text-slate-800">
@php
  $termId = $termId ?? request()->route('termId');
  // Dữ liệu mẫu (thay bằng dữ liệu thật từ Controller)
  $supervisors = $supervisors ?? [
    ['id'=>101, 'name'=>'TS. Đặng Hữu T', 'email'=>'danght@uni.edu', 'dept'=>'CNTT', 'expertise'=>'Web, AI', 'current'=>3, 'max'=>6],
    ['id'=>102, 'name'=>'ThS. Lưu Lan', 'email'=>'lanll@uni.edu', 'dept'=>'CNTT', 'expertise'=>'Mobile, UX', 'current'=>2, 'max'=>4],
    ['id'=>103, 'name'=>'TS. Nguyễn Văn A', 'email'=>'anng@uni.edu', 'dept'=>'HTTT', 'expertise'=>'Data, BI', 'current'=>5, 'max'=>5],
  ];
  $students = $students ?? [
    ['id'=>20123456, 'code'=>'20123456', 'name'=>'Nguyễn Văn A', 'class'=>'KTPM2025', 'email'=>'20123456@sv.uni.edu'],
    ['id'=>20124567, 'code'=>'20124567', 'name'=>'Trần Thị B', 'class'=>'KTPM2025', 'email'=>'20124567@sv.uni.edu'],
    ['id'=>20125678, 'code'=>'20125678', 'name'=>'Lê Văn C', 'class'=>'HTTT2025', 'email'=>'20125678@sv.uni.edu'],
  ];
@endphp

<div id="layoutRoot" class="flex h-screen">
    @php
    $user = auth()->user();
    $userName = $user->fullname ?? $user->name ?? 'Giảng viên';
    $email = $user->email ?? '';
    // Tùy mô hình dữ liệu, thay các field bên dưới cho khớp
    $dept = $user->department_name ?? optional($user->teacher)->department ?? '';
    $faculty = $user->faculty_name ?? optional($user->teacher)->faculty ?? '';
    $subtitle = trim(($dept ? "Bộ môn $dept" : '') . (($dept && $faculty) ? ' • ' : '') . ($faculty ? "Khoa $faculty" : ''));
    $degree = $user->teacher->degree ?? '';
    $teacherId = $user->teacher->id ?? 0;
    $avatarUrl = $user->avatar_url
      ?? $user->profile_photo_url
      ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    $departmentRole = $user->teacher->departmentRoles->where('role', 'head')->first() ?? null;
    $departmentId = $departmentRole?->department_id ?? 0;
  @endphp
  <!-- Sidebar (đồng nhất với round-detail) -->
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
          @if ($departmentRole)
          <a href="{{ route('web.teacher.thesis_rounds', ['teacherId' => $teacherId]) }}"
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

  <!-- Main -->
  <div class="flex-1 min-h-screen flex flex-col">
    <!-- Header cố định -->
    @php
      $user = auth()->user();
      $userName = $user->fullname ?? $user->name ?? 'Assistant';
      $email = $user->email ?? '';
      $avatarUrl = $user->avatar_url
        ?? $user->profile_photo_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    @endphp
    <header class="fixed top-0 left-0 md:left-[260px] right-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-40">
      <div class="flex items-center gap-3 flex-1">
        <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
        <div>
          <h1 class="text-lg md:text-xl font-semibold">Phân công giảng viên hướng dẫn</h1>
          <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Đồ án tốt nghiệp / Phân công GVHD • Đợt #{{ $termId }}</nav>
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
          <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
      </div>
    </header>

    <!-- Content scroll -->
    <main id="mainScroll" class="flex-1 pt-20 h-full overflow-y-auto px-4 md:px-6 pb-10 space-y-6">
      @php
        $lecturerCount   = is_countable($supervisors ?? []) ? count($supervisors) : 0;
        $unassignedCount = is_countable($students ?? []) ? count($students) : 0;
        $curTotal = 0; $maxTotal = 0;
        foreach(($supervisors ?? []) as $t){ $curTotal += (int)($t['current'] ?? 0); $maxTotal += (int)($t['max'] ?? 0); }
        $pct = $maxTotal > 0 ? min(100, round(($curTotal / $maxTotal) * 100)) : 0;
        $termName = $projectTerm->academy_year->year_name ?? $term->academyYear->year_name ?? 'Đồ án tốt nghiệp';
        $startLabel = (isset($projectTerm) && !empty($projectTerm->start_date)) ? \Carbon\Carbon::parse($projectTerm->start_date)->format('d/m/Y') : '—';
        $endLabel   = (isset($projectTerm) && !empty($projectTerm->end_date)) ? \Carbon\Carbon::parse($projectTerm->end_date)->format('d/m/Y') : '—';
      @endphp

      <!-- Thông tin đợt đồ án (modern card, consistent with blind-review) -->
      <section class="rounded-xl overflow-hidden">
        @php
          $startLabel = (isset($projectTerm) && !empty($projectTerm->start_date)) ? \Carbon\Carbon::parse($projectTerm->start_date)->format('d/m/Y') : '—';
          $endLabel   = (isset($projectTerm) && !empty($projectTerm->end_date)) ? \Carbon\Carbon::parse($projectTerm->end_date)->format('d/m/Y') : '—';
          $now = \Carbon\Carbon::now();
          $supervisorCount = isset($projectTerm->supervisors) ? $projectTerm->supervisors->count() : 0;
          $pendingCount = isset($unassignedAssignments) ? $unassignedAssignments->count() : 0;

          if ($projectTerm->start_date && $projectTerm->end_date) {
            $start = \Carbon\Carbon::parse($projectTerm->start_date);
            $end = \Carbon\Carbon::parse($projectTerm->end_date);
            if ($now->lt($start)) {
              $statusText = 'Sắp diễn ra';
              $badge = 'bg-yellow-50 text-yellow-700';
              $iconClass = 'text-yellow-600';
            } elseif ($now->gt($end)) {
              $statusText = 'Đã kết thúc';
              $badge = 'bg-slate-100 text-slate-600';
              $iconClass = 'text-slate-500';
            } else {
              $statusText = 'Đang diễn ra';
              $badge = 'bg-emerald-50 text-emerald-700';
              $iconClass = 'text-emerald-600';
            }
          } else {
            $statusText = $statusLabel ?? 'Sắp diễn ra';
            $badge = $statusClass ?? 'bg-yellow-50 text-yellow-700';
            $iconClass = 'text-yellow-600';
          }
        @endphp

        <div class="bg-gradient-to-r from-indigo-50 to-white border border-slate-200 p-4 md:p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-lg bg-indigo-600/10 grid place-items-center">
              <i class="ph ph-graduation-cap text-indigo-600 text-2xl"></i>
            </div>
            <div>
              <div class="text-sm text-slate-500">Đợt đồ án</div>
              <div class="text-lg md:text-xl font-semibold text-slate-900">Đợt {{ $projectTerm->stage }}</div>
              <div class="text-lg md:text-xl font-semibold text-slate-900">{{ $projectTerm->academy_year->year_name }}</div>
            </div>
          </div>

          <div class="flex items-center gap-3 md:gap-4">
            <div class="hidden md:flex items-center gap-3">
              <span class="inline-flex items-center gap-2 px-3 py-2 rounded-lg {{ $badge }} text-sm">
                <i class="ph ph-circle {{ $iconClass }}"></i>
                {{ $statusText }}
              </span>
            </div>

            <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
              <div class="flex items-center gap-3 bg-white border border-slate-100 rounded-lg px-3 py-2 shadow-sm">
                <div class="p-2 rounded-md bg-indigo-50 text-indigo-600">
                  <i class="ph ph-chalkboard text-lg"></i>
                </div>
                <div>
                  <div class="text-xs text-slate-500">Giảng viên</div>
                  <div class="text-sm font-semibold text-slate-800">{{ $supervisorCount }}</div>
                </div>
              </div>

              <div class="flex items-center gap-3 bg-white border border-slate-100 rounded-lg px-3 py-2 shadow-sm">
                <div class="p-2 rounded-md bg-indigo-50 text-indigo-600">
                  <i class="ph ph-student text-lg"></i>
                </div>
                <div>
                  <div class="text-xs text-slate-500">SV chưa có GVHD</div>
                  <div class="text-sm font-semibold text-slate-800">{{ $pendingCount }}</div>
                </div>
              </div>

              <div class="flex items-center gap-3 bg-white border border-slate-100 rounded-lg px-3 py-2 shadow-sm">
                <div class="p-2 rounded-md bg-indigo-50 text-indigo-600">
                  <i class="ph ph-calendar text-lg"></i>
                </div>
                <div>
                  <div class="text-xs text-slate-500">Khoảng thời gian</div>
                  <div class="text-sm font-semibold text-slate-800">{{ $startLabel }} — {{ $endLabel }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Thanh hành động chính -->
      <div class="flex items-center justify-between bg-white border border-slate-200 rounded-xl px-4 py-3">
        <div class="text-sm text-slate-600">
          Chọn 1 giảng viên và các sinh viên cần phân công, sau đó bấm nút.
        </div>
        <button id="btnAssignMain" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">
          <i class="ph ph-user-switch"></i>
          Phân công sinh viên
        </button>
      </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
        <!-- Bảng giảng viên -->
        <section class="bg-white rounded-xl border border-slate-200 h-full flex flex-col">
          <div class="p-3 border-b">
            <div>
              <div class="font-semibold">Giảng viên</div>
              <div class="text-xs text-slate-500">Chọn 1 giảng viên để phân công</div>
            </div>
          </div>
          <div class="p-3 border-b">
            <div class="relative max-w-md">
              <input id="searchTeachers" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm w-full" placeholder="Tìm theo tên, email">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
          </div>
          <div class="p-4 flex-1 overflow-auto">
            <div id="teachersList" class="grid grid-cols-1 gap-3">
              @php
                $supervisors = $projectTerm->supervisors ?? collect();
              @endphp
              @foreach ($supervisors as $t)
                @php
                  $pct = min(100, round(($t->assignment_supervisors->where('status', '!=', 'pending')->count() / max(1,$t->max_students))*100));
                  // màu thanh tiến độ (gradient)
                  $barClass = $pct>=100
                    ? 'bg-gradient-to-r from-rose-500 to-rose-600'
                    : ($pct>=75
                      ? 'bg-gradient-to-r from-amber-500 to-amber-600'
                      : 'bg-gradient-to-r from-emerald-500 to-emerald-600');
                  // màu badge phần trăm
                  $pctBadge = $pct>=100
                    ? 'bg-rose-50 text-rose-700'
                    : ($pct>=75 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700');
                @endphp
        <div class="teacher-card bg-white rounded-2xl p-4 shadow-sm hover:shadow-md border border-slate-100 transition-all duration-200"
          data-id="{{ $t->id }}"
          data-name="{{ $t->teacher->user->fullname }}"
          data-current="{{ $t->assignment_supervisors->count() }}"
          data-max="{{ $t->max_students }}"
          data-expertise="{{ $t->expertise }}"
                    tabindex="0" role="group" aria-label="Giảng viên {{ $t->teacher->user->fullname }}">
                  <div class="flex items-start gap-4">
                    <!-- Radio chọn -->
                    <div class="flex-shrink-0">
                      <input type="radio" name="teacher" class="mt-1 w-6 h-6 accent-[#10b981]" title="Chọn giảng viên">
                    </div>

                    <!-- Thông tin chính -->
                    <div class="flex-1">
                      <div class="font-medium text-slate-800 text-base" title="Tên giảng viên">{{ $t->teacher->user->fullname }}</div>
                      <div class="text-sm text-slate-500 mt-0.5" title="Email và chức vụ">{{ $t->teacher->user->email }} • {{ $t->teacher->position }}</div>
                      <div class="text-sm text-slate-600 mt-2" title="Chuyên môn / lĩnh vực nghiên cứu">{{ $t->expertise }}</div>

                      <!-- Tiêu đề Hướng nghiên cứu -->
                      <div class="text-xs font-semibold text-slate-700 mt-3 mb-1">Hướng nghiên cứu:</div>

                      <!-- Thẻ hướng nghiên cứu -->
                      <div class="flex flex-wrap gap-2">
                        @php
                          $teacherReseachAreas = $t->teacher->user->userResearches ?? collect();
                        @endphp
                        @if ($teacherReseachAreas != null && $teacherReseachAreas->count() > 0)
                          @foreach ($teacherReseachAreas as $area)
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-xs rounded-full border border-amber-100" title="{{ $area->research->name }}">
                              {{ $area->research->name }}
                            </span>
                          @endforeach
                        @else
                          <span class="text-xs text-slate-400 italic" title="Chưa có hướng nghiên cứu">Chưa có hướng nghiên cứu</span>
                        @endif
                      </div>
                    </div>

                    <!-- Thông tin số lượng sinh viên -->
                    <div class="w-24 text-right">
                      <div class="text-xs text-slate-500" title="Số sinh viên hiện tại / tối đa">Số SV</div>
                      <div class="mt-1 text-sm font-medium text-slate-800" title="{{ $t->assignment_supervisors->where('status', '!=', 'pending')->count() }} / {{ $t->max_students }}">
                        <span class="cur">{{ $t->assignment_supervisors->where('status', '!=', 'pending')->count() }}</span> /
                        <span class="max">{{ $t->max_students }}</span>
                      </div>
                      <div class="mt-2">
                        <span class="pct inline-flex items-center px-1.5 py-0.5 rounded-md text-[11px] font-medium {{ $pctBadge }}" title="Tỷ lệ sinh viên hiện tại: {{ $pct }}%">
                          {{ $pct }}%
                        </span>
                        <div class="w-full bg-slate-200 rounded-full h-2 mt-2" title="Progress bar số sinh viên">
                          <div class="h-2 rounded-full {{ $barClass }}" style="width: {{ $pct }}%"></div>
                        </div>
                      </div>
                    </div>
                    <!-- Action: xem thông tin -->
                    <div class="flex-shrink-0 ml-3">
                      <button type="button" class="btnViewSupervisor text-sm text-blue-600 hover:underline" data-id="{{ $t->id }}">Xem</button>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </section>

        <!-- Bảng sinh viên chưa có GVHD -->
        <section class="bg-white rounded-xl border border-slate-200 h-full flex flex-col">
          <div class="p-3 border-b">
            <div>
              <div class="font-semibold">Sinh viên chưa có giảng viên hướng dẫn</div>
              <div class="text-xs text-slate-500">Chọn 1 hoặc nhiều sinh viên để phân công</div>
            </div>
          </div>
          <div class="p-3 border-b">
            <div class="relative flex items-center gap-3 max-w-full">
              <div class="ml-3">
                <input id="chkAll" type="checkbox" class="inline-block" title="Chọn tất cả">
              </div>
              <div class="flex-1 md:flex md:items-center md:justify-end">
                <div class="relative w-full md:w-80">
                  <input id="searchStudents" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm w-full" placeholder="Tìm theo tên, mã SV">
                  <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="p-4 flex-1 overflow-auto">
            @php
              $assignments = $projectTerm->assignments ?? collect();
            @endphp
            <div id="studentsList" class="grid grid-cols-1 gap-3">
              @foreach ($unassignedAssignments as $s)
                @if ($s->status !== 'active')
                  <div class="student-card bg-gradient-to-r from-white to-blue-50 rounded-2xl p-4 shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 border border-blue-100 flex items-start gap-4" 
                      data-id="{{ $s->id }}" tabindex="0" role="group" aria-label="Sinh viên {{ $s->student->user->fullname }}">

                    <!-- Checkbox -->
                    <div class="flex-shrink-0 flex items-start">
                      <input type="checkbox" class="rowChk accent-[#10b981] w-5 h-5 mt-1.5" aria-label="Chọn sinh viên">
                    </div>

                    <!-- Avatar -->
                    <img class="h-12 w-12 rounded-full object-cover flex-shrink-0 ring-2 ring-blue-100" 
                        src="{{ $s->student->user->avatar_url ?? ('https://ui-avatars.com/api/?name=' . urlencode($s->student->user->fullname) . '&background=f0f9ff&color=0f172a') }}" 
                        alt="Avatar {{ $s->student->user->fullname }}">

                    <div class="flex-1 min-w-0 space-y-2">
                      <!-- Tên + Mã + Email -->
                      <div class="bg-white/70 p-2 rounded-lg">
                        <div class="flex items-center justify-between gap-3">
                          <div class="min-w-0">
                            <div class="text-sm font-semibold text-slate-900 truncate">{{ $s->student->user->fullname }}</div>
                            <div class="text-xs text-slate-600 truncate">
                              {{ $s->student->student_code }} • 
                              <span class="text-blue-600 font-medium">{{ $s->student->class_code }}</span>
                            </div>
                          </div>
                          <div class="flex-shrink-0 text-right ml-3">
                            <div class="text-xs text-slate-500">{{ $s->student->user->email }}</div>
                          </div>
                        </div>
                      </div>

                      <!-- Thông tin lớp / ngành / hướng nghiên cứu (with icons) -->
                      <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2 bg-blue-50/50 p-1.5 rounded-md">
                          <i class="ph ph-chalkboard text-slate-400 text-sm"></i>
                          <span class="text-xs font-medium text-slate-700">Lớp</span>
                          <span class="text-xs font-medium text-slate-700">{{ $s->student->class_code ?? '—' }}</span>
                        </div>
                        <div class="flex items-center gap-2 bg-green-50/50 p-1.5 rounded-md">
                          <i class="ph ph-book-open text-slate-400 text-sm"></i>
                          <span class="text-xs font-medium text-slate-700">Ngành</span>
                          <span class="text-xs font-medium text-slate-700">{{ $s->student->marjor->name ?? '—' }}</span>
                        </div>
                        <div class="flex items-center gap-2 bg-yellow-50/50 p-1.5 rounded-md">
                          <i class="ph ph-flask text-slate-400 text-sm"></i>
                          <span class="text-xs font-medium text-slate-500">Hướng nghiên cứu</span>
                          @php
                            $studentResearch = $s->student->user->userResearches ?? [];
                          @endphp
                          @if ($studentResearch != null)
                            @foreach ($studentResearch as $sr)
                              <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-700 text-[11px] rounded-full font-medium">{{ $sr->research->name }}</span>
                            @endforeach
                          @else
                            <span class="text-slate-500">—</span>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              @endforeach
            </div>
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>
</div>

@php
  // Build a simple map of supervisor id => assigned students for the current term
  $assignedMap = [];
  $supCollection = $projectTerm->supervisors ?? collect();
  foreach($supCollection as $sup){
    $arr = [];
    // assignment_supervisors holds the pivot/assignment entries for this supervisor
    $assignments = $sup->assignment_supervisors ?? collect();
    foreach($assignments->where('status','!=','pending') as $as){
      $s = $as->student;
      if(!$s) continue;
      $arr[] = [
        'assignment_id' => $as->id,
        'student_id' => $s->id,
        'name' => $s->user->fullname ?? ($s->user->name ?? ''),
        'code' => $s->student_code ?? '',
        'email' => $s->user->email ?? '',
        'class' => $s->class_code ?? ''
      ];
    }
    $assignedMap[$sup->id] = $arr;
  }
@endphp

<script>
  // Sidebar/header logic giống round-detail
  const html=document.documentElement, sidebar=document.getElementById('sidebar');
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
  document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed');setCollapsed(c);localStorage.setItem('assistant_sidebar',''+(c?1:0));});
  document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
  if(localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);
  sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');


  // Profile dropdown
  const profileBtn=document.getElementById('profileBtn');
  const profileMenu=document.getElementById('profileMenu');
  profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
  document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

  // Graduation submenu toggle + mở sẵn
  document.addEventListener('DOMContentLoaded', () => {
    const wrap = document.querySelector('.graduation-item');
    const toggleBtn = wrap?.querySelector('.toggle-button');
    const submenu = wrap?.querySelector('.submenu');
    const caret = wrap?.querySelector('.ph.ph-caret-down');
    if (submenu && submenu.classList.contains('hidden')) submenu.classList.remove('hidden');
    if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
    caret?.classList.add('transition-transform', 'rotate-180');
    toggleBtn?.addEventListener('click', (e) => {
      e.preventDefault();
      submenu?.classList.toggle('hidden');
      const expanded = !submenu?.classList.contains('hidden');
      toggleBtn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
      caret?.classList.toggle('rotate-180', expanded);
    });
  });

  document.body.classList.add('lock-scroll'); // khóa body scroll
  // đảm bảo main luôn có scrollbar riêng (tránh layout shift)
  const mainEl = document.getElementById('mainScroll');
  mainEl.classList.add('overflow-y-scroll');

  const CSRF='{{ csrf_token() }}';
  // assignedMap: supervisor id -> array of assigned students for the current term
  const assignedMap = @json($assignedMap ?? []);

  // Search filters (operate on card lists)
  document.getElementById('searchTeachers')?.addEventListener('input', (e)=>{
    const q=(e.target.value||'').toLowerCase();
    document.querySelectorAll('#teachersList .teacher-card').forEach(card=>{
      card.style.display = card.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
  });
  document.getElementById('searchStudents')?.addEventListener('input', (e)=>{
    const q=(e.target.value||'').toLowerCase();
    document.querySelectorAll('#studentsList .student-card').forEach(card=>{
      card.style.display = card.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
    // When filtering, ensure select-all reflects visible items
    syncChkAll?.();
  });

  // Check all students (select visible)
  document.getElementById('chkAll')?.addEventListener('change', (e)=>{
    document.querySelectorAll('#studentsList .rowChk').forEach(chk=> {
      const card = chk.closest('.student-card');
      if (card && card.style.display !== 'none') chk.checked = e.target.checked;
    });
  });

  // helper to sync header select-all state (exists optionally)
  function syncChkAll(){
    const all = Array.from(document.querySelectorAll('#studentsList .student-card')).filter(c=>c.style.display !== 'none');
    if(all.length===0){ document.getElementById('chkAll') && (document.getElementById('chkAll').indeterminate = false, document.getElementById('chkAll').checked = false); return; }
    const checks = all.map(c => !!c.querySelector('.rowChk')?.checked);
    const allTrue = checks.every(Boolean);
    const noneTrue = checks.every(v=>!v);
    const chk = document.getElementById('chkAll');
    if(!chk) return;
    chk.checked = allTrue;
    chk.indeterminate = (!allTrue && !noneTrue);
  }

  // Confirm modal (customizable)
  // options: { teacherName, count, onConfirm, title, message }
  function openConfirmModal({teacherName, count, onConfirm, title, message}) {
  const wrap=document.createElement('div');
  // use Tailwind-supported z-index so confirm modal appears above supervisor modal
  wrap.className='fixed inset-0 z-50 flex items-center justify-center px-4';
    const modalTitle = title || 'Xác nhận phân công';
    const modalMessage = message || `Phân công <span class="font-semibold">${count}</span> sinh viên cho <span class="font-semibold">${escapeHtml(teacherName)}</span>?`;
    wrap.innerHTML=`
      <div class="absolute inset-0 bg-slate-900/50" data-close></div>
      <div class="relative w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b flex items-center justify-between">
          <div class="flex items-center gap-2">
            <div class="h-9 w-9 grid place-items-center rounded-lg bg-amber-50 text-amber-600"><i class="ph ph-user-switch"></i></div>
            <h3 class="font-semibold text-lg">${escapeHtml(modalTitle)}</h3>
          </div>
          <button class="p-2 rounded-lg hover:bg-slate-100" data-close><i class="ph ph-x"></i></button>
        </div>
        <div class="p-5">
          <p class="text-sm text-slate-700">${modalMessage}</p>
        </div>
        <div class="px-5 py-4 border-t bg-slate-50 flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-lg border hover:bg-slate-100 text-sm" data-close>Hủy</button>
          <button id="confirmAssign" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">Xác nhận</button>
        </div>
      </div>`;
    function close(){ wrap.remove(); document.removeEventListener('keydown', esc); }
    function esc(e){ if(e.key==='Escape') close(); }
    wrap.addEventListener('click', (e)=>{ if(e.target.hasAttribute('data-close')||e.target===wrap) close(); });
    document.addEventListener('keydown', esc);
    document.body.appendChild(wrap);
    wrap.querySelector('#confirmAssign')?.addEventListener('click', ()=>{ onConfirm?.(); close(); });
  }

  function toast(msg, type='success'){
    const host=document.getElementById('toastHost')||(()=>{const d=document.createElement('div');d.id='toastHost';d.className='fixed top-4 right-4 z-50 space-y-2';document.body.appendChild(d);return d;})();
    const color= type==='success'?'bg-emerald-600':type==='error'?'bg-rose-600':'bg-slate-800';
    const el=document.createElement('div'); el.className=`px-4 py-2 rounded-lg text-white text-sm shadow ${color}`; el.textContent=msg; host.appendChild(el);
    setTimeout(()=>{ el.style.opacity='0'; el.style.transform='translateY(-4px)'; el.style.transition='all .25s'; }, 1800);
    setTimeout(()=> el.remove(), 2100);
  }

  function updateTeacherCapacityRow(teacherRow, delta=0){
    if(!teacherRow) return;
    const curEl = teacherRow.querySelector('.cur');
    const maxEl = teacherRow.querySelector('.max');
    const pctEl = teacherRow.querySelector('.pct');
    const bar   = teacherRow.querySelector('.h-2.rounded-full');
    let cur = parseInt(curEl?.textContent || teacherRow.dataset.current || '0', 10) + (delta||0);
    const max = parseInt(maxEl?.textContent || teacherRow.dataset.max || '0', 10) || 0;
    cur = Math.max(0, cur);
    teacherRow.dataset.current = cur;
    if(curEl) curEl.textContent = cur;
    const pct = max > 0 ? Math.min(100, Math.round((cur / max) * 100)) : 0;
    if(pctEl){
      pctEl.textContent = pct + '%';
      const badgeClass = pct>=100
        ? 'bg-rose-50 text-rose-700'
        : (pct>=75 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700');
      pctEl.className = `pct inline-flex items-center px-1.5 py-0.5 rounded-md text-[11px] font-medium ${badgeClass}`;
    }
    if(bar){
      bar.style.width = pct + '%';
      const grad = pct>=100
        ? 'bg-gradient-to-r from-rose-500 to-rose-600'
        : (pct>=75 ? 'bg-gradient-to-r from-amber-500 to-amber-600' : 'bg-gradient-to-r from-emerald-500 to-emerald-600');
      bar.className = `h-2 rounded-full ${grad}`;
    }
  }

  async function doAssign(){
  const teacherRow = document.querySelector('#teachersList input[type="radio"]:checked')?.closest('.teacher-card');
     if(!teacherRow) { toast('Vui lòng chọn giảng viên', 'error'); return; }
     const teacherName = teacherRow.dataset.name;
     const cur = parseInt(teacherRow.dataset.current||'0',10);
     const max = parseInt(teacherRow.dataset.max||'0',10);

     const selectedStudents = Array.from(document.querySelectorAll('#studentsList .rowChk:checked'))
       .map(chk => chk.closest('.student-card'));
     if(selectedStudents.length===0){ toast('Vui lòng chọn ít nhất 1 sinh viên', 'error'); return; }

     if(cur + selectedStudents.length > max){
       toast('Vượt quá chỉ tiêu của giảng viên', 'error'); return;
     }

     openConfirmModal({
       teacherName, count: selectedStudents.length,
       onConfirm: async ()=>{
         // Gọi API thực tế
         try {
           const res = await fetch("{{ route('web.head.assign_supervisors.bulk', ['termId'=>$termId]) }}", {
             method:'POST',
             headers:{
               'Content-Type':'application/json',
               'X-CSRF-TOKEN':'{{ csrf_token() }}',
               'Accept':'application/json'
             },
             body: JSON.stringify({
               supervisor_id: teacherRow.dataset.id,
               project_term_id: {{ $termId }},
               assignment_ids: selectedStudents.map(tr=>tr.dataset.id),
               status: 'accepted'
             })
           });
           const json = await res.json();
           if(!res.ok){
             toast(json.message || 'Lỗi phân công', 'error');
             return;
           }
         } catch(e){
           toast('Lỗi mạng', 'error');
           return;
         }

         // Cập nhật UI: số SV + % + thanh tiến độ trong hàng giảng viên
         updateTeacherCapacityRow(teacherRow, selectedStudents.length);
        // Update assignedMap so modal shows newly assigned students without reload
        try{
          const supId = teacherRow.dataset.id;
          assignedMap[supId] = assignedMap[supId] || [];
          selectedStudents.forEach(card => {
            const name = card.querySelector('.text-sm.font-semibold')?.innerText.trim() || (card.querySelector('img')?.alt || '').replace(/^Avatar\s*/,'');
            const codeText = card.querySelector('.text-xs.text-slate-600')?.innerText || '';
            const code = (codeText.split('•')[0]||'').trim() || card.dataset.code || '';
            const cls = card.querySelector('.text-blue-600')?.innerText?.trim() || '';
            const email = card.querySelector('.text-xs.text-slate-500')?.innerText?.trim() || '';
            assignedMap[supId].push({ assignment_id: card.dataset.id, student_id: card.dataset.id, name, code, email, class: cls });
          });
        }catch(e){ /* ignore */ }

          selectedStudents.forEach(card => card.remove());
          // sync select-all header if present
          syncChkAll?.();
          toast('Phân công thành công');
       }
     });
  }
  document.getElementById('btnAssign')?.addEventListener('click', doAssign);
  document.getElementById('btnAssignMain')?.addEventListener('click', doAssign);

  // keep select-all header in sync when individual checkboxes change
  document.querySelector('#studentsList')?.addEventListener('change', (e)=>{
    if(e.target && e.target.matches('.rowChk')){
      syncChkAll?.();
      // visual selected state (indigo accent)
      const card = e.target.closest('.student-card');
      if(card) {
        const checked = !!e.target.checked;
        card.classList.toggle('bg-indigo-50', checked);
        card.classList.toggle('ring-2', checked);
        card.classList.toggle('ring-indigo-100', checked);
      }
    }
  });

  // Make student cards clickable to toggle their checkbox (supports multi-select)
  document.querySelector('#studentsList')?.addEventListener('click', (e) => {
    const card = e.target.closest('.student-card');
    if (!card) return;
    // Ignore clicks on real interactive controls so native behavior is preserved
    const interactive = e.target.closest('input, a, button, label, select, textarea');
    if (interactive && interactive !== card) {
      // allow native checkbox clicks to propagate
      if (interactive.tagName && interactive.tagName.toLowerCase() === 'input') return;
      return;
    }
    const chk = card.querySelector('.rowChk');
    if (!chk) return;
    chk.checked = !chk.checked;
  // toggle visual state (indigo accent)
  const checked = !!chk.checked;
  card.classList.toggle('bg-indigo-50', checked);
  card.classList.toggle('ring-2', checked);
  card.classList.toggle('ring-indigo-100', checked);
    syncChkAll?.();
  });

  // Keyboard support: Space / Enter toggles selection when card is focused
  document.querySelector('#studentsList')?.addEventListener('keydown', (e) => {
    const card = e.target.closest('.student-card');
    if (!card) return;
    if (e.key === ' ' || e.key === 'Spacebar' || e.key === 'Enter') {
      e.preventDefault();
      const chk = card.querySelector('.rowChk');
      if (!chk) return;
      chk.checked = !chk.checked;
  const checked = !!chk.checked;
  card.classList.toggle('bg-indigo-50', checked);
  card.classList.toggle('ring-2', checked);
  card.classList.toggle('ring-indigo-100', checked);
      syncChkAll?.();
    }
  });

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

  // --- Supervisor modal logic (fetch assignments from server) ---
  // build API URL template (replace __SUPID__ with supervisor id at runtime)
  const supervisorListApiTemplate = "{{ url('head/assignments/supervisor/__SUPID__/term/'.$termId.'/list') }}";

  async function openSupervisorModal(supId){
    const teacherCard = document.querySelector(`#teachersList .teacher-card[data-id="${supId}"]`);
    const dataName = teacherCard?.dataset?.name || 'Giảng viên';
    const current = teacherCard?.dataset?.current || '0';
    const max = teacherCard?.dataset?.max || '0';
    const expertise = teacherCard?.dataset?.expertise || '';

    // fetch assignments from server
    let students = [];
    try{
      const url = supervisorListApiTemplate.replace('__SUPID__', encodeURIComponent(supId));
      const res = await fetch(url, { headers: { 'Accept':'application/json' } });
      if(res.ok){
        const j = await res.json();
        if(j && Array.isArray(j.data)){
          students = j.data.map(row => ({
            // assignment_id here must be the AssignmentSupervisor (pivot) id so
            // delete (which uses AssignmentSupervisor implicit binding) works.
            assignment_id: row.id ?? row.assignment_supervisor_id ?? row.assignment_id,
            student_id: row.student?.id ?? null,
            name: row.student?.name ?? '',
            code: row.student?.code ?? '',
            email: row.student?.email ?? '',
            class: row.student?.class ?? ''
          }));
        }
      } else {
        // fallback to client-side assignedMap if available
        students = (assignedMap && assignedMap[supId]) ? assignedMap[supId] : [];
      }
    }catch(e){
      students = (assignedMap && assignedMap[supId]) ? assignedMap[supId] : [];
    }

  const wrap=document.createElement('div');
  // place supervisor modal under confirm modal (lower z-index)
  wrap.className='fixed inset-0 z-40 flex items-center justify-center px-4';
    wrap.innerHTML = `
      <div class="absolute inset-0 bg-slate-900/50" data-close></div>
      <div class="relative w-full max-w-2xl bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-50 text-blue-600"><i class="ph ph-user"></i></div>
            <div>
              <div class="font-semibold text-lg">${escapeHtml(dataName)}</div>
              <div class="text-xs text-slate-500">SV hiện tại: ${escapeHtml(current)} / ${escapeHtml(max)}</div>
            </div>
          </div>
          <button class="p-2 rounded-lg hover:bg-slate-100" data-close><i class="ph ph-x"></i></button>
        </div>
        <div class="p-5 space-y-4">
          <div class="text-sm text-slate-700">Chuyên môn: <span class="font-medium">${escapeHtml(expertise)}</span></div>
          <div>
            <div class="text-sm font-semibold mb-2">Danh sách sinh viên đang hướng dẫn</div>
            <div class="overflow-auto max-h-64 border rounded-md">
              <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs"><tr><th class="p-2 text-left">Mã</th><th class="p-2 text-left">Họ tên</th><th class="p-2 text-left">Lớp</th><th class="p-2 text-left">Email</th><th class="p-2 text-left">Hành động</th></tr></thead>
                <tbody class="align-top">${students.length>0 ? students.map(s => `
                  <tr class="border-b last:border-b-0" data-assignment-id="${s.assignment_id}">
                    <td class="p-2 align-top">${escapeHtml(s.code)}</td>
                    <td class="p-2 align-top">${escapeHtml(s.name)}</td>
                    <td class="p-2 align-top">${escapeHtml(s.class)}</td>
                    <td class="p-2 align-top">${escapeHtml(s.email)}</td>
                    <td class="p-2 align-top"><button data-assign-id="${s.assignment_id}" class="deleteAssignmentBtn text-rose-600 text-sm hover:underline">Xóa</button></td>
                  </tr>`).join('') : `<tr><td class="p-4" colspan="5">Chưa có sinh viên được phân công.</td></tr>`}
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="px-5 py-4 border-t bg-slate-50 flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-lg border hover:bg-slate-100 text-sm" data-close>Đóng</button>
        </div>
      </div>
    `;
    function close(){ wrap.remove(); document.removeEventListener('keydown', esc); }
    function esc(e){ if(e.key==='Escape') close(); }
    wrap.addEventListener('click', (e)=>{ if(e.target.hasAttribute('data-close')||e.target===wrap) close(); });
    document.addEventListener('keydown', esc);
    document.body.appendChild(wrap);

    // attach delete handlers for rows in this modal
    wrap.querySelectorAll('.deleteAssignmentBtn').forEach(btn => {
      btn.addEventListener('click', async (ev) => {
        ev.stopPropagation();
        const asId = btn.dataset.assignId;
        if(!asId) return;
        openConfirmModal({
          teacherName: dataName,
          count: 1,
          title: 'Xác nhận xóa',
          message: `Bạn có chắc muốn xóa phân công của <span class="font-semibold">${escapeHtml(dataName)}</span>?`,
          onConfirm: async () => {
          try{
            const delUrl = `{{ url('head/assignment-supervisors') }}/${encodeURIComponent(asId)}`;
            const res = await fetch(delUrl, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' } });
            const j = await res.json();
            if(!res.ok){ toast(j.message || 'Lỗi khi xóa', 'error'); return; }
            // remove from DOM row
            const row = btn.closest('tr');
            if(row) row.remove();
            // update client-side assignedMap and teacher counts
            try{
              // `supId` is captured from the outer scope (openSupervisorModal parameter)
              if(typeof supId !== 'undefined'){
                const supKey = String(supId);
                if(window.assignedMap && assignedMap[supKey]){
                  assignedMap[supKey] = assignedMap[supKey].filter(x => String(x.assignment_id) !== String(asId));
                }
                // update capacity visual (decrement by 1)
                const teacherCardNode = document.querySelector(`#teachersList .teacher-card[data-id="${supId}"]`);
                updateTeacherCapacityRow(teacherCardNode, -1);
              }
            }catch(e){ /* ignore */ }
            toast('Xóa phân công thành công');
          }catch(err){ toast('Lỗi khi xóa', 'error'); }
        }});
      });
    });
  }

  // small helper to escape html when injecting strings
  function escapeHtml(str){ if(!str && str!==0) return ''; return String(str).replace(/[&<>"']/g, function(m){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[m]; }); }

  // wire click handlers for view buttons (event delegation)
  document.getElementById('teachersList')?.addEventListener('click', (e)=>{
    const btn = e.target.closest('.btnViewSupervisor');
    if(!btn) return;
    e.stopPropagation();
    const id = btn.dataset.id;
    if(id) openSupervisorModal(id);
  });
</script>
<div id="toastHost" class="fixed top-4 right-4 z-50 space-y-2"></div>
</body>
</html>