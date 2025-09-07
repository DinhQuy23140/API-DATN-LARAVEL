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
      <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-transform transform -translate-x-full md:translate-x-0">
        <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
          <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-buildings"></i></div>
          <div class="sidebar-label">
            <div class="font-semibold">Assistant</div>
            <div class="text-xs text-slate-500">Quản trị khoa</div>
          </div>
        </div>
        <nav class="flex-1 overflow-y-auto p-3">
          <a href="dashboard.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span class="sidebar-label">Bảng điều khiển</span></a>
          <a href="manage-departments.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-buildings"></i><span class="sidebar-label">Bộ môn</span></a>
          <a href="manage-majors.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
          <a href="manage-staff.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
          <a href="assign-head.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Gán trưởng bộ môn</span></a>
          <div class="sidebar-label text-xs uppercase text-slate-400 px-3 mt-3">Học phần tốt nghiệp</div>
          <div class="graduation-item">
            <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button">
              <span class="flex items-center gap-3">
                <i class="ph ph-folder"></i>
                <span class="sidebar-label">Học phần tốt nghiệp</span>
              </span>
              <i class="ph ph-caret-down"></i>
            </div>
            <div id="gradMenu" class="submenu pl-6">
              <a href="#" class="block px-3 py-2 rounded hover:bg-slate-100">Thực tập tốt nghiệp</a>
              <a href="{{ route('web.assistant.rounds') }}"
                 class="block px-3 py-2 rounded hover:bg-slate-100 bg-slate-100 font-semibold"
                 aria-current="page">Đồ án tốt nghiệp</a>
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
              <h1 class="text-lg md:text-xl font-semibold">Import danh sách sinh viên</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp / Import sinh viên</nav>
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

  <main class="pt-20 px-4 md:px-6 pb-10 md:pl-[284px]">
          <div class="w-full max-w-none space-y-6">
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <h2 class="font-semibold">Chọn file Excel để import</h2>
            <div class="mt-3">
              <input type="file" accept=".xlsx,.xls" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
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
      <div class="max-h-96 overflow-auto divide-y" id="manualList">
        <!-- render by script -->
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
            <tr><td colspan="5" class="py-4 px-3 text-slate-500">Chưa chọn sinh viên nào.</td></tr>
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
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
  function setCollapsed(c){
    const h=document.querySelector('header');
    const m=document.querySelector('main');
    if(c){
      html.classList.add('sidebar-collapsed');
      h.classList.add('md:left-[72px]');  h.classList.remove('md:left-[260px]');
      m.classList.add('md:pl-[72px]');    m.classList.remove('md:pl-[260px]');
    } else {
      html.classList.remove('sidebar-collapsed');
      h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]');
      m.classList.remove('md:pl-[72px]');   m.classList.add('md:pl-[260px]');
    }
  }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed');setCollapsed(c);localStorage.setItem('assistant_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);
      // Khởi tạo đúng theo breakpoint (mobile ẩn, md+ hiện)
      sidebar.classList.add('transition-transform','transform','-translate-x-full','md:translate-x-0');

      // profile dropdown
      const profileBtn=document.getElementById('profileBtn');
      const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

          // auto active nav highlight
          (function(){
            const current = location.pathname.split('/').pop();
            document.querySelectorAll('aside nav a').forEach(a=>{
              // Bỏ qua link đã được đánh dấu thủ công
              if (a.hasAttribute('aria-current')) return;
              const href=a.getAttribute('href')||'';
              const active=href.endsWith(current);
              a.classList.toggle('bg-slate-100', active);
              a.classList.toggle('font-semibold', active);
              a.classList.toggle('text-slate-900', active);
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

// Toast helper
function toast(msg){
  let host=document.getElementById('toastHost');
  if(!host){ host=document.createElement('div'); host.id='toastHost'; host.className='fixed bottom-4 right-4 z-50 space-y-2'; document.body.appendChild(host); }
  const el=document.createElement('div');
  el.className='px-4 py-2 rounded-lg bg-slate-800 text-white text-sm shadow';
  el.textContent=msg;
  host.appendChild(el);
  setTimeout(()=>{ el.style.opacity='0'; el.style.transform='translateY(4px)'; el.style.transition='all .25s'; },2000);
  setTimeout(()=> el.remove(),2300);
}

(function(){
  // Keys
  const CANDIDATE_KEY='assistant_candidate_students';
  const ADDED_KEY='assistant_students';

  // Elements
  const searchInput=document.getElementById('manualSearch');
  const listEl=document.getElementById('manualList');
  const selectedTbody=document.getElementById('selectedTbody');
  const btnAddSelected=document.getElementById('btnAddSelected');

  // Load/seed data
  function load(key, fallback){
    try{ const d=localStorage.getItem(key); return d?JSON.parse(d):fallback; }catch{return fallback;}
  }
  function save(key, val){ localStorage.setItem(key, JSON.stringify(val)); }

  let candidates=load(CANDIDATE_KEY, []);
  if(!candidates.length){
    candidates=[
      {id:'20123456', name:'Nguyễn Văn A', email:'a@uni.edu', major:'CNTT'},
      {id:'20123457', name:'Trần Thị B', email:'b@uni.edu', major:'CNTT'},
      {id:'20123458', name:'Lê Văn C', email:'c@uni.edu', major:'Marketing'},
      {id:'20123459', name:'Phạm Thị D', email:'d@uni.edu', major:'CNTT'},
      {id:'20123460', name:'Nguyễn Thị E', email:'e@uni.edu', major:'KTPM'},
      {id:'20123461', name:'Đỗ Văn F', email:'f@uni.edu', major:'HTTT'}
    ];
    save(CANDIDATE_KEY, candidates);
  }
  let addedList=load(ADDED_KEY, []); // [{id,name,email,major},...]

  // Selection state
  const selected=new Set();

  function updateAddBtn(){
    const n=selected.size;
    btnAddSelected.disabled=n===0;
    btnAddSelected.textContent=`Thêm sinh viên (${n})`;
    btnAddSelected.classList.toggle('bg-blue-400', n===0);
    btnAddSelected.classList.toggle('bg-blue-600', n>0);
    btnAddSelected.classList.toggle('hover:bg-blue-700', n>0);
  }

  function isAlreadyAdded(id){
    return addedList.some(s=>s.id===id);
  }

  function renderSelected(){
    if(selected.size===0){
      selectedTbody.innerHTML = `<tr><td colspan="5" class="py-4 px-3 text-slate-500">Chưa chọn sinh viên nào.</td></tr>`;
      updateAddBtn();
      return;
    }
    const rows=[...selected].map(id=>{
      const s=candidates.find(x=>x.id===id);
      if(!s) return '';
      return `<tr class="border-b">
        <td class="py-2 px-3 font-medium">${s.id}</td>
        <td class="py-2 px-3">${s.name}</td>
        <td class="py-2 px-3">${s.email||'-'}</td>
        <td class="py-2 px-3">${s.major||''}</td>
        <td class="py-2 px-3 text-right">
          <button class="px-2 py-1 text-xs rounded border border-slate-200 hover:bg-slate-50" data-unselect="${s.id}">
            Bỏ chọn
          </button>
        </td>
      </tr>`;
    }).join('');
    selectedTbody.innerHTML = rows;
    selectedTbody.querySelectorAll('[data-unselect]').forEach(b=>{
      b.addEventListener('click', ()=>{
        const id=b.getAttribute('data-unselect');
        selected.delete(id);
        renderSelected();
        renderList(); // sync checkbox
        updateAddBtn();
      });
    });
    updateAddBtn();
  }

  function renderList(){
    const q=(searchInput?.value||'').toLowerCase();
    const data=candidates.filter(s=>{
      if(!q) return true;
      return s.id.toLowerCase().includes(q) || s.name.toLowerCase().includes(q) || (s.email||'').toLowerCase().includes(q);
    });
    if(!data.length){
      listEl.innerHTML=`<div class="p-4 text-slate-500 text-sm">Không tìm thấy sinh viên phù hợp.</div>`;
      return;
    }
    listEl.innerHTML=data.map(s=>{
      const checked=selected.has(s.id) ? 'checked' : '';
      const disabled=isAlreadyAdded(s.id) ? 'disabled' : '';
      return `<label class="flex items-center gap-3 p-3 hover:bg-slate-50 ${disabled?'opacity-60 cursor-not-allowed':''}">
        <input type="checkbox" class="rounded border-slate-300" data-id="${s.id}" ${checked} ${disabled}>
        <div class="flex-1">
          <div class="font-medium">${s.name} <span class="text-slate-500 font-normal">(${s.id})</span></div>
          <div class="text-xs text-slate-500">${s.email||'-'} • ${s.major||''}</div>
        </div>
        ${disabled?'<span class="text-xs text-green-700 bg-green-50 border border-green-100 px-2 py-1 rounded-full">Đã có</span>':''}
      </label>`;
    }).join('');
    listEl.querySelectorAll('input[type=checkbox][data-id]').forEach(cb=>{
      cb.addEventListener('change', ()=>{
        const id=cb.getAttribute('data-id');
        if(cb.checked) selected.add(id); else selected.delete(id);
        renderSelected();
        updateAddBtn();
      });
    });
  }

  btnAddSelected?.addEventListener('click', ()=>{
    if(selected.size===0) return;
    const toAdd=[...selected]
      .map(id=> candidates.find(s=>s.id===id))
      .filter(Boolean)
      .filter(s=> !isAlreadyAdded(s.id));
    if(toAdd.length===0){
      toast('Không có sinh viên mới để thêm.');
      return;
    }
    addedList = load(ADDED_KEY, []);
    addedList.push(...toAdd);
    save(ADDED_KEY, addedList);
    const count=toAdd.length;
    selected.clear();
    renderSelected();
    renderList();
    toast(`Đã thêm ${count} sinh viên`);
  });

  searchInput?.addEventListener('input', renderList);

  // Initial render
  renderSelected();
  renderList();
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
