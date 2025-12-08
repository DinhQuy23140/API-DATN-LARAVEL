<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý Trợ lý khoa</title>
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
          <a href="{{ route('web.admin.manage_faculties') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">
            <i class="ph ph-graduation-cap"></i> <span class="sidebar-label">Quản lý Khoa</span>
          </a>
          <a href="{{ route('web.admin.manage_assistants') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 bg-slate-100 font-bold">
            <i class="ph ph-users-three"></i> <span class="sidebar-label">Trợ lý khoa</span>
          </a>
          <a href="{{ route('web.admin.manage_students') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">
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

      <!-- Main area (header fixed; make main content scrollable) -->
      <div class="flex-1 h-screen flex flex-col">
        <!-- Topbar -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
          <div class="flex items-center gap-3 flex-1">
            <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
            <div>
              <h1 class="text-lg md:text-xl font-semibold">Quản lý Trợ lý khoa</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Quản trị viên / Quản lý Trợ lý khoa</nav>
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
  <main class="flex-1 overflow-auto pt-16 px-4 md:px-6 pb-10">
    <div class="max-w-6xl mx-auto space-y-5">
          <!-- Search + Add -->
          <div class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="relative w-full sm:max-w-sm">
              <input id="searchInput" class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tên hoặc email" />
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <button id="btnAdd" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><i class="ph ph-plus"></i>Thêm tài khoản</button>
          </div>
          <!-- Role assignment UI (static) -->
          <div class="bg-white border border-slate-200 rounded-xl p-4 mb-4">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <!-- Current assistants list -->
              <div class="md:col-span-2 bg-slate-50 rounded-lg p-3">
                <div class="font-semibold mb-2 flex items-center gap-2"><i class="ph ph-users text-sky-600"></i> Tài khoản trợ lý khoa hiện tại</div>
                <!-- Assistants list: support both array-of-data and model shapes -->
                <ul class="space-y-2">
                  @if (empty($assistants) || $assistants->isEmpty())
                    <li class="text-sm text-slate-500">Chưa có trợ lý khoa nào được phân quyền.</li>
                  @else
                    @foreach($assistants as $asst)
                      @php
                        // support two shapes: array like ['id','name','email','faculty'] or object with user relation
                        $name = $asst['name'] ?? $asst->user->fullname ?? $asst->fullname ?? '';
                        $email = $asst['email'] ?? $asst->user->email ?? $asst->email ?? '';
                        $phone = $asst['phone'] ?? $asst->user->phone ?? $asst->phone ?? '';
                        $dob = $asst['dob'] ?? ($asst->user->dob ?? '') ?? '';
                        $aid = $asst['id'] ?? $asst->user->id ?? ($asst->id ?? '');
                      @endphp
                        <li class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                          <div>
                            <div class="font-medium flex items-center gap-2">
                              <i class="ph ph-user-circle text-indigo-500"></i> {{ $name }}
                            </div>
                            <div class="text-xs text-slate-500 mt-1">
                              <div class="flex items-center gap-3">
                                <i class="ph ph-envelope simple text-slate-400"></i> {{ $email }}
                              </div>
                              <div class="flex items-center gap-3 mt-1">
                                <i class="ph ph-phone simple text-slate-400"></i> {{ $phone ?: '-' }}
                              </div>
                              <div class="flex items-center gap-3 mt-1">
                                <i class="ph ph-calendar simple text-slate-400"></i> {{ $dob ?: '-' }}
                              </div>
                            </div>
                          </div>

                          <div class="flex flex-col items-end gap-2">
                            <div class="text-xs text-slate-400">ID: {{ $aid }}</div>

                            <!-- Nút xóa -->
                            <button 
                              class="text-red-500 hover:text-red-600 transition"
                              onclick="deleteAssistant('{{ $aid }}')"
                            >
                              <i class="ph ph-trash text-lg"></i>
                            </button>
                          </div>
                        </li>
                    @endforeach
                  @endif
                </ul>
              </div>

              <!-- Assignment control -->
              <div class="md:col-span-1 bg-white rounded-lg p-4 border border-slate-100">
                <div class="font-semibold mb-2 flex items-center gap-2"><i class="ph ph-shield-check text-emerald-600"></i> Phân quyền trợ lý khoa</div>
                <div class="text-sm text-slate-600 mb-3"><i class="ph ph-info-circle text-slate-400"></i> Chọn tài khoản trợ lý để phân quyền (giao diện tĩnh).</div>

                <label class="block text-sm font-medium text-slate-700">Chọn tài khoản</label>
                <select id="assistantSelect" class="mt-2 w-full px-3 py-2 rounded-lg border border-slate-200 text-sm">
                  <option value="">-- Chọn trợ lý --</option>
                  @foreach(($userTeachers ?? []) as $userTeacher)
                    @php
                      $optValue = $userTeacher->id;
                      $optLabel = $userTeacher-> fullname ?? " Không có tên";
                    @endphp
                    <option value="{{ $optValue }}">{{ $optLabel }}</option>
                  @endforeach
                </select>

                <div class="mt-4">
                  <button id="assignRoleBtn" class="w-full px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">Phân quyền</button>
                </div>

                <p id="assignMsg" class="mt-3 text-sm text-slate-500"></p>
              </div>
            </div>
          </div>

          <!-- Table -->
          <div class="bg-white border border-slate-200 rounded-xl">
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500">
                    <th class="py-3 px-4 border-b w-10"><input id="chkAll" type="checkbox" class="h-4 w-4" /></th>
                    <th class="py-3 px-4 border-b">Tên</th>
                    <th class="py-3 px-4 border-b">Email</th>
                    <th class="py-3 px-4 border-b">Trạng thái</th>
                    <th class="py-3 px-4 border-b">Khoa quản lý</th>
                    <th class="py-3 px-4 border-b text-right">Hành động</th>
                  </tr>
                </thead>
                <tbody id="tableBody">
                @if (!empty($faculties))
                @foreach ($faculties as $faculty)
                  @php
                    $facultyRoles = $faculty->facultyRoles ?? [];
                    $assistant = $facultyRoles->firstWhere('role', 'assistant');
                  @endphp
                  <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4"><input type="checkbox" class="rowChk h-4 w-4" /></td>
                    <td class="py-3 px-4">{{ $assistant?->user->fullname }}</td>
                    <td class="py-3 px-4">{{ $assistant?->user->email }}</td>
                    <td class="py-3 px-4"><span class="px-2 py-1 rounded-full text-xs bg-emerald-50 text-emerald-700">Active</span></td>
                    <td class="py-3 px-4"><a href="manage-faculties.html" class="text-blue-600 hover:underline">{{ $faculty->name }}</a></td>
                    <td class="py-3 px-4 text-right space-x-2">
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-amber-600"><i class="ph ph-key"></i> Reset</button>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" onclick="openModal('edit')"><i class="ph ph-pencil"></i> Sửa</button>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"><i class="ph ph-trash"></i> Xóa</button>
                    </td>
                  </tr>
                @endforeach
                @endif
                </tbody>
              </table>
            </div>
            <div class="p-4 flex items-center justify-between text-sm text-slate-600">
              <div>Hiển thị 1-2 của 24</div>
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

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">
      <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">
        <div class="p-4 border-b flex items-center justify-between">
          <h3 id="modalTitle" class="font-semibold">Thêm tài khoản</h3>
          <button class="p-2 hover:bg-slate-100 rounded-lg" onclick="closeModal()"><i class="ph ph-x"></i></button>
        </div>
        <form class="p-4 space-y-4" onsubmit="event.preventDefault(); closeModal();">
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-medium">Họ tên</label>
              <input required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600" placeholder="VD: Nguyễn Văn A" />
            </div>
            <div>
              <label class="text-sm font-medium">Email</label>
              <input type="email" required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600" placeholder="name@uni.edu" />
            </div>
          </div>
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-medium">Mật khẩu</label>
              <input type="password" required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600" placeholder="********" />
            </div>
            <div>
              <label class="text-sm font-medium">Khoa quản lý</label>
              <select class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                <option>Khoa Kỹ thuật</option>
                <option>Khoa Kinh tế</option>
                <option>Khoa Khoa học</option>
              </select>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <label class="text-sm font-medium">Trạng thái</label>
            <label class="inline-flex items-center cursor-pointer gap-2">
              <span class="relative inline-flex items-center">
                <input type="checkbox" class="peer sr-only" checked>
                <span class="w-11 h-6 bg-slate-200 rounded-full transition peer-checked:bg-blue-600"></span>
                <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></span>
              </span>
              <span class="text-sm text-slate-600">Active</span>
            </label>
          </div>
          <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
            <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Lưu</button>
          </div>
        </form>
      </div>
    </div>

    <script>
      const html = document.documentElement;
      const sidebar = document.getElementById('sidebar');
      const toggleSidebar = document.getElementById('toggleSidebar');
      const openSidebar = document.getElementById('openSidebar');
      const modal = document.getElementById('modal');
      const modalTitle = document.getElementById('modalTitle');

      function setCollapsed(collapsed){
  const header = document.querySelector('header');
  const mainEl = document.querySelector('main');
        if (collapsed) {
          html.classList.add('sidebar-collapsed');
        } else {
          html.classList.remove('sidebar-collapsed');
        }
      }
      toggleSidebar?.addEventListener('click', ()=>{
        const collapsed = !html.classList.contains('sidebar-collapsed');
        setCollapsed(collapsed);
        localStorage.setItem('admin_sidebar_collapsed', collapsed ? '1':'0');
      });
      openSidebar?.addEventListener('click', ()=> sidebar.classList.toggle('-translate-x-full'));
      if (localStorage.getItem('admin_sidebar_collapsed') === '1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      function openModal(mode){
        modalTitle.textContent = mode === 'edit' ? 'Sửa tài khoản' : 'Thêm tài khoản';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
      function closeModal(){
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
      window.openModal = openModal;
      window.closeModal = closeModal;

      document.getElementById('searchInput').addEventListener('input', (e)=>{
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('#tableBody tr').forEach(tr=>{
          tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
      })

      // Handler for static assignment UI
      document.getElementById('assignRoleBtn')?.addEventListener('click', ()=>{
        const sel = document.getElementById('assistantSelect');
        const msg = document.getElementById('assignMsg');
        const userId = sel ? sel.value : null;
        if (!userId) {
          msg.textContent = 'Vui lòng chọn một tài khoản trợ lý.';
          msg.classList.remove('text-emerald-600');
          msg.classList.add('text-rose-600');
          return;
        }
        // Static demo behavior: show selected userId. Replace with AJAX to send userId to server as needed.
        msg.textContent = 'Gửi userId: ' + userId;
        msg.classList.remove('text-rose-600');
        msg.classList.add('text-emerald-600');
        console.log('Assign role to userId=', userId);
      });

      // checkbox all
      document.getElementById('chkAll')?.addEventListener('change', (e)=>{
        document.querySelectorAll('.rowChk').forEach(chk=>{ chk.checked = e.target.checked; });
      });

      // profile dropdown
      const profileBtn=document.getElementById('profileBtn');
      const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=>{ profileMenu.classList.toggle('hidden'); });
      document.addEventListener('click', (e)=>{
        if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden');
      });

      // Assign assistant AJAX
      document.getElementById('assignRoleBtn')?.addEventListener('click', async ()=>{
        const sel = document.getElementById('assistantSelect');
        const msg = document.getElementById('assignMsg');
        const userId = sel ? sel.value : null;
        if(!userId){
          msg.textContent = 'Vui lòng chọn một tài khoản trợ lý.';
          msg.classList.remove('text-emerald-600');
          msg.classList.add('text-rose-600');
          return;
        }

        const btn = document.getElementById('assignRoleBtn');
        const oldHtml = btn.innerHTML;
        btn.disabled = true; btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> Đang gửi...';

        try {
          const url = '{{ route("web.admin.faculties.assign_assistant") }}';
          const res = await fetch(url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({ user_id: userId })
          });

          const data = await res.json().catch(()=>({}));
          if(!res.ok || data.ok === false){
            throw new Error(data.message || 'Không thể phân quyền');
          }

          // Success: show message and reload to refresh assistants list
          msg.textContent = data.message || 'Đã phân trợ lý khoa';
          msg.classList.remove('text-rose-600');
          msg.classList.add('text-emerald-600');
          setTimeout(()=> location.reload(), 700);
        } catch(err){
          console.error('Assign assistant failed', err);
          msg.textContent = err?.message || 'Lỗi khi phân quyền';
          msg.classList.remove('text-emerald-600');
          msg.classList.add('text-rose-600');
        } finally {
          btn.disabled = false; btn.innerHTML = oldHtml;
        }
      });

      // Delete / Unassign assistant
      async function deleteAssistant(userId){
        if(!confirm('Bạn có chắc muốn huỷ phân trợ lý cho người này?')) return;
        const msgEl = document.getElementById('assignMsg');
        const url = '{{ route("web.admin.faculties.remove_assistant") }}';
        const btn = document.getElementById('assignRoleBtn');
        const oldHtml = btn ? btn.innerHTML : null;
        if(btn){ btn.disabled = true; btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> Đang xử lý...'; }

        try{
          const res = await fetch(url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({ user_id: userId })
          });

          const data = await res.json().catch(()=>({}));
          if(!res.ok || data.ok === false) throw new Error(data.message || 'Không thể huỷ phân trợ lý');

          if(msgEl){ msgEl.textContent = data.message || 'Đã huỷ trợ lý'; msgEl.classList.remove('text-rose-600'); msgEl.classList.add('text-emerald-600'); }
          setTimeout(()=> location.reload(), 600);
        }catch(err){
          console.error('Remove assistant failed', err);
          if(msgEl){ msgEl.textContent = err?.message || 'Lỗi khi huỷ phân trợ lý'; msgEl.classList.remove('text-emerald-600'); msgEl.classList.add('text-rose-600'); }
        }finally{
          if(btn){ btn.disabled = false; btn.innerHTML = oldHtml; }
        }
      }
    </script>
  </body>
</html>
