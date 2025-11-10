<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Thêm giảng viên hướng dẫn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <!-- SheetJS for Excel parsing -->
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
      html, body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
      .sidebar { width: 260px; }
      .sidebar-collapsed .sidebar { width: 72px; }
      .sidebar-collapsed .sidebar-label { display: none; }
      .submenu { display: none; }
      .submenu.open { display: block; }
    </style>
  </head>
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
  <body class="bg-slate-50 text-slate-800">
    <div class="min-h-screen flex">
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
          <a href="{{ route('web.assistant.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span class="sidebar-label">Bảng điều khiển</span></a>
          <a href="{{ route('web.assistant.manage_departments') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-buildings"></i><span class="sidebar-label">Bộ môn</span></a>
          <a href="{{ route('web.assistant.manage_majors') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
          <a href="{{ route('web.assistant.manage_staffs') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
          <a href="{{ route('web.assistant.assign_head') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Gán trưởng bộ môn</span></a>

          <div class="graduation-item">
            <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button font-semibold bg-slate-100">
              <span class="flex items-center gap-3">
                <i class="ph ph-graduation-cap"></i>
                <span class="sidebar-label">Học phần tốt nghiệp</span>
              </span>
              <i class="ph ph-caret-down"></i>
            </div>
            <div id="gradMenu" class="submenu pl-6">
              <a href="#" class="block px-3 py-2 rounded hover:bg-slate-100"><i class="ph ph-briefcase"></i> Thực tập tốt nghiệp</a>
              <a href="{{ route('web.assistant.rounds') }}"
                 class="block px-3 py-2 rounded hover:bg-slate-100 bg-slate-100 font-semibold"
                 aria-current="page"><i class="ph ph-calendar"></i> Đồ án tốt nghiệp</a>
            </div>
          </div>
        </nav>
        <div class="p-3 border-t border-slate-200">
          <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
        </div>
      </aside>

      <!-- Content -->
      <div class="flex-1">
        <header class="fixed top-0 left-0 md:left-[260px] right-0 h-16 bg-white border-b border-slate-200 z-20">
          <div class="h-full flex items-center justify-between px-4 md:px-6">
            <div class="flex items-center gap-3">
              <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
              <a href="{{ url()->previous() ?: route('web.assistant.rounds') }}"
                 class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50"
                 title="Quay lại">
                <i class="ph ph-arrow-left"></i><span class="text-sm">Quay lại</span>
              </a>
              <button type="button" onclick="history.back()" class="sm:hidden p-2 rounded-lg hover:bg-slate-100" aria-label="Quay lại">
                <i class="ph ph-arrow-left"></i>
              </button>
              <div>
                <h1 class="text-lg md:text-xl font-semibold">Thêm giảng viên hướng dẫn</h1>
                <p class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp / Thêm giảng viên</p>
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
          </div>
        </header>
        <main class="pt-20 px-4 md:px-6 pb-10 md:pl-[260px] space-y-6">
          <!-- Card: Tải tệp Excel -->
          <section class="bg-white border border-slate-200 rounded-xl p-5">
            <h2 class="font-semibold">Chọn tệp Excel</h2>
            <div class="mt-3">
              <label class="block">
                <input id="fileExcel" type="file" accept=".xlsx,.xls"
                       class="block w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
              </label>
              <p class="text-xs text-slate-500 mt-2">Mẫu cột: Email, Họ tên, Học vị, Bộ môn, Trạng thái.</p>
            </div>
            <div class="mt-4 flex items-center gap-2">
              <button id="btnUpload" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Tải lên</button>
              <a href="#" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Tải mẫu</a>
            </div>
          </section>

          <!-- Card: Thêm thủ công -->
          <section class="bg-white border border-slate-200 rounded-xl p-5">
            <div class="flex items-center justify-between">
              <h2 class="font-semibold">Thêm thủ công</h2>
              <div class="relative w-72 max-w-full">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="qInput" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600"
                       placeholder="Tìm email / họ tên / bộ môn" />
              </div>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-6">
              <!-- Danh sách ứng viên (table + checkbox) -->
