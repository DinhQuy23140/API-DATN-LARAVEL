<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Quản lý sinh viên</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <style>
    body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
    .sidebar { width: 260px; }
    .sidebar-collapsed .sidebar { width: 72px; }
    .sidebar-collapsed .sidebar-label { display: none; }
  </style>
</head>
<body class="bg-slate-50 text-slate-800">
@php
  $user = auth()->user();
  $userName = $user->fullname ?? $user->name ?? 'Quản trị viên';
  $email = $user->email ?? '';
  $avatarUrl = $user->avatar_url ?? $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';

@endphp

<div class="flex min-h-screen">
  <!-- Sidebar -->
      <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
        <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
          <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-buildings"></i></div>
          <div class="sidebar-label">
            <div class="font-semibold">UniAdmin</div>
            <div class="text-xs text-slate-500">Quản trị hệ thống</div>
          </div>
        </div>
        <nav class="flex-1 overflow-y-auto p-3">
          <a href="{{ route('web.admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">
            <i class="ph ph-gauge"></i> <span class="sidebar-label">Bảng điều khiển</span>
          </a>
          <a href="{{ route('web.admin.manage_faculties') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">
            <i class="ph ph-graduation-cap"></i> <span class="sidebar-label">Quản lý Khoa</span>
          </a>
          <a href="{{ route('web.admin.manage_assistants') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">
            <i class="ph ph-users-three"></i> <span class="sidebar-label">Trợ lý khoa</span>
          </a>
          <a href="{{ route('web.admin.manage_students') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 bg-slate-100 font-bold">
            <i class="ph ph-users"></i> <span class="sidebar-label">Quản lý Sinh viên</span>
          </a>
          <a href="{{ route('web.admin.manage_lecturers') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">
            <i class="ph ph-chalkboard-teacher"></i> <span class="sidebar-label">Quản lý Giảng viên</span>
          </a>
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
          <h1 class="text-lg md:text-xl font-semibold">Quản lý sinh viên</h1>
          <nav aria-label="Breadcrumb" class="mt-0.5 text-xs text-slate-500">
            <ol class="flex items-center gap-1">
              <li><a href="{{ route('web.admin.dashboard') }}" class="hover:text-slate-700">Dashboard</a><span class="text-slate-400">/</span></li>
              <li class="text-slate-700 font-medium">Sinh viên</li>
            </ol>
          </nav>
        </div>
      </div>
      <div class="relative">
        <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100" aria-expanded="false">
          <img class="h-9 w-9 rounded-full object-cover" src="{{ $avatarUrl }}" alt="avatar" />
          <div class="hidden sm:block text-left">
            <div class="text-sm font-semibold leading-4">{{ $userName }}</div>
            <div class="text-xs text-slate-500">{{ $email }}</div>
          </div>
          <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
        </button>
        <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
          <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Thông tin</a>
          <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
          <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
      </div>
    </header>

    <!-- Content -->
    <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
      <div class="max-w-7xl mx-auto space-y-6">
        <!-- Toolbar -->
        <section class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
          <div class="flex items-center gap-2">
            <button id="btnAdd" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm">
              <i class="ph ph-plus"></i> Thêm sinh viên
            </button>
            <button id="btnExport" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border text-slate-700 hover:bg-slate-50 text-sm">
              <i class="ph ph-download-simple"></i> Xuất danh sách
            </button>
          </div>
          <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
            <div class="relative">
              <input id="searchInput" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo MSSV, họ tên, email">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <select id="filterStatus" class="px-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
              <option value="">Tất cả trạng thái</option>
              <option value="active">Đang học</option>
              <option value="pending">Chờ xác nhận</option>
              <option value="paused">Tạm dừng</option>
            </select>
          </div>
        </section>

        <!-- Table -->
        <section class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
              <tr>
                <th class="text-left px-4 py-3 w-12"><input type="checkbox" id="chkAll" class="h-4 w-4"></th>
                <th class="text-left px-4 py-3">MSSV</th>
                <th class="text-left px-4 py-3">Họ tên</th>
                <th class="text-left px-4 py-3">Lớp</th>
                <th class="text-left px-4 py-3">Email</th>
                <th class="text-center px-4 py-3">Ngành</th>
                <th class="text-right px-4 py-3">Hành động</th>
              </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-slate-100">
              @foreach ($students as $st)
                @php
                  $status = $st->status ?? 'active';
                @endphp
                <tr class="hover:bg-slate-50" data-status="{{ $status }}">
                  <td class="px-4 py-3"><input type="checkbox" class="rowChk h-4 w-4"></td>
                  <td class="px-4 py-3 font-mono">{{ $st->student_code ?? '-' }}</td>
                  <td class="px-4 py-3 font-medium text-slate-800">{{ $st->fullname ?? ($st->user->fullname ?? '-') }}</td>
                  <td class="px-4 py-3">{{ $st->class_code ?? ($st->classroom->name ?? '-') }}</td>
                  <td class="px-4 py-3">{{ $st->email ?? ($st->user->email ?? '-') }}</td>
                  <td class="px-4 py-3 text-center">
                    {{ $st->marjor->name ??  '-' }}
                  </td>
                  <td class="px-4 py-3 text-right space-x-2">
          <button class="btnEdit px-2 py-1.5 rounded-lg border hover:bg-slate-50 text-indigo-600"
          data-id="{{ $st->id }}"
          data-code="{{ $st->student_code ?? '' }}"
          data-name="{{ $st->fullname ?? ($st->user->fullname ?? '') }}"
          data-classname="{{ $st->class_code ?? ($st->classroom->name ?? '') }}"
          data-email="{{ $st->email ?? ($st->user->email ?? '') }}"
          data-phone="{{ $st->phone ?? ($st->user->phone ?? '') }}"
          data-dob="{{ $st->dob ?? ($st->user->dob ?? '') }}"
          data-major="{{ $st->marjor->id ?? '' }}"
          data-status="{{ $status }}">
                      <i class="ph ph-pencil"></i>
                    </button>
                    <button class="btnDelete px-2 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600" data-id="{{ $st->user->id }}">
                      <i class="ph ph-trash"></i>
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </section>

        <!-- Pagination (tĩnh nếu chưa có paginate) -->
        @if(method_exists($students, 'links'))
          <div>{{ $students->links() }}</div>
        @endif
      </div>
    </main>
  </div>
