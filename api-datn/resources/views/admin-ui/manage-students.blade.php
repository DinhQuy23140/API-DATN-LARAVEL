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

  // Data mẫu khi chưa có $students từ controller
  $hasData = isset($students);
  $items = $hasData ? $students : collect([
    (object)['id'=>1,'student_code'=>'20123456','fullname'=>'Nguyễn Văn A','class_name'=>'KTPM2021','email'=>'a@example.com','status'=>'active'],
    (object)['id'=>2,'student_code'=>'20124567','fullname'=>'Trần Thị B','class_name'=>'HTTT2021','email'=>'b@example.com','status'=>'pending'],
    (object)['id'=>3,'student_code'=>'20125678','fullname'=>'Lê Văn C','class_name'=>'CNPM2021','email'=>'c@example.com','status'=>'paused'],
  ]);
@endphp

<div class="flex min-h-screen">
  <!-- Sidebar -->
  <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
    <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
      <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-buildings"></i></div>
      <div class="sidebar-label">
        <div class="font-semibold">Bảng quản trị</div>
        <div class="text-xs text-slate-500">Hệ thống</div>
      </div>
    </div>
    <nav class="flex-1 overflow-y-auto p-3">
      <a href="{{ route('web.admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100">
        <i class="ph ph-gauge"></i><span class="sidebar-label">Dashboard</span>
      </a>
      <div class="mt-3 text-xs uppercase text-slate-400 sidebar-label px-3">Quản lý</div>
      <a href="{{ route('web.admin.students.index') ?? '#' }}" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold">
        <i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span>
      </a>
      <a href="{{ route('web.admin.lecturers.index') ?? '#' }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100">
        <i class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span>
      </a>
    </nav>
    <div class="p-3 border-t border-slate-200">
      <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg">
        <i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span>
      </button>
    </div>
  </aside>

  <!-- Main -->
  <div class="flex-1 h-screen overflow-hidden flex flex-col md:pl-[260px]">
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
                <th class="text-center px-4 py-3">Trạng thái</th>
                <th class="text-right px-4 py-3">Hành động</th>
              </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-slate-100">
              @foreach ($items as $st)
                @php
                  $status = $st->status ?? 'active';
                  $pill = ['active'=>'bg-emerald-50 text-emerald-700','pending'=>'bg-amber-50 text-amber-700','paused'=>'bg-slate-100 text-slate-700'][$status] ?? 'bg-slate-100 text-slate-700';
                  $pillText = ['active'=>'Đang học','pending'=>'Chờ xác nhận','paused'=>'Tạm dừng'][$status] ?? 'Khác';
                @endphp
                <tr class="hover:bg-slate-50" data-status="{{ $status }}">
                  <td class="px-4 py-3"><input type="checkbox" class="rowChk h-4 w-4"></td>
                  <td class="px-4 py-3 font-mono">{{ $st->student_code ?? '-' }}</td>
                  <td class="px-4 py-3 font-medium text-slate-800">{{ $st->fullname ?? ($st->user->fullname ?? '-') }}</td>
                  <td class="px-4 py-3">{{ $st->class_name ?? ($st->classroom->name ?? '-') }}</td>
                  <td class="px-4 py-3">{{ $st->email ?? ($st->user->email ?? '-') }}</td>
                  <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $pill }}">{{ $pillText }}</span>
                  </td>
                  <td class="px-4 py-3 text-right space-x-2">
                    <button class="btnEdit px-2 py-1.5 rounded-lg border hover:bg-slate-50 text-indigo-600"
                            data-id="{{ $st->id }}"
                            data-code="{{ $st->student_code ?? '' }}"
                            data-name="{{ $st->fullname ?? ($st->user->fullname ?? '') }}"
                            data-class="{{ $st->class_name ?? ($st->classroom->name ?? '') }}"
                            data-email="{{ $st->email ?? ($st->user->email ?? '') }}"
                            data-status="{{ $status }}">
                      <i class="ph ph-pencil"></i>
                    </button>
                    <button class="btnDelete px-2 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600" data-id="{{ $st->id }}">
                      <i class="ph ph-trash"></i>
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </section>

        <!-- Pagination (tĩnh nếu chưa có paginate) -->
        @if($hasData && method_exists($students, 'links'))
          <div>{{ $students->links() }}</div>
        @endif
      </div>
    </main>
  </div>
</div>

<!-- Modal: Thêm/Sửa sinh viên -->
<div id="studentModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/40" data-close></div>
  <div class="relative z-10 bg-white w-full max-w-xl rounded-2xl shadow-lg flex flex-col max-h-[calc(100vh-4rem)]">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 id="modalTitle" class="font-semibold">Thêm sinh viên</h3>
      <button class="text-slate-500 hover:text-slate-700" data-close><i class="ph ph-x"></i></button>
    </div>
    <div class="p-5 space-y-3 overflow-y-auto">
      <input type="hidden" id="stId">
      <div>
        <label class="text-sm text-slate-600">MSSV</label>
        <input id="stCode" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
      </div>
      <div>
        <label class="text-sm text-slate-600">Họ tên</label>
        <input id="stName" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
          <label class="text-sm text-slate-600">Lớp</label>
          <input id="stClass" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
        </div>
        <div>
          <label class="text-sm text-slate-600">Email</label>
          <input id="stEmail" type="email" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
        </div>
      </div>
      <div>
        <label class="text-sm text-slate-600">Trạng thái</label>
        <select id="stStatus" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
          <option value="active">Đang học</option>
          <option value="pending">Chờ xác nhận</option>
          <option value="paused">Tạm dừng</option>
        </select>
      </div>
    </div>
    <div class="px-5 py-4 border-t flex items-center justify-end gap-2">
      <button class="px-3 py-1.5 rounded-lg border text-sm hover:bg-slate-50" data-close>Đóng</button>
      <button id="btnSave" class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm">
        <i class="ph ph-check"></i> Lưu
      </button>
    </div>
  </div>
