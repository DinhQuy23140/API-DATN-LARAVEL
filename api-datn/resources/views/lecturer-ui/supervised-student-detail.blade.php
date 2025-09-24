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
        $expertise = $user->teacher->supervisor->expertise ?? null;
        $data_assignment_supervisors = $user->teacher->supervisor->assignment_supervisors ?? null;
        $supervisorId = $user->teacher->supervisor->id ?? 0;
        $teacherId = $user->teacher->id ?? 0;
        $avatarUrl = $user->avatar_url
            ?? $user->profile_photo_url
            ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';

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
        $committee = $assignment->committee ?? null;
        $asCurrent = optional($assignment->assignment_supervisors ?? collect())->firstWhere('supervisor_id', $supervisorId);
        $asId = $supervisorId ?? 0;
        $currentScoreReport = $asCurrent->score_report ?? '';
    @endphp

    <body class="bg-slate-50 text-slate-800">
    <div class="flex min-h-screen">
        <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
        <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
            <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-chalkboard-teacher"></i></div>
            <div class="sidebar-label">
            <div class="font-semibold">Lecturer</div>
            <div class="text-xs text-slate-500">Bảng điều khiển</div>
            </div>
        </div>
        <nav class="flex-1 overflow-y-auto p-3">
            <a href="overview.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span class="sidebar-label">Tổng quan</span></a>
            <a href="profile.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user"></i><span class="sidebar-label">Hồ sơ</span></a>
            <a href="research.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-flask"></i><span class="sidebar-label">Nghiên cứu</span></a>
            <a href="students.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span></a>
            <div class="sidebar-label text-xs uppercase text-slate-400 px-3 mt-3">Học phần tốt nghiệp</div>
            <a href="thesis-internship.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 pl-10"><i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span></a>
            <a href="thesis-rounds.html" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold pl-10"><i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span></a>
        </nav>
        <div class="p-3 border-t border-slate-200">
            <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
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

               <div class="bg-white border rounded-xl p-4 mb-4">
                   <div class="flex items-center justify-between">
                       <div>
                           <div class="text-sm text-slate-500">
                               MSSV: <span class="font-medium text-slate-700">{{ $student->student_code ?? $student->id ?? '-' }}</span>
                           </div>
                           <h2 class="font-semibold text-lg mt-1">{{ $studentUser->fullname ?? 'Sinh viên' }}</h2>
                           <div class="text-sm text-slate-600">Lớp: {{ $student->class_code ?? '-' }}</div>
                       </div>
                       <div class="text-right">
                           <div class="text-sm text-slate-500">GVHD</div>
                           <div class="font-medium text-blue-600">
                               {{ $supervisorTeacherUser->fullname ?? '—' }}
                           </div>
                       </div>
                   </div>
               </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <section class="md:col-span-2 bg-white border rounded-xl p-4">
                        <h2 class="font-semibold mb-3">Thông tin đề tài</h2>
                        <div class="text-sm text-slate-700 space-y-1">
                            <div><span class="text-slate-500">Đề tài: </span><span class="font-medium">{{ $project->name ?? ($project->title ?? 'Chưa có đề tài') }}</span></div>
                        </div>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="bg-blue-50 p-3 rounded">
                                <div class="text-sm text-blue-800">Ngày bắt đầu</div>
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ optional($assignment?->created_at)->format('d/m/Y') ?? '-' }}
                                </div>
                            </div>
                            <div class="bg-slate-50 p-3 rounded">
                                <div class="text-sm text-slate-700">Trạng thái</div>
                                @php
                                    $status = $assignment->status ?? null;
                                    $statusClass = match($status){
                                        'pending' => 'bg-amber-50 text-amber-700',
                                        'submitted' => 'bg-amber-50 text-amber-700',
                                        'active' => 'bg-emerald-50 text-emerald-700',
                                        'approved' => 'bg-emerald-50 text-emerald-700',
                                        'rejected' => 'bg-rose-50 text-rose-700',
                                        default => 'bg-slate-100 text-slate-700'
                                    };
                                    $statusText = [
                                        'pending' => 'Chờ duyệt',
                                        'submitted' => 'Đã nộp',
                                        'active' => 'Đã duyệt',
                                        'approved' => 'Đã duyệt',
                                        'rejected' => 'Bị từ chối'
                                    ];
                                    $status = $statusText[$status] ?? null;
                                @endphp
                                 <span class="px-2 py-0.5 rounded-full text-xs {{ $statusClass }}">{{ $status ?? 'Chưa nộp' }}</span>
                            </div>
                        </div>
