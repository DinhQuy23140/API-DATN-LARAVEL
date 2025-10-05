<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Trạng thái nộp báo cáo</title>
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
            <h1 class="text-lg md:text-xl font-semibold">Trạng thái nộp báo cáo</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đồ án tốt nghiệp</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Trạng thái nộp báo cáo</span>
            </nav>
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
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
        <div class="max-w-6xl mx-auto">
          <div class="flex items-center justify-between mb-4">
            <a href="#" onclick="window.history.back(); return false;" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại</a>
          </div>

          <!-- Filters -->
          <div class="bg-white border rounded-xl p-3 mb-3">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
              <div class="flex flex-wrap items-center gap-2">
                <div class="relative">
                  <i class="ph ph-magnifying-glass absolute left-2 top-2.5 text-slate-400"></i>
                  <input id="searchBox" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tên/MSSV/đề tài" />
                </div>
                <select id="statusFilter" class="px-3 py-2 border border-slate-200 rounded text-sm">
                  <option value="">Tất cả trạng thái</option>
                  <option value="Chưa nộp">Chưa nộp</option>
                  <option value="Đã nộp">Đã nộp</option>
                  <option value="Đã duyệt">Đã duyệt</option>
                  <option value="Từ chối">Từ chối</option>
                </select>
                <button id="resetBtn" class="px-2 py-1 text-sm text-slate-600 hover:text-slate-800">Đặt lại</button>
              </div>
              <div class="text-xs text-slate-500">
                Hiển thị trạng thái nộp báo cáo cuối đồ án của từng sinh viên
              </div>
            </div>
          </div>

          <!-- Students table -->
          <div class="overflow-x-auto bg-white border rounded-xl">
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-500 border-b">
                  <th class="py-3 px-3">Sinh viên</th>
                  <th class="py-3 px-3">MSSV</th>
                  <th class="py-3 px-3">Đề tài</th>
                  <th class="py-3 px-3">Trạng thái báo cáo</th>
                  <th class="py-3 px-3">Ngày cập nhật</th>
                  <th class="py-3 px-3">Hành động</th>
                </tr>
              </thead>
              <tbody id="tbody">
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    const students = [
      { id:'20210001', name:'Nguyễn Văn A', topic:'Hệ thống quản lý thư viện', status:'Đã duyệt', updated:'10/10/2025' },
      { id:'20210002', name:'Trần Thị B', topic:'Ứng dụng quản lý công việc', status:'Đã nộp', updated:'09/10/2025' },
      { id:'20210003', name:'Lê Văn C', topic:'Hệ thống đặt lịch khám', status:'Chưa nộp', updated:'-' },
      { id:'20210004', name:'Phạm D', topic:'Nền tảng học trực tuyến', status:'Từ chối', updated:'08/10/2025' }
    ];

    const tbody = document.getElementById('tbody');
    const searchEl = document.getElementById('searchBox');
    const statusEl = document.getElementById('statusFilter');

    function statusPill(s){
      switch(s){
        case 'Đã duyệt': return 'bg-emerald-50 text-emerald-700';
        case 'Đã nộp': return 'bg-amber-50 text-amber-700';
        case 'Từ chối': return 'bg-rose-50 text-rose-700';
        default: return 'bg-slate-100 text-slate-700';
      }
    }

    function render(){
      const q = (searchEl?.value||'').toLowerCase();
      const st = statusEl?.value || '';
      const filtered = students.filter(s=>{
        const hit = s.name.toLowerCase().includes(q) || s.id.includes(q) || s.topic.toLowerCase().includes(q);
        const ok = !st || s.status===st;
        return hit && ok;
      });
      tbody.innerHTML = filtered.map(s=>`
        <tr class="border-b hover:bg-slate-50">
          <td class="py-3 px-3"><a class="text-blue-600 hover:underline" href="supervised-student-detail.html?id=${encodeURIComponent(s.id)}&name=${encodeURIComponent(s.name)}">${s.name}</a></td>
          <td class="py-3 px-3">${s.id}</td>
          <td class="py-3 px-3">${s.topic}</td>
          <td class="py-3 px-3"><span class="px-2 py-0.5 rounded-full text-xs ${statusPill(s.status)}">${s.status}</span></td>
          <td class="py-3 px-3">${s.updated}</td>
          <td class="py-3 px-3">
            <div class="flex items-center gap-1">
              <a class="px-2 py-1 border border-slate-200 rounded text-xs hover:bg-slate-50" href="supervised-student-detail.html?id=${encodeURIComponent(s.id)}&name=${encodeURIComponent(s.name)}">Xem chi tiết</a>
              <button class="px-2 py-1 border border-slate-200 rounded text-xs">Nhắc nộp</button>
            </div>
          </td>
        </tr>
      `).join('');
    }

    document.getElementById('resetBtn').addEventListener('click',()=>{ if(searchEl) searchEl.value=''; if(statusEl) statusEl.value=''; render(); });
    searchEl?.addEventListener('input', render);
    statusEl?.addEventListener('change', render);
    render();

    // Sidebar/header interactions
    (function(){
      const html = document.documentElement;
      const sidebar = document.getElementById('sidebar');
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{
        const c = !html.classList.contains('sidebar-collapsed');
        html.classList.toggle('sidebar-collapsed', c);
        localStorage.setItem('lecturer_sidebar', c ? '1' : '0');
      });
      document.getElementById('openSidebar')?.addEventListener('click',()=> sidebar?.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') html.classList.add('sidebar-collapsed');
      sidebar?.classList.add('md:translate-x-0','-translate-x-full','md:static');

      const profileBtn = document.getElementById('profileBtn');
      const profileMenu = document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu?.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    })();
  </script>
</body>
</html>
