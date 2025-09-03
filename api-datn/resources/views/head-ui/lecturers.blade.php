<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Trưởng bộ môn - Giảng viên</title>
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
          <h1 class="text-lg md:text-xl font-semibold">Giảng viên</h1>
          <nav class="text-xs text-slate-500 mt-0.5">
            <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
            <span class="mx-1">/</span>
            <span class="text-slate-500">Giảng viên</span>
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
      <div class="max-w-7xl mx-auto space-y-6">
        <section class="bg-white border rounded-xl p-5">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
            <div class="flex flex-wrap gap-2 items-center">
              <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="searchInput" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-72" placeholder="Tìm theo tên/email" />
              </div>
              <select id="filterDept" class="border border-slate-200 rounded px-2 py-2 text-sm">
                <option value="">Tất cả bộ môn</option>
              </select>
            </div>
            <button id="addLecturer" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm flex items-center gap-1"><i class="ph ph-plus"></i> Thêm giảng viên</button>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-500 border-b">
                  <th class="py-3 px-3">Họ tên</th>
                  <th class="py-3 px-3">Email</th>
                  <th class="py-3 px-3">Bộ môn</th>
                  <th class="py-3 px-3">Số SV HD</th>
                  <th class="py-3 px-3">Hội đồng</th>
                  <th class="py-3 px-3">Trạng thái</th>
                  <th class="py-3 px-3">Hành động</th>
                </tr>
              </thead>
              <tbody id="lectRows"></tbody>
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

// Mock data
let lecturers=[
  { name:'TS. Trần Văn B', email:'b@uni.edu', dept:'CNTT', students:8, committees:2, status:'Hoạt động' },
  { name:'TS. Lê Thị C', email:'c@uni.edu', dept:'CNTT', students:6, committees:3, status:'Hoạt động' },
  { name:'TS. Phạm Văn D', email:'d@uni.edu', dept:'DTVT', students:4, committees:1, status:'Tạm nghỉ' }
];
const departments=[{id:'CNTT', name:'Công nghệ thông tin'},{id:'DTVT', name:'Điện tử viễn thông'}];

const filterDept=document.getElementById('filterDept');
filterDept.innerHTML+=""+departments.map(d=>`<option value='${d.id}'>${d.name}</option>`).join('');

function render(){
  const q=(document.getElementById('searchInput').value||'').toLowerCase();
  const fd=filterDept.value;
  const rows=document.getElementById('lectRows');
  const list=lecturers.filter(l=> (l.name.toLowerCase().includes(q)||l.email.toLowerCase().includes(q)) && (!fd||l.dept===fd));
  if(!list.length){ rows.innerHTML="<tr><td colspan='7' class='py-4 px-3 text-slate-500'>Không có dữ liệu.</td></tr>"; return; }
  rows.innerHTML=list.map(l=>`<tr class='border-b hover:bg-slate-50'>
    <td class='py-3 px-3 font-medium'>${l.name}</td>
    <td class='py-3 px-3'>${l.email}</td>
    <td class='py-3 px-3'>${l.dept}</td>
    <td class='py-3 px-3'>${l.students}</td>
    <td class='py-3 px-3'>${l.committees}</td>
    <td class='py-3 px-3'>${l.status}</td>
    <td class='py-3 px-3'><div class='flex items-center gap-1'><button class='px-2 py-1 border border-slate-200 rounded text-xs' data-edit='${l.email}'>Sửa</button></div></td>
  </tr>`).join('');
  rows.querySelectorAll('[data-edit]').forEach(btn=>btn.addEventListener('click',()=>openModal(lecturers.find(l=>l.email===btn.getAttribute('data-edit')))));
}

function openModal(lec){
  const wrap=document.createElement('div');
  wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
  lec=lec||{name:'', email:'', dept:'', students:0, committees:0, status:'Hoạt động'};
  const isNew=!lec.email;
  wrap.innerHTML=`<div class='absolute inset-0 bg-black/40' data-close></div>
  <div class='bg-white w-full sm:max-w-lg rounded-t-2xl sm:rounded-2xl shadow-lg p-5 relative'>
    <h3 class='font-semibold mb-3'>${isNew?'Thêm giảng viên':'Cập nhật giảng viên'}</h3>
    <div class='grid grid-cols-1 md:grid-cols-2 gap-3 text-sm'>
      <div class='md:col-span-2'><label class='text-xs text-slate-500'>Họ tên</label><input id='fName' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${lec.name}' /></div>
      <div class='md:col-span-2'><label class='text-xs text-slate-500'>Email</label><input id='fEmail' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${lec.email}' ${isNew?'':'readonly'} /></div>
      <div><label class='text-xs text-slate-500'>Bộ môn</label><select id='fDept' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5'>${departments.map(d=>`<option ${lec.dept===d.id?'selected':''} value='${d.id}'>${d.name}</option>`).join('')}</select></div>
      <div><label class='text-xs text-slate-500'>Trạng thái</label><select id='fStatus' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5'><option ${lec.status==='Hoạt động'?'selected':''}>Hoạt động</option><option ${lec.status==='Tạm nghỉ'?'selected':''}>Tạm nghỉ</option></select></div>
    </div>
    <div class='mt-5 flex justify-end gap-2'>
      <button class='px-3 py-2 text-slate-600 hover:bg-slate-100 rounded text-sm' data-close>Hủy</button>
      <button id='saveLecturer' class='px-3 py-2 bg-indigo-600 text-white rounded text-sm'>Lưu</button>
    </div>
  </div>`;
  document.body.appendChild(wrap);
  wrap.addEventListener('click', e=>{ if(e.target.matches('[data-close]')) wrap.remove(); });
  document.getElementById('saveLecturer').addEventListener('click',()=>{
    const item={
      name: document.getElementById('fName').value.trim(),
      email: document.getElementById('fEmail').value.trim(),
      dept: document.getElementById('fDept').value.trim(),
      status: document.getElementById('fStatus').value.trim(),
      students: lec.students||0,
      committees: lec.committees||0
    };
    if(!item.name||!item.email){ alert('Vui lòng nhập đủ'); return; }
    if(isNew){ if(lecturers.some(l=>l.email===item.email)){ alert('Email đã tồn tại'); return; } lecturers.push(item);} else { const idx=lecturers.findIndex(l=>l.email===item.email); lecturers[idx]=item; }
    wrap.remove(); render();
  });
}

document.getElementById('searchInput').addEventListener('input', render);
filterDept.addEventListener('change', render);

document.getElementById('addLecturer').addEventListener('click', ()=>openModal(null));
render();
</script>
</body>
</html>
