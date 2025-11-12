<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Danh sách giảng viên hướng dẫn</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
    .sidebar { width: 260px; }
    .sidebar-collapsed .sidebar { width: 72px; }
    .sidebar-collapsed .sidebar-label { display: none; }
    .submenu { display: none; }
    .submenu.open { display: block; }
  </style>
</head>
<body class="bg-slate-50 text-slate-800">
@php
  $user = auth()->user();
  $userName = $user->fullname ?? $user->name ?? 'Người dùng';
  $email = $user->email ?? '';
  $avatarUrl = $user->avatar_url
    ?? $user->profile_photo_url
    ?? 'https://ui-avatars.com/api/?name='.urlencode($userName).'&background=0ea5e9&color=ffffff';

  // Dữ liệu đợt đồ án (ưu tiên $projectTerm giống students-detail)
  $term = $projectTerm ?? ($round ?? null);
  $roundName  = optional($term)->term_name;
  $roundStage = optional($term)->stage;
  $roundYear  = optional(optional($term)->academy_year)->year_name ?? optional(optional($term)->academyYear)->year_name;
  $roundStart = optional($term)->start_date;
  $roundEnd   = optional($term)->end_date;

  // Tập giảng viên hiển thị
  $collection = $items ?? ($supervisors ?? collect());
  $totalSupervisors = method_exists($collection,'count') ? $collection->count() : (is_array($collection) ? count($collection) : 0);
@endphp

