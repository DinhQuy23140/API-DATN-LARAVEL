<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chi tiết hội đồng</title>
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
    $data_assignment_supervisors = $user->teacher->supervisor->assignment_supervisors ?? collect();
    $supervisorId = $user->teacher->supervisor->id ?? 0;
    $teacherId = $user->teacher->id ?? 0;
    $avatarUrl = $user->avatar_url
      ?? $user->profile_photo_url
      ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
  @endphp

<body class="bg-slate-50 text-slate-800">
  <div class="flex min-h-screen">
    <aside id="sidebar"
      class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i
            class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Lecturer</div>
          <div class="text-xs text-slate-500">Bảng điều khiển</div>
        </div>
      </div>
      @php
        $isThesisOpen = request()->routeIs('web.teacher.thesis_internship') || request()->routeIs('web.teacher.thesis_rounds');
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
          <a href="{{ route('web.teacher.students', ['supervisorId' => $user->teacher->supervisor->id]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.students') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span>
          </a>
        @else
          <span class="text-slate-400">Chưa có supervisor</span>
        @endif

        @php
          $isThesisOpen = request()->routeIs('web.teacher.thesis_internship') || request()->routeIs('web.teacher.thesis_rounds');
        @endphp
        <button
          type="button"
          id="toggleThesisMenu"
          aria-controls="thesisSubmenu"
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
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_rounds') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
             @if(request()->routeIs('web.teacher.thesis_rounds')) aria-current="page" @endif>
            <i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span>
          </a>
        </div>
      </nav>
      <div class="p-3 border-t border-slate-200">
        <button id="toggleSidebar"
          class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i
            class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
      </div>
    </aside>

    <div class="flex-1 h-screen overflow-hidden flex flex-col">
      <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Chi tiết hội đồng</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-round-detail.html" class="hover:underline text-slate-600">Chi tiết đợt</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Hội đồng</span>
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
          </div>
        </div>
      </header>
      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
        <div class="max-w-6xl mx-auto space-y-4">
          <div class="flex items-center justify-between">
            <div class="text-sm text-slate-600">Mã hội đồng: .{{ $council->code }}</div>
            <a href="committees.html" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Danh sách hội đồng</a>
          </div>

          <!-- Thông tin đợt đồ án -->
          <section class="bg-white rounded-xl border border-slate-200 p-6 space-y-5 shadow-sm hover:shadow-md transition">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
              <h2 class="font-semibold text-lg flex items-center gap-2 text-slate-800">
                <i class="ph ph-graduation-cap text-indigo-500"></i> Thông tin đợt đồ án
              </h2>
              <span class="text-xs text-slate-500">Mã hội đồng: {{ $council->code }}</span>
            </div>

            @php
              $term = $council->project_term ?? null;
              $termName = $term->stage ?? 'Chưa có';
              $year = $term->academy_year->year_name ?? 'N/A';
              $semester = $term->stage ?? 'N/A';
              $termId = $term->id ?? 0;
              $start = $term->start_date ? \Carbon\Carbon::parse($term->start_date)->format('d/m/Y') : 'N/A';
              $end = $term->end_date ? \Carbon\Carbon::parse($term->end_date)->format('d/m/Y') : 'N/A';
            @endphp

            <!-- Grid thông tin -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
              <!-- Đợt -->
              <div class="flex items-center gap-2">
                <i class="ph ph-flag text-rose-500"></i>
                <span class="text-slate-500 font-medium">Đợt:</span>
                <span class="text-slate-700 font-semibold">{{ $termName }}</span>
              </div>

              <!-- Năm học -->
              <div class="flex items-center gap-2">
                <i class="ph ph-calendar-blank text-indigo-500"></i>
                <span class="text-slate-500 font-medium">Năm học:</span>
                <span class="text-indigo-600 font-semibold">{{ $year }}</span>
              </div>

              <!-- Học kỳ -->
              <div class="flex items-center gap-2">
                <i class="ph ph-books text-blue-500"></i>
                <span class="text-slate-500 font-medium">Học kỳ:</span>
                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                  {{ $semester }}
                </span>
              </div>

              <!-- Thời gian -->
              <div class="flex items-center gap-2">
                <i class="ph ph-clock text-emerald-500"></i>
                <span class="text-slate-500 font-medium">Thời gian:</span>
                <span class="text-emerald-700 font-semibold">{{ $start }} – {{ $end }}</span>
              </div>
            </div>
          </section>

          <section class="bg-white border rounded-2xl p-6 shadow-sm hover:shadow-md transition space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
              <h2 class="font-bold text-xl flex items-center gap-2 text-slate-800">
                <i class="ph ph-users-three text-blue-500"></i> Thông tin hội đồng
              </h2>
              <span class="px-2 py-1 rounded-lg text-xs bg-sky-50 text-sky-700 border border-sky-200">
                Mã: {{ $council->code }}
              </span>
            </div>

            <!-- Thông tin chính -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Tên hội đồng -->
              <div>
                <h3 class="text-sm font-semibold text-slate-500 flex items-center gap-1">
                  <i class="ph ph-identification-badge text-indigo-500"></i> Tên hội đồng
                </h3>
                <p class="mt-1 text-lg font-semibold text-slate-800">{{ $council->name }} ({{ $council->code }})</p>
              </div>

              <!-- Trạng thái -->
              <div>
                <h3 class="text-sm font-semibold text-slate-500 flex items-center gap-1">
                  <i class="ph ph-activity text-emerald-500"></i> Trạng thái
                </h3>
                <p class="mt-1">
                  <span class="px-3 py-1 text-sm rounded-full font-medium
                    @if($council->status == 'active')
                      bg-emerald-50 text-emerald-700 border border-emerald-200
                    @elseif($council->status == 'stopped')
                      bg-slate-50 text-slate-600 border border-slate-200
                    @else
                      bg-rose-50 text-rose-700 border border-rose-200
                    @endif
                  ">
                    {{ ucfirst($council->status) }}
                  </span>
                </p>
              </div>

              <!-- Ngày -->
              <div>
                <h3 class="text-sm font-semibold text-slate-500 flex items-center gap-1">
                  <i class="ph ph-calendar text-sky-500"></i> Ngày tổ chức
                </h3>
                <p class="mt-1 text-sky-700 font-medium">{{ $council->date ?? 'Chưa có ngày' }}</p>
              </div>

              <!-- Phòng -->
              <div>
                <h3 class="text-sm font-semibold text-slate-500 flex items-center gap-1">
                  <i class="ph ph-map-pin text-pink-500"></i> Phòng bảo vệ
                </h3>
                <p class="mt-1 text-pink-700 font-medium">{{ $council->address ?? 'Chưa có phòng' }}</p>
              </div>

              <!-- Khoa -->
              <div>
                <h3 class="text-sm font-semibold text-slate-500 flex items-center gap-1">
                  <i class="ph ph-buildings text-violet-500"></i> Khoa / Viện
                </h3>
                <p class="mt-1 text-violet-700 font-medium">{{ $council->department->name ?? 'Chưa có thông tin' }}</p>
              </div>

              <!-- Đợt đồ án -->
              <div>
                <h3 class="text-sm font-semibold text-slate-500 flex items-center gap-1">
                  <i class="ph ph-books text-teal-500"></i> Đợt đồ án
                </h3>
                <p class="mt-1 text-teal-700 font-medium">{{ $council->project_term->stage ?? 'N/A' }}</p>
              </div>
            </div>

            <!-- Mô tả -->
            <div>
              <h3 class="text-sm font-semibold text-slate-500 flex items-center gap-1">
                <i class="ph ph-note text-amber-500"></i> Mô tả
              </h3>
              <p class="mt-1 text-slate-600 leading-snug">{{ $council->description ?? 'Không có mô tả' }}</p>
            </div>

            <!-- Sơ đồ phòng -->
            <div>
              <h3 class="text-sm font-semibold text-slate-500 flex items-center gap-1">
                <i class="ph ph-layout text-slate-500"></i> Sơ đồ phòng: Phòng {{ $council->address }}
              </h3>
              <div class="mt-3 h-44 bg-slate-50 border-2 border-dashed rounded-xl flex items-center justify-center text-slate-400 text-sm">
                <i class="ph ph-layout text-lg mr-2"></i> Sơ đồ phòng (placeholder)
              </div>
            </div>
          </section>

          @php
          $council_members = $council->council_members->sortByDesc('role') ?? collect();
          @endphp

          <section class="bg-white border rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold text-slate-800">Thành viên hội đồng</h3>
              <div class="text-xs text-slate-500">Xem thông tin chi tiết từng thành viên</div>
            </div>

            <div id="members" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              @foreach ($council_members as $council_member)
                @php
                  $liatRole = [
                    '5' => 'Chủ tịch',
                    '4' => 'Thư ký',
                    '3' => 'Ủy viên 1',
                    '2' => 'Ủy viên 2',
                    '1' => 'Ủy viên 3',
                  ];
                  $role  = $liatRole[$council_member->role] ?? 'Thành viên';
                  $name  = $council_member->supervisor->teacher->user->fullname ?? 'N/A';
                  $email = $council_member->supervisor->teacher->user->email ?? 'N/A';
                  $phone = $council_member->supervisor->teacher->user->phone ?? 'N/A';
                @endphp

                <div class="border rounded-xl p-4 bg-white shadow-sm hover:shadow-md transition duration-200 flex flex-col">
                  <div class="flex items-center gap-3">
                    <!-- Avatar -->
                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                      <i class="ph ph-user text-xl"></i>
                    </div>
                    <div>
                      <div class="font-medium text-slate-800">{{ $name }}</div>
                      <div class="text-xs text-slate-500">{{ $email }}</div>
                      <div class="text-xs text-slate-500">{{ $phone }}</div>
                    </div>
                  </div>

                  <div class="mt-3">
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full 
                      {{ $council_member->role == 5 ? 'bg-red-100 text-red-600' : 
                        ($council_member->role == 4 ? 'bg-blue-100 text-blue-600' : 
                        'bg-slate-100 text-slate-600') }}">
                      {{ $role }}
                    </span>
                  </div>

                  <div class="mt-auto pt-3">
                    <button data-midx="${idx}" 
                      class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 
                            border border-slate-200 rounded-lg text-sm font-medium text-slate-700 
                            hover:bg-slate-50 transition">
                      <i class="ph ph-address-book"></i> Xem thông tin
                    </button>
                  </div>
                </div>
              @endforeach
            </div>
          </section>

