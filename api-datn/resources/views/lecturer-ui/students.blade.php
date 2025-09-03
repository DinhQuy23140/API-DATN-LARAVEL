<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Danh sách SV đăng ký - Giảng viên</title>
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
  <body class="bg-slate-50 text-slate-800">
    @php
    $userName = $user->fullname;
        $avatarUrl = $user->avatar_url
      ?? $user->profile_photo_url
      ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    @endphp
    <div class="flex min-h-screen">
      <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
        <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
          <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-chalkboard-teacher"></i></div>
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
          <a href="{{ route('web.teacher.students', ['supervisorId' => Auth::user()->teacher->supervisor->id]) }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.students') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span>
          </a>
          <button type="button" id="toggleThesisMenu"
                  class="w-full flex items-center justify-between px-3 py-2 rounded-lg mt-3
                         {{ $isThesisOpen ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <span class="flex items-center gap-3"><i class="ph ph-graduation-cap"></i><span class="sidebar-label">Học phần tốt nghiệp</span></span>
            <i id="thesisCaret" class="ph ph-caret-down transition-transform {{ $isThesisOpen ? 'rotate-180' : '' }}"></i>
          </button>
          <div id="thesisSubmenu" class="mt-1 pl-3 space-y-1 {{ $isThesisOpen ? '' : 'hidden' }}">
            <a href="{{ route('web.teacher.thesis_internship') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_internship') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
              <i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span>
            </a>
            <a href="{{ route('web.teacher.thesis_rounds') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_rounds') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
              <i class="ph ph-calendar"></i><span class="sidebar-label">Học phần tốt nghiệp</span>
            </a>
          </div>
        </nav>
        <div class="p-3 border-t border-slate-200">
          <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
        </div>
      </aside>

  <div class="flex-1">
        <header class="fixed left-0 md:left-[260px] right-0 top-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-20">
          <div class="flex items-center gap-3 flex-1">
            <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
            <div>
              <h1 class="text-lg md:text-xl font-semibold">SV đăng ký làm đồ án</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Giảng viên / SV đăng ký</nav>
            </div>
          </div>
          <div class="relative">
            <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
              <img class="h-9 w-9 rounded-full object-cover" src="{{ $avatarUrl }}" alt="avatar" />
              <span class="hidden sm:block text-sm">{{ $user->email }}</span>
              <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
            </button>
            <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
              <a href="{{ route('web.teacher.profile') }}" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50">
                <i class="ph ph-user"></i>Xem thông tin
              </a>
              <a href="#"
                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                 class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600">
                 <i class="ph ph-sign-out"></i>Đăng xuất
              </a>
              <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
                @csrf
              </form>
            </div>
          </div>
        </header>

  <main class="pt-20 px-4 md:px-6 pb-10 space-y-6 ">
          <div class="max-w-6xl mx-auto space-y-6">
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between">
              @php
                $user = Auth::user();
                $assignmentSupervisors = $user->teacher->supervisor->assignment_supervisors;
                //$years = collect($assignmentSupervisors)
                //  ->map(fn($as) => data_get($as, 'assignment.batch_student.project_term.academy_year.year_name'))
                //  ->filter()->unique()->values();
                //$terms = collect($assignmentSupervisors)
                //  ->map(fn($as) => data_get($as, 'assignment.batch_student.project_term.stage'))
                //  ->filter()->unique()->values();
                //if ($terms->isEmpty()) { $terms = collect(['HK1','HK2','HK Hè']); }
              @endphp

              <div class="flex items-center gap-2 flex-wrap">
                <!-- Năm học -->
                <div class="relative">
                  <i class="ph ph-calendar text-slate-400 absolute left-2 top-2.5"></i>
                  <select id="filterYear" class="pl-8 pr-3 py-2 rounded-lg border border-slate-200 text-sm">
                    <option value="">Tất cả năm học</option>
                    @foreach ($years as $y)
                      <option value="{{ $y->year_name }}">{{ $y->year_name }}</option>
                    @endforeach
                  </select>
                </div>
                <!-- Kỳ học -->
                <div class="relative">
                  <i class="ph ph-clock text-slate-400 absolute left-2 top-2.5"></i>
                  <select id="filterTerm" class="pl-8 pr-3 py-2 rounded-lg border border-slate-200 text-sm">
                    <option value="">Tất cả kỳ học</option>
                    @foreach ($terms as $t)
                      <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                  </select>
                </div>
                <!-- Trạng thái -->
                <div class="relative">
                  <i class="ph ph-funnel text-slate-400 absolute left-2 top-2.5"></i>
                  <select id="filterStatus" class="pl-8 pr-3 py-2 rounded-lg border border-slate-200 text-sm">
                    <option value="">Tất cả trạng thái</option>
                    <option value="approved">Đã duyệt</option>
                    <option value="pending">Chưa duyệt</option>
                  </select>
                </div>
              </div>

              <div class="relative">
                <input id="searchInput" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo MSSV, họ tên" />
                <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              </div>
            </div>
            <div class="mt-4 overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500">
                    <th data-sort-key="mssv" data-sort-type="string" class="py-3 px-4 border-b cursor-pointer select-none">MSSV <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="name" data-sort-type="string" class="py-3 px-4 border-b cursor-pointer select-none">Họ tên <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="major" data-sort-type="string" class="py-3 px-4 border-b cursor-pointer select-none">Ngành <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="gpa" data-sort-type="number" class="py-3 px-4 border-b cursor-pointer select-none">Đề tài <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="gpa" data-sort-type="number" class="py-3 px-4 border-b cursor-pointer select-none"> Đợt <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="gpa" data-sort-type="number" class="py-3 px-4 border-b cursor-pointer select-none">Năm học <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="status" data-sort-type="string" class="py-3 px-4 border-b cursor-pointer select-none">Trạng thái <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th class="py-3 px-4 border-b text-right">Hành động</th>
                  </tr>
                </thead>
                <tbody id="tableBody">
                  @foreach ($assignmentSupervisors as $assignmentSupervisor)
                    @php
                      $stage    = data_get($assignmentSupervisor, 'assignment.batch_student.project_term.stage', '');
                      $yearName = data_get($assignmentSupervisor, 'assignment.batch_student.project_term.academy_year.year_name', '');
                      $statusRaw = $assignmentSupervisor->assignment->status ?? 'pending';
                      $statusLabel = $statusRaw === 'approved' ? 'Đã duyệt' : 'Chưa duyệt';
                      $statusClass = $statusRaw === 'approved' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700';
                    @endphp
                    <tr class="hover:bg-slate-50"
                        data-year="{{ $yearName }}"
                        data-term="{{ $stage }}"
                        data-status="{{ strtolower($statusRaw) }}">
                      <td class="py-3 px-4">{{ $assignmentSupervisor->assignment->batch_student->student->student_code }}</td>
                      <td class="py-3 px-4">{{ $assignmentSupervisor->assignment->batch_student->student->user->fullname }}</td>
                      <td class="py-3 px-4">{{ $assignmentSupervisor->assignment->batch_student->student->major }}</td>
                      <td class="py-3 px-4">{{ $assignmentSupervisor->assignment->project->name }}</td>
                      <td class="py-3 px-4">{{ $stage }}</td>
                      <td class="py-3 px-4">{{ $yearName }}</td>
                      <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded-full text-xs {{ $statusClass }}">{{ $statusLabel }}</span>
                      </td>
                      <td class="py-3 px-4 text-right space-x-2">
                        <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600"><i class="ph ph-check"></i> Duyệt</button>
                        <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"><i class="ph ph-x"></i> Từ chối</button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </section>
        </div>
        </main>
      </div>
    </div>

    <script>
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
  function setCollapsed(c){const h=document.querySelector('header');const m=document.querySelector('main'); if(c){html.classList.add('sidebar-collapsed');h.classList.add('md:left-[72px]');h.classList.remove('md:left-[260px]');m.classList.add('md:pl-[72px]');m.classList.remove('md:pl-[260px]');} else {html.classList.remove('sidebar-collapsed');h.classList.remove('md:left-[72px]');h.classList.add('md:left-[260px]');m.classList.remove('md:pl-[72px]');m.classList.add('md:pl-[260px]');}}
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed');setCollapsed(c);localStorage.setItem('lecturer_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      // simple filter
      // document.getElementById('searchInput').addEventListener('input', (e)=>{
      //   const q=e.target.value.toLowerCase();
      //   document.querySelectorAll('#tableBody tr').forEach(tr=> tr.style.display = tr.innerText.toLowerCase().includes(q)?'':'none');
      // });

      // Filters: Year (Năm học) + Stage (Kỳ học) + Search (nếu nhập)
      const fy = document.getElementById('filterYear');
      const ft = document.getElementById('filterTerm');
      const qEl = document.getElementById('searchInput');

      function norm(v){ return (v||'').toString().trim().toLowerCase(); }

      function applyFilters(){
        const year = norm(fy?.value);
        const term = norm(ft?.value);
        const q    = norm(qEl?.value);

        document.querySelectorAll('#tableBody tr').forEach(tr=>{
          const ry = norm(tr.dataset.year);
          const rt = norm(tr.dataset.term);
          const fullname = norm(tr.querySelector('td:nth-child(2)')?.innerText); // cột Họ tên

          const okYear = !year || ry === year;
          const okTerm = !term || rt === term;
          const okQ    = !q || fullname.includes(q); // chỉ so khớp theo họ tên

          tr.style.display = (okYear && okTerm && okQ) ? '' : 'none';
        });
      }

      fy?.addEventListener('change', applyFilters);
      ft?.addEventListener('change', applyFilters);
      qEl?.addEventListener('input', applyFilters);
      applyFilters();

      // sorting
      const sortState = { key:null, dir:1 };
      function getSortValue(tr, key){
        const tds = tr.querySelectorAll('td');
        if(key==='mssv') return (tds[0]?.innerText||'').trim();
        if(key==='name') return (tds[1]?.innerText||'').trim().toLowerCase();
        if(key==='major') return (tds[2]?.innerText||'').trim().toLowerCase();
        if(key==='gpa') return parseFloat(tds[3]?.innerText||'0') || 0;
        if(key==='status') return (tds[4]?.innerText||'').trim().toLowerCase();
        return '';
      }
      function applySort(th){
        const key = th.dataset.sortKey;
        if(!key) return;
        sortState.dir = sortState.key===key ? -sortState.dir : 1;
        sortState.key = key;
        const rows = Array.from(document.querySelectorAll('#tableBody tr')).filter(r=>r.style.display!=='none');
        rows.sort((a,b)=>{
          const va=getSortValue(a,key), vb=getSortValue(b,key);
          if(typeof va==='number' && typeof vb==='number') return (va-vb)*sortState.dir;
          return (va>vb?1:va<vb?-1:0)*sortState.dir;
        });
        const tbody=document.getElementById('tableBody');
        rows.forEach(r=>tbody.appendChild(r));
        document.querySelectorAll('thead th[data-sort-key] i').forEach(i=>{i.className='ph ph-caret-up-down ml-1 text-slate-400';});
        const icon = th.querySelector('i');
        icon.className = sortState.dir===1 ? 'ph ph-caret-up ml-1 text-slate-600' : 'ph ph-caret-down ml-1 text-slate-600';
      }
      document.querySelectorAll('thead th[data-sort-key]').forEach(th=> th.addEventListener('click',()=>applySort(th)));

      // profile dropdown
      const profileBtn=document.getElementById('profileBtn');
      const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

      (function () {
        const btn = document.getElementById('toggleThesisMenu');
        const menu = document.getElementById('thesisSubmenu');
        const caret = document.getElementById('thesisCaret');
        btn?.addEventListener('click', () => {
          menu?.classList.toggle('hidden');
          caret?.classList.toggle('rotate-180');
          btn?.classList.toggle('bg-slate-100');
          btn?.classList.toggle('font-semibold');
        });
      })();
    </script>
  </body>
</html>
