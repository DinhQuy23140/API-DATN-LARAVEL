<!DOCTYPE html>
<html lang="vi">
    <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chi tiết sinh viên hướng dẫn</title>
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    @php
        $user = auth()->user();
        $userName = $user->fullname ?? $user->name ?? 'Giảng viên';
        $email = $user->email ?? '';
        $dept = $user->department_name ?? optional($user->teacher)->department ?? '';
        $faculty = $user->faculty_name ?? optional($user->teacher)->faculty ?? '';
        $subtitle = trim(($dept ? "Bộ môn $dept" : '') . (($dept && $faculty) ? ' • ' : '') . ($faculty ? "Khoa $faculty" : ''));
        $degree = $user->teacher->degree ?? '';

        // Tập bản ghi phân công
        $asList = collect($assignment->assignment_supervisors ?? []);
        // Tìm bản ghi theo supervisor_id (so sánh int), nếu không có thì fallback bản ghi đầu tiên
        $asCurrent = $asList->first(function($as) use ($supervisorId){
            return (int)($as->supervisor_id ?? 0) === $supervisorId;
        }) ?? $asList->first();

        $asId = (int)($asCurrent->id ?? 0);
        $currentScoreReport = $asCurrent->score_report ?? '';

        $avatarUrl = $user->avatar_url
            ?? $user->profile_photo_url
            ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
        $departmentRole = $user->teacher->departmentRoles->where('role', 'head')->first() ?? null;
        $departmentId = $departmentRole?->department_id ?? 0;

        // Data dùng cho render tĩnh
        $student = optional($assignment)->student;
        $studentUser = optional($student)->user;
        $studentId = $student->id ?? 0;
        $project = optional($assignment)->project;
        $firstSupervisor = optional(optional($assignment)->assignment_supervisors[0] ?? null)->supervisor;
        $supervisorTeacherUser = optional(optional($firstSupervisor)->teacher)->user;

        // Các tập dữ liệu (đổi theo quan hệ thật của bạn)
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
        $asCurrent = optional($assignment->assignment_supervisors ?? collect())->firstWhere('supervisor_id', $supervisorId);
        $asId = $asCurrent->id ?? 0;
        $currentScoreReport = $asCurrent->score_report ?? '';
        $current_assignment_supervisor = $assignment->assignment_supervisors->where('supervisor_id', $supervisorId)->first();
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

        <div class="flex-1 h-screen overflow-hidden flex flex-col md:pl-[260px]">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
            <div class="flex items-center gap-3 flex-1">
            <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
            <div>
                <h1 class="text-lg md:text-xl font-semibold">Chi tiết sinh viên hướng dẫn</h1>
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
                    <span class="text-slate-500">Chi tiết</span>
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
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
                <form action="{{ route('web.auth.logout') }}" method="POST" class="hidden"><input type="submit">
                    @csrf
                </form>
            </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
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
               <div class="text-2xl font-bold mt-1">{{ $studentUser->fullname ?? 'Sinh viên' }}</div>
               <div class="text-sm opacity-90 mt-1">MSSV: <span class="font-medium">{{ $student->student_code ?? $student->id ?? '-' }}</span> • Lớp: {{ $student->class_code ?? '-' }}</div>
             </div>
             <div class="text-right">
               <div class="text-sm opacity-90">Giảng viên hướng dẫn</div>
               <div class="mt-1 inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white/20">
                 <i class="ph ph-user-circle text-white"></i>
                 <div class="font-medium">{{ $supervisorTeacherUser->fullname ?? '—' }}</div>
               </div>
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
              <th class="py-2.5 px-4 font-medium text-center">Hành động</th>
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
                  <a href="{{ route('web.teacher.weekly_log_detail', ['progressLogId' => $w->id]) }}"
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

                <td class="py-3 px-4 text-center">
                  <a href="{{ route('web.teacher.weekly_log_detail', ['progressLogId' => $w->id]) }}"
                     class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 font-medium transition">
                    <i class="ph ph-eye text-base"></i> Xem chi tiết
                  </a>
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

                    <button id="btnGradeFinal"
                            class="flex items-center gap-1 px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-sm hover:bg-emerald-700 transition"
                            data-as-id="{{ $asId }}"
                            data-student="{{ $studentUser->fullname ?? 'Sinh viên' }}"
                            data-project="{{ $project->name ?? ($project->title ?? 'Đề tài') }}"
                            data-current-score="{{ $currentScoreReport }}">
                      <i class="ph ph-check-circle text-base"></i>
                      Chấm điểm
                    </button>
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

    <script>
        // ...rất nhiều JS render nội dung...
    </script>
    <!-- Không cần JS để render nội dung; giữ lại nếu cần dropdown profile -->
    <script>
        const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
        profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
        document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    </script>
    <!-- Modal: Chấm điểm GVHD -->
    <div id="modalReportScore" class="fixed inset-0 z-50 hidden">
      <div class="absolute inset-0 bg-black/40" data-close-rs></div>
      <div class="relative bg-white w-full max-w-xl mx-auto mt-10 md:mt-24 rounded-2xl shadow-xl">
        <div class="flex items-center justify-between px-5 py-4 border-b">
          <h3 class="font-semibold">Chấm điểm báo cáo (GVHD)</h3>
          <button class="text-slate-500 hover:text-slate-700" data-close-rs><i class="ph ph-x"></i></button>
        </div>
        <div class="p-5 space-y-4 text-sm">
          <div>
            <div class="text-slate-500">Sinh viên</div>
            <div id="rsStudent" class="font-medium text-slate-800">—</div>
          </div>
          <div>
            <div class="text-slate-500">Đề tài</div>
            <div id="rsProject" class="font-medium text-slate-800">—</div>
          </div>
          <form id="rsForm" class="space-y-3">
            <input type="hidden" id="rsAsId" />
            <div>
              <label class="text-sm text-slate-600">Điểm báo cáo (0 - 10)</label>
              <input id="rsScore" type="number" step="0.1" min="0" max="10"
                     class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
            </div>
            <div>
              <label class="text-sm text-slate-600">Ghi chú (không bắt buộc)</label>
              <textarea id="rsNote" rows="3"
                        class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Nhận xét/ghi chú..."></textarea>
            </div>
          </form>
        </div>
        <div class="px-5 py-4 border-t flex items-center justify-end gap-2">
          <button class="px-3 py-1.5 rounded-lg border text-sm hover:bg-slate-50" data-close-rs>Đóng</button>
          <button id="rsSubmit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm">
            <i class="ph ph-check"></i> Lưu điểm
          </button>
        </div>
      </div>
    </div>

    <script>
        // Modal Report Score (GVHD)
        const rsModal = document.getElementById('modalReportScore');
        const openRs = ()=> { rsModal.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); };
        const closeRs= ()=> { rsModal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); };
        rsModal?.addEventListener('click', (e)=>{ if(e.target.closest('[data-close-rs]')) closeRs(); });

        document.getElementById('btnGradeFinal')?.addEventListener('click', ()=>{
          const btn = document.getElementById('btnGradeFinal');
          const asId = btn?.dataset.asId || '';
          if (!asId || asId === '0') { alert('Không tìm thấy phân công GVHD cho sinh viên này.'); return; }
          document.getElementById('rsAsId').value = asId;
          document.getElementById('rsStudent').textContent = btn?.dataset.student || 'Sinh viên';
          document.getElementById('rsProject').textContent = btn?.dataset.project || 'Đề tài';
          document.getElementById('rsScore').value = btn?.dataset.currentScore || '';
          document.getElementById('rsNote').value = '';
          openRs();
        });

        document.getElementById('rsSubmit')?.addEventListener('click', async ()=>{
          const asId = document.getElementById('rsAsId').value;
          let score = document.getElementById('rsScore').value;
          const note  = document.getElementById('rsNote').value;
          if (!asId) { alert('Thiếu mã phân công.'); return; }
          if (score === '' || isNaN(parseFloat(score))) { alert('Vui lòng nhập điểm hợp lệ.'); return; }
          score = Math.max(0, Math.min(10, parseFloat(score))); // clamp 0..10

          const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
          const url = `{{ route('web.teacher.assignment_supervisors.report_score', ['assignmentSupervisor' => $asId]) }}`.replace('/0','/'+asId);
          const btn = document.getElementById('rsSubmit');
          const old = btn.innerHTML; btn.disabled = true; btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> Đang lưu...';
          try {
            const res = await fetch(url, {
              method: 'POST',
              headers: {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': token,'X-Requested-With':'XMLHttpRequest'},
              body: JSON.stringify({ score_report: score, note })
            });
            const data = await res.json().catch(()=> ({}));
            if (!res.ok || data.ok === false) { alert(data.message || 'Lưu điểm thất bại.'); btn.disabled=false; btn.innerHTML=old; return; }
            closeRs();
            location.reload();
          } catch (e) {
            alert('Lỗi mạng, vui lòng thử lại.');
            btn.disabled = false; btn.innerHTML = old;
          }
        });
    </script>
     <!-- Modal: Từ chối tệp (đề cương/báo cáo) -->
     <div id="fileRejectModal" class="fixed inset-0 z-50 hidden">
       <div class="absolute inset-0 bg-black/40" data-close-fr></div>
       <div class="relative bg-white w-full max-w-lg mx-auto mt-10 md:mt-24 rounded-2xl shadow-xl">
         <div class="flex items-center justify-between px-5 py-4 border-b">
           <h3 class="font-semibold">Từ chối tệp</h3>
           <button class="text-slate-500 hover:text-slate-700" data-close-fr><i class="ph ph-x"></i></button>
         </div>
         <div class="p-5 space-y-3 text-sm">
           <input type="hidden" id="frFileId">
           <input type="hidden" id="frFileType">
           <div>
             <div class="text-slate-600">Lý do từ chối</div>
             <textarea id="frReason" rows="4" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500" placeholder="Nhập lý do từ chối..."></textarea>
           </div>
         </div>
         <div class="px-5 py-4 border-t flex items-center justify-end gap-2">
           <button class="px-3 py-1.5 rounded-lg border text-sm hover:bg-slate-50" data-close-fr>Đóng</button>
           <button id="frSubmit" class="px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-sm">
             <i class="ph ph-x-circle"></i> Xác nhận từ chối
           </button>
         </div>
       </div>
     </div>
 
     <script>
       // Giao diện: duyệt/từ chối tệp đề cương và báo cáo (UI only)
       const frModal = document.getElementById('fileRejectModal');
       const openFr = ()=> { frModal.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); };
       const closeFr= ()=> { frModal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); };
       frModal?.addEventListener('click', (e)=>{ if(e.target.closest('[data-close-fr]')) closeFr(); });
 
       // Chấp nhận
       document.addEventListener('click', async (e)=>{
         const btn = e.target.closest('.btn-approve-file');
         if(!btn) return;
         const id = btn.dataset.fileId, type = btn.dataset.fileType;
         if(!id) return alert('Thiếu mã tệp.');
         if(!confirm(`Xác nhận chấp nhận ${type === 'outline' ? 'đề cương' : 'báo cáo'} này?`)) return;

         const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
         const urlTpl = `{{ route('web.teacher.report_files.update_status', ['report_file' => '__ID__']) }}`;
         const url = urlTpl.replace('__ID__', encodeURIComponent(id));
         try {
           const res = await fetch(url, {
             method: 'PATCH',
             headers: {
               'Content-Type':'application/json',
               'Accept':'application/json',
               'X-Requested-With':'XMLHttpRequest',
               'X-CSRF-TOKEN': token
             },
             body: JSON.stringify({ status: 'approved' })
           });
           const js = await res.json().catch(()=> ({}));
           if (!res.ok || js.ok === false) return alert(js.message || 'Cập nhật thất bại.');
           location.reload();
         } catch (err) {
           alert('Lỗi mạng, vui lòng thử lại.');
         }
       });
 
       // Từ chối (mở modal lấy lý do)
       document.addEventListener('click', (e)=>{
         const btn = e.target.closest('.btn-reject-file');
         if(!btn) return;
         const id = btn.dataset.fileId, type = btn.dataset.fileType;
         if(!id) return alert('Thiếu mã tệp.');
         document.getElementById('frFileId').value = id;
         document.getElementById('frFileType').value = type;
         document.getElementById('frReason').value = '';
         openFr();
       });
 
       // Submit từ chối
       document.getElementById('frSubmit')?.addEventListener('click', async ()=>{
         const id = document.getElementById('frFileId').value;
         const type = document.getElementById('frFileType').value;
         const reason = document.getElementById('frReason').value.trim();
         if(!id) { alert('Thiếu mã tệp.'); return; }
         if(!reason) { alert('Vui lòng nhập lý do.'); return; }
         const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
         const urlTpl = `{{ route('web.teacher.report_files.update_status', ['report_file' => '__ID__']) }}`;
         const url = urlTpl.replace('__ID__', encodeURIComponent(id));
         try {
           const res = await fetch(url, {
             method: 'PATCH',
             headers: {
               'Content-Type':'application/json',
               'Accept':'application/json',
               'X-Requested-With':'XMLHttpRequest',
               'X-CSRF-TOKEN': token
             },
             body: JSON.stringify({ status: 'rejected', note: reason })
           });
           const js = await res.json().catch(()=> ({}));
           if (!res.ok || js.ok === false) return alert(js.message || 'Cập nhật thất bại.');
           closeFr();
           location.reload();
         } catch (err) {
           alert('Lỗi mạng, vui lòng thử lại.');
         }
       });
     </script>
    </body>
</html>