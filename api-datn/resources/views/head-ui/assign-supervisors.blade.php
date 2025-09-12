<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Phân công giảng viên hướng dẫn</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <style>
    body { font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial; }
    html,body { height:100%; }
    body.lock-scroll { overflow:hidden; }
    .sidebar-collapsed .sidebar-label { display:none; }
    .sidebar-collapsed .sidebar { width:72px; }
    .sidebar { width:260px; }
    .table-wrap { overflow:auto; }
  </style>
</head>
<body class="bg-slate-50 text-slate-800">
@php
  $termId = $termId ?? request()->route('termId');
  // Dữ liệu mẫu (thay bằng dữ liệu thật từ Controller)
  $supervisors = $supervisors ?? [
    ['id'=>101, 'name'=>'TS. Đặng Hữu T', 'email'=>'danght@uni.edu', 'dept'=>'CNTT', 'expertise'=>'Web, AI', 'current'=>3, 'max'=>6],
    ['id'=>102, 'name'=>'ThS. Lưu Lan', 'email'=>'lanll@uni.edu', 'dept'=>'CNTT', 'expertise'=>'Mobile, UX', 'current'=>2, 'max'=>4],
    ['id'=>103, 'name'=>'TS. Nguyễn Văn A', 'email'=>'anng@uni.edu', 'dept'=>'HTTT', 'expertise'=>'Data, BI', 'current'=>5, 'max'=>5],
  ];
  $students = $students ?? [
    ['id'=>20123456, 'code'=>'20123456', 'name'=>'Nguyễn Văn A', 'class'=>'KTPM2025', 'email'=>'20123456@sv.uni.edu'],
    ['id'=>20124567, 'code'=>'20124567', 'name'=>'Trần Thị B', 'class'=>'KTPM2025', 'email'=>'20124567@sv.uni.edu'],
    ['id'=>20125678, 'code'=>'20125678', 'name'=>'Lê Văn C', 'class'=>'HTTT2025', 'email'=>'20125678@sv.uni.edu'],
  ];
@endphp

