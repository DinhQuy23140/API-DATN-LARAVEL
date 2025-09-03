<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Trưởng bộ môn - Tổng quan</title>
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
  </style>
</head>
<body class="bg-slate-50 text-slate-800">
  <div class="flex min-h-screen">
    <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-indigo-600 text-white"><i class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Head</div>
          <div class="text-xs text-slate-500">Bảng điều khiển</div>
        </div>
      </div>
      <nav class="flex-1 overflow-y-auto p-3">
        <a href="overview.html" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold"><i class="ph ph-gauge"></i><span class="sidebar-label">Tổng quan</span></a>
        <a href="profile.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user"></i><span class="sidebar-label">Hồ sơ</span></a>
        <a href="research.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-flask"></i><span class="sidebar-label">Nghiên cứu</span></a>
        <a href="students.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span></a>
        <div class="sidebar-label text-xs uppercase text-slate-400 px-3 mt-3">Học phần tốt nghiệp</div>
        <a href="thesis-internship.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 pl-10"><i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span></a>
        <a href="thesis-rounds.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 pl-10"><i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span></a>
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
            <h1 class="text-lg md:text-xl font-semibold">Tổng quan</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <span class="text-slate-500">Trang chủ</span>
            </nav>
          </div>
        </div>
        <div class="relative">
          <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
            <img class="h-9 w-9 rounded-full object-cover" src="https://i.pravatar.cc/100?img=10" alt="avatar" />
            <div class="hidden sm:block text-left">
              <div class="text-sm font-semibold leading-4">ThS. Nguyễn Văn H</div>
              <div class="text-xs text-slate-500">head@uni.edu</div>
            </div>
            <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
          </button>
          <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Hồ sơ</a>
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 space-y-6">
        <div class="max-w-7xl mx-auto space-y-6">
          <section class="bg-white border rounded-xl p-5">
            <h2 class="font-semibold mb-4">Tổng quan nhanh</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div class="p-4 border rounded-lg bg-gradient-to-br from-indigo-50 to-white">
                <div class="text-xs text-slate-500">Giảng viên</div>
                <div class="text-2xl font-semibold mt-1">34</div>
              </div>
              <div class="p-4 border rounded-lg bg-gradient-to-br from-emerald-50 to-white">
                <div class="text-xs text-slate-500">Sinh viên</div>
                <div class="text-2xl font-semibold mt-1">512</div>
              </div>
              <div class="p-4 border rounded-lg bg-gradient-to-br from-amber-50 to-white">
                <div class="text-xs text-slate-500">Đợt đang mở</div>
                <div class="text-2xl font-semibold mt-1">2</div>
              </div>
              <div class="p-4 border rounded-lg bg-gradient-to-br from-rose-50 to-white">
                <div class="text-xs text-slate-500">Hội đồng</div>
                <div class="text-2xl font-semibold mt-1">8</div>
              </div>
            </div>
          </section>

          <section class="bg-white border rounded-xl p-5">
            <h2 class="font-semibold mb-4">Hoạt động gần đây</h2>
            <ul class="space-y-2 text-sm text-slate-600" id="recentActivities"></ul>
          </section>
        </div>
      </main>
    </div>
  </div>

  <script>
    (function(){
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const h=document.querySelector('header'); const m=document.querySelector('main');
        if(c){ html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); }
        else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]'); }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('head_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('head_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
      const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    })();

    // Mock recent activities
    const act=[
      'Giảng viên Nguyễn A cập nhật điểm phản biện','Tạo hội đồng CNTT-09','Phê duyệt đề cương mới','Cập nhật danh sách sinh viên xét tốt nghiệp'
    ];
    document.getElementById('recentActivities').innerHTML = act.map(a=>`<li class='pl-3 relative before:content-["•"] before:absolute before:left-0 before:text-indigo-500'>${a}</li>`).join('');
  </script>
</body>
</html>