</div>

<!-- Modal: Thêm/Sửa sinh viên -->
<div id="studentModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/40" data-close></div>
  <div class="relative z-10 bg-white w-full max-w-5xl rounded-2xl shadow-2xl overflow-hidden md:overflow-visible max-h-[calc(100vh-2rem)]">
    <div class="md:flex">
      <!-- Left accent panel -->
      <div class="hidden md:flex md:w-1/3 bg-gradient-to-b from-indigo-600 to-blue-600 text-white p-6 flex-col items-center justify-center gap-4">
        <div class="h-20 w-20 rounded-full bg-white/10 grid place-items-center text-white text-3xl font-bold">
          <i class="ph ph-users"></i>
        </div>
        <div class="text-center">
          <h3 id="modalTitle" class="text-lg font-semibold">Thêm sinh viên</h3>
          <p class="text-sm opacity-90">Quản lý thông tin sinh viên & tài khoản</p>
        </div>
        <div class="text-xs text-white/80">Các thay đổi sẽ cập nhật ngay sau khi lưu.</div>
      </div>

      <!-- Form area -->
      <div class="w-full md:w-2/3 bg-white p-6">
        <div class="flex items-start justify-between mb-4">
          <div class="flex items-center gap-3">
            <div class="md:hidden h-12 w-12 rounded-full bg-indigo-600 grid place-items-center text-white text-xl"><i class="ph ph-users"></i></div>
            <div>
              <h3 id="modalTitleMobile" class="font-semibold">Thêm sinh viên</h3>
              <p class="text-sm text-slate-500">Điền đầy đủ thông tin, sau đó nhấn Lưu</p>
            </div>
          </div>
          <button class="text-slate-500 hover:text-slate-700" data-close aria-label="Đóng"><i class="ph ph-x"></i></button>
        </div>

        <div class="space-y-4 overflow-y-auto md:overflow-visible max-h-[60vh] md:max-h-none pr-2">
          <input type="hidden" id="stId">

          <div>
            <label class="text-xs text-slate-500">MSSV</label>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-indigo-50 text-indigo-600"><i class="ph ph-hash"></i></span>
              <input id="stCode" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="VD: 20190001">
            </div>
          </div>

          <div>
            <label class="text-xs text-slate-500">Họ tên</label>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-emerald-50 text-emerald-600"><i class="ph ph-user"></i></span>
              <input id="stName" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400" placeholder="Họ và tên">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="text-xs text-slate-500">Lớp</label>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-sky-50 text-sky-600"><i class="ph ph-chalkboard"></i></span>
                <input id="stClass" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
              </div>
            </div>

            <div>
              <label class="text-xs text-slate-500">Email</label>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-amber-50 text-amber-600"><i class="ph ph-envelope"></i></span>
                <input id="stEmail" type="email" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-400 focus:border-amber-400" placeholder="email@domain.edu">
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="text-xs text-slate-500">Mật khẩu (mới)</label>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-rose-50 text-rose-600"><i class="ph ph-key"></i></span>
                <input id="stPassword" type="password" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-rose-400 focus:border-rose-400" placeholder="Để trống nếu không đổi">
              </div>
            </div>

            <div>
              <label class="text-xs text-slate-500">Ngành học</label>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-violet-50 text-violet-600"><i class="ph ph-books"></i></span>
                @php $majorOptions = $majors ?? $marjors ?? collect(); @endphp
                <select id="stMajor" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-violet-400 focus:border-violet-400">
                  <option value="">-- Chọn ngành --</option>
                  @foreach($majors as $m)
                    @php $mid = $m->id ?? ($m['id'] ?? $m); $mname = $m->name ?? ($m['name'] ?? ($m->code ?? $m)); @endphp
                    <option value="{{ $mid }}">{{ $mname }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="text-xs text-slate-500">Số điện thoại</label>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-slate-50 text-slate-600"><i class="ph ph-phone"></i></span>
                <input id="stPhone" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-400 focus:border-slate-400" placeholder="Số điện thoại">
              </div>
            </div>
            <div>
              <label class="text-xs text-slate-500">Ngày sinh</label>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-sky-50 text-sky-600"><i class="ph ph-calendar"></i></span>
                <input id="stDob" type="date" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
              </div>
            </div>
          </div>
        </div>

        <div class="mt-4 flex items-center justify-end gap-3">
          <button class="px-4 py-2 rounded-lg border text-sm hover:bg-slate-50" data-close>Đóng</button>
          <button id="btnSave" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm">
            <i class="ph ph-check"></i> Lưu
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Sidebar collapse giống dashboard
  const html = document.documentElement, sidebar = document.getElementById('sidebar');
  function setCollapsed(c){
    const mainArea = document.querySelector('.flex-1');
    if(c){ html.classList.add('sidebar-collapsed');}
    else { html.classList.remove('sidebar-collapsed'); }
  }
  document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('admin_sidebar',''+(c?1:0));});
  document.getElementById('openSidebar')?.addEventListener('click',()=> sidebar.classList.toggle('-translate-x-full'));
  if(localStorage.getItem('admin_sidebar')==='1') setCollapsed(true);
  sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

  // Profile dropdown: toggle + click outside + ESC (đồng bộ dashboard)
  const profileBtn = document.getElementById('profileBtn');
  const profileMenu = document.getElementById('profileMenu');
  function closeProfileMenu(){ profileMenu?.classList.add('hidden'); profileBtn?.setAttribute('aria-expanded','false'); }
  function openProfileMenu(){ profileMenu?.classList.remove('hidden'); profileBtn?.setAttribute('aria-expanded','true'); }
  profileBtn?.addEventListener('click',(e)=>{ e.stopPropagation(); const isHidden=profileMenu?.classList.contains('hidden'); if(isHidden) openProfileMenu(); else closeProfileMenu(); });
  document.addEventListener('click',(e)=>{ if(!profileMenu||!profileBtn) return; if(!profileMenu.contains(e.target)&&!profileBtn.contains(e.target)) closeProfileMenu(); });
  document.addEventListener('keydown',(e)=>{ if(e.key==='Escape') closeProfileMenu(); });

  // Modal helpers
  const modal = document.getElementById('studentModal');
  function openModal(){ modal.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); }
  function closeModal(){ modal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
  modal?.addEventListener('click',(e)=>{ if(e.target.matches('[data-close]')) closeModal(); });

  // Toolbar actions
  document.getElementById('btnAdd')?.addEventListener('click', () => {
    document.getElementById('modalTitle').textContent = 'Thêm sinh viên';
    document.getElementById('stId').value = '';
    document.getElementById('stCode').value = '';
    document.getElementById('stName').value = '';
    document.getElementById('stClass').value = '';
    document.getElementById('stEmail').value = '';
    document.getElementById('stPassword').value = '';
    document.getElementById('stMajor').value = '';
    document.getElementById('stPhone').value = '';
    document.getElementById('stDob').value = '';
    openModal();
  });

  // Search + filter (client-side)
  const searchInput = document.getElementById('searchInput');
  const filterStatus = document.getElementById('filterStatus');
  function applyFilter(){
    const q = (searchInput?.value || '').toLowerCase();
    const st = filterStatus?.value || '';
    document.querySelectorAll('#tableBody tr').forEach(tr => {
      const text = tr.innerText.toLowerCase();
      const okQ = !q || text.includes(q);
      const okS = !st || tr.getAttribute('data-status') === st;
      tr.style.display = okQ && okS ? '' : 'none';
    });
  }
  searchInput?.addEventListener('input', applyFilter);
  filterStatus?.addEventListener('change', applyFilter);

  // Edit row
  document.getElementById('tableBody')?.addEventListener('click', (e)=>{
    const btn = e.target.closest('.btnEdit');
    if(!btn) return;
    document.getElementById('modalTitle').textContent = 'Sửa sinh viên';
    document.getElementById('stId').value = btn.dataset.id || '';
    document.getElementById('stCode').value = btn.dataset.code || '';
    document.getElementById('stName').value = btn.dataset.name || '';
  document.getElementById('stClass').value = btn.dataset.classname || '';
    document.getElementById('stEmail').value = btn.dataset.email || '';
    // Do not populate password for security reasons
    document.getElementById('stPassword').value = '';
    document.getElementById('stMajor').value = btn.dataset.major || '';
    document.getElementById('stPhone').value = btn.dataset.phone || '';
    document.getElementById('stDob').value = btn.dataset.dob || '';
    openModal();
  });

  // Delete row (confirm)
  document.getElementById('tableBody')?.addEventListener('click', async (e)=>{
    const btn = e.target.closest('.btnDelete');
    if(!btn) return;
    const id = btn.dataset.id;
    if(!id) return alert('Thiếu ID sinh viên');
    if(!confirm('Xóa sinh viên này?')) return;

    const old = btn.innerHTML; btn.disabled = true; btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i>';
    const url = '{{ route("web.users.destroy", "__id__") }}'.replace('__id__', id);
    try {
      const res = await fetch(url, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
          'Accept': 'application/json'
        }
      });

      // Try parse JSON; if not JSON, fallback to empty object
      let data = {};
      try { data = await res.json(); } catch (parseErr) { data = {}; }

      // If HTTP status indicates error, surface message
      if (!res.ok) {
        throw new Error(data.message || `HTTP ${res.status}`);
      }

      // If server returned structured JSON with ok:false
      if (typeof data.ok !== 'undefined' && data.ok === false) {
        throw new Error(data.message || 'Xóa thất bại');
      }

      // Success — remove row
      btn.closest('tr')?.remove();
    } catch (err) {
      console.error('Delete failed', err);
      alert(err?.message || 'Không thể xóa. Vui lòng thử lại.');
    } finally {
      btn.disabled = false; btn.innerHTML = old;
    }
  });

  // Save (create/update) — call API for update, fallback to local for new rows
  document.getElementById('btnSave')?.addEventListener('click', async ()=>{
    const id = document.getElementById('stId').value.trim();
    const code = document.getElementById('stCode').value.trim();
    const name = document.getElementById('stName').value.trim();
    const className = document.getElementById('stClass').value.trim();
    const email = document.getElementById('stEmail').value.trim();
    const password = document.getElementById('stPassword').value;
    const majorSelect = document.getElementById('stMajor');
    const major = (majorSelect?.value || '').toString().trim();
    const majorLabel = (majorSelect?.selectedOptions && majorSelect.selectedOptions[0]) ? (majorSelect.selectedOptions[0].text || major) : (major || '-');
    const phone = document.getElementById('stPhone').value.trim();
    const dob = document.getElementById('stDob').value;

    if(!code || !name){ alert('Vui lòng nhập MSSV và Họ tên'); return; }

    // If this is an existing persisted student (not a local new-* row), call the PUT route
    if(id && !id.startsWith('new-')){
      const btn = document.getElementById('btnSave');
      const oldHtml = btn.innerHTML;
      btn.disabled = true; btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> Đang lưu...';

      const url = '{{ route("web.admin.manage_students.update", "__id__") }}'.replace('__id__', id);
      try {
        const payload = {
          student_code: code,
          class_code: className || null,
          marjor_id: major || null,
          fullname: name,
          email: email || null,
          phone: phone || null,
          dob: dob || null
        };
        if(password) payload.password = password;

        const res = await fetch(url, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
          },
          body: JSON.stringify(payload)
        });

        const data = await res.json().catch(()=>({}));

        if(res.status === 422 && data.errors){
          // validation errors
          const messages = Object.values(data.errors).flat().join('\n');
          alert(messages);
          return;
        }

        if(!res.ok || data.ok === false){
          throw new Error(data.message || 'Lưu thất bại');
        }

        // Success — update row from returned student
        const student = data.student || {};
        const tr = [...document.querySelectorAll('#tableBody tr')].find(x => x.querySelector('.btnEdit')?.dataset.id === id);
        if(tr){
          if(student.student_code) tr.children[1].textContent = student.student_code;
          tr.children[2].textContent = (student.user && student.user.fullname) || student.fullname || name;
          tr.children[3].textContent = student.class_code || className || '-';
          tr.children[4].textContent = (student.user && student.user.email) || student.email || email || '-';
          if(tr.children[5]){
            tr.children[5].textContent = (student.marjor && student.marjor.name) || majorLabel || '-';
          }
          const editBtn = tr.querySelector('.btnEdit');
          editBtn.dataset.code = student.student_code || code;
          editBtn.dataset.name = (student.user && student.user.fullname) || student.fullname || name;
          editBtn.dataset.classname = student.class_code || className || '';
          editBtn.dataset.email = (student.user && student.user.email) || student.email || '';
          editBtn.dataset.major = (student.marjor && student.marjor.id) || major || '';
          editBtn.dataset.phone = (student.user && student.user.phone) || phone || '';
          editBtn.dataset.dob = (student.user && student.user.dob) || dob || '';
        }

        closeModal();
      } catch(err){
        console.error('Update failed', err);
        alert(err?.message || 'Không thể lưu. Vui lòng thử lại.');
      } finally {
        btn.disabled = false; btn.innerHTML = oldHtml;
      }

      return;
    }

    // Otherwise fallback to local demo insertion for new rows
    try {
      const tr = document.createElement('tr');
      tr.className = 'hover:bg-slate-50';
      tr.setAttribute('data-status', 'active');
      const newId = 'new-' + Date.now();
      tr.innerHTML = `
        <td class="px-4 py-3"><input type="checkbox" class="rowChk h-4 w-4"></td>
        <td class="px-4 py-3 font-mono">${code}</td>
        <td class="px-4 py-3 font-medium text-slate-800">${name}</td>
        <td class="px-4 py-3">${className || '-'}</td>
        <td class="px-4 py-3">${email || '-'}</td>
        <td class="px-4 py-3 text-center">${majorLabel || '-'}</td>
        <td class="px-4 py-3 text-right space-x-2">
          <button class="btnEdit px-2 py-1.5 rounded-lg border hover:bg-slate-50 text-indigo-600"
                  data-id="${newId}" data-code="${code}" data-name="${name}" data-classname="${className}" data-email="${email}" data-major="${major}" data-phone="${phone}" data-dob="${dob}">
            <i class="ph ph-pencil"></i>
          </button>
          <button class="btnDelete px-2 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600" data-id="${newId}">
            <i class="ph ph-trash"></i>
          </button>
        </td>`;
      document.getElementById('tableBody')?.prepend(tr);
      closeModal();
    } catch (err) {
      console.error(err);
      alert('Không thể lưu. Vui lòng thử lại.');
    }
  });
</script>
</body>
</html>
```