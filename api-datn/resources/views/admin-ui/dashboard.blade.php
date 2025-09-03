<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bảng điều khiển quản trị viên</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <style>
      :root { --primary:#2563eb; }
      body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, 'Apple Color Emoji', 'Segoe UI Emoji'; }
      .sidebar-collapsed .sidebar-label { display:none; }
      .sidebar-collapsed .sidebar { width:72px; }
      .sidebar { width:260px; }
  /* utility classes if needed */
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
          <a href="manage-assistants.html" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">
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
              <h1 class="text-lg md:text-xl font-semibold">Bảng điều khiển quản trị viên</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Quản trị viên / Dashboard</nav>
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
        <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 space-y-6">
          <div class="max-w-6xl mx-auto space-y-6">
          <!-- Cards -->
          <section class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="manage-faculties.html" class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4 hover:shadow-sm transition">
              <div class="h-12 w-12 rounded-lg bg-blue-50 text-blue-600 grid place-items-center"><i class="ph ph-graduation-cap text-xl"></i></div>
              <div>
                <div class="text-sm text-slate-500">Tổng số Khoa</div>
                <div class="text-2xl font-semibold">12</div>
                <div class="text-xs text-emerald-600 mt-1 flex items-center gap-1"><i class="ph ph-trend-up"></i>+2 trong tháng</div>
              </div>
            </a>
            <a href="manage-assistants.html" class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4 hover:shadow-sm transition">
              <div class="h-12 w-12 rounded-lg bg-blue-50 text-blue-600 grid place-items-center"><i class="ph ph-users-three text-xl"></i></div>
              <div>
                <div class="text-sm text-slate-500">Tổng số Trợ lý khoa</div>
                <div class="text-2xl font-semibold">24</div>
                <div class="text-xs text-emerald-600 mt-1 flex items-center gap-1"><i class="ph ph-trend-up"></i>+5 tuần này</div>
              </div>
            </a>
          </section>

          <!-- Charts & Table -->
          <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Pie chart (mock) -->
            <div class="bg-white rounded-xl border border-slate-200 p-5">
              <div class="flex items-center justify-between">
                <h2 class="font-semibold">Phân bố khoa theo lĩnh vực</h2>
                <button class="text-slate-500 hover:text-slate-700 text-sm flex items-center gap-1"><i class="ph ph-download-simple"></i>Xuất</button>
              </div>
              <div class="mt-6 grid place-items-center">
                <div class="relative h-56 w-56">
                  <div class="absolute inset-0 rounded-full" style="background:
                    conic-gradient(#2563eb 0 35%, #60a5fa 35% 58%, #93c5fd 58% 78%, #c7d2fe 78% 100%);"></div>
                  <div class="absolute inset-4 rounded-full bg-white"></div>
                  <div class="absolute inset-0 grid place-items-center">
                    <div class="text-center">
                      <div class="text-2xl font-semibold">12</div>
                      <div class="text-xs text-slate-500">Tổng khoa</div>
                    </div>
                  </div>
                </div>
                <div class="mt-6 grid grid-cols-2 gap-3 w-full max-w-sm text-sm">
                  <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>Kỹ thuật (35%)</div>
                  <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-blue-400"></span>Kinh tế (23%)</div>
                  <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-blue-300"></span>Khoa học (20%)</div>
                  <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-indigo-200"></span>Xã hội (22%)</div>
                </div>
              </div>
            </div>

            <!-- Recent faculties table -->
            <div class="bg-white rounded-xl border border-slate-200 p-5">
              <div class="flex items-center justify-between">
                <h2 class="font-semibold">Khoa mới thêm gần đây</h2>
                <div class="relative">
                  <input class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tên hoặc mã" />
                  <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
              </div>
              <div class="mt-4 overflow-x-auto">
                <table class="w-full text-sm">
                  <thead>
                    <tr class="text-left text-slate-500">
                      <th class="py-2.5 border-b border-slate-200 w-10"><input id="chkAll" type="checkbox" class="h-4 w-4"></th>
                      <th class="py-2.5 border-b border-slate-200">Mã khoa</th>
                      <th class="py-2.5 border-b border-slate-200">Tên khoa</th>
                      <th class="py-2.5 border-b border-slate-200">Ngày tạo</th>
                      <th class="py-2.5 border-b border-slate-200 text-right">Hành động</th>
                    </tr>
                  </thead>
                  <tbody id="recentBody">
                    <tr class="hover:bg-slate-50">
                      <td class="py-3"><input type="checkbox" class="rowChk h-4 w-4"></td>
                      <td class="py-3">FENG</td><td class="py-3">Khoa Kỹ thuật</td><td class="py-3">02/08/2025</td>
                      <td class="py-3 text-right"><a class="text-blue-600 hover:underline" href="manage-faculties.html">Xem chi tiết</a></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                      <td class="py-3"><input type="checkbox" class="rowChk h-4 w-4"></td>
                      <td class="py-3">FBUS</td><td class="py-3">Khoa Kinh tế</td><td class="py-3">31/07/2025</td>
                      <td class="py-3 text-right"><a class="text-blue-600 hover:underline" href="manage-faculties.html">Xem chi tiết</a></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                      <td class="py-3"><input type="checkbox" class="rowChk h-4 w-4"></td>
                      <td class="py-3">FSCI</td><td class="py-3">Khoa Khoa học</td><td class="py-3">29/07/2025</td>
                      <td class="py-3 text-right"><a class="text-blue-600 hover:underline" href="manage-faculties.html">Xem chi tiết</a></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                      <td class="py-3"><input type="checkbox" class="rowChk h-4 w-4"></td>
                      <td class="py-3">FSOC</td><td class="py-3">Khoa Xã hội</td><td class="py-3">25/07/2025</td>
                      <td class="py-3 text-right"><a class="text-blue-600 hover:underline" href="manage-faculties.html">Xem chi tiết</a></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                      <td class="py-3"><input type="checkbox" class="rowChk h-4 w-4"></td>
                      <td class="py-3">FART</td><td class="py-3">Khoa Nghệ thuật</td><td class="py-3">21/07/2025</td>
                      <td class="py-3 text-right"><a class="text-blue-600 hover:underline" href="manage-faculties.html">Xem chi tiết</a></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="mt-3 flex items-center justify-between text-sm text-slate-600">
                <div>Hiển thị 5 mục</div>
                <div class="inline-flex rounded-lg border border-slate-200 overflow-hidden">
                  <button class="px-3 py-1.5 hover:bg-slate-50"><i class="ph ph-caret-left"></i></button>
                  <button class="px-3 py-1.5 bg-slate-100 font-medium">1</button>
                  <button class="px-3 py-1.5 hover:bg-slate-50">2</button>
                  <button class="px-3 py-1.5 hover:bg-slate-50">3</button>
                  <button class="px-3 py-1.5 hover:bg-slate-50"><i class="ph ph-caret-right"></i></button>
                </div>
              </div>
            </div>
          </section>
          </div>
        </main>
      </div>
    </div>

    <script>
      const html = document.documentElement;
      const sidebar = document.getElementById('sidebar');
      const toggleSidebar = document.getElementById('toggleSidebar');
      const openSidebar = document.getElementById('openSidebar');

      function setCollapsed(collapsed){
        const mainArea = document.querySelector('.flex-1');
        if (collapsed) {
          html.classList.add('sidebar-collapsed');
          mainArea.classList.add('md:pl-[72px]');
          mainArea.classList.remove('md:pl-[260px]');
        } else {
          html.classList.remove('sidebar-collapsed');
          mainArea.classList.remove('md:pl-[72px]');
          mainArea.classList.add('md:pl-[260px]');
        }
      }

      toggleSidebar?.addEventListener('click', ()=>{
        const collapsed = !html.classList.contains('sidebar-collapsed');
        setCollapsed(collapsed);
        localStorage.setItem('admin_sidebar_collapsed', collapsed ? '1':'0');
      });

      openSidebar?.addEventListener('click', ()=>{
        sidebar.classList.toggle('-translate-x-full');
      });

      // init
      if (localStorage.getItem('admin_sidebar_collapsed') === '1') setCollapsed(true);
      // mobile offcanvas
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
    </script>
  </body>
</html>
