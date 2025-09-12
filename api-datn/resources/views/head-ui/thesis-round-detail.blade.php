<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Trưởng bộ môn - Chi tiết đợt đồ án</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
<style>
  body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
  html, body { height:100%; }
  body { overflow:hidden; } /* Khóa cuộn toàn trang, chỉ main cuộn */
  .sidebar-collapsed .sidebar-label { display:none; }
  .sidebar-collapsed .sidebar { width:72px; }
  .sidebar { width:260px; }
  .step-active{background:#4f46e5;color:#fff;}
  .step-done{background:#e0e7ff;color:#4338ca;}
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
  <div class="flex h-screen">
    <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all overflow-hidden">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-indigo-600 text-white"><i class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Head</div>
          <div class="text-xs text-slate-500">Bảng điều khiển</div>
        </div>
      </div>
      @php
        $isThesisOpen = request()->routeIs('web.head.thesis_internship')
          || request()->routeIs('web.head.thesis_rounds')
          || request()->routeIs('web.head.thesis_round_detail');
      @endphp
      <nav class="flex-1 p-3">
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
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg mt-3
                       {{ $isThesisOpen ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <span class="flex items-center gap-3">
            <i class="ph ph-graduation-cap"></i>
            <span class="sidebar-label">Học phần tốt nghiệp</span>
          </span>
          <i id="thesisCaret" class="ph ph-caret-down transition-transform {{ $isThesisOpen ? 'rotate-180' : '' }}"></i>
        </button>
        <div id="thesisSubmenu" class="mt-1 pl-3 space-y-1 {{ $isThesisOpen ? '' : 'hidden' }}">
          <a href="{{ route('web.head.thesis_internship') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.head.thesis_internship') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span>
          </a>
          <a href="{{ route('web.head.thesis_rounds') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.head.thesis_rounds') || request()->routeIs('web.head.thesis_round_detail') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
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

    <div class="flex-1 h-screen flex flex-col">
      <!-- Header + Main giữ nguyên -->
      <header class="fixed left-0 md:left-[260px] right-0 top-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-20">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Chi tiết đợt</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Các đợt</a>
              <span class="mx-1">/</span>
              <span id="breadcrumbId" class="text-slate-500">...</span>
            </nav>
          </div>
        </div>
        <div class="relative">
          <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
            <img class="h-9 w-9 rounded-full object-cover" src="https://i.pravatar.cc/100?img=10" alt="avatar" />
            <div class="hidden sm:block text-left">
              <div class="text-sm font-semibold leading-4">ThS. Nguyễn Văn H</div>
              <div class="text-xs text-slate-500">head@uni.edu</div>
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
      <main class="flex-1 pt-20 px-4 md:px-6 pb-10 space-y-6 overflow-y-auto">
        <div class="max-w-6xl mx-auto space-y-6">
          <!-- Round Info -->
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between">
              <div>
                <div class="text-sm text-slate-500">Mã đợt: <span class="font-medium text-slate-700" id="rId">ROUND</span></div>
                <h2 class="font-semibold text-lg mt-1" id="rName">Tên đợt</h2>
                <div class="text-sm text-slate-600" id="rRange">--/--/---- - --/--/----</div>
              </div>
              <div class="text-right text-sm" id="roundSummary">
                <div class="text-slate-500">Tổng quan</div>
                <div class="font-medium text-indigo-600" id="summaryMain">0 SV • 0 đề tài</div>
                <div class="text-xs text-slate-500 mt-1" id="summarySub">0 hội đồng • 0 GV</div>
              </div>
            </div>
          </section>

          <!-- Timeline -->
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-6">
              <h3 class="font-semibold">Tiến độ giai đoạn</h3>
              <div class="flex items-center gap-2 text-sm">
                <span class="font-medium" id="progressText">0%</span>
                <div class="w-40 h-2 rounded-full bg-slate-100 overflow-hidden">
                  <div class="h-full bg-indigo-600" id="progressBar" style="width:0%"></div>
                </div>
              </div>
            </div>
            <!-- Horizontal Timeline -->
            <div class="relative" id="timelineWrapper">
              <div class="absolute top-6 left-8 right-8 h-0.5 bg-slate-200">
                <div class="h-full bg-indigo-600" id="timelineProgress" style="width:0%"></div>
              </div>
              <div id="timelineStages" class="grid grid-cols-8 gap-4 relative"></div>
            </div>
            <!-- Details Panel -->
            <div class="mt-8 p-6 bg-slate-50 rounded-lg" id="timelineDetails">
              <div id="stageContent" class="text-center text-slate-500">
                <i class="ph ph-cursor-click text-2xl mb-2"></i>
                <p>Click vào một giai đoạn để xem chi tiết.</p>
              </div>
            </div>
            <!-- Legend -->
            <div class="mt-6 text-xs text-slate-500 flex flex-wrap gap-4">
              <span class="inline-flex items-center gap-1"><span class="h-2.5 w-2.5 rounded-full bg-emerald-600"></span>Hoàn thành</span>
              <span class="inline-flex items-center gap-1"><span class="h-2.5 w-2.5 rounded-full bg-indigo-600"></span>Đang diễn ra</span>
              <span class="inline-flex items-center gap-1"><span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>Sắp tới</span>
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>

  <script>
    (function(){
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const h=document.querySelector('header'); const m=document.querySelector('main');
        if(c){ html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); }
        else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]'); }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('head_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('head_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
    })();
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
<!-- Data -->
<script>
// ================= TIMELINE & ROUND DETAIL SCRIPT =================
(function(){
  // Breadcrumb: đổi sang route nếu có
  const breadcrumbRoot = document.querySelector('nav.text-xs a[href="overview.html"]');
  if(breadcrumbRoot){ breadcrumbRoot.setAttribute('href', "{{ route('web.head.overview') }}"); }
  const breadcrumbRounds = document.querySelector('nav.text-xs a[href="thesis-rounds.html"]');
  if(breadcrumbRounds){ breadcrumbRounds.setAttribute('href', "{{ route('web.head.thesis_rounds') }}"); }

  // Lấy id đợt (nếu dùng param ?id=)
  const params = new URLSearchParams(location.search);
  const roundId = params.get('id') || 'R2025A';

  // Dữ liệu demo (sau này thay bằng fetch API)
  const round = {
    id: roundId,
    name: 'Đợt ' + roundId,
    start: '05/01/2025',
    end: '30/06/2025',
    students: 230,
    topics: 210,
    committees: 12,
    lecturers: 58
  };

  const stages = [
    { id:1, title:'Đăng ký đề tài',     status:'done'    },
    { id:2, title:'Phân công GVHD',     status:'active'  },
    { id:3, title:'Nộp đề cương',       status:'upcoming'},
    { id:4, title:'Phản biện đề cương', status:'upcoming'},
    { id:5, title:'Theo dõi tiến độ',   status:'upcoming'},
    { id:6, title:'Nộp báo cáo',        status:'upcoming'},
    { id:7, title:'Phân công / Bảo vệ', status:'upcoming'},
    { id:8, title:'Tổng kết',           status:'upcoming'},
  ];

  function initRoundInfo(){
    const set = (id, val) => { const el=document.getElementById(id); if(el) el.textContent = val; };
    set('breadcrumbId', round.id);
    set('rId', round.id);
    set('rName', round.name);
    set('rRange', `${round.start} - ${round.end}`);
    set('summaryMain', `${round.students} SV • ${round.topics} đề tài`);
    set('summarySub', `${round.committees} hội đồng • ${round.lecturers} GV`);
  }

  function renderTimeline(){
    const container = document.getElementById('timelineStages');
    if(!container) return;
    const doneCount   = stages.filter(s=>s.status==='done').length;
    const activeCount = stages.filter(s=>s.status==='active').length;
    const progressPct = Math.round(((doneCount + activeCount*0.5)/stages.length)*100);

    const progressText = document.getElementById('progressText');
    const progressBar  = document.getElementById('progressBar');
    const lineProgress = document.getElementById('timelineProgress');
    if(progressText)  progressText.textContent = progressPct + '%';
    if(progressBar)   progressBar.style.width  = progressPct + '%';
    if(lineProgress)  lineProgress.style.width = progressPct + '%';

    container.innerHTML = stages.map(s=>{
      let circleClass='bg-slate-300';
      let statusLabel='Sắp tới';
      let textColor='text-slate-600';
      if(s.status==='done'){ circleClass='bg-emerald-600'; statusLabel='Hoàn thành'; textColor='text-emerald-600'; }
      else if(s.status==='active'){ circleClass='bg-indigo-600'; statusLabel='Đang diễn ra'; textColor='text-indigo-600'; }
      return `<div class="timeline-stage cursor-pointer select-none" data-stage="${s.id}">
        <div class="w-12 h-12 mx-auto ${circleClass} rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">${s.id}</div>
        <div class="text-center mt-2">
          <div class="text-xs font-medium text-slate-900">${s.title}</div>
          <div class="text-xs ${textColor} mt-1">${statusLabel}</div>
        </div>
      </div>`;
    }).join('');

    container.querySelectorAll('.timeline-stage').forEach(el=>{
      el.addEventListener('click', ()=>{
        const stageNum = parseInt(el.dataset.stage);
        showStageDetails(stageNum);
      });
    });
  }

  // ===== DEMO DATA CHO CÁC PANEL (sẽ thay bằng fetch API sau) =====
  const outlineSubmissions = [
    { name:'Nguyễn Văn A', mssv:'20210001', topic:'Hệ thống quản lý thư viện', status:'approved', date:'02/08/2025' },
    { name:'Trần Thị B',   mssv:'20210002', topic:'Ứng dụng quản lý công việc', status:'submitted', date:'03/08/2025' },
    { name:'Lê Văn C',     mssv:'20210003', topic:'Hệ thống đặt lịch khám', status:'pending', date:'—' }
  ];
  const weeklyLogs = [
    { name:'Nguyễn Văn A', mssv:'20210001', week:'Tuần 1', status:'graded', updated:'02/08/2025' },
    { name:'Trần Thị B',   mssv:'20210002', week:'Tuần 1', status:'submitted', updated:'03/08/2025' },
    { name:'Lê Văn C',     mssv:'20210003', week:'Tuần 1', status:'none', updated:'—' }
  ];
  const finalReports = [
    { name:'Nguyễn Văn A', mssv:'20210001', topic:'Hệ thống quản lý thư viện', status:'submitted', date:'12/08/2025' },
    { name:'Trần Thị B',   mssv:'20210002', topic:'Ứng dụng quản lý công việc', status:'none', date:'—' },
    { name:'Lê Văn C',     mssv:'20210003', topic:'Hệ thống đặt lịch khám', status:'submitted', date:'13/08/2025' }
  ];
  const committees = [
    { name:'Nguyễn Văn A', mssv:'20210001', committee:'CNTT-01', time:'20/08/2025 • 08:00', room:'P.A203',
      members:'Chủ tịch: PGS.TS. Trần Văn B; Ủy viên: TS. Lê Thị C, TS. Phạm Văn D; Thư ký: ThS. Nguyễn Văn G; Phản biện: TS. Nguyễn Thị E' },
    { name:'Trần Thị B', mssv:'20210002', committee:'CNTT-02', time:'20/08/2025 • 09:30', room:'P.A204',
      members:'Chủ tịch: TS. Phạm Văn D; Ủy viên: TS. Lê Thị C, ThS. Trần Thị F; Thư ký: ThS. Nguyễn Văn G; Phản biện: TS. Nguyễn Thị E' },
    { name:'Lê Văn C', mssv:'20210003', committee:'CNTT-01', time:'20/08/2025 • 08:00', room:'P.A203',
      members:'Chủ tịch: PGS.TS. Trần Văn B; Ủy viên: TS. Lê Thị C, TS. Phạm Văn D; Thư ký: ThS. Nguyễn Văn G; Phản biện: TS. Nguyễn Thị E' }
  ];
  const reviews = [
    { name:'Nguyễn Văn A', mssv:'20210001', committee:'CNTT-01', reviewer:'TS. Nguyễn Thị E', role:'Phản biện', order:'01', time:'20/08/2025 • 08:00' },
    { name:'Trần Thị B', mssv:'20210002', committee:'CNTT-02', reviewer:'TS. Nguyễn Thị E', role:'Phản biện', order:'01', time:'20/08/2025 • 09:30' },
    { name:'Lê Văn C', mssv:'20210003', committee:'CNTT-01', reviewer:'TS. Nguyễn Thị E', role:'Phản biện', order:'02', time:'20/08/2025 • 08:45' }
  ];
  const reviewResults = [
    { name:'Nguyễn Văn A', mssv:'20210001', committee:'CNTT-01', result:'pass', order:'01', defend:'20/08/2025 • 08:00' },
    { name:'Trần Thị B',   mssv:'20210002', committee:'CNTT-02', result:'improve', order:'02', defend:'20/08/2025 • 09:45' },
    { name:'Lê Văn C',     mssv:'20210003', committee:'CNTT-01', result:'pass', order:'03', defend:'20/08/2025 • 10:00' }
  ];
  const defenseResults = [
    { name:'Nguyễn Văn A', mssv:'20210001', committee:'CNTT-01', score:'8.5', result:'pass', note:'Trình bày tốt, trả lời rõ.' },
    { name:'Trần Thị B',   mssv:'20210002', committee:'CNTT-02', score:'6.8', result:'improve', note:'Bổ sung & làm rõ chương 3.' },
    { name:'Lê Văn C',     mssv:'20210003', committee:'CNTT-01', score:'8.0', result:'pass', note:'Hoàn thành tốt yêu cầu.' }
  ];

  const statusBadges = {
    outline: {
      approved:'bg-emerald-50 text-emerald-700',
      submitted:'bg-amber-50 text-amber-700',
      pending:'bg-slate-100 text-slate-700',
      rejected:'bg-rose-50 text-rose-700'
    },
    weekly: {
      graded:'bg-emerald-50 text-emerald-700',
      submitted:'bg-amber-50 text-amber-700',
      need:'bg-rose-50 text-rose-700',
      none:'bg-slate-100 text-slate-700'
    },
    final: {
      submitted:'bg-amber-50 text-amber-700',
      none:'bg-slate-100 text-slate-700'
    },
    reviewResult: {
      pass:'bg-emerald-50 text-emerald-700',
      improve:'bg-amber-50 text-amber-700',
      fail:'bg-rose-50 text-rose-700'
    }
  };

  function pill(label, cls){
    return `<span class="px-2 py-0.5 rounded-full text-[11px] font-medium ${cls}">${label}</span>`;
  }

  function gridWrap(content){
    return `<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">${content}</div>`;
  }

  function studentCard({title, subtitle, lines=[], footer='', badge=''}) {
    return `<div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md transition">
      <div class="flex justify-between items-start gap-3">
        <div>
          <div class="font-medium">${title}</div>
          <div class="text-xs text-slate-500">${subtitle||''}</div>
        </div>
        ${badge||''}
      </div>
      <ul class="mt-3 space-y-1 text-[13px] text-slate-600">
        ${lines.map(l=>`<li class="flex gap-2"><span class="min-w-[72px] text-slate-400">${l.label}</span><span class="flex-1">${l.value}</span></li>`).join('')}
      </ul>
      ${footer ? `<div class="mt-3 pt-3 border-t text-right">${footer}</div>`:''}
    </div>`;
  }

  // Card chức năng hiện đại (bổ sung để tránh lỗi ReferenceError)
  function featureCard({icon='ph-rocket-launch', title='', subtitle='', desc='', href='#', color='indigo'}){
    const palette = {
      indigo:'from-indigo-500 to-indigo-600',
      emerald:'from-emerald-500 to-emerald-600',
      sky:'from-sky-500 to-sky-600',
      amber:'from-amber-500 to-amber-600',
      violet:'from-violet-500 to-fuchsia-600',
      rose:'from-rose-500 to-rose-600',
      cyan:'from-cyan-500 to-cyan-600'
    };
    const grad = palette[color] || palette.indigo;
    return `
      <a href="${href}" class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition duration-300">
        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition">
          <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full blur-2xl bg-gradient-to-br ${grad} opacity-30"></div>
        </div>
        <div class="flex items-start gap-4 relative z-10">
          <div class="h-12 w-12 shrink-0 rounded-xl bg-gradient-to-br ${grad} text-white grid place-items-center shadow-inner shadow-black/10">
            <i class="ph ${icon} text-xl"></i>
          </div>
          <div class="flex-1 min-w-0">
            <h5 class="font-semibold text-slate-800 tracking-tight group-hover:text-slate-900">${title}</h5>
            ${subtitle?`<p class="text-[11px] font-medium text-slate-500 mt-0.5 uppercase tracking-wide">${subtitle}</p>`:''}
            ${desc?`<p class="text-[13px] leading-snug text-slate-600 mt-2 line-clamp-3">${desc}</p>`:''}
            <div class="mt-3">
              <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 group-hover:bg-slate-900 group-hover:text-white text-slate-700 px-3 py-1.5 text-[12px] font-medium transition">
                <i class="ph ph-arrow-right text-xs"></i> Mở
              </span>
            </div>
          </div>
        </div>
      </a>`;
  }

  // ================== HÀM HIỂN THỊ PANEL DẠNG CARD ==================
  function showStageDetails(stageNum){
    const box = document.getElementById('stageContent');
    if(!box) return;

    // Kích hoạt highlight stage đang chọn
    // document.querySelectorAll('.timeline-stage').forEach(el=>{
    //   el.classList.remove('ring-2','ring-indigo-400','shadow-lg');
    // });
    // const activeStage = document.querySelector(`.timeline-stage[data-stage="${stageNum}"]`);
    // if(activeStage){
    //   activeStage.classList.add('ring-2','ring-indigo-400','shadow-lg');
    // }

    switch(stageNum){

      // ================= Stage 1 =================
      case 1: {
        const featureGrid = gridWrap([
          featureCard({
            icon:'ph-inbox',
            title:'Tiếp nhận yêu cầu',
            subtitle:'Xin hướng dẫn',
            desc:'Xem, lọc, duyệt hoặc từ chối các yêu cầu sinh viên gửi tới giảng viên.',
            href:'requests-management.html',
            color:'emerald'
          }),
          featureCard({
            icon:'ph-notebook',
            title:'Đề xuất đề tài',
            subtitle:'Quản lý đề tài',
            desc:'Tạo mới, hiệu chỉnh, tạm đóng hoặc mở lại đề tài đã đề xuất.',
            href:'proposed-topics.html',
            color:'indigo'
          }),
            featureCard({
            icon:'ph-users-three',
            title:'SV được hướng dẫn',
            subtitle:'Theo dõi danh sách',
            desc:'Tra cứu, cập nhật tiến độ, xem thông tin chi tiết từng sinh viên.',
            href:'supervised-students.html',
            color:'sky'
          }),
          featureCard({
            icon:'ph-user-switch',
            title:'Phân công sinh viên',
            subtitle:'Gán GVHD',
            desc:'Phân công / điều chỉnh nhanh sinh viên cho giảng viên phụ trách.',
            href:'{{route('web.head.thesis_round_supervision', ['termId' => $projectTerm->id])}}',
            color:'amber'
          })
        ].join(''));
        box.innerHTML = `
          <h3 class="text-lg font-semibold mb-5">Giai đoạn 01: Khởi động</h3>
          ${featureGrid}
        `;
        break;
      }

      // ================= Stage 2 =================
      case 2: {
        const featureSection = `
          <h4 class="text-sm font-semibold mb-3 text-slate-700 flex items-center gap-2">
            <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
            Chức năng
          </h4>
          ${gridWrap([
            featureCard({
              icon:'ph-files',
              title:'Báo cáo đề cương',
              subtitle:'Theo dõi nộp',
              desc:'Trạng thái & số lần nộp đề cương của các sinh viên được hướng dẫn.',
              href:'supervised-outline-reports.html',
              color:'indigo'
            }),
            featureCard({
              icon:'ph-pencil-line',
              title:'Chấm đề cương',
              subtitle:'Phân công cho bạn',
              desc:'Mở danh sách đề cương được giao để nhập nhận xét & đánh giá.',
              href:'outline-review-assignments.html',
              color:'amber'
            }),
            featureCard({
              icon:'ph-eye-slash',
              title:'Phản biện kín',
              subtitle:'Ẩn GVHD',
              desc:'Quản lý phản biện ẩn giúp đảm bảo tính khách quan.',
              href:'blind-review-assignments.html',
              color:'violet'
            }),
            featureCard({
              icon:'ph-checks',
              title:'Duyệt đề cương',
              subtitle:'Quyết định',
              desc:'Duyệt, từ chối hoặc thu hồi trạng thái đề cương.',
              href:'outline-approvals.html',
              color:'emerald'
            })
          ].join(''))}
        `;

        // (giữ nguyên phần list sinh viên stage 2 cũ – không sửa)
        // ...existing code for building student list (outlineSubmissions)...

        // Thay nội dung đầu phần stage 2: chèn featureSection trước
        // (Copy lại logic lọc cũ, chỉ thay set box.innerHTML):
        const studentCards = outlineSubmissions.map(s=>{
          const badgeClass = {
            approved: pill('Đã duyệt', statusBadges.outline.approved),
            submitted: pill('Đã nộp', statusBadges.outline.submitted),
            pending: pill('Chưa nộp', statusBadges.outline.pending),
            rejected: pill('Từ chối', statusBadges.outline.rejected)
          }[s.status] || pill('—','bg-slate-100 text-slate-600');
          return studentCard({
            title:s.name,
            subtitle:s.mssv,
            badge:badgeClass,
            lines:[
              {label:'Đề tài', value:s.topic},
              {label:'Lần cuối', value:s.date}
            ],
            footer:`<div class="flex gap-2 justify-end">
              <a class="text-xs px-2 py-1 rounded border border-slate-200 hover:bg-slate-50" href="#">Xem</a>
              ${s.status==='submitted'?'<button class="text-xs px-2 py-1 rounded border border-slate-200 hover:bg-slate-50">Duyệt</button>':''}
            </div>`
          });
        }).join('');

        box.innerHTML = `
          <h3 class="text-lg font-semibold mb-5">Giai đoạn 02: Đề cương sinh viên</h3>
          ${featureSection}
          <div class="mt-10 mb-3 flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchStage2" class="pl-8 pr-3 py-2 border border-slate-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm w-72" placeholder="Tìm tên / MSSV / đề tài">
            </div>
            <div class="flex flex-wrap gap-2 text-[11px]">
              ${pill('Chưa nộp', statusBadges.outline.pending)}
              ${pill('Đã nộp', statusBadges.outline.submitted)}
              ${pill('Đã duyệt', statusBadges.outline.approved)}
              ${pill('Từ chối', statusBadges.outline.rejected)}
            </div>
          </div>
          <div id="stage2List">
            ${gridWrap(studentCards)}
          </div>
        `;
        const inp = document.getElementById('searchStage2');
        inp?.addEventListener('input', ()=>{
          const q=inp.value.trim().toLowerCase();
          const filtered = outlineSubmissions.filter(s =>
            s.name.toLowerCase().includes(q) ||
            s.mssv.toLowerCase().includes(q) ||
            s.topic.toLowerCase().includes(q)
          );
          document.getElementById('stage2List').innerHTML = gridWrap(filtered.map(s=>{
            const badgeClass = {
              approved: pill('Đã duyệt', statusBadges.outline.approved),
              submitted: pill('Đã nộp', statusBadges.outline.submitted),
              pending: pill('Chưa nộp', statusBadges.outline.pending),
              rejected: pill('Từ chối', statusBadges.outline.rejected)
            }[s.status] || pill('—','bg-slate-100 text-slate-600');
            return studentCard({
              title:s.name,
              subtitle:s.mssv,
              badge:badgeClass,
              lines:[
                {label:'Đề tài', value:s.topic},
                {label:'Lần cuối', value:s.date}
              ],
              footer:`<div class="flex gap-2 justify-end">
                <a class="text-xs px-2 py-1 rounded border border-slate-200 hover:bg-slate-50" href="#">Xem</a>
                ${s.status==='submitted'?'<button class="text-xs px-2 py-1 rounded border border-slate-200 hover:bg-slate-50">Duyệt</button>':''}
              </div>`
            });
          }).join(''));
        });
        break;
      }

      // ================= Stage 5 (Hội đồng) =================
      case 5: {
        const featureSection = `
          <h4 class="text-sm font-semibold mb-3 text-slate-700 flex items-center gap-2">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
            Chức năng
          </h4>
          ${gridWrap([
            featureCard({
              icon:'ph-users-four',
              title:'Hội đồng của SV',
              subtitle:'Lịch & phòng',
              desc:'Xem lịch trình bảo vệ, phòng thi và thông tin liên quan của SV bạn phụ trách.',
              href:'student-committees.html',
              color:'emerald'
            }),
            featureCard({
              icon:'ph-chalkboard-teacher',
              title:'Hội đồng tôi tham gia',
              subtitle:'Vai trò thành viên',
              desc:'Danh sách hội đồng bạn là chủ tịch / phản biện / thư ký / ủy viên.',
              href:'my-committees.html',
              color:'violet'
            }),
            featureCard({
              icon:'ph-flow-arrow',
              title:'Phân SV vào hội đồng',
              subtitle:'Điều phối',
              desc:'Cân bằng số lượng, sắp xếp phòng, thời gian và vai trò nhanh chóng.',
              href:'committee-assignments.html',
              color:'amber'
            })
          ].join(''))}
        `;

        const makeCards = rows=> rows.map(c=>{
          return studentCard({
            title:c.name,
            subtitle:`${c.mssv} • ${c.committee}`,
            lines:[
              {label:'Thời gian', value:c.time},
              {label:'Phòng', value:c.room},
              {label:'Thành viên', value:`<span class="line-clamp-3">${c.members}</span>`}
            ],
            footer:`<div class="flex gap-2 justify-end">
              <a class="text-xs px-2 py-1 border border-slate-200 rounded hover:bg-slate-50" href="#">Xem SV</a>
              <a class="text-xs px-2 py-1 border border-slate-200 rounded hover:bg-slate-50" href="#">Hội đồng</a>
            </div>`
          });
        }).join('');
        box.innerHTML = `
          <h3 class="text-lg font-semibold mb-5">Giai đoạn 05: Hội đồng</h3>
          ${featureSection}
          <div class="mt-10 mb-3">
            <div class="relative inline-block">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchStage5" class="pl-8 pr-3 py-2 border border-slate-200 rounded-lg bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm w-80" placeholder="Tìm tên / MSSV / hội đồng / thành viên">
            </div>
          </div>
          <div id="stage5List">
            ${gridWrap(makeCards(committees))}
          </div>
        `;
        const inp=document.getElementById('searchStage5');
        inp?.addEventListener('input',()=>{
          const q=inp.value.trim().toLowerCase();
          const filtered = committees.filter(c=>
            c.name.toLowerCase().includes(q) ||
            c.mssv.toLowerCase().includes(q) ||
            c.committee.toLowerCase().includes(q) ||
            c.members.toLowerCase().includes(q)
          );
          document.getElementById('stage5List').innerHTML = gridWrap(makeCards(filtered));
        });
        break;
      }

      // ================= Stage 6 (Phản biện) =================
      case 6: {
        const featureSection = `
          <h4 class="text-sm font-semibold mb-3 text-slate-700 flex items-center gap-2">
            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
            Chức năng
          </h4>
          ${gridWrap([
            featureCard({
              icon:'ph-chats-circle',
              title:'Phản biện của SV',
              subtitle:'Thông tin',
              desc:'Xem thứ tự phản biện, thời gian và giảng viên phản biện cho từng SV.',
              href:'student-reviews.html',
              color:'rose'
            }),
            featureCard({
              icon:'ph-clipboard-text',
              title:'Chấm phản biện',
              subtitle:'Giảng viên',
              desc:'Danh sách phản biện được giao – nhập nhận xét & kết quả nhanh.',
              href:'review-assignments.html',
              color:'cyan'
            })
          ].join(''))}
        `;

        const makeCards = rows=> rows.map(r=>{
          return studentCard({
            title:r.name,
            subtitle:`${r.mssv} • ${r.committee}`,
            lines:[
              {label:'Phản biện', value:r.reviewer},
              {label:'Chức vụ', value:r.role},
              {label:'Thứ tự', value:r.order},
              {label:'Thời gian', value:r.time}
            ],
            footer:`<div class="flex gap-2 justify-end">
              <a class="text-xs px-2 py-1 border border-slate-200 rounded hover:bg-slate-50" href="#">SV</a>
              <a class="text-xs px-2 py-1 border border-slate-200 rounded hover:bg-slate-50" href="#">Hội đồng</a>
            </div>`
          });
        }).join('');
        box.innerHTML = `
          <h3 class="text-lg font-semibold mb-5">Giai đoạn 06: Phản biện</h3>
          ${featureSection}
          <div class="mt-10 mb-3">
            <div class="relative inline-block">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchStage6" class="pl-8 pr-3 py-2 border border-slate-200 rounded-lg bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 text-sm w-80" placeholder="Tìm tên / MSSV / hội đồng / phản biện">
            </div>
          </div>
          <div id="stage6List">
            ${gridWrap(makeCards(reviews))}
          </div>
        `;
        const inp=document.getElementById('searchStage6');
        inp?.addEventListener('input',()=>{
          const q=inp.value.trim().toLowerCase();
          const filtered = reviews.filter(r=>
            r.name.toLowerCase().includes(q) ||
            r.mssv.toLowerCase().includes(q) ||
            r.committee.toLowerCase().includes(q) ||
            r.reviewer.toLowerCase().includes(q)
          );
          document.getElementById('stage6List').innerHTML = gridWrap(makeCards(filtered));
        });
        break;
      }

      // ================= Stage 3 (Nhật ký tuần) =================
      case 3: {
        const makeCards = (rows)=> rows.map(s=>{
          const badge = {
            graded: pill('Đã chấm', statusBadges.weekly.graded),
            submitted: pill('Đã nộp', statusBadges.weekly.submitted),
            none: pill('Chưa nộp', statusBadges.weekly.none),
            need: pill('Cần bổ sung', statusBadges.weekly.need)
          }[s.status];
          return studentCard({
            title:s.name,
            subtitle:s.mssv,
            badge,
            lines:[
              {label:'Tuần', value:s.week},
              {label:'Cập nhật', value:s.updated}
            ],
            footer:`<div class="flex gap-2 justify-end">
              <a class="text-xs px-2 py-1 border border-slate-200 rounded hover:bg-slate-50" href="#">Xem</a>
              ${s.status==='submitted'?'<a class="text-xs px-2 py-1 border border-slate-200 rounded hover:bg-slate-50" href="#">Chấm</a>':''}
            </div>`
          });
        }).join('');
        box.innerHTML = `
          <h3 class="text-lg font-semibold mb-4">Giai đoạn 03: Nhật ký tuần của sinh viên</h3>
          <div class="mb-4">
            <div class="relative inline-block">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchStage3" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-72" placeholder="Tìm tên / MSSV / tuần">
            </div>
          </div>
          <div id="stage3List">
            ${gridWrap(makeCards(weeklyLogs))}
          </div>
        `;
        const inp=document.getElementById('searchStage3');
        inp?.addEventListener('input',()=>{
          const q=inp.value.trim().toLowerCase();
            const filtered = weeklyLogs.filter(s =>
              s.name.toLowerCase().includes(q) ||
              s.mssv.toLowerCase().includes(q) ||
              s.week.toLowerCase().includes(q)
            );
            document.getElementById('stage3List').innerHTML = gridWrap(makeCards(filtered));
        });
        break;
      }

      // ================= Stage 4 (Báo cáo cuối) =================
      case 4: {
        const makeCards = rows=> rows.map(s=>{
          const badge = {
            submitted: pill('Đã nộp', statusBadges.final.submitted),
            none: pill('Chưa nộp', statusBadges.final.none)
          }[s.status] || pill('—','bg-slate-100 text-slate-600');
          return studentCard({
            title:s.name,
            subtitle:s.mssv,
            badge,
            lines:[
              {label:'Đề tài', value:s.topic},
              {label:'Ngày', value:s.date}
            ],
            footer:`<div class="flex gap-2 justify-end">
              <a class="text-xs px-2 py-1 border border-slate-200 rounded hover:bg-slate-50" href="#">Chi tiết</a>
              ${s.status==='submitted'?'<a class="text-xs px-2 py-1 border border-slate-200 rounded hover:bg-slate-50" href="#">Tải</a>':''}
              ${s.status==='none'?'<button class="text-xs px-2 py-1 border border-slate-200 rounded hover:bg-slate-50">Nhắc nộp</button>':''}
            </div>`
          });
        }).join('');
        box.innerHTML = `
          <h3 class="text-lg font-semibold mb-4">Giai đoạn 04: Báo cáo cuối</h3>
          <div class="mb-4">
            <div class="relative inline-block">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchStage4" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-72" placeholder="Tìm tên / MSSV / đề tài">
            </div>
          </div>
          <div id="stage4List">
            ${gridWrap(makeCards(finalReports))}
          </div>
        `;
        const inp=document.getElementById('searchStage4');
        inp?.addEventListener('input',()=>{
          const q=inp.value.trim().toLowerCase();
          const filtered = finalReports.filter(s =>
            s.name.toLowerCase().includes(q) ||
            s.mssv.toLowerCase().includes(q) ||
            s.topic.toLowerCase().includes(q)
          );
          document.getElementById('stage4List').innerHTML = gridWrap(makeCards(filtered));
        });
        break;
      }

      // ================= Stage 7 (Kết quả phản biện & thứ tự) =================
      case 7: {
        const makeCards = rows=> rows.map(r=>{
          const badge = {
            pass: pill('Đạt', statusBadges.reviewResult.pass),
            improve: pill('Cần bổ sung', statusBadges.reviewResult.improve),
            fail: pill('Không đạt', statusBadges.reviewResult.fail)
          }[r.result];
          return studentCard({
            title:r.name,
            subtitle:`${r.mssv} • ${r.committee}`,
            badge,
            lines:[
              {label:'Thứ tự', value:r.order},
              {label:'Bảo vệ', value:r.defend}
            ],
            footer:`<div class="flex gap-2 justify-end">
              <a class="text-xs px-2 py-1 border border-slate-200 rounded hover:bg-slate-50" href="#">SV</a>
              <a class="text-xs px-2 py-1 border border-slate-200 rounded hover:bg-slate-50" href="#">Hội đồng</a>
            </div>`
          });
        }).join('');
        box.innerHTML = `
          <h3 class="text-lg font-semibold mb-4">Giai đoạn 07: Kết quả phản biện & thứ tự bảo vệ</h3>
          <div class="mb-4">
            <div class="relative inline-block">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchStage7" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-80" placeholder="Tìm tên / MSSV / hội đồng / kết quả">
            </div>
          </div>
          <div id="stage7List">
            ${gridWrap(makeCards(reviewResults))}
          </div>
        `;
        const inp=document.getElementById('searchStage7');
        inp?.addEventListener('input',()=>{
          const q=inp.value.trim().toLowerCase();
          const filtered = reviewResults.filter(r=>
            r.name.toLowerCase().includes(q) ||
            r.mssv.toLowerCase().includes(q) ||
            r.committee.toLowerCase().includes(q) ||
            r.result.toLowerCase().includes(q)
          );
          document.getElementById('stage7List').innerHTML = gridWrap(makeCards(filtered));
        });
        break;
      }

      // ================= Stage 8 (Bảo vệ / Tổng kết) =================
      case 8: {
        const makeCards = rows=> rows.map(r=>{
          const badge = {
            pass: pill('Đạt', statusBadges.reviewResult.pass),
            improve: pill('Cần bổ sung', statusBadges.reviewResult.improve),
            fail: pill('Không đạt', statusBadges.reviewResult.fail)
          }[r.result];
          return studentCard({
            title:r.name,
            subtitle:`${r.mssv} • ${r.committee}`,
            badge,
            lines:[
              {label:'Điểm', value:r.score},
              {label:'Nhận xét', value:`<span class="line-clamp-2">${r.note}</span>`}
            ],
            footer:`<div class="flex gap-2 justify-end">
              <a class="text-xs px-2 py-1 border border-slate-200 rounded hover:bg-slate-50" href="#">SV</a>
              <a class="text-xs px-2 py-1 border border-slate-200 rounded hover:bg-slate-50" href="#">Hội đồng</a>
            </div>`
          });
        }).join('');
        box.innerHTML = `
          <h3 class="text-lg font-semibold mb-4">Giai đoạn 08: Bảo vệ & Tổng kết</h3>
          <div class="mb-4">
            <div class="relative inline-block">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchStage8" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-80" placeholder="Tìm tên / MSSV / hội đồng / điểm / ghi chú">
            </div>
          </div>
          <div id="stage8List">
            ${gridWrap(makeCards(defenseResults))}
          </div>
        `;
        const inp=document.getElementById('searchStage8');
        inp?.addEventListener('input',()=>{
          const q=inp.value.trim().toLowerCase();
          const filtered = defenseResults.filter(r=>
            r.name.toLowerCase().includes(q) ||
            r.mssv.toLowerCase().includes(q) ||
            r.committee.toLowerCase().includes(q) ||
            r.score.toLowerCase().includes(q) ||
            r.note.toLowerCase().includes(q)
          );
          document.getElementById('stage8List').innerHTML = gridWrap(makeCards(filtered));
        });
        break;
      }

      default:
        box.innerHTML = `<div class="text-slate-500 text-sm">Không có dữ liệu giai đoạn.</div>`;
    }
  }

  // Khởi chạy
  initRoundInfo();
  renderTimeline();
  showStageDetails(1); // mở mặc định giai đoạn 1
})();
// =================================================================
</script>
</body>
</html>
