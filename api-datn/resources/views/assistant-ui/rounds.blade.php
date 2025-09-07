<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đợt đồ án - Trợ lý khoa</title>
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

      <div class="flex-1 h-screen overflow-hidden flex flex-col">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
          <div class="flex items-center gap-3 flex-1">
            <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
            <div>
              <h1 class="text-lg md:text-xl font-semibold">Danh sách đợt đồ án</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp</nav>
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

        <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 space-y-5">
          <div class="max-w-6xl mx-auto space-y-5">
          <!-- Actions -->
          <div class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="relative w-full sm:max-w-sm">
              <input id="searchInput" class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tên đợt" />
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <button onclick="openCreateRoundModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><i class="ph ph-plus"></i>Tạo đợt mới</button>
          </div>

          <!-- Table -->
          <div class="bg-white border border-slate-200 rounded-xl">
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500">
                    <th data-sort-key="name" data-sort-type="string" class="py-3 px-4 border-b cursor-pointer select-none">Tên đợt <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="time" data-sort-type="date-range" class="py-3 px-4 border-b cursor-pointer select-none">Thời gian <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="committees" data-sort-type="number" class="py-3 px-4 border-b cursor-pointer select-none">Số hội đồng <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th class="py-3 px-4 border-b text-right">Hành động</th>
                  </tr>
                </thead>
                <tbody id="tableBody">
                  @foreach ($terms as $term)
                    @php
                      $start_year = $term->start_date ? substr($term->start_date, 0, 4) : '';
                      $end_year = $term->end_date ? substr($term->end_date, 0, 4) : '';
                    @endphp
                  <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4"><a href="{{ route('web.assistant.round_detail', ['round_id' => $term->id]) }}" class="text-blue-600 hover:underline">{{"Đợt " . $term->stage . " " . $start_year . "-" . $end_year}}</a></td>
                    <td class="py-3 px-4">{{$term->start_date}} - {{$term->end_date}}</td>
                    <td class="py-3 px-4">12</td>
                    <td class="py-3 px-4 text-right space-x-2">
                      <a class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" href="{{ route('web.assistant.round_detail', ['round_id' => $term->id]) }}"><i class="ph ph-eye"></i></a>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" onclick="openModal('edit')"><i class="ph ph-pencil"></i></button>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"><i class="ph ph-trash"></i></button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        </main>
      </div>
    </div>

    <!-- Modal (static) -->
    <div id="modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center z-50">
      <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">
        <div class="p-4 border-b flex items-center justify-between">
          <h3 id="modalTitle" class="font-semibold">Tạo đợt mới</h3>
          <button class="p-2 hover:bg-slate-100 rounded-lg" onclick="closeModal()" data-close aria-label="Đóng"><i class="ph ph-x"></i></button>
        </div>
        <form class="p-4 space-y-5" onsubmit="event.preventDefault(); closeModal();">
          <!-- Năm học + Đợt (gọn) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="relative">
              <label class="text-sm font-medium">Năm học</label>
              <i class="ph ph-calendar text-slate-400 absolute left-3 bottom-3.5 pointer-events-none"></i>
              <select id="modalYearSelect" required class="mt-1 w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                <!-- JS sẽ render options hoặc render Blade nếu có -->
              </select>
            </div>
            <div class="relative">
              <label class="text-sm font-medium">Đợt</label>
              <i class="ph ph-flag text-slate-400 absolute left-3 bottom-3.5 pointer-events-none"></i>
              <select id="modalStageSelect" required class="mt-1 w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                <option value="1">Đợt 1</option>
                <option value="2">Đợt 2</option>
                <option value="Hè">Đợt Hè</option>
              </select>
            </div>
          </div>

          <!-- Thời gian đợt -->
          <div class="grid sm:grid-cols-2 gap-4">
            <div> <!-- Ngày bắt đầu (static modal) -->
              <label class="text-sm font-medium">Ngày bắt đầu</label>
              <div class="relative mt-1">
                <i class="ph ph-calendar-check text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input type="date" required class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm" />
              </div>
            </div>
            <div> <!-- Ngày kết thúc (static modal) -->
              <label class="text-sm font-medium">Ngày kết thúc</label>
              <div class="relative mt-1">
                <i class="ph ph-calendar-x text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input type="date" required class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm" />
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeModal()" data-close class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
            <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Lưu</button>
          </div>
        </form>
      </div>
    </div>

    <script>
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const mainArea = document.querySelector('.flex-1');
        if(c){
          html.classList.add('sidebar-collapsed');
          mainArea.classList.add('md:pl-[72px]');
          mainArea.classList.remove('md:pl-[260px]');
        } else {
          html.classList.remove('sidebar-collapsed');
          mainArea.classList.remove('md:pl-[72px]');
          mainArea.classList.add('md:pl-[260px]');
        }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed');setCollapsed(c);localStorage.setItem('assistant_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      // simple filter
      document.getElementById('searchInput').addEventListener('input', (e)=>{
        const q=e.target.value.toLowerCase();
        document.querySelectorAll('#tableBody tr').forEach(tr=> tr.style.display = tr.innerText.toLowerCase().includes(q)?'':'none');
      });

      // sorting
      const sortState = { key:null, dir:1 };
      function parseDateVN(d){ // dd/mm/yyyy
        const [dd,mm,yyyy] = d.split('/').map(Number);
        return new Date(yyyy, mm-1, dd).getTime();
      }
      function getSortValue(tr, key){
        const tds = tr.querySelectorAll('td');
        if(key==='name') return (tds[0]?.innerText||'').trim().toLowerCase();
        if(key==='time'){
          const txt=(tds[1]?.innerText||'').trim();
          const start = txt.split('-')[0]?.trim();
          return start? parseDateVN(start) : 0;
        }
        if(key==='committees') return Number((tds[2]?.innerText||'0').replace(/\D+/g,''));
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
        // update indicators
        document.querySelectorAll('thead th[data-sort-key] i').forEach(i=>{i.className='ph ph-caret-up-down ml-1 text-slate-400';});
        const icon = th.querySelector('i');
        icon.className = sortState.dir===1 ? 'ph ph-caret-up ml-1 text-slate-600' : 'ph ph-caret-down ml-1 text-slate-600';
      }
      document.querySelectorAll('thead th[data-sort-key]').forEach(th=> th.addEventListener('click',()=>applySort(th)));

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
        const toggleButton = graduationItem?.querySelector('.toggle-button');
        const submenu = graduationItem?.querySelector('.submenu');
        const caretIcon = toggleButton?.querySelector('.ph.ph-caret-down');

        if (toggleButton && submenu) {
          // Mở sẵn khi vào trang
          submenu.classList.remove('hidden');
          toggleButton.setAttribute('aria-expanded', 'true');
          caretIcon?.classList.add('transition-transform','rotate-180');

          toggleButton.addEventListener('click', (e) => {
            e.preventDefault();
            submenu.classList.toggle('hidden');
            const expanded = !submenu.classList.contains('hidden');
            toggleButton.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            caretIcon?.classList.toggle('rotate-180', expanded);
          });
        }
      });

      // ===== Dynamic Create-Round Modal with 8 fixed timeline stages =====
      const AU_STAGES=[
        {id:1, name:'Tiếp nhận yêu cầu'},
        {id:2, name:'Đề cương'},
        {id:3, name:'Nhật ký tuần'},
        {id:4, name:'Báo cáo'},
        {id:5, name:'Phân hội đồng'},
        {id:6, name:'Phản biện'},
        {id:7, name:'Công bố & thứ tự'},
        {id:8, name:'Bảo vệ & kết quả'}
      ];
      function au_registerModal(wrapper){
        const panel=wrapper.querySelector('[data-modal-container]');
        function close(){ wrapper.remove(); document.removeEventListener('keydown', esc); }
        function esc(e){ if(e.key==='Escape') close(); }
        // Sửa: dùng closest('[data-close]') để bắt cả click vào icon trong button
        wrapper.addEventListener('click',e=>{
          if (e.target.hasAttribute('data-overlay') || e.target.closest('[data-close]')) {
            close();
          }
        });
        panel.addEventListener('click',e=> e.stopPropagation());
        document.addEventListener('keydown', esc);
        const first=panel.querySelector('input,select,textarea,button'); first && first.focus();
      }
      // Tạo options năm học dạng 2025-2026, 2024-2025, ...
      function buildYearOptions(count = 6) {
        const now = new Date().getFullYear();
        const items = [];
        for (let i = 0; i < count; i++) {
          const start = now - i;
          const end = start + 1;
          const y = `${start}-${end}`;
          items.push(`<option value="${y}">${y}</option>`);
        }
        return items.join('');
      }

      // Khởi tạo options cho select Năm học trong modal tĩnh
      const modalYear = document.getElementById('modalYearSelect');
      if (modalYear) modalYear.innerHTML = buildYearOptions(6);

      // Thay thế hàm modal cũ
      function openCreateRoundModal(){
        const wrap=document.createElement('div');
        wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
        wrap.innerHTML=`
          <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" data-overlay></div>
          <div class="bg-white w-full sm:max-w-4xl max-h-[92vh] overflow-auto rounded-t-2xl sm:rounded-2xl shadow-xl relative z-10" data-modal-container>
            <div class="p-5 border-b flex items-center justify-between sticky top-0 bg-white">
              <h3 class="font-semibold text-base">Tạo đợt đồ án tốt nghiệp</h3>
              <button data-close class="p-2 hover:bg-slate-100 rounded-lg" aria-label="Đóng"><i class="ph ph-x"></i></button>
            </div>

            <form id="createRoundForm" method="POST" action="{{ route('web.assistant.rounds.store') }}" class="p-5 space-y-6">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <!-- Năm học + Đợt -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative">
                  <label class="text-sm font-medium">Năm học</label>
                  <i class="ph ph-calendar text-slate-400 absolute left-3 bottom-3.5 pointer-events-none"></i>
                  <select name="academy_year_id" required class="mt-1 w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm">
                    @foreach ($years as $year)
                      <option value="{{ $year->id }}">{{ $year->year_name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="relative">
                  <label class="text-sm font-medium">Đợt</label>
                  <i class="ph ph-flag text-slate-400 absolute left-3 bottom-3.5 pointer-events-none"></i>
                  <select name="stage" required class="mt-1 w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm">
                    <option value="1">Đợt 1</option>
                    <option value="2">Đợt 2</option>
                    <option value="Hè">Đợt Hè</option>
                  </select>
                </div>
              </div>

              <!-- Ngày bắt đầu/kết thúc -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-medium">Ngày bắt đầu đợt</label>
                  <div class="relative mt-1">
                    <i class="ph ph-calendar-check text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    <input type="date" name="start_date" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm" />
                  </div>
                </div>
                <div>
                  <label class="text-sm font-medium">Ngày kết thúc đợt</label>
                  <div class="relative mt-1">
                    <i class="ph ph-calendar-x text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    <input type="date" name="end_date" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm" />
                  </div>
                </div>
              </div>

              <!-- Mô tả -->
              <div>
                <label class="text-sm font-medium">Mô tả</label>
                <input name="description" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 text-sm" placeholder="VD: Đợt 1 2025-2026" />
              </div>

              <!-- Timeline 1–8 -->
              <div>
                <div class="flex items-center justify-between mb-2">
                  <h4 class="font-semibold">Mốc timeline (1–8)</h4>
                  <span class="text-xs text-slate-500">Nhập thời gian cho từng mốc</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  ${[1,2,3,4,5,6,7,8].map(i=>`
                    <div class="rounded-lg border border-slate-200 p-3 bg-slate-50/50">
                      <div class="font-medium mb-2">Bước ${i}</div>
                      <div class="grid grid-cols-2 gap-3">
                        <div>
                          <label class="block text-xs text-slate-600 mb-1">Bắt đầu</label>
                          <div class="relative">
                            <i class="ph ph-clock text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input type="date" name="stage_${i}_start" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                          </div>
                        </div>
                        <div>
                          <label class="block text-xs text-slate-600 mb-1">Kết thúc</label>
                          <div class="relative">
                            <i class="ph ph-clock-afternoon text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input type="date" name="stage_${i}_end" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                          </div>
                        </div>
                      </div>
                    </div>`).join('')}
                </div>
              </div>

              <div class="flex items-center justify-end gap-2 pt-2 border-t">
                <button type="button" data-close class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Tạo đợt</button>
              </div>
            </form>
          </div>`;
        document.body.appendChild(wrap);
        au_registerModal(wrap);

        // LƯU Ý: Không chặn submit nữa (để POST về server)
      }

      // Close/Open static modal (#modal)
      const modalEl = document.getElementById('modal');
      const modalPanel = modalEl?.querySelector('div.bg-white');

      function openModal() {
        modalEl?.classList.remove('hidden');
      }

      function closeModal() {
        modalEl?.classList.add('hidden');
      }

      // Click outside to close
      modalEl?.addEventListener('click', (e) => {
        if (e.target === modalEl) closeModal();
      });
      // Prevent bubbling when clicking inside panel
      modalPanel?.addEventListener('click', (e) => e.stopPropagation());

      // ESC to close
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modalEl && !modalEl.classList.contains('hidden')) {
          closeModal();
        }
      });

      // Expose for buttons already using onclick
      window.openModal = openModal;
      window.closeModal = closeModal;
    </script>
  </body>
</html>
