<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gán trưởng bộ môn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
          <a href="{{ route('web.assistant.assign_head') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold"><i class="ph ph-user-switch"></i><span class="sidebar-label">Gán trưởng bộ môn</span></a>

          <div class="graduation-item">
            <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button">
              <span class="flex items-center gap-3">
                <i class="ph ph-graduation-cap"></i>
                <span class="sidebar-label">Học phần tốt nghiệp</span>
              </span>
              <i class="ph ph-caret-down"></i>
            </div>
            <div class="submenu hidden pl-6">
              <a href="internship.html" class="block px-3 py-2 hover:bg-slate-100"><i class="ph ph-briefcase"></i> Thực tập tốt nghiệp</a>
              <a href="{{ route('web.assistant.rounds') }}" class="block px-3 py-2 hover:bg-slate-100"><i class="ph ph-calendar"></i> Đồ án tốt nghiệp</a>
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
              <h1 class="text-lg md:text-xl font-semibold">Gán quyền Trưởng bộ môn</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Gán quyền Trưởng bộ môn</nav>
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
          <div class="max-w-6xl mx-auto space-y-6">
          <!-- Assign form -->
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <h2 class="font-semibold">Gán quyền</h2>
            <div class="mt-4 grid sm:grid-cols-3 gap-4">
              <div>
                <label class="text-sm font-medium">Chọn giảng viên</label>
                <select id="selectTeacher"
                  class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                  @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" data-user-id="{{ $teacher->user->id }}">
                      {{ $teacher->user->fullname }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="text-sm font-medium">Chọn bộ môn</label>
                <select id="selectDepartment" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                  @foreach ($departmentsNotHead as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="flex items-end">
                <button id="btnAssignHead" class="w-full sm:w-auto inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><i class="ph ph-check"></i>Xác nhận gán quyền</button>
              </div>
            </div>
          </section>
          <!-- Current heads list -->
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between">
              <h2 class="font-semibold">Danh sách Trưởng bộ môn hiện tại</h2>
            </div>
            <div class="mt-4 overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500">
                    <th class="py-3 px-4 border-b w-10"><input id="chkAll" type="checkbox" class="h-4 w-4"/></th>
                    <th class="py-3 px-4 border-b">Mã bộ môn</th>
                    <th class="py-3 px-4 border-b">Bộ môn</th>
                    <th class="py-3 px-4 border-b">Trưởng bộ môn</th>
                    <th class="py-3 px-4 border-b">Email</th>
                    <th class="py-3 px-4 border-b text-right">Hành động</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($departments as $department)
                    <tr class="hover:bg-slate-50">
                      <td class="py-3 px-4"><input type="checkbox" class="rowChk h-4 w-4"/></td>
                      <td class="py-3 px-4">{{ $department->department->code }}</td>
                      <td class="py-3 px-4">{{ $department->department->name }}</td>
                      <td class="py-3 px-4">{{ $department->teacher->user->fullname }}</td>
                      <td class="py-3 px-4">{{ $department->teacher->user->email }}</td>
                      <td class="py-3 px-4 text-right">
                        <button
                          class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"
                          data-department-id="{{ $department->department->id }}"
                          data-role-id="{{ $department->id }}"
                          data-teacher-id="{{ $department->teacher->id }}"
                          onclick="removeHead(this)">
                          <i class="ph ph-user-minus"></i>
                          Bỏ quyền
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
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

  // checkbox all
  document.getElementById('chkAll')?.addEventListener('change', (e)=>{document.querySelectorAll('.rowChk').forEach(chk=> chk.checked = e.target.checked);});

  // remove head action (CALL route('departments.destroy'))
  async function removeHead(btn){
    const tr = btn.closest('tr');
    const deptId   = btn.dataset.departmentId;
    const roleId   = btn.dataset.roleId;     // id của bảng department_roles
    const teacherId= btn.dataset.teacherId;

    if(!roleId){
      alert('Thiếu role_id (department_roles.id)'); return;
    }
    if(!confirm('Bỏ quyền trưởng bộ môn khỏi người này?')) return;

    const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const oldHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> Đang xử lý...';

    try{
      // route đang bind DepartmentRole → cần truyền roleId
      const url = `{{ route('web.assistant.departmentRole.destroy', 0) }}`.replace('/0','/'+roleId);

      const res = await fetch(url, {
        method: 'DELETE',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': token,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'remove_head',
          department_id: deptId,
          teacher_id: teacherId
        })
      });

      const txt = await res.text();
      let data; try { data = JSON.parse(txt); } catch { data = { ok: res.ok }; }

      if(!res.ok || data.ok === false){
        alert(data?.message || 'Thao tác thất bại');
        return;
      }

      tr?.remove();
    } catch(err){
      alert('Lỗi mạng hoặc máy chủ');
      console.error(err);
    } finally {
      btn.disabled = false;
      btn.innerHTML = oldHtml;
    }
  }

  // Gán quyền trưởng bộ môn
