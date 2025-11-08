<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hội đồng của sinh viên</title>
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
            <h1 class="text-lg md:text-xl font-semibold">Hội đồng của sinh viên</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-round-detail.html" class="hover:underline text-slate-600">Chi tiết đợt</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Hội đồng của sinh viên</span>
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
        <div class="max-w-6xl mx-auto space-y-4">

          <section class="bg-white rounded-xl border border-slate-200 p-4">
            <h2 class="font-semibold text-lg mb-2">Thông tin đợt đồ án</h2>
            <div class="text-slate-700 text-sm space-y-2">
              @php
              $stage = $rows->stage;
              $term = $rows->academy_year->name . ' - Học kỳ ' . $rows->stage;
              $semester = ($rows->stage % 2 == 1) ? '1' : '2';
              $date = date('d/m/Y', strtotime($rows->start_date)) . ' - ' . date('d/m/Y', strtotime($rows->end_date));
              @endphp
              <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-md bg-indigo-50 text-indigo-600 grid place-items-center">
                  <i class="ph ph-calendar"></i>
                </div>
                <div><strong class="text-indigo-700">Đợt:</strong> <span class="text-slate-700">{{ $term }}</span></div>
              </div>
              <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-md bg-emerald-50 text-emerald-600 grid place-items-center">
                  <i class="ph ph-book-open"></i>
                </div>
                <div><strong class="text-emerald-700">Năm học:</strong> <span class="text-slate-700">{{ $term }}</span></div>
              </div>
              <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-md bg-amber-50 text-amber-600 grid place-items-center">
                  <i class="ph ph-graduation-cap"></i>
                </div>
                <div><strong class="text-amber-700">Học kỳ:</strong> <span class="text-slate-700">{{ $semester }}</span></div>
              </div>
              <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-md bg-violet-50 text-violet-600 grid place-items-center">
                  <i class="ph ph-clock"></i>
                </div>
                <div><strong class="text-violet-700">Thời gian:</strong> <span class="text-slate-700">{{ $date }}</span></div>
              </div>
            </div>
          </section>

          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="relative w-full md:w-auto">
              <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
              <input id="search" class="pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm w-full md:w-80 focus:outline-none focus:ring-2 focus:ring-sky-200" placeholder="Tìm theo tên/MSSV/hội đồng" />
            </div>
            <a href="thesis-round-detail.html" class="text-sm text-sky-600 hover:underline flex items-center gap-2"><i class="ph ph-caret-left"></i><span>Quay lại chi tiết đợt</span></a>
          </div>

<div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table id="studentTable" class="w-full text-sm border-collapse">
      <thead class="bg-slate-50 border-b sticky top-0">
        <tr class="text-slate-600">
          <th class="py-3 px-4 text-left font-semibold"><i class="ph ph-user mr-2"></i> Sinh viên</th>
          <th class="py-3 px-4 text-left font-semibold"><i class="ph ph-hash mr-2"></i> MSSV</th>
          <th class="py-3 px-4 text-left font-semibold"><i class="ph ph-file-text mr-2"></i> Đề tài</th>
          <th class="py-3 px-4 text-left font-semibold"><i class="ph ph-chalkboard-teacher mr-2"></i> Hội đồng</th>
          <th class="py-3 px-4 text-left font-semibold"><i class="ph ph-calendar mr-2"></i> Lịch bảo vệ</th>
          <th class="py-3 px-4 text-left font-semibold"><i class="ph ph-map-pin mr-2"></i> Phòng</th>
          <th class="py-3 px-4 text-center font-semibold"><i class="ph ph-gear-six mr-2"></i> Hành động</th>
        </tr>
      </thead>
      <tbody id="committeesTableBody" class="divide-y divide-slate-100">
        @php
          $assignments = $rows->assignments;
        @endphp
  @foreach ($assignments as $assignment)
          @php
            $fullname = $assignment->student->user->fullname;
            $student_code = $assignment->student->code;
            $topic = $assignment->project->name ?? 'Chưa có đề tài';
            $council_name = $assignment->council_project->council->name ?? 'Chưa có hội đồng';
            $date = $assignment->council_project->council->date ?? 'Chưa có lịch';
            $room = $assignment->council_project->council->address ?? 'Chưa có phòng';
            $councilId = $assignment->council_project->council->id ?? null;
          @endphp
          <tr class="hover:bg-slate-50 transition group">
            <td class="py-3 px-4 font-medium text-slate-700">
              <a class="text-sky-600 hover:underline font-medium" href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $assignment->student->id, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}">
                {{ $fullname }}
              </a>
              <div class="text-xs text-slate-500 mt-1">{{ $topic }}</div>
            </td>
            <td class="py-3 px-4 text-slate-600">{{ $student_code }}</td>
            <td class="py-3 px-4 text-slate-700">{{ $topic }}</td>
            <td class="py-3 px-4 text-slate-700">{{ $council_name }}</td>
            <td class="py-3 px-4 text-slate-600">{{ $date }}</td>
            <td class="py-3 px-4 text-slate-600">{{ $room }}</td>
            <td class="py-3 px-4 text-center">
              <div class="flex items-center justify-center gap-2">
                <a href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $assignment->student->id, 'termId' => $rows->id, 'supervisorId' => $supervisorId]) }}"
                  class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-sky-50 text-sky-600 hover:bg-sky-100 text-xs font-medium transition">
                  <i class="ph ph-user text-sm"></i>
                  <span class="hidden sm:inline">SV</span>
                </a>
                @if ($councilId)
                <a href="{{ route('web.teacher.committee_detail', ['councilId'=>$councilId, 'termId'=>$rows->id, 'supervisorId' => $supervisorId]) }}"
                  class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 text-slate-600 hover:bg-slate-100 text-xs font-medium transition">
                  <i class="ph ph-chalkboard-teacher text-sm"></i>
                  <span class="hidden sm:inline">Hội đồng</span>
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

          <div id="noResults" class="hidden p-6 text-center text-slate-500">Không tìm thấy kết quả nào.</div>


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
        if(c){ html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); }
        else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]'); }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
      const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    })();
    
    // Debounced, diacritic-insensitive search for committees table
    (function(){
      const input = document.getElementById('search');
      const tbody = document.getElementById('committeesTableBody');
      const noResults = document.getElementById('noResults');
      if(!input || !tbody) return;
      let timer = null;
      function normalize(s){ return (s||'').toString().toLowerCase().normalize('NFD').replace(/\p{Diacritic}/gu,''); }
      input.addEventListener('input', ()=>{
        clearTimeout(timer);
        timer = setTimeout(()=>{
          const q = normalize(input.value.trim());
          let visible = 0;
          for(const row of Array.from(tbody.querySelectorAll('tr'))){
            const text = normalize(row.textContent || '');
            const match = q === '' || text.indexOf(q) !== -1;
            row.style.display = match ? '' : 'none';
            if(match) visible++;
          }
          if(visible === 0){ noResults.classList.remove('hidden'); }
          else { noResults.classList.add('hidden'); }
        }, 220);
      });
    })();
  </script>
</body>
</html>
