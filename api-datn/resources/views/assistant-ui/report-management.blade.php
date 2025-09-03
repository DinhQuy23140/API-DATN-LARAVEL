<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý báo cáo cuối kỳ - Trợ lý khoa</title>
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

      <div class="flex-1 h-screen overflow-hidden flex flex-col">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
          <div class="flex items-center gap-3 flex-1">
            <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
            <div>
              <h1 class="text-lg md:text-xl font-semibold">Quản lý báo cáo cuối kỳ</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Đợt đồ án / Báo cáo cuối kỳ</nav>
            </div>
          </div>
        </header>

        <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
          <div class="max-w-6xl mx-auto">
            <div class="flex flex-wrap items-center gap-2 mb-4">
              <div class="relative w-full md:w-80">
                <input id="searchInput" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tên, mã SV, đề tài, GVHD" />
                <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              </div>
              <select id="classFilter" class="px-3 py-2 rounded-lg border border-slate-200 text-sm">
                <option value="">Tất cả lớp</option>
                <option>KTPM2025</option>
                <option>HTTT2025</option>
              </select>
              <select id="statusFilter" class="px-3 py-2 rounded-lg border border-slate-200 text-sm">
                <option value="">Tất cả trạng thái</option>
                <option>Chờ duyệt</option>
                <option>Đã duyệt</option>
                <option>Bị từ chối</option>
              </select>
              <div class="ml-auto flex items-center gap-2">
                <button id="exportBtn" class="px-3 py-2 rounded-lg border text-sm hover:bg-slate-50"><i class="ph ph-file-text"></i> Xuất danh sách</button>
              </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500 border-b">
                    <th class="py-3 px-4">Mã SV</th>
                    <th class="py-3 px-4">Tên</th>
                    <th class="py-3 px-4">Lớp</th>
                    <th class="py-3 px-4">Đề tài</th>
                    <th class="py-3 px-4">GVHD</th>
                    <th class="py-3 px-4">Báo cáo</th>
                    <th class="py-3 px-4">Nộp lúc</th>
                    <th class="py-3 px-4">Trạng thái</th>
                    <th class="py-3 px-4 text-right">Hành động</th>
                  </tr>
                </thead>
                <tbody id="reportBody"></tbody>
              </table>
            </div>
          </div>
        </main>
      </div>
    </div>

    <script>
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const mainArea = document.querySelector('.flex-1');
        if(c){ html.classList.add('sidebar-collapsed'); mainArea.classList.add('md:pl-[72px]'); mainArea.classList.remove('md:pl-[260px]'); }
        else { html.classList.remove('sidebar-collapsed'); mainArea.classList.remove('md:pl-[72px]'); mainArea.classList.add('md:pl-[260px]'); }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed');setCollapsed(c);localStorage.setItem('assistant_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      // submenu
      document.addEventListener('DOMContentLoaded', () => {
        const graduationItem = document.querySelector('.graduation-item');
        const toggleButton = graduationItem?.querySelector('.toggle-button');
        const submenu = graduationItem?.querySelector('.submenu');
        if (toggleButton && submenu) {
          toggleButton.addEventListener('click', (e) => { e.preventDefault(); submenu.classList.toggle('hidden'); });
        }
      });

      // mock data
      const reports = [
        { sid:'20123456', name:'Nguyễn Văn A', class:'KTPM2025', topic:'Hệ thống quản lý thư viện', advisor:'TS. Đặng Hữu T', file:'bao_cao_cuoi_ky_A.pdf', submittedAt:'05/10/2025 21:40', status:'Chờ duyệt' },
        { sid:'20124567', name:'Trần Thị B', class:'KTPM2025', topic:'Ứng dụng mobile bán hàng', advisor:'ThS. Lưu Lan', file:'bao_cao_cuoi_ky_B.pdf', submittedAt:'05/10/2025 19:10', status:'Đã duyệt' },
        { sid:'20125678', name:'Lê Văn C', class:'HTTT2025', topic:'Website thương mại điện tử', advisor:'TS. Nguyễn Văn A', file:'bao_cao_cuoi_ky_C.pdf', submittedAt:'06/10/2025 08:05', status:'Bị từ chối' },
        { sid:'20126789', name:'Phạm D', class:'HTTT2025', topic:'Hệ thống CRM', advisor:'TS. Trần Minh', file:'bao_cao_cuoi_ky_D.pdf', submittedAt:'06/10/2025 10:30', status:'Chờ duyệt' }
      ];

      const bodyEl = document.getElementById('reportBody');
      const qEl = document.getElementById('searchInput');
      const classEl = document.getElementById('classFilter');
      const statusEl = document.getElementById('statusFilter');

      function statusBadge(s){
        if(s==='Đã duyệt') return 'bg-green-50 text-green-700';
        if(s==='Chờ duyệt') return 'bg-amber-50 text-amber-700';
        if(s==='Bị từ chối') return 'bg-rose-50 text-rose-700';
        return 'bg-slate-100 text-slate-700';
      }

      function applyFilters(){
        const q = (qEl.value||'').toLowerCase();
        const c = classEl.value||'';
        const st = statusEl.value||'';
        return reports.filter(r=>{
          const text = [r.sid, r.name, r.topic, r.advisor].join(' ').toLowerCase();
          const okQ = !q || text.includes(q);
          const okC = !c || r.class===c;
          const okS = !st || r.status===st;
          return okQ && okC && okS;
        });
      }

      function render(){
        const rows = applyFilters();
        bodyEl.innerHTML = rows.map(r=>`
          <tr class="border-b hover:bg-slate-50">
            <td class="py-3 px-4">${r.sid}</td>
            <td class="py-3 px-4">${r.name}</td>
            <td class="py-3 px-4">${r.class}</td>
            <td class="py-3 px-4">${r.topic}</td>
            <td class="py-3 px-4">${r.advisor}</td>
            <td class="py-3 px-4"><a class="text-blue-600 hover:underline" href="#"><i class="ph ph-download"></i> ${r.file}</a></td>
            <td class="py-3 px-4">${r.submittedAt}</td>
            <td class="py-3 px-4"><span class="px-2 py-1 rounded-full text-xs ${statusBadge(r.status)}">${r.status}</span></td>
            <td class="py-3 px-4 text-right space-x-2">
              <button class="px-2 py-1 rounded-lg border hover:bg-slate-50 text-slate-600" data-action="view" data-id="${r.sid}"><i class="ph ph-eye"></i></button>
              <button class="px-2 py-1 rounded-lg border hover:bg-slate-50 text-emerald-600" data-action="approve" data-id="${r.sid}"><i class="ph ph-check"></i></button>
              <button class="px-2 py-1 rounded-lg border hover:bg-slate-50 text-rose-600" data-action="reject" data-id="${r.sid}"><i class="ph ph-x"></i></button>
            </td>
          </tr>
        `).join('');
      }

      function handleAction(e){
        const btn = e.target.closest('button[data-action]');
        if(!btn) return;
        const act = btn.getAttribute('data-action');
        const sid = btn.getAttribute('data-id');
        const item = reports.find(r=>r.sid===sid);
        if(!item) return;
        if(act==='approve'){ item.status='Đã duyệt'; }
        if(act==='reject'){ item.status='Bị từ chối'; }
        if(act==='view'){ alert(`Xem báo cáo của ${item.name}: ${item.file}`); }
        render();
      }

      qEl.addEventListener('input', render);
      classEl.addEventListener('change', render);
      statusEl.addEventListener('change', render);
      document.addEventListener('click', handleAction);

      // init
      render();
    </script>
  </body>
</html>