</div>

<script>
  // Sidebar collapse giống dashboard
  const html = document.documentElement, sidebar = document.getElementById('sidebar');
  function setCollapsed(c){
    const mainArea = document.querySelector('.flex-1');
    if(c){ html.classList.add('sidebar-collapsed'); mainArea?.classList.add('md:pl-[72px]'); mainArea?.classList.remove('md:pl-[260px]'); }
    else { html.classList.remove('sidebar-collapsed'); mainArea?.classList.remove('md:pl-[72px]'); mainArea?.classList.add('md:pl-[260px]'); }
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
    document.getElementById('stStatus').value = 'active';
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
    document.getElementById('stClass').value = btn.dataset.class || '';
    document.getElementById('stEmail').value = btn.dataset.email || '';
    document.getElementById('stStatus').value = btn.dataset.status || 'active';
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
    try {
      // TODO: gọi API xóa khi có route:
      // const url = `{{ route('web.admin.students.destroy', 0) }}`.replace('/0','/'+id);
      // const res = await fetch(url, { method:'DELETE', headers:{'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content, 'Accept':'application/json'} });
      // const data = await res.json().catch(()=>({}));
      // if(!res.ok || data.ok===false) throw new Error(data.message || 'Xóa thất bại');
      btn.closest('tr')?.remove();
    } catch (err) {
      console.error(err);
      alert('Không thể xóa. Vui lòng thử lại.');
    } finally {
      btn.disabled = false; btn.innerHTML = old;
    }
  });

  // Save (create/update) — demo client-side; gắn API khi có route
  document.getElementById('btnSave')?.addEventListener('click', async ()=>{
    const id = document.getElementById('stId').value.trim();
    const code = document.getElementById('stCode').value.trim();
    const name = document.getElementById('stName').value.trim();
    const className = document.getElementById('stClass').value.trim();
    const email = document.getElementById('stEmail').value.trim();
    const status = document.getElementById('stStatus').value;

    if(!code || !name){ alert('Vui lòng nhập MSSV và Họ tên'); return; }

    try {
      // TODO: gọi API khi có route:
      // const method = id ? 'PATCH' : 'POST';
      // const url = id
      //   ? `{{ route('web.admin.students.update', 0) }}`.replace('/0','/'+id)
      //   : `{{ route('web.admin.students.store') }}`;
      // const res = await fetch(url, {
      //   method, headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content},
      //   body: JSON.stringify({ student_code:code, fullname:name, class_name:className, email, status })
      // });
      // const data = await res.json().catch(()=>({}));
      // if(!res.ok || data.ok===false) throw new Error(data.message || 'Lưu thất bại');

      // Demo: cập nhật UI cục bộ
      if(id){
        const tr = [...document.querySelectorAll('#tableBody tr')].find(x => x.querySelector('.btnEdit')?.dataset.id === id);
        if(tr){
          tr.setAttribute('data-status', status);
          tr.children[1].textContent = code;
          tr.children[2].textContent = name;
          tr.children[3].textContent = className || '-';
          tr.children[4].textContent = email || '-';
          const pillMap = {active:['Đang học','bg-emerald-50 text-emerald-700'], pending:['Chờ xác nhận','bg-amber-50 text-amber-700'], paused:['Tạm dừng','bg-slate-100 text-slate-700']};
          const [label, cls] = pillMap[status] || ['Khác','bg-slate-100 text-slate-700'];
          tr.children[5].querySelector('span').className = 'px-2 py-0.5 rounded-full text-xs ' + cls;
          tr.children[5].querySelector('span').textContent = label;
          const editBtn = tr.querySelector('.btnEdit');
          editBtn.dataset.code = code; editBtn.dataset.name = name; editBtn.dataset.class = className; editBtn.dataset.email = email; editBtn.dataset.status = status;
        }
      } else {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-slate-50';
        tr.setAttribute('data-status', status);
        const pillMap = {active:['Đang học','bg-emerald-50 text-emerald-700'], pending:['Chờ xác nhận','bg-amber-50 text-amber-700'], paused:['Tạm dừng','bg-slate-100 text-slate-700']};
        const [label, cls] = pillMap[status] || ['Khác','bg-slate-100 text-slate-700'];
        const newId = 'new-' + Date.now();
        tr.innerHTML = `
          <td class="px-4 py-3"><input type="checkbox" class="rowChk h-4 w-4"></td>
          <td class="px-4 py-3 font-mono">${code}</td>
          <td class="px-4 py-3 font-medium text-slate-800">${name}</td>
          <td class="px-4 py-3">${className || '-'}</td>
          <td class="px-4 py-3">${email || '-'}</td>
          <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded-full text-xs ${cls}">${label}</span></td>
          <td class="px-4 py-3 text-right space-x-2">
            <button class="btnEdit px-2 py-1.5 rounded-lg border hover:bg-slate-50 text-indigo-600"
                    data-id="${newId}" data-code="${code}" data-name="${name}" data-class="${className}" data-email="${email}" data-status="${status}">
              <i class="ph ph-pencil"></i>
            </button>
            <button class="btnDelete px-2 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600" data-id="${newId}">
              <i class="ph ph-trash"></i>
            </button>
          </td>`;
        document.getElementById('tableBody')?.prepend(tr);
      }
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