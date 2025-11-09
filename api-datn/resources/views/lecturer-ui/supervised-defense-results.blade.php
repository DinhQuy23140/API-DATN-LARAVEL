<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kết quả bảo vệ - SV hướng dẫn</title>
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
            <h1 class="text-lg md:text-xl font-semibold">Kết quả bảo vệ - SV hướng dẫn</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-round-detail.html" class="hover:underline text-slate-600">Chi tiết đợt</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Kết quả bảo vệ</span>
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
          <div class="flex items-center justify-between">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchInput" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-80" placeholder="Tìm theo tên/MSSV/hội đồng" />
            </div>
            <a href="thesis-round-detail.html" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại chi tiết đợt</a>
          </div>

          <section class="bg-white border rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
              <h3 class="font-semibold">Kết quả bảo vệ của sinh viên hướng dẫn</h3>
              <div class="text-xs text-slate-500">Nguồn: dữ liệu đã lưu khi chấm tại hội đồng</div>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500 border-b">
                    <th class="py-3 px-3">Sinh viên</th>
                    <th class="py-3 px-3">MSSV</th>
                    <th class="py-3 px-3">Hội đồng</th>
                    <th class="py-3 px-3">Tổng điểm</th>
                    <th class="py-3 px-3">Kết quả</th>
                    <th class="py-3 px-3">Nhận xét</th>
                    <th class="py-3 px-3">Hành động</th>
                  </tr>
                </thead>
                <tbody id="rows"></tbody>
              </table>
            </div>
          </section>
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

    // Mock supervised student list (IDs, names, and default committees if no saved data)
    const supervisedStudents = [
      { id:'20210001', name:'Nguyễn Văn A', committeeId:'CNTT-01' },
      { id:'20210002', name:'Trần Thị B', committeeId:'CNTT-02' },
      { id:'20210003', name:'Lê Văn C', committeeId:'CNTT-01' }
    ];

    function getSavedDefense(studentId){
      try { return JSON.parse(localStorage.getItem(`defense_student_${studentId}`)||'null'); } catch { return null; }
    }

    function pill(result){
      if(result==='Đạt') return "<span class='px-2 py-0.5 rounded-full text-xs bg-emerald-50 text-emerald-700'>Đạt</span>";
      if(result==='Cần bổ sung') return "<span class='px-2 py-0.5 rounded-full text-xs bg-amber-50 text-amber-700'>Cần bổ sung</span>";
      if(result==='Không đạt') return "<span class='px-2 py-0.5 rounded-full text-xs bg-rose-50 text-rose-700'>Không đạt</span>";
      return "<span class='px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700'>Chưa có</span>";
    }

    function render(){
      const q=(document.getElementById('searchInput').value||'').toLowerCase();
      const rows=document.getElementById('rows');
      const data = supervisedStudents.map(s=>{
        const d = getSavedDefense(s.id);
        return {
          ...s,
          committeeId: d?.committeeId || s.committeeId,
          total: typeof d?.total==='number' ? d.total.toFixed(1) : '-',
          result: d?.result || 'Chưa có',
          note: d?.note || ''
        }
      }).filter(s=>{
        return s.name.toLowerCase().includes(q) || s.id.includes(q) || (s.committeeId||'').toLowerCase().includes(q)
      });

      if(!data.length){ rows.innerHTML = "<tr><td class='py-4 px-3 text-slate-500' colspan='7'>Không có dữ liệu.</td></tr>"; return; }

      rows.innerHTML = data.map(s=>`
        <tr class='border-b hover:bg-slate-50 cursor-pointer' data-id='${s.id}' data-name='${s.name}'>
          <td class='py-3 px-3'><a class='text-blue-600 hover:underline' href='supervised-student-detail.html?id=${encodeURIComponent(s.id)}&name=${encodeURIComponent(s.name)}'>${s.name}</a></td>
          <td class='py-3 px-3'>${s.id}</td>
          <td class='py-3 px-3'>${s.committeeId || '-'}</td>
          <td class='py-3 px-3'>${s.total}</td>
          <td class='py-3 px-3'>${pill(s.result)}</td>
          <td class='py-3 px-3'>${s.note || '-'}</td>
          <td class='py-3 px-3'>
            <div class='flex items-center gap-1'>
              <a class='px-2 py-1 border border-slate-200 rounded text-xs hover:bg-slate-50' href='committee-detail.html?id=${encodeURIComponent(s.committeeId || '')}' data-no-row-nav>Xem hội đồng</a>
            </div>
          </td>
        </tr>
      `).join('');

      // Row click -> go to student detail, unless clicking on a link marked to skip
      rows.querySelectorAll('tr[data-id]').forEach(tr=>{
        tr.addEventListener('click', (e)=>{
          if (e.target.closest('[data-no-row-nav]') || e.target.closest('a')) return;
          const id = tr.getAttribute('data-id');
          const name = tr.getAttribute('data-name');
          location.href = `supervised-student-detail.html?id=${encodeURIComponent(id)}&name=${encodeURIComponent(name)}`;
        });
      });
    }

    document.getElementById('searchInput').addEventListener('input', render);
    render();
  </script>
</body>
</html>
