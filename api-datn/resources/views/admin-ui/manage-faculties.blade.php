<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý Khoa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <style>
      :root { --primary:#2563eb; }
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
    $teacherId = $user->teacher->id ?? 0;
    $avatarUrl = $user->avatar_url
      ?? $user->profile_photo_url
      ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
  @endphp
  <body class="bg-slate-50 text-slate-800">
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
          <a href="{{ route('web.admin.manage_faculties') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 bg-slate-100 font-bold">
            <i class="ph ph-graduation-cap"></i> <span class="sidebar-label">Quản lý Khoa</span>
          </a>
          <a href="{{ route('web.admin.manage_assistants') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">
            <i class="ph ph-users-three"></i> <span class="sidebar-label">Trợ lý khoa</span>
          </a>
        </nav>
        <div class="p-3 border-t border-slate-200">
          <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
        </div>
      </aside>

      <!-- Main area -->
      <div class="flex-1 h-screen overflow-hidden flex flex-col">
        <!-- Topbar -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
          <div class="flex items-center gap-3 flex-1">
            <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
            <div>
              <h1 class="text-lg md:text-xl font-semibold">Quản lý Khoa</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Quản trị viên / Quản lý Khoa</nav>
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

        <!-- Content -->
        <main class="pt-20 px-4 md:px-6 pb-10">
        <div class="max-w-6xl mx-auto space-y-5">
          <!-- Search + Add -->
          <div class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="relative w-full sm:max-w-sm">
              <input id="searchInput" class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo mã hoặc tên khoa" />
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <button id="btnAdd" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><i class="ph ph-plus"></i>Thêm khoa</button>
          </div>
          <!-- Table -->
          <div class="bg-white border border-slate-200 rounded-xl">
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500">
                    <th class="py-3 px-4 border-b w-10"><input id="chkAll" type="checkbox" class="h-4 w-4" /></th>
                    <th class="py-3 px-4 border-b">Mã</th>
                    <th class="py-3 px-4 border-b">Tên</th>
                    <th class="py-3 px-4 border-b">Trưởng khoa</th>
                    <th class="py-3 px-4 border-b">Phó khoa</th>
                    <th class="py-3 px-4 border-b">Trợ lý khoa</th>
                    <th class="py-3 px-4 border-b text-right">Hành động</th>
                  </tr>
                </thead>
                <tbody id="tableBody">
                  @if ($faculties->isEmpty())
                    <tr>
                      <td colspan="7" class="text-center text-slate-500 py-6">Chưa có khoa nào. Nhấn "Thêm khoa" để tạo mới.</td>
                    </tr>
                  @else
                    @foreach($faculties as $f)
                    @php
                        $deanRole  = $f->facultyRoles->firstWhere('role', 'dean');
                        $viceRole  = $f->facultyRoles->firstWhere('role', 'vice_dean');
                        $assistRole= $f->facultyRoles->firstWhere('role', 'assistant');

                        $deanName   = optional($deanRole?->user)->fullname;
                        $viceName   = optional($viceRole?->user)->fullname;
                        $assistName = optional($assistRole?->user)->fullname;
                    @endphp
                      <tr class="hover:bg-slate-50" data-row-id="{{ $f->id }}">
                        <td class="py-3 px-4"><input type="checkbox" class="rowChk h-4 w-4" /></td>
                        <td class="py-3 px-4">{{ $f->code }}</td>
                        <td class="py-3 px-4">{{ $f->name }}</td>
                        <td class="py-3 px-4">
                          @if($deanName)
                            <span class="text-slate-800">{{ $deanName }}</span>
                          @else
                            <span class="text-slate-400 italic">—</span>
                          @endif
                        </td>
                        <td class="py-3 px-4">
                          @if($viceName) <span class="text-slate-800">{{ $viceName }}</span>
                          @else <span class="text-slate-400 italic">—</span>@endif
                        </td>
                        <td class="py-3 px-4">
                          @if($assistName) <span class="text-slate-800">{{ $assistName }}</span>
                          @else <span class="text-slate-400 italic">—</span>@endif
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                          <button type="button"
                            class="btnEdit px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600"
                            data-id="{{ $f->id }}"
                            data-code="{{ $f->code }}"
                            data-name="{{ $f->name }}"
                            data-short-name="{{ $f->short_name }}"
                            data-description="{{ $f->description }}"
                            data-assistant-id="{{ $assistRole?->user?->id }}"
                            data-dean-id="{{ $deanRole?->user?->id }}"
                            data-vice-dean-id="{{ $viceRole?->user?->id }}"
                            data-phone="{{ $f->phone }}"
                            data-email="{{ $f->email }}"
                            data-address="{{ $f->address }}">
                            <i class="ph ph-pencil"></i>
                          </button>
                          <button type="button"
                            class="btnDelete px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"
                            data-id="{{ $f->id }}">
                            <i class="ph ph-trash"></i>
                          </button>
                        </td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
            <div class="p-4 flex items-center justify-between text-sm text-slate-600">
              <div>Hiển thị 1-3 của 12</div>
              <div class="inline-flex rounded-lg border border-slate-200 overflow-hidden">
                <button class="px-3 py-1.5 hover:bg-slate-50"><i class="ph ph-caret-left"></i></button>
                <button class="px-3 py-1.5 bg-slate-100 font-medium">1</button>
                <button class="px-3 py-1.5 hover:bg-slate-50">2</button>
                <button class="px-3 py-1.5 hover:bg-slate-50">3</button>
                <button class="px-3 py-1.5 hover:bg-slate-50"><i class="ph ph-caret-right"></i></button>
              </div>
            </div>
          </div>
          </div>
        </main>
      </div>
    </div>

    <!-- Modal: Tạo khoa -->
<div id="modalCreateFaculty" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 backdrop-blur-sm">
  <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl">
    <div class="p-5 border-b flex items-center justify-between bg-slate-50 rounded-t-2xl">
      <h3 class="text-lg font-semibold">Thêm khoa</h3>
      <button class="p-2 hover:bg-slate-200 rounded-full" data-close-create><i class="ph ph-x"></i></button>
    </div>
    <form id="facultyCreateForm" class="p-6 space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div>
          <label class="text-sm font-medium">Mã khoa *</label>
          <input name="code" required class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
        </div>
        <div class="md:col-span-2">
          <label class="text-sm font-medium">Tên khoa *</label>
          <input name="name" required class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
        </div>
        <div>
          <label class="text-sm font-medium">Tên ngắn *</label>
          <input name="short_name" required class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
        </div>
        <div>
          <label class="text-sm font-medium">Trưởng khoa *</label>
          <select name="dean_id" required class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
            <option value="">— Chọn —</option>
            @foreach($teachers as $t)
              <option value="{{ $t->user->id }}">{{ $t->degree }} {{ $t->user->fullname }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="text-sm font-medium">Phó khoa *</label>
          <select name="vice_dean_id" required class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
            <option value="">— Chọn —</option>
            @foreach($teachers as $t)
              <option value="{{ $t->user->id }}">{{ $t->degree }} {{ $t->user->fullname }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="text-sm font-medium">Trợ lý khoa *</label>
          <select name="assistant_id" required class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
            <option value="">— Chọn —</option>
            @foreach($teachers as $t)
              <option value="{{ $t->user->id }}">{{ $t->degree }} {{ $t->user->fullname }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div>
          <label class="text-sm font-medium">Điện thoại</label>
          <input name="phone" class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
        </div>
        <div>
          <label class="text-sm font-medium">Email</label>
          <input name="email" type="email" class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
        </div>
        <div>
          <label class="text-sm font-medium">Địa chỉ</label>
          <input name="address" class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
        </div>
      </div>

      <div>
        <label class="text-sm font-medium">Mô tả</label>
        <textarea name="description" rows="3" class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 text-sm"></textarea>
      </div>

      <div class="flex items-center justify-end gap-3 pt-2">
        <button type="button" class="px-4 py-2 rounded-lg border hover:bg-slate-50" data-close-create>Hủy</button>
        <button id="btnCreateFaculty" class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Lưu</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Sửa khoa -->
<div id="modalEditFaculty" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 backdrop-blur-sm">
  <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl">
    <div class="p-5 border-b flex items-center justify-between bg-slate-50 rounded-t-2xl">
      <h3 class="text-lg font-semibold">Sửa khoa</h3>
      <button class="p-2 hover:bg-slate-200 rounded-full" data-close-edit><i class="ph ph-x"></i></button>
    </div>
    <form id="facultyEditForm" class="p-6 space-y-6">
      <input type="hidden" name="id" id="edit_faculty_id">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div>
          <label class="text-sm font-medium">Mã khoa *</label>
          <input name="code" required class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-sm">
        </div>
        <div class="md:col-span-2">
          <label class="text-sm font-medium">Tên khoa *</label>
          <input name="name" required class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-sm">
        </div>
        <div>
          <label class="text-sm font-medium">Tên ngắn *</label>
          <input name="short_name" required class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-sm">
        </div>
        <div>
          <label class="text-sm font-medium">Trưởng khoa *</label>
          <select name="dean_id" required class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-sm">
            <option value="">— Chọn —</option>
            @foreach($teachers as $t)
              <!-- @if ($t->user->role === 'teacher')
                <option value="{{ $t->user->id }}">{{ $t->degree }} {{ $t->user->fullname }}</option>
              @endif -->
              <option value="{{ $t->user->id }}">{{ $t->degree }} {{ $t->user->fullname }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="text-sm font-medium">Phó khoa *</label>
          <select name="vice_dean_id" required class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-sm">
            <option value="">— Chọn —</option>
            @foreach($teachers as $t)
              <option value="{{ $t->user->id }}">{{ $t->degree }} {{ $t->user->fullname }}</option>
              <!-- @if ($t->user->role === 'teacher')
                <option value="{{ $t->user->id }}">{{ $t->degree }} {{ $t->user->fullname }}</option>
              @endif -->
            @endforeach
          </select>
        </div>
        <div>
          <label class="text-sm font-medium">Trợ lý khoa *</label>
          <select name="assistant_id" required class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-sm">
            <option value="">— Chọn —</option>
            @foreach($teachers as $t)
              <!-- @if ($t->user->role === 'teacher')
                <option value="{{ $t->user->id }}">{{ $t->degree }} {{ $t->user->fullname }}</option>
              @endif -->
              <option value="{{ $t->user->id }}">{{ $t->degree }} {{ $t->user->fullname }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div>
          <label class="text-sm font-medium">Điện thoại</label>
          <input name="phone" class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-sm">
        </div>
        <div>
          <label class="text-sm font-medium">Email</label>
          <input name="email" type="email" class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-sm">
        </div>
        <div>
          <label class="text-sm font-medium">Địa chỉ</label>
          <input name="address" class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-sm">
        </div>
      </div>

      <div>
        <label class="text-sm font-medium">Mô tả</label>
        <textarea name="description" rows="3" class="mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-sm"></textarea>
      </div>

      <div class="flex items-center justify-end gap-3 pt-2">
        <button type="button" class="px-4 py-2 rounded-lg border hover:bg-slate-50" data-close-edit>Hủy</button>
        <button id="btnUpdateFaculty" class="px-5 py-2 rounded-lg bg-amber-500 text-white hover:bg-amber-600">Cập nhật</button>
      </div>
    </form>
  </div>
</div>

    <script>
      const html = document.documentElement;
      const sidebar = document.getElementById('sidebar');
      const toggleSidebar = document.getElementById('toggleSidebar');
      const openSidebar = document.getElementById('openSidebar');
      const modalCreate = document.getElementById('modalCreateFaculty');
      const modalEdit = document.getElementById('modalEditFaculty');
      const createForm = document.getElementById('facultyCreateForm');
      const editForm   = document.getElementById('facultyEditForm');
      const editIdInput = document.getElementById('edit_faculty_id');

      function safeReset(formEl){
        if(formEl && typeof formEl.reset === 'function') formEl.reset();
      }

      function setCollapsed(collapsed){
        if (collapsed) html.classList.add('sidebar-collapsed');
        else html.classList.remove('sidebar-collapsed');
      }

      toggleSidebar?.addEventListener('click', ()=>{
        const collapsed = !html.classList.contains('sidebar-collapsed');
        setCollapsed(collapsed);
        localStorage.setItem('admin_sidebar_collapsed', collapsed ? '1':'0');
      });
      openSidebar?.addEventListener('click', ()=> sidebar.classList.toggle('-translate-x-full'));
      if (localStorage.getItem('admin_sidebar_collapsed') === '1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      // Modal helpers
      function openCreate(){
        modalCreate.classList.remove('hidden');
        modalCreate.classList.add('flex');
      }
      function closeCreate(){
        modalCreate.classList.add('hidden');
        modalCreate.classList.remove('flex');
        safeReset(createForm);
      }
      function openEdit(){
        modalEdit.classList.remove('hidden');
        modalEdit.classList.add('flex');
      }
      function closeEdit(){
        modalEdit.classList.add('hidden');
        modalEdit.classList.remove('flex');
        safeReset(editForm);
        if (editIdInput) editIdInput.value = '';
      }

      // Close buttons
      document.querySelectorAll('[data-close-create]').forEach(b=> b.addEventListener('click', closeCreate));
      document.querySelectorAll('[data-close-edit]').forEach(b=> b.addEventListener('click', closeEdit));

      // Open create
      document.getElementById('btnAdd')?.addEventListener('click', ()=>{
        closeEdit();
        openCreate();
      });

      // Fill edit form
      function fillEdit(ds){
        const form = document.getElementById('facultyEditForm');
        form.querySelector('[name="code"]').value         = ds.code || '';
        form.querySelector('[name="name"]').value         = ds.name || '';
        form.querySelector('[name="short_name"]').value   = ds.shortName || '';
        form.querySelector('[name="assistant_id"]').value = ds.assistantId || '';
        form.querySelector('[name="dean_id"]').value      = ds.deanId || '';
        form.querySelector('[name="vice_dean_id"]').value = ds.viceDeanId || '';
        form.querySelector('[name="phone"]').value        = ds.phone || '';
        form.querySelector('[name="email"]').value        = ds.email || '';
        form.querySelector('[name="address"]').value      = ds.address || '';
        form.querySelector('[name="description"]').value  = ds.description || '';
        document.getElementById('edit_faculty_id').value  = ds.id;
      }

      // Delegation edit/delete
      document.getElementById('tableBody')?.addEventListener('click', (e)=>{
        const btn = e.target.closest('.btnEdit,.btnDelete');
        if(!btn) return;
        if(btn.classList.contains('btnEdit')){
          fillEdit(btn.dataset);
          filterSelectOptions('#facultyEditForm'); // đảm bảo options không bị disable sai
          closeCreate();
          openEdit();
        } else {
          if(!confirm('Xóa khoa này?')) return;
          const id = btn.dataset.id;
          const token = document.querySelector('meta[name="csrf-token"]').content;
          fetch(`{{ route('web.admin.faculties.destroy', 0) }}`.replace('/0','/'+id), {
            method:'DELETE',
            headers:{'Accept':'application/json','X-CSRF-TOKEN':token}
          }).then(r=>r.json().catch(()=>({}))).then(j=>{
            if(j.ok){ btn.closest('tr')?.remove(); }
            else alert(j.message || 'Xóa thất bại');
          }).catch(()=> alert('Lỗi mạng'));
        }
      });

      function getOptionText(sel){
        const el = document.querySelector(sel);
        if(!el) return '';
        const o = el.options[el.selectedIndex];
        return o ? o.textContent.trim() : '';
      }

      // Create submit
      document.getElementById('facultyCreateForm')?.addEventListener('submit', async (e)=>{
        e.preventDefault();
        const btn = document.getElementById('btnCreateFaculty');
        const old = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> Lưu...';
        const fd = new FormData(e.currentTarget);
        const token = document.querySelector('meta[name="csrf-token"]').content;

        const deanText      = getOptionText('#facultyCreateForm select[name="dean_id"]');
        const viceDeanText  = getOptionText('#facultyCreateForm select[name="vice_dean_id"]');
        const assistantText = getOptionText('#facultyCreateForm select[name="assistant_id"]');

        try{
          const res  = await fetch(`{{ route('web.admin.faculties.store') }}`, {
            method:'POST',
            headers:{'Accept':'application/json','X-CSRF-TOKEN':token},
            body: fd
          });
          const txt = await res.text();
          let data; try{ data=JSON.parse(txt);}catch{ console.error(txt); throw new Error('RESP_NOT_JSON'); }
          if(!res.ok || data.ok === false){
            alert(data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Lưu thất bại'));
            btn.disabled=false; btn.innerHTML=old; return;
          }
          const f = data.data;

          // Lấy lại các value đã chọn từ form CREATE
          const deanVal      = document.querySelector('#facultyCreateForm select[name="dean_id"]')?.value || '';
          const viceDeanVal  = document.querySelector('#facultyCreateForm select[name="vice_dean_id"]')?.value || '';
          const assistantVal = document.querySelector('#facultyCreateForm select[name="assistant_id"]')?.value || '';

          const tb = document.getElementById('tableBody');
          const tr = document.createElement('tr');
          tr.className='hover:bg-slate-50';
          tr.dataset.rowId = f.id;
          tr.innerHTML = `
            <td class="py-3 px-4"><input type="checkbox" class="rowChk h-4 w-4" /></td>
            <td class="py-3 px-4">${f.code}</td>
            <td class="py-3 px-4">${f.name}</td>
            <td class="py-3 px-4">${deanText || '<span class="text-slate-400 italic">—</span>'}</td>
            <td class="py-3 px-4">${viceDeanText || '<span class="text-slate-400 italic">—</span>'}</td>
            <td class="py-3 px-4">${assistantText || '<span class="text-slate-400 italic">—</span>'}</td>
            <td class="py-3 px-4 text-right space-x-2">
              <button type="button"
                class="btnEdit px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600"
                data-id="${f.id}"
                data-code="${f.code}"
                data-name="${f.name}"
                data-short-name="${f.short_name}"
                data-description="${f.description||''}"
                data-assistant-id="${assistantVal}"
                data-dean-id="${deanVal}"
                data-vice-dean-id="${viceDeanVal}"
                data-phone="${f.phone||''}"
                data-email="${f.email||''}"
                data-address="${f.address||''}">
                <i class="ph ph-pencil"></i>
              </button>
              <button type="button"
                class="btnDelete px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"
                data-id="${f.id}">
                <i class="ph ph-trash"></i>
              </button>
            </td>`;
          tb.prepend(tr);
          // e.currentTarget.reset();
          safeReset(createForm);
          closeCreate();
        }catch(err){
          alert('Lỗi: '+ (err.message||'Không xác định'));
        }finally{
          btn.disabled=false; btn.innerHTML=old;
        }
      });

      // Edit submit
      document.getElementById('facultyEditForm')?.addEventListener('submit', async (e)=>{
        e.preventDefault();
        const id  = document.getElementById('edit_faculty_id').value;
        if(!id){ alert('Thiếu ID khoa.'); return; }
        const btn = document.getElementById('btnUpdateFaculty');
        const old = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> Cập nhật...';

        const deanText      = getOptionText('#facultyEditForm select[name="dean_id"]');
        const viceDeanText  = getOptionText('#facultyEditForm select[name="vice_dean_id"]');
        const assistantText = getOptionText('#facultyEditForm select[name="assistant_id"]');

        const fd = new FormData(e.currentTarget);
        fd.append('_method','PATCH');
        const token = document.querySelector('meta[name="csrf-token"]').content;
        try{
          const res  = await fetch(`{{ route('web.admin.faculties.update', 0) }}`.replace('/0','/'+id), {
            method:'POST',
            headers:{'Accept':'application/json','X-CSRF-TOKEN':token},
            body: fd
          });
          const txt = await res.text();
          let data; try{ data=JSON.parse(txt);}catch{ console.error(txt); throw new Error('RESP_NOT_JSON'); }
          if(!res.ok || data.ok === false){
            alert(data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Cập nhật thất bại'));
            btn.disabled=false; btn.innerHTML=old; return;
          }
          const f = data.data;

          // Lấy lại các value đã chọn từ form EDIT
          const deanVal      = document.querySelector('#facultyEditForm select[name="dean_id"]')?.value || '';
          const viceDeanVal  = document.querySelector('#facultyEditForm select[name="vice_dean_id"]')?.value || '';
          const assistantVal = document.querySelector('#facultyEditForm select[name="assistant_id"]')?.value || '';

          const tr = document.querySelector(`tr[data-row-id="${f.id}"]`);
          if(tr){
            tr.querySelectorAll('td')[1].textContent = f.code;
            tr.querySelectorAll('td')[2].textContent = f.name;
            tr.querySelectorAll('td')[3].textContent = deanText || '—';
            tr.querySelectorAll('td')[4].textContent = viceDeanText || '—';
            tr.querySelectorAll('td')[5].textContent = assistantText || '—';
            const editBtn = tr.querySelector('.btnEdit');
            if(editBtn){
              editBtn.dataset.code = f.code;
              editBtn.dataset.name = f.name;
              editBtn.dataset.shortName = f.short_name || '';
              editBtn.dataset.description = f.description || '';
              editBtn.dataset.assistantId = assistantVal;
              editBtn.dataset.deanId = deanVal;
              editBtn.dataset.viceDeanId = viceDeanVal;
              editBtn.dataset.phone = f.phone || '';
              editBtn.dataset.email = f.email || '';
              editBtn.dataset.address = f.address || '';
            }
          }
          closeEdit();
        }catch(err){
          alert('Lỗi: '+(err.message||'Không xác định'));
        }finally{
          btn.disabled=false; btn.innerHTML=old;
        }
      });

      // Đóng khi click nền
      modalCreate?.addEventListener('click', e=>{
        if(e.target===modalCreate) closeCreate();
      });
      modalEdit?.addEventListener('click', e=>{
        if(e.target===modalEdit) closeEdit();
      });

      //validate select
      function filterSelectOptions(formSelector) {
        const selects = document.querySelectorAll(`${formSelector} select[name$="_id"]`);

        const selectedValues = Array.from(selects)
          .map(s => s.value)
          .filter(v => v !== "");

        selects.forEach(select => {
          const currentValue = select.value;
          Array.from(select.options).forEach(option => {
            if (option.value === "") return; // bỏ qua option rỗng

            // disable nếu đã chọn ở select khác
            if (selectedValues.includes(option.value) && option.value !== currentValue) {
              option.disabled = true;
              option.classList.add("text-slate-400");
            } else {
              option.disabled = false;
              option.classList.remove("text-slate-400");
            }
          });
        });
      }

      // gắn sự kiện cho modal CREATE
      document.querySelectorAll('#facultyCreateForm select[name$="_id"]').forEach(sel => {
        sel.addEventListener("change", () => filterSelectOptions("#facultyCreateForm"));
      });

      // gắn sự kiện cho modal EDIT
      document.querySelectorAll('#facultyEditForm select[name$="_id"]').forEach(sel => {
        sel.addEventListener("change", () => filterSelectOptions("#facultyEditForm"));
      });

      // gọi mỗi lần modal mở
      document.getElementById("modalCreateFaculty").addEventListener("click", e => {
        if (e.target.closest("[data-close-create]")) return;
        filterSelectOptions("#facultyCreateForm");
      });

      document.getElementById("modalEditFaculty").addEventListener("click", e => {
        if (e.target.closest("[data-close-edit]")) return;
        filterSelectOptions("#facultyEditForm");
      });

      // Profile dropdown: toggle + click outside + ESC
      const profileBtn = document.getElementById('profileBtn');
      const profileMenu = document.getElementById('profileMenu');

      function closeProfileMenu() {
        profileMenu?.classList.add('hidden');
        profileBtn?.setAttribute('aria-expanded', 'false');
      }
      function openProfileMenu() {
        profileMenu?.classList.remove('hidden');
        profileBtn?.setAttribute('aria-expanded', 'true');
      }
      profileBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        const isHidden = profileMenu?.classList.contains('hidden');
        if (isHidden) openProfileMenu(); else closeProfileMenu();
      });
      document.addEventListener('click', (e) => {
        if (!profileMenu || !profileBtn) return;
        if (!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
          closeProfileMenu();
        }
      });
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeProfileMenu();
      });
    </script>
  </body>
</html>
