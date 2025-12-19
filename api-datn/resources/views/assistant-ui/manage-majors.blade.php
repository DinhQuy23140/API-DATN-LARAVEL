<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quản lý Ngành</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>

  <style>
    :root {
      --sidebar-open: 260px;
      --sidebar-collapsed: 72px;
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial;
    }

    /* sidebar width & layout when collapsed */
    #sidebar {
      width: var(--sidebar-open);
      transition: width .18s ease;
    }

    html.sidebar-collapsed #sidebar {
      width: var(--sidebar-collapsed);
    }

    header.app-header {
      left: var(--sidebar-open);
      right: 0;
      transition: left .18s ease;
    }

    html.sidebar-collapsed header.app-header {
      left: var(--sidebar-collapsed);
    }

    main.content {
      margin-left: var(--sidebar-open);
      padding-top: 4rem;
      /* header height */
      transition: margin-left .18s ease;
    }

    html.sidebar-collapsed main.content {
      margin-left: var(--sidebar-collapsed);
    }

    /* collapsed labels inside sidebar */
    html.sidebar-collapsed .sidebar-label {
      display: none;
    }

    /* small screens: sidebar hidden by default (utility handled by tailwind + js) */
    @media (max-width: 767px) {
      #sidebar {
        transform: translateX(-100%);
      }

      #sidebar.open {
        transform: translateX(0);
      }

      header.app-header {
        left: 0;
      }

      main.content {
        margin-left: 0;
      }
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
    $avatarUrl = $user->avatar_url
      ?? $user->profile_photo_url
      ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
  @endphp
  <aside id="sidebar" class="fixed inset-y-0 left-0 top-0 z-30 bg-white border-r border-slate-200 flex flex-col">
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
        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-buildings"></i><span
          class="sidebar-label">Bộ môn</span></a>
      <a href="{{ route('web.assistant.manage_majors') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold"><i
          class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
      <a href="{{ route('web.assistant.manage_staffs') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i
          class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
      <a href="{{ route('web.assistant.assign_head') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span
          class="sidebar-label">Phân trưởng bộ môn</span></a>

          <div class="graduation-item">
            <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button">
              <span class="flex items-center gap-3">
                <i class="ph ph-graduation-cap"></i>
                <span class="sidebar-label">Học phần tốt nghiệp</span>
              </span>
              <i class="ph ph-caret-down"></i>
            </div>
            <div class="submenu hidden pl-6">
              <a href="internship.html" class="block px-3 py-2 hover:bg-slate-100"><i class="ph ph-briefcase"></i> Thực tập tốt nghiệp</a>
              <a href="{{ route('web.assistant.rounds') }}" class="block px-3 py-2 hover:bg-slate-100"><i class="ph ph-calendar"></i> Đồ án tốt nghiệp</a>
            </div>
          </div>
    </nav>

    <div class="p-3 border-t border-slate-200">
      <button id="toggleSidebar"
        class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i
          class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
    </div>
  </aside>

  <header class="app-header fixed top-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-20">
    <div class="flex items-center gap-3 flex-1">
      <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
      <div>
        <h1 class="text-lg md:text-xl font-semibold">Quản lý Ngành</h1>
        <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Quản lý Ngành</nav>
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
          class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng
          xuất</a>
        <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
          @csrf
        </form>
      </div>
    </div>
  </header>

  <main class="content min-h-screen">
    <div class="max-w-6xl mx-auto px-4 md:px-6 py-6">
      <!-- Controls -->
      <section class="bg-white rounded-xl border border-slate-200 p-5 mb-6">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-lg font-semibold">Danh sách Ngành</h2>
            <div class="text-sm text-slate-500 mt-1">Quản lý thông tin ngành đào tạo</div>
          </div>
          <div class="flex items-center gap-3">
            <div class="relative">
              <input id="searchInput"
                class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm"
                placeholder="Tìm theo mã hoặc tên ngành" />
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <button id="btnAdd"
              class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm flex items-center gap-2"><i
                class="ph ph-plus"></i> Thêm ngành</button>
          </div>
        </div>
      </section>

      <!-- Table -->
