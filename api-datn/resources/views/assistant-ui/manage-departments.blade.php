<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quản lý Bộ môn</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <style>
    body {
      font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
    }

    .sidebar-collapsed .sidebar-label {
      display: none;
    }

    .sidebar-collapsed .sidebar {
      width: 72px;
    }

    .sidebar {
      width: 260px;
    }

    .submenu {
      display: none;
    }

    .submenu.hidden {
      display: none;
    }

    .submenu:not(.hidden) {
      display: block;
    }
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
  $data_assignment_supervisors = $user->teacher->supervisor->assignment_supervisors ?? collect();;
  $avatarUrl = $user->avatar_url
    ?? $user->profile_photo_url
    ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
@endphp
  <div class="flex min-h-screen">
    <aside id="sidebar"
      class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-buildings"></i>
        </div>
        <div class="sidebar-label">
          <div class="font-semibold">Assistant</div>
          <div class="text-xs text-slate-500">Quản trị khoa</div>
        </div>
      </div>
      <nav class="flex-1 overflow-y-auto p-3">
        <a href="{{ route('web.assistant.dashboard') }}"
          class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span
            class="sidebar-label">Bảng điều khiển</span></a>
        <a href="{{ route('web.assistant.manage_departments') }}"
          class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold"><i
            class="ph ph-buildings"></i><span class="sidebar-label">Bộ môn</span></a>
        <a href="{{ route('web.assistant.manage_majors') }}"
          class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i
            class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
        <a href="{{ route('web.assistant.manage_staffs') }}"
          class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i
            class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
        <a href="{{ route('web.assistant.assign_head') }}"
          class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span
            class="sidebar-label">Gán trưởng bộ môn</span></a>
        <div class="sidebar-label text-xs uppercase text-slate-400 px-3 mt-3">Học phần tốt nghiệp</div>
        <div class="graduation-item">
          <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button">
            <span class="flex items-center gap-3">
              <i class="ph ph-folder"></i>
              <span class="sidebar-label">Học phần tốt nghiệp</span>
            </span>
            <i class="ph ph-caret-down"></i>
          </div>
          <div class="submenu hidden pl-6">
            <a href="internship.html" class="block px-3 py-2 hover:bg-slate-100">Thực tập tốt nghiệp</a>
            <a href="{{ route('web.assistant.rounds') }}" class="block px-3 py-2 hover:bg-slate-100">Đồ án tốt
              nghiệp</a>
          </div>
        </div>
      </nav>
      <div class="p-3 border-t border-slate-200">
        <button id="toggleSidebar"
          class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i
            class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
      </div>
    </aside>

    <div class="flex-1">
      <header
        class="fixed left-0 md:left-[260px] right-0 top-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-20">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i
              class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Quản lý Bộ môn</h1>
            <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Quản lý Bộ môn</nav>
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
          <div id="profileMenu"
            class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông
              tin</a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
              class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i
                class="ph ph-sign-out"></i>Đăng xuất</a>
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </header>

      <main class="pt-20 px-4 md:px-6 pb-10 space-y-5">
        <div class="max-w-6xl mx-auto space-y-5">
          <div
            class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="relative w-full sm:max-w-sm">
              <input id="searchInput"
                class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm"
                placeholder="Tìm theo mã hoặc tên bộ môn" />
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <button onclick="openModal('add')"
              class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><i
                class="ph ph-plus"></i>Thêm bộ môn</button>
          </div>

          <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
            <div class="overflow-x-auto rounded-t-xl">
              <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                  <tr>
                    <th class="py-3 px-4 w-10">
                      <input id="chkAll" type="checkbox" class="h-4 w-4 rounded border-slate-300">
                    </th>
                    <th class="py-3 px-4">Mã</th>
                    <th class="py-3 px-4">Tên bộ môn</th>
                    <th class="py-3 px-4">Trưởng bộ môn</th>
                    <th class="py-3 px-4">Môn học</th>
                    <th class="py-3 px-4">Số GV</th>
                    <th class="py-3 px-4 text-right">Hành động</th>
                  </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-slate-100">
                  @if ($departments->isEmpty())
                    <tr>
                      <td colspan="7" class="text-center text-slate-500 py-8">Chưa có bộ môn nào.</td>
                    </tr>
                  @else
                    @foreach ($departments as $department)
                      <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-4">
                          <input type="checkbox" class="rowChk h-4 w-4 rounded border-slate-300" />
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-700">{{ $department->code }}</td>
                        <td class="py-3 px-4 font-medium text-slate-800 flex items-center gap-2">
                          <i class="ph ph-buildings text-slate-400"></i>
                          {{ $department->name }}
                        </td>
                        <td class="py-3 px-4">
                          @php
                            $head = $department->departmentRoles ?? [];
                          @endphp
                          @if ($head->where('role', 'head')->first()?->teacher->user->fullname)
                            <a href="{{ route('web.teacher.profile') }}" class="flex items-center gap-2 text-blue-600 hover:underline">
                              <i class="ph ph-user text-slate-400"></i>
                              {{ $head->where('role', 'head')->first()?->teacher->user->fullname ?? 'Chưa có trưởng bộ môn' }}
                            </a>
                          @else
                            <span class="text-slate-400">Chưa có trưởng bộ môn</span>
                          @endif
                        </td>
                        <td class="py-3 px-4">
                          @if ($department->subjects->isNotEmpty())
                            <div class="flex flex-wrap gap-1">
                              @foreach ($department->subjects as $subject)
                                <a href="manage-subjects.html"
                                  class="px-2 py-0.5 text-xs rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100">
                                  {{ $subject->name }}
                                </a>
                              @endforeach
                            </div>
                          @else
                            <span class="text-slate-400">Chưa có môn học</span>
                          @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                          <span class="px-2 py-1 rounded-md bg-slate-100 text-slate-700 text-xs">
                            {{ $department->teachers->count() }}
                          </span>
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                          <button
                            class="p-2 rounded-full hover:bg-slate-100 text-slate-600"
                            onclick="openModal('edit', this.dataset)"
                            data-id="{{ $department->id }}"
                            data-code="{{ $department->code }}"
                            data-name="{{ $department->name }}"
                            data-head-id="{{ optional($department->departmentRoles->where('role','head')->first())->teacher_id }}"
                            data-description="{{ e($department->description) }}"
                          >
                            <i class="ph ph-pencil"></i>
                          </button>
                          <button
                            class="btnDelete p-2 rounded-full hover:bg-rose-50 text-rose-600"
                            data-id="{{ $department->id }}">
                            <i class="ph ph-trash"></i>
                          </button>
                        </td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 flex items-center justify-between text-sm text-slate-600 border-t">
              <div>Hiển thị 1–2 của 18</div>
              <div class="inline-flex rounded-lg border border-slate-200 overflow-hidden">
                <button class="px-3 py-1.5 hover:bg-slate-50"><i class="ph ph-caret-left"></i></button>
                <button class="px-3 py-1.5 bg-blue-600 text-white font-medium">1</button>
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