<div id="layoutRoot" class="flex h-screen">
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
  <!-- Sidebar (đồng nhất với round-detail) -->
  <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all overflow-hidden">
    <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
      <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-buildings"></i></div>
      <div class="sidebar-label">
        <div class="font-semibold">Assistant</div>
        <div class="text-xs text-slate-500">Quản trị khoa</div>
      </div>
    </div>
    <nav class="flex-1 p-3">
      <a href="{{ route('web.assistant.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span class="sidebar-label">Bảng điều khiển</span></a>
      <a href="{{ route('web.assistant.manage_departments') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-buildings"></i><span class="sidebar-label">Bộ môn</span></a>
      <a href="{{ route('web.assistant.manage_majors') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
      <a href="{{ route('web.assistant.manage_staffs') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
      <a href="{{ route('web.assistant.assign_head') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Gán trưởng bộ môn</span></a>
      <div class="sidebar-label text-xs uppercase text-slate-400 px-3 mt-3">Học phần tốt nghiệp</div>
      <div class="graduation-item">
        <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button">
          <span class="flex items-center gap-3">
            <i class="ph ph-folder"></i>
            <span class="sidebar-label">Học phần tốt nghiệp</span>
          </span>
          <i class="ph ph-caret-down"></i>
        </div>
        <div id="gradMenu" class="submenu pl-6">
          <a href="#" class="block px-3 py-2 rounded hover:bg-slate-100">Thực tập tốt nghiệp</a>
          <a href="{{ route('web.assistant.rounds') }}" class="block px-3 py-2 rounded hover:bg-slate-100 bg-slate-100 font-semibold" aria-current="page">Đồ án tốt nghiệp</a>
        </div>
      </div>
    </nav>
    <div class="p-3 border-t border-slate-200">
      <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg">
        <i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span>
      </button>
    </div>
  </aside>

  <!-- Main -->
  <div class="flex-1 min-h-screen flex flex-col">
    <!-- Header cố định -->
    @php
      $user = auth()->user();
      $userName = $user->fullname ?? $user->name ?? 'Assistant';
      $email = $user->email ?? '';
      $avatarUrl = $user->avatar_url
        ?? $user->profile_photo_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    @endphp
    <header class="fixed top-0 left-0 md:left-[260px] right-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-40">
      <div class="flex items-center gap-3 flex-1">
        <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
        <div>
          <h1 class="text-lg md:text-xl font-semibold">Phân công giảng viên hướng dẫn</h1>
          <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Đồ án tốt nghiệp / Phân công GVHD • Đợt #{{ $termId }}</nav>
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
          <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
      </div>
    </header>

    <!-- Content scroll -->
    <main id="mainScroll" class="flex-1 pt-20 h-full overflow-y-auto px-4 md:px-6 pb-10 space-y-6 max-w-7xl mx-auto">
      @php
        $lecturerCount   = is_countable($supervisors ?? []) ? count($supervisors) : 0;
        $unassignedCount = is_countable($students ?? []) ? count($students) : 0;
        $curTotal = 0; $maxTotal = 0;
        foreach(($supervisors ?? []) as $t){ $curTotal += (int)($t['current'] ?? 0); $maxTotal += (int)($t['max'] ?? 0); }
        $pct = $maxTotal > 0 ? min(100, round(($curTotal / $maxTotal) * 100)) : 0;
        $status = $term->status ?? 'active';
        $statusLabel = $status === 'active' ? 'Đang mở' : ($status === 'closed' ? 'Đã đóng' : ucfirst($status));
        $statusClass = $status === 'active' ? 'bg-emerald-100 text-emerald-700' : ($status === 'closed' ? 'bg-slate-200 text-slate-700' : 'bg-amber-100 text-amber-700');
        $termName = $term->name ?? ('Đợt đồ án #' . $termId);
        $startLabel = (isset($term) && !empty($term->start_date)) ? \Carbon\Carbon::parse($term->start_date)->format('d/m/Y') : '—';
        $endLabel   = (isset($term) && !empty($term->end_date)) ? \Carbon\Carbon::parse($term->end_date)->format('d/m/Y') : '—';
      @endphp

      <!-- Thông tin đợt đồ án -->
      <section class="bg-white border border-slate-200 rounded-xl p-4 md:p-5">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
          <div>
            <div class="text-sm text-slate-500">Đợt đồ án</div>
            <div class="text-base md:text-lg font-semibold">{{ $termName }}</div>
            <div class="mt-1 text-sm text-slate-600 flex items-center gap-2">
              <i class="ph ph-calendar-dots text-slate-500"></i>
              <span>Thời gian: {{ $startLabel }} - {{ $endLabel }}</span>
            </div>
          </div>
          <div>
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs {{ $statusClass }}">
              <i class="ph ph-circle"></i> {{ $statusLabel }}
            </span>
          </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
          <div class="rounded-lg border border-slate-200 p-3">
            <div class="text-xs text-slate-500">Giảng viên</div>
            <div class="mt-1 text-lg font-semibold">{{ $lecturerCount }}</div>
          </div>
          <div class="rounded-lg border border-slate-200 p-3">
            <div class="text-xs text-slate-500">SV chưa có GVHD</div>
            <div class="mt-1 text-lg font-semibold">{{ $unassignedCount }}</div>
          </div>
          <div class="rounded-lg border border-slate-200 p-3 col-span-2">
            <div class="flex items-center justify-between text-xs text-slate-500">
              <span>Chỉ tiêu sử dụng</span>
              <span>{{ $curTotal }}/{{ $maxTotal }}</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2 mt-1.5">
              <div class="h-2 rounded-full {{ $pct>=100?'bg-rose-600':($pct>=75?'bg-yellow-600':'bg-emerald-600') }}" style="width: {{ $pct }}%"></div>
            </div>
          </div>
        </div>
      </section>

      <!-- Thanh hành động chính -->
      <div class="flex items-center justify-between bg-white border border-slate-200 rounded-xl px-4 py-3">
        <div class="text-sm text-slate-600">
          Chọn 1 giảng viên và các sinh viên cần phân công, sau đó bấm nút.
        </div>
        <button id="btnAssignMain" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">
          <i class="ph ph-user-switch"></i>
          Phân công sinh viên
        </button>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Bảng giảng viên -->
        <section class="bg-white rounded-xl border border-slate-200">
          <div class="p-4 border-b flex items-center justify-between">
            <div>
              <div class="font-semibold">Giảng viên</div>
              <div class="text-xs text-slate-500">Chọn 1 giảng viên để phân công</div>
            </div>
            <div class="relative">
              <input id="searchTeachers" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tên, email">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
          </div>
          <div class="table-wrap">
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-500 border-b">
                  <th class="py-2 px-3 w-10">Chọn</th>
                  <th class="py-2 px-3">Giảng viên</th>
                  <th class="py-2 px-3">Chuyên môn</th>
                  <th class="py-2 px-3">Số SV</th>
                </tr>
              </thead>
              @php
                $supervisors = $projectTerm->supervisors ?? collect();
              @endphp
              <tbody id="tbTeachers">
                @foreach ($supervisors as $t)
                  @php
                    $pct = min(100, round(($t->assignment_supervisors->count() / max(1,$t->max_students))*100));
                    $color = $pct>=100 ? 'bg-rose-600' : ($pct>=75 ? 'bg-yellow-600' : 'bg-emerald-600');
                  @endphp
                  <tr class="border-b hover:bg-slate-50"
                      data-id="{{ $t->id }}"
                      data-name="{{ $t->teacher->user->fullname }}"
                      data-current="{{ $t->assignment_supervisors->count() }}"
                      data-max="{{ $t->max_students }}">
                    <td class="py-2 px-3 align-top">
                      <input type="radio" name="teacher" class="mt-2">
                    </td>
                    <td class="py-2 px-3">
                      <div class="font-medium">{{ $t->teacher->user->fullname }}</div>
                      <div class="text-xs text-slate-500">{{ $t->teacher->user->email }} • {{ $t->teacher->position }}</div>
                    </td>
                    <td class="py-2 px-3">{{ $t->expertise }}</td>
                    <td class="py-2 px-3">
                      <div class="flex items-center justify-between text-xs text-slate-600">
                        <span><span class="cur">{{ $t->assignment_supervisors->count() }}</span>/<span class="max">{{ $t->max_students }}</span></span>
                        <span>{{ $pct }}%</span>
                      </div>
                      <div class="w-full bg-slate-200 rounded-full h-2 mt-1">
                        <div class="{{ $color }} h-2 rounded-full" style="width: {{ $pct }}%"></div>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </section>

        <!-- Bảng sinh viên chưa có GVHD -->
        <section class="bg-white rounded-xl border border-slate-200">
          <div class="p-4 border-b flex items-center justify-between">
            <div>
              <div class="font-semibold">Sinh viên chưa có giảng viên hướng dẫn</div>
              <div class="text-xs text-slate-500">Chọn 1 hoặc nhiều sinh viên để phân công</div>
            </div>
            <div class="relative">
              <input id="searchStudents" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tên, mã SV">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
          </div>
          <div class="table-wrap">
            @php
              $assignments = $projectTerm->assignments ?? collect();
            @endphp
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-500 border-b">
                  <th class="py-2 px-3 w-10">
                    <input id="chkAll" type="checkbox">
                  </th>
                  <th class="py-2 px-3">Sinh viên</th>
                  <th class="py-2 px-3">Lớp</th>
                  <th class="py-2 px-3">Email</th>
                </tr>
              </thead>
              <tbody id="tbStudents">
                @php
                  $assignments = $projectTerm->assignments ?? collect();
                @endphp
                @foreach ($assignments as $s)
                  @if ($s->status !== 'active') 
                    <tr class="border-b hover:bg-slate-50" data-id="{{ $s->id }}">
                      <td class="py-2 px-3 align-top">
                        <input type="checkbox" class="rowChk mt-2">
                      </td>
                      <td class="py-2 px-3">
                        <div class="font-medium">{{ $s->student->user->fullname }}</div>
                        <div class="text-xs text-slate-500">{{ $s->student->student_code }}</div>
                      </td>
                      <td class="py-2 px-3">{{ $s->student->class_code }}</td>
                      <td class="py-2 px-3">{{ $s->student->user->email }}</td>
                    </tr>
                  @endif
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
  // Sidebar/header logic giống round-detail
  const html=document.documentElement, sidebar=document.getElementById('sidebar');
  function setCollapsed(c){
    const mainArea = document.querySelector('.flex-1');
    if(c){
      html.classList.add('sidebar-collapsed');
      mainArea.classList.add('md:pl-[72px]');
      mainArea.classList.remove('md:pl-[260px]');
    } else {
      html.classList.remove('sidebar-collapsed');
      mainArea.classList.remove('md:pl-[72px]');
      mainArea.classList.add('md:pl-[260px]');
    }
  }
  document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed');setCollapsed(c);localStorage.setItem('assistant_sidebar',''+(c?1:0));});
  document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
  if(localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);
  sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');


  // Profile dropdown
  const profileBtn=document.getElementById('profileBtn');
  const profileMenu=document.getElementById('profileMenu');
  profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
  document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

  // Graduation submenu toggle + mở sẵn
  document.addEventListener('DOMContentLoaded', () => {
    const wrap = document.querySelector('.graduation-item');
    const toggleBtn = wrap?.querySelector('.toggle-button');
    const submenu = wrap?.querySelector('.submenu');
    const caret = wrap?.querySelector('.ph.ph-caret-down');
    if (submenu && submenu.classList.contains('hidden')) submenu.classList.remove('hidden');
    if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
    caret?.classList.add('transition-transform', 'rotate-180');
    toggleBtn?.addEventListener('click', (e) => {
      e.preventDefault();
      submenu?.classList.toggle('hidden');
      const expanded = !submenu?.classList.contains('hidden');
      toggleBtn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
      caret?.classList.toggle('rotate-180', expanded);
    });
  });

  document.body.classList.add('lock-scroll'); // khóa body scroll
  // đảm bảo main luôn có scrollbar riêng (tránh layout shift)
  const mainEl = document.getElementById('mainScroll');
  mainEl.classList.add('overflow-y-scroll');

  const CSRF='{{ csrf_token() }}';

  // Search filters
  document.getElementById('searchTeachers')?.addEventListener('input', (e)=>{
    const q=(e.target.value||'').toLowerCase();
    document.querySelectorAll('#tbTeachers tr').forEach(tr=>{
      tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
  });
  document.getElementById('searchStudents')?.addEventListener('input', (e)=>{
    const q=(e.target.value||'').toLowerCase();
    document.querySelectorAll('#tbStudents tr').forEach(tr=>{
      tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
  });

  // Check all students
  document.getElementById('chkAll')?.addEventListener('change', (e)=>{
    document.querySelectorAll('#tbStudents .rowChk').forEach(chk=> {
      if (chk.closest('tr').style.display !== 'none') chk.checked = e.target.checked;
    });
  });

  // Confirm modal
  function openConfirmModal({teacherName, count, onConfirm}) {
    const wrap=document.createElement('div');
    wrap.className='fixed inset-0 z-50 flex items-center justify-center px-4';
    wrap.innerHTML=`
      <div class="absolute inset-0 bg-slate-900/50" data-close></div>
      <div class="relative w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b flex items-center justify-between">
          <div class="flex items-center gap-2">
            <div class="h-9 w-9 grid place-items-center rounded-lg bg-amber-50 text-amber-600"><i class="ph ph-user-switch"></i></div>
            <h3 class="font-semibold text-lg">Xác nhận phân công</h3>
          </div>
          <button class="p-2 rounded-lg hover:bg-slate-100" data-close><i class="ph ph-x"></i></button>
        </div>
        <div class="p-5">
          <p class="text-sm text-slate-700">Phân công <span class="font-semibold">${count}</span> sinh viên cho <span class="font-semibold">${teacherName}</span>?</p>
        </div>
        <div class="px-5 py-4 border-t bg-slate-50 flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-lg border hover:bg-slate-100 text-sm" data-close>Hủy</button>
          <button id="confirmAssign" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">Xác nhận</button>
        </div>
      </div>`;
    function close(){ wrap.remove(); document.removeEventListener('keydown', esc); }
    function esc(e){ if(e.key==='Escape') close(); }
    wrap.addEventListener('click', (e)=>{ if(e.target.hasAttribute('data-close')||e.target===wrap) close(); });
    document.addEventListener('keydown', esc);
    document.body.appendChild(wrap);
    wrap.querySelector('#confirmAssign')?.addEventListener('click', ()=>{ onConfirm?.(); close(); });
  }

  function toast(msg, type='success'){
    const host=document.getElementById('toastHost')||(()=>{const d=document.createElement('div');d.id='toastHost';d.className='fixed top-4 right-4 z-50 space-y-2';document.body.appendChild(d);return d;})();
    const color= type==='success'?'bg-emerald-600':type==='error'?'bg-rose-600':'bg-slate-800';
    const el=document.createElement('div'); el.className=`px-4 py-2 rounded-lg text-white text-sm shadow ${color}`; el.textContent=msg; host.appendChild(el);
    setTimeout(()=>{ el.style.opacity='0'; el.style.transform='translateY(-4px)'; el.style.transition='all .25s'; }, 1800);
    setTimeout(()=> el.remove(), 2100);
  }

  async function doAssign(){
     const teacherRow = document.querySelector('#tbTeachers input[type="radio"]:checked')?.closest('tr');
     if(!teacherRow) { toast('Vui lòng chọn giảng viên', 'error'); return; }
     const teacherName = teacherRow.dataset.name;
     const cur = parseInt(teacherRow.dataset.current||'0',10);
     const max = parseInt(teacherRow.dataset.max||'0',10);

     const selectedStudents = Array.from(document.querySelectorAll('#tbStudents .rowChk:checked'))
       .map(chk => chk.closest('tr'));
     if(selectedStudents.length===0){ toast('Vui lòng chọn ít nhất 1 sinh viên', 'error'); return; }

     if(cur + selectedStudents.length > max){
       toast('Vượt quá chỉ tiêu của giảng viên', 'error'); return;
     }

     openConfirmModal({
       teacherName, count: selectedStudents.length,
       onConfirm: async ()=>{
         // Gọi API thực tế
         try {
           const res = await fetch("{{ route('web.head.assign_supervisors.bulk', ['termId'=>$termId]) }}", {
             method:'POST',
             headers:{
               'Content-Type':'application/json',
               'X-CSRF-TOKEN':'{{ csrf_token() }}',
               'Accept':'application/json'
             },
             body: JSON.stringify({
               supervisor_id: teacherRow.dataset.id,
               project_term_id: {{ $termId }},
               assignment_ids: selectedStudents.map(tr=>tr.dataset.id),
               status: 'accepted'
             })
           });
           const json = await res.json();
           if(!res.ok){
             toast(json.message || 'Lỗi phân công', 'error');
             return;
           }
         } catch(e){
           toast('Lỗi mạng', 'error');
           return;
         }

         // Cập nhật UI demo
         const curEl = teacherRow.querySelector('.cur');
         const maxEl = teacherRow.querySelector('.max');
         const newCur = parseInt(curEl.textContent||'0',10) + selectedStudents.length;
         teacherRow.dataset.current = newCur;
         curEl.textContent = newCur;
         const pct = Math.min(100, Math.round((newCur/Math.max(1, parseInt(maxEl.textContent||'0',10)))*100));
         const bar = teacherRow.querySelector('.h-2.rounded-full');
         bar.style.width = pct + '%';
         bar.className = `h-2 rounded-full ${pct>=100?'bg-rose-600':(pct>=75?'bg-yellow-600':'bg-emerald-600')}`;

         selectedStudents.forEach(tr => tr.remove());
         document.getElementById('chkAll')?.checked && (document.getElementById('chkAll').checked = false);
         toast('Phân công thành công');
       }
     });
  }
  document.getElementById('btnAssign')?.addEventListener('click', doAssign);
  document.getElementById('btnAssignMain')?.addEventListener('click', doAssign);
</script>
<div id="toastHost" class="fixed top-4 right-4 z-50 space-y-2"></div>
</body>
</html>