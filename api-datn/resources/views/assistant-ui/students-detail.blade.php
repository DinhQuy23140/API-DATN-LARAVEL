<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Danh sách sinh viên - Trợ lý khoa</title>
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

  <div class="flex-1">
        <header class="fixed left-0 md:left-[260px] right-0 top-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-20">
          <div class="flex items-center gap-3 flex-1">
            <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
            <div>
              <h1 class="text-lg md:text-xl font-semibold">Danh sách sinh viên</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp / Danh sách sinh viên</nav>
            </div>
          </div>
          <div class="relative">
            <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
              <img class="h-9 w-9 rounded-full object-cover" src="https://i.pravatar.cc/100?img=6" alt="avatar" />
              <div class="hidden sm:block text-left">
                <div class="text-sm font-semibold leading-4">Assistant</div>
                <div class="text-xs text-slate-500">assistant@uni.edu</div>
              </div>
              <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
            </button>
            <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
              <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông tin</a>
              <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
            </div>
          </div>
        </header>

  <main class="pt-20 px-4 md:px-6 pb-10">
          <div class="max-w-6xl mx-auto space-y-6">
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="relative">
                  <i class="ph ph-calendar text-slate-400 absolute left-2 top-2.5"></i>
                  <select class="pl-8 pr-3 py-2 rounded-lg border border-slate-200 text-sm">
                    <option>Đợt HK1 2025-2026</option>
                    <option>Đợt HK2 2025-2026</option>
                  </select>
                </div>
                <div class="relative">
                  <i class="ph ph-funnel text-slate-400 absolute left-2 top-2.5"></i>
                  <select class="pl-8 pr-3 py-2 rounded-lg border border-slate-200 text-sm">
                    <option>Tất cả trạng thái</option>
                    <option>Đã duyệt</option>
                    <option>Chưa duyệt</option>
                  </select>
                </div>
              </div>
              <div class="relative">
                <input id="searchInput" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo MSSV, họ tên" />
                <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              </div>
            </div>
            <div class="mt-4 overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500">
                    <th data-sort-key="mssv" class="py-3 px-4 border-b cursor-pointer select-none">MSSV <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="name" class="py-3 px-4 border-b cursor-pointer select-none">Họ tên <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="major" class="py-3 px-4 border-b cursor-pointer select-none">Ngành <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="gpa" class="py-3 px-4 border-b cursor-pointer select-none">Điểm TB <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="status" class="py-3 px-4 border-b cursor-pointer select-none">Trạng thái <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th class="py-3 px-4 border-b text-right">Hành động</th>
                  </tr>
                </thead>
                <tbody id="tableBody">
                  <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4">20123456</td>
                    <td class="py-3 px-4"><a class="text-blue-600 hover:underline" href="student-profile.html">Nguyễn Thị Mai</a></td>
                    <td class="py-3 px-4">CNTT</td>
                    <td class="py-3 px-4">3.35</td>
                    <td class="py-3 px-4"><span class="px-2 py-1 rounded-full text-xs bg-emerald-50 text-emerald-700">Đã duyệt</span></td>
                    <td class="py-3 px-4 text-right space-x-2">
                      <a class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" href="student-detail.html"><i class="ph ph-eye"></i></a>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600"><i class="ph ph-pencil"></i></button>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"><i class="ph ph-trash"></i></button>
                    </td>
                  </tr>
                  <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4">20124567</td>
                    <td class="py-3 px-4"><a class="text-blue-600 hover:underline" href="student-profile.html">Lê Văn Cường</a></td>
                    <td class="py-3 px-4">Marketing</td>
                    <td class="py-3 px-4">3.10</td>
                    <td class="py-3 px-4"><span class="px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-700">Chưa duyệt</span></td>
                    <td class="py-3 px-4 text-right space-x-2">
                      <a class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" href="student-detail.html"><i class="ph ph-eye"></i></a>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600"><i class="ph ph-pencil"></i></button>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"><i class="ph ph-trash"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>
        </div>
        </main>
      </div>
    </div>

    <script>
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
  function setCollapsed(c){const h=document.querySelector('header');const m=document.querySelector('main'); if(c){html.classList.add('sidebar-collapsed');h.classList.add('md:left-[72px]');h.classList.remove('md:left-[260px]');m.classList.add('md:pl-[72px]');m.classList.remove('md:pl-[260px]');} else {html.classList.remove('sidebar-collapsed');h.classList.remove('md:left-[72px]');h.classList.add('md:left-[260px]');m.classList.remove('md:pl-[72px]');}}
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed');setCollapsed(c);localStorage.setItem('assistant_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      // simple filter
      document.getElementById('searchInput').addEventListener('input', (e)=>{
        const q=e.target.value.toLowerCase();
        document.querySelectorAll('#tableBody tr').forEach(tr=> tr.style.display = tr.innerText.toLowerCase().includes(q)?'':'none');
      });

      // profile dropdown
      const profileBtn=document.getElementById('profileBtn');
      const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

      // sorting
      const sortState = { key:null, dir:1 };
      function getSortValue(tr, key){
        const tds = tr.querySelectorAll('td');
        if(key==='mssv') return (tds[0]?.innerText||'').trim();
        if(key==='name') return (tds[1]?.innerText||'').trim().toLowerCase();
        if(key==='major') return (tds[2]?.innerText||'').trim().toLowerCase();
        if(key==='gpa') return parseFloat(tds[3]?.innerText||'0') || 0;
        if(key==='status') return (tds[4]?.innerText||'').trim().toLowerCase();
        return '';
      }
      function applySort(th){
        const key = th.dataset.sortKey;
        if(!key) return;
        sortState.dir = sortState.key===key ? -sortState.dir : 1;
        sortState.key = key;
        const rows = Array.from(document.querySelectorAll('#tableBody tr')).filter(r=>r.style.display!=='none');
        rows.sort((a,b)=>{
          const va=getSortValue(a,key), vb=getSortValue(b,key);
          if(typeof va==='number' && typeof vb==='number') return (va-vb)*sortState.dir;
          return (va>vb?1:va<vb?-1:0)*sortState.dir;
        });
        const tbody=document.getElementById('tableBody');
        rows.forEach(r=>tbody.appendChild(r));
        document.querySelectorAll('thead th[data-sort-key] i').forEach(i=>{i.className='ph ph-caret-up-down ml-1 text-slate-400';});
        const icon = th.querySelector('i');
        icon.className = sortState.dir===1 ? 'ph ph-caret-up ml-1 text-slate-600' : 'ph ph-caret-down ml-1 text-slate-600';
      }
      document.querySelectorAll('thead th[data-sort-key]')?.forEach(th=> th.addEventListener('click',()=>applySort(th)));

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
