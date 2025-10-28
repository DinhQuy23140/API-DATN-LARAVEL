<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Danh sách phản biện được phân công</title>
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

    <div class="flex-1 h-screen overflow-hidden flex flex-col">
      <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Danh sách phản biện được phân công</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-round-detail.html" class="hover:underline text-slate-600">Chi tiết đợt</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Phản biện được phân công</span>
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
        <div class="max-w-7xl mx-auto space-y-4">

          <div class="flex flex-col md:flex-row md:items-center justify-end gap-3">
            <a href="thesis-round-detail.html" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại chi tiết đợt</a>
          </div>

          <section class="bg-white rounded-xl border border-slate-200 p-4">
            <h2 class="font-semibold text-lg mb-2">Thông tin đợt đồ án</h2>
            <div class="text-slate-700 text-sm space-y-1">
              @php
                $stage = $projectTerm->stage;
                $termName = $projectTerm->academy_year->name . ' - Học kỳ ' . $stage;
                $semester = ($stage % 2 == 1) ? '1' : '2';
                $date = date('d/m/Y', strtotime($projectTerm->start_date)) . ' - ' . date('d/m/Y', strtotime($projectTerm->end_date));
              @endphp
              <div><strong>Đợt:</strong> {{ $termName }} </div>
              <div><strong>Năm học:</strong> {{ $termName }} </div>
              <div><strong>Học kỳ:</strong> {{ $semester }} </div>
              <div><strong>Thời gian:</strong> {{ $date }} </div>
            </div>
          </section>

          <section class="bg-white rounded-xl border border-slate-200 p-4 my-6">
            <h2 class="font-semibold text-lg mb-4">Thông tin hội đồng</h2>
            <div class="text-slate-700 text-sm space-y-2">
              @if ($council)
                <div>
                  <strong>Tên hội đồng:</strong> {{ $council->name }}
                </div>
                <div>
                  <strong>Mã hội đồng:</strong> {{ $council->code }}
                </div>
                <div>
                  <strong>Mô tả:</strong> {{ $council->description ?? 'Không có mô tả' }}
                </div>
                <div>
                  <strong>Khoa/Bộ môn:</strong> {{ $council->department->name ?? 'N/A' }}
                </div>
                <div>
                  <strong>Địa điểm:</strong> {{ $council->address ?? 'Chưa có' }}
                </div>
                <div>
                  <strong>Ngày bảo vệ:</strong> 
                  {{ $council->date ? date('d/m/Y', strtotime($council->date)) : 'Chưa có' }}
                </div>
                <div>
                  <strong>Trạng thái:</strong> 
                  <span class="px-2 py-1 text-xs rounded-full border
                    {{ $council->status === 'active' 
                        ? 'bg-green-50 text-green-700 border-green-200' 
                        : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                    {{ ucfirst($council->status) }}
                  </span>
                </div>
                <div>
                  <strong>Thuộc đợt đồ án:</strong> 
                  {{ $council->project_term->academy_year->name ?? '' }} - Học kỳ {{ $council->project_term->stage ?? '' }}
                </div>
              @else
                <div class="text-slate-500 italic">Chưa có thông tin hội đồng</div>
              @endif
            </div>
          </section>

          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="search" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-80" placeholder="Tìm theo tên/MSSV/hội đồng" />
            </div>
          </div>
          @php
            $council_member_id = $council->council_members->where('supervisor_id', $supervisorId)->first()->id ?? null;
          @endphp
          <div class="bg-white border rounded-xl p-4 shadow-sm">
            <div class="overflow-x-auto">
              <table id="studentTable" class="w-full text-sm table-fixed border-collapse">
                <thead>
                  <tr class="bg-slate-100 text-slate-600 text-left">
                    <th class="py-3 px-3 font-medium w-40">Sinh viên</th>
                    <th class="py-3 px-3 font-medium w-28">MSSV</th>
                    <th class="py-3 px-3 font-medium w-64">Đề tài</th>
                    <th class="py-3 px-3 font-medium w-40">Báo cáo</th>
                    <th class="py-3 px-3 font-medium w-28 text-center">Thứ tự</th>
                    <th class="py-3 px-3 font-medium w-32 text-center">Điểm</th>
                    <th class="py-3 px-3 font-medium w-44 text-center">Thao tác</th>
                  </tr>
                </thead>
                <tbody id="rows">
                  @foreach ($council_projects as $council_project)
                    @php
                      $student = $council_project->assignment->student;
                      $studentName = $student ? $student->user->fullname : 'N/A';
                      $studentCode = $student ? $student->student_code : 'N/A';
                      $topic = $council_project->assignment->project?->name ?? 'N/A';
                      $advisor = $council_project->assignment->assignment_supervisors ?? [];
                      $reportUrl = $council_project->assignment->reportFiles?->sortByDesc('created_at')->first()?->file_url
                                    ?? $council_project->assignment->reportFiles?->sortByDesc('created_at')->first()?->file_path
                                    ?? '#';
                      $index = $loop->index + 1;

                      // Lấy điểm và nhận xét theo từng thành viên trong hội đồng cho dòng hiện tại
                      $listCouncilMember = $council_project->council_project_defences;
                      $review = $listCouncilMember->where('council_member_id', $council_member_id)->first();
                      $scoreCouncilMember = $review->score ?? 'Chưa chấm';
                      $comment = $review->comment ?? $review->comments ?? '';

                      $statusColor = $scoreCouncilMember !== 'Chưa chấm'
                                    ? 'bg-green-50 text-green-700 border-green-200'
                                    : 'bg-rose-50 text-rose-700 border-rose-200';
                      $time = $council_project->time ?? 'N/A';
                      $councilId = $council_project->council_id ?? null;
                      $className = $student->classroom->name ?? ($student->class_name ?? 'N/A');
                      $advisorNames = ($council_project->assignment->assignment_supervisors ?? collect())
                        ->map(fn($a) => $a->supervisor->teacher->user->fullname)
                        ->values();

                      // Map role -> member + score/comment
                      $roleMap = ['chair'=>5,'secretary'=>4,'member1'=>3,'member2'=>2,'member3'=>1];
                      $membersByRole = ($council?->council_members ?? collect())->keyBy('role');
                      $scoresMap = [];
                      foreach ($roleMap as $key => $role) {
                        $mem = $membersByRole->get($role);
                        $name = $mem?->supervisor?->teacher?->user?->fullname;
                        $def  = $mem ? $listCouncilMember->firstWhere('council_member_id', $mem->id) : null;
                        $scoresMap[$key] = [
                          'name'    => $name,
                          'score'   => $def->score ?? null,
                          'comment' => ($def->comment ?? $def->comments ?? null),
                        ];
                      }
                    @endphp
                     <tr class="rowStudent border-b hover:bg-slate-50 hover:shadow-sm transition cursor-pointer"
                         data-cp-id="{{ $council_project->id }}"
                         data-student-name="{{ $studentName }}"
                         data-student-code="{{ $studentCode }}"
                         data-class="{{ $className }}"
                         data-topic="{{ $topic }}"
                         data-advisors='@json($advisorNames)'
                         data-scores='@json($scoresMap)'>
                      <td class="py-3 px-3 truncate max-w-[150px]" title="{{ $studentName }}">{{ $studentName }}</td>
                      <td class="py-3 px-3 whitespace-nowrap">{{ $studentCode }}</td>
                      <td class="py-3 px-3 truncate max-w-[250px]" title="{{ $topic }}">{{ $topic }}</td>
                      <td class="py-3 px-3">
                        @if ($reportUrl && $reportUrl !== '#')
                          <a href="{{ $reportUrl }}" target="_blank" class="text-blue-600 hover:underline inline-flex items-center gap-1">
                            <i class="ph ph-file"></i> Xem báo cáo
                          </a>
                        @else
                          <span class="text-slate-500">Chưa có báo cáo</span>
                        @endif
                      </td>
                      <td class="py-3 px-3 text-center">{{ $index }}</td>
                      <td class="py-3 px-3 text-center">
                        <span class="px-2 py-1 text-xs font-medium rounded-full border {{ $statusColor }}">
                          {{ $scoreCouncilMember }}
                        </span>
                      </td>
                      <td class="py-3 px-3">
                        <div class="flex items-center justify-center gap-2">
                           <button type="button"
                                   class="btnGrade min-w-[90px] text-center inline-flex items-center justify-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-md border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition"
                                   data-cp-id="{{ $council_project->id }}"
                                   data-student-name="{{ $studentName }}"
                                   data-student-code="{{ $studentCode }}"
                                   data-topic="{{ $topic }}"
                                   data-advisor='@json($advisor->map(fn($a) => $a->supervisor->teacher->user->fullname))'
                                   data-report="{{ $reportUrl }}"
                                   data-current-score="{{ is_numeric($scoreCouncilMember) ? $scoreCouncilMember : '' }}"
                                   data-current-comment="{{ $comment }}">
                             <i class="ph ph-pen"></i> Chấm
                           </button>
                          @if ($councilId)
                            <a class="min-w-[90px] text-center inline-flex items-center justify-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-md border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100 transition" 
                              href="{{ route('web.teacher.committee_detail', ['councilId' => $councilId, 'termId' => $projectTerm->id, 'supervisorId'=> $supervisorId]) }}">
                              <i class="ph ph-eye"></i> Hội đồng
                            </a>
                          @endif
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div> 
        </div>
      </main>
    </div>
  </div> 
   <!-- Modal: Thông tin sinh viên -->
   <div id="studentInfoModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
     <div class="absolute inset-0 bg-black/40" data-close-si></div>
     <div class="relative z-10 bg-white w-full max-w-3xl rounded-2xl shadow-xl flex flex-col max-h-[calc(100vh-4rem)]">
       <div class="flex items-center justify-between px-5 py-4 border-b">
         <h3 class="font-semibold">Thông tin sinh viên</h3>
         <button class="text-slate-500 hover:text-slate-700" data-close-si><i class="ph ph-x"></i></button>
       </div>
       <div class="p-5 overflow-y-auto">
         <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
           <div>
             <div class="text-slate-500">Mã sinh viên</div>
             <div id="siCode" class="font-medium text-slate-800">—</div>
           </div>
           <div>
             <div class="text-slate-500">Tên sinh viên</div>
             <div id="siName" class="font-medium text-slate-800">—</div>
           </div>
           <div>
             <div class="text-slate-500">Lớp</div>
             <div id="siClass" class="font-medium text-slate-800">—</div>
           </div>
           <div class="md:col-span-2">
             <div class="text-slate-500">Đề tài</div>
             <div id="siTopic" class="font-medium text-slate-800">—</div>
           </div>
           <div class="md:col-span-2">
             <div class="text-slate-500">Giảng viên hướng dẫn</div>
             <div id="siAdvisors" class="font-medium text-slate-800">—</div>
           </div>
         </div>
         <hr class="my-4">
         <div>
           <div class="font-semibold mb-2">Điểm và nhận xét theo vai trò</div>
           <div class="overflow-x-auto">
             <table class="w-full text-sm border border-slate-200 rounded-lg overflow-hidden">
               <thead class="bg-slate-50 text-slate-600">
                 <tr>
                   <th class="text-left px-3 py-2 w-40">Thành viên</th>
                   <th class="text-left px-3 py-2 w-56">Họ tên</th>
                   <th class="text-left px-3 py-2 w-24">Điểm</th>
                   <th class="text-left px-3 py-2">Nhận xét</th>
                 </tr>
               </thead>
               <tbody id="siScores" class="divide-y divide-slate-100">
                 <tr><td class="px-3 py-2">Chủ tịch</td><td id="siChairName" class="px-3 py-2">—</td><td id="siChairScore" class="px-3 py-2">—</td><td id="siChairComment" class="px-3 py-2">—</td></tr>
                 <tr><td class="px-3 py-2">Thư ký</td><td id="siSecName" class="px-3 py-2">—</td><td id="siSecScore" class="px-3 py-2">—</td><td id="siSecComment" class="px-3 py-2">—</td></tr>
                 <tr><td class="px-3 py-2">Ủy viên 1</td><td id="siM1Name" class="px-3 py-2">—</td><td id="siM1Score" class="px-3 py-2">—</td><td id="siM1Comment" class="px-3 py-2">—</td></tr>
                 <tr><td class="px-3 py-2">Ủy viên 2</td><td id="siM2Name" class="px-3 py-2">—</td><td id="siM2Score" class="px-3 py-2">—</td><td id="siM2Comment" class="px-3 py-2">—</td></tr>
                 <tr><td class="px-3 py-2">Ủy viên 3</td><td id="siM3Name" class="px-3 py-2">—</td><td id="siM3Score" class="px-3 py-2">—</td><td id="siM3Comment" class="px-3 py-2">—</td></tr>
               </tbody>
             </table>
           </div>
         </div>
       </div>
       <div class="px-5 py-4 border-t flex items-center justify-end gap-2">
         <button class="px-3 py-1.5 rounded-lg border text-sm hover:bg-slate-50" data-close-si>Đóng</button>
       </div>
     </div>
   </div> 
  <!-- Modal: Chấm phản biện -->
  <div id="gradeModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" data-close-grade></div>
    <div class="relative bg-white w-full max-w-3xl mx-auto mt-10 md:mt-24 rounded-2xl shadow-xl">
      <div class="flex items-center justify-between px-5 py-4 border-b">
        <h3 class="font-semibold">Chấm phản biện</h3>
        <button class="text-slate-500 hover:text-slate-700" data-close-grade><i class="ph ph-x"></i></button>
      </div>
      <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
          <div>
            <div class="text-slate-500">Sinh viên</div>
            <div id="gStudent" class="font-medium text-slate-800">—</div>
          </div>
          <div>
            <div class="text-slate-500">MSSV</div>
            <div id="gCode" class="font-medium text-slate-800">—</div>
          </div>
          <div class="md:col-span-2">
            <div class="text-slate-500">Đề tài</div>
            <div id="gTopic" class="font-medium text-slate-800 line-clamp-2">—</div>
          </div>
          <div>
            <div class="text-slate-500">GV hướng dẫn</div>
            <div id="gAdvisor" class="font-medium text-slate-800">—</div>
          </div>
          <div class="flex items-center gap-2">
            <i class="ph ph-file text-slate-500"></i>
            <a id="gReportLink" href="#" target="_blank" class="text-blue-600 hover:underline text-sm">Xem báo cáo</a>
          </div>
        </div>
        <hr class="my-4">
        <form id="gradeForm" class="space-y-4">
          <input type="hidden" name="cp_id" id="gCpId" />
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="text-sm text-slate-600">Điểm (0 - 10)</label>
              <input name="score" id="gScore" type="number" step="0.1" min="0" max="10"
                     class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
            <div class="md:col-span-2">
              <label class="text-sm text-slate-600">Nhận xét</label>
              <textarea name="comment" id="gComment" rows="3"
                        class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Nhận xét, góp ý..."></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="px-5 py-4 border-t flex items-center justify-end gap-2">
        <button class="px-3 py-1.5 rounded-lg border text-sm hover:bg-slate-50" data-close-grade>Đóng</button>
        <button id="btnSubmitGrade" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm">
          <i class="ph ph-check"></i> Lưu điểm
        </button>
      </div>
    </div>
  </div>

  <script>
    // Sidebar/profile wiring
    (function(){
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const h=document.querySelector('header'); const m=document.querySelector('main');
        if(c){ html.classList.add('sidebar-collapsed')}
        else { html.classList.remove('sidebar-collapsed')}
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
      const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    })();

    document.getElementById('search')?.addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll('#studentTable tbody tr');
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    });

    // ===== Modal Thông tin sinh viên =====
    const siModal = document.getElementById('studentInfoModal');
    function openSI(){ siModal.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); }
    function closeSI(){ siModal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
    siModal?.addEventListener('click', (e)=>{ if(e.target.closest('[data-close-si]')) closeSI(); });
    document.addEventListener('keydown', (e)=>{ if(e.key==='Escape' && !siModal.classList.contains('hidden')) closeSI(); });

    function setTxt(id, val){ const el=document.getElementById(id); if(el) el.textContent = (val ?? '')!=='' ? val : '—'; }
    function renderSIScores(scores){
      // scores: { chair:{name,score,comment}, secretary:{...}, member1:{...}, member2:{...}, member3:{...} }
      const map = [
        ['chair','siChairName','siChairScore','siChairComment'],
        ['secretary','siSecName','siSecScore','siSecComment'],
        ['member1','siM1Name','siM1Score','siM1Comment'],
        ['member2','siM2Name','siM2Score','siM2Comment'],
        ['member3','siM3Name','siM3Score','siM3Comment'],
      ];
      map.forEach(([k,n,s,c])=>{
        const it = (scores && scores[k]) || {};
        setTxt(n, it.name || '—');
        setTxt(s, (it.score ?? '') === '' ? '—' : it.score);
        setTxt(c, it.comment || '—');
      });
    }

    // Click hàng -> mở modal thông tin SV và hiển thị điểm các thành viên từ dataset
    document.getElementById('rows')?.addEventListener('click', async (e)=>{
      if (e.target.closest('button, a, input, textarea, select, label')) return;
      const tr = e.target.closest('tr.rowStudent');
      if(!tr) return;
      const cpId = tr.dataset.cpId;
      const name = tr.dataset.studentName || 'N/A';
      const code = tr.dataset.studentCode || 'N/A';
      const cls  = tr.dataset.class || 'N/A';
      const topic= tr.dataset.topic || 'N/A';
      let advisors = [];
      try { advisors = JSON.parse(tr.dataset.advisors || '[]'); } catch { advisors = []; }

      setTxt('siName', name);
      setTxt('siCode', code);
      setTxt('siClass', cls);
      setTxt('siTopic', topic);
      setTxt('siAdvisors', advisors.length ? advisors.join(', ') : '—');

      // Đổ điểm/nhận xét của các thành viên hội đồng từ dataset
      let scoresDs = {};
      try { scoresDs = JSON.parse(tr.dataset.scores || '{}'); } catch { scoresDs = {}; }
      renderSIScores(scoresDs);

      openSI();

      // (Tuỳ chọn) nếu có API thì có thể fetch cập nhật chi tiết sau đó
      // ...
    });

    // Grade modal helpers
    const gradeModal = document.getElementById('gradeModal');
    function openGrade(){ gradeModal.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); }
    function closeGrade(){ gradeModal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
    gradeModal?.addEventListener('click', (e)=>{ if(e.target.closest('[data-close-grade]')) closeGrade(); });

    // Open modal chấm: điền sẵn score/comment hiện tại
    document.querySelectorAll('.btnGrade').forEach(btn => {
      btn.addEventListener('click', () => {
        const cpId = btn.dataset.cpId || '';
        const stName = btn.dataset.studentName || 'N/A';
        const stCode = btn.dataset.studentCode || 'N/A';
        const topic = btn.dataset.topic || 'N/A';
        let advisor = [];
        try { advisor = JSON.parse(btn.dataset.advisor || '[]'); } catch { advisor = []; }
        const report = btn.dataset.report || '#';
        const currentScore = btn.dataset.currentScore || '';
        const currentComment = btn.dataset.currentComment || '';

        document.getElementById('gCpId').value = cpId;
        document.getElementById('gStudent').textContent = stName;
        document.getElementById('gCode').textContent   = stCode;
        document.getElementById('gTopic').textContent  = topic;
        document.getElementById('gAdvisor').textContent = advisor.join(', ');
        const link = document.getElementById('gReportLink');
        link.href = report && report !== '' ? report : '#';
        link.classList.toggle('pointer-events-none', !report || report === '#');
        link.classList.toggle('text-slate-400', !report || report === '#');

        // Prefill điểm và nhận xét
        document.getElementById('gScore').value = currentScore;
        document.getElementById('gComment').value = currentComment;

        openGrade();
      });
    });

    // council_member_id lấy từ Blade (ưu tiên)
    const councilMemberId = @json($council_member_id ?? null);
    // (Tuỳ chọn) giữ supervisorId nếu cần dùng nơi khác
    const supervisorId = @json($supervisorId ?? null);

    // Submit grade -> POST web.teacher.reviews.store
    document.getElementById('btnSubmitGrade')?.addEventListener('click', async ()=>{
      const cpId = document.getElementById('gCpId').value?.trim();
      const score = document.getElementById('gScore').value?.trim();
      const comment = document.getElementById('gComment').value?.trim() || '';

      if (!cpId) { alert('Thiếu council_project_id.'); return; }
      if (score === '' || isNaN(Number(score))) { alert('Vui lòng nhập điểm hợp lệ (0 - 10).'); return; }
      if (!councilMemberId) { alert('Thiếu council_member_id.'); return; }

      const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
      const urlTpl = `{{ route('web.teacher.councile_project_defences.store', ['council_project' => '__ID__']) }}`;
      const url = urlTpl.replace('__ID__', encodeURIComponent(cpId));

      const btn = document.getElementById('btnSubmitGrade');
      const old = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> Đang lưu...';

      try {
        const res = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token
          },
          body: JSON.stringify({
            score: Number(score),
            comment,
            council_member_id: councilMemberId
          })
        });
        const data = await res.json().catch(()=> ({}));
        if (!res.ok || data.ok === false) {
          alert(data.message || 'Lưu điểm thất bại.');
          return;
        }

        // Cập nhật UI trên hàng tương ứng
        const row = document.querySelector(`tr.rowStudent[data-cp-id="${cpId}"]`);
        if (row) {
          const scoreCell = row.children[5];
          const pill = scoreCell?.querySelector('span');
          if (pill) {
            pill.textContent = String(Number(score));
            pill.className = 'px-2 py-1 text-xs font-medium rounded-full border bg-green-50 text-green-700 border-green-200';
          }
          // Đồng bộ điểm hiện tại cho nút "Chấm"
          row.querySelector('.btnGrade')?.setAttribute('data-current-score', String(Number(score)));
        }

        // Đóng modal
        typeof closeGrade === 'function' && closeGrade();
      } catch (e) {
        console.error(e);
        alert('Lỗi mạng khi lưu điểm.');
      } finally {
        btn.disabled = false;
        btn.innerHTML = old;
      }
    });
  </script>
</body>
</html>
