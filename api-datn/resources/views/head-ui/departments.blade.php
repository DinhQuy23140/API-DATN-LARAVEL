<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Trưởng bộ môn - Bộ môn/Khoa</title>
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
      <div class="h-9 w-9 grid place-items-center rounded-lg bg-indigo-600 text-white"><i class="ph ph-chalkboard-teacher"></i></div>
      <div class="sidebar-label">
        <div class="font-semibold">Head</div>
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
      <a href="thesis-rounds.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 pl-10"><i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span></a>
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
          <h1 class="text-lg md:text-xl font-semibold">Bộ môn / Khoa</h1>
          <nav class="text-xs text-slate-500 mt-0.5">
            <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
            <span class="mx-1">/</span>
            <span class="text-slate-500">Bộ môn / Khoa</span>
          </nav>
        </div>
      </div>
      <div class="relative">
        <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
          <img class="h-9 w-9 rounded-full object-cover" src="https://i.pravatar.cc/100?img=10" alt="avatar" />
          <div class="hidden sm:block text-left">
            <div class="text-sm font-semibold leading-4">ThS. Nguyễn Văn H</div>
            <div class="text-xs text-slate-500">head@uni.edu</div>
          </div>
          <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
        </button>
        <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
          <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Hồ sơ</a>
          <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
        </div>
      </div>
    </header>

    <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 space-y-6">
      <div class="max-w-6xl mx-auto space-y-6">
        <section class="bg-white border rounded-xl p-5">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-72" placeholder="Tìm theo mã/tên bộ môn" id="searchInput" />
            </div>
            <button id="newBtn" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm flex items-center gap-1"><i class="ph ph-plus"></i> Thêm bộ môn</button>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-500 border-b">
                  <th class="py-3 px-3">Mã</th>
                  <th class="py-3 px-3">Tên bộ môn</th>
                  <th class="py-3 px-3">Số GV</th>
                  <th class="py-3 px-3">Số SV</th>
                  <th class="py-3 px-3">Trưởng BM</th>
                  <th class="py-3 px-3">Hành động</th>
                </tr>
              </thead>
              <tbody id="deptRows"></tbody>
            </table>
          </div>
        </section>
      </div>
    </main>
  </div>
</div>
<script>
(function(){
  const html=document.documentElement, sidebar=document.getElementById('sidebar');
  function setCollapsed(c){
    const h=document.querySelector('header'); const m=document.querySelector('main');
    if(c){ html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); }
    else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]'); }
  }
  document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('head_sidebar',''+(c?1:0));});
  document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
  if(localStorage.getItem('head_sidebar')==='1') setCollapsed(true);
  sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
  const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
  profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
  document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
})();

// Mock dept list
let departments = [
  { id:'CNTT', name:'Công nghệ thông tin', lecturers:34, students:512, head:'ThS. Nguyễn Văn H' },
  { id:'DTVT', name:'Điện tử viễn thông', lecturers:18, students:220, head:'TS. Phạm Văn K' },
  { id:'ATTT', name:'An toàn thông tin', lecturers:12, students:150, head:'TS. Lê Thị M' }
];

function render(){
  const q=(document.getElementById('searchInput').value||'').toLowerCase();
  const rows=document.getElementById('deptRows');
  const list=departments.filter(d=> d.id.toLowerCase().includes(q)||d.name.toLowerCase().includes(q));
  if(!list.length){ rows.innerHTML = "<tr><td colspan='6' class='py-4 px-3 text-slate-500'>Không có dữ liệu.</td></tr>"; return; }
  rows.innerHTML = list.map(d=>`
    <tr class='border-b hover:bg-slate-50'>
      <td class='py-3 px-3 font-medium'>${d.id}</td>
      <td class='py-3 px-3'>${d.name}</td>
      <td class='py-3 px-3'>${d.lecturers}</td>
      <td class='py-3 px-3'>${d.students}</td>
      <td class='py-3 px-3'>${d.head}</td>
      <td class='py-3 px-3'>
        <div class='flex items-center gap-1'>
          <button class='px-2 py-1 border border-slate-200 rounded text-xs' data-edit='${d.id}'>Sửa</button>
          <button class='px-2 py-1 border border-rose-200 text-rose-600 rounded text-xs' data-del='${d.id}'>Xóa</button>
        </div>
      </td>
    </tr>`).join('');
  rows.querySelectorAll('[data-del]').forEach(btn=>btn.addEventListener('click',()=>{
    const id=btn.getAttribute('data-del');
    if(confirm('Xóa bộ môn '+id+'?')){ departments=departments.filter(d=>d.id!==id); render(); }
  }));
  rows.querySelectorAll('[data-edit]').forEach(btn=>btn.addEventListener('click',()=>{
    const id=btn.getAttribute('data-edit');
    const dept=departments.find(d=>d.id===id);
    openModal(dept);
  }));
}

function openModal(dept){
  const isNew=!dept;
  dept = dept||{ id:'', name:'', lecturers:0, students:0, head:'' };
  const wrap=document.createElement('div');
  wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
  wrap.innerHTML=`<div class='absolute inset-0 bg-black/40' data-close></div>
  <div class='bg-white w-full sm:max-w-lg rounded-t-2xl sm:rounded-2xl shadow-lg p-5 relative'>
    <h3 class='font-semibold mb-3'>${isNew?'Thêm bộ môn':'Cập nhật bộ môn'}</h3>
    <div class='grid grid-cols-1 md:grid-cols-2 gap-3 text-sm'>
      <div><label class='text-xs text-slate-500'>Mã</label><input id='fId' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${dept.id}' ${isNew?'':'readonly'} /></div>
      <div><label class='text-xs text-slate-500'>Tên</label><input id='fName' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${dept.name}' /></div>
      <div><label class='text-xs text-slate-500'>Số GV</label><input id='fLect' type='number' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${dept.lecturers}' /></div>
      <div><label class='text-xs text-slate-500'>Số SV</label><input id='fStu' type='number' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${dept.students}' /></div>
      <div class='md:col-span-2'><label class='text-xs text-slate-500'>Trưởng bộ môn</label><input id='fHead' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${dept.head}' /></div>
    </div>
    <div class='mt-5 flex justify-end gap-2'>
      <button class='px-3 py-2 text-slate-600 hover:bg-slate-100 rounded text-sm' data-close>Hủy</button>
      <button id='saveDept' class='px-3 py-2 bg-indigo-600 text-white rounded text-sm'>Lưu</button>
    </div>
  </div>`;
  document.body.appendChild(wrap);
  wrap.addEventListener('click', e=>{ if(e.target.matches('[data-close]')) wrap.remove(); });
  document.getElementById('saveDept').addEventListener('click',()=>{
    const newDept={
      id: document.getElementById('fId').value.trim(),
      name: document.getElementById('fName').value.trim(),
      lecturers: parseInt(document.getElementById('fLect').value)||0,
      students: parseInt(document.getElementById('fStu').value)||0,
      head: document.getElementById('fHead').value.trim()
    };
    if(!newDept.id || !newDept.name){ alert('Vui lòng nhập mã và tên.'); return; }
    if(isNew){
      if(departments.some(d=>d.id===newDept.id)){ alert('Mã đã tồn tại'); return; }
      departments.push(newDept);
    } else {
      const idx=departments.findIndex(d=>d.id===newDept.id); departments[idx]=newDept;
    }
    wrap.remove();
    render();
  });
}

document.getElementById('searchInput').addEventListener('input', render);

document.getElementById('newBtn').addEventListener('click', ()=>openModal(null));

render();
</script>
</body>
</html>
