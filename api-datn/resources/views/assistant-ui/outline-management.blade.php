<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý đề cương - Trợ lý khoa</title>
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
      .submenu { display: none; }
      .submenu.hidden { display: none; }
      .submenu:not(.hidden) { display: block; }
    </style>
  </head>
  <body class="bg-slate-50 text-slate-800">
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
          <a href="dashboard.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span class="sidebar-label">Bảng điều khiển</span></a>
          <a href="manage-departments.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-buildings"></i><span class="sidebar-label">Bộ môn</span></a>
          <a href="manage-majors.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
          <a href="manage-staff.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
          <a href="assign-head.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Gán trưởng bộ môn</span></a>
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
              <a href="rounds.html" class="block px-3 py-2 hover:bg-slate-100">Đồ án tốt nghiệp</a>
            </div>
          </div>
        </nav>
        <div class="p-3 border-t border-slate-200">
          <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
        </div>
      </aside>

      <!-- Content -->
      <div class="flex-1 h-screen overflow-hidden flex flex-col">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
          <div class="flex items-center gap-3 flex-1">
            <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
            <div>
              <h1 class="text-lg md:text-xl font-semibold">Quản lý đề cương</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp / Quản lý đề cương</nav>
            </div>
          </div>
        </header>

        <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 space-y-6">
          <div class="max-w-6xl mx-auto space-y-6">
            <section class="bg-white rounded-xl border border-slate-200 p-5">
              <div class="flex items-center justify-between gap-3 flex-wrap">
                <div>
                  <div class="text-sm text-slate-500">Đợt hiện tại: <span class="font-medium text-slate-700">HK1 2025-2026</span></div>
                  <div class="text-sm text-slate-600">Thời gian: 01/08/2025 - 30/10/2025</div>
                </div>
                <div class="flex items-center gap-2">
                  <div class="relative">
                    <input id="searchInput" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo mã/tên sinh viên, đề tài" />
                    <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                  </div>
                  <select id="classFilter" class="px-3 py-2 rounded-lg border border-slate-200 text-sm">
                    <option value="">Lọc theo lớp</option>
                    <option>KTPM2025</option>
                    <option>HTTT2025</option>
                    <option>ANM2025</option>
                  </select>
                  <select id="statusFilter" class="px-3 py-2 rounded-lg border border-slate-200 text-sm">
                    <option value="">Trạng thái</option>
                    <option value="submitted">Đã nộp</option>
                    <option value="pending">Chờ nộp</option>
                  </select>
                </div>
              </div>

              <div class="mt-4 overflow-x-auto">
                <table class="w-full text-sm">
                  <thead>
                    <tr class="text-left text-slate-500">
                      <th class="py-3 px-4 border-b">Mã SV</th>
                      <th class="py-3 px-4 border-b">Tên sinh viên</th>
                      <th class="py-3 px-4 border-b">Lớp</th>
                      <th class="py-3 px-4 border-b">Đề tài</th>
                      <th class="py-3 px-4 border-b">Giảng viên hướng dẫn</th>
                      <th class="py-3 px-4 border-b">Đề cương</th>
                      <th class="py-3 px-4 border-b">Ngày nộp</th>
                      <th class="py-3 px-4 border-b text-right">Hành động</th>
                    </tr>
                  </thead>
                  <tbody id="outlineTableBody"></tbody>
                </table>
              </div>
            </section>
          </div>
        </main>
      </div>
    </div>

    <script>
      // Sidebar behaviors
      const html = document.documentElement;
      const sidebar = document.getElementById('sidebar');
      function setCollapsed(c){
        const mainArea = document.querySelector('.flex-1.h-screen');
        if(c){
          html.classList.add('sidebar-collapsed');
        } else {
          html.classList.remove('sidebar-collapsed');
        }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{
        const c = !html.classList.contains('sidebar-collapsed');
        setCollapsed(c);
        localStorage.setItem('assistant_sidebar',''+(c?1:0));
      });
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      // Graduation submenu toggle
      document.addEventListener('DOMContentLoaded', () => {
        const graduationItem = document.querySelector('.graduation-item');
        const toggleButton = graduationItem?.querySelector('.toggle-button');
        const submenu = graduationItem?.querySelector('.submenu');
        if (toggleButton && submenu) {
          toggleButton.addEventListener('click', (e) => {
            e.preventDefault();
            submenu.classList.toggle('hidden');
          });
        }
      });

      // Auto active nav highlight
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

      // Mock data
      const outlines = [
        { id: 'SV001', name: 'Nguyễn Văn A', class: 'KTPM2025', topic: 'Hệ thống quản lý thư viện', advisor: 'TS. Đặng Hữu T', file: 'de_cuong_SV001.pdf', date: '12/08/2025', status: 'submitted' },
        { id: 'SV002', name: 'Lê Thị B', class: 'KTPM2025', topic: 'Ứng dụng blockchain trong chuỗi cung ứng', advisor: 'ThS. Lưu Lan', file: 'de_cuong_SV002.pdf', date: '11/08/2025', status: 'submitted' },
        { id: 'SV003', name: 'Trần Văn C', class: 'HTTT2025', topic: 'Khai phá dữ liệu khách hàng', advisor: 'TS. Nguyễn Văn A', file: '', date: '', status: 'pending' },
        { id: 'SV004', name: 'Phạm Thị D', class: 'ANM2025', topic: 'Phát hiện xâm nhập mạng dùng ML', advisor: 'TS. Đặng Hữu T', file: 'de_cuong_SV004.pdf', date: '10/08/2025', status: 'submitted' },
      ];

      function renderTable(rows){
        const tbody = document.getElementById('outlineTableBody');
        tbody.innerHTML = rows.map(r=>`
          <tr class="hover:bg-slate-50">
            <td class="py-3 px-4">${r.id}</td>
            <td class="py-3 px-4">${r.name}</td>
            <td class="py-3 px-4">${r.class}</td>
            <td class="py-3 px-4">${r.topic}</td>
            <td class="py-3 px-4">${r.advisor}</td>
            <td class="py-3 px-4">
              ${r.file ? `<a class="text-blue-600 hover:underline" href="#" download>${r.file}</a>` : '<span class="text-slate-400">Chưa nộp</span>'}
            </td>
            <td class="py-3 px-4">${r.date || '-'}</td>
            <td class="py-3 px-4 text-right space-x-2">
              <button class="px-2 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600 text-xs"><i class="ph ph-eye"></i> Xem</button>
              ${r.file ? `<button class="px-2 py-1.5 rounded-lg border hover:bg-slate-50 text-green-600 text-xs"><i class="ph ph-check"></i> Duyệt</button>` : ''}
            </td>
          </tr>
        `).join('');
      }

      function applyFilters(){
        const q = (document.getElementById('searchInput').value || '').toLowerCase();
        const cls = document.getElementById('classFilter').value || '';
        const st = document.getElementById('statusFilter').value || '';
        const filtered = outlines.filter(o =>
          (!cls || o.class === cls)
          && (!st || o.status === st)
          && (o.id.toLowerCase().includes(q)
              || o.name.toLowerCase().includes(q)
              || o.topic.toLowerCase().includes(q))
        );
        renderTable(filtered);
      }

      document.getElementById('searchInput').addEventListener('input', applyFilters);
      document.getElementById('classFilter').addEventListener('change', applyFilters);
      document.getElementById('statusFilter').addEventListener('change', applyFilters);

      // Initial render
      renderTable(outlines);
    </script>
  </body>
</html>
