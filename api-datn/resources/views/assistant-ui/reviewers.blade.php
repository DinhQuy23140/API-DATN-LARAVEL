<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Giáo viên phản biện - Trợ lý khoa</title>
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
      .submenu { display:none; }
      .submenu.hidden { display:none; }
      .submenu:not(.hidden) { display:block; }
    </style>
  </head>
  <body class="bg-slate-50 text-slate-800">
    @php
      $user = auth()->user();
      $userName = $user->fullname ?? $user->name ?? 'Giảng viên';
      $email = $user->email ?? '';
      // Tùy mô hình dữ liệu, thay các field bên dưới cho khớp
      $dept = $user->department_name ?? optional($user->teacher)->department ?? '';
      $faculty = $user->faculty_name ?? optional($user->teacher)->faculty ?? '';
      $subtitle = trim(($dept ? "Bộ môn $dept" : '') . (($dept && $faculty) ? ' • ' : '') . ($faculty ? "Khoa $faculty" : ''));
      $degree = $user->teacher->degree ?? '';
      $avatarUrl = $user->avatar_url
        ?? $user->profile_photo_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    @endphp
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
              <h1 class="text-lg md:text-xl font-semibold">Giáo viên phản biện</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp / GV phản biện</nav>
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

        <main class="pt-20 px-4 md:px-6 pb-10">
          <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-[340px,1fr] gap-6">
            <!-- Left: reviewer list -->
            <section class="bg-white rounded-xl border border-slate-200 p-4">
              <div class="flex items-center justify-between gap-2">
                <div class="relative flex-1">
                  <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
                  <input id="searchLecturer" class="pl-9 pr-3 py-2 border rounded-lg text-sm w-full" placeholder="Tìm theo mã, tên, email..." />
                </div>
                <button id="btnAddLecturer" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm"><i class="ph ph-user-plus"></i> Thêm</button>
                <button id="btnImportLecturers" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm"><i class="ph ph-upload-simple"></i> Import</button>
              </div>
              <div class="mt-3 text-xs text-slate-500">Tổng: <span id="lecturerCount">0</span> GV phản biện</div>
              <div id="lecturerList" class="mt-3 divide-y"></div>
            </section>

            <!-- Right: detail -->
            <section class="bg-white rounded-xl border border-slate-200 p-5">
              <div id="emptyState" class="text-center py-14 text-slate-500">
                <div class="mx-auto h-14 w-14 rounded-full bg-slate-100 grid place-items-center mb-3"><i class="ph ph-chalkboard-teacher text-xl"></i></div>
                <div class="font-medium">Chọn giáo viên phản biện để xem chi tiết</div>
                <div class="text-sm">Hoặc thêm mới/import danh sách bên trái.</div>
              </div>

              <div id="detailPanel" class="hidden space-y-5">
                <!-- Info -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                  <div class="flex items-center gap-4">
                    <div id="detailAvatar" class="h-14 w-14 rounded-lg bg-blue-600 text-white grid place-items-center font-semibold">GV</div>
                    <div>
                      <div id="detailName" class="text-lg font-semibold">---</div>
                      <div class="text-sm text-slate-600"><span id="detailId">---</span> • <span id="detailDept">---</span></div>
                      <div class="text-xs text-slate-500"><i class="ph ph-envelope-open"></i> <span id="detailEmail">---</span> • <i class="ph ph-phone"></i> <span id="detailPhone">---</span></div>
                    </div>
                  </div>
                  <div class="flex items-center gap-2">
                    <button id="btnAssignStudents" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm"><i class="ph ph-user-plus"></i> Gán sinh viên</button>
                    <button id="btnAssignSingle" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm"><i class="ph ph-user-plus"></i> Thêm MSSV</button>
                    <button id="btnAssignImport" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm"><i class="ph ph-upload-simple"></i> Import danh sách</button>
                    <button id="btnEditLecturer" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm"><i class="ph ph-pencil"></i> Sửa</button>
                    <button id="btnDeleteLecturer" class="px-3 py-2 rounded-lg border hover:bg-rose-50 text-rose-600 text-sm"><i class="ph ph-trash"></i> Xóa</button>
                  </div>
                </div>

                <!-- Research interests -->
                <div>
                  <div class="text-sm font-semibold mb-2">Hướng nghiên cứu</div>
                  <div id="detailResearch" class="flex flex-wrap gap-2"></div>
                </div>

                <!-- Students table -->
                <div>
                  <div class="flex items-center justify-between gap-2 flex-wrap">
                    <div class="text-sm font-semibold">Danh sách sinh viên phản biện (<span id="detailStudentCount">0</span>)</div>
                    <div class="relative">
                      <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
                      <input id="searchAssigned" class="pl-9 pr-3 py-2 border rounded-lg text-sm w-56" placeholder="Tìm theo MSSV, tên, lớp..." />
                    </div>
                  </div>
                  <div class="mt-3 overflow-x-auto">
                    <table class="min-w-full text-sm">
                      <thead>
                        <tr class="text-left text-slate-600 border-b">
                          <th class="py-2 pr-4">Mã SV</th>
                          <th class="py-2 pr-4">Họ tên</th>
                          <th class="py-2 pr-4">Lớp</th>
                          <th class="py-2 pr-4">Đề tài</th>
                          <th class="py-2 pr-4">GVHD</th>
                          <th class="py-2 pr-4 text-right">Hành động</th>
                        </tr>
                      </thead>
                      <tbody id="assignedBody"></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </main>
      </div>
    </div>

    <!-- Add Lecturer Modal -->
    <div id="modalAddLecturer" class="hidden fixed inset-0 z-50 bg-black/40 p-4">
      <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b">
          <h4 class="font-semibold">Thêm GV phản biện</h4>
          <button class="p-2 rounded-lg hover:bg-slate-100" data-close-modal="modalAddLecturer"><i class="ph ph-x"></i></button>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="text-sm text-slate-600">Mã GV</label>
            <input id="lec_id" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder="GV001" />
          </div>
          <div>
            <label class="text-sm text-slate-600">Họ tên</label>
            <input id="lec_name" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder="TS. Nguyễn Văn A" />
          </div>
          <div>
            <label class="text-sm text-slate-600">Email</label>
            <input id="lec_email" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder="a@uni.edu" />
          </div>
          <div>
            <label class="text-sm text-slate-600">SĐT</label>
            <input id="lec_phone" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder="0901xxxxxx" />
          </div>
          <div>
            <label class="text-sm text-slate-600">Bộ môn</label>
            <input id="lec_dept" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder="CNTT" />
          </div>
          <div class="sm:col-span-2">
            <label class="text-sm text-slate-600">Hướng nghiên cứu (phân tách bằng dấu phẩy)</label>
            <input id="lec_research" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder="AI, Hệ thống thông tin, Mạng" />
          </div>
        </div>
        <div class="px-5 py-3 border-t flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm" data-close-modal="modalAddLecturer">Hủy</button>
          <button id="confirmAddLecturer" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">Thêm</button>
        </div>
      </div>
    </div>

    <!-- Import Lecturers Modal -->
    <div id="modalImportLecturers" class="hidden fixed inset-0 z-50 bg-black/40 p-4">
      <div class="max-w-xl mx-auto bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b">
          <h4 class="font-semibold">Import danh sách GV phản biện (CSV)</h4>
          <button class="p-2 rounded-lg hover:bg-slate-100" data-close-modal="modalImportLecturers"><i class="ph ph-x"></i></button>
        </div>
        <div class="p-5 space-y-3">
          <input id="lecCsvFile" type="file" accept=".csv" class="w-full text-sm" />
          <p class="text-xs text-slate-500">Định dạng: id,name,email,phone,dept,research (research có thể là danh sách phân tách bằng dấu chấm phẩy).</p>
          <details class="text-xs text-slate-500">
            <summary class="cursor-pointer select-none">Ví dụ</summary>
            <pre class="bg-slate-50 p-3 rounded border overflow-auto">id,name,email,phone,dept,research\nGV001,TS. Nguyen Van A,a@uni.edu,0901000001,CNTT,AI;He thong;Mang\nGV002,ThS. Le Thi B,b@uni.edu,0901000002,CNTT,Thi giac may tinh;DL lon</pre>
          </details>
        </div>
        <div class="px-5 py-3 border-t flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm" data-close-modal="modalImportLecturers">Hủy</button>
          <button id="confirmImportLecturers" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">Import</button>
        </div>
      </div>
    </div>

    <!-- Assign Students Modal -->
    <div id="modalAssign" class="hidden fixed inset-0 z-50 bg-black/40 p-4">
      <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between px-5 py-3 border-b">
          <h4 class="font-semibold">Gán sinh viên cho GV phản biện</h4>
          <button class="p-2 rounded-lg hover:bg-slate-100" data-close-modal="modalAssign"><i class="ph ph-x"></i></button>
        </div>
        <div class="p-5 overflow-auto">
          <div class="flex items-center justify-between gap-2 flex-wrap">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
              <input id="searchAllStudents" class="pl-9 pr-3 py-2 border rounded-lg text-sm w-64" placeholder="Tìm theo MSSV, tên, lớp..." />
            </div>
            <div class="text-xs text-slate-500">Đã chọn: <span id="assignSelectedCount">0</span></div>
          </div>
          <div class="mt-3 overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead>
                <tr class="text-left text-slate-600 border-b">
                  <th class="py-2 pr-4"><input id="assignSelectAll" type="checkbox" /></th>
                  <th class="py-2 pr-4">Mã SV</th>
                  <th class="py-2 pr-4">Họ tên</th>
                  <th class="py-2 pr-4">Lớp</th>
                  <th class="py-2 pr-4">Đề tài</th>
                  <th class="py-2 pr-4">GVHD</th>
                </tr>
              </thead>
              <tbody id="assignList"></tbody>
            </table>
          </div>
        </div>
        <div class="px-5 py-3 border-t flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm" data-close-modal="modalAssign">Hủy</button>
          <button id="confirmAssign" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">Gán</button>
        </div>
      </div>
    </div>

    <!-- Assign single MSSV Modal -->
    <div id="modalAssignSingle" class="hidden fixed inset-0 z-50 bg-black/40 p-4">
      <div class="max-w-md mx-auto bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b">
          <h4 class="font-semibold">Thêm MSSV cho GV phản biện</h4>
          <button class="p-2 rounded-lg hover:bg-slate-100" data-close-modal="modalAssignSingle"><i class="ph ph-x"></i></button>
        </div>
        <div class="p-5 space-y-2">
          <label class="text-sm text-slate-600">Mã số sinh viên</label>
          <input id="assignSingleMssv" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="2212345" />
          <p class="text-xs text-slate-500">MSSV phải thuộc danh sách sinh viên hợp lệ trong hệ thống.</p>
        </div>
        <div class="px-5 py-3 border-t flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm" data-close-modal="modalAssignSingle">Hủy</button>
          <button id="confirmAssignSingle" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">Thêm</button>
        </div>
      </div>
    </div>

    <!-- Import assignments Modal -->
    <div id="modalAssignImport" class="hidden fixed inset-0 z-50 bg-black/40 p-4">
      <div class="max-w-xl mx-auto bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b">
          <h4 class="font-semibold">Import danh sách MSSV gán cho GV</h4>
          <button class="p-2 rounded-lg hover:bg-slate-100" data-close-modal="modalAssignImport"><i class="ph ph-x"></i></button>
        </div>
        <div class="p-5 space-y-4">
          <div>
            <label class="text-sm text-slate-600">Chọn file CSV (tùy chọn)</label>
            <input id="assignCsvFile" type="file" accept=".csv" class="mt-1 w-full text-sm" />
            <p class="mt-1 text-xs text-slate-500">CSV có thể có cột <code>mssv</code>; nếu không có header, sẽ lấy cột đầu tiên làm MSSV.</p>
          </div>
          <div>
            <label class="text-sm text-slate-600">Hoặc dán danh sách MSSV (mỗi dòng một MSSV hoặc phân tách bằng dấu phẩy/khoảng trắng)</label>
            <textarea id="assignCodes" rows="6" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder="2212345\n2212346\n2212347"></textarea>
          </div>
          <details class="text-xs text-slate-500">
            <summary class="cursor-pointer select-none">Ví dụ CSV</summary>
            <pre class="bg-slate-50 p-3 rounded border overflow-auto">mssv\n2212345\n2212346\n2212347</pre>
          </details>
        </div>
        <div class="px-5 py-3 border-t flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm" data-close-modal="modalAssignImport">Hủy</button>
          <button id="confirmAssignImport" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">Import</button>
        </div>
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
          toggleButton.addEventListener('click', (e) => { e.preventDefault(); submenu.classList.toggle('hidden'); });
        }
      });

      // Mock data
      (function(){
        let lecturers = [
          { id:'GV001', name:'TS. Nguyễn Văn A', email:'a@uni.edu', phone:'0901000001', dept:'CNTT', research:['AI','Hệ thống','Mạng'], students:['2212345','2212347'] },
          { id:'GV002', name:'ThS. Lê Thị B', email:'b@uni.edu', phone:'0901000002', dept:'CNTT', research:['Thị giác máy tính','Dữ liệu lớn'], students:[] },
          { id:'GV003', name:'TS. Trần Văn C', email:'c@uni.edu', phone:'0901000003', dept:'CNTT', research:['Web','Cloud'], students:['2212346'] },
        ];
        const allStudents = [
          { mssv:'2212345', name:'Nguyễn Văn A', lop:'DHKTPM16A', topic:'Hệ thống quản lí đồ án', gvhd:'TS. Trần B' },
          { mssv:'2212346', name:'Lê Thị B', lop:'DHKHMT16B', topic:'AI hỗ trợ giảng dạy', gvhd:'ThS. Nguyễn C' },
          { mssv:'2212347', name:'Trần Minh C', lop:'DHKTPM16C', topic:'Website thương mại', gvhd:'TS. Lê D' },
          { mssv:'2212348', name:'Phạm Gia D', lop:'DHKTPM16A', topic:'Ứng dụng di động', gvhd:'TS. Phạm E' },
          { mssv:'2212349', name:'Đỗ Khánh E', lop:'DHKHMT16B', topic:'Mạng xã hội', gvhd:'ThS. Lý F' },
        ];

        // State
        let selectedId = null;
        const searchLecturer = document.getElementById('searchLecturer');
        const lecturerList = document.getElementById('lecturerList');
        const lecturerCount = document.getElementById('lecturerCount');

        // Detail refs
        const emptyState = document.getElementById('emptyState');
        const detailPanel = document.getElementById('detailPanel');
        const detailAvatar = document.getElementById('detailAvatar');
        const detailName = document.getElementById('detailName');
        const detailId = document.getElementById('detailId');
        const detailDept = document.getElementById('detailDept');
        const detailEmail = document.getElementById('detailEmail');
        const detailPhone = document.getElementById('detailPhone');
        const detailResearch = document.getElementById('detailResearch');
        const detailStudentCount = document.getElementById('detailStudentCount');
        const searchAssigned = document.getElementById('searchAssigned');
        const assignedBody = document.getElementById('assignedBody');

        function renderLecturerList(){
          const q = (searchLecturer.value||'').toLowerCase().trim();
          const items = lecturers.filter(l=>{
            if(!q) return true; return [l.id,l.name,l.email].some(x=> (x||'').toLowerCase().includes(q));
          }).map(l=>{
            const count = l.students.length;
            const initials = (l.name.match(/\b\p{L}/gu)||[]).slice(0,2).join('').toUpperCase();
            return `<button class="w-full text-left px-3 py-3 hover:bg-slate-50 ${l.id===selectedId?'bg-slate-100':''}" data-lecturer="${l.id}">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg bg-blue-600 text-white grid place-items-center text-sm font-semibold">${initials||'GV'}</div>
                <div class="flex-1">
                  <div class="font-medium leading-5">${l.name}</div>
                  <div class="text-xs text-slate-500">${l.id} • ${l.dept}</div>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700">${count} SV</span>
              </div>
            </button>`;
          });
          lecturerList.innerHTML = items.join('');
          lecturerCount.textContent = lecturers.length;
          lecturerList.querySelectorAll('[data-lecturer]').forEach(btn=>{
            btn.addEventListener('click', ()=>{ selectedId = btn.getAttribute('data-lecturer'); renderDetail(); renderLecturerList(); })
          })
        }

        function renderDetail(){
          const lec = lecturers.find(x=>x.id===selectedId);
          if(!lec){ emptyState.classList.remove('hidden'); detailPanel.classList.add('hidden'); return; }
          emptyState.classList.add('hidden');
          detailPanel.classList.remove('hidden');
          // avatar initials
          const initials = (lec.name.match(/\b\p{L}/gu)||[]).slice(0,2).join('').toUpperCase();
          detailAvatar.textContent = initials||'GV';
          detailName.textContent = lec.name;
          detailId.textContent = lec.id;
          detailDept.textContent = lec.dept;
          detailEmail.textContent = lec.email;
          detailPhone.textContent = lec.phone;
          detailResearch.innerHTML = (lec.research||[]).map(t=>`<span class="px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-700">${t}</span>`).join('') || '<span class="text-xs text-slate-500">Chưa cập nhật</span>';
          renderAssigned();
        }

        function renderAssigned(){
          const lec = lecturers.find(x=>x.id===selectedId); if(!lec) return;
          const q = (searchAssigned.value||'').toLowerCase().trim();
          const rows = lec.students
            .map(id => allStudents.find(s=>s.mssv===id))
            .filter(Boolean)
            .filter(s=> !q || [s.mssv,s.name,s.lop,s.topic,s.gvhd].some(x=> (x||'').toLowerCase().includes(q)))
            .map(s=>`<tr class="border-b hover:bg-slate-50">
                <td class="py-2 pr-4 font-mono">${s.mssv}</td>
                <td class="py-2 pr-4">${s.name}</td>
                <td class="py-2 pr-4">${s.lop}</td>
                <td class="py-2 pr-4">${s.topic}</td>
                <td class="py-2 pr-4">${s.gvhd}</td>
                <td class="py-2 pr-0 text-right"><button class="px-2 py-1 rounded border text-xs hover:bg-slate-50" data-remove-stu="${s.mssv}"><i class="ph ph-user-minus"></i> Gỡ</button></td>
              </tr>`);
          assignedBody.innerHTML = rows.join('');
          detailStudentCount.textContent = lec.students.length;
          assignedBody.querySelectorAll('[data-remove-stu]').forEach(btn=>{
            btn.addEventListener('click', ()=>{
              lec.students = lec.students.filter(x=>x!==btn.getAttribute('data-remove-stu'));
              renderAssigned(); renderLecturerList();
            })
          })
        }

        // Search handlers
        searchLecturer.addEventListener('input', renderLecturerList);
        searchAssigned.addEventListener('input', renderAssigned);

        // Modals helpers
        function openModal(id){ document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id){ document.getElementById(id).classList.add('hidden'); }
        document.querySelectorAll('[data-close-modal]').forEach(btn=>{ btn.addEventListener('click', ()=> closeModal(btn.getAttribute('data-close-modal'))); })

        // Add lecturer
        document.getElementById('btnAddLecturer').addEventListener('click', ()=> openModal('modalAddLecturer'));
        document.getElementById('confirmAddLecturer').addEventListener('click', ()=>{
          const l = {
            id: document.getElementById('lec_id').value.trim(),
            name: document.getElementById('lec_name').value.trim(),
            email: document.getElementById('lec_email').value.trim(),
            phone: document.getElementById('lec_phone').value.trim(),
            dept: document.getElementById('lec_dept').value.trim(),
            research: document.getElementById('lec_research').value.split(',').map(x=>x.trim()).filter(Boolean),
            students: []
          };
          if(!l.id || !l.name){ alert('Vui lòng nhập tối thiểu Mã GV và Họ tên'); return; }
          if(lecturers.some(x=>x.id===l.id)){ alert('Mã GV đã tồn tại'); return; }
          lecturers.push(l);
          ['lec_id','lec_name','lec_email','lec_phone','lec_dept','lec_research'].forEach(id=> document.getElementById(id).value='');
          closeModal('modalAddLecturer');
          renderLecturerList();
        })

        // Import lecturers
        document.getElementById('btnImportLecturers').addEventListener('click', ()=> openModal('modalImportLecturers'));
        function parseLecCSV(text){
          const lines = text.split(/\r?\n/).filter(l=>l.trim().length>0);
          if(lines.length===0) return [];
          const header = lines[0].split(',').map(x=>x.trim());
          const idx = {
            id: header.indexOf('id'),
            name: header.indexOf('name'),
            email: header.indexOf('email'),
            phone: header.indexOf('phone'),
            dept: header.indexOf('dept'),
            research: header.indexOf('research'),
          };
          const out = [];
          for(let i=1;i<lines.length;i++){
            const cols = lines[i].split(',');
            if(cols.length<header.length) continue;
            const l = {
              id: (cols[idx.id]||'').trim(),
              name: (cols[idx.name]||'').trim(),
              email: (cols[idx.email]||'').trim(),
              phone: (cols[idx.phone]||'').trim(),
              dept: (cols[idx.dept]||'').trim(),
              research: ((cols[idx.research]||'').split(/;|,/).map(x=>x.trim()).filter(Boolean)),
              students: []
            };
            if(l.id && l.name) out.push(l);
          }
          return out;
        }
        document.getElementById('confirmImportLecturers').addEventListener('click', ()=>{
          const file = document.getElementById('lecCsvFile').files[0];
          if(!file){ alert('Chọn file CSV trước.'); return; }
          const reader = new FileReader();
          reader.onload = ()=>{
            try{
              const items = parseLecCSV(String(reader.result||''));
              let added=0, skipped=0;
              items.forEach(l=>{ if(lecturers.some(x=>x.id===l.id)) skipped++; else { lecturers.push(l); added++; } });
              renderLecturerList();
              closeModal('modalImportLecturers');
              alert(`Đã import ${added} GV, bỏ qua ${skipped} do trùng mã.`);
            }catch(err){ alert('Không thể đọc file CSV'); }
          }
          reader.readAsText(file);
        })

        // Edit/Delete lecturer (simple demo)
        document.getElementById('btnEditLecturer').addEventListener('click', ()=>{
          const l = lecturers.find(x=>x.id===selectedId); if(!l) return;
          const name = prompt('Cập nhật Họ tên', l.name); if(name===null) return; l.name = name.trim()||l.name; renderDetail(); renderLecturerList();
        })
        document.getElementById('btnDeleteLecturer').addEventListener('click', ()=>{
          const l = lecturers.find(x=>x.id===selectedId); if(!l) return;
          if(!confirm('Xóa GV phản biện này?')) return;
          lecturers = lecturers.filter(x=>x.id!==l.id); selectedId=null; renderLecturerList(); renderDetail();
        })

        // Assign students
        const modalAssign = document.getElementById('modalAssign');
        const searchAllStudents = document.getElementById('searchAllStudents');
        const assignList = document.getElementById('assignList');
        const assignSelectedCount = document.getElementById('assignSelectedCount');
        const assignSelectAll = document.getElementById('assignSelectAll');

        function renderAssignList(){
          const lec = lecturers.find(x=>x.id===selectedId); if(!lec) return;
          const q = (searchAllStudents.value||'').toLowerCase().trim();
          const rows = allStudents
            .filter(s=> !q || [s.mssv,s.name,s.lop,s.topic,s.gvhd].some(x=> (x||'').toLowerCase().includes(q)))
            .map(s=>{
              const checked = lec.students.includes(s.mssv) ? 'checked' : '';
              return `<tr class="border-b">
                <td class="py-2 pr-4"><input type="checkbox" data-assign-mssv="${s.mssv}" ${checked} /></td>
                <td class="py-2 pr-4 font-mono">${s.mssv}</td>
                <td class="py-2 pr-4">${s.name}</td>
                <td class="py-2 pr-4">${s.lop}</td>
                <td class="py-2 pr-4">${s.topic}</td>
                <td class="py-2 pr-4">${s.gvhd}</td>
              </tr>`
            });
          assignList.innerHTML = rows.join('');
          updateAssignSelectedCount();
          assignSelectAll.checked = false;
        }
        function updateAssignSelectedCount(){
          const lec = lecturers.find(x=>x.id===selectedId); if(!lec) return;
          const selected = modalAssign.querySelectorAll('input[data-assign-mssv]:checked').length;
          assignSelectedCount.textContent = selected;
        }
        modalAssign.addEventListener('change', (e)=>{
          if(e.target && e.target.matches('input[data-assign-mssv]')) updateAssignSelectedCount();
        })
        assignSelectAll.addEventListener('change', ()=>{
          const checks = modalAssign.querySelectorAll('input[data-assign-mssv]');
          checks.forEach(ch=> ch.checked = assignSelectAll.checked);
          updateAssignSelectedCount();
        })
        document.getElementById('btnAssignStudents').addEventListener('click', ()=>{ renderAssignList(); openModal('modalAssign'); })
        document.getElementById('btnAssignSingle').addEventListener('click', ()=>{ document.getElementById('assignSingleMssv').value=''; openModal('modalAssignSingle'); })
        searchAllStudents.addEventListener('input', renderAssignList);
        document.getElementById('confirmAssign').addEventListener('click', ()=>{
          const lec = lecturers.find(x=>x.id===selectedId); if(!lec) return;
          const selected = Array.from(modalAssign.querySelectorAll('input[data-assign-mssv]:checked')).map(ch=> ch.getAttribute('data-assign-mssv'));
          lec.students = selected; // replace assignments with selected set
          closeModal('modalAssign');
          renderAssigned(); renderLecturerList();
        })

        // Assign single code
        document.getElementById('confirmAssignSingle').addEventListener('click', ()=>{
          const lec = lecturers.find(x=>x.id===selectedId); if(!lec){ alert('Chọn GV trước.'); return; }
          const code = (document.getElementById('assignSingleMssv').value||'').trim();
          if(!code){ alert('Nhập MSSV'); return; }
          if(!allStudents.some(s=>s.mssv===code)){ alert('MSSV không tồn tại.'); return; }
          if(lec.students.includes(code)){ alert('MSSV đã được gán cho GV này.'); return; }
          lec.students.push(code);
          closeModal('modalAssignSingle');
          renderAssigned(); renderLecturerList();
        })

        // Import assignments
        document.getElementById('btnAssignImport').addEventListener('click', ()=> openModal('modalAssignImport'));
        function parseMSSVFromCSV(text){
          const lines = text.split(/\r?\n/).filter(l=>l.trim().length>0);
          if(lines.length===0) return [];
          const header = lines[0].split(',').map(x=>x.trim().toLowerCase());
          let hasHeader = header.includes('mssv');
          const start = hasHeader ? 1 : 0;
          const colIndex = hasHeader ? header.indexOf('mssv') : 0;
          const out = [];
          for(let i=start;i<lines.length;i++){
            const cols = lines[i].split(',');
            const code = (cols[colIndex]||'').trim();
            if(code) out.push(code);
          }
          return out;
        }
        function parseMSSVFromText(text){
          return text.split(/[\s,]+/).map(x=>x.trim()).filter(Boolean);
        }
        document.getElementById('confirmAssignImport').addEventListener('click', ()=>{
          const lec = lecturers.find(x=>x.id===selectedId); if(!lec){ alert('Chọn GV trước.'); return; }
          const file = document.getElementById('assignCsvFile').files[0];
          const manualText = document.getElementById('assignCodes').value||'';
          const applyCodes = (codes)=>{
            const valid = codes.filter(code => allStudents.some(s=>s.mssv===code));
            const notFound = codes.filter(code => !allStudents.some(s=>s.mssv===code));
            const set = new Set([...(lec.students||[]), ...valid]);
            lec.students = Array.from(set);
            closeModal('modalAssignImport');
            // reset inputs
            document.getElementById('assignCsvFile').value='';
            document.getElementById('assignCodes').value='';
            renderAssigned(); renderLecturerList();
            alert(`Đã thêm ${valid.length} MSSV. Bỏ qua ${notFound.length} không tồn tại.`);
          };
          if(file){
            const reader = new FileReader();
            reader.onload = ()=>{
              try{ const codes = parseMSSVFromCSV(String(reader.result||'')); applyCodes(codes); }
              catch{ alert('Không thể đọc file CSV'); }
            };
            reader.readAsText(file);
          } else if(manualText.trim().length>0){
            const codes = parseMSSVFromText(manualText);
            applyCodes(codes);
          } else {
            alert('Vui lòng chọn file CSV hoặc dán danh sách MSSV.');
          }
        })

        // initial render and optional preselect via ?id=
        function getQuery(k){ return new URLSearchParams(location.search).get(k); }
        selectedId = getQuery('id') || lecturers[0]?.id || null;
        renderLecturerList();
        renderDetail();
      })();
    </script>
  </body>
</html>