<section class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition">
  <!-- Header -->
  <div class="flex items-center justify-between mb-4">
    <div>
      <h3 class="font-semibold text-lg text-slate-800 flex items-center gap-2">
        <i class="ph ph-student text-indigo-500"></i> Sinh viên thuộc hội đồng
      </h3>
      <p class="text-xs text-slate-500 mt-1 hidden md:block">
        Danh sách sinh viên bảo vệ tại hội đồng này
      </p>
    </div>
    
    @php
      $supervisorExis = $council_members->where('supervisor_id', $supervisorId)->first();
    @endphp
    @if ($supervisorExis && $supervisorExis->role == 4)
      <button id="btnOpenAssignModal"
        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium shadow-sm">
        <i class="ph ph-user-switch"></i> Phân công sinh viên
      </button>
    @endif
  </div>

  @php
    $council_projects = $council->council_projects ?? collect();
  @endphp

  <!-- Table -->
  <div class="overflow-x-auto border rounded-lg">
    <table class="w-full text-sm">
      <thead>
        <tr class="bg-slate-50 text-slate-600 border-b">
          <th class="py-3 px-4 text-left font-medium"><i class="ph ph-user text-slate-400"></i> Sinh viên</th>
          <th class="py-3 px-4 text-left font-medium"><i class="ph ph-identification-badge text-slate-400"></i> MSSV</th>
          <th class="py-3 px-4 text-left font-medium"><i class="ph ph-book text-slate-400"></i> Lớp</th>
          <th class="py-3 px-4 text-left font-medium"><i class="ph ph-book text-slate-400"></i> Đề tài</th>
          <th class="py-3 px-4 text-left font-medium"><i class="ph ph-chalkboard-teacher text-slate-400"></i> Giảng viên hướng dẫn</th>
          <th class="py-3 px-4 text-left font-medium"><i class="ph ph-gear text-slate-400"></i> Hành động</th>
        </tr>
      </thead>
      <tbody id="studentRows">
        @foreach ($council_projects as $council_project)
          @php
            $student = $council_project->assignment->student ?? null;
            $name = $student->user->fullname ?? 'N/A';
            $class = $student->class_code ?? 'N/A';
            $id = $student->student_code ?? 'N/A';
            $topic = $council_project->assignment->project->name ?? 'N/A';
            $time = $council_project->time ?? 'N/A';
            $assignment_supervisors = $council_project->assignment->assignment_supervisors ?? collect();
          @endphp
          <tr class="border-b last:border-0 hover:bg-slate-50 transition">
            <td class="py-3 px-4">
              <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $student->id, 'termId' => $termId, 'supervisorId' => $supervisorId])}}"
                 class="text-blue-600 font-medium hover:underline">
                {{ $name }}
              </a>
            </td>
            <td class="py-3 px-4 text-slate-700">{{ $class }}</td>
            <td class="py-3 px-4 text-slate-700">{{ $id }}</td>
            <td class="py-3 px-4 text-slate-700">{{ $topic }}</td>
            <td class="py-3 px-4 text-slate-700 space-y-1">
              @foreach ($assignment_supervisors as $assignment_supervisor)
                <span class="block">{{ $assignment_supervisor->supervisor->teacher->user->fullname }}</span>
              @endforeach
            </td>
            <td class="py-3 px-4">
              <div class="flex items-center gap-2">
                <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $student->id, 'termId' => $termId, 'supervisorId' => $supervisorId])}}"
                   class="px-2 py-1 rounded-lg text-xs font-medium border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center gap-1">
                  <i class="ph ph-eye"></i> Xem SV
                </a>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</section>


          <!-- Modal: Phân công sinh viên -->
          <div id="assignModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 md:p-6" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-black/40" data-close-assign></div>
            <div class="relative z-10 bg-white w-full max-w-5xl rounded-2xl shadow-lg flex flex-col max-h-[calc(100vh-2rem)] md:max-h-[calc(100vh-4rem)]">
              <div class="flex items-center justify-between px-5 py-4 border-b flex-shrink-0">
                 <h3 class="font-semibold">Phân công sinh viên vào giảng viên phản biện</h3>
                 <button class="text-slate-500 hover:text-slate-700" data-close-assign><i class="ph ph-x"></i></button>
               </div>
              <!-- Vùng nội dung cuộn -->
              <div class="p-5 overflow-y-auto">
                 <!-- Lịch bảo vệ: Ngày - Giờ - Phòng -->
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                   <div>
                     <label class="text-sm text-slate-600">Ngày</label>
                     <input id="assignDate" type="date"
                            class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                   </div>
                   <div>
                     <label class="text-sm text-slate-600">Giờ</label>
                     <input id="assignTime" type="time"
                            class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                   </div>
                   <div>
                     <label class="text-sm text-slate-600">Phòng</label>
                     <input id="assignRoom" type="text" placeholder="VD: B203"
                            class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                   </div>
                 </div>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                   <!-- Bảng giảng viên -->
                   <div class="border rounded-xl overflow-hidden">
                     <div class="px-4 py-3 border-b font-medium bg-slate-50">Giảng viên trong hội đồng</div>
                     <div class="overflow-x-auto">
                       <table class="w-full text-sm">
                         <thead>
                           <tr class="text-left text-slate-500 border-b">
                             <th class="py-2 px-3 w-10"></th>
                             <th class="py-2 px-3">Họ tên</th>
                             <th class="py-2 px-3">Chức vụ</th>
                           </tr>
                         </thead>
                         <tbody id="cmTbody" class="divide-y divide-slate-200">
                           @php
                             $roleMap = ['5'=>'Chủ tịch','4'=>'Thư ký','3'=>'Ủy viên 1','2'=>'Ủy viên 2','1'=>'Ủy viên 3'];
                           @endphp
                           @foreach(($council->council_members ?? collect())->sortByDesc('role') as $m)
                             @php
                               $cmId = $m->id;
                               $cmName = $m->supervisor->teacher->user->fullname ?? 'N/A';
                               $cmRole = $roleMap[(string)$m->role] ?? 'Thành viên';
                             @endphp
                             <tr class="hover:bg-slate-50 cursor-pointer" data-cm-id="{{ $cmId }}">
                               <td class="py-2 px-3">
                                 <input type="checkbox" class="cm-check" value="{{ $cmId }}">
                               </td>
                               <td class="py-2 px-3 font-medium text-slate-800">{{ $cmName }}</td>
                               <td class="py-2 px-3 text-slate-600">{{ $cmRole }}</td>
                             </tr>
                           @endforeach
                         </tbody>
                       </table>
                     </div>
                     <div class="px-4 py-2 border-t text-xs text-slate-500">Chọn 1 giảng viên (click dòng để chọn).</div>
                   </div>

                  <!-- Bảng sinh viên -->
                   <div class="border rounded-xl overflow-hidden">
                     <div class="px-4 py-3 border-b font-medium bg-slate-50">Sinh viên thuộc hội đồng</div>
                     <div class="overflow-x-auto">
                       <table class="w-full text-sm">
                         <thead>
                           <tr class="text-left text-slate-500 border-b">
                             <th class="py-2 px-3 w-10"></th>
                             <th class="py-2 px-3">MSSV</th>
                             <th class="py-2 px-3">Họ tên</th>
                             <th class="py-2 px-3">Đề tài</th>
                           </tr>
                         </thead>
                         <tbody id="cpTbody" class="divide-y divide-slate-200">
                           @foreach (($council->council_projects ?? collect()) as $cp)
                             @php
                               $cpId = $cp->id;
                               $svCode = $cp->assignment->student->student_code ?? 'N/A';
                               $svName = $cp->assignment->student->user->fullname ?? 'N/A';
                               $topic  = $cp->assignment->project->name ?? 'N/A';
                             @endphp
                             <tr class="hover:bg-slate-50 cursor-pointer" data-cp-id="{{ $cpId }}">
                               <td class="py-2 px-3">
                                 <input type="checkbox" class="cp-check" value="{{ $cpId }}">
                               </td>
                               <td class="py-2 px-3 font-medium text-slate-800">{{ $svCode }}</td>
                               <td class="py-2 px-3">{{ $svName }}</td>
                               <td class="py-2 px-3 text-slate-600">{{ $topic }}</td>
                             </tr>
                           @endforeach
                         </tbody>
                       </table>
                     </div>
                     <div class="px-4 py-2 border-t text-xs text-slate-500">Chọn 1 hoặc nhiều sinh viên (click dòng để chọn).</div>
                   </div>
                 </div>
               </div>
              <div class="px-5 py-4 border-t flex items-center justify-end gap-2 flex-shrink-0 bg-white">
                 <button class="px-3 py-1.5 rounded-lg border text-sm hover:bg-slate-50" data-close-assign>Đóng</button>
                 <button id="btnDoAssign"
                         class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm">
                   <i class="ph ph-check"></i> Phân công
                 </button>
               </div>
             </div>
           </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    // Sidebar/profile wiring
    (function(){
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const h=document.querySelector('header'); const m=document.querySelector('main');
        if(c){ html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]') }
        else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]')}
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
      const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    })();

    // Chỉ giữ logic cho modal phân công

    // Modal helpers
    const assignModal = document.getElementById('assignModal');
    function openAssignModal(){
      assignModal?.classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
    }
    function closeAssignModal(){
      assignModal?.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }
    document.getElementById('btnOpenAssignModal')?.addEventListener('click', (e)=>{
      e.preventDefault();
      openAssignModal();
    });
    assignModal?.addEventListener('click', (e)=>{
      if(e.target.closest('[data-close-assign]')) closeAssignModal();
    });

    // Toggle bằng click row
    function setupRowToggle(containerSel, checkboxCls, single=false){
      const tbody = document.querySelector(containerSel);
      if(!tbody) return;
      tbody.addEventListener('click', (e)=>{
        const tr = e.target.closest('tr');
        if(!tr) return;
        const cb = tr.querySelector('input.'+checkboxCls);
        if(!cb) return;
        if (single) {
          tbody.querySelectorAll('input.'+checkboxCls).forEach(x => x.checked = false);
          cb.checked = true;
        } else {
          cb.checked = !cb.checked;
        }
      });
    }
    // Giảng viên: chọn 1
    setupRowToggle('#cmTbody', 'cm-check', true);
    // Sinh viên: chọn nhiều
    setupRowToggle('#cpTbody', 'cp-check', false);

    function getSelectedCM(){
      const el = document.querySelector('#cmTbody input.cm-check:checked');
      return el ? el.value : null;
    }
    function getSelectedCPs(){
      return Array.from(document.querySelectorAll('#cpTbody input.cp-check:checked')).map(x=>x.value);
    }

    // Gọi API phân công
    document.getElementById('btnDoAssign')?.addEventListener('click', async ()=>{
      const cmId = getSelectedCM();
      const cpIds = getSelectedCPs();
      const date = (document.getElementById('assignDate')?.value || '').trim();
      const time = (document.getElementById('assignTime')?.value || '').trim();
      const room = (document.getElementById('assignRoom')?.value || '').trim();
      if(!cmId){ alert('Vui lòng chọn 1 giảng viên trong hội đồng.'); return; }
      if(!cpIds.length){ alert('Vui lòng chọn ít nhất 1 sinh viên.'); return; }
      // Có thể yêu cầu bắt buộc nếu DB của bạn không cho null
      // if (!room) { alert('Vui lòng nhập phòng.'); return; }

      const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
      const url = `{{ route('web.teacher.councils.assign_reviewer', ['council' => $council->id]) }}`;
      const btn = document.getElementById('btnDoAssign');
      const old = btn.innerHTML; btn.disabled = true; btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> Đang phân công...';
      try {
        const res = await fetch(url, {
          method: 'POST',
          headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': token,'X-Requested-With':'XMLHttpRequest' },
          body: JSON.stringify({ council_member_id: cmId, council_project_ids: cpIds, date, time, room })
        });
        const data = await res.json().catch(()=> ({}));
        if(!res.ok || data.ok === false){ alert(data.message || 'Phân công thất bại.'); btn.disabled=false; btn.innerHTML=old; return; }
        closeAssignModal();
        location.reload();
      } catch(err){
        alert('Lỗi mạng, vui lòng thử lại.');
        btn.disabled = false; btn.innerHTML = old;
      }
    });
  </script>
</body>
</html>