<section class="bg-white rounded-xl border border-slate-200 p-5">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="text-left text-slate-500">
          <th class="py-3 px-4 border-b w-10 align-middle">
            <input id="chkAll" type="checkbox" class="h-4 w-4" />
          </th>
          <th class="py-3 px-4 border-b align-middle">Mã ngành</th>
          <th class="py-3 px-4 border-b">Thông tin ngành</th>
          <th class="py-3 px-4 border-b align-middle">Số sinh viên</th>
          <th class="py-3 px-4 border-b text-right align-middle">Hành động</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        @if($majors->isEmpty())
          <tr class="text-center">
            <td colspan="5" class="py-3 px-4 border-b">Không có dữ liệu</td>
          </tr>
        @else
          @foreach($majors as $major)
            <tr class="hover:bg-slate-50 align-middle">
              <!-- Checkbox -->
              <td class="py-3 px-4 align-middle">
                <input type="checkbox" class="rowChk h-4 w-4" />
              </td>

              <!-- Mã ngành -->
              <td class="py-3 px-4 font-semibold text-indigo-700">
                <i class="ph ph-identification-card text-indigo-600"></i>
                {{ $major->code }}
              </td>

              <!-- Thông tin ngành -->
              <td class="py-3 px-4">
                <!-- Tên ngành -->
                <div class="font-semibold text-slate-800 flex items-center gap-2">
                  <i class="ph ph-graduation-cap text-indigo-600"></i>
                  <span>{{ $major->name }}</span>
                </div>

                <!-- Khoa phụ trách -->
                <div class="text-slate-600 text-sm flex items-center gap-2 mt-1">
                  <i class="ph ph-buildings text-slate-500"></i>
                  <span>{{ $major->department?->name ?? 'Chưa có bộ môn phụ trách' }}</span>
                </div>

                <!-- Mô tả -->
                @if($major->description)
                  <div class="text-slate-500 text-sm mt-1 flex items-start gap-2">
                    <i class="ph ph-note text-slate-400 mt-0.5"></i>
                    <span>{{ $major->description }}</span>
                  </div>
                @endif
              </td>

              <!-- Số sinh viên -->
              <td class="py-3 px-4 font-medium text-slate-700 align-middle">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">
                  <i class="ph ph-users-three"></i> {{ $major->students->count() }}
                </span>
              </td>

              <!-- Hành động -->
              <td class="py-3 px-4 text-right space-x-2 align-middle">
                <button
                  class="btnEdit px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-indigo-600"
                  data-id="{{ $major->id }}"
                  data-code="{{ $major->code }}"
                  data-name="{{ $major->name }}"
                  data-faculty-id="{{ $major->faculties?->id }}"
                  data-description="{{ e($major->description) }}">
                  <i class="ph ph-pencil"></i>
                </button>
                <button class="btnDelete px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"
                  data-id="{{ $major->id }}">
                  <i class="ph ph-trash"></i>
                </button>
              </td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>
  </div>

  <!-- Phân trang -->
  <div class="p-4 flex items-center justify-between text-sm text-slate-600">
    <div id="infoCount">Hiển thị 1-2 của 32</div>
    <div class="inline-flex rounded-lg border border-slate-200 overflow-hidden">
      <button class="px-3 py-1.5 hover:bg-slate-50"><i class="ph ph-caret-left"></i></button>
      <button class="px-3 py-1.5 bg-slate-100 font-medium">1</button>
      <button class="px-3 py-1.5 hover:bg-slate-50">2</button>
      <button class="px-3 py-1.5 hover:bg-slate-50">3</button>
      <button class="px-3 py-1.5 hover:bg-slate-50"><i class="ph ph-caret-right"></i></button>
    </div>
  </div>
</section>

    </div>
  </main>

<!-- Modal Add Major -->
<div id="modalAdd" 
     class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">
    
    <!-- Header -->
    <div class="p-4 border-b flex items-center justify-between">
      <h3 class="font-semibold">Thêm ngành</h3>
      <button type="button" class="p-2 hover:bg-slate-100 rounded-lg" onclick="closeAddModal()">
        <i class="ph ph-x"></i>
      </button>
    </div>

    <!-- Form -->
    <form id="formAdd" class="p-4 space-y-4" onsubmit="event.preventDefault(); saveNewMajor();">

      <!-- Code + Name -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-medium flex items-center gap-1">
            <i class="ph ph-hash text-slate-500"></i> Mã ngành
          </label>
          <input id="addCode" required 
                 class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" 
                 placeholder="VD: MJ-SE"/>
        </div>
        <div>
          <label class="text-sm font-medium flex items-center gap-1">
            <i class="ph ph-book text-slate-500"></i> Tên ngành
          </label>
          <input id="addName" required 
                 class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" 
                 placeholder="VD: Kỹ thuật phần mềm"/>
        </div>
      </div>

      <!-- Description -->
      <div>
        <label class="text-sm font-medium flex items-center gap-1">
          <i class="ph ph-text-align-left text-slate-500"></i> Mô tả
        </label>
        <textarea id="addDescription" rows="3" 
                  class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                  placeholder="Mô tả chi tiết ngành..."></textarea>
      </div>

      <!-- Actions -->
      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="closeAddModal()" 
                class="px-4 py-2 border rounded-lg hover:bg-slate-50">
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