<div class="flex min-h-screen">
  <!-- Sidebar cố định -->
  <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-transform transform -translate-x-full md:translate-x-0">
    <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
      <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-buildings"></i></div>
      <div class="sidebar-label">
        <div class="font-semibold">Assistant</div>
        <div class="text-xs text-slate-500">Quản trị khoa</div>
      </div>
    </div>
    <nav class="flex-1 overflow-y-auto p-3">
      <a href="{{ route('web.assistant.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span class="sidebar-label">Bảng điều khiển</span></a>
      <a href="{{ route('web.assistant.manage_departments') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-buildings"></i><span class="sidebar-label">Bộ môn</span></a>
      <a href="{{ route('web.assistant.manage_majors') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
      <a href="{{ route('web.assistant.manage_staffs') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
      <a href="{{ route('web.assistant.assign_head') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Phân trưởng bộ môn</span></a>

      <div class="text-xs uppercase text-slate-400 px-3 mt-3 sidebar-label">Học phần tốt nghiệp</div>
      <button id="gradToggle" class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-slate-100">
        <span class="flex items-center gap-3">
          <i class="ph ph-folder"></i>
          <span class="sidebar-label">Học phần tốt nghiệp</span>
        </span>
        <i class="ph ph-caret-down"></i>
      </button>
      <div id="gradMenu" class="submenu pl-6">
        <a href="#" class="block px-3 py-2 rounded hover:bg-slate-100">Thực tập tốt nghiệp</a>
        <a href="{{ route('web.assistant.rounds') }}" class="block px-3 py-2 rounded hover:bg-slate-100 bg-slate-100 font-semibold" aria-current="page">Đồ án tốt nghiệp</a>
      </div>
    </nav>
    <div class="p-3 border-t border-slate-200">
      <button id="collapseBtn" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700">
        <i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span>
      </button>
    </div>
  </aside>

  <div class="flex-1">
    <!-- Header -->
    <header class="fixed top-0 left-0 md:left-[260px] right-0 h-16 bg-white border-b border-slate-200 z-20">
      <div class="h-full flex items-center justify-between px-4 md:px-6">
        <div class="flex items-center gap-3">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
          <a href="{{ url()->previous() ?: route('web.assistant.rounds') }}" class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50" title="Quay lại">
            <i class="ph ph-arrow-left"></i><span class="text-sm">Quay lại</span>
          </a>
          <button type="button" onclick="history.back()" class="sm:hidden p-2 rounded-lg hover:bg-slate-100" aria-label="Quay lại"><i class="ph ph-arrow-left"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Giảng viên hướng dẫn</h1>
            <p class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp / Danh sách giảng viên</p>
          </div>
        </div>
        <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
          <img class="h-9 w-9 rounded-full object-cover" src="{{ $avatarUrl }}" alt="avatar">
          <span class="hidden sm:block text-left">
            <span class="text-sm font-semibold leading-4">{{ $userName }}</span>
            <span class="block text-xs text-slate-500">{{ $email }}</span>
          </span>
          <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
        </button>
      </div>
    </header>

    <!-- Main -->
    <main class="pt-20 px-4 md:px-6 pb-10 md:pl-[260px]">
      <div class="max-w-6xl mx-auto space-y-6">
        <section class="bg-white rounded-xl border border-slate-200 p-5">
          <div class="flex items-center justify-between gap-4">
            <div>
              <h2 class="text-base font-semibold">Giảng viên cho đợt đồ án</h2>
              <p class="text-xs text-slate-500">Danh sách giảng viên hướng dẫn của đợt được chọn</p>
            </div>
            <div class="relative w-full sm:w-80">
              <input id="q" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo họ tên, email, bộ môn" />
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
          </div>

          <!-- Thông tin đợt -->
          <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-4 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm">
            <div class="flex items-center gap-2">
              <i class="ph ph-flag text-slate-500"></i>
              <span class="text-slate-600">Đợt:</span>
              <span class="font-medium">{{ $roundName ?? ('Đợt ' . ($roundStage ?? '—')) }}</span>
            </div>
            <div class="flex items-center gap-2">
              <i class="ph ph-calendar text-slate-500"></i>
              <span class="text-slate-600">Năm học:</span>
              <span class="font-medium">{{ $roundYear ?? '—' }}</span>
            </div>
            <div class="flex items-center gap-2">
              <i class="ph ph-calendar-check text-slate-500"></i>
              <span class="text-slate-600">Bắt đầu:</span>
              <span class="font-medium">{{ $roundStart ?? '—' }}</span>
            </div>
            <div class="flex items-center gap-2">
              <i class="ph ph-calendar-x text-slate-500"></i>
              <span class="text-slate-600">Kết thúc:</span>
              <span class="font-medium">{{ $roundEnd ?? '—' }}</span>
            </div>
            <div class="flex items-center gap-2">
              <i class="ph ph-users-three text-slate-500"></i>
              <span class="text-slate-600">Tổng GV:</span>
              <span class="font-medium">{{ $totalSupervisors }}</span>
            </div>
          </div>

          <!-- Tiêu đề bảng -->
          <div class="mt-4 mb-2 flex items-center gap-2">
            <i class="ph ph-list-bullets text-slate-500"></i>
            <h3 class="font-semibold">Danh sách giảng viên hướng dẫn</h3>
          </div>