<!-- Modal Add Department -->
<div id="modalAddDepartment"
  class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-2xl shadow-xl">
    
    <!-- Header -->
    <div class="p-4 border-b flex items-center justify-between">
      <h3 class="font-semibold text-lg">Thêm Bộ môn</h3>
      <button type="button" class="p-2 hover:bg-slate-100 rounded-lg"
              onclick="closeModal('add')">
        <i class="ph ph-x"></i>
      </button>
    </div>

    <!-- Form -->
    <form class="p-6 space-y-5" onsubmit="event.preventDefault(); submitAddDepartment(); closeModal('add');">
      
      <!-- Code + Name -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-medium flex items-center gap-2">
            <i class="ph ph-hash"></i> Mã bộ môn
          </label>
          <input id="addCode" required
                 class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 
                        focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600"
                 placeholder="VD: D-CNTT" />
        </div>
        <div>
          <label class="text-sm font-medium flex items-center gap-2">
            <i class="ph ph-bookmarks"></i> Tên bộ môn
          </label>
          <input id="addName" required
                 class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 
                        focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600"
                 placeholder="VD: Công nghệ thông tin" />
        </div>
      </div>

      <!-- Head + Majors -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-medium flex items-center gap-2">
            <i class="ph ph-user-circle"></i> Trưởng bộ môn
          </label>
          <select id="addHead"
                  class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 
                         focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
            <option>— Chọn trưởng bộ môn —</option>
            @foreach ($teachers as $teacher)
              <option value="{{ $teacher->id }}">{{ $teacher->user->fullname }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <!-- Description -->
      <div>
        <label class="text-sm font-medium flex items-center gap-2">
          <i class="ph ph-text-align-left"></i> Mô tả
        </label>
        <textarea id="addDescription" rows="3"
                  class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 
                         focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600"
                  placeholder="Nhập mô tả về bộ môn..."></textarea>
      </div>

      <!-- Actions -->
      <div class="flex items-center justify-end gap-2 pt-2">
        <button type="button" onclick="closeModal('add')"
                class="px-4 py-2 rounded-lg border hover:bg-slate-50">
          Hủy
        </button>
        <button type="submit"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
          Lưu
        </button>
      </div>
    </form>
  </div>
</div>


  <!-- Modal sửa bộ môn -->
<div id="modalEditDepartment"
  class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-2xl shadow-xl">

    <!-- Header -->
    <div class="p-4 border-b flex items-center justify-between">
      <h3 class="font-semibold text-lg flex items-center gap-2">
        <i class="ph ph-buildings text-blue-600"></i>
        Sửa Bộ môn
      </h3>
      <button type="button" class="p-2 hover:bg-slate-100 rounded-lg"
              onclick="closeModal('edit')">
        <i class="ph ph-x"></i>
      </button>
    </div>

    <!-- Form -->
    <form class="p-6 space-y-5" onsubmit="event.preventDefault(); submitEditDepartment();">
      @csrf
      @method('PATCH')
      <input type="hidden" name="id" id="editId">

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-medium flex items-center gap-2">
            <i class="ph ph-hash"></i> Mã bộ môn
          </label>
          <input id="editCode" name="code" required
                 class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 
                        focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600"
                 placeholder="VD: D-CNTT" />
        </div>
        <div>
          <label class="text-sm font-medium flex items-center gap-2">
            <i class="ph ph-bookmarks"></i> Tên bộ môn
          </label>
          <input id="editName" name="name" required
                 class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 
                        focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600"
                 placeholder="VD: Công nghệ thông tin" />
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-medium flex items-center gap-2">
            <i class="ph ph-user-circle"></i> Trưởng bộ môn
          </label>
          <select id="editHead" name="head_id"
              class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 
                     focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
        <option value="">— Chọn trưởng bộ môn —</option>
        @foreach ($teachers as $teacher)
          <option value="{{ $teacher->id }}">{{ $teacher->user->fullname }}</option>
        @endforeach
      </select>
        </div>
      </div>

      <div>
        <label class="text-sm font-medium flex items-center gap-2">
          <i class="ph ph-text-align-left"></i> Mô tả
        </label>
        <textarea id="editDescription" name="description" rows="3"
              class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 
                     focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600"
              placeholder="Nhập mô tả về bộ môn..."></textarea>
      </div>

      <!-- Actions -->
      <div class="flex items-center justify-end gap-2 pt-2">
        <button type="button" onclick="closeModal('edit')"
                class="px-4 py-2 rounded-lg border hover:bg-slate-50">
          Hủy
        </button>
        <button type="submit"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
          Cập nhật
        </button>
      </div>
    </form>
  </div>
</div>


  <script>
    const html = document.documentElement, sidebar = document.getElementById('sidebar');
    function setCollapsed(c) { const h = document.querySelector('header'); const m = document.querySelector('main'); if (c) { html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); } else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); } }
    document.getElementById('toggleSidebar')?.addEventListener('click', () => { const c = !html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('assistant_sidebar', '' + (c ? 1 : 0)); });
    document.getElementById('openSidebar')?.addEventListener('click', () => sidebar.classList.toggle('-translate-x-full'));
    if (localStorage.getItem('assistant_sidebar') === '1') setCollapsed(true);
    sidebar.classList.add('md:translate-x-0', '-translate-x-full', 'md:static');

    // checkbox all
    document.getElementById('chkAll')?.addEventListener('change', (e) => { document.querySelectorAll('.rowChk').forEach(chk => chk.checked = e.target.checked); });

    // profile dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    profileBtn?.addEventListener('click', () => profileMenu.classList.toggle('hidden'));
    document.addEventListener('click', (e) => { if (!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

    // auto active nav highlight
    (function () {
      const current = location.pathname.split('/').pop();
      document.querySelectorAll('aside nav a').forEach(a => {
        const href = a.getAttribute('href') || '';
        const active = href.endsWith(current);
        a.classList.toggle('bg-slate-100', active);
        a.classList.toggle('font-semibold', active);
        if (active) a.classList.add('text-slate-900');
      });
    })();

    document.addEventListener('DOMContentLoaded', () => {
      const graduationItem = document.querySelector('.graduation-item');
      const toggleButton = graduationItem.querySelector('.toggle-button');
      const submenu = graduationItem.querySelector('.submenu');

      if (toggleButton && submenu) {
        toggleButton.addEventListener('click', (e) => {
          e.preventDefault(); // Prevent default link behavior
          submenu.classList.toggle('hidden');
        });
      }
    });

    // XÓA 2 hàm cũ dưới đây (nếu còn):
    // function openModal(mode) { document.getElementById('modalTitle')...; const m = document.getElementById('modal'); ... }
    // function closeModal() { const m = document.getElementById('modal'); ... }
    // window.openModal = openModal; window.closeModal = closeModal;

    // Helper: nếu option chưa có thì thêm tạm rồi set value
function ensureSelectedOption(selectEl, value, label) {
  if (!selectEl || !value) return;
  if (![...selectEl.options].some(o => o.value === String(value))) {
    const opt = document.createElement('option');
    opt.value = value;
    opt.textContent = label || value;
    selectEl.appendChild(opt);
  }
  selectEl.value = String(value);
}

function openModal(type, ds = null) {
  if (type === 'add') {
    const m = document.getElementById('modalAddDepartment');
    m.classList.remove('hidden'); m.classList.add('flex');
    return;
  }

  if (type === 'edit') {
    // Điền dữ liệu
    document.getElementById('editId').value = ds?.id || '';
    document.getElementById('editCode').value = ds?.code || '';
    document.getElementById('editName').value = ds?.name || '';
    document.getElementById('editDescription').value = ds?.description || '';

    const selHead = document.getElementById('editHead');
    const headId = ds?.headId || '';
    // Lấy nhãn từ cột bảng (nếu cần): tìm về tr gần nhất để lấy tên hiển thị
    let headLabel = '';
    try {
      const btn = document.querySelector(`button[data-id="${ds?.id}"][data-code="${ds?.code}"]`);
      const tr = btn?.closest('tr');
      headLabel = tr?.querySelectorAll('td')[3]?.innerText?.trim() || '';
    } catch (_) {}
    ensureSelectedOption(selHead, headId, headLabel);

    const m = document.getElementById('modalEditDepartment');
    m.classList.remove('hidden'); m.classList.add('flex');
  }
}

function closeModal(type) {
  const id = type === 'add' ? 'modalAddDepartment' : 'modalEditDepartment';
  const m = document.getElementById(id);
  m.classList.add('hidden'); m.classList.remove('flex');
}

window.openModal = openModal;
window.closeModal = closeModal;

    async function submitAddDepartment() {
      const code = document.getElementById('addCode').value.trim();
      const name = document.getElementById('addName').value.trim();
      const head = document.getElementById('addHead').value;
      const description = document.getElementById('addDescription').value.trim();
      const faculty_id = 1;

      const data  = {
        code: code,
        name: name,
        head_id: head,
        description: description,
        faculty_id: faculty_id  
      };

      console.log("data", data);

      try {
        const response = await fetch("{{route('web.assistant.departments.store')}}", {
          method: "POST",
          headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
          body: JSON.stringify(data)
        })

        if (!response.ok) {
            const err = await response.json();
            console.error("Lỗi:", err);
            alert("Thêm bộ môn thất bại!");
            return;
        }

        const result = await response.json();
        console.log("Kết quả:", result);

        alert("Thêm bộ môn thành công!");
        location.reload()
      } catch (error) {
        console.error("Lỗi fetch:", error);
        alert("Không thể kết nối server!");
      }
    } 

   async function submitEditDepartment() {
     const id = document.getElementById('editId')?.value;
     if (!id) { alert('Thiếu ID bộ môn'); return; }

     const code = document.getElementById('editCode')?.value?.trim() || '';
     const name = document.getElementById('editName')?.value?.trim() || '';
     const head = document.getElementById('editHead')?.value || '';
     const description = document.getElementById('editDescription')?.value?.trim() || '';
     const headLabel = document.getElementById('editHead')?.selectedOptions?.[0]?.textContent?.trim() || 'Chưa có trưởng bộ môn';

     const payload = {
       code, name, description,
       head_id: head || null
     };

     try {
       const url = `{{ route('web.assistant.departments.update', 0) }}`.replace('/0', '/' + id);
       const res = await fetch(url, {
         method: 'PATCH',
         headers: {
           'X-CSRF-TOKEN': '{{ csrf_token() }}',
           'Content-Type': 'application/json',
           'Accept': 'application/json'
         },
         body: JSON.stringify(payload)
       });
       const txt = await res.text();
       let data; try { data = JSON.parse(txt); } catch { console.error(txt); throw new Error('RESP_NOT_JSON'); }
       if (!res.ok || data.ok === false) {
         alert(data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Cập nhật thất bại'));
         return;
       }

       // Cập nhật lại hàng trong bảng
       const editBtn = document.querySelector(`button[data-id="${id}"]`);
       const tr = editBtn?.closest('tr');
       if (tr) {
         // Cột Mã (index 1)
         tr.querySelectorAll('td')[1].textContent = code;
         // Cột Tên (index 2)
         tr.querySelectorAll('td')[2].innerHTML = `
           <i class="ph ph-buildings text-slate-400"></i>
           ${name}
         `;
         // Cột Trưởng bộ môn (index 3)
         tr.querySelectorAll('td')[3].innerHTML = `
           <a href="../lecturer-ui/profile.html" class="flex items-center gap-2 text-blue-600 hover:underline">
             <i class="ph ph-user text-slate-400"></i>
             ${head ? headLabel : 'Chưa có trưởng bộ môn'}
           </a>
         `;
         // Cập nhật dataset của nút sửa
         editBtn.dataset.code = code;
         editBtn.dataset.name = name;
         editBtn.dataset.description = description;
         editBtn.dataset.headId = head || '';
       }

       closeModal('edit');
       // Optional: toast
       // alert('Cập nhật bộ môn thành công');
     } catch (err) {
       console.error(err);
       alert('Lỗi: ' + (err.message || 'Không xác định'));
     }
   }

    // Ủy quyền click nút Xóa
    document.getElementById('tableBody')?.addEventListener('click', async (e) => {
      const delBtn = e.target.closest('.btnDelete');
      if (!delBtn) return;

      const id = delBtn.dataset.id;
      if (!id) { alert('Thiếu ID bộ môn'); return; }

      if (!confirm('Bạn có chắc muốn xóa bộ môn này?')) return;

      const old = delBtn.innerHTML;
      delBtn.disabled = true;
      delBtn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i>';

      try {
        const url = `{{ route('web.assistant.departments.destroy', 0) }}`.replace('/0', '/' + id);
        const res = await fetch(url, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          }
        });

        const txt = await res.text();
        let data; try { data = JSON.parse(txt); } catch { data = { ok: res.ok }; }

        if (!res.ok || data.ok === false) {
          alert(data?.message || 'Xóa thất bại');
          return;
        }

        // Xóa hàng khỏi bảng
        delBtn.closest('tr')?.remove();
      } catch (err) {
        console.error(err);
        alert('Lỗi: ' + (err.message || 'Không xác định'));
      } finally {
        delBtn.disabled = false;
        delBtn.innerHTML = old;
      }
    });
  </script>
</body>

</html>