<!-- Modal Edit Major -->
<div id="modalEdit" 
     class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">
    
    <!-- Header -->
    <div class="p-4 border-b flex items-center justify-between">
      <h3 class="font-semibold">Sửa ngành</h3>
      <button type="button" class="p-2 hover:bg-slate-100 rounded-lg" onclick="closeEditModal()">
        <i class="ph ph-x"></i>
      </button>
    </div>

    <!-- Form -->
    <form id="formEdit" class="p-4 space-y-4" onsubmit="event.preventDefault(); updateMajor();">
      <input type="hidden" id="editId"/>

      <!-- Code + Name -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-medium flex items-center gap-1">
            <i class="ph ph-hash text-slate-500"></i> Mã ngành
          </label>
          <input id="editCode" required 
                 class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"/>
        </div>
        <div>
          <label class="text-sm font-medium flex items-center gap-1">
            <i class="ph ph-book text-slate-500"></i> Tên ngành
          </label>
          <input id="editName" required 
                 class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"/>
        </div>
      </div>

      <!-- Description -->
      <div>
        <label class="text-sm font-medium flex items-center gap-1">
          <i class="ph ph-text-align-left text-slate-500"></i> Mô tả
        </label>
        <textarea id="editDescription" rows="3" 
                  class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
      </div>

      <!-- Actions -->
      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="closeEditModal()" 
                class="px-4 py-2 border rounded-lg hover:bg-slate-50">
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
    // Layout controls
    const htmlEl = document.documentElement;
    const sidebar = document.getElementById('sidebar');
    const openSidebarBtn = document.getElementById('openSidebar');
    const toggleSidebarBtn = document.getElementById('toggleSidebar');

    function setCollapsed(collapsed) {
      if (collapsed) htmlEl.classList.add('sidebar-collapsed');
      else htmlEl.classList.remove('sidebar-collapsed');
      localStorage.setItem('assistant_sidebar', collapsed ? '1' : '0');
    }

    // Load saved preference
    if (localStorage.getItem('assistant_sidebar') === '1') setCollapsed(true);

    // Toggle (desktop)
    toggleSidebarBtn?.addEventListener('click', () => {
      setCollapsed(!htmlEl.classList.contains('sidebar-collapsed'));
    });

    // Mobile open/close
    openSidebarBtn?.addEventListener('click', () => {
      sidebar.classList.toggle('open');
    });

    // Submenu toggle
    document.addEventListener('DOMContentLoaded', () => {
      const graduationItem = document.querySelector('.graduation-item');
      const toggleButton = graduationItem?.querySelector('.toggle-button');
      const submenu = graduationItem?.querySelector('.submenu');

      toggleButton?.addEventListener('click', (e) => {
        e.preventDefault();
        submenu.classList.toggle('hidden');
      });
    });

    // Profile dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    profileBtn?.addEventListener('click', (e) => {
      e.stopPropagation();
      profileMenu?.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
      if (!profileBtn?.contains(e.target)) profileMenu?.classList.add('hidden');
    });

    // Search filter
    document.getElementById('searchInput')?.addEventListener('input', (e) => {
      const q = e.target.value.toLowerCase();
      document.querySelectorAll('#tableBody tr').forEach(tr => {
        tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
      });
    });

    // Checkbox select all
    const chkAll = document.getElementById('chkAll');
    chkAll?.addEventListener('change', (e) => {
      document.querySelectorAll('.rowChk').forEach(chk => chk.checked = e.target.checked);
    });

function openAddModal() {
  document.getElementById('formAdd').reset();
  const m = document.getElementById('modalAdd');
  m.classList.remove('hidden'); m.classList.add('flex');
}

function closeAddModal() {
  const m = document.getElementById('modalAdd');
  m.classList.add('hidden'); m.classList.remove('flex');
}

// Sửa: nhận dataset, không đụng tới editFaculty
function openEditModal(ds) {
  document.getElementById('editId').value = ds.id || '';
  document.getElementById('editCode').value = ds.code || '';
  document.getElementById('editName').value = ds.name || '';
  document.getElementById('editDescription').value = ds.description || '';
  const m = document.getElementById('modalEdit');
  m.classList.remove('hidden'); m.classList.add('flex');
}