document.getElementById('btnAssignHead')?.addEventListener('click', assignHead);

async function assignHead(e){
  e.preventDefault();
  const selTeacher = document.getElementById('selectTeacher');
  const selectedOption = selTeacher.options[selTeacher.selectedIndex];

  const teacher_id = selectedOption.value;
  const user_id = selectedOption.dataset.userId;
  const selDept    = document.getElementById('selectDepartment');
  const department_id = selDept?.value || '';
  const teacherName = selTeacher?.selectedOptions?.[0]?.textContent?.trim() || '';
  const deptName    = selDept?.selectedOptions?.[0]?.textContent?.trim() || '';

  if(!teacher_id || !department_id){
    alert('Vui lòng chọn giảng viên và bộ môn'); return;
  }

  const btn = document.getElementById('btnAssignHead');
  const old = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> Đang gán...';

  try{
    const res = await fetch(`{{ route('web.assistant.department_roles.store') }}`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
      },
      body: JSON.stringify({
        teacher_id,
        department_id,
        role: 'head',
        user_id
      })
    });

    const txt = await res.text();
    let data; try { data = JSON.parse(txt); } catch { console.error(txt); throw new Error('RESP_NOT_JSON'); }
    if(!res.ok || data.ok === false){
      alert(data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Gán quyền thất bại'));
      return;
    }

    // Kỳ vọng API trả về DepartmentRole + quan hệ
    const role = data.data || data;
    const roleId = role.id || role.role_id || '';
    const teacherEmail = role.teacher?.user?.email || '';

    // Thêm hàng mới vào bảng
    const tb = document.querySelector('table tbody');
    const tr = document.createElement('tr');
    tr.className = 'hover:bg-slate-50';
    tr.innerHTML = `
      <td class="py-3 px-4"><input type="checkbox" class="rowChk h-4 w-4"/></td>
      <td class="py-3 px-4">${role.department?.code ?? '—'}</td>
      <td class="py-3 px-4">${deptName}</td>
      <td class="py-3 px-4">${teacherName}</td>
      <td class="py-3 px-4">${teacherEmail || '—'}</td>
      <td class="py-3 px-4 text-right">
        <button
          class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"
          data-department-id="${department_id}"
          data-role-id="${roleId}"
          data-teacher-id="${teacher_id}"
          onclick="removeHead(this)">
          <i class="ph ph-user-minus"></i>
          Bỏ quyền
        </button>
      </td>
    `;
    tb?.prepend(tr);

    // Loại bộ môn vừa được gán khỏi select (không còn "chưa có trưởng")
    selDept?.querySelector(`option[value="${department_id}"]`)?.remove();

    alert('Gán quyền Trưởng bộ môn thành công');
  } catch(err){
    console.error(err);
    alert('Lỗi: ' + (err.message || 'Không xác định'));
  } finally {
    btn.disabled = false;
    btn.innerHTML = old;
  }
  }

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