<!-- de cuong -->
                        <div class="mt-6">
                            <h3 class="font-semibold mb-2">Đề cương đã nộp</h3>
                           <div class="border rounded-lg p-3 bg-slate-50 text-sm">
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
                    <section class="bg-white border rounded-xl p-4">
                        <h2 class="font-semibold mb-3">Liên hệ</h2>
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

                <section class="bg-white border rounded-xl p-4 mt-4">
                    <h2 class="font-semibold mb-3">Nhật ký theo tuần</h2>
                   <div class="text-sm">
                       @if($weeklyLogs->isEmpty())
                           <div class="text-slate-500">Chưa có nhật ký tuần.</div>
                       @else
                           <div class="overflow-x-auto border rounded-xl bg-white">
                               <table class="w-full text-sm">
                                   <thead>
                                       <tr class="text-left text-slate-500 border-b">
                                           <th class="py-2 px-3">Tuần</th>
                                           <th class="py-2 px-3">Tiêu đề</th>
                                           <th class="py-2 px-3">Thời gian</th>
                                           <th class="py-2 px-3">Trạng thái</th>
                                           <th class="py-2 px-3">Điểm</th>
                                           <th class="py-2 px-3">Hành động</th>
                                       </tr>
                                   </thead>
                                   
                                   <tbody>
                                       @foreach($weeklyLogs as $w)
                                           @php

                                                $listStatus = [
                                                    'chua_nop' => 'Chưa nộp',
                                                    'da_nop' => 'Đã nộp',
                                                    'dat' => 'Đạt',
                                                    'chua_dat' => 'Chưa đạt',
                                                    'can_chinh_sua' => 'Cần chỉnh sửa',
                                                    'chua_danh_gia' => 'Chưa đánh giá',
                                                ];

                                                $listColor = [
                                                    'chua_nop' => 'bg-slate-50 text-slate-700',
                                                    'da_nop' => 'bg-blue-50 text-blue-700',
                                                    'dat' => 'bg-emerald-50 text-emerald-700',
                                                    'chua_dat' => 'bg-rose-50 text-rose-700',
                                                    'can_chinh_sua' => 'bg-amber-50 text-amber-700',
                                                    'chua_danh_gia' => 'bg-slate-100 text-slate-700',
                                                ];
                                           @endphp
                                           <tr class="border-b hover:bg-slate-50">
                                               <td class="py-2 px-3">Tuần {{ $loop->index + 1 ?? '-' }}</td>
                                               <td class="py-2 px-3"><a class="text-blue-600 hover:underline" href="weekly-log-detail.html?studentId=20210001&amp;name=Nguy%E1%BB%85n%20V%C4%83n%20A&amp;week=1">{{ $w->title ?? '-' }}</a></td>
                                               <td class="py-2 px-3">{{ $w->created_at->format('H:i:s d/m/Y') ?? '-' }}</td>
                                               <td class="py-2 px-3"><span class="px-2 py-0.5 rounded-full text-xs {{ $listColor[$w->instructor_status] }}">{{ $listStatus[$w->instructor_status] ?? 'Chưa nộp' }}</span></td>
                                               <td class="py-2 px-3">{{ $w->score ?? '-' }}</td>
                                               <td class="py-2 px-3"><a class="text-blue-600 hover:underline" href="{{ route('web.teacher.weekly_log_detail', ['progressLogId' => $w->id]) }}">Xem chi tiết</a></td>
                                           </tr>
                                       @endforeach
                                   </tbody>
                               </table>
                           </div>
                       @endif
                   </div>
                </section>

                <section class="bg-white border rounded-xl p-4 mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="font-semibold">Báo cáo cuối đồ án</h2>
                       <button id="btnGradeFinal"
                               class="px-3 py-1.5 bg-emerald-600 text-white rounded text-sm"
                               data-as-id="{{ $asId }}"
                               data-student="{{ $studentUser->fullname ?? 'Sinh viên' }}"
                               data-project="{{ $project->name ?? ($project->title ?? 'Đề tài') }}"
                               data-current-score="{{ $currentScoreReport }}">
                         <i class="ph ph-check-circle"></i> Chấm điểm
                       </button>
                    </div>
                   <div id="finalReport" class="text-sm text-slate-700"></div>