function closeEditModal() {
  const m = document.getElementById('modalEdit');
  m.classList.add('hidden'); m.classList.remove('flex');
}

// expose
window.openAddModal = openAddModal;
window.closeAddModal = closeAddModal;
window.openEditModal = openEditModal;
window.closeEditModal = closeEditModal;

// Ủy quyền click: Sửa + Xóa
document.getElementById('tableBody')?.addEventListener('click', async (e) => {
  const editBtn = e.target.closest('.btnEdit');
  if (editBtn) {
    openEditModal(editBtn.dataset);
    return;
  }
  const delBtn = e.target.closest('.btnDelete');
  if (delBtn) {
    const id = delBtn.dataset.id || delBtn.closest('tr')?.querySelector('.btnEdit')?.dataset.id;
    if (!id) { alert('Thiếu ID ngành'); return; }
    if (!confirm('Bạn có chắc muốn xóa ngành này?')) return;
    const ok = await deleteMajor(id);
    if (ok) delBtn.closest('tr')?.remove();
  }
});

    // expose đúng các hàm dùng inline
    window.openAddModal = openAddModal;
    window.closeAddModal = closeAddModal;
    window.openEditModal = openEditModal;
    window.closeEditModal = closeEditModal;

    // Gỡ bỏ 2 dòng gây lỗi ReferenceError:
