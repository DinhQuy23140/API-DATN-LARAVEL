<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chấm đề cương - Phân công</title>
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
    $teacherId = $user->teacher->id ?? 0;
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
            href="{{ route('web.teacher.students', ['supervisorId' => $user->teacher->supervisor->id]) }}"
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
            <h1 class="text-lg md:text-xl font-semibold">Chấm đề cương - Phân công</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-round-detail.html" class="hover:underline text-slate-600">Chi tiết đợt</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Chấm đề cương (được phân chấm)</span>
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
            <a href="{{ route('web.teacher.thesis_round_detail', ['termId' => $termId, 'supervisorId' => $supervisorId]) }}" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại chi tiết đợt</a>
          </div>

          <div class="bg-white border rounded-xl p-4">
            <div class="flex items-center gap-2 text-xs mb-3">
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-700"><span class="h-2 w-2 rounded-full bg-slate-400"></span> Chưa chấm</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700"><span class="h-2 w-2 rounded-full bg-amber-400"></span> Đang chấm</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Đã chấm</span>
            </div>
<div class="overflow-x-auto">
  <table class="w-full text-sm border border-slate-200 rounded-xl shadow-sm overflow-hidden">
    <thead class="bg-slate-100">
      <tr class="text-slate-600">
        <th class="py-3 px-3 text-left">Sinh viên</th>
        <th class="py-3 px-3 text-left">MSSV</th>
        <th class="py-3 px-3 text-left">Đề tài</th>
        <th class="py-3 px-3 text-center">Trạng thái chấm</th>
        <th class="py-3 px-3 text-center">Thời gian nộp</th>
        <th class="py-3 px-3 text-center">Hành động</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-200">
      @foreach ($rows as $row)
        @php
          $fullname = $row->student->user->fullname;
          $student_code = $row->student->student_code;
          $topic = $row->project->name ?? 'Chưa có đề tài';
          $status = $row->counter_argument_status ?? 'pending';
          $lastOutline = $row->project?->reportFiles()->latest('created_at')->first();
          $lastOutlineDate = $lastOutline ? $lastOutline->created_at->format('H:i:s d/m/Y') : 'Chưa nộp báo cáo';
          $fileUrl = $lastOutline ? $lastOutline->file_url : '#';

          $statusLabels = [
            'approved' => ['label' => 'Đã duyệt',   'color' => 'bg-emerald-100 text-emerald-700'],
            'rejected' => ['label' => 'Từ chối',    'color' => 'bg-rose-100 text-rose-700'],
            'pending'  => ['label' => 'Chưa duyệt',  'color' => 'bg-slate-100 text-slate-600'],
            'todo'     => ['label' => 'Cần chấm',   'color' => 'bg-amber-100 text-amber-700'],
            'progress' => ['label' => 'Đang chấm',  'color' => 'bg-blue-100 text-blue-700'],
            'done'     => ['label' => 'Hoàn tất',   'color' => 'bg-emerald-100 text-emerald-700'],
          ];
          $st = $statusLabels[$status] ?? $statusLabels['pending'];
        @endphp
        <tr class="hover:bg-slate-50" data-assignment-id="{{ $row->id }}">
          <td class="py-3 px-3 text-left">{{ $fullname }}</td>
          <td class="py-3 px-3 text-left">{{ $student_code }}</td>
          <td class="py-3 px-3 text-left max-w-xs break-words">{{ $topic }}</td>
          <td class="py-3 px-3 text-center">
            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $st['color'] }}" data-status-pill>
              {{ $st['label'] }}
            </span>
          </td>
          <td class="py-3 px-3 text-center">{{ $lastOutlineDate }}</td>
          <td class="py-3 px-3 text-left">
            <div class="flex flex-col gap-2 items-center" data-actions>
              <a class="px-3 py-1 border border-slate-200 rounded text-xs hover:bg-slate-100 transition"
                 href="{{ $fileUrl }}">
                Tải đề cương
              </a>
              @if ($status === 'pending')
                <div class="flex gap-2">
                  <button class="px-3 py-1 bg-emerald-600 text-white rounded text-xs hover:bg-emerald-700 transition"
                          onclick="approveOutline('{{ $row->id }}', this)">
                    Phê duyệt
                  </button>
                  <button class="px-3 py-1 bg-rose-600 text-white rounded text-xs hover:bg-rose-700 transition"
                          onclick="rejectOutline('{{ $row->id }}', this)">
                    Từ chối
                  </button>
                </div>
              @endif
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Grade modal -->
  <div id="gradeModal" class="fixed inset-0 bg-black/30 hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-xl shadow border p-4">
      <div class="flex items-center justify-between mb-2">
        <div class="font-semibold">Chấm điểm đề cương</div>
        <button id="closeModal" class="p-1 rounded hover:bg-slate-100"><i class="ph ph-x"></i></button>
      </div>
      <div id="gradeBody" class="space-y-3 text-sm">
        <!-- dynamic -->
      </div>
      <div class="mt-3 flex justify-end gap-2">
        <button id="saveGrade" class="px-3 py-2 rounded bg-blue-600 text-white text-sm">Lưu</button>
      </div>
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

    // Mock assignments - could be replaced by localStorage integration
    let assignments = [
      { id:'20210011', name:'Phạm Thị D', topic:'Website bán hàng', status:'todo', last:'02/08/2025', file:'#', score:null, comment:'' },
      { id:'20210012', name:'Võ Văn E', topic:'Ứng dụng đặt xe', status:'progress', last:'03/08/2025', file:'#', score:6.5, comment:'Cần bổ sung mục tiêu.' },
      { id:'20210013', name:'Đỗ Thị F', topic:'Quản lý kho', status:'done', last:'04/08/2025', file:'#', score:8.0, comment:'Tốt.' }
    ];

    function statusPill(s){
      if(s==='done') return '<span class="px-2 py-0.5 rounded-full text-xs bg-emerald-50 text-emerald-700">Đã chấm</span>';
      if(s==='progress') return '<span class="px-2 py-0.5 rounded-full text-xs bg-amber-50 text-amber-700">Đang chấm</span>';
      return '<span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700">Chưa chấm</span>';
    }

    function render(){
      const q=(document.getElementById('search').value||'').toLowerCase();
      const rows=document.getElementById('rows');
      const list = assignments.filter(s=> s.name.toLowerCase().includes(q) || s.id.includes(q) || s.topic.toLowerCase().includes(q));
    }

    document.getElementById('search').addEventListener('input', render);

    // Grading modal logic
    let currentId=null;
    function openGrade(id){
      currentId=id;
      const s=assignments.find(x=>x.id===id);
      const body=document.getElementById('gradeBody');
      body.innerHTML = `
        <div><span class="text-slate-500">Sinh viên:</span> <span class="font-medium">${s.name} (${s.id})</span></div>
        <div><span class="text-slate-500">Đề tài:</span> <span class="font-medium">${s.topic}</span></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">
          <label class="text-sm">Điểm (0-10)
            <input id="gradeScore" type="number" min="0" max="10" step="0.1" class="mt-1 w-full border rounded px-2 py-1" value="${s.score??''}">
          </label>
          <label class="text-sm">Trạng thái
            <select id="gradeStatus" class="mt-1 w-full border rounded px-2 py-1">
              <option value="todo" ${s.status==='todo'?'selected':''}>Chưa chấm</option>
              <option value="progress" ${s.status==='progress'?'selected':''}>Đang chấm</option>
              <option value="done" ${s.status==='done'?'selected':''}>Đã chấm</option>
            </select>
          </label>
        </div>
        <label class="text-sm block">Nhận xét
          <textarea id="gradeComment" class="mt-1 w-full border rounded px-2 py-1" rows="4">${s.comment||''}</textarea>
        </label>
      `;
      document.getElementById('gradeModal').classList.remove('hidden');
      document.getElementById('gradeModal').classList.add('flex');
    }
    window.openGrade=openGrade;

    document.getElementById('closeModal').addEventListener('click',()=>{
      document.getElementById('gradeModal').classList.add('hidden');
      document.getElementById('gradeModal').classList.remove('flex');
    });

    document.getElementById('saveGrade').addEventListener('click',()=>{
      if(!currentId) return;
      const s=assignments.find(x=>x.id===currentId);
      s.score = parseFloat(document.getElementById('gradeScore').value)||null;
      s.status = document.getElementById('gradeStatus').value;
      s.comment = document.getElementById('gradeComment').value.trim();
      localStorage.setItem('outline_review_assignments', JSON.stringify(assignments));
      render();
      document.getElementById('gradeModal').classList.add('hidden');
      document.getElementById('gradeModal').classList.remove('flex');
    });

    // init
    const saved = localStorage.getItem('outline_review_assignments');
    if(saved){ try{ assignments = JSON.parse(saved);}catch(e){} }
    render();

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

    // Helper: đổi pill theo status
    function applyStatusPill(pillEl, status, labelClass){
      if(!pillEl) return;
      pillEl.className = 'px-2 py-1 rounded-full text-xs font-medium ' + (labelClass || '');
      const labelMap = {
        approved: 'Đã duyệt',
        rejected: 'Từ chối',
        pending: 'Chưa chấm',
        todo: 'Cần chấm',
        progress: 'Đang chấm',
        done: 'Hoàn tất',
      };
      pillEl.textContent = labelMap[status] || 'Chưa chấm';
    }

    // Cập nhật khu vực hành động sau khi duyệt/từ chối
    function updateRowAfterStatus(tr, status){
      if(!tr) return;
      tr.setAttribute('data-status', status);
      const actions = tr.querySelector('[data-actions]');
      if(actions){
        const msg = status === 'approved' ? 'Đã duyệt' : (status === 'rejected' ? 'Đã từ chối' : 'Đã cập nhật');
        actions.innerHTML = `
          <div class="text-xs text-slate-500">${msg}</div>
          <a class="px-3 py-1 border border-slate-200 rounded text-xs hover:bg-slate-100 transition"
             href="#">
            Tải đề cương</a>
        `;
      }
    }

    async function sendCounterStatus(id, status){
      const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
      const url = `{{ url('/teacher/assignments') }}/${id}/counter-status`;
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ status })
      });
      if(!res.ok) throw new Error('Request failed');
      return res.json();
    }

    window.approveOutline = async function(id, btn){
      try{
        btn?.setAttribute('disabled','true');
        const data = await sendCounterStatus(id, 'approved');
        const tr = document.querySelector(`tr[data-assignment-id="${id}"]`);
        const pill = tr?.querySelector('[data-status-pill]');
        applyStatusPill(pill, data.status, data.class);
        updateRowAfterStatus(tr, data.status);
      }catch(e){
        alert('Không thể cập nhật: ' + (e.message || 'Lỗi không xác định'));
      }finally{
        btn?.removeAttribute('disabled');
      }
    };

    window.rejectOutline = async function(id, btn){
      try{
        btn?.setAttribute('disabled','true');
        const data = await sendCounterStatus(id, 'rejected');
        const tr = document.querySelector(`tr[data-assignment-id="${id}"]`);
        const pill = tr?.querySelector('[data-status-pill]');
        applyStatusPill(pill, data.status, data.class);
        updateRowAfterStatus(tr, data.status);
      }catch(e){
        alert('Không thể cập nhật: ' + (e.message || 'Lỗi không xác định'));
      }finally{
        btn?.removeAttribute('disabled');
      }
    };
  </script>
</body>
</html>
