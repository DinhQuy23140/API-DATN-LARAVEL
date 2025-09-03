<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tiếp nhận yêu cầu sinh viên</title>
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
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Lecturer</div>
          <div class="text-xs text-slate-500">Bảng điều khiển</div>
        </div>
      </div>
      <nav class="flex-1 overflow-y-auto p-3">
        <a href="overview.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span class="sidebar-label">Tổng quan</span></a>
        <a href="profile.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user"></i><span class="sidebar-label">Hồ sơ</span></a>
        <a href="research.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-flask"></i><span class="sidebar-label">Nghiên cứu</span></a>
        <a href="students.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span></a>
        <div class="sidebar-label text-xs uppercase text-slate-400 px-3 mt-3">Học phần tốt nghiệp</div>
        <a href="thesis-internship.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 pl-10"><i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span></a>
        <a href="thesis-rounds.html" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold pl-10"><i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span></a>
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
            <h1 class="text-lg md:text-xl font-semibold">Tiếp nhận yêu cầu sinh viên</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đồ án tốt nghiệp</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Tiếp nhận yêu cầu</span>
            </nav>
          </div>
        </div>
        <div class="relative">
          <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
            <img class="h-9 w-9 rounded-full object-cover" src="https://i.pravatar.cc/100?img=20" alt="avatar" />
            <div class="hidden sm:block text-left">
              <div class="text-sm font-semibold leading-4">TS. Nguyễn Văn A</div>
              <div class="text-xs text-slate-500">lecturer@uni.edu</div>
            </div>
            <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
          </button>
          <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông tin</a>
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
        <div class="max-w-6xl mx-auto">
          <div class="flex items-center justify-between mb-4">
            <div></div>
            <a href="thesis-round-detail.html" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại đợt</a>
          </div>
    <!-- Stage info banner -->
    <section class="bg-white border rounded-xl p-4 mb-4">
      <div class="flex items-start justify-between">
        <div>
          <div class="text-xs uppercase text-slate-500">Giai đoạn</div>
          <h2 class="text-lg font-semibold">Giai đoạn 01: Tiếp nhận yêu cầu sinh viên</h2>
          <div class="text-sm text-slate-600">Thời gian: 01/08/2025 – 10/08/2025 • Hạn phản hồi chuẩn: 7 ngày</div>
        </div>
        <div class="text-right">
          <span class="px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-700">Đang diễn ra</span>
        </div>
      </div>
    </section>

    <!-- Quick stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
      <div class="bg-blue-50 p-4 rounded-lg flex items-center gap-3">
        <div class="h-10 w-10 rounded-lg bg-blue-600/10 text-blue-600 grid place-items-center"><i class="ph ph-inbox"></i></div>
        <div><div class="text-2xl font-bold text-blue-600">12</div><div class="text-sm text-blue-800">Tổng yêu cầu</div></div>
      </div>
      <div class="bg-yellow-50 p-4 rounded-lg flex items-center gap-3">
        <div class="h-10 w-10 rounded-lg bg-yellow-600/10 text-yellow-600 grid place-items-center"><i class="ph ph-hourglass"></i></div>
        <div><div class="text-2xl font-bold text-yellow-600">6</div><div class="text-sm text-yellow-800">Chờ duyệt</div></div>
      </div>
      <div class="bg-green-50 p-4 rounded-lg flex items-center gap-3">
        <div class="h-10 w-10 rounded-lg bg-green-600/10 text-green-600 grid place-items-center"><i class="ph ph-check-circle"></i></div>
        <div><div class="text-2xl font-bold text-green-600">4</div><div class="text-sm text-green-800">Đã chấp nhận</div></div>
      </div>
      <div class="bg-red-50 p-4 rounded-lg flex items-center gap-3">
        <div class="h-10 w-10 rounded-lg bg-red-600/10 text-red-600 grid place-items-center"><i class="ph ph-x-circle"></i></div>
        <div><div class="text-2xl font-bold text-red-600">2</div><div class="text-sm text-red-800">Từ chối</div></div>
      </div>
    </div>

    <!-- Filters and bulk actions -->
    <div class="bg-white border rounded-xl p-3 mb-3">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
          <div class="relative">
            <i class="ph ph-magnifying-glass absolute left-2 top-2.5 text-slate-400"></i>
            <input class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tên/MSSV/đề tài" />
          </div>
          <select class="px-3 py-2 border border-slate-200 rounded text-sm">
            <option value="">Tất cả trạng thái</option>
            <option>Chờ duyệt</option>
            <option>Đã chấp nhận</option>
            <option>Từ chối</option>
          </select>
          <input type="date" class="px-3 py-2 border border-slate-200 rounded text-sm" />
          <button class="px-2 py-1 text-sm text-slate-600 hover:text-slate-800">Đặt lại</button>
        </div>
        <div class="flex items-center gap-2">
          <button class="px-3 py-1.5 bg-green-600 text-white rounded text-sm disabled:opacity-50"><i class="ph ph-check"></i> Chấp nhận đã chọn</button>
          <button class="px-3 py-1.5 bg-red-600 text-white rounded text-sm disabled:opacity-50"><i class="ph ph-x"></i> Từ chối đã chọn</button>
        </div>
      </div>
    </div>

    <!-- Requests table -->
    <div class="overflow-x-auto bg-white border rounded-xl">
      <table class="w-full text-sm">
        <thead class="bg-slate-50">
          <tr class="text-left text-slate-500 border-b">
            <th class="py-3 px-3"><input type="checkbox" /></th>
            <th class="py-3 px-3">Sinh viên</th>
            <th class="py-3 px-3">MSSV</th>
            <th class="py-3 px-3">Đề tài đề xuất</th>
            <th class="py-3 px-3">Ngày gửi</th>
            <th class="py-3 px-3">Hạn phản hồi</th>
            <th class="py-3 px-3">Trạng thái</th>
            <th class="py-3 px-3">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b hover:bg-slate-50">
            <td class="py-3 px-3"><input type="checkbox" /></td>
            <td class="py-3 px-3">Nguyễn Văn A</td>
            <td class="py-3 px-3">20210001</td>
            <td class="py-3 px-3">Hệ thống quản lý thư viện</td>
            <td class="py-3 px-3">15/07/2025</td>
            <td class="py-3 px-3">25/07/2025</td>
            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded-full text-xs bg-yellow-50 text-yellow-700">Chờ duyệt</span></td>
            <td class="py-3 px-3">
              <div class="flex gap-1">
                <button class="px-2 py-1 bg-green-600 text-white rounded text-xs">Chấp nhận</button>
                <button class="px-2 py-1 bg-red-600 text-white rounded text-xs">Từ chối</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Legend / notes -->
    <div class="mt-4 bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm text-slate-600">
      <div class="mb-1 font-medium text-slate-700">Ghi chú giai đoạn</div>
      <div>• Hạn phản hồi tiêu chuẩn là trong vòng 7 ngày kể từ ngày nhận yêu cầu.</div>
      <div>• Có thể chấp nhận nhiều yêu cầu cùng lúc nếu phù hợp chỉ tiêu.</div>
          </div>
        </main>
      </div>
    </div>

    <script>
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const h=document.querySelector('header'); const m=document.querySelector('main');
        if(c){ html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); }
        else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]'); }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
      const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    </script>
  </div>
</body>
</html>