<div class="rounded-xl border border-slate-200 shadow-sm overflow-hidden bg-white">
  <div class="h-11 bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-4 text-sm text-slate-700 flex items-center justify-between">
    <div class="flex items-center gap-2 font-semibold">
      <i class="ph ph-users text-lg text-blue-500"></i>
      Danh sách giảng viên
    </div>
    <label class="flex items-center gap-2 text-[13px] text-slate-600 cursor-pointer select-none">
      <input type="checkbox" id="selectAllSup" class="rounded border-slate-300 focus:ring-0 accent-blue-500">
      <span>Chọn tất cả</span>
    </label>
  </div>

  <div class="max-h-[480px] overflow-auto scrollbar-thin scrollbar-thumb-slate-300 scrollbar-track-transparent">
    <table class="w-full text-sm border-collapse">
      <thead class="sticky top-0 bg-slate-50 border-b border-slate-200 text-slate-600 text-[13px] uppercase">
        <tr>
          <th class="py-3 px-4 w-10"></th>
          <th class="py-3 px-4 text-left"><i class="ph ph-envelope text-slate-500 mr-1"></i>Email</th>
          <th class="py-3 px-4 text-left"><i class="ph ph-user text-slate-500 mr-1"></i>Họ tên</th>
          <th class="py-3 px-4 text-left"><i class="ph ph-buildings text-slate-500 mr-1"></i>Bộ môn</th>
          <th class="py-3 px-4 text-left"><i class="ph ph-graduation-cap text-slate-500 mr-1"></i>Học vị</th>
          <th class="py-3 px-4 text-left"><i class="ph ph-flask text-slate-500 mr-1"></i>Hướng nghiên cứu</th>
          <th class="py-3 px-4 text-right"><i class="ph ph-users-three text-slate-500 mr-1"></i>Số SV tối đa</th>
        </tr>
      </thead>

      <tbody id="supTbody" class="divide-y divide-slate-100">
        @php
          $supItems = $items ?? $supervisors ?? [];
        @endphp

        @if(!empty($supItems) && count($supItems))
          @foreach($supItems as $t)
            @php
              $email = optional(optional($t)->user)->email ?? $t->email ?? '';
              $name  = optional(optional($t)->user)->fullname ?? $t->fullname ?? ($t->name ?? '');
              $dept  = optional($t->department)->name ?? ($t->department_name ?? ($t->dept ?? ''));
              $title = $t->degree ?? ($t->title ?? '');
              $listResearches = $t->user?->userResearches ?? [];
            @endphp
            <tr class="hover:bg-slate-50 transition-all duration-150">
              <td class="py-3 px-4">
                <input type="checkbox"
                  class="rounded border-slate-300 accent-blue-500 focus:ring-0"
                  data-email="{{ $email }}"
                  data-name="{{ $name }}"
                  data-dept="{{ $dept }}"
                  data-title="{{ $title }}">
              </td>

              <td class="py-3 px-4 font-medium text-slate-800">{{ $email }}</td>
              <td class="py-3 px-4">{{ $name }}</td>
              <td class="py-3 px-4">{{ $dept ?: '-' }}</td>
              <td class="py-3 px-4">{{ $title ?: '-' }}</td>

              <td class="py-3 px-4 text-slate-700">
                @if ($listResearches->count() > 0)
                  @foreach ($listResearches as $re)
                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full text-xs font-medium">
                      <i class="ph ph-flask text-[11px]"></i>{{ $re->research->name }}
                    </span>
                  @endforeach
                @else
                  <span class="text-slate-400 italic">-</span>
                @endif
              </td>

              <td class="py-3 px-4 text-right">
                <input type="number" min="0" step="1" value=""
                  placeholder="vd: 10"
                  class="w-24 px-2 py-1 border border-slate-300 rounded-lg text-sm text-right focus:border-blue-400 focus:ring-1 focus:ring-blue-100 outline-none transition"
                  data-max-for="{{ $email }}">
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="7" class="text-center py-8 text-slate-500 text-sm">
              <i class="ph ph-info text-lg text-blue-500 block mb-2"></i>
              Không có giảng viên nào để hiển thị.
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>


              <!-- Danh sách đã chọn (rút gọn) -->
              <div class="rounded-xl border border-slate-200 flex flex-col">
                <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                  <div class="font-medium">Giảng viên được chọn: <span id="selectedCount" class="font-semibold">0</span></div>
                  <button id="btnCommit"
                          class="px-4 py-2 rounded-lg bg-blue-400 text-white text-sm disabled:opacity-60 disabled:cursor-not-allowed"
                          disabled>Thêm giảng viên</button>
                </div>
                <div class="p-4 text-sm text-slate-500">Chọn checkbox ở danh sách bên trái sau đó nhấn "Thêm giảng viên".</div>
              </div>
            </div>
          </section>
        </main>
      </div>
    </div>

    <!-- Toast -->
    <div id="toastHost" class="fixed bottom-4 right-4 z-50 space-y-2 pointer-events-none"></div>

    <!-- Excel preview modal (hidden by default) -->
