<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hội đồng chấm thi của tôi</title>
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
          <a href="{{ route('web.teacher.thesis_rounds', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold {{ $isThesisRoundsActive ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
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
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Hội đồng chấm thi của tôi</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-round-detail.html" class="hover:underline text-slate-600">Chi tiết đợt</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Hội đồng của tôi</span>
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

          <div class="flex items-center justify-end">
            <a href="thesis-round-detail.html" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại chi tiết đợt</a>
          </div>

          <section class="bg-white rounded-xl border border-slate-200 p-4">
            <h2 class="font-semibold text-lg mb-2">Thông tin đợt đồ án</h2>
            <div class="text-slate-700 text-sm space-y-1">
              @php
              $stage = $term->stage;
              $academicYear = $term->academy_year->year_name ?? 'N/A';
              $semester = $term->stage;
              $date = date('d/m/Y', strtotime($term->start_date)) . ' - ' . date('d/m/Y', strtotime($term->end_date));
              @endphp
              <div><strong>Đợt:</strong> {{ $stage }} </div>
              <div><strong>Năm học:</strong> {{ $academicYear }} </div>
              <div><strong>Học kỳ:</strong> {{ $semester }} </div>
              <div><strong>Thời gian:</strong> {{ $date }} </div>
            </div>
          </section>

          <div class="flex items-center justify-between">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchInput" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-80" placeholder="Tìm theo mã/tên hội đồng" />
            </div>
          </div>

          <div id="committeesWrap" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @foreach ($coucilMenbers as $coucilMenber)
              @php
                $name = $coucilMenber->council->name ?? 'N/A';
                $code = $coucilMenber->council->code ?? 'N/A';
                $date = $coucilMenber->council->date ?? 'Chưa có lịch';
                $room = $coucilMenber->council->address ?? 'Chưa có phòng';
                $listRole = [5 => 'Chủ tịch', 4 => 'Thư ký', 3 => 'Ủy viên 1', 2 => 'Ủy viên 2', 1 => 'Ủy viên 3'];
                $role = $listRole[$coucilMenber->role];
                $department = $coucilMenber->council->department->name ?? 'N/A';
                $faculty = "Công nghệ thông tin"; // Placeholder, replace with actual faculty if available
                $description = $coucilMenber->council->description;
              @endphp
              <div class="bg-white border rounded-2xl p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between h-full">
                <div class="space-y-3">
                  <!-- Mã hội đồng -->
                  <div class="flex items-center gap-2 text-sm text-slate-600">
                    <i class="ph ph-identification-badge text-slate-400"></i>
                    <span>Mã hội đồng: <span class="font-semibold text-slate-800">{{ $code }}</span></span>
                  </div>

                  <!-- Tên hội đồng -->
                  <h2 class="font-bold text-lg text-slate-900">{{ $name }}</h2>

                  <!-- Ngày -->
                  <div class="flex items-center gap-2 text-sm">
                    <i class="ph ph-calendar text-sky-500"></i>
                    <span class="text-sky-600 font-semibold">{{ $date }}</span>
                  </div>

                  <!-- Phòng -->
                  <div class="flex items-center gap-2 text-sm">
                    <i class="ph ph-map-pin text-pink-500"></i>
                    <span class="text-pink-600 font-semibold">{{ $room }}</span>
                  </div>

                  <!-- Khoa -->
                  <div class="flex items-center gap-2 text-sm">
                    <i class="ph ph-buildings text-violet-500"></i>
                    <span>Khoa: <span class="font-semibold text-violet-600">{{ $faculty ?? 'N/A' }}</span></span>
                  </div>

                  <!-- Bộ môn -->
                  <div class="flex items-center gap-2 text-sm">
                    <i class="ph ph-chalkboard-teacher text-teal-500"></i>
                    <span>Bộ môn: <span class="font-semibold text-teal-600">{{ $department ?? 'N/A' }}</span></span>
                  </div>

                  <!-- Mô tả -->
                  <p class="text-sm text-slate-500 leading-snug">
                    {{ $description ?? 'Không có mô tả' }}
                  </p>
                </div>

                <!-- Vai trò & hành động -->
                <div class="flex items-center justify-between mt-5">
                  <div class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium 
                              bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="ph ph-user-circle"></i>
                    <span>Vai trò: {{ $role }}</span>
                  </div>

                  <a href="{{ route('web.teacher.committee_detail', ['councilId' => $coucilMenber->council_id, 'termId'=>$termId, 'supervisorId' => $supervisorId]) }}"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition flex items-center gap-2 shadow-sm">
                    <i class="ph ph-eye"></i> Xem chi tiết
                  </a>
                </div>
              </div>
            @endforeach
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

  document.getElementById("searchInput").addEventListener("input", function () {
    const searchValue = this.value.toLowerCase().trim();
    const cards = document.querySelectorAll("#committeesWrap > div");

    cards.forEach(card => {
      // Lấy toàn bộ text trong card
      const text = card.innerText.toLowerCase();

      if (text.includes(searchValue)) {
        card.style.display = "block"; // hiện
      } else {
        card.style.display = "none"; // ẩn
      }
    });
  });

  </script>
</body>
</html>
