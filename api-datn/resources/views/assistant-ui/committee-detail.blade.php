<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chi tiết hội đồng - Trợ lý khoa</title>
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
    @php
      $user = auth()->user();
      $userName = $user->fullname ?? $user->name ?? 'Giảng viên';
      $email = $user->email ?? '';
      // Tùy mô hình dữ liệu, thay các field bên dưới cho khớp
      $dept = $user->department_name ?? optional($user->teacher)->department ?? '';
      $faculty = $user->faculty_name ?? optional($user->teacher)->faculty ?? '';
      $subtitle = trim(($dept ? "Bộ môn $dept" : '') . (($dept && $faculty) ? ' • ' : '') . ($faculty ? "Khoa $faculty" : ''));
      $degree = $user->teacher->degree ?? '';
      $expertise = $user->teacher->supervisor->expertise ?? 'null';
      $data_assignment_supervisors = $user->teacher->supervisor->assignment_supervisors ?? collect();;
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
              <h1 class="text-lg md:text-xl font-semibold">Chi tiết hội đồng</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp / Hội đồng</nav>
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

  <main class="pt-20 px-4 md:px-6 pb-10 ">
          <div class="max-w-6xl mx-auto space-y-6">
          <!-- Committee header -->
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between">
              <div>
                <div class="flex items-center gap-2">
                  <h2 class="font-semibold text-lg" id="committeeCode">HĐ-CNTT-01</h2>
                  <span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700" id="committeeDept">CNTT</span>
                </div>
                <div class="text-sm text-slate-600">Thuộc đợt: <a class="text-blue-600 hover:underline" href="round-detail.html" id="committeeRound">HK1 2025-2026</a></div>
                <div class="mt-1 text-sm text-slate-600 flex items-center gap-4">
                  <span class="flex items-center gap-1"><i class="ph ph-map-pin-line"></i><span id="committeeRoom">Phòng B203</span></span>
                  <span class="flex items-center gap-1"><i class="ph ph-calendar"></i><span id="committeeTime">20/09/2025 08:00</span></span>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <div class="hidden sm:flex items-center gap-2 mr-2">
                  <span class="px-2 py-1 rounded-lg text-xs bg-slate-100 text-slate-700"><i class="ph ph-users-three mr-1"></i> <span id="memberCount">0</span>/5 TV</span>
                  <span class="px-2 py-1 rounded-lg text-xs bg-slate-100 text-slate-700"><i class="ph ph-student mr-1"></i> <span id="studentCount">0</span> SV</span>
                </div>
                <button id="btnEditCommittee" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm"><i class="ph ph-pencil"></i> Sửa</button>
                <button id="btnAddMember" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm"><i class="ph ph-user-plus"></i> Thêm thành viên</button>
                <div class="hidden md:flex items-center gap-2">
                  <button id="btnAddStudent" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm"><i class="ph ph-user-plus"></i> Thêm SV</button>
                  <button id="btnImportStudents" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm"><i class="ph ph-upload-simple"></i> Import SV</button>
                </div>
              </div>
            </div>
          </section>

          <!-- Members -->
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between">
              <h3 class="font-semibold">Thành viên hội đồng</h3>
              <button id="btnAddMemberTop" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm"><i class="ph ph-user-plus"></i> Thêm thành viên</button>
            </div>
            <div id="membersGrid" class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4"></div>
            <p class="mt-3 text-xs text-slate-500">Thành phần: 1 Chủ tịch, 1 Thư kí, 3 Ủy viên.</p>
          </section>

          <!-- Students -->
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between gap-3 flex-wrap">
              <div class="flex items-center gap-3">
                <h3 class="font-semibold">Sinh viên thuộc hội đồng</h3>
                <span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700"><span id="studentCountBadge">0</span> sinh viên</span>
              </div>
              <div class="flex items-center gap-2">
                <div class="relative">
                  <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
                  <input id="searchStudent" class="pl-9 pr-3 py-2 border rounded-lg text-sm w-56" placeholder="Tìm theo MSSV, tên, lớp..." />
                </div>
                <button id="btnAssignReviewers" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm"><i class="ph ph-user-switch"></i> Gán SV phản biện</button>
                <button id="btnAddStudent2" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm"><i class="ph ph-user-plus"></i> Thêm SV</button>
                <button id="btnImportStudents2" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm"><i class="ph ph-upload-simple"></i> Import</button>
              </div>
            </div>

            <div class="mt-4 overflow-x-auto">
              <table class="min-w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-600 border-b">
                    <th class="py-2 pr-4">Mã SV</th>
                    <th class="py-2 pr-4">Họ tên</th>
                    <th class="py-2 pr-4">Lớp</th>
                    <th class="py-2 pr-4">Đề tài</th>
                    <th class="py-2 pr-4">GVHD</th>
                    <th class="py-2 pr-4">Phản biện</th>
                    <th class="py-2 pr-4">Trạng thái</th>
                    <th class="py-2 pr-4 text-right">Hành động</th>
                  </tr>
                </thead>
                <tbody id="studentsBody"></tbody>
              </table>
            </div>
          </section>
        </div>
        </main>

        <!-- Add Member Modal -->
        <div id="modalAddMember" class="hidden fixed inset-0 z-50 bg-black/40 p-4">
          <div class="max-w-lg mx-auto bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b">
              <h4 class="font-semibold">Thêm thành viên</h4>
              <button class="p-2 rounded-lg hover:bg-slate-100" data-close-modal="modalAddMember"><i class="ph ph-x"></i></button>
            </div>
            <div class="p-5 space-y-4">
              <div>
                <label class="text-sm text-slate-600">Vai trò</label>
                <select id="memberRole" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm">
                  <option value="chutich">Chủ tịch</option>
                  <option value="thuki">Thư kí</option>
                  <option value="uyvien">Ủy viên</option>
                </select>
              </div>
              <div>
                <label class="text-sm text-slate-600">Giảng viên</label>
                <select id="lecturerSelect" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm"></select>
                <p class="mt-1 text-xs text-slate-500">Danh sách demo từ mock data.</p>
              </div>
            </div>
            <div class="px-5 py-3 border-t flex items-center justify-end gap-2">
              <button class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm" data-close-modal="modalAddMember">Hủy</button>
              <button id="confirmAddMember" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">Thêm</button>
            </div>
          </div>
        </div>

        <!-- Add Student Modal -->
        <div id="modalAddStudent" class="hidden fixed inset-0 z-50 bg-black/40 p-4">
          <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b">
              <h4 class="font-semibold">Thêm sinh viên vào hội đồng</h4>
              <button class="p-2 rounded-lg hover:bg-slate-100" data-close-modal="modalAddStudent"><i class="ph ph-x"></i></button>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="text-sm text-slate-600">Mã SV</label>
                <input id="sv_mssv" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder="2212345" />
              </div>
              <div>
                <label class="text-sm text-slate-600">Họ tên</label>
                <input id="sv_name" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder="Nguyễn Văn A" />
              </div>
              <div>
                <label class="text-sm text-slate-600">Lớp</label>
                <input id="sv_class" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder="DHKTPM16A" />
              </div>
              <div>
                <label class="text-sm text-slate-600">GVHD</label>
                <input id="sv_gvhd" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder="TS. Trần B" />
              </div>
              <div class="sm:col-span-2">
                <label class="text-sm text-slate-600">Đề tài</label>
                <input id="sv_topic" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder="Xây dựng hệ thống ..." />
              </div>
            </div>
            <div class="px-5 py-3 border-t flex items-center justify-end gap-2">
              <button class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm" data-close-modal="modalAddStudent">Hủy</button>
              <button id="confirmAddStudent" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">Thêm</button>
            </div>
          </div>
        </div>

        <!-- Import Students Modal -->
        <div id="modalImport" class="hidden fixed inset-0 z-50 bg-black/40 p-4">
          <div class="max-w-xl mx-auto bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b">
              <h4 class="font-semibold">Import danh sách sinh viên (CSV)</h4>
              <button class="p-2 rounded-lg hover:bg-slate-100" data-close-modal="modalImport"><i class="ph ph-x"></i></button>
            </div>
            <div class="p-5 space-y-3">
              <input id="csvFile" type="file" accept=".csv" class="w-full text-sm" />
              <p class="text-xs text-slate-500">Định dạng: mssv,hoTen,lop,deTai,gvhd (có header).</p>
              <details class="text-xs text-slate-500">
                <summary class="cursor-pointer select-none">Ví dụ</summary>
                <pre class="bg-slate-50 p-3 rounded border overflow-auto">mssv,hoTen,lop,deTai,gvhd\n2212345,Nguyen Van A,DHKTPM16A,Hệ thống quản lí đồ án,TS. Tran B\n2212346,Le Thi B,DHKHMT16B,AI hỗ trợ giảng dạy,ThS. Nguyen C</pre>
              </details>
            </div>
            <div class="px-5 py-3 border-t flex items-center justify-end gap-2">
              <button class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm" data-close-modal="modalImport">Hủy</button>
              <button id="confirmImport" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">Import</button>
            </div>
          </div>
        </div>

        <!-- Assign Reviewers Modal -->
        <div id="modalAssignReviewers" class="hidden fixed inset-0 z-50 bg-black/40 p-4">
          <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between px-5 py-3 border-b">
              <h4 class="font-semibold">Gán sinh viên cho giảng viên phản biện</h4>
              <button class="p-2 rounded-lg hover:bg-slate-100" data-close-modal="modalAssignReviewers"><i class="ph ph-x"></i></button>
            </div>
            <div class="p-5 overflow-auto">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                  <label class="text-sm text-slate-600">Giảng viên phản biện</label>
                  <select id="ar_reviewer" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm"></select>
                </div>
                <div class="">
                  <label class="text-sm text-slate-600">Tìm sinh viên</label>
                  <div class="relative">
                    <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
                    <input id="ar_search" class="mt-1 w-full pl-9 pr-3 py-2 border rounded-lg text-sm" placeholder="MSSV, tên, lớp..." />
                  </div>
                </div>
              </div>
              <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                  <thead>
                    <tr class="text-left text-slate-600 border-b">
                      <th class="py-2 pr-4"><input id="ar_selectAll" type="checkbox" /></th>
                      <th class="py-2 pr-4">Mã SV</th>
                      <th class="py-2 pr-4">Họ tên</th>
                      <th class="py-2 pr-4">Lớp</th>
                      <th class="py-2 pr-4">Đề tài</th>
                      <th class="py-2 pr-4">GVHD</th>
                    </tr>
                  </thead>
                  <tbody id="ar_list"></tbody>
                </table>
              </div>
            </div>
            <div class="px-5 py-3 border-t flex items-center justify-end gap-2">
              <button class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm" data-close-modal="modalAssignReviewers">Hủy</button>
              <button id="ar_confirm" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">Gán</button>
            </div>
          </div>
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
      toggleButton.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent default link behavior
        submenu.classList.toggle('hidden');
      });
    }
  });

          // Committee mock data and UI logic
          (function(){
            const lecturers = [
              { id:'gv01', name:'TS. Đặng Hữu T', dept:'CNTT' },
              { id:'gv02', name:'ThS. Lưu Lan', dept:'CNTT' },
              { id:'gv03', name:'TS. Nguyễn Văn A', dept:'CNTT' },
              { id:'gv04', name:'ThS. Phạm Minh K', dept:'CNTT' },
              { id:'gv05', name:'TS. Trần Thị B', dept:'CNTT' },
            ];

          const committee = {
              code: 'HĐ-CNTT-01', dept: 'CNTT', round: 'HK1 2025-2026', room: 'Phòng B203', time: '20/09/2025 08:00',
              members: {
                chutich: null, // { id, name }
                thuki: null,
                uyvien: [] // up to 3
              },
              students: [
            { mssv:'2212345', name:'Nguyễn Văn A', lop:'DHKTPM16A', topic:'Hệ thống quản lí đồ án', gvhd:'TS. Trần B', status:'Chờ bảo vệ', reviewers:[] },
            { mssv:'2212346', name:'Lê Thị B', lop:'DHKHMT16B', topic:'AI hỗ trợ giảng dạy', gvhd:'ThS. Nguyễn C', status:'Chờ bảo vệ', reviewers:[] },
            { mssv:'2212347', name:'Trần Minh C', lop:'DHKTPM16C', topic:'Website thương mại', gvhd:'TS. Lê D', status:'Đã bảo vệ', reviewers:[] },
              ]
            };

            // hydrate header
            document.getElementById('committeeCode').textContent = committee.code;
            document.getElementById('committeeDept').textContent = committee.dept;
            document.getElementById('committeeRound').textContent = committee.round;
            document.getElementById('committeeRoom').textContent = committee.room;
            document.getElementById('committeeTime').textContent = committee.time;

            // member select options
            const lecturerSelect = document.getElementById('lecturerSelect');
            lecturerSelect.innerHTML = lecturers.map(l=>`<option value="${l.id}">${l.name}</option>`).join('');

            const membersGrid = document.getElementById('membersGrid');

            function memberCard(roleLabel, member, roleKey){
              if(!member){
                return `<div class="border rounded-lg p-4 h-full flex flex-col justify-between">
                  <div>
                    <div class="text-xs text-slate-500">${roleLabel}</div>
                    <div class="mt-1 text-slate-400 italic">Chưa có</div>
                  </div>
                  <div class="mt-3">
                    <button class="px-2 py-1 rounded border text-xs hover:bg-slate-50" data-open-role="${roleKey}"><i class="ph ph-user-plus"></i> Thêm</button>
                  </div>
                </div>`;
              }
              return `<div class="border rounded-lg p-4 h-full flex flex-col justify-between">
                <div>
                  <div class="text-xs text-slate-500">${roleLabel}</div>
                  <div class="mt-1 font-medium">${member.name}</div>
                </div>
                <div class="mt-3 flex items-center gap-2">
                  <a href="../lecturer-ui/profile.html" class="px-2 py-1 rounded border text-xs hover:bg-slate-50"><i class="ph ph-user"></i> Hồ sơ</a>
                  <button class="px-2 py-1 rounded border text-xs text-rose-600 hover:bg-rose-50" data-remove-role="${roleKey}"><i class="ph ph-trash"></i> Gỡ</button>
                </div>
              </div>`;
            }

            function renderMembers(){
              const slots = [];
              slots.push(memberCard('Chủ tịch', committee.members.chutich, 'chutich'));
              slots.push(memberCard('Thư kí', committee.members.thuki, 'thuki'));
              const uv = committee.members.uyvien;
              for(let i=0;i<3;i++){
                slots.push(memberCard(`Ủy viên ${i+1}`, uv[i]||null, `uyvien_${i}`));
              }
              membersGrid.innerHTML = slots.join('');
              document.getElementById('memberCount').textContent = (committee.members.chutich?1:0) + (committee.members.thuki?1:0) + committee.members.uyvien.filter(Boolean).length;

              // wire buttons
              membersGrid.querySelectorAll('[data-open-role]').forEach(btn=>{
                btn.addEventListener('click', ()=>{
                  const roleKey = btn.getAttribute('data-open-role');
                  openAddMember(roleKey);
                })
              });
              membersGrid.querySelectorAll('[data-remove-role]').forEach(btn=>{
                btn.addEventListener('click', ()=>{
                  const roleKey = btn.getAttribute('data-remove-role');
                  if(roleKey==='chutich') committee.members.chutich = null;
                  else if(roleKey==='thuki') committee.members.thuki = null;
                  else if(roleKey.startsWith('uyvien_')){
                    const idx = parseInt(roleKey.split('_')[1]);
                    committee.members.uyvien[idx] = null;
                    // compact array
                    committee.members.uyvien = committee.members.uyvien.filter(Boolean);
                  }
                  renderMembers();
                })
              });
            }

            // students table
            const studentsBody = document.getElementById('studentsBody');
            const searchStudent = document.getElementById('searchStudent');
            function renderStudents(){
              const q = (searchStudent.value||'').toLowerCase().trim();
              const rows = committee.students.filter(s=>{
                if(!q) return true;
                return [s.mssv,s.name,s.lop,s.topic,s.gvhd].some(x=> (x||'').toLowerCase().includes(q));
              }).map(s=>{
                const statusBadge = s.status==='Đã bảo vệ'
                  ? '<span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Đã bảo vệ</span>'
                  : '<span class="px-2 py-1 rounded-full text-xs bg-amber-100 text-amber-700">Chờ bảo vệ</span>';
                const rb = (s.reviewers||[]).map(n=>`<span class=\"px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700\">${n}</span>`).join(' ') || '<span class="text-xs text-slate-400">Chưa gán</span>';
                return `<tr class="border-b hover:bg-slate-50">
                  <td class="py-2 pr-4 font-mono">${s.mssv}</td>
                  <td class="py-2 pr-4">${s.name}</td>
                  <td class="py-2 pr-4">${s.lop}</td>
                  <td class="py-2 pr-4">${s.topic}</td>
                  <td class="py-2 pr-4">${s.gvhd}</td>
                  <td class="py-2 pr-4">${rb}</td>
                  <td class="py-2 pr-4">${statusBadge}</td>
                  <td class="py-2 pr-0 text-right">
                    <div class="inline-flex gap-2">
                      <a class="px-2 py-1 rounded border text-xs hover:bg-slate-50" href="progress-tracking.html?studentId=${encodeURIComponent(s.mssv)}"><i class="ph ph-chart-line-up"></i> Tiến độ</a>
                      <button class="px-2 py-1 rounded border text-xs hover:bg-slate-50" data-remove-student="${s.mssv}"><i class="ph ph-user-minus"></i> Gỡ</button>
                    </div>
                  </td>
                </tr>`
              });
              studentsBody.innerHTML = rows.join('');
              const count = committee.students.length;
              document.getElementById('studentCount').textContent = count;
              document.getElementById('studentCountBadge').textContent = count;

              studentsBody.querySelectorAll('[data-remove-student]').forEach(btn=>{
                btn.addEventListener('click', ()=>{
                  const mssv = btn.getAttribute('data-remove-student');
                  committee.students = committee.students.filter(s=>s.mssv!==mssv);
                  renderStudents();
                })
              })
            }

            searchStudent.addEventListener('input', renderStudents);

            // modal helpers
            function openModal(id){ document.getElementById(id).classList.remove('hidden'); }
            function closeModal(id){ document.getElementById(id).classList.add('hidden'); }
            document.querySelectorAll('[data-close-modal]').forEach(btn=>{
              btn.addEventListener('click', ()=> closeModal(btn.getAttribute('data-close-modal')));
            })

            // add member flow
            let selectedRoleKey = 'chutich';
            function openAddMember(roleKey){
              selectedRoleKey = roleKey;
              // map roleKey like 'uyvien_1' -> 'uyvien'
              const roleSelect = document.getElementById('memberRole');
              const value = roleKey.startsWith('uyvien') ? 'uyvien' : roleKey;
              roleSelect.value = value;
              openModal('modalAddMember');
            }

            function addMember(){
              const role = document.getElementById('memberRole').value; // chutich | thuki | uyvien
              const lecId = document.getElementById('lecturerSelect').value;
              const lec = lecturers.find(l=>l.id===lecId);
              if(!lec) return;
              if(role==='chutich') committee.members.chutich = { id:lec.id, name:lec.name };
              else if(role==='thuki') committee.members.thuki = { id:lec.id, name:lec.name };
              else if(role==='uyvien'){
                if(committee.members.uyvien.length>=3){ alert('Tối đa 3 Ủy viên.'); return; }
                committee.members.uyvien.push({ id:lec.id, name:lec.name });
              }
              renderMembers();
              closeModal('modalAddMember');
            }

            document.getElementById('confirmAddMember').addEventListener('click', addMember);
            document.getElementById('btnAddMember').addEventListener('click', ()=> openAddMember('uyvien'));
            document.getElementById('btnAddMemberTop').addEventListener('click', ()=> openAddMember('uyvien'));

            // add student flow
            function addStudent(){
              const s = {
                mssv: document.getElementById('sv_mssv').value.trim(),
                name: document.getElementById('sv_name').value.trim(),
                lop: document.getElementById('sv_class').value.trim(),
                topic: document.getElementById('sv_topic').value.trim(),
                gvhd: document.getElementById('sv_gvhd').value.trim(),
                status: 'Chờ bảo vệ'
              };
              if(!s.mssv || !s.name) { alert('Vui lòng nhập tối thiểu MSSV và Họ tên'); return; }
              if(committee.students.some(x=>x.mssv===s.mssv)) { alert('MSSV đã tồn tại trong hội đồng.'); return; }
              committee.students.push(s);
              renderStudents();
              closeModal('modalAddStudent');
              // reset
              ['sv_mssv','sv_name','sv_class','sv_topic','sv_gvhd'].forEach(id=> document.getElementById(id).value='');
            }
            document.getElementById('confirmAddStudent').addEventListener('click', addStudent);
            ['btnAddStudent','btnAddStudent2'].forEach(id=>{
              document.getElementById(id).addEventListener('click', ()=> openModal('modalAddStudent'))
            })

            // import students from CSV
            function parseCSV(text){
              const lines = text.split(/\r?\n/).filter(l=>l.trim().length>0);
              if(lines.length===0) return [];
              const header = lines[0].split(',').map(x=>x.trim());
              const idx = {
                mssv: header.indexOf('mssv'),
                hoTen: header.indexOf('hoTen'),
                lop: header.indexOf('lop'),
                deTai: header.indexOf('deTai'),
                gvhd: header.indexOf('gvhd'),
              };
              const out = [];
              for(let i=1;i<lines.length;i++){
                const cols = lines[i].split(',');
                if(cols.length<header.length) continue;
                const s = {
                  mssv: (cols[idx.mssv]||'').trim(),
                  name: (cols[idx.hoTen]||'').trim(),
                  lop: (cols[idx.lop]||'').trim(),
                  topic: (cols[idx.deTai]||'').trim(),
                  gvhd: (cols[idx.gvhd]||'').trim(),
                  status: 'Chờ bảo vệ'
                };
                if(s.mssv && s.name) out.push(s);
              }
              return out;
            }
            document.getElementById('confirmImport').addEventListener('click', ()=>{
              const file = document.getElementById('csvFile').files[0];
              if(!file){ alert('Chọn file CSV trước.'); return; }
              const reader = new FileReader();
              reader.onload = ()=>{
                try{
                  const items = parseCSV(String(reader.result||''));
                  let added=0, skipped=0;
                  items.forEach(s=>{
                    if(committee.students.some(x=>x.mssv===s.mssv)) skipped++; else { committee.students.push(s); added++; }
                  })
                  renderStudents();
                  closeModal('modalImport');
                  alert(`Đã import ${added} SV, bỏ qua ${skipped} do trùng MSSV.`);
                }catch(err){ alert('Không thể đọc file CSV'); }
              }
              reader.readAsText(file);
            })
            ;['btnImportStudents','btnImportStudents2'].forEach(id=>{
              document.getElementById(id).addEventListener('click', ()=> openModal('modalImport'))
            })

            // initial render
            renderMembers();
            renderStudents();

            // Assign Reviewers modal logic
            const modalAR = document.getElementById('modalAssignReviewers');
            const arReviewer = document.getElementById('ar_reviewer');
            const arSearch = document.getElementById('ar_search');
            const arList = document.getElementById('ar_list');
            const arSelectAll = document.getElementById('ar_selectAll');

            function getAllReviewerNames(){
              const names = [];
              if(committee.members.chutich) names.push(committee.members.chutich.name);
              if(committee.members.thuki) names.push(committee.members.thuki.name);
              committee.members.uyvien.filter(Boolean).forEach(m=> names.push(m.name));
              return names;
            }
            function refreshReviewerOptions(){
              const names = getAllReviewerNames();
              arReviewer.innerHTML = '<option value="">-- Chọn giảng viên --</option>' + names.map(n=>`<option>${n}</option>`).join('');
            }
            function renderARList(){
              const q = (arSearch.value||'').toLowerCase().trim();
              const rows = committee.students
                .filter(s=> !q || [s.mssv,s.name,s.lop,s.topic,s.gvhd].some(x=> (x||'').toLowerCase().includes(q)))
                .map(s=>{
                  const checked = (s.reviewers||[]).includes(arReviewer.value) ? 'checked' : '';
                  return `<tr class="border-b">
                    <td class="py-2 pr-4"><input type="checkbox" data-ar-mssv="${s.mssv}" ${checked} /></td>
                    <td class="py-2 pr-4 font-mono">${s.mssv}</td>
                    <td class="py-2 pr-4">${s.name}</td>
                    <td class="py-2 pr-4">${s.lop}</td>
                    <td class="py-2 pr-4">${s.topic}</td>
                    <td class="py-2 pr-4">${s.gvhd}</td>
                  </tr>`
                });
              arList.innerHTML = rows.join('');
              arSelectAll.checked = false;
            }

            document.getElementById('btnAssignReviewers').addEventListener('click', ()=>{
              refreshReviewerOptions();
              openModal('modalAssignReviewers');
              renderARList();
            })
            arReviewer.addEventListener('change', renderARList);
            arSearch.addEventListener('input', renderARList);
            arSelectAll.addEventListener('change', ()=>{
              modalAR.querySelectorAll('input[data-ar-mssv]').forEach(ch=> ch.checked = arSelectAll.checked);
            })
            document.getElementById('ar_confirm').addEventListener('click', ()=>{
              const reviewer = arReviewer.value;
              if(!reviewer){ alert('Chọn giảng viên phản biện.'); return; }
              const selected = Array.from(modalAR.querySelectorAll('input[data-ar-mssv]:checked')).map(ch=> ch.getAttribute('data-ar-mssv'));
              // assign selected: ensure reviewer name added to s.reviewers (unique)
              committee.students.forEach(s=>{
                if(selected.includes(s.mssv)){
                  s.reviewers = Array.from(new Set([...(s.reviewers||[]), reviewer]));
                } else {
                  // keep existing assignments (no removal here)
                }
              })
              closeModal('modalAssignReviewers');
              renderStudents();
            })
          })();
    </script>
  </body>
</html>
