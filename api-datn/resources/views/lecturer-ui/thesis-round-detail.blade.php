<!DOCTYPE html>

<html lang="vi">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Chi tiết đợt đồ án - Giảng viên</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
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

    .timeline-stage.active .w-12 {
      transform: scale(1.1);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    }

    .timeline-stage:hover .w-12 {
      transform: scale(1.05);
    }

    .timeline-stage .w-12 {
      transition: all 0.2s ease;
    }
  </style>
</head>

<body class="bg-slate-50 text-slate-800">
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
    $teacherId = $user->teacher->id ?? 0;
    $avatarUrl = $user->avatar_url
      ?? $user->profile_photo_url
      ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    $assignments = $rows->assignments;
  $stage = $rows->stagetimelines->sortBy('number_of_rounds');
  $stageTimeline = $rows->stageTimelines?->sortBy('number_of_rounds') ?? collect();
  // Precompute progress percentage (number of completed stages / total stages)
  $progressWidth = 0;
  foreach ($stageTimeline as $st) {
    $endDate = $st->end_date ?? null;
    if ($endDate && Carbon::parse($endDate)->isPast()) $progressWidth++;
  }
  $totalStages = max(1, $stageTimeline->count());
  $pct = ($progressWidth * 100) / $totalStages;
  $pct = max(0, min(100, round($pct)));
    $departmentRole = $user->teacher->departmentRoles->where('role', 'head')->first() ?? null;
    $departmentId = $departmentRole?->department_id ?? 0;
  @endphp

  @php
    $listProgressLog = $assignments[0]->project->progressLogs ?? [];
    $latestLog = collect($listProgressLog)->sortByDesc('created_at')->first() ?? null;
  @endphp
  <div class="flex min-h-screen">
    <aside class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all"
      id="sidebar">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i
            class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Giảng viên</div>
          <div class="text-xs text-slate-500">Bảng điều khiển</div>
        </div>
      </div>
      @php
        // Mở nhóm "Học phần tốt nghiệp" nếu vào các trang liên quan (kể cả trang chi tiết)
        $isThesisOpen = request()->routeIs('web.teacher.thesis_internship')
          || request()->routeIs('web.teacher.thesis_rounds')
          || request()->routeIs('web.teacher.thesis_round_detail'); // thêm route detail nếu có
        // Active item "Đồ án tốt nghiệp" trong submenu cho cả list + detail
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

        @php
          $isThesisOpen = request()->routeIs('web.teacher.thesis_internship') || request()->routeIs('web.teacher.thesis_rounds');
        @endphp
        <button type="button" id="toggleThesisMenu" aria-controls="thesisSubmenu"
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
          @if ($departmentRole)
          <a href="{{ route('web.teacher.thesis_rounds', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ $isThesisRoundsActive ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
            @if($isThesisRoundsActive) aria-current="page" @endif>
            <i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span>
          </a>
          @else
          <a href="{{ route('web.teacher.thesis_rounds', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ $isThesisRoundsActive ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
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
          <button class="md:hidden p-2 rounded-lg hover:bg-slate-100" id="openSidebar"><i
              class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Chi tiết đợt đồ án</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đồ án tốt nghiệp</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Chi tiết đợt đồ án</span>
            </nav>
          </div>
        </div>
        <div class="relative">
          <button class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100" id="profileBtn">
            <img alt="avatar" class="h-9 w-9 rounded-full object-cover" src="{{ $avatarUrl }}" />
            <div class="hidden sm:block text-left">
              <div class="text-sm font-semibold leading-4">{{ $userName }}</div>
              <div class="text-xs text-slate-500">{{ $email }}</div>
            </div>
            <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
          </button>
          <div
            class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm"
            id="profileMenu">
            <a class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="ph ph-user"></i>Xem thông
              tin</a>
            <a class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600" 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
            href="#"><i
                class="ph ph-sign-out"></i>Đăng xuất</a>
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">@csrf</form>
          </div>
        </div>
      </header>
      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 space-y-6">
        <div class="max-w-6xl mx-auto space-y-6">
          <!-- Round Info (modern card) -->
          @php
            $termName = ($rows->academy_year->year_name ?? '—') . ' - Học kỳ ' . ($rows->stage ?? '');
            $startLabel = $rows->start_date ? Carbon::parse($rows->start_date)->format('d/m/Y') : '—';
            $endLabel = $rows->end_date ? Carbon::parse($rows->end_date)->format('d/m/Y') : '—';
            $now = Carbon::now();
            if ($rows->start_date && $rows->end_date) {
              $s = Carbon::parse($rows->start_date); $e = Carbon::parse($rows->end_date);
              if ($now->lt($s)) { $statusText = 'Sắp diễn ra'; $badge = 'bg-yellow-50 text-yellow-700'; $iconClass = 'text-yellow-600'; }
              elseif ($now->gt($e)) { $statusText = 'Đã kết thúc'; $badge = 'bg-slate-100 text-slate-600'; $iconClass = 'text-slate-500'; }
              else { $statusText = 'Đang diễn ra'; $badge = 'bg-emerald-50 text-emerald-700'; $iconClass = 'text-emerald-600'; }
            } else { $statusText = 'Sắp diễn ra'; $badge = 'bg-yellow-50 text-yellow-700'; $iconClass = 'text-yellow-600'; }

            $supervisorCount = isset($rows->supervisors) ? $rows->supervisors->count() : 0;
            $studentCount = isset($assignments) ? $assignments->count() : 0;
            $councilCount = isset($councils) ? $councils->count() : 0;
          @endphp

          <section class="rounded-xl overflow-hidden mb-4">
            <div class="bg-gradient-to-r from-indigo-50 to-white border border-slate-200 p-4 md:p-5 flex flex-col md:flex-row md:items-center gap-4">
              <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-lg bg-indigo-600/10 grid place-items-center">
                  <i class="ph ph-graduation-cap text-indigo-600 text-2xl"></i>
                </div>
                <div>
                  <div class="text-sm text-slate-500">Đợt đồ án</div>
                  <div class="text-lg md:text-xl font-semibold text-slate-900">{{ $termName }}</div>
                  <div class="mt-1 text-sm text-slate-600 flex items-center gap-2">
                    <i class="ph ph-calendar-dots text-slate-400"></i>
                    <span>{{ $startLabel }} — {{ $endLabel }}</span>
                  </div>
                </div>
              </div>

              <div class="flex items-center gap-3 md:gap-4 ml-auto">
                <div class="hidden md:flex items-center">
                  <span class="inline-flex items-center gap-2 px-3 py-2 rounded-lg {{ $badge }} text-sm">
                    <i class="ph ph-circle {{ $iconClass }}"></i>
                    {{ $statusText }}
                  </span>
                </div>

                <div class="flex items-center gap-3">
                  <a href="{{ route('web.teacher.student_supervisor_term', ['supervisorId' => $supervisorId, 'termId' => $rows->id]) }}">
                    <div class="flex items-center gap-3 bg-white border border-slate-100 rounded-lg px-3 py-2 shadow-sm">
                      <div class="p-2 rounded-md bg-indigo-50 text-indigo-600">
                        <i class="ph ph-student text-lg"></i>
                      </div>
                      <div>
                        <div class="text-xs text-slate-500">Số sinh viên</div>
                        <div class="text-sm font-semibold text-slate-800">{{ $studentCount }}</div>
                      </div>
                    </div>
                  </a>

                  <a href="{{ route('web.teacher.my_committees', ['supervisorId' => $supervisorId, 'termId' => $rows->id]) }}">
                    <div class="flex items-center gap-3 bg-white border border-slate-100 rounded-lg px-3 py-2 shadow-sm">
                      <div class="p-2 rounded-md bg-indigo-50 text-indigo-600">
                        <i class="ph ph-users-three text-lg"></i>
                      </div>
                      <div>
                        <div class="text-xs text-slate-500">Số hội đồng</div>
                        <div class="text-sm font-semibold text-slate-800">{{ $councilCount }}</div>
                      </div>
                    </div>
                  </a>
                </div>
              
              </div>
            </div>
          </section>
          <!-- Timeline -->
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-6">
              <h3 class="font-semibold">Tiến độ giai đoạn hướng dẫn</h3>
              <div class="flex items-center gap-2 text-sm">
                @php
                  // Calculate progress percentage based on number of completed stages
                  $progressWidth = 0;
                  $totalStages = max(1, $stageTimeline->count());
                @endphp
                <span class="font-medium" id="progressText">{{ $pct }}%</span>
                <div class="w-40 h-2 rounded-full bg-slate-100 overflow-hidden">
                  <div class="h-full bg-blue-600" id="progressBar" style="width:{{ $pct }}%"></div>
                </div>
              </div>
            </div>
            <!-- Horizontal Timeline -->
            <div class="relative">
              <!-- Progress Line -->
            <div class="absolute top-6 left-8 right-8 h-0.5 bg-slate-200">
              @php
                  // Count completed stages
                  $progressWidth = 0;
                  foreach ($stageTimeline as $index => $stageDate) {
                      $endDate = $stageDate->end_date ?? null;
                      if ($endDate && Carbon::parse($endDate)->isPast()) {
                          $progressWidth++;
                      }
                  }
                  $totalStages = max(1, $stageTimeline->count());
                  $pct = ($progressWidth * 100) / $totalStages;
                  // clamp 0..100 and format
                  $pct = max(0, min(100, round($pct)));
              @endphp
                  <div class="h-full bg-emerald-600" style="width: {{ $pct }}%"></div>
            </div>
              <!-- Timeline Items -->
              <div class="grid grid-cols-8 gap-4 relative">
                <!-- Stage 1 -->
              @php
                $startDateStage1 = $stageTimeline[0]->start_date ?? null;
                $endDateStage1   = $stageTimeline[0]->end_date ?? null;
                $today = now();

                if ($startDateStage1 && $today->lt(Carbon::parse($startDateStage1))) {
                    // Trạng thái: Chưa bắt đầu
                    $statusStage1 = 'Chưa bắt đầu';
                    $statusColor = 'text-slate-500';
                    $backgroundStage1 = 'bg-slate-400';
                }
                elseif ($endDateStage1 && $today->gt(Carbon::parse($endDateStage1))) {
                    // Trạng thái: Hoàn thành
                    $statusStage1 = 'Hoàn thành';
                    $statusColor = 'text-emerald-600';
                    $backgroundStage1 = 'bg-emerald-600';
                }
                else {
                    // Trạng thái: Đang diễn ra
                    $statusStage1 = 'Đang diễn ra';
                    $statusColor = 'text-blue-600';
                    $backgroundStage1 = 'bg-blue-600';
                }
              @endphp
                <div class="timeline-stage cursor-pointer" data-stage="1" onclick="showStageDetails(1)">
                  <div
                    class="w-12 h-12 mx-auto {{ $backgroundStage1 }} rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    1</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Tiếp nhận</div>
                    <div class="text-xs {{ $statusColor }} mt-1">{{ $statusStage1 }}</div>
                  </div>
                </div>
                <!-- Stage 2 -->
              @php
                $startDateStage2 = $stageTimeline[1]->start_date ?? null;
                $endDateStage2   = $stageTimeline[1]->end_date ?? null;
                $today = now();

                if ($startDateStage2 && $today->lt(Carbon::parse($startDateStage2))) {
                    // Trạng thái: Chưa bắt đầu
                    $statusStage2 = 'Chưa bắt đầu';
                    $statusColor = 'text-slate-500';
                    $backgroundStage2 = 'bg-slate-400';
                }
                elseif ($endDateStage2 && $today->gt(Carbon::parse($endDateStage2))) {
                    // Trạng thái: Hoàn thành
                    $statusStage2 = 'Hoàn thành';
                    $statusColor = 'text-emerald-600';
                    $backgroundStage2 = 'bg-emerald-600';
                }
                else {
                    // Trạng thái: Đang diễn ra
                    $statusStage2 = 'Đang diễn ra';
                    $statusColor = 'text-blue-600';
                    $backgroundStage2 = 'bg-blue-600';
                }
              @endphp
                <div class="timeline-stage cursor-pointer" data-stage="2" onclick="showStageDetails(2)">
                  <div
                    class="w-12 h-12 mx-auto {{ $backgroundStage2 }} rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    2</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Đề cương</div>
                    <div class="text-xs {{ $statusColor }} mt-1">{{ $statusStage2 }}</div>
                  </div>
                </div>
                <!-- Stage 3 -->
              @php
                $startDateStage3 = $stageTimeline[2]->start_date ?? null;
                $endDateStage3   = $stageTimeline[2]->end_date ?? null;
                $today = now();

                if ($startDateStage3 && $today->lt(Carbon::parse($startDateStage3))) {
                    // Trạng thái: Chưa bắt đầu
                    $statusStage3 = 'Chưa bắt đầu';
                    $statusColor = 'text-slate-500';
                    $backgroundStage3 = 'bg-slate-400';
                }
                elseif ($endDateStage3 && $today->gt(Carbon::parse($endDateStage3))) {
                    // Trạng thái: Hoàn thành
                    $statusStage3 = 'Hoàn thành';
                    $statusColor = 'text-emerald-600';
                    $backgroundStage3 = 'bg-emerald-600';
                }
                else {
                    // Trạng thái: Đang diễn ra
                    $statusStage3 = 'Đang diễn ra';
                    $statusColor = 'text-blue-600';
                    $backgroundStage3 = 'bg-blue-600';
                }
              @endphp
                <div class="timeline-stage cursor-pointer" data-stage="3" onclick="showStageDetails(3)">
                  <div
                    class="w-12 h-12 mx-auto {{ $backgroundStage3 }} rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    3</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Nhật ký</div>
                    <div class="text-xs {{ $statusColor }} mt-1">{{ $statusStage3 }}</div>
                  </div>
                </div>
                <!-- Stage 4 -->
              @php
                $startDateStage4 = $stageTimeline[3]->start_date ?? null;
                $endDateStage4   = $stageTimeline[3]->end_date ?? null;
                $today = now();

                if ($startDateStage4 && $today->lt(Carbon::parse($startDateStage4))) {
                    // Trạng thái: Chưa bắt đầu
                    $statusStage4 = 'Chưa bắt đầu';
                    $statusColor = 'text-slate-500';
                    $backgroundStage4 = 'bg-slate-400';
                }
                elseif ($endDateStage4 && $today->gt(Carbon::parse($endDateStage4))) {
                    // Trạng thái: Hoàn thành
                    $statusStage4 = 'Hoàn thành';
                    $statusColor = 'text-emerald-600';
                    $backgroundStage4 = 'bg-emerald-600';
                }
                else {
                    // Trạng thái: Đang diễn ra
                    $statusStage4 = 'Đang diễn ra';
                    $statusColor = 'text-blue-600';
                    $backgroundStage4 = 'bg-blue-600';
                }
              @endphp
                <div class="timeline-stage cursor-pointer" data-stage="4" onclick="showStageDetails(4)">
                  <div
                    class="w-12 h-12 mx-auto {{ $backgroundStage4 }} rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    4</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Báo cáo</div>
                    <div class="text-xs {{ $statusColor }} mt-1">{{ $statusStage4 }}</div>
                  </div>
                </div>
                <!-- Stage 5 -->
                 @php
                $startDateStage5 = $stageTimeline[4]->start_date ?? null;
                $endDateStage5   = $stageTimeline[4]->end_date ?? null;
                $today = now();

                if ($startDateStage5 && $today->lt(Carbon::parse($startDateStage5))) {
                    // Trạng thái: Chưa bắt đầu
                    $statusStage5 = 'Chưa bắt đầu';
                    $statusColor = 'text-slate-500';
                    $backgroundStage5 = 'bg-slate-400';
                }
                elseif ($endDateStage5 && $today->gt(Carbon::parse($endDateStage5))) {
                    // Trạng thái: Hoàn thành
                    $statusStage5 = 'Hoàn thành';
                    $statusColor = 'text-emerald-600';
                    $backgroundStage5 = 'bg-emerald-600';
                }
                else {
                    // Trạng thái: Đang diễn ra
                    $statusStage5 = 'Đang diễn ra';
                    $statusColor = 'text-blue-600';
                    $backgroundStage5 = 'bg-blue-600';
                }
              @endphp
                <div class="timeline-stage cursor-pointer" data-stage="5" onclick="showStageDetails(5)">
                  <div
                    class="w-12 h-12 mx-auto {{ $backgroundStage5 }} rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    5</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Hội đồng</div>
                    <div class="text-xs {{ $statusColor }} mt-1">{{ $statusStage5 }}</div>
                  </div>
                </div>
                <!-- Stage 6 -->
              @php
                $startDateStage6 = $stageTimeline[5]->start_date ?? null;
                $endDateStage6   = $stageTimeline[5]->end_date ?? null;
                $today = now();

                if ($startDateStage6 && $today->lt(Carbon::parse($startDateStage6))) {
                    // Trạng thái: Chưa bắt đầu
                    $statusStage6 = 'Chưa bắt đầu';
                    $statusColor = 'text-slate-500';
                    $backgroundStage6 = 'bg-slate-400';
                }
                elseif ($endDateStage6 && $today->gt(Carbon::parse($endDateStage6))) {
                    // Trạng thái: Hoàn thành
                    $statusStage6 = 'Hoàn thành';
                    $statusColor = 'text-emerald-600';
                    $backgroundStage6 = 'bg-emerald-600';
                }
                else {
                    // Trạng thái: Đang diễn ra
                    $statusStage6 = 'Đang diễn ra';
                    $statusColor = 'text-blue-600';
                    $backgroundStage6 = 'bg-blue-600';
                }
              @endphp
                <div class="timeline-stage cursor-pointer" data-stage="6" onclick="showStageDetails(6)">
                  <div
                    class="w-12 h-12 mx-auto {{ $backgroundStage6 }} rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    6</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Phản biện</div>
                    <div class="text-xs {{ $statusColor }} mt-1">{{ $statusStage6 }}</div>
                  </div>
                </div>
                <!-- Stage 7 -->
                @php
                  $startDateStage7 = $stageTimeline[6]->start_date ?? null;
                  $endDateStage7   = $stageTimeline[6]->end_date ?? null;
                  $today = now();

                  if ($startDateStage7 && $today->lt(Carbon::parse($startDateStage7))) {
                      // Trạng thái: Chưa bắt đầu
                      $statusStage7 = 'Chưa bắt đầu';
                      $statusColor = 'text-slate-500';
                      $backgroundStage7 = 'bg-slate-400';
                  }
                  elseif ($endDateStage7 && $today->gt(Carbon::parse($endDateStage7))) {
                      // Trạng thái: Hoàn thành
                      $statusStage7 = 'Hoàn thành';
                      $statusColor = 'text-emerald-600';
                      $backgroundStage7 = 'bg-emerald-600';
                  }
                  else {
                      // Trạng thái: Đang diễn ra
                      $statusStage7 = 'Đang diễn ra';
                      $statusColor = 'text-blue-600';
                      $backgroundStage7 = 'bg-blue-600';
                  }
                @endphp
                <div class="timeline-stage cursor-pointer" data-stage="7" onclick="showStageDetails(7)">
                  <div
                    class="w-12 h-12 mx-auto {{ $backgroundStage7 }} rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    7</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Công bố</div>
                    <div class="text-xs {{ $statusColor }} mt-1">{{ $statusStage7 }}</div>
                  </div>
                </div>
                <!-- Stage 8 -->
                @php
                  $startDateStage8 = $stageTimeline[7]->start_date ?? null;
                  $endDateStage8   = $stageTimeline[7]->end_date ?? null;
                  $today = now();

                  if ($startDateStage8 && $today->lt(Carbon::parse($startDateStage8))) {
                      // Trạng thái: Chưa bắt đầu
                      $statusStage8 = 'Chưa bắt đầu';
                      $statusColor = 'text-slate-500';
                      $backgroundStage8 = 'bg-slate-400';
                  }
                  elseif ($endDateStage8 && $today->gt(Carbon::parse($endDateStage8))) {
                      // Trạng thái: Hoàn thành
                      $statusStage8 = 'Hoàn thành';
                      $statusColor = 'text-emerald-600';
                      $backgroundStage8 = 'bg-emerald-600';
                  }
                  else {
                      // Trạng thái: Đang diễn ra
                      $statusStage8 = 'Đang diễn ra';
                      $statusColor = 'text-blue-600';
                      $backgroundStage8 = 'bg-blue-600';
                  }
                @endphp
                <div class="timeline-stage cursor-pointer" data-stage="8" onclick="showStageDetails(8)">
                  <div
                    class="w-12 h-12 mx-auto {{ $backgroundStage8 }} rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    8</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Bảo vệ</div>
                    <div class="text-xs {{ $statusColor }} mt-1">{{ $statusStage8 }}</div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Timeline Details Panel -->
            <div class="mt-8 p-6 bg-slate-50 rounded-lg" id="timelineDetails">
              <div id="stageContent">
                <div class="text-center text-slate-500">
                  <i class="ph ph-cursor-click text-2xl mb-2"></i>
                  <p>Click vào một giai đoạn để xem chi tiết chức năng</p>
                </div>
              </div>
            </div>
            <!-- Legend -->
            <div class="mt-6 text-xs text-slate-500 flex flex-wrap gap-4">
              <span class="inline-flex items-center gap-1"><span
                  class="h-2.5 w-2.5 rounded-full bg-emerald-600"></span>Hoàn thành</span>
              <span class="inline-flex items-center gap-1"><span
                  class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>Đang diễn ra</span>
              <span class="inline-flex items-center gap-1"><span
                  class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>Sắp tới</span>
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>

  <script></script>
  <script>
    function showStageDetails(stageNum) {
      const contentBox = document.getElementById("stageContent");
      if (!contentBox) return;
      switch (stageNum) {
        case 1:
          contentBox.innerHTML = `
          <h3 class="text-lg font-semibold mb-1">Giai đoạn 01</h3>
          <div class="mt-2 mb-3 p-3 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border border-emerald-100 shadow-sm inline-block">
            <p class="text-base font-semibold text-emerald-700 flex items-center gap-2">
              <span class="text-lg">📅</span>
              Thời gian:
              <span class="ml-1 font-medium text-green-700">
                {{ Carbon::parse($stage[0]->start_date)->format('d/m/Y') }}
                —
                {{ Carbon::parse($stage[0]->end_date)->format('d/m/Y') }}
              </span>
            </p>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('web.teacher.requests_management', ['supervisorId' => $supervisorId, 'termId' => $rows->id]) }}" 
              class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
              <div class="flex items-start gap-3">
                <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-emerald-50 to-emerald-100 text-emerald-600 group-hover:from-emerald-100 group-hover:to-emerald-200">
                  <i class="ph ph-inbox"></i>
                </div>
                <div class="flex-1">
                  <div class="font-medium">Tiếp nhận yêu cầu sinh viên</div>
                  <div class="text-xs text-slate-500 mt-0.5">Xem, lọc và duyệt các yêu cầu xin hướng dẫn.</div>
                  <div class="mt-3">
                    <span class="inline-flex items-center gap-1.5 text-emerald-700 text-sm group-hover:gap-2 transition-all">
                      Mở <i class="ph ph-arrow-right"></i>
                    </span>
                  </div>
                </div>
              </div>
            </a>

            <a href="{{ route('web.teacher.proposed_topic', ['supervisorId' => $supervisorId, 'termId' => $rows->id]) }}" 
              class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
              <div class="flex items-start gap-3">
                <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-indigo-50 to-indigo-100 text-indigo-600 group-hover:from-indigo-100 group-hover:to-indigo-200">
                  <i class="ph ph-notebook"></i>
                </div>
                <div class="flex-1">
                  <div class="font-medium">Đề xuất danh sách đề tài</div>
                  <div class="text-xs text-slate-500 mt-0.5">Tạo, chỉnh sửa, đóng/mở đề tài để SV đăng ký.</div>
                  <div class="mt-3">
                    <span class="inline-flex items-center gap-1.5 text-indigo-700 text-sm group-hover:gap-2 transition-all">
                      Mở <i class="ph ph-arrow-right"></i>
                    </span>
                  </div>
                </div>
              </div>
            </a>

            <a href="{{ route('web.teacher.student_supervisor_term', ['supervisorId' => $supervisorId, 'termId' => $rows->id]) }}" 
              class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
              <div class="flex items-start gap-3">
                <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600 group-hover:from-blue-100 group-hover:to-blue-200">
                  <i class="ph ph-users-three"></i>
                </div>
                <div class="flex-1">
                  <div class="font-medium">Danh sách sinh viên hướng dẫn</div>
                  <div class="text-xs text-slate-500 mt-0.5">Quản lý danh sách SV, cập nhật tiến độ và trạng thái.</div>
                  <div class="mt-3">
                    <span class="inline-flex items-center gap-1.5 text-blue-700 text-sm group-hover:gap-2 transition-all">
                      Mở <i class="ph ph-arrow-right"></i>
                    </span>
                  </div>
                </div>
              </div>
            </a>
            @if($departmentRole)
            <a href="{{ route('web.head.thesis_round_supervision', ['departmentId' => $departmentId, 'termId' => $rows->id]) }}" 
              class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-amber-300 transition">

              <div class="flex items-start gap-3">
                <!-- Icon -->
                <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-amber-50 to-amber-100 text-amber-600 group-hover:from-amber-100 group-hover:to-amber-200">
                  <i class="ph ph-user-switch text-lg"></i>
                </div>

                <!-- Nội dung -->
                <div class="flex-1">
                  <div class="font-medium text-slate-800">Phân công giảng viên hướng dẫn</div>
                  <div class="text-[11px] font-medium text-amber-600 uppercase tracking-wide mt-0.5">Gán GVHD</div>
                  <div class="text-xs text-slate-500 mt-1">
                    Phân công / điều chỉnh nhanh sinh viên cho giảng viên phụ trách.
                  </div>

                  <!-- Nút mở -->
                  <div class="mt-3">
                    <span class="inline-flex items-center gap-1.5 text-amber-700 text-sm group-hover:gap-2 transition-all">
                      Mở <i class="ph ph-arrow-right"></i>
                    </span>
                  </div>
                </div>
              </div>
            </a>
            @endif
          </div>
      `;
          break;
        case 2:
          contentBox.innerHTML = `
        <h3 class="text-lg font-semibold mb-3">Giai đoạn 02: Đề cương sinh viên</h3>
        <div class="mt-2 mb-3 p-3 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border border-emerald-100 shadow-sm inline-block">
          <p class="text-base font-semibold text-emerald-700 flex items-center gap-2">
            <span class="text-lg">📅</span>
            Thời gian:
            <span class="ml-1 font-medium text-green-700">
              {{ Carbon::parse($stage[1]->start_date)->format('d/m/Y') }}
              —
              {{ Carbon::parse($stage[1]->end_date)->format('d/m/Y') }}
            </span>
          </p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <a href="{{ route('web.teacher.supervised_outline_reports', ['supervisorId' => $supervisorId, 'termId' => $rows->id]) }}" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
            <div class="flex items-start gap-3">
              <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-indigo-50 to-indigo-100 text-indigo-600 group-hover:from-indigo-100 group-hover:to-indigo-200">
                <i class="ph ph-files"></i>
              </div>
              <div class="flex-1">
                <div class="font-medium">Danh sách đề cương của sinh viên hướng dẫn</div>
                <div class="text-xs text-slate-500 mt-0.5">Theo dõi các lần nộp đề cương, trạng thái và thao tác.</div>
                <div class="mt-3">
                  <span class="inline-flex items-center gap-1.5 text-indigo-700 text-sm group-hover:gap-2 transition-all">
                    Mở <i class="ph ph-arrow-right"></i>
                  </span>
                </div>
              </div>
            </div>
          </a>
          <a href="{{ route('web.teacher.outline_review_assignments', ['termId' => $rows->id, 'supervisorId' => $supervisorId]) }}" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
            <div class="flex items-start gap-3">
              <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-amber-50 to-amber-100 text-amber-600 group-hover:from-amber-100 group-hover:to-amber-200">
                <i class="ph ph-pencil-line"></i>
              </div>
              <div class="flex-1">
                <div class="font-medium">Chấm đề cương sinh viên</div>
                <div class="text-xs text-slate-500 mt-0.5">Danh sách đề cương được phân công chấm điểm.</div>
                <div class="mt-3">
                  <span class="inline-flex items-center gap-1.5 text-amber-700 text-sm group-hover:gap-2 transition-all">
                    Mở <i class="ph ph-arrow-right"></i>
                  </span>
                </div>
              </div>
            </div>
          </a>
          @if ($departmentRole)
          <a href="{{ route('web.head.blind_review_lecturers', ['departmentId' => $departmentId, 'termId' => $rows->id]) }}" 
            class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-violet-300 transition">

            <div class="flex items-start gap-3">
              <!-- Icon -->
              <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-violet-50 to-violet-100 text-violet-600 group-hover:from-violet-100 group-hover:to-violet-200">
                <i class="ph ph-eye-slash text-lg"></i>
              </div>

              <!-- Nội dung -->
              <div class="flex-1">
                <div class="font-medium text-slate-800">Phân phản biện kín</div>
                <div class="text-[11px] font-medium text-violet-600 uppercase tracking-wide mt-0.5">Ẩn GVPB</div>
                <div class="text-xs text-slate-500 mt-1">
                  Quản lý phản biện ẩn giúp đảm bảo tính khách quan.
                </div>

                <!-- Nút mở -->
                <div class="mt-3">
                  <span class="inline-flex items-center gap-1.5 text-violet-700 text-sm group-hover:gap-2 transition-all">
                    Mở <i class="ph ph-arrow-right"></i>
                  </span>
                </div>
              </div>
            </div>
          </a>
          @endif
        </div>
        <!-- Giữ nguyên bảng -->
        <div class="bg-white border rounded-xl p-4">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-3">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchStage2" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tên/MSSV/đề tài" />
            </div>
            <div class="flex items-center gap-2 text-xs">
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-700"><span class="h-2 w-2 rounded-full bg-slate-400"></span> Chưa nộp</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700"><span class="h-2 w-2 rounded-full bg-amber-400"></span> Đã nộp</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Đã duyệt</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-50 text-rose-700"><span class="h-2 w-2 rounded-full bg-rose-500"></span> Bị từ chối</span>
            </div>
          </div>
<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-md">
  <table id="tableStage2" class="w-full text-sm">
    <!-- Header -->
    <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
      <tr class="text-slate-700">
        <th class="py-3 px-4 font-semibold text-left">Sinh viên</th>
        <th class="py-3 px-4 font-semibold text-center">MSSV</th>
        <th class="py-3 px-4 font-semibold">Đề tài</th>
        <th class="py-3 px-4 font-semibold text-center">Trạng thái đề cương</th>
        <th class="py-3 px-4 font-semibold">Lần nộp cuối</th>
        <th class="py-3 px-4 font-semibold text-center">Hành động</th>
      </tr>
    </thead>

    <!-- Body -->
    <tbody class="divide-y divide-slate-100">
          @foreach ($assignments as $assignment)
        @php
          $student = $assignment->student;
          $fullname = $student->user->fullname;
          $student_code = $student->student_code;
          $studentId = $student->id;
          $topic = $assignment->project->name ?? 'Chưa có đề tài';

          $latestReport = $assignment->project?->reportFiles()->where('type_report', 'outline')->latest('created_at')->first();
          $statusRaw = $latestReport?->status ?? 'none';

          $listStatus = [
            'none' => ['label' => 'Chưa nộp', 'class' => 'bg-slate-100 text-slate-600', 'icon' => 'ph-clock'],
            'pending' => ['label' => 'Đã nộp', 'class' => 'bg-amber-100 text-amber-700', 'icon' => 'ph-hourglass'],
            'submitted' => ['label' => 'Đã nộp', 'class' => 'bg-amber-100 text-amber-700', 'icon' => 'ph-hourglass'],
            'approved' => ['label' => 'Đã duyệt', 'class' => 'bg-emerald-100 text-emerald-700', 'icon' => 'ph-check-circle'],
            'rejected' => ['label' => 'Bị từ chối', 'class' => 'bg-rose-100 text-rose-700', 'icon' => 'ph-x-circle'],
            'passed' => ['label' => 'Đã duyệt phản biện kín', 'class' => 'bg-emerald-50 text-emerald-700', 'icon' => 'ph-check-circle'],
            'failured' => ['label' => 'Bị từ chối phản biện kín', 'class' => 'bg-rose-50 text-rose-700', 'icon' => 'ph-x-circle'],
          ];
          $statusConfig = $listStatus[$statusRaw] ?? $listStatus['none'];

          $updateLast = $latestReport?->created_at?->format('H:i:s d/m/Y') ?? 'Chưa nộp báo cáo';
        @endphp

        <tr class="hover:bg-slate-50 transition-colors">
          <!-- Sinh viên -->
          <td class="py-3 px-4">
            <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
               class="text-blue-600 hover:underline font-medium">
              {{ $fullname }}
            </a>
          </td>

          <!-- MSSV -->
          <td class="py-3 px-4 text-center font-mono text-slate-700">{{ $student_code }}</td>

          <!-- Đề tài -->
          <td class="py-3 px-4 text-slate-700">{{ $topic }}</td>

          <!-- Trạng thái -->
          <td class="py-3 px-4 text-center">
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $statusConfig['class'] }}">
              <i class="ph {{ $statusConfig['icon'] }} text-sm"></i>
              {{ $statusConfig['label'] }}
            </span>
          </td>

          <!-- Lần nộp cuối -->
          <td class="py-3 px-4 text-slate-600">{{ $updateLast }}</td>

          <!-- Hành động -->
          <td class="py-3 px-4 text-center">
            <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-100 transition">
              <i class="ph ph-eye"></i> Xem
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

        </div>`;
          break;
        case 3:
          contentBox.innerHTML = `
        <h3 class="text-lg font-semibold mb-3">Giai đoạn 03: Nhật ký tuần của sinh viên</h3>
        <div class="mt-2 mb-3 p-3 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border border-emerald-100 shadow-sm inline-block">
          <p class="text-base font-semibold text-emerald-700 flex items-center gap-2">
            <span class="text-lg">📅</span>
            Thời gian:
            <span class="ml-1 font-medium text-green-700">
              {{ Carbon::parse($stage[2]->start_date)->format('d/m/Y') }}
              —
              {{ Carbon::parse($stage[2]->end_date)->format('d/m/Y') }}
            </span>
          </p>
        </div>
        <div class="bg-white border rounded-xl p-4">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-3">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchStage3" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tên/MSSV/tuần" />
            </div>
            <div class="flex items-center gap-2 text-xs">
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-700"><span class="h-2 w-2 rounded-full bg-slate-400"></span> Chưa nộp</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700"><span class="h-2 w-2 rounded-full bg-amber-400"></span> Đã nộp</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Đã chấm</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-50 text-rose-700"><span class="h-2 w-2 rounded-full bg-rose-500"></span> Cần bổ sung</span>
            </div>
          </div>
<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-md">
  <table id="tableStage3" class="w-full text-sm">
    <!-- Header -->
    <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
      <tr class="text-slate-700">
        <th class="py-3 px-4 font-semibold text-left">Sinh viên</th>
        <th class="py-3 px-4 font-semibold text-center">MSSV</th>
        <th class="py-3 px-4 font-semibold">Tuần gần nhất</th>
        <th class="py-3 px-4 font-semibold text-center">Trạng thái</th>
        <th class="py-3 px-4 font-semibold">Lần cập nhật</th>
        <th class="py-3 px-4 font-semibold text-center">Hành động</th>
      </tr>
    </thead>

    <!-- Body -->
    <tbody class="divide-y divide-slate-100">
      @foreach ($assignments as $assignment)
        @php
          $student = $assignment->student;
          $fullname = $student->user->fullname;
          $student_code = $student->student_code;
          $studentId = $student->id;

          $listProgressLog = $assignment->project?->progressLogs ?? [];
          $latestLog = collect($listProgressLog)->sortByDesc('created_at')->first() ?? null;

          $lastestTitle = $latestLog->title ?? 'Tiêu đề tuần chưa có';
          $lastestStatusRaw = $latestLog->student_status ?? 'in_progress';

          $listStatus = [
            'none' => ['label' => 'Chưa nộp', 'class' => 'bg-slate-100 text-slate-600', 'icon' => 'ph-clock'],
            'in_progress' => ['label' => 'Đang thực hiện', 'class' => 'bg-amber-100 text-amber-700', 'icon' => 'ph-hourglass'],
            'completed' => ['label' => 'Đã hoàn thành', 'class' => 'bg-emerald-100 text-emerald-700', 'icon' => 'ph-check-circle'],
            'not_completed' => ['label' => 'Cần bổ sung', 'class' => 'bg-rose-100 text-rose-700', 'icon' => 'ph-warning'],
          ];

          $lastestTime = $latestLog?->created_at?->format('H:i:s d/m/Y') ?? 'Chưa có';
          $statusConfig = $listStatus[$lastestStatusRaw] ?? $listStatus['none'];
        @endphp

        <tr class="hover:bg-slate-50 transition-colors">
          <!-- Sinh viên -->
          <td class="py-3 px-4">
            <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id, 'supervisorId' => $supervisorId, 'assignmentId' => $assignment->id]) }}"
               class="text-blue-600 hover:underline font-medium">
              {{ $fullname }}
            </a>
          </td>

          <!-- MSSV -->
          <td class="py-3 px-4 text-center font-mono text-slate-700">{{ $student_code }}</td>

          <!-- Tuần gần nhất -->
          <td class="py-3 px-4 text-slate-700 text-center font-medium">{{ $lastestTitle }}</td>

          <!-- Trạng thái -->
          <td class="py-3 px-4 text-center">
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $statusConfig['class'] }}">
              <i class="ph {{ $statusConfig['icon'] }} text-sm"></i>
              {{ $statusConfig['label'] }}
            </span>
          </td>

          <!-- Thời gian -->
          <td class="py-3 px-4 text-slate-600 text-center">{{ $lastestTime }}</td>

          <!-- Hành động -->
          <td class="py-3 px-4 text-center">
            <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-100 transition">
              <i class="ph ph-eye"></i> Xem
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>


        </div>`;
          break;
        case 4:
          contentBox.innerHTML = `
        <h3 class="text-lg font-semibold mb-3">Giai đoạn 04: Báo cáo cuối</h3>
        <div class="mt-2 mb-3 p-3 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border border-emerald-100 shadow-sm inline-block">
          <p class="text-base font-semibold text-emerald-700 flex items-center gap-2">
            <span class="text-lg">📅</span>
            Thời gian:
            <span class="ml-1 font-medium text-green-700">
              {{ Carbon::parse($stage[3]->start_date)->format('d/m/Y') }}
              —
              {{ Carbon::parse($stage[3]->end_date)->format('d/m/Y') }}
            </span>
          </p>
        </div>
        <div class="bg-white border rounded-xl p-4">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-3">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchStage4" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tên/MSSV/đề tài" />
            </div>
            <div class="flex items-center gap-2 text-xs">
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-700"><span class="h-2 w-2 rounded-full bg-slate-400"></span> Chưa nộp</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700"><span class="h-2 w-2 rounded-full bg-amber-400"></span> Đã nộp</span>
            </div>
          </div>
          <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-md">
            <table id="tableStage4" class="w-full text-sm">
              <!-- Header -->
              <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                <tr class="text-slate-700">
                  <th class="py-3 px-4 font-semibold">Sinh viên</th>
                  <th class="py-3 px-4 font-semibold text-center">MSSV</th>
                  <th class="py-3 px-4 font-semibold">Đề tài</th>
                  <th class="py-3 px-4 font-semibold text-center">Trạng thái báo cáo</th>
                  <th class="py-3 px-4 font-semibold">Lần nộp</th>
                  <th class="py-3 px-4 font-semibold text-center">Hành động</th>
                </tr>
              </thead>

              <!-- Body -->
              <tbody class="divide-y divide-slate-100">
                @foreach ($assignments as $assignment)
                  @php
                    $student = $assignment->student;
                    $fullname = $student->user->fullname;
                    $student_code = $student->student_code;
                    $studentId = $student->id;
                    $topic = $assignment->project->name ?? 'Chưa có đề tài';

                    // Lấy report cuối cùng
                    $latestReport = $assignment->project?->reportFiles()
                    ->where('type_report', 'report')
                    ->latest('created_at')
                    ->first();
                    $statusRaw = $latestReport?->status ?? 'none';

                    $listStatus = [
                      'none' => ['label' => 'Chưa nộp', 'class' => 'bg-slate-100 text-slate-600', 'icon' => 'ph-clock'],
                      'pending' => ['label' => 'Đã nộp', 'class' => 'bg-amber-100 text-amber-700', 'icon' => 'ph-upload-simple'],
                      'approved' => ['label' => 'Đã duyệt', 'class' => 'bg-emerald-100 text-emerald-700', 'icon' => 'ph-check-circle'],
                      'rejected' => ['label' => 'Bị từ chối', 'class' => 'bg-rose-100 text-rose-700', 'icon' => 'ph-x-circle'],
                    ];
                    $statusConfig = $listStatus[$statusRaw] ?? $listStatus['none'];

                    $lastSubmit = $latestReport?->created_at?->format('d/m/Y') ?? 'Chưa có';
                  @endphp

                  <tr class="hover:bg-slate-50 transition-colors">
                    <!-- Sinh viên -->
                    <td class="py-3 px-4">
                      <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
                        class="text-blue-600 hover:underline font-medium">
                        {{ $fullname }}
                      </a>
                    </td>

                    <!-- MSSV -->
                    <td class="py-3 px-4 text-center font-mono text-slate-700">{{ $student_code }}</td>

                    <!-- Đề tài -->
                    <td class="py-3 px-4 text-slate-700">{{ $topic }}</td>

                    <!-- Trạng thái -->
                    <td class="py-3 px-4 text-center">
                      <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $statusConfig['class'] }}">
                        <i class="ph {{ $statusConfig['icon'] }} text-sm"></i>
                        {{ $statusConfig['label'] }}
                      </span>
                    </td>

                    <!-- Lần nộp -->
                    <td class="py-3 px-4 text-slate-600">{{ $lastSubmit }}</td>

                    <!-- Hành động -->
                    <td class="py-3 px-4 text-center">
                      <div class="flex justify-center gap-2">
                        <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
                          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-100 transition">
                          <i class="ph ph-eye"></i> Xem
                        </a>
                        <a href="{{ $latestReport?->file_url ?? '#' }}"
                          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-blue-600 hover:bg-blue-50 transition">
                          <i class="ph ph-download-simple"></i> Tải
                        </a>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>`;
          break;
        case 5:
          contentBox.innerHTML = `
        <h3 class="text-lg font-semibold mb-3">Giai đoạn 05: Hội đồng</h3>
        <div class="mt-2 mb-3 p-3 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border border-emerald-100 shadow-sm inline-block">
          <p class="text-base font-semibold text-emerald-700 flex items-center gap-2">
            <span class="text-lg">📅</span>
            Thời gian:
            <span class="ml-1 font-medium text-green-700">
              {{ Carbon::parse($stage[4]->start_date)->format('d/m/Y') }}
              —
              {{ Carbon::parse($stage[4]->end_date)->format('d/m/Y') }}
            </span>
          </p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <a href="{{ route('web.teacher.student_committee', ['supervisorId' => $supervisorId, 'termId' => $rows->id]) }}" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
            <div class="flex items-start gap-3">
              <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-fuchsia-50 to-fuchsia-100 text-fuchsia-600 group-hover:from-fuchsia-100 group-hover:to-fuchsia-200">
                <i class="ph ph-student"></i>
              </div>
              <div class="flex-1">
                <div class="font-medium">Xem hội đồng của sinh viên</div>
                <div class="text-xs text-slate-500 mt-0.5">Thông tin hội đồng và lịch bảo vệ theo SV.</div>
                <div class="mt-3">
                  <span class="inline-flex items-center gap-1.5 text-fuchsia-700 text-sm group-hover:gap-2 transition-all">
                    Mở <i class="ph ph-arrow-right"></i>
                  </span>
                </div>
              </div>
            </div>
          </a>
          <a href="{{ route('web.teacher.my_committees', ['supervisorId' => $supervisorId, 'termId' => $rows->id] )}}" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
            <div class="flex items-start gap-3">
              <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-sky-50 to-sky-100 text-sky-600 group-hover:from-sky-100 group-hover:to-sky-200">
                <i class="ph ph-users-three"></i>
              </div>
              <div class="flex-1">
                <div class="font-medium">Danh sách hội đồng tham gia</div>
                <div class="text-xs text-slate-500 mt-0.5">Các hội đồng bạn tham gia, bấm để xem chi tiết.</div>
                <div class="mt-3">
                  <span class="inline-flex items-center gap-1.5 text-sky-700 text-sm group-hover:gap-2 transition-all">
                    Mở <i class="ph ph-arrow-right"></i>
                  </span>
                </div>
              </div>
            </div>
          </a>

        </div>
        <!-- Giữ nguyên bảng -->
        <div class="bg-white border rounded-xl p-4">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-3">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchStage5" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tên/MSSV/hội đồng" />
            </div>
            <div class="flex items-center gap-2"></div>
          </div>
          <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-md">
            <table id="tableStage5" class="w-full text-sm">
              <!-- Header -->
              <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                <tr class="text-slate-700">
                  <th class="py-3 px-4 font-semibold">Sinh viên</th>
                  <th class="py-3 px-4 font-semibold text-center">MSSV</th>
                  <th class="py-3 px-4 font-semibold">Đề tài</th>
                  <th class="py-3 px-4 font-semibold">Giảng viên hướng dẫn</th>
                  <th class="py-3 px-4 font-semibold text-center">Hội đồng</th>
                  <th class="py-3 px-4 font-semibold text-center">Phòng</th>
                  <th class="py-3 px-4 font-semibold text-center">Hành động</th>
                </tr>
              </thead>

              <!-- Body -->
              <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @foreach ($assignments as $assignment)
                  @php
                    $student = $assignment->student;
                    $fullname = $student->user->fullname;
                    $student_code = $student->student_code;
                    $studentId = $student->id;
                    $topic = $assignment->project?->name ?? 'Chưa có đề tài';
                    $assignment_supervisors = $assignment->assignment_supervisors->where('status', 'accepted') ?? [];

                    $committee = $assignment->council_project?->council->name ?? 'Chưa có hội đồng';
                    $councilId = $assignment->council_project?->council_id;
                    $schedule  = $assignment->council_project?->council?->date ?? 'Chưa có lịch';
                    $room = $assignment->council_project?->council?->address ?? 'Chưa có phòng';
                  @endphp

                  <tr class="hover:bg-slate-50 transition-colors">
                    <!-- Họ tên -->
                    <td class="py-3 px-4">
                      <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
                        class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-medium transition">
                        <i class="ph ph-user-circle text-base"></i>
                        <span>{{ $fullname }}</span>
                      </a>
                    </td>

                    <!-- MSSV -->
                    <td class="py-3 px-4 text-center font-mono text-slate-600">
                      <span class="bg-slate-50 px-2 py-1 rounded-md text-xs">{{ $student_code }}</span>
                    </td>

                    <!-- Đề tài -->
                    <td class="py-3 px-4 text-slate-700">
                      <div class="flex items-start gap-2">
                        <i class="ph ph-book text-slate-400 mt-0.5"></i>
                        <span>{{ $topic }}</span>
                      </div>
                    </td>

                    <!-- Giảng viên hướng dẫn -->
                    <td class="py-3 px-4 text-slate-700">
                      @forelse ($assignment_supervisors as $assignment_supervisor)
                        @php
                          $supervisorName = $assignment_supervisor->supervisor->teacher->user->fullname ?? 'Chưa có';
                        @endphp
                        <div class="flex items-center gap-2 mb-1 last:mb-0">
                          <i class="ph ph-chalkboard-teacher text-slate-400"></i>
                          <span>{{ $supervisorName }}</span>
                        </div>
                      @empty
                        <span class="text-slate-400 italic">Chưa có GVHD</span>
                      @endforelse
                    </td>

                    <!-- Hội đồng -->
                    <td class="py-3 px-4 text-center">
                      <span class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-full text-xs font-medium">
                        <i class="ph ph-users-three text-base"></i>
                        {{ $committee }}
                      </span>
                    </td>

                    <!-- Phòng -->
                    <td class="py-3 px-4 text-center">
                      <div class="inline-flex items-center gap-1 text-slate-600">
                        <i class="ph ph-map-pin text-slate-400"></i>
                        <span>{{ $room }}</span>
                      </div>
                    </td>

                    <!-- Hành động -->
                    <td class="py-3 px-4 text-center">
                      <div class="flex justify-center gap-2">
                        <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
                          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-100 transition">
                          <i class="ph ph-user"></i> SV
                        </a>
                        @if($councilId)
                          <a href="{{ route('web.teacher.committee_detail', ['councilId' => $councilId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-indigo-200 text-xs font-medium text-indigo-600 hover:bg-indigo-50 transition">
                            <i class="ph ph-users-three"></i> Hội đồng
                          </a>
                        @else
                          <span class="text-xs text-slate-400 italic">Chưa có hội đồng</span>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>`;
          break;
        case 6:
          contentBox.innerHTML = `
        <h3 class="text-lg font-semibold mb-3">Giai đoạn 06: Phản biện</h3>
        <div class="mt-2 mb-3 p-3 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border border-emerald-100 shadow-sm inline-block">
          <p class="text-base font-semibold text-emerald-700 flex items-center gap-2">
            <span class="text-lg">📅</span>
            Thời gian:
            <span class="ml-1 font-medium text-green-700">
              {{ Carbon::parse($stage[5]->start_date)->format('d/m/Y') }}
              —
              {{ Carbon::parse($stage[5]->end_date)->format('d/m/Y') }}
            </span>
          </p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <a href="{{ route('web.teacher.student_review', ['termId' => $rows->id, 'supervisorId' => $supervisorId]) }}" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
            <div class="flex items-start gap-3">
              <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-rose-50 to-rose-100 text-rose-600 group-hover:from-rose-100 group-hover:to-rose-200">
                <i class="ph ph-chat-circle-dots"></i>
              </div>
              <div class="flex-1">
                <div class="font-medium">Phản biện của sinh viên</div>
                <div class="text-xs text-slate-500 mt-0.5">Xem hội đồng, GV phản biện, thứ tự PB và thời gian.</div>
                <div class="mt-3">
                  <span class="inline-flex items-center gap-1.5 text-rose-700 text-sm group-hover:gap-2 transition-all">
                    Mở <i class="ph ph-arrow-right"></i>
                  </span>
                </div>
              </div>
            </div>
          </a>
          <a href="{{ route('web.teacher.review_council', ['supervisorId' => $supervisorId, 'termId' => $rows->id]) }}" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
            <div class="flex items-start gap-3">
              <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-teal-50 to-teal-100 text-teal-600 group-hover:from-teal-100 group-hover:to-teal-200">
                <i class="ph ph-checks"></i>
              </div>
              <div class="flex-1">
                <div class="font-medium">Chấm phản biện sinh viên</div>
                <div class="text-xs text-slate-500 mt-0.5">Danh sách phản biện được phân công cho bạn.</div>
                <div class="mt-3">
                  <span class="inline-flex items-center gap-1.5 text-teal-700 text-sm group-hover:gap-2 transition-all">
                    Mở <i class="ph ph-arrow-right"></i>
                  </span>
                </div>
              </div>
            </div>
          </a>
        </div>
        <!-- Giữ nguyên bảng -->
        <div class="bg-white border rounded-xl p-4">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-3">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchStage6" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tên/MSSV/hội đồng" />
            </div>
          </div>
          <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-md">
            <table id="tableStage6" class="w-full text-sm">
              <!-- Header -->
              <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                <tr class="text-slate-700">
                  <th class="px-4 py-3 font-semibold text-left">Sinh viên</th>
                  <th class="px-4 py-3 font-semibold text-center">MSSV</th>
                  <th class="px-4 py-3 font-semibold text-center">Hội đồng</th>
                  <th class="px-4 py-3 font-semibold text-left">GV phản biện</th>
                  <th class="px-4 py-3 font-semibold text-center">Chức vụ</th>
                  <th class="px-4 py-3 font-semibold text-left">Thời gian</th>
                  <th class="px-4 py-3 font-semibold text-center">Hành động</th>
                </tr>
              </thead>

              <!-- Body -->
<tbody class="divide-y divide-slate-100">
  @foreach ($assignments as $assignment)
    @php
      $student = $assignment->student;
      $fullname = $student->user->fullname;
      $student_code = $student->student_code;
      $studentId = $student->id;
      $topic = $assignment->project->title ?? 'Chưa có đề tài';
      $councilId = $assignment->council_project?->council_id;
      $committee = $assignment->council_project?->council->name ?? 'Chưa có hội đồng';
      $reviewer = $assignment->council_project?->council_member?->supervisor->teacher->user->fullname ?? 'Chưa có giảng viên';
      $role     = $assignment->council_project?->council_member?->role ?? 'NA';
      $listRole = [
        '5' => 'Chủ tịch',
        '4' => 'Thư ký',
        '3' => 'Ủy viên 1',
        '2' => 'Ủy viên 2',
        '1' => 'Ủy viên 3',
      ];
      $role = $listRole[$role] ?? 'NA';
      $order = $loop->index + 1;
      $time = $assignment->council_project && $assignment->council_project->date
        ? Carbon::parse($assignment->council_project->date)->format('H:i d/m/Y')
        : 'Chưa có lịch';

      // Icon cho hội đồng
      $icon = $committee === 'Chưa có hội đồng' ? 'ph-question' : 'ph-users-three';
      $color = $committee === 'Chưa có hội đồng' ? 'text-slate-400' : 'text-indigo-500';
    @endphp

    <tr class="hover:bg-slate-50 transition-colors">
      <!-- Sinh viên -->
      <td class="px-4 py-3">
        <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
           class="text-blue-600 hover:underline font-medium">
          {{ $fullname }}
        </a>
      </td>

      <!-- MSSV -->
      <td class="px-4 py-3 text-center font-mono text-slate-700">{{ $student_code }}</td>

      <!-- Hội đồng -->
      <td class="px-4 py-3 text-center">
        <div class="inline-flex items-center justify-center gap-1.5 text-slate-700">
          <i class="ph {{ $icon }} {{ $color }} text-lg"></i>
          <span>{{ $committee }}</span>
        </div>
      </td>

      <!-- GV phản biện -->
      <td class="px-4 py-3 text-slate-700">{{ $reviewer }}</td>

      <!-- Chức vụ -->
      <td class="px-4 py-3 text-center">
        <span class="inline-block whitespace-nowrap px-2 py-1 text-xs rounded-full bg-indigo-50 text-indigo-700 font-medium">
          {{ $role }}
        </span>
      </td>

      <!-- Thời gian -->
      <td class="px-4 py-3 text-slate-600">{{ $time }}</td>

      <!-- Hành động -->
      <td class="px-4 py-3 text-center">
        <div class="flex justify-center gap-2">
          <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
             class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
            <i class="ph ph-user"></i> SV
          </a>

          @if($councilId !== null)
            <a href="{{ route('web.teacher.committee_detail', ['councilId' => $councilId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-indigo-600 hover:bg-indigo-50 transition">
              <i class="ph ph-users-three"></i> Hội đồng
            </a>
          @else
            <span class="text-xs text-slate-400 italic">Chưa có hội đồng</span>
          @endif
        </div>
      </td>
    </tr>
  @endforeach
</tbody>

            </table>
          </div>
        </div>`;
          break;
        case 7:
          contentBox.innerHTML = `
        <h3 class="text-lg font-semibold mb-3">Giai đoạn 07: Kết quả phản biện & thứ tự bảo vệ</h3>
        <div class="mt-2 mb-3 p-3 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border border-emerald-100 shadow-sm inline-block">
          <p class="text-base font-semibold text-emerald-700 flex items-center gap-2">
            <span class="text-lg">📅</span>
            Thời gian:
            <span class="ml-1 font-medium text-green-700">
              {{ Carbon::parse($stage[6]->start_date)->format('d/m/Y') }}
              —
              {{ Carbon::parse($stage[6]->end_date)->format('d/m/Y') }}
            </span>
          </p>
        </div>
        <div class="bg-white border rounded-xl p-4">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-3">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchStage7" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tên/MSSV/hội đồng" />
            </div>
            <div class="flex items-center gap-2 text-xs">
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Đạt</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700"><span class="h-2 w-2 rounded-full bg-amber-400"></span> Cần bổ sung</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-50 text-rose-700"><span class="h-2 w-2 rounded-full bg-rose-500"></span> Không đạt</span>
            </div>
          </div>
          <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-md">
            <table id="tableStage7" class="w-full text-sm">
              <!-- Header -->
              <thead class="bg-slate-50 border-b border-slate-200">
                <tr class="text-slate-700">
                  <th class="py-3 px-4 font-semibold text-left">Sinh viên</th>
                  <th class="py-3 px-4 font-semibold text-center">MSSV</th>
                  <th class="py-3 px-4 font-semibold text-center">Hội đồng</th>
                  <th class="py-3 px-4 font-semibold text-center">Kết quả phản biện</th>
                  <th class="py-3 px-4 font-semibold text-left">Thời gian bảo vệ</th>
                  <th class="py-3 px-4 font-semibold text-center">Hành động</th>
                </tr>
              </thead>

              <!-- Body -->
              <tbody class="divide-y divide-slate-100">
                @foreach ($assignments as $assignment)
                  @php
                    $student = $assignment->student;
                    $fullname = $student->user->fullname;
                    $student_code = $student->student_code;
                    $studentId = $student->id;
                    $topic = $assignment->project->title ?? 'Chưa có đề tài';
                    $councilId = $assignment->council_project?->council_id;
                    $committee = $assignment->council_project?->council?->name ?? 'Chưa có hội đồng';
                    $score = $assignment->council_project?->review_score ?? null;
                    if ($score !== null) {
                      if ($score >= 5.5) {
                        $result = 'Đạt';
                        $resultClass = 'bg-emerald-100 text-emerald-700';
                      } else {
                        $result = 'Không đạt';
                        $resultClass = 'bg-rose-100 text-rose-700';
                      }
                    } else {
                      $result = 'Chưa có';
                      $resultClass = 'bg-slate-100 text-slate-600';
                    }
                    $order = $loop->index + 1;
                    $time = $assignment->council_project?->council?->date ?? 'Chưa có lịch';
                  @endphp

                  <tr class="hover:bg-slate-50 transition-colors">
                    <!-- Sinh viên -->
                    <td class="px-4 py-3">
                      <i class="ph ph-user-circle text-slate-400 mr-1"></i>
                      <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
                        class="text-blue-600 hover:underline font-medium">
                        {{ $fullname }}
                      </a>
                    </td>

                    <!-- MSSV -->
                    <td class="px-4 py-3 text-center font-mono text-slate-700">
                      <i class="ph ph-hash text-slate-400 mr-1"></i>{{ $student_code }}
                    </td>

                    <!-- Hội đồng -->
                    <td class="px-4 py-3 text-center">
                      <i class="ph ph-users text-slate-400 mr-1"></i>{{ $committee }}
                    </td>

                    <!-- Kết quả phản biện -->
                    <td class="px-4 py-3 text-center">
                      <span class="px-2 py-1 text-xs font-medium rounded-full {{ $resultClass }}">
                        <i class="ph ph-check-circle text-xs mr-1"></i>{{ $result }}
                      </span>
                    </td>

                    <!-- Thời gian bảo vệ -->
                    <td class="px-4 py-3 text-slate-600">
                      <i class="ph ph-clock text-slate-400 mr-1"></i>{{ $time }}
                    </td>

                    <!-- Hành động -->
                    <td class="px-4 py-3 text-center">
                      <div class="flex justify-center gap-2">
                        <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
                          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                          <i class="ph ph-user"></i> SV
                        </a>
                        @if($councilId !== null)
                          <a href="{{ route('web.teacher.committee_detail', ['councilId'=>$councilId, 'termId'=>$rows->id, 'supervisorId' => $supervisorId]) }}"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-indigo-600 hover:bg-indigo-50 transition">
                            <i class="ph ph-users-three"></i> Hội đồng
                          </a>
                        @else
                          <span class="text-xs text-slate-400 italic">Chưa có hội đồng</span>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>`;
          break;
        case 8:
          contentBox.innerHTML = `
        <h3 class="text-lg font-semibold mb-3">Giai đoạn 08: Bảo vệ đồ án</h3>
        <div class="mt-2 mb-3 p-3 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border border-emerald-100 shadow-sm inline-block">
          <p class="text-base font-semibold text-emerald-700 flex items-center gap-2">
            <span class="text-lg">📅</span>
            Thời gian:
            <span class="ml-1 font-medium text-green-700">
              {{ Carbon::parse($stage[7]->start_date)->format('d/m/Y') }}
              —
              {{ Carbon::parse($stage[7]->end_date)->format('d/m/Y') }}
            </span>
          </p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <a href="{{ route('web.teacher.student_council', ['termId' => $rows->id, 'supervisorId' => $supervisorId]) }}" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
            <div class="flex items-start gap-3">
              <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-emerald-50 to-emerald-100 text-emerald-600 group-hover:from-emerald-100 group-hover:to-emerald-200">
                <i class="ph ph-graduation-cap"></i>
              </div>
              <div class="flex-1">
                <div class="font-medium">Quản lý sinh viên hướng dẫn</div>
                <div class="text-xs text-slate-500 mt-0.5">Theo dõi kết quả bảo vệ của SV đang hướng dẫn.</div>
                <div class="mt-3">
                  <span class="inline-flex items-center gap-1.5 text-emerald-700 text-sm group-hover:gap-2 transition-all">
                    Mở <i class="ph ph-arrow-right"></i>
                  </span>
                </div>
              </div>
            </div>
          </a>
          <a href="{{ route('web.teacher.my_evaluations', ['supervisorId' => $supervisorId, 'termId' => $rows->id] ) }}" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
            <div class="flex items-start gap-3">
              <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-sky-50 to-sky-100 text-sky-600 group-hover:from-sky-100 group-hover:to-sky-200">
                <i class="ph ph-users"></i>
              </div>
              <div class="flex-1">
                <div class="font-medium">Chấm bảo vệ đồ án</div>
                <div class="text-xs text-slate-500 mt-0.5">Vào hội đồng để xem SV và chấm bảo vệ.</div>
                <div class="mt-3">
                  <span class="inline-flex items-center gap-1.5 text-sky-700 text-sm group-hover:gap-2 transition-all">
                    Mở <i class="ph ph-arrow-right"></i>
                  </span>
                </div>
              </div>
            </div>
          </a>

          <a href="{{ route('web.teacher.manage_report_file_council', ['supervisorId' => $supervisorId, 'termId' => $rows->id]  ) }}"
            class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-indigo-300 transition">
            <div class="flex items-start gap-3">
              <!-- Icon -->
              <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-indigo-50 to-indigo-100 text-indigo-600 group-hover:from-indigo-100 group-hover:to-indigo-200 transition">
                <i class="ph ph-file-text"></i>
              </div>

              <!-- Content -->
              <div class="flex-1">
                <div class="font-medium text-slate-900">Quản lý báo cáo sau bảo vệ</div>
                <div class="text-xs text-slate-500 mt-0.5">
                  Theo dõi, đánh giá và xác nhận các báo cáo của sinh viên hướng dẫn sau khi bảo vệ.
                </div>

                <div class="mt-3">
                  <span class="inline-flex items-center gap-1.5 text-indigo-700 text-sm group-hover:gap-2 transition-all">
                    Vào quản lý <i class="ph ph-arrow-right"></i>
                  </span>
                </div>
              </div>
            </div>
          </a>

        </div>
        <!-- Giữ nguyên bảng -->
        <div id="stage8-managed" class="bg-white border rounded-xl p-4">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-3">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchStage8" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tên/MSSV/hội đồng" />
            </div>
            <div class="flex items-center gap-2 text-xs">
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Đạt</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700"><span class="h-2 w-2 rounded-full bg-amber-400"></span> Cần bổ sung</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-50 text-rose-700"><span class="h-2 w-2 rounded-full bg-rose-500"></span> Không đạt</span>
            </div>
          </div>
          <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-md">
            <table id="tableStage8" class="w-full text-sm">
              <!-- Header -->
              <thead class="bg-slate-50 border-b border-slate-200">
                <tr class="text-slate-700">
                  <th class="py-3 px-4 font-semibold text-center">Sinh viên</th>
                  <th class="py-3 px-4 font-semibold text-center">MSSV</th>
                  <th class="py-3 px-4 font-semibold text-center">Hội đồng</th>
                  <th class="py-3 px-4 font-semibold text-center">Điểm bảo vệ</th>
                  <th class="py-3 px-4 font-semibold text-center">Kết quả</th>
                  <th class="py-3 px-4 font-semibold text-center">Nhận xét</th>
                  <th class="py-3 px-4 font-semibold text-center">Hành động</th>
                </tr>
              </thead>

              <!-- Body -->
              <tbody class="divide-y divide-slate-100">
                @foreach ($assignments as $assignment)
                  @php
                    $student = $assignment->student;
                    $fullname = $student->user->fullname;
                    $student_code = $student->student_code;
                    $studentId = $student->id;
                    $councilId = $assignment->council_project?->council_id;
                    $committee = $assignment->council_project?->council?->name ?? 'Chưa có hội đồng';
                    $list_score_defences = $assignment->council_project?->council_project_defences ?? [];
                    if(count($list_score_defences) > 0) {
                      $totalScore = 0;
                      $countScores = 0;
                      $comment = "";
                      foreach ($list_score_defences as $score_defence) {
                        if ($score_defence->score !== null) {
                          $totalScore += $score_defence->score;
                          $countScores++;
                          $comment .= $score_defence->comments . "." . "\n";
                        }
                      }
                      $score = $countScores > 0 ? round($totalScore / $countScores, 2) : null;
                      $resultClass = "bg-emerald-100 text-emerald-700";
                      $result = "Đạt"; // Hoặc tính theo điểm
                    } else {
                      $score     = "Chưa có";
                      $result    = "Chưa có";
                      $comment   = "Chưa có";
                      $resultClass = "bg-slate-100 text-slate-600";
                    }
                  @endphp

                  <tr class="hover:bg-slate-50 transition">
                    <!-- Sinh viên -->
                    <td class="px-4 py-3 max-w-xs break-words text-center">
                      <i class="ph ph-user-circle text-slate-400 mr-1"></i>
                      <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
                        class="text-blue-600 hover:underline font-medium break-words">
                        {{ $fullname }}
                      </a>
                    </td>

                    <!-- MSSV -->
                    <td class="px-4 py-3 text-center font-mono text-slate-700 truncate max-w-[100px]">
                      <i class="ph ph-hash text-slate-400 mr-1"></i>{{ $student_code }}
                    </td>

                    <!-- Hội đồng -->
                    <td class="px-4 py-3 text-center max-w-[150px] break-words">
                      <i class="ph ph-users text-slate-400 mr-1"></i>{{ $committee }}
                    </td>

                    <!-- Điểm bảo vệ -->
                    <td class="px-4 py-3 text-center font-semibold text-slate-800 min-w-[60px]">{{ $score }}</td>

                    <!-- Kết quả -->
                    <td class="px-4 py-3 text-center min-w-[90px]">
                      <span class="inline-block px-2 py-1 text-xs font-medium rounded-full {{ $resultClass }} whitespace-nowrap">
                        <i class="ph ph-check-circle text-xs mr-1"></i>{{ $result }}
                      </span>
                    </td>

                    <!-- Nhận xét -->
                    <td class="px-4 py-3 text-slate-600 max-w-xs break-words text-center">
                      <i class="ph ph-chat-text text-slate-400 mr-1"></i>
                      {!! nl2br(e($comment)) !!}
                    </td>

                    <!-- Hành động -->
                    <td class="px-4 py-3 text-center min-w-[120px]">
                      <div class="flex justify-center gap-2 flex-wrap">
                        <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
                          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                          <i class="ph ph-user"></i> SV
                        </a>
                        @if($councilId !== null)
                          <a href="{{ route('web.teacher.committee_detail', ['councilId'=>$councilId, 'termId'=>$rows->id, 'supervisorId' => $supervisorId]) }}"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-indigo-600 hover:bg-indigo-50 transition">
                            <i class="ph ph-users-three"></i> Hội đồng
                          </a>
                        @else
                          <span class="text-xs text-slate-400 italic">Chưa có hội đồng</span>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>

            </table>
          </div>
        </div>`;
          break;
        default:
          contentBox.innerHTML = "<p>Chưa có thông tin cho giai đoạn này.</p>";
      }
      // Highlight active stage
      document.querySelectorAll('.timeline-stage').forEach(el => el.classList.remove('active'));
      const activeStage = document.querySelector(`.timeline-stage[data-stage="${stageNum}"]`);
      if (activeStage) activeStage.classList.add('active');

     // Gắn search cho bảng của stage (nếu có)
     attachSearchByIds(`searchStage${stageNum}`, `tableStage${stageNum}`);
    }

    const html = document.documentElement, sidebar = document.getElementById('sidebar');
    function setCollapsed(c) {
      const mainArea = document.querySelector('.flex-1');
      if (c) {
        html.classList.add('sidebar-collapsed');
      } else {
        html.classList.remove('sidebar-collapsed');
      }
    }

    document.getElementById('toggleSidebar')?.addEventListener('click', () => { const c = !html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar', '' + (c ? 1 : 0)); });
    document.getElementById('openSidebar')?.addEventListener('click', () => sidebar.classList.toggle('-translate-x-full'));
    if (localStorage.getItem('lecturer_sidebar') === '1') setCollapsed(true);
    sidebar.classList.add('md:translate-x-0', '-translate-x-full', 'md:static');

    // Show Stage 1 by default when the page loads
    window.addEventListener('DOMContentLoaded', function () {
      try { showStageDetails(1); } catch (e) { console.error('Init stage load failed:', e); }
     // Mở cứng submenu "Học phần tốt nghiệp" nếu đang ở trang chi tiết
     const submenu = document.getElementById('thesisSubmenu');
     const toggleBtn = document.getElementById('toggleThesisMenu');
     const caret = document.getElementById('thesisCaret');
     if (submenu && toggleBtn && caret) {
       submenu.classList.remove('hidden');
       toggleBtn.setAttribute('aria-expanded','true');
       caret.classList.add('rotate-180');
       // Tô đậm nhóm
       toggleBtn.classList.add('bg-slate-100','font-semibold');
     }
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
        if (a.hasAttribute('aria-current') || a.dataset.skipActive != null) return;
         const href = a.getAttribute('href') || '';
         const active = href.endsWith(current);
         a.classList.toggle('bg-slate-100', active);
         a.classList.toggle('font-semibold', active);
       });
     })();

  // Helper: chuẩn hóa chuỗi để search (bỏ dấu + lowercase)
  function textNorm(s){
    return (s || '').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  }
  // Gắn sự kiện search cho input/table theo id
  function attachSearchByIds(inputId, tableId){
    const inp = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    if(!inp || !table) return;
    const rows = Array.from(table.querySelectorAll('tbody tr'));
    const doFilter = ()=>{
      const q = textNorm(inp.value.trim());
      if(!q){
        rows.forEach(tr => tr.classList.remove('hidden'));
        return;
      }
      rows.forEach(tr=>{
        const txt = textNorm(tr.innerText);
        tr.classList.toggle('hidden', !txt.includes(q));
      });
    };
    inp.addEventListener('input', doFilter);
  }
  </script>
</body>

</html>