<div id="excelModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
  <!-- Overlay mờ nền -->
  <div id="excelModalOverlay" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

  <!-- Khung modal -->
  <div class="relative mx-auto mt-10 w-full max-w-6xl px-4">
    <div id="excelModalCard"
      class="bg-gradient-to-r from-white/80 via-slate-50 to-white/80 rounded-2xl shadow-2xl ring-1 ring-slate-200 transform transition-all duration-300 scale-95 opacity-0 overflow-hidden flex flex-col max-h-[90vh]">
      
      <!-- Header -->
      <div class="flex flex-col sm:flex-row gap-4 p-5 border-b border-slate-100 bg-white/70 backdrop-blur">
        <!-- Icon -->
        <div class="flex-shrink-0 flex justify-center sm:justify-start">
          <div
            class="h-14 w-14 rounded-xl bg-gradient-to-br from-green-400 to-emerald-600 grid place-items-center text-white shadow-md">
            <i class="ph ph-spreadsheet text-2xl"></i>
          </div>
        </div>

        <!-- Nội dung header -->
        <div class="flex-1 min-w-0">
          <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex-1">
              <div class="flex flex-wrap items-center gap-3">
                <h3 class="text-lg font-semibold text-slate-800 truncate">Xem trước file Excel</h3>
                <span id="excelFileBadge"
                  class="inline-flex items-center gap-2 text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded whitespace-nowrap">
                  <i class="ph ph-file-text text-sm"></i>
                  <span id="excelSheetName" class="font-medium truncate"></span>
                </span>
              </div>

              <div class="flex flex-wrap items-center gap-3 mt-2">
                <div id="excelMeta" class="text-xs text-slate-500">&nbsp;</div>

                <label class="inline-flex items-center gap-2 text-xs text-slate-600 whitespace-nowrap">
                  <input type="checkbox" id="excelUseHeader" class="rounded border-slate-300" checked>
                  Dùng hàng đầu tiên làm tiêu đề
                </label>

                <label class="inline-flex items-center gap-2 text-xs text-slate-600 whitespace-nowrap">
                  Hiển thị
                  <select id="excelRowsPerPage" class="ml-1 text-xs border rounded px-2 py-0.5">
                    <option value="20">20 hàng</option>
                    <option value="50">50 hàng</option>
                    <option value="100">100 hàng</option>
                    <option value="all">Tất cả</option>
                  </select>
                </label>
              </div>
            </div>

            <!-- Nút hành động -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
              <button id="excelImportBtn"
                class="flex-1 sm:flex-none px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700 shadow transition w-full sm:w-auto">
                Nhập
              </button>
              <button id="excelCloseBtn"
                class="flex-1 sm:flex-none px-3 py-1.5 rounded-lg bg-white border text-sm hover:bg-slate-50 transition w-full sm:w-auto">
                Đóng
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Vùng preview -->
      <div class="flex-1 overflow-auto bg-white p-3">
        <div id="excelPreviewContainer"
          class="text-sm text-slate-600 min-w-full overflow-x-auto overflow-y-auto max-h-[70vh] whitespace-nowrap">
          <!-- preview injected here -->
        </div>
      </div>
    </div>
  </div>
