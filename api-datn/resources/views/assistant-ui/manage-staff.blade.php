<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý Giảng viên</title>
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
      .submenu {
    display: none;
  }
  .submenu.hidden {
    display: none;
  }
  .submenu:not(.hidden) {
    display: block;
  }

  /* Ẩn message của pagination Tailwind ("Showing ...") */
  .pg-wrap .sm\:justify-between > div:first-child { display: none !important; }
  .pg-wrap p.text-sm { display: none !important; }
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
    <div class="flex min-h-screen">
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
          <a href="{{ route('web.assistant.manage_staffs') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold"><i class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
          <a href="{{ route('web.assistant.assign_head') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Phân trưởng bộ môn</span></a>

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
          <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
        </div>
      </aside>

  <div class="flex-1">
        <header class="fixed left-0 md:left-[260px] right-0 top-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-20">
          <div class="flex items-center gap-3 flex-1">
            <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
            <div>
              <h1 class="text-lg md:text-xl font-semibold">Quản lý Cán bộ - Giảng viên</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Quản lý Cán bộ - Giảng viên</nav>
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

  <main class="pt-20 px-4 md:px-6 pb-10 space-y-5 md:pl-[260px]">
    <div class="max-w-6xl mx-auto space-y-5">
          <div class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="relative w-full sm:max-w-sm">
              <input id="searchInput" class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tên, mã GV, email" />
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <button onclick="openModal('add')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><i class="ph ph-plus"></i>Thêm giảng viên</button>
          </div>

          <div class="bg-white border border-slate-200 shadow-md rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-sm text-slate-700">
                <thead class="bg-slate-100 text-slate-600 text-xs uppercase">
                  <tr>
                    <th class="py-3 px-4 w-10 text-center border-b">
                      <input id="chkAll" type="checkbox" class="h-4 w-4 rounded border-slate-300"/>
                    </th>
                    <th class="py-3 px-4 border-b">Mã GV</th>
                    <th class="py-3 px-4 border-b text-left">Tên</th>
                    <th class="py-3 px-4 border-b text-left">Bộ môn</th>
                    <th class="py-3 px-4 border-b text-left">Chức vụ</th>
                    <th class="py-3 px-4 border-b text-left">Email</th>
                    <th class="py-3 px-4 border-b text-right">Hành động</th>
                  </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-slate-200">
                  @foreach ($teachers as $teacher)
                    @php
                      // Lấy roles của bộ môn
                      $rolesDepartment = $teacher->departmentRoles?->pluck('role')->toArray() ?? [];
                      $listRolesDepartment = [
                        'head' => 'Trưởng bộ môn',
                        'vice_head' => 'Phó bộ môn',
                        'staff' => 'Cán bộ bộ môn'
                      ];
                      $rolesDepartmentWithNames = array_map(fn($role) => $listRolesDepartment[$role] ?? $role, $rolesDepartment);
                      $rolesDepartmentText = $rolesDepartment ? implode(', ', $rolesDepartmentWithNames) : '';

                      // Lấy roles của khoa
                      $rolesFaculty = $teacher->user?->facultyRoles?->pluck('role')->toArray() ?? [];
                      $listRolesFaculty = [
                        'dean'=> 'Trưởng khoa',
                        'vice_dean' => 'Phó khoa',
                        'assistant' => 'Trợ lý khoa'
                      ];
                      $rolesFacultyWithNames = array_map(fn($role) => $listRolesFaculty[$role] ?? $role, $rolesFaculty);
                      $rolesFacultyText = $rolesFaculty ? implode(', ', $rolesFacultyWithNames) : '';

                      // Ghép text hiển thị
                      if ($rolesDepartmentText && $rolesFacultyText) {
                        $rolesText = $rolesDepartmentText . ', ' . $rolesFacultyText;
                      } elseif ($rolesDepartmentText) {
                        $rolesText = $rolesDepartmentText;
                      } elseif ($rolesFacultyText) {
                        $rolesText = $rolesFacultyText;
                      } else {
                        $rolesText = 'Giảng viên';
                      }
                    @endphp

                    <tr class="hover:bg-slate-50 transition-colors">
                      <td class="py-3 px-4 text-center">
                        <input type="checkbox" class="rowChk h-4 w-4 rounded border-slate-300"/>
                      </td>
                      <td class="py-3 px-4 font-medium text-slate-800">{{ $teacher->teacher_code }}</td>
                      <td class="py-3 px-4">
                        <a class="text-blue-600 hover:text-blue-800 hover:underline font-medium" href="../lecturer-ui/profile.html">
                          {{ $teacher->user->fullname }}
                        </a>
                      </td>
                      <td class="py-3 px-4">{{ $teacher->department->name ?? 'Chưa có bộ môn' }}</td>
                      <td class="py-3 px-4 text-left">{{ $rolesText }}</td>
                      <td class="py-3 px-4">{{ $teacher->user->email }}</td>
                      <td class="py-3 px-4 text-right space-x-1">
                        <button class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-600 hover:bg-slate-100 transition" onclick="openModal('edit')">
                          <i class="ph ph-pencil"></i>
                        </button>
                        <button class="inline-flex items-center px-3 py-1.5 rounded-lg border border-rose-300 bg-white text-rose-600 hover:bg-rose-50 transition">
                          <i class="ph ph-trash"></i>
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="p-4 flex items-center justify-between text-sm text-slate-600 bg-slate-50 border-t">
              @if ($teachers instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                <div>
                  Hiển thị {{ $teachers->firstItem() }}-{{ $teachers->lastItem() }} của {{ $teachers->total() }}
                </div>
                <div class="ml-auto pg-wrap">
                  {!! method_exists($teachers, 'links')
                        ? $teachers->appends(request()->query())->links('pagination::tailwind')
                        : $teachers->appends(request()->query())->render() !!}
                </div>
              @else
                <div>Hiển thị {{ count($teachers ?? []) }} mục</div>
              @endif
            </div>
          </div>

          
        </div>
        </main>
      </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">
      <div class="bg-white rounded-xl w-full max-w-2xl shadow-xl">
        <div class="p-4 border-b flex items-center justify-between">
          <h3 id="modalTitle" class="font-semibold">Thêm giảng viên</h3>
          <button class="p-2 hover:bg-slate-100 rounded-lg" onclick="closeModal()"><i class="ph ph-x"></i></button>
        </div>
        <form class="p-4 space-y-4" onsubmit="event.preventDefault(); closeModal();">
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-medium">Mã GV</label>
              <input required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600" placeholder="VD: GV001" />
            </div>
            <div>
              <label class="text-sm font-medium">Họ tên</label>
              <input required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600" placeholder="VD: Nguyễn Văn A" />
            </div>
          </div>
          <div class="grid sm:grid-cols-4 gap-4">
            <div>
              <label class="text-sm font-medium">Bộ môn</label>
              <select class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                <option>CNTT</option>
                <option>Điện</option>
              </select>
            </div>
            <div>
              <label class="text-sm font-medium">Chức vụ</label>
              <select class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                <option>Giảng viên</option>
                <option>Phó bộ môn</option>
                <option>Trưởng bộ môn</option>
              </select>
            </div>
            <div>
              <label class="text-sm font-medium">Ngành</label>
              <select class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                <option>KTPM</option>
                <option>TĐH</option>
              </select>
            </div>
            <div>
              <label class="text-sm font-medium">Email</label>
              <input type="email" required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600" placeholder="name@uni.edu" />
            </div>
          </div>
          <div class="grid sm:grid-cols-3 gap-4">
            <div>
              <label class="text-sm font-medium">Trạng thái</label>
              <select class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
          <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
            <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Lưu</button>
          </div>
        </form>
      </div>
    </div>

    <script>
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
  function setCollapsed(c){
    const h=document.querySelector('header');
    const m=document.querySelector('main');
    if(c){
      html.classList.add('sidebar-collapsed');
      h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]');
      m.classList.add('md:pl-[72px]');   m.classList.remove('md:pl-[260px]');
    } else {
      html.classList.remove('sidebar-collapsed');
      h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]');
      m.classList.remove('md:pl-[72px]');   m.classList.add('md:pl-[260px]');
    }
  }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed');setCollapsed(c);localStorage.setItem('assistant_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);
      // Init: sidebar luôn fixed; mobile ẩn, md+ hiện
      sidebar.classList.add('transition-transform','transform','-translate-x-full','md:translate-x-0');
      if (window.matchMedia('(min-width:768px)').matches) sidebar.classList.remove('-translate-x-full');

      function openModal(mode){document.getElementById('modalTitle').textContent = mode==='edit'?'Sửa giảng viên':'Thêm giảng viên'; const m=document.getElementById('modal');m.classList.remove('hidden');m.classList.add('flex');}
      function closeModal(){const m=document.getElementById('modal');m.classList.add('hidden');m.classList.remove('flex');}
      window.openModal=openModal; window.closeModal=closeModal;

      document.getElementById('searchInput').addEventListener('input', e=>{const q=e.target.value.toLowerCase();document.querySelectorAll('#tableBody tr').forEach(tr=>{tr.style.display = tr.innerText.toLowerCase().includes(q)?'':'';});});

  // checkbox all
  document.getElementById('chkAll')?.addEventListener('change', (e)=>{document.querySelectorAll('.rowChk').forEach(chk=> chk.checked = e.target.checked);});

  // profile dropdown
  const profileBtn=document.getElementById('profileBtn');
  const profileMenu=document.getElementById('profileMenu');
  profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
  document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

  // auto active nav highlight
  (function(){
    const current = location.pathname.split('/').pop();
    document.querySelectorAll('aside nav a').forEach(a=>{
      const href=a.getAttribute('href')||'';
      const active=href.endsWith(current);
      a.classList.toggle('bg-slate-100', active);
      a.classList.toggle('font-semibold', active);
      if(active) a.classList.add('text-slate-900');
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
    </script>
  </body>
</html>
