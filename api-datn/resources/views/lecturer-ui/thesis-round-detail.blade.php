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
    $teacherId = $user->teacher->id ?? 0;
    $avatarUrl = $user->avatar_url
      ?? $user->profile_photo_url
      ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    $assignments = $rows->assignments;
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
          <div class="font-semibold">Lecturer</div>
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
            href="{{ route('web.teacher.students', ['supervisorId' => $user->teacher->supervisor->id]) }}"
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
          <a href="{{ route('web.teacher.thesis_rounds', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ $isThesisRoundsActive ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
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
          <button class="md:hidden p-2 rounded-lg hover:bg-slate-100" id="openSidebar"><i
              class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Chi tiết đợt đồ án</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đồ án tốt nghiệp</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Chi tiết</span>
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
          <!-- Round Info -->
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between">
              <div>
                <div class="text-sm text-slate-500">Mã đợt: <span
                    class="font-medium text-slate-700">{{ $rows->id }}</span></div>
                <h2 class="font-semibold text-lg mt-1">{{ "Đợt ". $rows->stage . " năm học " . date('Y', strtotime($rows->start_date)) . " - " . date('Y', strtotime($rows->end_date))}}</h2>
                <div class="text-sm text-slate-600">{{ date('d/m/Y', strtotime($rows->start_date)) }} - {{ date('d/m/Y', strtotime($rows->end_date)) }}</div>
              </div>
              <div class="text-right">
                <div class="text-sm text-slate-500">Vai trò của bạn</div>
                <div class="font-medium text-blue-600">Giảng viên hướng dẫn • Thành viên hội đồng</div>
                <div class="text-xs text-slate-500 mt-1">{{$rows->supervisors->count()}} sinh viên hướng dẫn • 4 hội đồng tham gia</div>
              </div>
            </div>
          </section>
          <!-- Timeline -->
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-6">
              <h3 class="font-semibold">Tiến độ giai đoạn hướng dẫn</h3>
              <div class="flex items-center gap-2 text-sm">
                <span class="font-medium" id="progressText">25%</span>
                <div class="w-40 h-2 rounded-full bg-slate-100 overflow-hidden">
                  <div class="h-full bg-blue-600" id="progressBar" style="width:25%"></div>
                </div>
              </div>
            </div>
            <!-- Horizontal Timeline -->
            <div class="relative">
              <!-- Progress Line -->
              <div class="absolute top-6 left-8 right-8 h-0.5 bg-slate-200">
                <div class="h-full bg-blue-600" style="width: 25%"></div>
              </div>
              <!-- Timeline Items -->
              <div class="grid grid-cols-8 gap-4 relative">
                <!-- Stage 1 -->
                <div class="timeline-stage cursor-pointer" data-stage="1" onclick="showStageDetails(1)">
                  <div
                    class="w-12 h-12 mx-auto bg-emerald-600 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    1</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Tiếp nhận</div>
                    <div class="text-xs text-emerald-600 mt-1">Hoàn thành</div>
                  </div>
                </div>
                <!-- Stage 2 -->
                <div class="timeline-stage cursor-pointer" data-stage="2" onclick="showStageDetails(2)">
                  <div
                    class="w-12 h-12 mx-auto bg-blue-600 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    2</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Đề cương</div>
                    <div class="text-xs text-blue-600 mt-1">Đang diễn ra</div>
                  </div>
                </div>
                <!-- Stage 3 -->
                <div class="timeline-stage cursor-pointer" data-stage="3" onclick="showStageDetails(3)">
                  <div
                    class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    3</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Nhật ký</div>
                    <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                  </div>
                </div>
                <!-- Stage 4 -->
                <div class="timeline-stage cursor-pointer" data-stage="4" onclick="showStageDetails(4)">
                  <div
                    class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    4</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Chấm báo cáo</div>
                    <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                  </div>
                </div>
                <!-- Stage 5 -->
                <div class="timeline-stage cursor-pointer" data-stage="5" onclick="showStageDetails(5)">
                  <div
                    class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    5</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Hội đồng</div>
                    <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                  </div>
                </div>
                <!-- Stage 6 -->
                <div class="timeline-stage cursor-pointer" data-stage="6" onclick="showStageDetails(6)">
                  <div
                    class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    6</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Phản biện</div>
                    <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                  </div>
                </div>
                <!-- Stage 7 -->
                <div class="timeline-stage cursor-pointer" data-stage="7" onclick="showStageDetails(7)">
                  <div
                    class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    7</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Công bố</div>
                    <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                  </div>
                </div>
                <!-- Stage 8 -->
                <div class="timeline-stage cursor-pointer" data-stage="8" onclick="showStageDetails(8)">
                  <div
                    class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    8</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Bảo vệ</div>
                    <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
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
        <h3 class="text-lg font-semibold mb-3">Giai đoạn 01</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <a href="{{ route('web.teacher.requests_management', ['supervisorId' => $supervisorId, 'termId' => $rows->id]) }}" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
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
          <a href="{{ route('web.teacher.proposed_topic', ['supervisorId' => $supervisorId, 'termId' => $rows->id]) }}" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
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
            <a href="{{ route('web.teacher.student_supervisor_term', ['supervisorId' => $supervisorId, 'termId' => $rows->id]) }}" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
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
        </div>
      `;
          break;
        case 2:
          contentBox.innerHTML = `
        <h3 class="text-lg font-semibold mb-3">Giai đoạn 02: Đề cương sinh viên</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <a href="{{ route('web.teacher.supervised_outline_reports', ['supervisorId' => $supervisorId, 'termId' => $rows->id]) }}" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
            <div class="flex items-start gap-3">
              <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-indigo-50 to-indigo-100 text-indigo-600 group-hover:from-indigo-100 group-hover:to-indigo-200">
                <i class="ph ph-files"></i>
              </div>
              <div class="flex-1">
                <div class="font-medium">Danh sách báo cáo đề cương</div>
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

          $latestReport = $assignment->project?->reportFiles()->latest('created_at')->first();
          $statusRaw = $latestReport?->status ?? 'none';

          $listStatus = [
            'none' => ['label' => 'Chưa nộp', 'class' => 'bg-slate-100 text-slate-600', 'icon' => 'ph-clock'],
            'pending' => ['label' => 'Đã nộp', 'class' => 'bg-amber-100 text-amber-700', 'icon' => 'ph-hourglass'],
            'submitted' => ['label' => 'Đã nộp', 'class' => 'bg-amber-100 text-amber-700', 'icon' => 'ph-hourglass'],
            'approved' => ['label' => 'Đã duyệt', 'class' => 'bg-emerald-100 text-emerald-700', 'icon' => 'ph-check-circle'],
            'rejected' => ['label' => 'Bị từ chối', 'class' => 'bg-rose-100 text-rose-700', 'icon' => 'ph-x-circle'],
          ];
          $statusConfig = $listStatus[$statusRaw] ?? $listStatus['none'];

          $updateLast = $latestReport?->created_at?->format('H:i:s d/m/Y') ?? 'Chưa nộp báo cáo';
        @endphp

        <tr class="hover:bg-slate-50 transition-colors">
          <!-- Sinh viên -->
          <td class="py-3 px-4">
            <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id]) }}"
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
            <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id]) }}"
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

          $listProgressLog = $assignment->project->progressLogs ?? [];
          $latestLog = collect($listProgressLog)->sortByDesc('created_at')->first() ?? null;

          $lastestTitle = $latestLog->title ?? 'Chưa nộp';
          $lastestStatusRaw = $latestLog->student_status ?? 'none';

          $listStatus = [
            'none' => ['label' => 'Chưa nộp', 'class' => 'bg-slate-100 text-slate-600', 'icon' => 'ph-clock'],
            'chua_bat_dau' => ['label' => 'Chưa bắt đầu', 'class' => 'bg-slate-100 text-slate-600', 'icon' => 'ph-clock'],
            'dang_thuc_hien' => ['label' => 'Đang thực hiện', 'class' => 'bg-amber-100 text-amber-700', 'icon' => 'ph-hourglass'],
            'da_hoan_thanh' => ['label' => 'Đã hoàn thành', 'class' => 'bg-emerald-100 text-emerald-700', 'icon' => 'ph-check-circle'],
            'approved' => ['label' => 'Đã chấm', 'class' => 'bg-blue-100 text-blue-700', 'icon' => 'ph-seal-check'],
            'needs_revision' => ['label' => 'Cần bổ sung', 'class' => 'bg-rose-100 text-rose-700', 'icon' => 'ph-warning'],
          ];

          $lastestTime = $latestLog?->created_at?->format('H:i:s d/m/Y') ?? 'Chưa có';
          $statusConfig = $listStatus[$lastestStatusRaw] ?? $listStatus['none'];
        @endphp

        <tr class="hover:bg-slate-50 transition-colors">
          <!-- Sinh viên -->
          <td class="py-3 px-4">
            <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id]) }}"
               class="text-blue-600 hover:underline font-medium">
              {{ $fullname }}
            </a>
          </td>

          <!-- MSSV -->
          <td class="py-3 px-4 text-center font-mono text-slate-700">{{ $student_code }}</td>

          <!-- Tuần gần nhất -->
          <td class="py-3 px-4 text-slate-700">{{ $lastestTitle }}</td>

          <!-- Trạng thái -->
          <td class="py-3 px-4 text-center">
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $statusConfig['class'] }}">
              <i class="ph {{ $statusConfig['icon'] }} text-sm"></i>
              {{ $statusConfig['label'] }}
            </span>
          </td>

          <!-- Thời gian -->
          <td class="py-3 px-4 text-slate-600">{{ $lastestTime }}</td>

          <!-- Hành động -->
          <td class="py-3 px-4 text-center">
            <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id]) }}"
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
                    $latestReport = $assignment->project?->reportFiles()->latest('created_at')->first();
                    $statusRaw = $latestReport?->status ?? 'none';

                    $listStatus = [
                      'none' => ['label' => 'Chưa nộp', 'class' => 'bg-slate-100 text-slate-600', 'icon' => 'ph-clock'],
                      'submitted' => ['label' => 'Đã nộp', 'class' => 'bg-amber-100 text-amber-700', 'icon' => 'ph-upload-simple'],
                      'approved' => ['label' => 'Đã duyệt', 'class' => 'bg-emerald-100 text-emerald-700', 'icon' => 'ph-check-circle'],
                      'rejected' => ['label' => 'Bị từ chối', 'class' => 'bg-rose-100 text-rose-700', 'icon' => 'ph-x-circle'],
                    ];
                    $statusConfig = $listStatus[$statusRaw] ?? $listStatus['none'];

                    $lastSubmit = $latestReport?->created_at?->format('d/m/Y') ?? 'Chưa có';
                  @endphp

                  <tr class="hover:bg-slate-50 transition-colors">
                    <!-- Sinh viên -->
                    <td class="py-3 px-4">
                      <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id]) }}"
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
                        <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id]) }}"
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
          <a href="{{ route('web.teacher.my_committees' )}}" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
            <div class="flex items-start gap-3">
              <div class="h-10 w-10 rounded-lg grid place-items-center bg-gradient-to-br from-sky-50 to-sky-100 text-sky-600 group-hover:from-sky-100 group-hover:to-sky-200">
                <i class="ph ph-users-three"></i>
              </div>
              <div class="flex-1">
                <div class="font-medium">Danh sách hội đồng chấm thi</div>
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
                  <th class="py-3 px-4 font-semibold text-center">Hội đồng</th>
                  <th class="py-3 px-4 font-semibold">Lịch bảo vệ</th>
                  <th class="py-3 px-4 font-semibold text-center">Phòng</th>
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

                    $committee = "CNTT-01"; // demo
                    $schedule  = "20/08/2025 • 08:00";
                    $room      = "P.A203";
                  @endphp

                  <tr class="hover:bg-slate-50 transition-colors">
                    <!-- Họ tên -->
                    <td class="py-3 px-4">
                      <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id]) }}"
                        class="text-blue-600 hover:underline font-medium">
                        {{ $fullname }}
                      </a>
                    </td>

                    <!-- MSSV -->
                    <td class="py-3 px-4 text-center font-mono text-slate-700">{{ $student_code }}</td>

                    <!-- Đề tài -->
                    <td class="py-3 px-4 text-slate-700">{{ $topic }}</td>

                    <!-- Hội đồng -->
                    <td class="py-3 px-4 text-center">{{ $committee }}</td>

                    <!-- Ngày giờ -->
                    <td class="py-3 px-4 text-slate-600">{{ $schedule }}</td>

                    <!-- Phòng -->
                    <td class="py-3 px-4 text-center">{{ $room }}</td>

                    <!-- Hành động -->
                    <td class="py-3 px-4 text-center">
                      <div class="flex justify-center gap-2">
                        <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id]) }}"
                          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-100 transition">
                          <i class="ph ph-user"></i> SV
                        </a>
                        <a href="committee-detail.html?id={{ $committee }}"
                          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-indigo-600 hover:bg-indigo-50 transition">
                          <i class="ph ph-users-three"></i> Hội đồng
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
        case 6:
          contentBox.innerHTML = `
        <h3 class="text-lg font-semibold mb-3">Giai đoạn 06: Phản biện</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <a href="student-reviews.html" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
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
          <a href="{{ route('web.teacher.review_assignments') }}" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
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
                  <th class="px-4 py-3 font-semibold text-center">STT PB</th>
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

                    $committee = "CNTT-01"; // ví dụ
                    $reviewer = "TS. Nguyễn Thị E"; // ví dụ
                    $role     = "Phản biện";
                    $order    = "01";
                    $time     = "20/08/2025 • 08:00";
                  @endphp

                  <tr class="hover:bg-slate-50 transition-colors">
                    <!-- Sinh viên -->
                    <td class="px-4 py-3">
                      <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id]) }}"
                        class="text-blue-600 hover:underline font-medium">
                        {{ $fullname }}
                      </a>
                    </td>

                    <!-- MSSV -->
                    <td class="px-4 py-3 text-center font-mono text-slate-700">{{ $student_code }}</td>

                    <!-- Hội đồng -->
                    <td class="px-4 py-3 text-center">{{ $committee }}</td>

                    <!-- GV phản biện -->
                    <td class="px-4 py-3 text-slate-700">{{ $reviewer }}</td>

                    <!-- Chức vụ -->
                    <td class="px-4 py-3 text-center">
                      <span class="px-2 py-1 text-xs rounded-full bg-indigo-50 text-indigo-700 font-medium">{{ $role }}</span>
                    </td>

                    <!-- STT PB -->
                    <td class="px-4 py-3 text-center">
                      <span class="px-2 py-1 text-xs rounded-md bg-slate-100 text-slate-700">{{ $order }}</span>
                    </td>

                    <!-- Thời gian -->
                    <td class="px-4 py-3 text-slate-600">{{ $time }}</td>

                    <!-- Hành động -->
                    <td class="px-4 py-3 text-center">
                      <div class="flex justify-center gap-2">
                        <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id]) }}"
                          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                          <i class="ph ph-user"></i> SV
                        </a>
                        <a href="committee-detail.html?id={{ $committee }}"
                          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-indigo-600 hover:bg-indigo-50 transition">
                          <i class="ph ph-users-three"></i> Hội đồng
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
        case 7:
          contentBox.innerHTML = `
        <h3 class="text-lg font-semibold mb-3">Giai đoạn 07: Kết quả phản biện & thứ tự bảo vệ</h3>
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
                  <th class="py-3 px-4 font-semibold text-center">STT bảo vệ</th>
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

                    $committee = "CNTT-01"; // ví dụ
                    $result    = "Đạt";     // ví dụ
                    $resultClass = "bg-emerald-100 text-emerald-700"; // ví dụ
                    $order     = "01";
                    $time      = "20/08/2025 • 08:00";
                  @endphp

                  <tr class="hover:bg-slate-50 transition-colors">
                    <!-- Sinh viên -->
                    <td class="px-4 py-3">
                      <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id]) }}"
                        class="text-blue-600 hover:underline font-medium">
                        {{ $fullname }}
                      </a>
                    </td>

                    <!-- MSSV -->
                    <td class="px-4 py-3 text-center font-mono text-slate-700">{{ $student_code }}</td>

                    <!-- Hội đồng -->
                    <td class="px-4 py-3 text-center">{{ $committee }}</td>

                    <!-- Kết quả phản biện -->
                    <td class="px-4 py-3 text-center">
                      <span class="px-2 py-1 text-xs font-medium rounded-full {{ $resultClass }}">
                        {{ $result }}
                      </span>
                    </td>

                    <!-- STT bảo vệ -->
                    <td class="px-4 py-3 text-center">
                      <span class="px-2 py-1 text-xs rounded-md bg-slate-100 text-slate-700">{{ $order }}</span>
                    </td>

                    <!-- Thời gian -->
                    <td class="px-4 py-3 text-slate-600">{{ $time }}</td>

                    <!-- Hành động -->
                    <td class="px-4 py-3 text-center">
                      <div class="flex justify-center gap-2">
                        <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id]) }}"
                          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                          <i class="ph ph-user"></i> SV
                        </a>
                        <a href="committee-detail.html?id={{ $committee }}"
                          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-indigo-600 hover:bg-indigo-50 transition">
                          <i class="ph ph-users-three"></i> Hội đồng
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
        case 8:
          contentBox.innerHTML = `
        <h3 class="text-lg font-semibold mb-3">Giai đoạn 08: Bảo vệ đồ án</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <a href="supervised-defense-results.html" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
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
          <a href="my-committees.html" class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md hover:border-blue-300 transition">
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
                  <th class="py-3 px-4 font-semibold text-left">Sinh viên</th>
                  <th class="py-3 px-4 font-semibold text-center">MSSV</th>
                  <th class="py-3 px-4 font-semibold text-center">Hội đồng</th>
                  <th class="py-3 px-4 font-semibold text-center">Điểm bảo vệ</th>
                  <th class="py-3 px-4 font-semibold text-center">Kết quả</th>
                  <th class="py-3 px-4 font-semibold text-left">Nhận xét</th>
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

                    $committee = "CNTT-01"; 
                    $score     = 8.5; 
                    $result    = "Đạt"; 
                    $comment   = "Trình bày tốt, trả lời câu hỏi rõ ràng.";
                    $resultClass = "bg-emerald-100 text-emerald-700";
                  @endphp

                  <tr class="hover:bg-slate-50 transition">
                    <!-- Sinh viên -->
                    <td class="px-4 py-3">
                      <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id]) }}"
                        class="text-blue-600 hover:underline font-medium">
                        {{ $fullname }}
                      </a>
                    </td>

                    <!-- MSSV -->
                    <td class="px-4 py-3 text-center font-mono text-slate-700">{{ $student_code }}</td>

                    <!-- Hội đồng -->
                    <td class="px-4 py-3 text-center">{{ $committee }}</td>

                    <!-- Điểm -->
                    <td class="px-4 py-3 text-center font-semibold text-slate-800">{{ $score }}</td>

                    <!-- Kết quả -->
                    <td class="px-4 py-3 text-center">
                      <span class="px-2 py-1 text-xs font-medium rounded-full {{ $resultClass }}">
                        {{ $result }}
                      </span>
                    </td>

                    <!-- Nhận xét -->
                    <td class="px-4 py-3 text-slate-600 max-w-xs whitespace-normal">
                      {{ $comment }}
                    </td>

                    <!-- Hành động -->
                    <td class="px-4 py-3 text-center">
                      <div class="flex justify-center gap-2">
                        <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $studentId, 'termId' => $rows->id]) }}"
                          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                          <i class="ph ph-user"></i> SV
                        </a>
                        <a href="committee-detail.html?id={{ $committee }}"
                          class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-indigo-600 hover:bg-indigo-50 transition">
                          <i class="ph ph-users-three"></i> Hội đồng
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