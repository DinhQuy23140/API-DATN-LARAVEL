<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Danh sách sinh viên - Trợ lý khoa</title>
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
    $outlineSubmissions = collect($assignment->project->progressLogs ?? []); // [] nếu không có
    $weeklyLogs = collect($assignment?->project?->progressLogs->sortBy('created_at') ?? []); // [] nếu không có
    $finalReport = $assignment->project?->reportFiles()
        ->where('type_report', 'report')
        ->latest('created_at')
        ->first() ?? null;
    $finalOutline = $assignment->project?->reportFiles()
        ->where('type_report', 'outline')
        ->latest('created_at')
        ->first() ?? null;
    $teacherId = $user->teacher->id ?? null;
    $committee = $assignment->committee ?? null;
    $asId = $asCurrent->id ?? 0;
    $currentScoreReport = $asCurrent->score_report ?? '';
  @endphp
  <div class="flex min-h-screen">
    <aside id="sidebar"
      class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-buildings"></i>
        </div>
        <div class="sidebar-label">
          <div class="font-semibold">Assistant</div>
          <div class="text-xs text-slate-500">Quản trị khoa</div>
        </div>
      </div>
      <nav class="flex-1 overflow-y-auto p-3">
        <a href="{{ route('web.assistant.dashboard') }}"
          class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span
            class="sidebar-label">Bảng điều khiển</span></a>
        <a href="{{ route('web.assistant.manage_departments') }}"
          class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-buildings"></i><span
            class="sidebar-label">Bộ môn</span></a>
        <a href="{{ route('web.assistant.manage_majors') }}"
          class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i
            class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
        <a href="{{ route('web.assistant.manage_staffs') }}"
          class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i
            class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
        <a href="{{ route('web.assistant.assign_head') }}"
          class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span
            class="sidebar-label">Phân trưởng bộ môn</span></a>
        <div class="sidebar-label text-xs uppercase text-slate-400 px-3 mt-3">Học phần tốt nghiệp</div>
        <div class="graduation-item">
          <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button">
            <span class="flex items-center gap-3">
              <i class="ph ph-folder"></i>
              <span class="sidebar-label">Học phần tốt nghiệp</span>
            </span>
            <i class="ph ph-caret-down"></i>
          </div>
          <div class="submenu hidden pl-6">
            <a href="internship.html" class="block px-3 py-2 hover:bg-slate-100">Thực tập tốt nghiệp</a>
            <a href="{{ route('web.assistant.rounds') }}" class="block px-3 py-2 hover:bg-slate-100">Đồ án tốt
              nghiệp</a>
          </div>
        </div>
      </nav>
      <div class="p-3 border-t border-slate-200">
        <button id="toggleSidebar"
          class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i
            class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
      </div>
    </aside>

    <div class="flex-1 h-screen overflow-hidden flex flex-col">
      <header
        class="fixed left-0 md:left-[260px] right-0 top-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-20">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i
              class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Thông tin đồ án sinh viên</h1>
            <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp
              / Thông tin đồ án sinh viên</nav>
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

        <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 mt-16">
            <div class="max-w-6xl mx-auto">
              <div class="flex items-center justify-between mb-4">
                  <div></div>
                  <a href="{{ url()->previous() }}" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại danh sách</a>
              </div>

                
              <div class="rounded-2xl overflow-hidden shadow-lg mb-6 bg-gradient-to-r from-sky-600 via-indigo-600 to-violet-600 text-white">
                <div class="p-5 md:p-6 flex items-center gap-4">
                  <img src="{{ $studentUser->avatar_url ?? ($studentUser->profile_photo_url ?? ('https://ui-avatars.com/api/?name=' . urlencode($studentUser->fullname ?? 'Sinh viên') . '&background=ffffff&color=000')) }}" alt="avatar" class="h-20 w-20 rounded-full ring-4 ring-white object-cover shadow-md" />
                  <div class="flex-1">
                    <div class="text-sm uppercase opacity-90">Sinh viên hướng dẫn</div>
                    @php
                      $student = $assignment->student ?? null;
                      $studentUser = optional($student)->user;
                    @endphp
                    <div class="text-2xl font-bold mt-1">{{ $studentUser->fullname ?? 'Sinh viên' }}</div>
                    <div class="text-sm opacity-90 mt-1">MSSV: <span class="font-medium">{{ $student->student_code ?? $student->id ?? '-' }}</span> • Lớp: {{ $student->class_code ?? '-' }}</div>
                  </div>
                  <div class="text-right flex flex-col">
                    <div class="text-sm opacity-90">Giảng viên hướng dẫn</div>
                    @php
                        $assignmentSupervisors = $assignment->assignment_supervisors->where('status', 'accepted') ?? collect();
                    @endphp
                    @if($assignmentSupervisors->isEmpty())
                      <div class="text-lg font-bold mt-1">Chưa có giảng viên hướng dẫn</div>
                    @else
                    @foreach($assignmentSupervisors as $as)
                      @php
                          $supervisorTeacher = $as->supervisor->teacher ?? null;
                          $supervisorTeacherUser = optional($supervisorTeacher)->user;
                      @endphp
                      <div class="mt-1 inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white/20">
                        <i class="ph ph-user-circle text-white"></i>
                        <div class="font-medium">{{ $supervisorTeacherUser->fullname ?? '—' }}</div>
                      </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
              <!-- (above) Student hero rendered -->

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <section class="md:col-span-2 bg-white border rounded-2xl p-5 shadow-sm hover:shadow-md transition">
              <div class="flex items-center justify-between mb-3">
                <h2 class="font-semibold text-lg flex items-center gap-2"><i class="ph ph-document-text text-indigo-600"></i> Thông tin đề tài</h2>
                <div class="text-sm text-slate-500">Thông tin chi tiết dự án</div>
              </div>
              <div class="text-sm text-slate-700 space-y-1">
                <div><span class="text-slate-500">Đề tài: </span><span class="font-medium">{{ $project->name ?? ($project->title ?? 'Chưa có đề tài') }}</span></div>
              </div>
              <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="bg-gradient-to-br from-indigo-50 to-sky-50 p-4 rounded-lg border border-slate-100">
                  <div class="text-sm text-indigo-600 flex items-center gap-2"><i class="ph ph-calendar-check"></i> Ngày bắt đầu</div>
                  <div class="text-2xl font-bold text-indigo-700 mt-1">{{ optional($assignment?->created_at)->format('d/m/Y') ?? '-' }}</div>
                </div>
                <div class="bg-gradient-to-br from-amber-50 to-emerald-50 p-4 rounded-lg border border-slate-100">
                  <div class="text-sm text-slate-700 flex items-center gap-2"><i class="ph ph-flag"></i> Trạng thái</div>
                                  @php
                                      $status = $assignment->status ?? null;
                                      $statusClass = match($status) {
                                          'pending'   => 'bg-amber-50 text-amber-700',
                                          'actived'   => 'bg-emerald-50 text-emerald-700',
                                          'cancelled' => 'bg-rose-50 text-rose-700',
                                          'stopped'   => 'bg-slate-50 text-slate-700',
                                          default     => 'bg-slate-100 text-slate-700',
                                      };

                                      $statusText = [
                                          'pending'   => 'Chờ xử lý',
                                          'actived'   => 'Đang hoạt động',
                                          'cancelled' => 'Đã hủy',
                                          'stopped'   => 'Đã dừng',
                                      ];

                                      $status = $statusText[$status] ?? null;
                                  @endphp
                                  <span class="px-2 py-0.5 rounded-full text-xs {{ $statusClass }}">{{ $status ?? 'Chưa nộp' }}</span>
                              </div>
                          </div>
                          <!-- de cuong -->
                          <div class="mt-6">
                              <h3 class="font-semibold mb-2 flex items-center gap-2"><i class="ph ph-file-text text-emerald-600"></i> Đề cương đã nộp</h3>
                            <div class="border-l-4 border-emerald-200 rounded-lg p-3 bg-slate-50 text-sm">
                                @php
                                    $hasOutline = $outlineSubmissions->count() > 0;
                                    $latest = $hasOutline ? $outlineSubmissions->first() : null;
                                    $overallStatus = $latest->status ?? null;
                                    $statusClass = match($overallStatus){
                                        'Đã duyệt' => 'bg-emerald-50 text-emerald-700',
                                        'Đã nộp' => 'bg-amber-50 text-amber-700',
                                        'Bị từ chối' => 'bg-rose-50 text-rose-700',
                                        default => 'bg-slate-100 text-slate-700'
                                    };

                                      $listOutline = $assignment->project?->reportFiles->sortByDesc('created_at') ?? collect();
                                      $countOutline = $assignment->project?->reportFiles()->count() ?? 0;

                                    $statusOutline = $finalOutline->status ?? null;
                                      $listStatus = [
                                      'pending' => 'Đã nộp',
                                      'submitted' => 'Đã nộp',
                                      'active' => 'Đang thực hiện',
                                      'approved' => 'Đã duyệt',
                                      'rejected' => 'Bị từ chối',
                                      ];

                                      $status = $listStatus[$statusOutline] ?? 'Chưa nộp';

                                      $listStatusColor = [
                                      'pending' => 'bg-amber-50 text-amber-700',
                                      'submitted' => 'bg-amber-50 text-amber-700',
                                      'active' => 'bg-amber-50 text-amber-700',
                                      'approved' => 'bg-emerald-50 text-emerald-700',
                                      'rejected' => 'bg-rose-50 text-rose-700',
                                      ];

                                      $statusOutlineColor = $listStatusColor[$statusOutline] ?? 'bg-slate-100 text-slate-700';

                                @endphp
                                @if(!$finalOutline)
                                    <div class="text-slate-500">Chưa có đề cương.</div>
                                @else
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="font-medium">{{ "Đề cương: " . $assignment->project->name }}</div>
                                            <div class="text-slate-600">
                                                Tệp:
                                                @if(!empty($finalOutline->file_url))
                                                    <a href="{{ $finalOutline->file_url }}" target="_blank" class="text-blue-600 hover:underline">{{ $finalOutline->file_name ?? 'Tệp đề cương' }}</a>
                                                @else
                                                    <span class="text-slate-500">-</span>
                                                @endif
                                            </div>
                                            <div class="text-slate-500">Nộp lúc: {{ $finalOutline->created_at->format('H:m:i d/m/Y') ?? '-' }}</div>
                                            @if(($latest->status ?? '') === 'rejected' && !empty("Rejected"))
                                                <div class="text-sm text-rose-600 mt-1">Lý do từ chối: Rejected</div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="px-2 py-0.5 rounded-full text-xs {{ $statusOutlineColor }}">{{ $status ?? 'Chưa nộp' }}</span>
                                        </div>
                                    </div>
                                    <!-- Actions: Outline -->
                                    @if ($finalOutline->status === 'pending')
                                      <div class="mt-3 flex items-center justify-end gap-2">
                                          <button type="button"
                                                  class="px-3 py-1.5 rounded bg-emerald-600 hover:bg-emerald-700 text-white text-sm btn-approve-file"
                                                  data-file-id="{{ $finalOutline->id }}" data-file-type="outline">
                                              <i class="ph ph-check"></i> Chấp nhận đề cương
                                          </button>
                                          <button type="button"
                                                  class="px-3 py-1.5 rounded border border-rose-200 text-rose-700 hover:bg-rose-50 text-sm btn-reject-file"
                                                  data-file-id="{{ $finalOutline->id }}" data-file-type="outline">
                                              <i class="ph ph-x-circle"></i> Từ chối đề cương
                                          </button>
                                      </div>
                                    @endif
                                    @if($countOutline > 0)
                                        <div class="mt-3">
                                            <div class="text-slate-600 text-sm mb-1">Các lần nộp</div>
                                            <div class="divide-y border rounded bg-white">
                                                @php
                                                $index = 0;
                                                @endphp
                                                @foreach($listOutline as $outline )
                                                      @if ($outline->type_report == 'outline')
                                                          @php
                                                              $index++;
                                                          @endphp
                                                          <div class="p-2 flex items-center justify-between gap-3">
                                                              <div>
                                                                  <div class="text-sm">
                                                                      <span class="text-slate-500">#{{ $index }}@if($index == 1) • mới nhất @endif:</span>
                                                                      {{ $outline->file_name ?? 'Đề cương' }}
                                                                  </div>
                                                                  <div class="text-xs text-slate-600">
                                                                      {{ $outline->created_at->format('H:m:i d/m/Y') ?? '-' }} •
                                                                      @if(!empty($outline->file_url))
                                                                          <a class="text-blue-600 hover:underline" href="{{ $outline->file_url }}" target="_blank">{{ $outline->file_name ?? 'Tệp' }}</a>
                                                                      @else
                                                                          <span class="text-slate-500">Không có tệp</span>
                                                                      @endif
                                                                  </div>
                                                                  @if(($outline->status ?? '') === 'rejected' && !empty($outline->note))
                                                                      <div class="text-xs text-rose-600">Lý do từ chối: Rejected</div>
                                                                  @endif
                                                              </div>
                                                              @php
                                                                  $pill = match($outline->status ?? null){
                                                                      'pending' => 'bg-amber-50 text-amber-700',
                                                                      'submitted' => 'bg-amber-50 text-amber-700',
                                                                      'approved' => 'bg-emerald-50 text-emerald-700',
                                                                      'rejected' => 'bg-rose-50 text-rose-700',
                                                                      default => 'bg-slate-100 text-slate-700'
                                                                  };
                                                                  $listStatus = [
                                                                      'pending' => 'Đã nộp',
                                                                      'submitted' => 'Đã nộp',
                                                                      'approved' => 'Đã duyệt',
                                                                      'rejected' => 'Bị từ chối',
                                                                  ];
                                                                  $statusOutline = $listStatus[$outline->status] ?? 'Chưa nộp';
                                                              @endphp
                                                              <div><span class="px-2 py-0.5 rounded-full text-xs {{ $pill }}">{{ $statusOutline }}</span></div>
                                                          </div>
                                                      @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                          </div>
                      </section>
            <section class="bg-white border rounded-2xl p-4 shadow-sm hover:shadow-md transition">
              <h2 class="font-semibold mb-3 flex items-center gap-2"><i class="ph ph-address-book text-blue-600"></i> Liên hệ</h2>
                        <div class="text-sm text-slate-700 space-y-1">
                            <div><span class="text-slate-500">Email: </span>
                                @if(!empty($studentUser->email))
                                    <a class="text-blue-600 hover:underline" href="mailto:{{ $studentUser->email }}">{{ $studentUser->email }}</a>
                                @else
                                    <span class="text-slate-500">-</span>
                                @endif
                            </div>
                            <div><span class="text-slate-500">SĐT: </span>{{ $studentUser->phone ?? '-' }}</div>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <a class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm" href="mailto:{{ $studentUser->email ?? '' }}"><i class="ph ph-envelope"></i> Gửi email</a>
                            <button class="px-3 py-1.5 border border-slate-200 rounded text-sm" disabled><i class="ph ph-chat-text"></i> Nhắn tin</button>
                        </div>
            </section>
        </div>

          <section class="bg-white border rounded-2xl p-5 mt-6 shadow-sm hover:shadow-md transition-all duration-200">
            <!-- Header -->
            <div class="flex items-center gap-2 mb-4">
              <i class="ph ph-notebook text-indigo-600 text-xl"></i>
              <h2 class="font-semibold text-lg text-slate-800">Nhật ký theo tuần</h2>
            </div>

            <div class="text-sm">
              @if($weeklyLogs->isEmpty())
                <div class="text-slate-500 flex items-center gap-2 p-4 border border-dashed rounded-xl bg-slate-50/50">
                  <i class="ph ph-calendar-x text-slate-400 text-lg"></i>
                  Chưa có nhật ký tuần.
                </div>
              @else
                <div class="overflow-x-auto border border-slate-100 rounded-xl bg-white shadow-sm">
                  <table class="w-full text-sm border-collapse">
                    <thead class="bg-slate-50/70 text-slate-600">
                      <tr class="text-left">
                        <th class="py-2.5 px-4 font-medium text-center">Tuần</th>
                        <th class="py-2.5 px-4 font-medium text-center">Tiêu đề</th>
                        <th class="py-2.5 px-4 font-medium text-center">Thời gian</th>
                        <th class="py-2.5 px-4 font-medium text-center">Trạng thái</th>
                      </tr>
                    </thead>

                    <tbody>
                      @foreach($weeklyLogs as $w)
                        @php
                          $listStatus = [
                              'pending'       => 'Chờ xử lý',
                              'approved'      => 'Đã duyệt',
                              'need_editing'  => 'Cần chỉnh sửa',
                              'not_achieved'  => 'Chưa đạt',
                          ];

                          $listColor = [
                              'pending'       => 'bg-slate-100 text-slate-700',
                              'approved'      => 'bg-emerald-100 text-emerald-700',
                              'need_editing'  => 'bg-amber-100 text-amber-700',
                              'not_achieved'  => 'bg-rose-100 text-rose-700',
                          ];
                        @endphp

                        <tr class="border-b border-slate-100 hover:bg-slate-50/70 transition-colors">
                          <td class="py-3 px-4 font-medium text-slate-700">
                            <div class="flex items-center gap-1.5">
                              <i class="ph ph-calendar text-indigo-500"></i>
                              Tuần {{ $loop->index + 1 ?? '-' }}
                            </div>
                          </td>

                          <td class="py-3 px-4">
                            <a href=""
                              class="text-blue-600 hover:underline font-medium">
                              {{ $w->title ?? '-' }}
                            </a>
                          </td>

                          <td class="py-3 px-4 text-slate-600 text-center">
                            {{ $w->created_at->format('H:i:s d/m/Y') ?? '-' }}
                          </td>

                          <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $listColor[$w->instructor_status] ?? 'bg-slate-100 text-slate-700' }}">
                              @switch($w->instructor_status)
                                @case('approved')
                                  <i class="ph ph-check-circle text-emerald-600"></i>
                                  @break
                                @case('need_editing')
                                  <i class="ph ph-pencil-simple text-amber-600"></i>
                                  @break
                                @case('not_achieved')
                                  <i class="ph ph-x-circle text-rose-600"></i>
                                  @break
                                @default
                                  <i class="ph ph-hourglass text-slate-500"></i>
                              @endswitch
                              {{ $listStatus[$w->instructor_status] ?? 'Chưa nộp' }}
                            </span>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </section>

                  <section class="bg-white border rounded-2xl p-5 mt-6 shadow-sm hover:shadow-md transition-all duration-200">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                      <div class="flex items-center gap-2">
                        <i class="ph ph-file-text text-emerald-600 text-xl"></i>
                        <h2 class="font-semibold text-lg text-slate-800">Báo cáo cuối đồ án</h2>
                      </div>
                    </div>

                    <!-- Main Content -->
                    <div id="finalReport" class="text-sm text-slate-700"></div>

                    <div class="text-sm text-slate-700">
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Report Info -->
                        <div class="border border-slate-100 rounded-xl p-4 bg-slate-50/70">
                          <div class="flex items-center gap-2 mb-2">
                            <i class="ph ph-file-arrow-down text-slate-600"></i>
                            <div class="font-medium text-slate-800">Báo cáo đồ án tốt nghiệp</div>
                          </div>

                          <div class="space-y-1 text-slate-600">
                            <div>
                              <span class="font-medium text-slate-700">Tệp:</span>
                              <a href="{{ $finalReport?->file_url ?? '#' }}" 
                                class="text-blue-600 hover:underline" 
                                target="_blank">{{ $finalReport->file_name ?? 'Chưa có' }}</a>
                            </div>

                            <div>
                              <span class="font-medium text-slate-700">Nộp lúc:</span>
                              {{ $finalReport?->created_at->format('H:i:s d/m/Y') ?? '-' }}
                            </div>

                            <div>
                              <span class="font-medium text-slate-700">Tương đồng:</span>
                              {{ $finalReport?->similarity ?? '-' }}
                            </div>
                          </div>

                          {{-- Hiển thị trạng thái --}}
                          @php
                            $listStatus = [
                                'pending' => 'Đang chờ',
                                'approved' => 'Đã duyệt',
                                'rejected' => 'Bị từ chối',
                            ];

                            $listColor = [
                                'pending' => 'bg-slate-100 text-slate-700',
                                'approved' => 'bg-emerald-100 text-emerald-700',
                                'rejected' => 'bg-rose-100 text-rose-700',
                            ];
                          @endphp

                          <div class="mt-3">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $listColor[$finalReport->status ?? 'pending'] ?? 'bg-slate-100 text-slate-700' }}">
                              <i class="ph ph-circle-wavy text-xs"></i>
                              {{ $listStatus[$finalReport->status ?? 'pending'] ?? 'Pending' }}
                            </span>
                          </div>
                        </div>

                        <!-- Score Info -->
                        <div class="border border-slate-100 rounded-xl p-4 bg-white">
                          <div class="flex items-center gap-2 mb-1">
                            <i class="ph ph-graduation-cap text-indigo-600"></i>
                            <div class="text-slate-700 font-medium">Điểm GVHD</div>
                          </div>
                          <div class="text-4xl font-bold text-slate-800">
                            {{ $current_assignment_supervisor?->score_report ?? '-'  }}
                          </div>
                          <p class="text-slate-500 text-xs mt-1">Điểm đánh giá cuối cùng của giảng viên hướng dẫn</p>
                        </div>
                      </div>

                      @if($finalReport && $finalReport->status == 'pending')
                        <!-- Actions -->
                        <div class="mt-4 flex items-center justify-end gap-3">
                          <button type="button"
                                  class="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm transition btn-approve-file"
                                  data-file-id="{{ $finalReport->id }}" data-file-type="report">
                            <i class="ph ph-check text-base"></i> Chấp nhận báo cáo
                          </button>

                          <button type="button"
                                  class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 hover:bg-rose-50 text-sm transition btn-reject-file"
                                  data-file-id="{{ $finalReport->id }}" data-file-type="report">
                            <i class="ph ph-x-circle text-base"></i> Từ chối báo cáo
                          </button>
                        </div>
                      @endif
                    </div>
                  </section>

                  <section class="bg-white border rounded-2xl p-6 shadow-sm mt-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-5">
                      <h2 class="font-semibold text-lg flex items-center gap-2 text-slate-800">
                        <i class="ph ph-users-three text-emerald-600 text-xl"></i>
                        Hội đồng & Điểm số
                      </h2>
                      <!-- <button id="btnUpdateScores"
                              class="flex items-center gap-1.5 px-3 py-1.5 border border-slate-300 rounded-lg text-sm text-slate-700 hover:bg-emerald-50 hover:border-emerald-400 transition">
                        <i class="ph ph-pencil-simple text-emerald-600"></i>
                        Cập nhật điểm
                      </button> -->
                    </div>

                    <div class="text-sm text-slate-700 space-y-6">
                      @php
                        $listMember = $assignment?->council_project?->council?->council_members ?? collect();
                        $listPosition = [
                          5 => 'Chủ tịch',
                          4 => 'Thư ký',
                          3 => 'Ủy viên 1',
                          2 => 'Ủy viên 2',
                          1 => 'Ủy viên 3',
                        ];
                        $council_project_id = $assignment?->council_project?->id ?? null;
                        $chair = $listMember->where('role', 5)->first();
                        $secretary = $listMember->where('role', 4)->first() ?? null;
                        $members1 = $listMember->where('role', 3)->first() ?? null;
                        $members2 = $listMember->where('role', 2)->first() ?? null;
                        $members3 = $listMember->where('role', 1)->first() ?? null;
                        $reviewer = $assignment->council_project->council_member ?? null;
                        $time = optional($assignment->council_project)->time;
                        $date = optional($assignment->council_project)->date;

                        $timeAndDate = ($date ? date('d/m/Y', strtotime($date)) : '') 
                            . ($time ? ' • ' . date('H:i', strtotime($time)) : 'Chưa có');
                        $room = $assignment->council_project->room ?? 'Chưa có';
                        $reviewScore = $assignment->council_project->review_score ?? 'Chưa có';
                      @endphp

                    <!-- Hội đồng -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                      <!-- 🧑‍🏫 Thông tin hội đồng -->
                      <div class="border border-slate-200 rounded-2xl bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                        <div>
                          <div class="flex items-center justify-between border-b pb-3 mb-4">
                            <div class="font-semibold text-slate-800 flex items-center gap-2 text-lg">
                              <i class="ph ph-chalkboard-teacher text-emerald-600 text-xl"></i>
                              Hội đồng CNTT-01
                            </div>
                            <span class="text-sm text-slate-500 flex items-center gap-1">
                              <i class="ph ph-map-pin-line text-slate-400"></i>{{ $room }}
                              <i class="ph ph-clock text-slate-400 ml-2"></i>{{ $timeAndDate }}
                            </span>
                          </div>

                          <div class="text-slate-700 font-medium mb-3 flex items-center gap-2">
                            <i class="ph ph-users-three text-emerald-600"></i>
                            Thành viên hội đồng
                          </div>

                          <div class="grid sm:grid-cols-2 gap-x-6 gap-y-4">
                            <div class="flex items-start gap-3">
                              <i class="ph ph-crown text-indigo-600 text-lg mt-1"></i>
                              <div>
                                <div class="text-sm text-slate-500">Chủ tịch</div>
                                <div class="font-semibold text-slate-800">{{ $chair?->supervisor?->teacher?->user?->fullname ?? 'Chưa có' }}</div>
                              </div>
                            </div>

                            <div class="flex items-start gap-3">
                              <i class="ph ph-user-circle text-blue-600 text-lg mt-1"></i>
                              <div>
                                <div class="text-sm text-slate-500">Ủy viên 1</div>
                                <div class="font-semibold text-slate-800">{{ $members1?->supervisor?->teacher?->user?->fullname ?? 'Chưa có' }}</div>
                              </div>
                            </div>

                            <div class="flex items-start gap-3">
                              <i class="ph ph-user-circle text-blue-600 text-lg mt-1"></i>
                              <div>
                                <div class="text-sm text-slate-500">Ủy viên 2</div>
                                <div class="font-semibold text-slate-800">{{ $members2?->supervisor?->teacher?->user?->fullname ?? 'Chưa có' }}</div>
                              </div>
                            </div>

                            <div class="flex items-start gap-3">
                              <i class="ph ph-user-circle text-blue-600 text-lg mt-1"></i>
                              <div>
                                <div class="text-sm text-slate-500">Ủy viên 3</div>
                                <div class="font-semibold text-slate-800">{{ $members3?->supervisor?->teacher?->user?->fullname ?? 'Chưa có' }}</div>
                              </div>
                            </div>

                            <div class="flex items-start gap-3">
                              <i class="ph ph-file-text text-amber-600 text-lg mt-1"></i>
                              <div>
                                <div class="text-sm text-slate-500">Thư ký</div>
                                <div class="font-semibold text-slate-800">{{ $secretary?->supervisor?->teacher?->user?->fullname ?? 'Chưa có' }}</div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- 📋 Phản biện -->
                      <div class="border border-slate-200 rounded-2xl bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                        <div>
                          <div class="flex items-center gap-2 mb-4 border-b pb-3">
                            <i class="ph ph-clipboard-text text-blue-600 text-xl"></i>
                            <div class="font-semibold text-slate-800 text-lg">Phản biện</div>
                          </div>

                          <div class="space-y-2 text-slate-700">
                            <div class="flex items-center justify-between">
                              <span class="font-medium text-slate-600">GV phản biện:</span>
                              <span class="text-slate-800 font-semibold">{{ $reviewer?->supervisor?->teacher?->user?->fullname ?? 'Chưa có' }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                              <span class="font-medium text-slate-600">Chức vụ:</span>
                              <span class="text-slate-800">{{ $listPosition[optional($reviewer)->role] ?? '—' }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                              <span class="font-medium text-slate-600">Số thứ tự PB:</span>
                              <span class="text-slate-800">01</span>
                            </div>

                            <div class="flex items-center justify-between">
                              <span class="font-medium text-slate-600">Thời gian:</span>
                              <span class="text-slate-800">{{ $timeAndDate }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                              <span class="font-medium text-slate-600">Địa điểm:</span>
                              <span class="text-slate-800">{{ $room }}</span>
                            </div>

                            <div class="mt-4 p-3 rounded-xl bg-gradient-to-r from-emerald-50 to-emerald-100 border border-emerald-200 flex items-center justify-between">
                              <span class="font-semibold text-emerald-700 flex items-center gap-2">
                                <i class="ph ph-seal-check text-emerald-700"></i>Điểm phản biện
                              </span>
                              <span class="text-2xl font-bold text-emerald-700">{{ $reviewScore }}</span>
                            </div>

                            <div class="text-slate-500 text-sm italic mt-3 bg-slate-50 rounded-md p-3 flex items-start gap-2">
                              <i class="ph ph-quotes text-slate-400 text-lg"></i>
                              <span>Nhận xét: Nhận xét tốt, cần bổ sung kiểm thử.</span>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5 mt-6">

                      @php
                        // Tạo danh sách thành viên hội đồng
                        $councilMembers = [
                            [
                                'title' => 'Chủ tịch',
                                'icon' => 'ph-user-circle',
                                'color' => 'indigo',
                                'member' => $chair ?? null
                            ],
                            [
                                'title' => 'Ủy viên 1',
                                'icon' => 'ph-user-circle',
                                'color' => 'blue',
                                'member' => $members1 ?? null
                            ],
                            [
                                'title' => 'Ủy viên 2',
                                'icon' => 'ph-user-circle',
                                'color' => 'blue',
                                'member' => $members2 ?? null
                            ],
                            [
                                'title' => 'Ủy viên 3',
                                'icon' => 'ph-user-circle',
                                'color' => 'blue',
                                'member' => $members3 ?? null
                            ],
                            [
                                'title' => 'Thư ký',
                                'icon' => 'ph-user-circle',
                                'color' => 'amber',
                                'member' => $secretary ?? null
                            ],
                        ];

                        $scores = [];
                      @endphp

                      @foreach ($councilMembers as $member)
                        @php
                          $defence = $member['member']?->council_project_defences
                            ->where('council_project_id', $council_project_id)
                            ->first();

                          $score = $defence?->score ?? null;
                          if (is_numeric($score)) $scores[] = $score;

                          $bgColor = "bg-{$member['color']}-100";
                          $textColor = "text-{$member['color']}-600";
                        @endphp

                        <div class="border rounded-2xl p-5 bg-white hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                          <div class="flex flex-col items-center">
                            <div class="w-12 h-12 flex items-center justify-center rounded-full {{ $bgColor }} {{ $textColor }} mb-3">
                              <i class="ph {{ $member['icon'] }} text-2xl"></i>
                            </div>
                            <div class="text-sm text-slate-500 font-medium">{{ $member['title'] }}</div>
                            <div class="text-base font-semibold text-slate-800 mt-1">
                              {{ $member['member']?->supervisor?->teacher?->user?->fullname ?? 'Chưa có' }}
                            </div>
                            <div class="mt-3 text-3xl font-extrabold text-slate-900">
                              {{ $score ?? '—' }}
                            </div>
                            @if($defence?->status)
                              <span class="mt-2 text-xs px-2 py-0.5 rounded-full
                                @switch($defence->status)
                                  @case('approved') bg-emerald-50 text-emerald-700 @break
                                  @case('need_editing') bg-amber-50 text-amber-700 @break
                                  @case('not_achieved') bg-rose-50 text-rose-700 @break
                                  @default bg-slate-50 text-slate-600
                                @endswitch
                              ">
                                {{ ucfirst(str_replace('_', ' ', $defence->status)) }}
                              </span>
                            @endif
                          </div>
                        </div>
                      @endforeach

                      <!-- Trung bình bảo vệ -->
                      <div class="border-2 border-emerald-500 rounded-2xl p-5 bg-gradient-to-b from-emerald-50 to-emerald-100 text-center shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="flex flex-col items-center">
                          <div class="w-12 h-12 flex items-center justify-center rounded-full bg-emerald-600 text-white mb-3">
                            <i class="ph ph-chart-line text-2xl"></i>
                          </div>
                          <div class="text-sm text-emerald-700 font-semibold uppercase tracking-wide">Điểm trung bình bảo vệ</div>
                          <div class="mt-2 text-4xl font-extrabold text-emerald-700 drop-shadow-sm">
                            @php
                              $averageScore = count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : '—';
                            @endphp
                            {{ $averageScore }}
                          </div>
                        </div>
                      </div>

                    </div>

                    </div>
                  </section>

              </div>

        </main>
    </div>
  </div>

  <script>
    const html = document.documentElement, sidebar = document.getElementById('sidebar');
    function setCollapsed(c) { const h = document.querySelector('header'); const m = document.querySelector('main'); if (c) { html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); } else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); } }
    document.getElementById('toggleSidebar')?.addEventListener('click', () => { const c = !html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('assistant_sidebar', '' + (c ? 1 : 0)); });
    document.getElementById('openSidebar')?.addEventListener('click', () => sidebar.classList.toggle('-translate-x-full'));
    if (localStorage.getItem('assistant_sidebar') === '1') setCollapsed(true);
    sidebar.classList.add('md:translate-x-0', '-translate-x-full', 'md:static');

    // simple filter
    document.getElementById('searchInput').addEventListener('input', (e) => {
      const q = e.target.value.toLowerCase();
      document.querySelectorAll('#tableBody tr').forEach(tr => tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none');
    });

    // profile dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    profileBtn?.addEventListener('click', () => profileMenu.classList.toggle('hidden'));
    document.addEventListener('click', (e) => { if (!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

    // sorting
    const sortState = { key: null, dir: 1 };
    function getSortValue(tr, key) {
      const tds = tr.querySelectorAll('td');
      if (key === 'mssv') return (tds[0]?.innerText || '').trim();
      if (key === 'name') return (tds[1]?.innerText || '').trim().toLowerCase();
      if (key === 'major') return (tds[2]?.innerText || '').trim().toLowerCase();
      if (key === 'gpa') return parseFloat(tds[3]?.innerText || '0') || 0;
      if (key === 'status') return (tds[4]?.innerText || '').trim().toLowerCase();
      return '';
    }
    function applySort(th) {
      const key = th.dataset.sortKey;
      if (!key) return;
      sortState.dir = sortState.key === key ? -sortState.dir : 1;
      sortState.key = key;
      const rows = Array.from(document.querySelectorAll('#tableBody tr')).filter(r => r.style.display !== 'none');
      rows.sort((a, b) => {
        const va = getSortValue(a, key), vb = getSortValue(b, key);
        if (typeof va === 'number' && typeof vb === 'number') return (va - vb) * sortState.dir;
        return (va > vb ? 1 : va < vb ? -1 : 0) * sortState.dir;
      });
      const tbody = document.getElementById('tableBody');
      rows.forEach(r => tbody.appendChild(r));
      document.querySelectorAll('thead th[data-sort-key] i').forEach(i => { i.className = 'ph ph-caret-up-down ml-1 text-slate-400'; });
      const icon = th.querySelector('i');
      icon.className = sortState.dir === 1 ? 'ph ph-caret-up ml-1 text-slate-600' : 'ph ph-caret-down ml-1 text-slate-600';
    }
    document.querySelectorAll('thead th[data-sort-key]')?.forEach(th => th.addEventListener('click', () => applySort(th)));

    // auto active nav highlight
    (function () {
      const current = location.pathname.split('/').pop();
      document.querySelectorAll('aside nav a').forEach(a => {
        const href = a.getAttribute('href') || '';
        const active = href.endsWith(current);
        a.classList.toggle('bg-slate-100', active);
        a.classList.toggle('font-semibold', active);
        if (active) a.classList.add('text-slate-900');
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