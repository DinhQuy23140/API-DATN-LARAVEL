<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>SV hoãn đồ án</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style> body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial} .sidebar{width:260px} .sidebar-collapsed .sidebar{width:72px} .sidebar-collapsed .sidebar-label{display:none} </style>
</head>
<body class="bg-slate-50 text-slate-800">
  @php
    $user = auth()->user();
    $userName = $user->fullname ?? $user->name ?? 'Người dùng';
    $email = $user->email ?? '';
    $avatarUrl = $user->avatar_url ?? $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
  @endphp

  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
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

          <div class="graduation-item">
            <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button bg-slate-100 font-semibold">
              <span class="flex items-center gap-3">
                <i class="ph ph-graduation-cap"></i>
                <span class="sidebar-label">Học phần tốt nghiệp</span>
              </span>
              <i class="ph ph-caret-down"></i>
            </div>
            <div id="gradMenu" class="submenu pl-6">
              <a href="#" class="block px-3 py-2 rounded hover:bg-slate-100"><i class="ph ph-briefcase"></i> Thực tập tốt nghiệp</a>
              <a href="{{ route('web.assistant.rounds') }}"
                 class="block px-3 py-2 rounded hover:bg-slate-100 bg-slate-100 font-semibold"
                 aria-current="page"><i class="ph ph-calendar"></i> Đồ án tốt nghiệp</a>
            </div>
          </div>
      </nav>
      <div class="p-3 border-t border-slate-200">
        <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
      </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 h-screen overflow-hidden flex flex-col">
      <!-- Header -->
      <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Sinh viên hoãn đồ án</h1>
            <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp / SV hoãn đồ án</nav>
            <div class="text-xs text-slate-500 mt-0.5">Đợt: {{ $termId }}</div>
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

      <!-- Content -->
      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
        <div class="max-w-6xl mx-auto space-y-6">
          <!-- Toolbar -->
          <section class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-sm">
                <i class="ph ph-warning"></i> Danh sách đã duyệt hoãn
              </span>
            </div>
            <div class="relative">
              <input id="searchBox" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 text-sm" placeholder="Tìm theo tên/MSSV" />
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
          </section>

          <!-- Table -->
          <section class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="overflow-x-auto rounded-xl">
              <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100/80 text-slate-700 text-xs uppercase sticky top-0 z-10">
                  <tr>
                    <th class="text-left px-4 py-3">MSSV</th>
                    <th class="text-left px-4 py-3 text-center">Họ tên</th>
                    <th class="text-left px-4 py-3 text-center">Lớp</th>
                    <th class="text-left px-4 py-3 text-center">Lý do</th>
                    <th class="text-left px-4 py-3 text-center">Minh chứng</th>
                    <th class="text-center px-4 py-3">Trạng thái</th>
                    <th class="text-left px-4 py-3 text-center">Ngày duyệt</th>
                    <th class="text-center px-4 py-3">Hành động</th>
                  </tr>
                </thead>
                <tbody id="deferList" class="divide-y divide-slate-100">
                  @foreach ($postponeProjectTerms as $postponeProjectTerm)
                    @php
                      $student = $postponeProjectTerm->assignment->student;
                      $user = $student->user;
                      $projectTerm = $postponeProjectTerm->assignment->projectTerm;
                      $postponeProjectTermFiles = $postponeProjectTerm->postponeProjectTermFiles;
                      $listStatus = ['pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'rejected' => 'Đã hủy'];
                      $listBgStatus = ['pending' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700'], 'approved' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700'], 'rejected' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700']];
                    @endphp
                    <tr class="hover:bg-slate-50 odd:bg-white even:bg-slate-50/40 transition">
                      <td class="px-4 py-3 font-mono">{{ $student->student_code }}</td>
                      <td class="px-4 py-3 text-center">{{ $user->fullname }}</td>
                      <td class="px-4 py-3 text-center">{{ $student->class_code }}</td>
                      <td class="px-4 py-3 text-center">{{ $postponeProjectTerm->note }}</td>
                      <td class="px-4 py-3 text-center">
                        @if ($postponeProjectTermFiles && $postponeProjectTermFiles->isNotEmpty())
                          @foreach ($postponeProjectTermFiles as $file)
                            <a href="#" class="inline-flex items-center gap-1 text-blue-600 hover:underline">
                              <i class="ph ph-paperclip"></i> {{ $file->file_name }}
                            </a>
                          @endforeach
                        @else
                          <span class="text-slate-400 italic">Không có</span>
                        @endif
                      </td>
                      <td class="px-4 py-3 text-center">
                        <span class="statusPill px-2 py-0.5 rounded-full text-xs {{ $listBgStatus[$postponeProjectTerm->status]['bg'] }} {{ $listBgStatus[$postponeProjectTerm->status]['text'] }}">{{ $listStatus[$postponeProjectTerm->status] }}</span>
                      </td>
                      @if ($postponeProjectTerm->status === 'pending')
                      <td class="px-4 py-3 text-center">--</td>
                        <td class="px-4 py-3 text-right space-x-2">
                          <button id="btnApprove" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-emerald-200 text-emerald-700 hover:bg-emerald-50 transition"
                                  data-id="{{ $postponeProjectTerm->id }}" data-student="{{ $user->fullname }}" data-note="{{ $postponeProjectTerm->note }}" data-assignment-id="{{ $postponeProjectTerm->assignment_id }}" data-status="approved" data-project-term-id="{{ $postponeProjectTerm->project_term_id }}">
                            <i class="ph ph-check"></i> Duyệt
                          </button>
                          <button id="btnReject" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 hover:bg-rose-50 transition"
                                  data-id="{{ $postponeProjectTerm->id }}" data-student="{{ $user->fullname }}" data-note="{{ $postponeProjectTerm->note }}" data-assignment-id="{{ $postponeProjectTerm->assignment_id }}" data-status="rejected" data-project-term-id="{{ $postponeProjectTerm->project_term_id }}">
                            <i class="ph ph-x"></i> Hủy
                          </button>
                        </td>
                      @else
                        <td class="px-4 py-3 text-center">{{ $postponeProjectTerm->updated_at }}</td>
                      @endif
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
    // Sidebar collapse (đồng bộ round-detail)
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
    document.getElementById('openSidebar')?.addEventListener('click',()=> sidebar.classList.toggle('-translate-x-full'));
    if(localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);
    sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

    // Profile dropdown (đồng bộ round-detail)
    const profileBtn=document.getElementById('profileBtn');
    const profileMenu=document.getElementById('profileMenu');
    profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
    document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

    // Submenu “Học phần tốt nghiệp” mở sẵn và toggle giống round-detail
    document.addEventListener('DOMContentLoaded', () => {
      const wrap = document.querySelector('.graduation-item');
      if (!wrap) return;
      const toggleBtn = wrap.querySelector('.toggle-button');
      const submenu = wrap.querySelector('.submenu');
      const caret = wrap.querySelector('.ph.ph-caret-down');
      if (submenu && submenu.classList.contains('hidden')) submenu.classList.remove('hidden');
      if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
      caret?.classList.add('transition-transform','rotate-180');
      toggleBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        submenu?.classList.toggle('hidden');
        const expanded = !submenu?.classList.contains('hidden');
        toggleBtn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        caret?.classList.toggle('rotate-180', expanded);
      });
    });

    // simple search
    document.getElementById('searchBox')?.addEventListener('input', (e)=>{
      const q=(e.target.value||'').toLowerCase();
      document.querySelectorAll('#deferList tr').forEach(tr=>{
        tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
      });
    });

    // Helpers: đổi trạng thái pill
    function setStatusPill(tr, status) {
      const pill = tr.querySelector('.statusPill');
      if (!pill) return;
      pill.className = 'statusPill px-2 py-0.5 rounded-full text-xs';
      if (status === 'approved') {
        pill.classList.add('bg-emerald-50','text-emerald-700');
        pill.textContent = 'Đã duyệt';
      } else if (status === 'rejected') {
        pill.classList.add('bg-rose-50','text-rose-700');
        pill.textContent = 'Đã hủy';
      } else {
        pill.classList.add('bg-slate-100','text-slate-700');
        pill.textContent = 'Chờ duyệt';
      }
    }

    // Ủy quyền click cho nút Duyệt/Hủy
    document.getElementById('deferList')?.addEventListener('click', async (e) => {
      const approveBtn = e.target.closest('.btnApprove');
      const rejectBtn  = e.target.closest('.btnReject');
      if (!approveBtn && !rejectBtn) return;

      const btn = approveBtn || rejectBtn;
      const tr = btn.closest('tr');
      const id = btn.dataset.id;
      const name = btn.dataset.student || tr.querySelectorAll('td')[1]?.textContent?.trim() || 'sinh viên';
      const isApprove = !!approveBtn;

      if (!id) { alert('Thiếu ID đơn hoãn'); return; }
      const ok = confirm(`${isApprove ? 'Duyệt' : 'Hủy'} đơn hoãn của ${name}?`);
      if (!ok) return;

      // Hiển thị loading nhỏ trên nút
      const old = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i>';
    });

    document.getElementById('btnApprove').addEventListener('click', async function(){
      const id = this.dataset.id;
      const assignmentId = this.dataset.assignmentId;
      const status = this.dataset.status;
      const projectTermId = this.dataset.projectTermId;
      const note = this.dataset.note;
      const data = {
        id: id,
        assignment_id: assignmentId,
        project_term_id: projectTermId,
        status: status,
        note: note
      }
      try{
        const response = await fetch("{{ route('web.assistant.postpone_project_terms.approved_deferments', ['postponeProjectTerm' => '__ID__']) }}".replace('__ID__', id), {
          method: 'PATCH',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(data)
        })

        if (!response.ok) {
          throw new Error('Network response was not ok');
        }

        const result = await response.json();
        if (result.success) {
          // reload to sync UI/counts
          location.reload();
          return;
        } else {
          throw new Error(result.message || 'Lỗi không xác định từ server');
        }
      } catch (err) {
        console.error(err);
        alert('Lỗi: ' + (err.message || 'Không xác định'));
      }
    })

    document.getElementById('btnReject').addEventListener('click', async function(){
      const id = this.dataset.id;
      const assignmentId = this.dataset.assignmentId;
      const status = this.dataset.status;
      const projectTermId = this.dataset.projectTermId;
      const note = this.dataset.note;
      const data = {
        id: id,
        assignment_id: assignmentId,
        project_term_id: projectTermId,
        status: status,
        note: note
      }
      try {
        const response = await fetch("{{ route('web.assistant.postpone_project_terms.rejected_deferments', ['postponeProjectTerm' => '__ID__']) }}".replace('__ID__', id), {
          method: 'PATCH',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(data)
        });

        if (!response.ok) {
          throw new Error('Network response was not ok');
        }

        const result = await response.json();
        if (result.success) {
          // reload to sync UI/counts
          location.reload();
          return;
        } else {
          throw new Error(result.message || 'Lỗi không xác định từ server');
        }
      } catch (err) {
        console.error(err);
        alert('Lỗi: ' + (err.message || 'Không xác định'));
      }
    })
  </script>
</body>
</html>