<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Báo cáo đề cương - SV đang hướng dẫn</title>
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
    $data_assignment_supervisors = $user->teacher->supervisor->assignment_supervisors ?? collect();
    $teacherId = $user->teacher->id ?? 0;
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
            <h1 class="text-lg md:text-xl font-semibold">Báo cáo đề cương - SV đang hướng dẫn</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-round-detail.html" class="hover:underline text-slate-600">Chi tiết đợt</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Báo cáo đề cương (SV hướng dẫn)</span>
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
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
        <div class="max-w-6xl mx-auto space-y-4">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="search" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-80" placeholder="Tìm theo tên/MSSV/đề tài" />
            </div>
            <a href="thesis-round-detail.html" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại chi tiết đợt</a>
          </div>

          <div class="bg-white border rounded-xl p-4">
            <div class="flex items-center gap-2 text-xs mb-3">
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-700"><span class="h-2 w-2 rounded-full bg-slate-400"></span> Chưa nộp</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700"><span class="h-2 w-2 rounded-full bg-amber-400"></span> Đã nộp</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Đã duyệt</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-50 text-rose-700"><span class="h-2 w-2 rounded-full bg-rose-500"></span> Bị từ chối</span>
            </div>
          <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            @php
              $assignments = $rows->assignments ?? collect();
              $listStatus = [
                'none' => 'Chưa có',
                'pending' => 'Đã nộp',
                'submitted' => 'Đã nộp',
                'approved' => 'Đã duyệt',
                'passed' => 'Đã duyệt',
                'rejected' => 'Bị từ chối',
                'failured' => 'Bị từ chối',
              ];
              $listColorStatus = [
                'none' => 'bg-slate-100 text-slate-600',
                'pending' => 'bg-amber-100 text-amber-700',
                'submitted' => 'bg-amber-100 text-amber-700',
                'approved' => 'bg-emerald-100 text-emerald-700',
                'passed' => 'bg-emerald-100 text-emerald-700',
                'rejected' => 'bg-rose-100 text-rose-700',
                'failured' => 'bg-rose-100 text-rose-700',
              ];
            @endphp
            <table class="w-full text-sm">
              <thead class="bg-slate-50">
                <tr class="text-left text-slate-600">
                  <th class="py-3 px-4 font-semibold">Sinh viên</th>
                  <th class="py-3 px-4 font-semibold">MSSV</th>
                  <th class="py-3 px-4 font-semibold">Đề tài</th>
                  <th class="py-3 px-4 font-semibold text-center">Trạng thái</th>
                  <th class="py-3 px-4 font-semibold">Lần nộp cuối</th>
                  <th class="py-3 px-4 font-semibold text-center">Hành động</th>
                </tr>
              </thead>
              <tbody id="rows" class="divide-y divide-slate-200">
                @foreach ($assignments as $assignment)
                  @if($assignment->assignment_supervisors[0]->status === 'accepted')
                    @php
                      $lastOutline = $assignment->project?->reportFiles?->sortByDesc('created_at')->where('type_report', 'outline')->first();
                      $status = $lastOutline->status ?? "none";
                    @endphp
                    <tr class="odd:bg-white even:bg-slate-50/50 hover:bg-slate-100 transition-colors"
                        data-last-outline-id="{{ $lastOutline?->id }}">
                      <td class="py-3 px-4">
                        <a class="text-blue-600 hover:underline font-medium"
                          href="{{ route('web.teacher.supervised_student_detail', ['studentId' => $assignment->student->id, 'termId' => $rows->id, 'supervisorId'=>$supervisorId]) }}">
                          {{ $assignment->student->user->fullname }}
                        </a>
                      </td>
                      <td class="py-3 px-4 text-slate-700">{{ $assignment->student->student_code }}</td>
                      <td class="py-3 px-4 text-slate-700">{{ $assignment->project->name ?? "Chưa có đề tài" }}</td>
                      <td class="py-3 px-4 text-center">
                        <span class="px-2 py-1 text-xs rounded-full {{ $listColorStatus[$status] }}" data-status-pill>
                           {{ $listStatus[$status] }}
                        </span>
                      </td>
                      <td class="py-3 px-4 text-slate-600">
                        {{ $lastOutline ? $lastOutline->created_at->format('H:i:s d/m/Y') : "Chưa có" }}
                      </td>
                      <td class="py-3 px-4 text-center">
                        @if ($lastOutline)
                          <div class="flex flex-col items-center gap-2" data-actions>
                            <a href="supervised-student-detail.html?id={{ $assignment->student->id }}&name={{ urlencode($assignment->student->user->name) }}"
                              class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition">
                              <i class="ph ph-eye text-slate-500"></i>
                              Xem
                            </a>
                            @if ($status === 'submitted' || $status === 'pending')
                              <div class="flex gap-2">
                                <button onclick="approveOutline('{{ $lastOutline->id }}', this)"
                                  class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-emerald-600 text-white shadow-sm hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-400 transition">
                                  <i class="ph ph-check-circle"></i>
                                  Phê duyệt
                                </button>
                                <button onclick="rejectOutline('{{ $lastOutline->id }}', this)"
                                  class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-rose-600 text-white shadow-sm hover:bg-rose-700 focus:ring-2 focus:ring-rose-400 transition">
                                  <i class="ph ph-x-circle"></i>
                                  Từ chối
                                </button>
                              </div>
                            @endif
                          </div>
                        @endif
                      </td>
                    </tr>
                  @endif
                @endforeach
              </tbody>
            </table>
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

    // Search filter for current table rows (client-side)
    (function(){
      const search = document.getElementById('search');
      const tbody  = document.getElementById('rows');
      if(!search || !tbody) return;
      const rows = Array.from(tbody.querySelectorAll('tr'));
      const norm = (s)=> (s||'').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'')
      const doFilter = ()=>{
        const q = norm(search.value.trim());
        if(!q){ rows.forEach(tr=>tr.classList.remove('hidden')); return; }
        rows.forEach(tr=>{
          const txt = norm(tr.innerText);
          tr.classList.toggle('hidden', !txt.includes(q));
        });
      };
      search.addEventListener('input', doFilter);
      search.addEventListener('keydown', (e)=>{ if(e.key==='Escape'){ search.value=''; doFilter(); } });
    })();

        // Show Stage 1 by default when the page loads
    window.addEventListener('DOMContentLoaded', function () {
      try { showStageDetails(1); } catch (e) { console.error('Init stage load failed:', e); }
      
     // Mở cứng submenu "Học phần tốt nghiệp" nếu đang ở trang chi tiết
     const submenu = document.getElementById('thesisSubmenu');
     const toggleBtn = document.getElementById('toggleThesisMenu');
     const caret = document.getElementById('thesisCaret');
     if (submenu && toggleBtn && caret) {
       submenu.classList.remove('hidden');
       toggleBtn.setAttribute('aria-expanded','true');
       caret.classList.add('rotate-180');
       // Tô đậm nhóm
       toggleBtn.classList.add('bg-slate-100','font-semibold');
     }
    });

    // Profile dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    profileBtn?.addEventListener('click', () => profileMenu.classList.toggle('hidden'));
    document.addEventListener('click', (e) => { if (!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

    // Toggle submenu "Học phần tốt nghiệp"
    const toggleBtn = document.getElementById('toggleThesisMenu');
    const thesisMenu = document.getElementById('thesisSubmenu');
    const thesisCaret = document.getElementById('thesisCaret');
    toggleBtn?.addEventListener('click', () => {
      const isHidden = thesisMenu?.classList.toggle('hidden');
      const expanded = !isHidden;
      toggleBtn?.setAttribute('aria-expanded', expanded ? 'true' : 'false');
      thesisCaret?.classList.toggle('rotate-180', expanded);
    });

   // Approve/Reject last outline via API, then update UI
   const STATUS_MAP = {
     none:      { label: 'Chưa có',  cls: 'bg-slate-100 text-slate-600' },
     submitted: { label: 'Đã nộp',   cls: 'bg-amber-100 text-amber-700' },
     approved:  { label: 'Đã duyệt', cls: 'bg-emerald-100 text-emerald-700' },
     rejected:  { label: 'Bị từ chối', cls: 'bg-rose-100 text-rose-700' },
   };
   async function sendOutlineStatus(id, status){
     const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
     const url = `${location.origin}/teacher/report-files/${id}/status`;
     const res = await fetch(url, {
       method: 'POST',
       headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept':'application/json' },
       body: JSON.stringify({ status })
     });
     if(!res.ok) throw new Error('Request failed');
     return res.json();
   }
   function applyPill(pill, status){
     const m = STATUS_MAP[status] || STATUS_MAP.none;
     pill.className = `px-2 py-1 text-xs rounded-full ${m.cls}`;
     pill.textContent = m.label;
   }
   function afterAction(btn, status){
     const tr = btn.closest('tr');
     const pill = tr?.querySelector('[data-status-pill]');
     if(pill) applyPill(pill, status);
     const actions = tr?.querySelector('[data-actions]');
     if(actions){
       actions.innerHTML = `<div class="text-xs text-slate-500">${status==='approved'?'Đã duyệt':'Đã từ chối'}</div>`;
     }
   }
   window.approveOutline = async function(reportId, btn){
     try{
       btn?.setAttribute('disabled','true');
       await sendOutlineStatus(reportId, 'approved');
       afterAction(btn, 'approved');
     }catch(e){ alert('Không thể cập nhật: ' + (e.message || 'Lỗi')); }
     finally{ btn?.removeAttribute('disabled'); }
   };
   window.rejectOutline = async function(reportId, btn){
     try{
       btn?.setAttribute('disabled','true');
       await sendOutlineStatus(reportId, 'rejected');
       afterAction(btn, 'rejected');
     }catch(e){ alert('Không thể cập nhật: ' + (e.message || 'Lỗi')); }
     finally{ btn?.removeAttribute('disabled'); }
   };
  </script>
</body>
</html>
