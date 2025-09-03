<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
          <a href="dashboard.html" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">
            <i class="ph ph-gauge"></i> <span class="sidebar-label">Bảng điều khiển</span>
          </a>
          <a href="manage-faculties.html" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">
            <i class="ph ph-graduation-cap"></i> <span class="sidebar-label">Quản lý Khoa</span>
          </a>
          <a href="manage-assistants.html" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 text-slate-900 font-semibold">
            <i class="ph ph-users-three"></i> <span class="sidebar-label">Trợ lý khoa</span>
          </a>
        </nav>
        <div class="p-3 border-t border-slate-200">
          <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
        </div>
      </aside>

      <!-- Main area -->
      <div class="flex-1 md:pl-[260px] h-screen overflow-hidden flex flex-col">
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
              <img class="h-9 w-9 rounded-full object-cover" src="https://i.pravatar.cc/100?img=12" alt="avatar" />
              <div class="hidden sm:block text-left">
                <div class="text-sm font-semibold leading-4">Admin</div>
                <div class="text-xs text-slate-500">admin@uni.edu</div>
              </div>
              <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
            </button>
            <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
              <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông tin</a>
              <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
            </div>
          </div>
        </header>

  <!-- Content -->
  <main class="pt-20 px-4 md:px-6 pb-10">
    <div class="max-w-6xl mx-auto space-y-5">
          <!-- Search + Add -->
          <div class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="relative w-full sm:max-w-sm">
              <input id="searchInput" class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tên hoặc email" />
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <button id="btnAdd" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><i class="ph ph-plus"></i>Thêm tài khoản</button>
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
                  <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4"><input type="checkbox" class="rowChk h-4 w-4" /></td>
                    <td class="py-3 px-4">Nguyễn Minh Đức</td>
                    <td class="py-3 px-4">duc.nguyen@uni.edu</td>
                    <td class="py-3 px-4"><span class="px-2 py-1 rounded-full text-xs bg-emerald-50 text-emerald-700">Active</span></td>
                    <td class="py-3 px-4"><a href="manage-faculties.html" class="text-blue-600 hover:underline">Khoa Kỹ thuật</a></td>
                    <td class="py-3 px-4 text-right space-x-2">
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-amber-600"><i class="ph ph-key"></i> Reset</button>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" onclick="openModal('edit')"><i class="ph ph-pencil"></i> Sửa</button>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"><i class="ph ph-trash"></i> Xóa</button>
                    </td>
                  </tr>
                  <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4"><input type="checkbox" class="rowChk h-4 w-4" /></td>
                    <td class="py-3 px-4">Trần Thị Thu</td>
                    <td class="py-3 px-4">thu.tran@uni.edu</td>
                    <td class="py-3 px-4"><span class="px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-700">Inactive</span></td>
                    <td class="py-3 px-4"><a href="manage-faculties.html" class="text-blue-600 hover:underline">Khoa Kinh tế</a></td>
                    <td class="py-3 px-4 text-right space-x-2">
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-amber-600"><i class="ph ph-key"></i> Reset</button>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" onclick="openModal('edit')"><i class="ph ph-pencil"></i> Sửa</button>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"><i class="ph ph-trash"></i> Xóa</button>
                    </td>
                  </tr>
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
          header.classList.add('md:left-[72px]');
          header.classList.remove('md:left-[260px]');
    mainEl.classList.add('md:pl-[72px]');
    mainEl.classList.remove('md:pl-[260px]');
        } else {
          html.classList.remove('sidebar-collapsed');
          header.classList.remove('md:left-[72px]');
          header.classList.add('md:left-[260px]');
    mainEl.classList.remove('md:pl-[72px]');
    mainEl.classList.add('md:pl-[260px]');
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
    </script>
  </body>
</html>
