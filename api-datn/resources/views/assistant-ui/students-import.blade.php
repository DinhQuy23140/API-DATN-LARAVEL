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
    body {
      font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
    }

    .sidebar-collapsed .sidebar-label {
      display: none;
    }

    .sidebar-collapsed .sidebar {
      width: 72px;
    }

    .sidebar {
      width: 260px;
    }

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
    $avatarUrl = $user->avatar_url
      ?? $user->profile_photo_url
      ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
  @endphp
  <div class="flex min-h-screen">
    <aside id="sidebar"
      class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-transform transform -translate-x-full md:translate-x-0">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-buildings"></i>
        </div>
        <div class="sidebar-label">
          <div class="font-semibold">Assistant</div>
          <div class="text-xs text-slate-500">Quản trị khoa</div>
        </div>
      </div>
      <nav class="flex-1 overflow-y-auto p-3">
        <a href="dashboard.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i
            class="ph ph-gauge"></i><span class="sidebar-label">Bảng điều khiển</span></a>
        <a href="manage-departments.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i
            class="ph ph-buildings"></i><span class="sidebar-label">Bộ môn</span></a>
        <a href="manage-majors.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i
            class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
        <a href="manage-staff.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i
            class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
        <a href="assign-head.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i
            class="ph ph-user-switch"></i><span class="sidebar-label">Gán trưởng bộ môn</span></a>

          <div class="graduation-item">
            <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button bg-slate-100 font-semibold">
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
        <button id="toggleSidebar"
          class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i
            class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
      </div>
    </aside>

    <div class="flex-1">
      <header
        class="fixed left-0 md:left-[260px] right-0 top-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-20">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i
              class="ph ph-list"></i></button>
          <a href="{{ url()->previous() ?: route('web.assistant.rounds') }}"
            class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50"
            title="Quay lại">
            <i class="ph ph-arrow-left"></i><span class="text-sm">Quay lại</span>
          </a>
          <button type="button" onclick="history.back()" class="sm:hidden p-2 rounded-lg hover:bg-slate-100"
            aria-label="Quay lại">
            <i class="ph ph-arrow-left"></i>
          </button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Import danh sách sinh viên</h1>
            <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp
              / Import sinh viên</nav>
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
          <div id="profileMenu"
            class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông
              tin</a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
              class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i
                class="ph ph-sign-out"></i>Đăng xuất</a>
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </header>

      <main class="pt-20 px-4 md:px-6 pb-10 md:pl-[284px]">
        <div class="w-full max-w-none space-y-6">
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <h2 class="font-semibold">Chọn file Excel để import</h2>
            <div class="mt-3">
              <input type="file" accept=".xlsx,.xls"
                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
              <p class="text-xs text-slate-500 mt-2">Mẫu cột: MSSV, Họ tên, Email, Ngành, Điểm TB, Trạng thái.</p>
            </div>
            <div class="mt-4 flex items-center gap-2">
              <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Tải lên</button>
              <a class="px-4 py-2 rounded-lg border hover:bg-slate-50" href="#">Tải mẫu</a>
            </div>
          </section>

          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <h2 class="font-semibold">Thêm thủ công</h2>

            <div class="mt-3 grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Cột trái: Tìm kiếm + danh sách tất cả SV (checkbox) -->
              <div class="rounded-xl border border-slate-200">
                <div class="p-4 border-b border-slate-200">
                  <div class="relative w-full">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input id="manualSearch"
                      class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600"
                      placeholder="Tìm theo MSSV / Họ tên / Email" />
                  </div>
                </div>
                <div class="max-h-96 overflow-auto">
                  <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-slate-50 border-b border-slate-200">
                      <tr class="text-left text-slate-600">
                        <th class="py-2 px-3 w-10">
                          <input type="checkbox" id="selectAllCandidates" class="rounded border-slate-300" />
                        </th>
                        <th class="py-2 px-3">MSSV</th>
                        <th class="py-2 px-3">Họ tên</th>
                        <th class="py-2 px-3">Email</th>
                        <th class="py-2 px-3">Ngành</th>
                      </tr>
                    </thead>
                    <tbody id="manualTbody">
                      @php
                        // Chuẩn hoá dữ liệu từ controller (ví dụ: getStudentNotInProjectTerm)
                        $items = $items ?? $serverCandidates ?? [];
                      @endphp
                      @if(!empty($items) && count($items))
                        @foreach($items as $s)
                          @php
                            $sid   = $s->student_code ?? $s->code ?? $s->mssv ?? (string)($s->id ?? '');
                            $sname = optional($s->user)->fullname ?? $s->fullname ?? $s->name ?? '';
                            $semail= optional($s->user)->email ?? $s->email ?? '';
                            $smajor= optional($s->major)->name ?? ($s->major ?? 'CNTT');
                          @endphp
                          <tr class="hover:bg-slate-50">
                            <td class="py-2 px-3">
                              <input type="checkbox"
                                     class="rounded border-slate-300"
                                     data-id="{{ $sid }}"
                                     data-name="{{ $sname }}"
                                     data-email="{{ $semail }}"
                                     data-major="{{ $smajor }}">
                            </td>
                            <td class="py-2 px-3 font-medium">{{ $sid }}</td>
                            <td class="py-2 px-3">{{ $sname }}</td>
                            <td class="py-2 px-3">{{ $semail ?: '-' }}</td>
                            <td class="py-2 px-3">{{ $smajor }}</td>
                          </tr>
                        @endforeach
                      @else
                        <tr><td colspan="5" class="py-4 px-3 text-slate-500">Không có dữ liệu.</td></tr>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Cột phải: Bảng SV đã chọn + nút Thêm -->
              <div class="rounded-xl border border-slate-200 flex flex-col">
                <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                  <div class="font-medium">Sinh viên đã chọn</div>
                  <button id="btnAddSelected" disabled
                    class="px-4 py-2 rounded-lg bg-blue-400 text-white text-sm disabled:opacity-60 disabled:cursor-not-allowed hover:bg-blue-500">
                    Thêm sinh viên (0)
                  </button>
                </div>
                <div class="flex-1 overflow-auto">
                  <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-slate-50 border-b border-slate-200">
                      <tr>
                        <th class="text-left py-2 px-3 font-medium text-slate-600">MSSV</th>
                        <th class="text-left py-2 px-3 font-medium text-slate-600">Họ tên</th>
                        <th class="text-left py-2 px-3 font-medium text-slate-600">Email</th>
                        <th class="text-left py-2 px-3 font-medium text-slate-600">Ngành</th>
                        <th class="text-right py-2 px-3 font-medium text-slate-600">Thao tác</th>
                      </tr>
                    </thead>
                    <tbody id="selectedTbody">
                      <tr>
                        <td colspan="5" class="py-4 px-3 text-slate-500">Chưa chọn sinh viên nào.</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>
  <script>
    const html = document.documentElement, sidebar = document.getElementById('sidebar');
    function setCollapsed(c) {
      const h = document.querySelector('header');
      const m = document.querySelector('main');
      if (c) {
        html.classList.add('sidebar-collapsed');
        h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]');
        m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]');
      } else {
        html.classList.remove('sidebar-collapsed');
        h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]');
        m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]');
      }
    }
    document.getElementById('toggleSidebar')?.addEventListener('click', () => { const c = !html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('assistant_sidebar', '' + (c ? 1 : 0)); });
    document.getElementById('openSidebar')?.addEventListener('click', () => sidebar.classList.toggle('-translate-x-full'));
    if (localStorage.getItem('assistant_sidebar') === '1') setCollapsed(true);
    // Khởi tạo đúng theo breakpoint (mobile ẩn, md+ hiện)
    sidebar.classList.add('transition-transform', 'transform', '-translate-x-full', 'md:translate-x-0');

    // profile dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    profileBtn?.addEventListener('click', () => profileMenu.classList.toggle('hidden'));
    document.addEventListener('click', (e) => { if (!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

    // auto active nav highlight
    (function () {
      const current = location.pathname.split('/').pop();
      document.querySelectorAll('aside nav a').forEach(a => {
        // Bỏ qua link đã được đánh dấu thủ công
        if (a.hasAttribute('aria-current')) return;
        const href = a.getAttribute('href') || '';
        const active = href.endsWith(current);
        a.classList.toggle('bg-slate-100', active);
        a.classList.toggle('font-semibold', active);
        a.classList.toggle('text-slate-900', active);
      });
    })();

    // Toast helper
    function toast(msg) {
      let host = document.getElementById('toastHost');
      if (!host) { host = document.createElement('div'); host.id = 'toastHost'; host.className = 'fixed bottom-4 right-4 z-50 space-y-2'; document.body.appendChild(host); }
      const el = document.createElement('div');
      el.className = 'px-4 py-2 rounded-lg bg-slate-800 text-white text-sm shadow';
      el.textContent = msg;
      host.appendChild(el);
      setTimeout(() => { el.style.opacity = '0'; el.style.transform = 'translateY(4px)'; el.style.transition = 'all .25s'; }, 2000);
      setTimeout(() => el.remove(), 2300);
    }

    (function () {
      // Elements
      const searchInput = document.getElementById('manualSearch');
      const manualTbody = document.getElementById('manualTbody');
      const selectAllEl = document.getElementById('selectAllCandidates');
      const selectedTbody = document.getElementById('selectedTbody');
      const btnAddSelected = document.getElementById('btnAddSelected');

      // State chọn
      const selected = new Set();
      const selectedData = new Map(); // id -> {id,name,email,major}
 
       function updateAddBtn() {
         const n = selected.size;
         btnAddSelected.disabled = n === 0;
         btnAddSelected.textContent = `Thêm sinh viên (${n})`;
         btnAddSelected.classList.toggle('bg-blue-400', n === 0);
         btnAddSelected.classList.toggle('bg-blue-600', n > 0);
         btnAddSelected.classList.toggle('hover:bg-blue-700', n > 0);
       }
 
       function renderSelected() {
         if (selected.size === 0) {
           selectedTbody.innerHTML = `<tr><td colspan="5" class="py-4 px-3 text-slate-500">Chưa chọn sinh viên nào.</td></tr>`;
           updateAddBtn();
           return;
         }
        const rows = [...selected].map(id => {
          const s = selectedData.get(id);
          if (!s) return '';
           return `<tr class="border-b">
             <td class="py-2 px-3 font-medium">${s.id}</td>
             <td class="py-2 px-3">${s.name}</td>
             <td class="py-2 px-3">${s.email || '-'}</td>
             <td class="py-2 px-3">${s.major || ''}</td>
             <td class="py-2 px-3 text-right">
               <button class="px-2 py-1 text-xs rounded border border-slate-200 hover:bg-slate-50" data-unselect="${s.id}">Bỏ chọn</button>
             </td>
           </tr>`;
         }).join('');
         selectedTbody.innerHTML = rows;
         selectedTbody.querySelectorAll('[data-unselect]').forEach(b => {
           b.addEventListener('click', () => {
             const id = b.getAttribute('data-unselect');
             selected.delete(id);
            selectedData.delete(id);
             renderSelected();
             // sync checkbox trong danh sách trái
             const cb = manualTbody.querySelector(`input[type=checkbox][data-id="${id}"]`);
             if (cb) cb.checked = false;
             updateAddBtn();
           });
         });
         updateAddBtn();
       }
 
      // Gán events cho checkbox đã render sẵn bằng Blade
      function bindManualListEvents() {
        manualTbody.querySelectorAll('input[type=checkbox][data-id]').forEach(cb => {
          cb.addEventListener('change', () => {
            const id = cb.dataset.id;
            if (cb.checked) {
              selected.add(id);
              selectedData.set(id, {
                id,
                name: cb.dataset.name || '',
                email: cb.dataset.email || '',
                major: cb.dataset.major || ''
              });
            } else {
              selected.delete(id);
              selectedData.delete(id);
            }
            renderSelected();
            updateAddBtn();
            syncSelectAllState();
          });
        });
        syncSelectAllState();
      }
 
       function syncSelectAllState() {
         if (!selectAllEl) return;
         const boxes = [...manualTbody.querySelectorAll('tr:not([style*="display: none"]) input[type=checkbox][data-id]:not([disabled])')];
         const allChecked = boxes.length > 0 && boxes.every(cb => cb.checked);
         selectAllEl.checked = allChecked;
         selectAllEl.indeterminate = !allChecked && boxes.some(cb => cb.checked);
       }
       selectAllEl?.addEventListener('change', () => {
         const target = selectAllEl.checked;
         manualTbody.querySelectorAll('tr:not([style*="display: none"]) input[type=checkbox][data-id]:not([disabled])').forEach(cb => {
           cb.checked = target;
           const id = cb.getAttribute('data-id');
           if (target) selected.add(id); else selected.delete(id);
          if (target) {
            selectedData.set(id, {
              id,
              name: cb.dataset.name || '',
              email: cb.dataset.email || '',
              major: cb.dataset.major || ''
            });
          } else {
            selectedData.delete(id);
          }
         });
         renderSelected();
         updateAddBtn();
         syncSelectAllState();
       });
 
      // GỌI API: chỉ giữ 1 listener (xóa listener cũ reset UI)
      btnAddSelected?.addEventListener('click', async () => {
        if (selected.size === 0) return;
        const termId = {{ $projectTerm->id ?? ($round->id ?? ($term->id ?? 'null')) }};
        if (!termId) { toast('Thiếu thông tin đợt đồ án'); return; }
        const students = [...selectedData.values()].map(s => s.id); // id hoặc student_code

         // UI loading
        const prevText = btnAddSelected.textContent;
        btnAddSelected.disabled = true;
        btnAddSelected.textContent = 'Đang thêm...';

        try {
          const res = await fetch(`{{ route('web.assistant.batch_students.bulk_store') }}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': `{{ csrf_token() }}`
            },
            body: JSON.stringify({
              project_term_id: termId,
              students,
              status: 'actived' // gửi đúng giá trị yêu cầu
            })
          });
          const data = await res.json().catch(()=>({}));
          if (!res.ok) {
            toast(data?.message || 'Thêm thất bại');
          } else {
            toast(`Đã thêm ${data.added||0} SV, bỏ qua ${data.skipped||0}`);
            // Reset chọn
            selected.clear();
            selectedData.clear();
            renderSelected();
            manualTbody.querySelectorAll('input[type=checkbox][data-id]').forEach(cb => cb.checked = false);
            syncSelectAllState();
          }
        } catch (e) {
          toast('Lỗi mạng khi thêm sinh viên');
        } finally {
          btnAddSelected.disabled = false;
          btnAddSelected.textContent = prevText;
          updateAddBtn();
        }
      });
 
      // Search ẩn/hiện hàng theo nội dung
      function applySearch() {
        const q = (searchInput?.value || '').toLowerCase();
        manualTbody.querySelectorAll('tr').forEach(tr => {
          const txt = tr.innerText.toLowerCase();
          tr.style.display = txt.includes(q) ? '' : 'none';
        });
        syncSelectAllState();
      }
      searchInput?.addEventListener('input', applySearch);
 
       // Initial render
      renderSelected();
      bindManualListEvents();
      applySearch();
     })();

    // Mở sẵn submenu "Học phần tốt nghiệp"
    document.addEventListener('DOMContentLoaded', () => {
      const wrap = document.querySelector('.graduation-item');
      if (!wrap) return;
      const toggleBtn = wrap.querySelector('.toggle-button');
      const submenu = wrap.querySelector('.submenu');
      const caret = wrap.querySelector('.ph.ph-caret-down');

      if (submenu && submenu.classList.contains('hidden')) submenu.classList.remove('hidden');
      if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
      caret?.classList.add('transition-transform', 'rotate-180');

      toggleBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        submenu?.classList.toggle('hidden');
        const expanded = !submenu?.classList.contains('hidden');
        toggleBtn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        caret?.classList.toggle('rotate-180', expanded);
      });
    });
  </script>
</body>

</html>