// window.openModal = openModal;
// window.closeModal = closeModal;

    // Save (demo: simply add to table)
    async function saveNewMajor() {
      const code = document.getElementById('addCode')?.value.trim() || '';
      const name = document.getElementById('addName')?.value.trim() || '';
      const description = document.getElementById('addDescription')?.value.trim() || '';
      if (!code || !name) { alert('Vui lòng nhập mã và tên ngành'); return; }

      const payload = { code, name, description, faculty_id: 1 };
      try {
        const res = await fetch("{{ route('web.assistant.majors.store') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          body: JSON.stringify(payload)
        });
        const txt = await res.text();
        let data; try { data = JSON.parse(txt); } catch { console.error(txt); throw new Error('RESP_NOT_JSON'); }
        if (!res.ok || data.ok === false) {
          const msg = data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Lưu thất bại');
          alert(msg); return;
        }
        const m = data.data || data;

        // Thêm hàng mới
        const tb = document.getElementById('tableBody');
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-slate-50 align-middle';
        tr.innerHTML = `
          <td class="py-3 px-4 align-middle"><input type="checkbox" class="rowChk h-4 w-4" /></td>
          <td class="py-3 px-4 font-semibold text-indigo-700 flex items-center gap-2 align-middle">
            <i class="ph ph-identification-card text-indigo-600"></i>${m.code}
          </td>
          <td class="py-3 px-4 align-middle">
            <div class="font-semibold text-slate-800 flex items-center gap-2">
              <i class="ph ph-graduation-cap text-indigo-600"></i>
              <span>${m.name}</span>
            </div>
            <div class="text-slate-600 text-sm flex items-center gap-2 mt-1">
              <i class="ph ph-buildings text-slate-500"></i>
              <span>${m.faculties?.name || 'Chưa có khoa phụ trách'}</span>
            </div>
            ${m.description ? `
              <div class="text-slate-500 text-sm mt-1 flex items-start gap-2">
                <i class="ph ph-note text-slate-400 mt-0.5"></i>
                <span>${m.description}</span>
              </div>` : ''}
          </td>
          <td class="py-3 px-4 font-medium text-slate-700 align-middle">
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">
              <i class="ph ph-users-three"></i> ${m.students_count ?? 0}
            </span>
          </td>
          <td class="py-3 px-4 text-right space-x-2 align-middle">
            <button
              class="btnEdit px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-indigo-600"
              data-id="${m.id}"
              data-code="${m.code}"
              data-name="${m.name}"
              data-faculty-id="${m.faculty_id ?? m.faculties?.id ?? ''}"
              data-description="${m.description ?? ''}">
              <i class="ph ph-pencil"></i>
            </button>
            <button class="btnDelete px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600" data-id="${m.id}">
              <i class="ph ph-trash"></i>
            </button>
          </td>
        `;
        tb?.prepend(tr);

        document.getElementById('formAdd')?.reset();
        closeAddModal();
      } catch (err) {
        console.error(err);
        alert('Lỗi: ' + (err.message || 'Không xác định'));
      }
    }

    async function updateMajor() {
      const id = document.getElementById('editId')?.value;
      const code = document.getElementById('editCode')?.value.trim() || '';
      const name = document.getElementById('editName')?.value.trim() || '';
      const description = document.getElementById('editDescription')?.value.trim() || '';
      if (!id || !code || !name) { alert('Vui lòng nhập mã và tên ngành'); return; }
      const payload = { code, name, description, faculty_id: 1 };
      try {
        const res = await fetch(`{{ route('web.assistant.majors.update', 0) }}`.replace('/0', '/' + id), {
          method: 'PATCH',
           headers: {
             'Content-Type': 'application/json',
             'Accept': 'application/json',
             'X-CSRF-TOKEN': "{{ csrf_token() }}"
           },
           body: JSON.stringify(payload)
         });
        const txt = await res.text();
        let data; try { data = JSON.parse(txt); } catch { console.error(txt); throw new Error('RESP_NOT_JSON'); }
        if (!res.ok || data.ok === false) {
          const msg = data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Cập nhật thất bại');
          alert(msg); return;
        }
        const m = data.data || data;

        // Cập nhật hàng
        const tb = document.getElementById('tableBody');
        const tr = tb?.querySelector(`button.btnEdit[data-id="${id}"]`)?.closest('tr');
        if (!tr) throw new Error('ROW_NOT_FOUND');
        tr.innerHTML = `
          <td class="py-3 px-4 align-middle"><input type="checkbox" class="rowChk h-4 w-4" /></td>
          <td class="py-3 px-4 font-semibold text-indigo-700 flex items-center gap-2 align-middle">
            <i class="ph ph-identification-card text-indigo-600"></i>${m.code}
          </td>
          <td class="py-3 px-4 align-middle">
            <div class="font-semibold text-slate-800 flex items-center gap-2">
              <i class="ph ph-graduation-cap text-indigo-600"></i>
              <span>${m.name}</span>
            </div>
            <div class="text-slate-600 text-sm flex items-center gap-2 mt-1">
              <i class="ph ph-buildings text-slate-500"></i>
              <span>${m.faculties?.name || 'Chưa có khoa phụ trách'}</span>
            </div>
            ${m.description ? `
              <div class="text-slate-500 text-sm mt-1 flex items-start gap-2">
                <i class="ph ph-note text-slate-400 mt-0.5"></i>
                <span>${m.description}</span>
              </div>` : ''}
          </td>
          <td class="py-3 px-4 font-medium text-slate-700 align-middle">
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">
              <i class="ph ph-users-three"></i> ${m.students_count ?? 0}
            </span>
          </td>
          <td class="py-3 px-4 text-right space-x-2 align-middle">
            <button
              class="btnEdit px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-indigo-600"
              data-id="${m.id}"
              data-code="${m.code}"
              data-name="${m.name}"
              data-faculty-id="${m.faculty_id ?? m.faculties?.id ?? ''}"
              data-description="${m.description ?? ''}">
              <i class="ph ph-pencil"></i>
            </button>
            <button class="btnDelete px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600" data-id="${m.id}">
              <i class="ph ph-trash"></i>
            </button>
          </td>
        `;

        // Đóng modal
        closeEditModal();
      } catch (err) {
        console.error(err);
        alert('Lỗi: ' + (err.message || 'Không xác định'));
      }

    }

    function deleteRow(btn) {
      if (!confirm('Bạn có chắc muốn xóa ngành này?')) return;
      const tr = btn.closest('tr');
      tr?.remove();
    }

    async function deleteMajor(id) {
      try {
        const res = await fetch(`{{ route('web.assistant.majors.destroy', 0) }}`.replace('/0', '/' + id), {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          }
        });
        const txt = await res.text();
        let data; try { data = JSON.parse(txt); } catch { console.error(txt); throw new Error('RESP_NOT_JSON'); }
        if (!res.ok || data.ok === false) {
          const msg = data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Xóa thất bại');
          alert(msg); return false;
        }
        return true;
      } catch (err) {
        console.error(err);
        alert('Lỗi: ' + (err.message || 'Không xác định'));
        return false;
      }
    }

    // hook add button
    document.getElementById('btnAdd')?.addEventListener('click', openAddModal);

    // Đóng modal khi click backdrop
    ['modalAdd','modalEdit'].forEach((id)=>{
      const m = document.getElementById(id);
      m?.addEventListener('click', (e)=>{
        if(e.target.id !== id) return;
        if(id === 'modalAdd') closeAddModal();
        else closeEditModal();
      });
    });

    // Gỡ listener thừa tới #modal không tồn tại
    // document.getElementById('modal')?.addEventListener(...);

    // (Tùy chọn) tránh lỗi submit vì thiếu hàm:
    window.saveNewMajor = saveNewMajor;
  </script>
</body>

</html>