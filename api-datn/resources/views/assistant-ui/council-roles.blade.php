<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Phân công vai trò hội đồng</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <style>
    body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial}
    .sidebar{width:260px}
  </style>
  <!-- CSRF for AJAX -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
  <div class="flex min-h-screen">
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
          <a href="{{ route('web.assistant.assign_head') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Phân trưởng bộ môn</span></a>

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
          <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
        </div>
      </aside>

    <div class="flex-1 h-screen overflow-hidden flex flex-col md:pl-[260px]">
      <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6">
        <div class="flex-1">
          <h1 class="text-lg md:text-xl font-semibold">Phân công vai trò hội đồng</h1>
          <div class="text-xs text-slate-500 mt-0.5">Đợt: 2025-Q3</div>
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

      <main class="flex-1 overflow-y-auto p-4 md:p-6">
        <!-- Filters row: department select moved here -->
        <div class="mb-4 flex items-center justify-end">
          <div class="w-full md:w-64">
            <select id="departmentFilter" class="w-full pl-3 pr-8 py-2 rounded-lg border border-slate-200 text-sm">
              <option value="">-- Tất cả bộ môn --</option>
              @isset($departments)
                @foreach($departments as $department)
                  <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
              @else
                <option value="CNTT">Công nghệ thông tin</option>
                <option value="CoKhi">Cơ khí</option>
              @endisset
            </select>
          </div>
        </div>

        <div class="w-full flex flex-col md:flex-row gap-6">
          <!-- Trái: Danh sách hội đồng (50%) -->
          <section class="w-full md:w-1/2 bg-white border rounded-xl">
            <div class="p-4 border-b">
              <div class="flex items-center justify-between">
                <h2 class="font-semibold">Danh sách hội đồng</h2>
                  <div class="relative flex items-center gap-3">
                    <div class="relative">
                      <input id="q" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo mã/tên hội đồng" />
                      <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                    <!-- departmentFilter moved to top filter row; removed duplicate here -->
                  </div>
              </div>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500 border-b">
                    <th class="py-3 px-4">Mã</th>
                    <th class="py-3 px-4">Tên</th>
                    <th class="py-3 px-4">Ngày</th>
                    <th class="py-3 px-4">Phòng</th>
                    <th class="py-3 px-4">Số thành viên</th>
                  </tr>
                </thead>
                <tbody id="councilRows">
                  @foreach ($councils as $council)
                    @php $department_id = $council->department->id ?? ''; @endphp
                    <tr class="hover:bg-slate-50 cursor-pointer" data-id="{{ $council->id }}" data-department-id="{{ $department_id }}">
                      <td class="py-3 px-4 font-medium">{{ $council->code }}</td>
                      <td class="py-3 px-4">{{ $council->name }}</td>
                      <td class="py-3 px-4">{{ $council->date }}</td>
                      <td class="py-3 px-4">{{ $council->address?? 'N/A' }}</td>
                      <td class="py-3 px-4" data-member-count>{{ $council->council_members->count() ?? "Chưa có thành viên" }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </section>

          <!-- Phải: Panel + 5 select vai trò (phần còn lại) -->
          <section class="w-full md:flex-1 bg-white border rounded-xl p-4" id="detailPanel">
            <div class="flex items-center justify-between">
              <h2 class="font-semibold">Thông tin hội đồng</h2>
              <button id="btnSave" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-sm">Lưu</button>
            </div>
            <div class="mt-3 text-sm" id="councilInfo">Chọn một hội đồng ở bảng bên trái để chỉnh sửa.</div>
            <div class="mt-3 text-sm" id="memberSummary" aria-live="polite"></div>

            <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
              <div>
                <label class="text-sm text-slate-700">Chủ tịch</label>
                <select id="sel_chairman" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                  <option value="">-- Chọn giảng viên --</option>
                  @foreach ($supervisors as $supervisor)
                    <option value="{{ $supervisor->id }}">{{ $supervisor->teacher->user->fullname }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="text-sm text-slate-700">Thư ký</label>
                <select id="sel_secretary" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                  <option value="">-- Chọn giảng viên --</option>
                  @foreach ($supervisors as $supervisor)
                    <option value="{{ $supervisor->id }}">{{ $supervisor->teacher->user->fullname }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="text-sm text-slate-700">Ủy viên 1</label>
                <select id="sel_member1" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                  <option value="">-- Chọn giảng viên --</option>
                  @foreach ($supervisors as $supervisor)
                    <option value="{{ $supervisor->id }}">{{ $supervisor->teacher->user->fullname }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="text-sm text-slate-700">Ủy viên 2</label>
                <select id="sel_member2" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                  <option value="">-- Chọn giảng viên --</option>
                  @foreach ($supervisors as $supervisor)
                    <option value="{{ $supervisor->id }}">{{ $supervisor->teacher->user->fullname }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="text-sm text-slate-700">Ủy viên 3</label>
                <select id="sel_member3" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                  <option value="">-- Chọn giảng viên --</option>
                  @foreach ($supervisors as $supervisor)
                    <option value="{{ $supervisor->id }}">{{ $supervisor->teacher->user->fullname }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>

  <script>
    const councils = @json($councils);
    const supervisors = @json($supervisors);

    const roleNumToKey = {5:'chairman', 4:'secretary', 3:'member1', 2:'member2', 1:'member3'};
    const keyToRoleNum = {chairman:5, secretary:4, member1:3, member2:2, member3:1};

    let councilMembers = [];
    const ROLES = ['chairman','secretary','member1','member2','member3'];

    const originalRoles = {};
    function initCouncilMembersFromCouncils(){
      councilMembers = [];
      (councils || []).forEach(c=>{
        const rolesObj = { chairman:null, secretary:null, member1:null, member2:null, member3:null };
        // Lấy danh sách member an toàn (array hoặc object)
        const cms = Array.isArray(c.council_members)
          ? c.council_members
          : (c.council_members && typeof c.council_members === 'object'
             ? Object.values(c.council_members)
             : []);
        (cms || []).forEach(cm => {
          const key = roleNumToKey[Number(cm.role)];
          if (!key) return;
          const name = cm?.supervisor?.teacher?.user?.fullname || cm?.supervisor_name || '';
          rolesObj[key] = { id: cm.supervisor_id, name };
          councilMembers.push({ council_id: c.id, role: key, supervisor_id: cm.supervisor_id });
        });
        c.roles = rolesObj;
        originalRoles[c.id] = {};
        ROLES.forEach(k => originalRoles[c.id][k] = rolesObj[k]?.id ? String(rolesObj[k].id) : '');
      });
    }
    initCouncilMembersFromCouncils();

    const detailEl = document.getElementById('councilInfo');
    const rowsEl = document.getElementById('councilRows');
    const sels = {
      chairman:  document.getElementById('sel_chairman'),
      secretary: document.getElementById('sel_secretary'),
      member1:   document.getElementById('sel_member1'),
      member2:   document.getElementById('sel_member2'),
      member3:   document.getElementById('sel_member3'),
    };
    let currentId = null;

    // helper: định dạng YYYY-MM-DD -> dd/mm/yyyy
    function formatVN(d) {
      if (!d) return '-';
      if (/^\d{4}-\d{2}-\d{2}$/.test(d)) return `${d.slice(8,10)}/${d.slice(5,7)}/${d.slice(0,4)}`;
      const dt = new Date(d);
      return isNaN(dt) ? (d || '-') : dt.toLocaleDateString('vi-VN');
    }

    // Đổ options giảng viên cho tất cả select
    function renderSupervisorOptions() {
      const getName = (s) => s?.fullname || s?.name || s?.teacher?.user?.fullname || `GV #${s?.id}`;
      const optsHtml = ['<option value="">-- Chọn giảng viên --</option>']
        .concat((supervisors || []).map(s => `<option value="${s.id}">${getName(s)}</option>`))
        .join('');
      Object.values(sels).forEach(sel => { if(sel) sel.innerHTML = optsHtml; });
    }
    renderSupervisorOptions(); 
    function renderMemberSummaryFromSelects() {
      const getNameById = (id)=>{
        const s = (supervisors || []).find(x => String(x.id) === String(id));
        return s?.fullname || s?.name || s?.teacher?.user?.fullname || '';
      };
      const items = [
        { label: 'Chủ tịch',  val: sels.chairman.value },
        { label: 'Thư ký',    val: sels.secretary.value },
        { label: 'Ủy viên 1', val: sels.member1.value },
        { label: 'Ủy viên 2', val: sels.member2.value },
        { label: 'Ủy viên 3', val: sels.member3.value },
      ].map(x => `<div><span class="text-slate-500">${x.label}:</span> ${x.val ? getNameById(x.val) : '-'}</div>`);
      const box = document.getElementById('memberSummary');
      if (box) box.innerHTML = `<div class="text-sm space-y-1">${items.join('')}</div>`;
    } 
    function fillPanel(c){
      if(!c) return;
      if(detailEl){
        detailEl.innerHTML = `
          <div class="text-sm">
            <div><span class="text-slate-500">Mã:</span> <span class="font-medium">${c.code||'-'}</span></div>
            <div><span class="text-slate-500">Tên:</span> <span class="font-medium">${c.name||'-'}</span></div>
            <div><span class="text-slate-500">Ngày:</span> ${formatVN(c.date)}</div>
            <div><span class="text-slate-500">Phòng:</span> ${c.address || '-'}</div>
          </div>
        `;
      }
      Object.values(sels).forEach(s => { if(s) s.value=''; });
      if(c.roles){
        if(c.roles.chairman && sels.chairman)  sels.chairman.value  = String(c.roles.chairman.id);
        if(c.roles.secretary && sels.secretary) sels.secretary.value = String(c.roles.secretary.id);
        if(c.roles.member1 && sels.member1)   sels.member1.value   = String(c.roles.member1.id);
        if(c.roles.member2 && sels.member2)   sels.member2.value   = String(c.roles.member2.id);
        if(c.roles.member3 && sels.member3)   sels.member3.value   = String(c.roles.member3.id);
      }
      if(!c.roles) c.roles = { chairman:null, secretary:null, member1:null, member2:null, member3:null };
      syncRoleSelects();
      renderMemberSummaryFromSelects();
    } 
    function syncRoleSelects(){
      const chosen = new Set(Object.values(sels).map(s=>s?.value).filter(Boolean));
      Object.values(sels).forEach(sel=>{
        if(!sel) return;
        Array.from(sel.options).forEach(opt=>{
          if(!opt.value) return;
          const dup = chosen.has(opt.value) && sel.value !== opt.value;
          opt.disabled = dup;
          opt.hidden = dup;
        });
      });
      renderMemberSummaryFromSelects();
    }
    Object.values(sels).forEach(s=> s?.addEventListener('change', syncRoleSelects)); 

    // Click row -> fill panel
    if (rowsEl) {
      rowsEl.querySelectorAll('tr[data-id]').forEach(tr=>{
        tr.addEventListener('click', ()=>{
          rowsEl.querySelectorAll('tr').forEach(r=> r.classList.remove('bg-slate-50'));
          tr.classList.add('bg-slate-50');
          const id = Number(tr.dataset.id);
          const c = (councils || []).find(x => Number(x.id) === id);
          currentId = c?.id || id;
          if(c) fillPanel(c);
        });
      });
    }

    // Mặc định chọn hàng đầu tiên khi load trang
    (function autoSelectFirst(){
      const firstRow = rowsEl?.querySelector('tr[data-id]');
      if (!firstRow) return;
      rowsEl.querySelectorAll('tr').forEach(r=> r.classList.remove('bg-slate-50'));
      firstRow.classList.add('bg-slate-50');
      const id = Number(firstRow.dataset.id);
      const c = (councils || []).find(x => Number(x.id) === id);
      currentId = c?.id || id;
      if (c) fillPanel(c);
    })();

    // Filtering by text + department
    const departmentFilter = document.getElementById('departmentFilter');
    function applyFilters(){
      const q = (document.getElementById('q')?.value||'').toLowerCase();
      const dept = (departmentFilter?.value||'').toString();
      rowsEl?.querySelectorAll('tr[data-id]').forEach(tr=>{
        const text = tr.innerText.toLowerCase();
        const rowDept = (tr.getAttribute('data-department-id')||'').toString();
        const matchesSearch = q === '' || text.includes(q);
        const matchesDept = dept === '' || rowDept === dept;
        tr.style.display = (matchesSearch && matchesDept) ? '' : 'none';
      });
      // auto-select first visible row if current selection is hidden or null
      const visible = Array.from(rowsEl?.querySelectorAll('tr[data-id]')||[]).find(r => r.style.display !== 'none');
      if(visible){
        const vid = Number(visible.dataset.id);
        if(!currentId || String(currentId) !== String(vid) || visible.classList.contains('bg-slate-50') === false){
          // simulate click to fill panel and update selection state
          visible.click();
        }
      }
    }
    document.getElementById('q')?.addEventListener('input', applyFilters);
    departmentFilter?.addEventListener('change', applyFilters);
    // run once to apply default filters
    applyFilters();
    // Lưu về server (PATCH members)
    document.getElementById('btnSave')?.addEventListener('click', async ()=>{
      if(!currentId){ alert('Vui lòng chọn một hội đồng.'); return; }
      const picked = Object.values(sels).map(s=>s.value).filter(Boolean);
      if(new Set(picked).size !== picked.length){ alert('Các vai trò không được trùng giảng viên.'); return; } 

      const payload = {
        chairman:  sels.chairman.value || null,
        secretary: sels.secretary.value || null,
        member1:   sels.member1.value || null,
        member2:   sels.member2.value || null,
        member3:   sels.member3.value || null,
      };
      const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
      const urlTpl = `{{ route('web.assistant.councils.members.update', ['council' => 0]) }}`;
      const url = urlTpl.replace('/0','/'+currentId);

      const res = await fetch(url, {
        method: 'PATCH',
        headers: {
          'Content-Type':'application/json',
          'Accept':'application/json',
          'X-CSRF-TOKEN': token,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(payload)
      });
      if (!res.ok) {
        const txt = await res.text().catch(()=> '');
        alert('Lưu thất bại' + (txt?': '+txt:''));
        return;
      }

      // Cập nhật local model để UI khớp ngay
      const c = (councils || []).find(x => Number(x.id) === Number(currentId));
      const getNameById = (id)=>{
        const s = (supervisors || []).find(x => String(x.id) === String(id));
        return s?.fullname || s?.name || s?.teacher?.user?.fullname || '';
      };
      if (c) {
        c.roles.chairman  = payload.chairman  ? { id:+payload.chairman,  name:getNameById(payload.chairman) }  : null;
        c.roles.secretary = payload.secretary ? { id:+payload.secretary, name:getNameById(payload.secretary) } : null;
        c.roles.member1   = payload.member1   ? { id:+payload.member1,   name:getNameById(payload.member1) }   : null;
        c.roles.member2   = payload.member2   ? { id:+payload.member2,   name:getNameById(payload.member2) }   : null;
        c.roles.member3   = payload.member3   ? { id:+payload.member3,   name:getNameById(payload.member3) }   : null;
      }
      originalRoles[currentId] = {
        chairman:  payload.chairman  || '',
        secretary: payload.secretary || '',
        member1:   payload.member1   || '',
        member2:   payload.member2   || '',
        member3:   payload.member3   || '',
      }; 

      // Cập nhật số thành viên bên bảng trái
      const c2 = (councils || []).find(x => Number(x.id) === Number(currentId));
      const count = ['chairman','secretary','member1','member2','member3'].reduce((n,k)=> n + (c2?.roles?.[k]?1:0), 0);
      const row = rowsEl?.querySelector(`tr[data-id="${currentId}"] [data-member-count]`);
      if(row) row.textContent = String(count); 

      alert('Đã lưu danh sách thành viên.');
    });

      // profile dropdown
      const profileBtn=document.getElementById('profileBtn');
      const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
  </script>
</body>
</html>