</div>


    <script>
      // Sidebar
      const htmlEl=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const header=document.querySelector('header');
        const main=document.querySelector('main');
        if(c){
          htmlEl.classList.add('sidebar-collapsed');
        } else {
          htmlEl.classList.remove('sidebar-collapsed');
        }
      }
      document.getElementById('collapseBtn')?.addEventListener('click',()=>{
        const c=!htmlEl.classList.contains('sidebar-collapsed');
        setCollapsed(c); localStorage.setItem('assistant_sidebar', c?'1':'0');
      });
      document.getElementById('openSidebar')?.addEventListener('click',()=> sidebar.classList.toggle('-translate-x-full'));
      document.getElementById('gradToggle')?.addEventListener('click',()=>{
        const m=document.getElementById('gradMenu');
        m?.classList.toggle('open');
        const expanded = m?.classList.contains('open');
        const caret = document.querySelector('#gradToggle .ph.ph-caret-down');
        if (expanded) caret?.classList.add('rotate-180'); else caret?.classList.remove('rotate-180');
      });
      // Init theo breakpoint
      sidebar.classList.add('transition-transform','md:translate-x-0');
      if (window.matchMedia('(min-width:768px)').matches) sidebar.classList.remove('-translate-x-full');
      if (localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);

      // Toast
      function pushToast(msg){
        const host=document.getElementById('toastHost');
        const el=document.createElement('div');
        el.className='px-4 py-2 rounded-lg bg-slate-800 text-white text-sm shadow pointer-events-auto';
        el.textContent=msg;
        host.appendChild(el);
        setTimeout(()=>{ el.style.opacity='0'; el.style.transform='translateY(4px)'; el.style.transition='all .25s'; }, 1800);
        setTimeout(()=> el.remove(), 2100);
      }

        // Excel file preview handling (SheetJS)
        const fileInputEl = document.getElementById('fileExcel');
        const excelModal = document.getElementById('excelModal');
        const excelOverlay = document.getElementById('excelModalOverlay');
        const excelCloseBtn = document.getElementById('excelCloseBtn');
        const excelSheetNameEl = document.getElementById('excelSheetName');
        const excelPreviewContainer = document.getElementById('excelPreviewContainer');
        const excelImportBtn = document.getElementById('excelImportBtn');
  const excelMetaEl = document.getElementById('excelMeta');
  const excelModalCard = document.getElementById('excelModalCard');
  const excelUseHeaderEl = document.getElementById('excelUseHeader');
  const excelRowsPerPageEl = document.getElementById('excelRowsPerPage');
  let excelCurrentRows = null;
  let excelUseHeader = true;
  let excelRowsPerPage = 20;
        const btnUploadEl = document.getElementById('btnUpload');

        // Open file dialog when clicking Tải lên
        btnUploadEl?.addEventListener('click', ()=> fileInputEl?.click());

        function closeExcelModal(){
          if(!excelModal) return;
          excelModalCard?.classList.remove('scale-100','opacity-100');
          excelModalCard?.classList.add('scale-95','opacity-0');
          setTimeout(()=>{ excelModal.classList.add('hidden'); }, 220);
          excelSheetNameEl.textContent = '';
          excelMetaEl.textContent = '';
          excelPreviewContainer.innerHTML = '';
        }

        function buildPreviewTable(rows, useHeader, rowsPerPage){
            if(!rows || !rows.length) return '<div class="text-slate-500">(Không có dữ liệu trong sheet)</div>';
            const fullCols = rows.reduce((m,r)=>Math.max(m, r.length||0), 0);
            const maxCols = Math.min(fullCols, 50); // cap extreme cols
            const headerRow = useHeader ? rows[0] || [] : null;
            const dataRows = useHeader ? rows.slice(1) : rows.slice(0);
            const maxRows = (rowsPerPage === 'all') ? dataRows.length : Math.min(parseInt(rowsPerPage,10)||20, dataRows.length);
            const slice = dataRows.slice(0, maxRows);

            // responsive container with horizontal scroll for many columns
            let html = '<div class="overflow-auto"><table class="min-w-max w-full border-collapse text-sm table-auto">';

            // header
            html += '<thead>';
            if(headerRow){
              html += '<tr>';
              for(let c=0;c<maxCols;c++){
                const v = headerRow[c] !== undefined && headerRow[c] !== null ? String(headerRow[c]) : `Col ${c+1}`;
                html += `<th class="border px-3 py-2 text-left bg-white" style="position:sticky; top:0; z-index:5">${escapeHtml(v)}</th>`;
              }
              html += '</tr>';
            } else {
              html += '<tr>';
              for(let c=0;c<maxCols;c++) html += `<th class="border px-3 py-2 text-left bg-white" style="position:sticky; top:0; z-index:5">C${c+1}</th>`;
              html += '</tr>';
            }
            html += '</thead>';

            // body
            html += '<tbody>';
            for(let r=0;r<slice.length;r++){
              const row = slice[r] || [];
              html += '<tr class="hover:bg-slate-50">';
              for(let c=0;c<maxCols;c++){
                const v = row[c] !== undefined && row[c] !== null ? String(row[c]) : '';
                html += `<td class="border px-3 py-2 align-top whitespace-pre max-w-[280px] break-words">${escapeHtml(v)}</td>`;
              }
              html += '</tr>';
            }
            html += '</tbody></table>';
            if(dataRows.length>maxRows) html += `<div class="text-xs text-slate-500 mt-2">Hiển thị ${maxRows} hàng đầu tiên trên ${dataRows.length} hàng.</div>`;
            html += '</div>';
            return html;
          }

        function escapeHtml(s){ return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

        function renderPreview(){
          if(!excelCurrentRows) return;
          const useHeader = excelUseHeader;
          const rowsPerPageVal = excelRowsPerPage;
          excelPreviewContainer.innerHTML = buildPreviewTable(excelCurrentRows, useHeader, rowsPerPageVal);
        }

        function showExcelModal(sheetName, rows){
          if(!excelModal) return;
          const rowCount = rows?.length || 0;
          const colCount = rows?.reduce ? rows.reduce((m,r)=>Math.max(m, r.length||0), 0) : 0;
          excelSheetNameEl.textContent = sheetName || '';
          excelMetaEl.textContent = `${rowCount} hàng • ${colCount} cột`;
          // store current
          excelCurrentRows = rows;
          // default heuristics: if first row looks like header (all strings), keep header checked
          if(rows && rows.length>1){
            const first = rows[0];
            const looksLikeHeader = first.every && first.every(v=> typeof v === 'string' && v.trim().length>0);
            excelUseHeader = looksLikeHeader;
          } else {
            excelUseHeader = true;
          }
          excelUseHeaderEl.checked = !!excelUseHeader;
          excelRowsPerPage = 20;
          excelRowsPerPageEl.value = String(excelRowsPerPage);
          renderPreview();
          excelModal.classList.remove('hidden');
          // animate card in
          setTimeout(()=>{
            excelModalCard?.classList.remove('scale-95','opacity-0');
            excelModalCard?.classList.add('scale-100','opacity-100');
          }, 10);
          // focus for accessibility
          excelModalCard.querySelector('button')?.focus();
        }

        excelCloseBtn?.addEventListener('click', closeExcelModal);
        excelOverlay?.addEventListener('click', closeExcelModal);
        document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closeExcelModal(); });

        // Excel import button placeholder (you can hook this to upload flow)
        excelImportBtn?.addEventListener('click', ()=>{
          pushToast('Chức năng nhập file chưa được cấu hình ở phía server');
          closeExcelModal();
        });

        // controls: header checkbox and rows-per-page select
        excelUseHeaderEl?.addEventListener('change', (e)=>{ excelUseHeader = !!e.target.checked; renderPreview(); });
        excelRowsPerPageEl?.addEventListener('change', (e)=>{ excelRowsPerPage = e.target.value; renderPreview(); });

        // Read file and parse
        fileInputEl?.addEventListener('change', (ev)=>{
          const f = ev.target.files && ev.target.files[0];
          if(!f) return;
          const name = (f.name||'').toLowerCase();
          if(!(name.endsWith('.xls') || name.endsWith('.xlsx'))){ pushToast('Vui lòng chọn file Excel (.xls hoặc .xlsx)'); return; }
          const reader = new FileReader();
          reader.onload = (e) => {
            try{
              const data = new Uint8Array(e.target.result);
              const wb = XLSX.read(data, { type: 'array' });
              const first = wb.SheetNames && wb.SheetNames[0];
              const sheet = wb.Sheets[first];
              const rows = XLSX.utils.sheet_to_json(sheet, { header: 1 });
              showExcelModal(first, rows);
            }catch(err){ console.error(err); pushToast('Không thể đọc file Excel'); }
          };
          reader.onerror = ()=> pushToast('Lỗi khi đọc file');
          reader.readAsArrayBuffer(f);
        });

      // Chọn/Thêm giảng viên: checkbox + nút commit (đơn giản)
      const qInput=document.getElementById('qInput');
      const supTbody=document.getElementById('supTbody');
      const btnCommit=document.getElementById('btnCommit');
      const selectAllSup=document.getElementById('selectAllSup');
      const selectedCountEl=document.getElementById('selectedCount');

      const selected=new Set(); // store emails

      function updateBtn(){
        const n=selected.size;
        btnCommit.disabled = n===0;
        btnCommit.textContent = `Thêm giảng viên${n>0? ` (${n})`:''}`;
        btnCommit.classList.toggle('bg-blue-400', n===0);
        btnCommit.classList.toggle('bg-blue-600', n>0);
        btnCommit.classList.toggle('hover:bg-blue-700', n>0);
        if (selectedCountEl) selectedCountEl.textContent = String(n);
      }

      function bindSupList(){
        supTbody.querySelectorAll('input[type=checkbox][data-email]').forEach(cb=>{
          cb.addEventListener('change', ()=>{
            const email=cb.dataset.email;
            if(cb.checked) selected.add(email); else selected.delete(email);
            updateBtn(); syncSelectAll();
          });
        });
        syncSelectAll();
      }

      function syncSelectAll(){
        const boxes=[...supTbody.querySelectorAll('tr:not([style*="display: none"]) input[type=checkbox][data-email]')];
        const all= boxes.length>0 && boxes.every(cb=>cb.checked);
        selectAllSup.checked = all;
        selectAllSup.indeterminate = !all && boxes.some(cb=>cb.checked);
      }
      selectAllSup?.addEventListener('change', ()=>{
        const target=selectAllSup.checked;
        supTbody.querySelectorAll('tr:not([style*="display: none"]) input[type=checkbox][data-email]').forEach(cb=>{
          cb.checked=target;
          const email=cb.dataset.email;
          if(target) selected.add(email); else selected.delete(email);
        });
        updateBtn(); syncSelectAll();
      });

      function applySearch(){
        const q=(qInput?.value||'').toLowerCase();
        supTbody.querySelectorAll('tr').forEach(tr=>{
          const txt=tr.innerText.toLowerCase();
          tr.style.display = txt.includes(q) ? '' : 'none';
        });
        syncSelectAll();
      }
      qInput?.addEventListener('input', applySearch);

      // Gọi API thêm danh sách GV vào đợt
      btnCommit?.addEventListener('click', async ()=>{
        if(!selected.size) return;
        const termId = {{ $projectTerm->id ?? ($round->id ?? ($term->id ?? 'null')) }};
        if(!termId){ pushToast('Thiếu thông tin đợt đồ án'); return; }
        const supervisors=[...selected].filter(Boolean).map(email => {
          const input = supTbody.querySelector(`input[data-max-for="${email}"]`);
          const max = input ? parseInt(input.value, 10) : null;
          return { email: email, max_students: Number.isNaN(max) ? null : max };
        });

        const prev=btnCommit.textContent;
        btnCommit.disabled=true; btnCommit.textContent='Đang thêm...';
        try{
          const res = await fetch(`{{ route('web.assistant.supervisors.bulk_store', [], false) }}`, {
            method:'POST',
            headers:{
              'Content-Type':'application/json',
              'Accept':'application/json',
              'X-CSRF-TOKEN': `{{ csrf_token() }}`
            },
            body: JSON.stringify({ project_term_id: termId, supervisors })
          });
          const data = await res.json().catch(()=>({}));
          if(!res.ok){
            pushToast(data?.message||'Thêm thất bại');
          }else{
            pushToast(`Đã thêm ${data.added||0} GV, bỏ qua ${data.skipped||0}`);
            selected.clear();
            supTbody.querySelectorAll('input[type=checkbox][data-email]').forEach(cb=> cb.checked=false);
            syncSelectAll();
            updateBtn();
            // Reload so server-side changes are reflected (short delay to allow toast to be seen)
            setTimeout(() => { location.reload(); }, 700);
          }
        }catch(e){
          pushToast('Lỗi mạng khi thêm giảng viên');
        }finally{
          btnCommit.disabled=false; btnCommit.textContent=prev; updateBtn();
        }
      });

      // Init
      bindSupList();
      updateBtn();
      applySearch();

      // Mở sẵn submenu "Học phần tốt nghiệp" + đánh dấu mục "Đồ án tốt nghiệp"
      document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('gradToggle');
        const menu = document.getElementById('gradMenu');
        const caret = btn?.querySelector('.ph.ph-caret-down');
        // Open submenu on load
        menu?.classList.add('open');
        btn?.setAttribute('aria-expanded', 'true');
        caret?.classList.add('transition-transform','rotate-180');
        // Ensure highlight for "Đồ án tốt nghiệp"
        const thesisLink = menu?.querySelector('a[href*="rounds"]');
        thesisLink?.classList.add('bg-slate-100','font-semibold');
        // Also run the global nav-highlighting so sidebar reflects current page
        function setActiveNav() {
          const currentPath = location.pathname.replace(/\/$/, '');
          document.querySelectorAll('aside nav a').forEach(a => {
            if (a.hasAttribute('aria-current')) { a.classList.add('bg-slate-100','font-semibold','text-slate-900'); return; }
            const href = a.getAttribute('href') || '';
            if (!href || href === '#') { a.classList.remove('bg-slate-100','font-semibold','text-slate-900'); return; }
            try {
              const url = new URL(href, location.origin);
              const path = url.pathname.replace(/\/$/, '');
              const active = (path === currentPath) || (path !== '' && currentPath.startsWith(path + '/')) || (path !== '' && currentPath === path);
              a.classList.toggle('bg-slate-100', active);
              a.classList.toggle('font-semibold', active);
              a.classList.toggle('text-slate-900', active);
            } catch (e) {
              const last = href.split('/').filter(Boolean).pop() || '';
              const curLast = currentPath.split('/').filter(Boolean).pop() || '';
              const active = last && last === curLast;
              a.classList.toggle('bg-slate-100', active);
              a.classList.toggle('font-semibold', active);
              a.classList.toggle('text-slate-900', active);
            }
          });
        }
        setActiveNav();
        window.addEventListener('popstate', setActiveNav);
      });

      // profile dropdown
      const profileBtn=document.getElementById('profileBtn');
      const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    </script>
  </body>
</html>