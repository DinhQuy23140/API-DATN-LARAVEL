<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Trưởng bộ môn - Hội đồng</title>
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
      <a href="committees.html" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold pl-10"><i class="ph ph-rows"></i><span class="sidebar-label">Hội đồng</span></a>
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
          <h1 class="text-lg md:text-xl font-semibold">Hội đồng</h1>
          <nav class="text-xs text-slate-500 mt-0.5">
            <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
            <span class="mx-1">/</span>
            <span class="text-slate-500">Hội đồng</span>
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

    <main class="pt-20 px-4 md:px-6 pb-10 space-y-6">
      <div class="max-w-7xl mx-auto space-y-6">
        <section class="bg-white border rounded-xl p-5">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="searchInput" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-72" placeholder="Tìm theo mã/tên hội đồng" />
            </div>
            <button id="newBtn" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm flex items-center gap-1"><i class="ph ph-plus"></i> Tạo hội đồng</button>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-500 border-b">
                  <th class="py-3 px-3">Mã</th>
                  <th class="py-3 px-3">Tên hội đồng</th>
                  <th class="py-3 px-3">Ngày</th>
                  <th class="py-3 px-3">Thời gian</th>
                  <th class="py-3 px-3">Phòng</th>
                  <th class="py-3 px-3">Số SV</th>
                  <th class="py-3 px-3">Hành động</th>
                </tr>
              </thead>
              <tbody id="cRows"></tbody>
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

let committees=[
  { id:'CNTT-01', name:'Hội đồng CNTT 01', date:'20/08/2025', time:'08:00', room:'P.A203', students:5 },
  { id:'CNTT-02', name:'Hội đồng CNTT 02', date:'20/08/2025', time:'09:30', room:'P.A204', students:4 },
  { id:'CNTT-03', name:'Hội đồng CNTT 03', date:'21/08/2025', time:'08:00', room:'P.A205', students:6 }
];

function render(){
  const q=(document.getElementById('searchInput').value||'').toLowerCase();
  const rows=document.getElementById('cRows');
  const list=committees.filter(c=> c.id.toLowerCase().includes(q)||c.name.toLowerCase().includes(q));
  if(!list.length){ rows.innerHTML="<tr><td colspan='7' class='py-4 px-3 text-slate-500'>Không có dữ liệu.</td></tr>"; return; }
  rows.innerHTML=list.map(c=>`<tr class='border-b hover:bg-slate-50'>
    <td class='py-3 px-3 font-medium'>${c.id}</td>
    <td class='py-3 px-3'>${c.name}</td>
    <td class='py-3 px-3'>${c.date}</td>
    <td class='py-3 px-3'>${c.time}</td>
    <td class='py-3 px-3'>${c.room}</td>
    <td class='py-3 px-3'>${c.students}</td>
    <td class='py-3 px-3'><div class='flex items-center gap-1'><button class='px-2 py-1 border border-slate-200 rounded text-xs' data-edit='${c.id}'>Sửa</button><button class='px-2 py-1 border border-rose-200 text-rose-600 rounded text-xs' data-del='${c.id}'>Xóa</button></div></td>
  </tr>`).join('');
  rows.querySelectorAll('[data-del]').forEach(btn=>btn.addEventListener('click',()=>{ const id=btn.getAttribute('data-del'); if(confirm('Xóa hội đồng '+id+'?')) { committees=committees.filter(c=>c.id!==id); render(); } }));
  rows.querySelectorAll('[data-edit]').forEach(btn=>btn.addEventListener('click',()=>openModal(committees.find(c=>c.id===btn.getAttribute('data-edit')))));
}

function openModal(item){
  const isNew=!item; item=item||{id:'', name:'', date:'', time:'', room:'', students:0};
  const wrap=document.createElement('div');
  wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
  wrap.innerHTML=`<div class='absolute inset-0 bg-black/40' data-close></div>
  <div class='bg-white w-full sm:max-w-lg rounded-t-2xl sm:rounded-2xl shadow-lg p-5 relative'>
    <h3 class='font-semibold mb-3'>${isNew?'Tạo hội đồng':'Cập nhật hội đồng'}</h3>
    <div class='grid grid-cols-1 md:grid-cols-2 gap-3 text-sm'>
      <div><label class='text-xs text-slate-500'>Mã</label><input id='fId' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${item.id}' ${isNew?'':'readonly'} /></div>
      <div><label class='text-xs text-slate-500'>Tên</label><input id='fName' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${item.name}' /></div>
      <div><label class='text-xs text-slate-500'>Ngày</label><input id='fDate' type='date' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${item.date?item.date.split('/').reverse().join('-'):''}' /></div>
      <div><label class='text-xs text-slate-500'>Giờ</label><input id='fTime' type='time' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${item.time||''}' /></div>
      <div><label class='text-xs text-slate-500'>Phòng</label><input id='fRoom' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${item.room}' /></div>
      <div><label class='text-xs text-slate-500'>Số SV</label><input id='fStudents' type='number' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${item.students}' /></div>
    </div>
    <div class='mt-5 flex justify-end gap-2'>
      <button class='px-3 py-2 text-slate-600 hover:bg-slate-100 rounded text-sm' data-close>Hủy</button>
      <button id='saveCommittee' class='px-3 py-2 bg-indigo-600 text-white rounded text-sm'>Lưu</button>
    </div>
  </div>`;
  document.body.appendChild(wrap);
  wrap.addEventListener('click', e=>{ if(e.target.matches('[data-close]')) wrap.remove(); });
  document.getElementById('saveCommittee').addEventListener('click',()=>{
    const itemData={
      id:document.getElementById('fId').value.trim(),
      name:document.getElementById('fName').value.trim(),
      date:(()=>{const v=document.getElementById('fDate').value; if(!v) return ''; const [y,m,d]=v.split('-'); return `${d}/${m}/${y}`; })(),
      time:document.getElementById('fTime').value.trim(),
      room:document.getElementById('fRoom').value.trim(),
      students:parseInt(document.getElementById('fStudents').value)||0
    };
    if(!itemData.id||!itemData.name){ alert('Vui lòng nhập mã và tên'); return; }
    if(isNew){ if(committees.some(c=>c.id===itemData.id)){ alert('Mã đã tồn tại'); return;} committees.push(itemData);} else { const idx=committees.findIndex(c=>c.id===itemData.id); committees[idx]=itemData; }
    wrap.remove(); render();
  });
}

document.getElementById('searchInput').addEventListener('input', render);

document.getElementById('newBtn').addEventListener('click', ()=>openModal(null));

render();
</script>
</body>
</html>