<div class="mt-4 overflow-x-auto border rounded-lg shadow-sm">
  <table class="min-w-[900px] w-full text-sm border-collapse">
    <thead class="bg-slate-50 text-slate-500">
      <tr>
        <th class="py-3 px-4 border-b select-none cursor-pointer hover:text-slate-700" data-key="code">
          Mã GV <i class="ph ph-caret-up-down ml-1 text-slate-400"></i>
        </th>
        <th class="py-3 px-4 border-b select-none cursor-pointer hover:text-slate-700" data-key="name">
          Họ tên <i class="ph ph-caret-up-down ml-1 text-slate-400"></i>
        </th>
        <th class="py-3 px-4 border-b select-none cursor-pointer hover:text-slate-700" data-key="dept">
          Bộ môn <i class="ph ph-caret-up-down ml-1 text-slate-400"></i>
        </th>
        <th class="py-3 px-4 border-b select-none cursor-pointer hover:text-slate-700" data-key="degree">
          Học vị <i class="ph ph-caret-up-down ml-1 text-slate-400"></i>
        </th>
        <th class="py-3 px-4 border-b select-none cursor-pointer hover:text-slate-700" data-key="email">
          Email <i class="ph ph-caret-up-down ml-1 text-slate-400"></i>
        </th>
        <th class="py-3 px-4 border-b select-none cursor-pointer hover:text-slate-700" data-key="load">
          Số sinh viên <i class="ph ph-caret-up-down ml-1 text-slate-400"></i>
        </th>
        <th class="py-3 px-4 border-b text-right">Hành động</th>
      </tr>
    </thead>
    <tbody id="tbody" class="bg-white">
      @if($totalSupervisors > 0)
        @foreach($collection as $row)
          @php
            $u = $row->user ?? optional($row->teacher)->user;
            $code = $row->staff_code ?? $row->code ?? optional($row->teacher)->teacher_code ?? '—';
            $name = optional($u)->fullname ?? $row->fullname ?? ($row->name ?? '—');
            $dept = $row->department_name ?? optional($row->department)->name ?? optional(optional($row->teacher)->department)->name ?? '—';
            $degree = $row->degree ?? optional($row->teacher)->degree ?? '—';
            $mail = optional($u)->email ?? $row->email ?? '—';
            $max = $row->max_students ?? 0;
            $cur = $row->current_students_count ?? $row->assignment_supervisors->count() ?? 0;
            $statusInt = $row->status ?? 1;
            $statusLabel = is_string($statusInt) ? $statusInt : ($statusInt ? 'Hoạt động' : 'Tạm dừng');
            $statusCls = (is_string($statusInt) && in_array(strtolower($statusInt), ['inactive','tạm dừng'])) || !$statusInt
              ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700';
          @endphp
          <tr class="hover:bg-slate-50">
            <td class="py-3 px-4">{{ $code }}</td>
            <td class="py-3 px-4">
              <a class="text-blue-600 hover:underline" href="#">{{ $name }}</a>
            </td>
            <td class="py-3 px-4">{{ $dept }}</td>
            <td class="py-3 px-4">{{ $degree }}</td>
            <td class="py-3 px-4 break-all">{{ $mail }}</td>
            <td class="py-3 px-4 flex flex-col sm:flex-row sm:items-center gap-1">
              <span class="px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-700">{{ $cur }}/{{ $max }}</span>
              <span class="px-2 py-1 rounded-full text-xs {{ $statusCls }}">{{ $statusLabel }}</span>
            </td>
            <td class="py-3 px-4 text-right">
              <div class="flex justify-end gap-2">
                <a class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" href="#"><i class="ph ph-eye"></i></a>
                <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600"><i class="ph ph-pencil"></i></button>
                <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"><i class="ph ph-trash"></i></button>
              </div>
            </td>
          </tr>
        @endforeach
      @else
        <tr>
          <td colspan="8" class="py-6 px-4 text-center text-slate-500">Chưa có giảng viên nào trong đợt này.</td>
        </tr>
      @endif
    </tbody>
  </table>
</div>

        </section>
      </div>
    </main>
  </div>
</div>