<!-- +                   <div class="text-sm text-slate-700">
+                       @if(!$finalReport)
+                           <div class="text-slate-500">Chưa có báo cáo cuối.</div>
+                       @else
+                           <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
+                               <div class="border rounded-lg p-3 bg-slate-50">
+                                   <div class="font-medium">{{ $finalReport->title ?? 'Báo cáo cuối' }}</div>
+                                   <div class="text-slate-600">
+                                       Tệp:
+                                       @if(!empty($finalReport->file_url))
+                                           <a href="{{ $finalReport->file_url }}" class="text-blue-600 hover:underline" target="_blank">{{ $finalReport->file_name ?? 'Tệp báo cáo' }}</a>
+                                       @else
+                                           <span class="text-slate-500">-</span>
+                                       @endif
+                                   </div>
+                                   <div class="text-slate-500">Nộp lúc: {{ $finalReport->submitted_at ?? '-' }}</div>
+                                   @if(!empty($finalReport->similarity))
+                                       <div class="text-slate-500">Tương đồng: {{ $finalReport->similarity }}</div>
+                                   @endif
+                               </div>
+                               <div class="border rounded-lg p-3">
+                                   <div class="text-slate-600">Điểm GVHD</div>
+                                   <div class="text-3xl font-bold">{{ $finalReport->supervisor_score ?? '-' }}</div>
+                               </div>
+                           </div>
+                       @endif
+                   </div> -->
                    <div class="text-sm text-slate-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="border rounded-lg p-3 bg-slate-50">
                                <div class="font-medium">Báo cáo đồ án tốt nghiệp</div>
                                <div class="text-slate-600">
                                    Tệp:
                                    <a href="{{ $finalReport?->file_url ?? '#' }}" class="text-blue-600 hover:underline" target="_blank">{{ $finalReport->file_name ?? 'Chưa có' }}</a>
                                </div>
                                <div class="text-slate-500">Nộp lúc: {{ $finalReport?->created_at->format('H:m:i d/m/Y') ?? '-' }}</div>
                                <div class="text-slate-500">Tương đồng: {{ $finalReport?->similarity ?? '-' }}</div>
                            </div>
                            <div class="border rounded-lg p-3">
                                <div class="text-slate-600">Điểm GVHD</div>
                                <div class="text-3xl font-bold">{{ $finalReport?->supervisor_score ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bg-white border rounded-xl p-4 mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="font-semibold">Hội đồng & điểm số</h2>
                       <button id="btnUpdateScores" class="px-3 py-1.5 border border-slate-200 rounded text-sm"><i class="ph ph-pencil"></i> Cập nhật điểm</button>
                       <button class="px-3 py-1.5 border border-slate-200 rounded text-sm" disabled><i class="ph ph-pencil"></i> Cập nhật điểm</button>
                   </div>
                   <div id="committee" class="text-sm text-slate-700"></div>
<!-- +                   <div class="text-sm text-slate-700">
+                       @if(!$committee)
+                           <div class="text-slate-500">Chưa có thông tin hội đồng.</div>
+                       @else
+                           <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
+                               <div class="lg:col-span-2 border rounded-lg p-3 bg-slate-50">
+                                   <div class="font-medium mb-1">{{ $committee->name ?? 'Hội đồng' }}</div>
+                                   <div class="text-slate-600">Thời gian: {{ $committee->date ?? '-' }} {{ $committee->time ?? '' }} • Phòng: {{ $committee->room ?? '-' }}</div>
+                                   <div class="mt-2">
+                                       <div class="text-slate-600 mb-1">Thành viên:</div>
+                                       <ul class="list-disc pl-5 space-y-0.5">
+                                           @foreach(($committee->members ?? []) as $m)
+                                               <li><span class="text-slate-500">{{ $m['role'] ?? $m->role ?? '-' }}:</span> {{ $m['name'] ?? $m->name ?? '-' }}</li>
+                                           @endforeach
+                                       </ul>
+                                   </div>
+                               </div>
+                               <div class="border rounded-lg p-3">
+                                   <div class="font-medium mb-2">Phản biện</div>
+                                   <div class="text-slate-600">GV phản biện: {{ data_get($committee, 'reviewer.name', '-') }}</div>
+                                   <div class="text-slate-600">Chức vụ: <span class="font-medium">Phản biện</span></div>
+                                   <div class="text-slate-600">Hội đồng: <span class="font-medium">{{ $committee->code ?? '-' }}</span></div>
+                                   <div class="text-slate-600">Số thứ tự PB: <span class="font-medium">{{ $committee->review_order ?? '—' }}</span></div>
+                                   <div class="text-slate-600">Thời gian: {{ $committee->date ?? '-' }} • {{ $committee->student_time ?? ($committee->time ?? '-') }}</div>
+                                   <div class="text-slate-600 mt-2">Điểm phản biện: <span class="font-semibold">{{ data_get($committee, 'reviewer.score', '-') }}</span></div>
+                                   <div class="text-slate-500">Nhận xét: {{ data_get($committee, 'reviewer.note', '-') }}</div>
+                               </div>
+                           </div>
+                           @if($defenseScores->isNotEmpty())
+                               <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
+                                   @foreach($defenseScores as $s)
+                                       <div class="border rounded-lg p-3 bg-white">
+                                           <div class="text-slate-500">{{ $s['by'] ?? $s->by ?? '-' }}</div>
+                                           <div class="text-2xl font-bold">{{ $s['score'] ?? $s->score ?? '-' }}</div>
+                                       </div>
+                                   @endforeach
+                                   <div class="border rounded-lg p-3 bg-emerald-50">
+                                       <div class="text-emerald-700">Trung bình bảo vệ</div>
+                                       <div class="text-3xl font-bold text-emerald-700">{{ $avg }}</div>
+                                   </div>
+                               </div>
+                           @endif
+                       @endif
+                   </div> -->
<div class="text-sm text-slate-700">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 border rounded-lg p-3 bg-slate-50">
            <div class="font-medium mb-1">Hội đồng CNTT-01</div>
            <div class="text-slate-600">Thời gian: 20/08/2025 08:00 • Phòng: P.A203</div>
            <div class="mt-2">
                <div class="text-slate-600 mb-1">Thành viên:</div>
                <ul class="list-disc pl-5 space-y-0.5">
                    <li><span class="text-slate-500">Chủ tịch:</span> PGS.TS. Trần Văn B</li>
                    <li><span class="text-slate-500">Ủy viên 1:</span> TS. Lê Thị C</li>
                    <li><span class="text-slate-500">Ủy viên 2:</span> TS. Phạm Văn D</li>
                    <li><span class="text-slate-500">Ủy viên 3:</span> ThS. Trần Thị F</li>
                    <li><span class="text-slate-500">Thư ký:</span> ThS. Nguyễn Văn G</li>
                    <li><span class="text-slate-500">Phản biện:</span> TS. Nguyễn Thị E</li>
                </ul>
            </div>
        </div>
        <div class="border rounded-lg p-3">
            <div class="font-medium mb-2">Phản biện</div>
            <div class="text-slate-600">GV phản biện: TS. Nguyễn Thị E</div>
            <div class="text-slate-600">Chức vụ: <span class="font-medium">Phản biện</span></div>
            <div class="text-slate-600">Hội đồng: <span class="font-medium">CNTT-01</span></div>
            <div class="text-slate-600">Số thứ tự PB: <span class="font-medium">01</span></div>
            <div class="text-slate-600">Thời gian: 20/08/2025 • 08:00</div>
            <div class="text-slate-600 mt-2">Điểm phản biện: <span class="font-semibold">8.7</span></div>
            <div class="text-slate-500">Nhận xét: Nhận xét tốt, cần bổ sung kiểm thử.</div>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="border rounded-lg p-3 bg-white">
            <div class="text-slate-500">Chủ tịch</div>
            <div class="text-2xl font-bold">8.5</div>
        </div>
        <div class="border rounded-lg p-3 bg-white">
            <div class="text-slate-500">Ủy viên</div>
            <div class="text-2xl font-bold">8.0</div>
        </div>
        <div class="border rounded-lg p-3 bg-white">
            <div class="text-slate-500">Phản biện</div>
            <div class="text-2xl font-bold">8.7</div>
        </div>
        <div class="border rounded-lg p-3 bg-emerald-50">
            <div class="text-emerald-700">Trung bình bảo vệ</div>
            <div class="text-3xl font-bold text-emerald-700">8.4</div>
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
          const score = document.getElementById('rsScore').value;
          const note  = document.getElementById('rsNote').value;
          if (!asId) { alert('Thiếu mã phân công.'); return; }
          if (score === '' || isNaN(parseFloat(score))) { alert('Vui lòng nhập điểm hợp lệ.'); return; }
          const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
          const url = `{{ route('web.teacher.assignment_supervisors.report_score', ['assignmentSupervisor' => 0]) }}`.replace('/0','/'+asId);
          const btn = document.getElementById('rsSubmit');
          const old = btn.innerHTML; btn.disabled = true; btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> Đang lưu...';
          try {
            const res = await fetch(url, {
              method: 'POST',
              headers: {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': token,'X-Requested-With':'XMLHttpRequest'},
              body: JSON.stringify({ score_report: parseFloat(score), note })
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
    </body>
</html>