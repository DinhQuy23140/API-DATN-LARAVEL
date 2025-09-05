<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Import sinh viên - Trợ lý khoa</title>
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
          <a href="{{ route('web.assistant.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span class="sidebar-label">Bảng điều khiển</span></a>
          <a href="{{ route('web.assistant.manage_departments') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-buildings"></i><span class="sidebar-label">Bộ môn</span></a>
          <a href="{{ route('web.assistant.manage_majors') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
          <a href="{{ route('web.assistant.manage_staffs') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
          <a href="{{ route('web.assistant.assign_head') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Gán trưởng bộ môn</span></a>
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
              <a href="{{ route('web.assistant.rounds') }}" class="block px-3 py-2 hover:bg-slate-100">Đồ án tốt nghiệp</a>
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
              <h1 class="text-lg md:text-xl font-semibold">Import danh sách sinh viên</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp / Import sinh viên</nav>
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
          <div class="max-w-4xl mx-auto space-y-6">
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <h2 class="font-semibold">Chọn file Excel để import</h2>
            <div class="mt-3">
              <input type="file" accept=".xlsx,.xls" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
              <p class="text-xs text-slate-500 mt-2">Mẫu cột: MSSV, Họ tên, Email, Ngành, Điểm TB, Trạng thái.</p>
            </div>
            <div class="mt-4 flex items-center gap-2">
              <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Tải lên</button>
              <a class="px-4 py-2 rounded-lg border hover:bg-slate-50" href="#">Tải mẫu</a>
            </div>
          </section>

          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <h2 class="font-semibold">Thêm thủ công</h2>
            <form class="mt-3 grid sm:grid-cols-2 gap-4" onsubmit="event.preventDefault(); alert('Đã thêm!'); this.reset();">
              <div>
                <label class="text-sm font-medium">MSSV</label>
                <input required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" placeholder="VD: 20123456" />
              </div>
              <div>
                <label class="text-sm font-medium">Họ tên</label>
                <input required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" />
              </div>
              <div>
                <label class="text-sm font-medium">Email</label>
                <input type="email" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" />
              </div>
              <div>
                <label class="text-sm font-medium">Ngành</label>
                <select class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200"><option>CNTT</option><option>Marketing</option></select>
              </div>
              <div>
                <label class="text-sm font-medium">Điểm TB</label>
                <input type="number" step="0.01" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" />
              </div>
              <div class="sm:col-span-2">
                <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Thêm</button>
              </div>
            </form>
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
