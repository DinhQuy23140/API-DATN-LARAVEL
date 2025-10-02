<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Trưởng bộ môn - Các đợt đồ án</title>
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
  <div class="flex min-h-screen">
    <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-indigo-600 text-white"><i class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Head</div>
          <div class="text-xs text-slate-500">Bảng điều khiển</div>
        </div>
      </div>
      @php
        $isThesisOpen = true;
      @endphp
      <nav class="flex-1 overflow-y-auto p-3">
        <a href="{{ route('web.head.overview') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.head.overview') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <i class="ph ph-gauge"></i><span class="sidebar-label">Tổng quan</span>
        </a>
        <a href="{{ route('web.head.profile', ['teacherId' => $teacherId]) }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.head.profile') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <i class="ph ph-user"></i><span class="sidebar-label">Hồ sơ</span>
        </a>
        <a href="{{ route('web.head.research') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.head.research') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <i class="ph ph-flask"></i><span class="sidebar-label">Nghiên cứu</span>
        </a>
        <a href="{{ route('web.head.students') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.head.students') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span>
        </a>
        <button type="button" id="toggleThesisMenu"
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg mt-3 bg-slate-100 font-semibold">
          <span class="flex items-center gap-3">
            <i class="ph ph-graduation-cap"></i>
            <span class="sidebar-label">Học phần tốt nghiệp</span>
          </span>
          <i id="thesisCaret" class="ph ph-caret-down transition-transform rotate-180"></i>
        </button>
        <div id="thesisSubmenu" class="mt-1 pl-3 space-y-1">
          <a href="{{ route('web.head.thesis_internship') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.head.thesis_internship') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span>
          </a>
          <a href="{{ route('web.head.thesis_rounds') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold" aria-current="page">
            <i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span>
          </a>
        </div>
      </nav>
      <div class="p-3 border-t border-slate-200">
        <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg">
          <i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span>
        </button>
      </div>
    </aside>

    <div class="flex-1 h-screen overflow-hidden flex flex-col">
      <!-- Header + Main -->
      <header class="fixed left-0 md:left-[260px] right-0 top-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-20">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Các đợt đồ án</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Các đợt đồ án</span>
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
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Hồ sơ</a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
            <form action="{{ route('web.auth.logout') }}" method="POST" class="hidden" id="logout-form">@csrf</form>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto pt-20 px-4 md:px-6 pb-10 space-y-6">
        <div class="max-w-7xl mx-auto space-y-6">
          <section class="bg-white border rounded-xl p-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
              <div class="flex gap-2 flex-wrap items-center">
                <div class="relative">
                  <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                  <input id="searchInput" class="pl-8 pr-8 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm mã / tên / năm / đợt" />
                  <button id="clearSearch" class="absolute right-1 top-1/2 -translate-y-1/2 h-6 w-6 grid place-items-center text-slate-400 hover:text-slate-600">
                    <i class="ph ph-x text-xs"></i>
                  </button>
                </div>
                <select id="yearFilter" class="border border-slate-200 rounded text-sm px-2 py-2">
                  <option value="">Năm</option>
                </select>
                <select id="phaseFilter" class="border border-slate-200 rounded text-sm px-2 py-2">
                  <option value="">Đợt</option>
                </select>
                <button id="resetFilter" class="px-3 py-2 text-sm rounded border border-slate-200 hover:bg-slate-50 flex items-center gap-1">
                  <i class="ph ph-arrow-counter-clockwise"></i> Reset
                </button>
              </div>
              <a href="{{ route('web.head.thesis_rounds') }}#create" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm flex items-center gap-1 shadow-sm hover:bg-indigo-700">
                <i class="ph ph-plus"></i> Tạo đợt
              </a>
            </div>
            <!-- Grid cards -->
            <div id="rGrid" class="flex flex-col gap-4">
              @foreach ($rows as $row)
                @php
                  $code = $row->id;
                  $yearName = $row->academy_year->year_name ?? '—';
                  $phase = $row->stage ?? '—';
                  $status = $row->status ?? 'Đang diễn ra';
                  $students = $row->assignments->count();
                  $startDisp = $row->start_date;
                  $endDisp = $row->end_date;
                  $statusMap = [
                    'Đang diễn ra' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
                    'Sắp diễn ra'  => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
                    'Đã kết thúc'  => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200',
                    'Đóng'         => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200',
                  ];
                  $statusClass = $statusMap[$status] ?? 'bg-slate-100 text-slate-600 ring-1 ring-slate-200';
                @endphp

                <div class="round-card relative flex flex-col rounded-xl border border-slate-200 bg-white shadow-sm hover:shadow-md hover:border-indigo-300 transition group"
                    data-code="{{ $code }}"
                    data-name="Năm học {{ $yearName }}"
                    data-year="{{ $yearName }}"
                    data-phase="{{ $phase }}"
                    data-status="{{ $status }}">
                  <!-- Accent bar -->
                  <div class="h-1 w-full rounded-t bg-gradient-to-r from-indigo-500 via-sky-500 to-cyan-400"></div>

                  <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                      <h3 class="font-semibold text-base group-hover:text-indigo-600 transition">
                        {{ "Năm học " . $yearName }}
                      </h3>
                      <p class="text-xs text-slate-500 mt-0.5">Mã: <span class="font-medium">{{ $code }}</span></p>
                      <p class="text-xs text-slate-500">Đợt: {{ $phase }}</p>
                    </div>
                    <span class="px-2 py-1 rounded-full text-[11px] font-medium {{ $statusClass }}">
                      {{ $status }}
                    </span>
                  </div>

                  <div class="flex flex-wrap gap-6 px-5 pb-4">
                    <div class="text-center">
                      <div class="text-sm font-semibold text-indigo-600">{{ $students }}</div>
                      <div class="text-[10px] uppercase tracking-wide text-slate-500">SV</div>
                    </div>
                    <div class="text-center">
                      <div class="text-sm font-medium text-slate-700">{{ $startDisp }}</div>
                      <div class="text-[10px] uppercase tracking-wide text-slate-500">Bắt đầu</div>
                    </div>
                    <div class="text-center">
                      <div class="text-sm font-medium text-slate-700">{{ $endDisp }}</div>
                      <div class="text-[10px] uppercase tracking-wide text-slate-500">Kết thúc</div>
                    </div>
                  </div>

                  <div class="flex items-center justify-between border-t px-5 py-3">
                    <div class="text-[11px] text-slate-400">Cập nhật: —</div>
                    <div class="flex items-center gap-1">
                      <a href="{{ route('web.head.thesis_round_detail', ['termId' => $code]) }}"
                        class="inline-flex items-center gap-1 px-2 py-1 border border-slate-200 rounded text-xs hover:bg-slate-50">
                        <i class="ph ph-info"></i> Chi tiết
                      </a>
                      <a href="{{ route('web.head.thesis_round_detail', ['termId' => $code]) }}#edit"
                        class="inline-flex items-center gap-1 px-2 py-1 border border-slate-200 rounded text-xs hover:bg-slate-50">
                        <i class="ph ph-pencil-simple"></i> Sửa
                      </a>
                      <form method="POST"
                            action="{{ route('web.head.thesis_rounds') }}"
                            onsubmit="return confirm('Xóa đợt {{ $code }}?')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="code" value="{{ $code }}">
                        <button class="inline-flex items-center gap-1 px-2 py-1 border border-rose-200 text-rose-600 rounded text-xs hover:bg-rose-50">
                          <i class="ph ph-trash"></i> Xóa
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <div id="emptyMsg" class="hidden py-8 text-center text-sm text-slate-500">Không có dữ liệu phù hợp.</div>
          </section>
        </div>
      </main>
    </div>
  </div>

  <script>
    (function sidebarInit(){
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const h=document.querySelector('header'); const m=document.querySelector('main');
        if(c){
          html.classList.add('sidebar-collapsed');
          h?.classList.add('md:left-[72px]'); h?.classList.remove('md:left-[260px]');
          m?.classList.add('md:pl-[72px]');    m?.classList.remove('md:pl-[260px]');
        } else {
          html.classList.remove('sidebar-collapsed');
            h?.classList.remove('md:left-[72px]'); h?.classList.add('md:left-[260px]');
            m?.classList.remove('md:pl-[72px]');   m?.classList.add('md:pl-[260px]');
        }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{
        const c=!html.classList.contains('sidebar-collapsed');
        setCollapsed(c);
        localStorage.setItem('head_sidebar', c?1:0);
      });
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('head_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      // Profile dropdown
      const profileBtn=document.getElementById('profileBtn');
      const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', e=>{
        if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden');
      });

      // Toggle submenu thesis
      const btn = document.getElementById('toggleThesisMenu');
      const menu = document.getElementById('thesisSubmenu');
      const caret= document.getElementById('thesisCaret');
      btn?.addEventListener('click', ()=>{
        menu?.classList.toggle('hidden');
        const open = !menu?.classList.contains('hidden');
        caret?.classList.toggle('rotate-180', open);
        btn.classList.toggle('bg-slate-100', open);
        btn.classList.toggle('font-semibold', open);
      });
    })();

    (function filtering(){
      const searchInput = document.getElementById('searchInput');
      const clearBtn    = document.getElementById('clearSearch');
      const yearSel     = document.getElementById('yearFilter');
      const phaseSel    = document.getElementById('phaseFilter');
      const resetBtn    = document.getElementById('resetFilter');
      const cards       = Array.from(document.querySelectorAll('.round-card'));
      const emptyMsg    = document.getElementById('emptyMsg');

      if(!cards.length) return;

      // Build options from dataset (nếu server chưa fill)
      function ensureOptions(select, values, label){
        if(select.options.length > 1) return; // đã có
        const frag=document.createDocumentFragment();
        [...values].sort((a,b)=>a.localeCompare(b,'vi')).forEach(v=>{
          const opt=document.createElement('option');
          opt.value=v; opt.textContent=v;
          frag.appendChild(opt);
        });
        select.appendChild(frag);
      }
      const yearSet  = new Set(cards.map(c=>c.dataset.year).filter(Boolean));
      const phaseSet = new Set(cards.map(c=>c.dataset.phase).filter(Boolean));
      ensureOptions(yearSel, yearSet, 'Năm');
      ensureOptions(phaseSel, phaseSet, 'Đợt');

      function normalize(str){
        return (str||'').toLowerCase().trim();
      }

      function apply(){
        const q   = normalize(searchInput.value);
        const y   = yearSel.value;
        const p   = phaseSel.value;

        let visibleCount=0;
        cards.forEach(card=>{
          const name  = normalize(card.dataset.name);
            const code  = normalize(card.dataset.code);
            const year  = card.dataset.year;
            const phase = card.dataset.phase;

          const okQ = !q || name.includes(q) || code.includes(q) || normalize(year).includes(q) || normalize(phase).includes(q);
          const okY = !y || year === y;
          const okP = !p || phase === p;

          const show = okQ && okY && okP;
          card.style.display = show ? '' : 'none';
          if(show) visibleCount++;
        });

        emptyMsg.classList.toggle('hidden', visibleCount>0);
      }

      // Debounce
      let t;
      searchInput?.addEventListener('input', ()=>{
        clearTimeout(t);
        t=setTimeout(apply, 180);
      });
      yearSel?.addEventListener('change', apply);
      phaseSel?.addEventListener('change', apply);

      clearBtn?.addEventListener('click', ()=>{
        if(!searchInput.value) return;
        searchInput.value='';
        apply();
      });

      resetBtn?.addEventListener('click', e=>{
        e.preventDefault();
        searchInput.value='';
        yearSel.value='';
        phaseSel.value='';
        apply();
      });

      apply();
    })();
  </script>
</body>
</html>