<script>
  // Sidebar behavior
  const htmlEl=document.documentElement, sidebar=document.getElementById('sidebar');
  document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
  function setCollapsedState(collapsed){
    const header=document.querySelector('header'); const main=document.querySelector('main');
    if(collapsed){
      htmlEl.classList.add('sidebar-collapsed');
      header?.classList.add('md:left-[72px]'); header?.classList.remove('md:left-[260px]');
      main?.classList.add('md:pl-[72px]');     main?.classList.remove('md:pl-[260px]');
    }else{
      htmlEl.classList.remove('sidebar-collapsed');
      header?.classList.remove('md:left-[72px]'); header?.classList.add('md:left-[260px]');
      main?.classList.remove('md:pl-[72px]');     main?.classList.add('md:pl-[260px]');
    }
  }
  document.getElementById('collapseBtn')?.addEventListener('click',()=>{
    const collapsed=!htmlEl.classList.contains('sidebar-collapsed');
    setCollapsedState(collapsed); localStorage.setItem('assistant_sidebar',collapsed?'1':'0');
  });
  // Init fixed + responsive
  sidebar.classList.add('transition-transform','transform','-translate-x-full','md:translate-x-0');
  if (window.matchMedia('(min-width:768px)').matches) sidebar.classList.remove('-translate-x-full');
  if (localStorage.getItem('assistant_sidebar')==='1') setCollapsedState(true);

  // Open "Học phần tốt nghiệp" submenu and mark "Đồ án tốt nghiệp"
  const gradBtn=document.getElementById('gradToggle');
  const gradMenu=document.getElementById('gradMenu');
  gradMenu?.classList.add('open');
  gradBtn?.setAttribute('aria-expanded','true');
  gradBtn?.querySelector('.ph.ph-caret-down')?.classList.add('rotate-180');
  const thesisLink=gradMenu?.querySelector('a[href*="rounds"]');
  thesisLink?.classList.add('bg-slate-100','font-semibold');

  gradBtn?.addEventListener('click',()=>{
    gradMenu?.classList.toggle('open');
    const expanded=gradMenu?.classList.contains('open');
    gradBtn?.setAttribute('aria-expanded', expanded?'true':'false');
    gradBtn?.querySelector('.ph.ph-caret-down')?.classList.toggle('rotate-180', expanded);
  });

  // Profile dropdown
  const profileBtn=document.getElementById('profileBtn');
  const profileMenu=document.createElement('div'); // optional: attach your own menu if cần

  // Search filter
  document.getElementById('q')?.addEventListener('input', (e)=>{
    const q=(e.target.value||'').toLowerCase();
    document.querySelectorAll('#tbody tr').forEach(tr=>{
      const txt=tr.innerText.toLowerCase();
      tr.style.display = txt.includes(q) ? '' : 'none';
    });
  });

  // Sort
  const sortHint = { key:null, dir:1 };
  function cellValue(tr, key){
    const tds=tr.querySelectorAll('td');
    switch(key){
      case 'code':  return (tds[0]?.innerText||'').trim();
      case 'name':  return (tds[1]?.innerText||'').toLowerCase();
      case 'dept':  return (tds[2]?.innerText||'').toLowerCase();
      case 'degree':return (tds[3]?.innerText||'').toLowerCase();
      case 'email': return (tds[4]?.innerText||'').toLowerCase();
      case 'load':  {
        const txt=(tds[5]?.innerText||'').trim(); // "cur/max ..."
        const m=txt.match(/(\d+)\s*\/\s*(\d+)/);
        return m ? (parseInt(m[1]) / Math.max(1,parseInt(m[2]))) : 0;
      }
    }
    return '';
  }
  function applySort(th){
    const key=th.dataset.key; if(!key) return;
    sortHint.dir = sortHint.key===key ? -sortHint.dir : 1;
    sortHint.key = key;
    const rows=[...document.querySelectorAll('#tbody tr')].filter(r=>r.style.display!=='none');
    rows.sort((a,b)=>{
      const va=cellValue(a,key), vb=cellValue(b,key);
      if(typeof va==='number' && typeof vb==='number') return (va-vb)*sortHint.dir;
      return (va>vb?1:va<vb?-1:0)*sortHint.dir;
    });
    const tb=document.getElementById('tbody'); rows.forEach(r=>tb.appendChild(r));
    document.querySelectorAll('thead th[data-key] i').forEach(i=> i.className='ph ph-caret-up-down ml-1 text-slate-400');
    th.querySelector('i').className = sortHint.dir===1 ? 'ph ph-caret-up ml-1 text-slate-600' : 'ph ph-caret-down ml-1 text-slate-600';
  }
  document.querySelectorAll('thead th[data-key]').forEach(th=>