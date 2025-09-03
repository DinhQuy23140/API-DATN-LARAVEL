<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Trưởng bộ môn - Chi tiết hội đồng</title>
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
  /* .role-badge utility placeholder (not using Tailwind @apply inline) */
  .role-badge{ font-size:12px; padding:2px 6px; border-radius:4px; background:#f1f5f9; display:inline-block; }
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
          <h1 class="text-lg md:text-xl font-semibold">Chi tiết hội đồng</h1>
          <nav class="text-xs text-slate-500 mt-0.5">
            <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
            <span class="mx-1">/</span>
            <a href="committees.html" class="hover:underline text-slate-600">Hội đồng</a>
            <span class="mx-1">/</span>
            <span id="breadcrumbId" class="text-slate-500">...</span>
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
        <section id="infoPanel" class="bg-white border rounded-xl p-5 space-y-5">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <h2 id="cName" class="text-lg font-semibold">...</h2>
              <div class="text-sm text-slate-500 mt-1 flex flex-wrap gap-x-4 gap-y-1">
                <span id="cId"></span>
                <span id="cDate"></span>
                <span id="cTime"></span>
                <span id="cRoom"></span>
              </div>
            </div>
            <div class="flex flex-wrap gap-2">
              <button id="editBtn" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm flex items-center gap-1"><i class="ph ph-pencil-simple"></i>Sửa</button>
              <button id="exportBtn" class="px-3 py-2 border border-slate-200 rounded text-sm flex items-center gap-1"><i class="ph ph-file-arrow-down"></i>Xuất DS</button>
            </div>
          </div>
          <div class="grid md:grid-cols-3 gap-3 text-sm">
            <div class="p-3 rounded-lg bg-indigo-50 border border-indigo-100">
              <div class="text-xs uppercase font-medium text-indigo-600">Số thành viên</div>
              <div id="memberCount" class="text-xl font-semibold mt-1">0</div>
            </div>
            <div class="p-3 rounded-lg bg-emerald-50 border border-emerald-100">
              <div class="text-xs uppercase font-medium text-emerald-600">Số sinh viên</div>
              <div id="studentCount" class="text-xl font-semibold mt-1">0</div>
            </div>
            <div class="p-3 rounded-lg bg-amber-50 border border-amber-100">
              <div class="text-xs uppercase font-medium text-amber-600">Trạng thái</div>
              <div id="status" class="text-sm font-medium mt-1">Chưa diễn ra</div>
            </div>
          </div>
        </section>

        <section class="bg-white border rounded-xl p-5">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <h3 class="font-semibold">Thành viên hội đồng</h3>
            <button id="addMemberBtn" class="px-3 py-2 bg-slate-800 text-white rounded text-sm flex items-center gap-1"><i class="ph ph-user-plus"></i>Thêm</button>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-500 border-b">
                  <th class="py-2.5 px-3">Mã</th>
                  <th class="py-2.5 px-3">Tên</th>
                  <th class="py-2.5 px-3">Vai trò</th>
                  <th class="py-2.5 px-3">Email</th>
                  <th class="py-2.5 px-3">Hành động</th>
                </tr>
              </thead>
              <tbody id="memberRows"></tbody>
            </table>
          </div>
        </section>

        <section class="bg-white border rounded-xl p-5">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <h3 class="font-semibold">Danh sách sinh viên</h3>
            <div class="flex gap-2">
              <button id="addStudentBtn" class="px-3 py-2 bg-slate-800 text-white rounded text-sm flex items-center gap-1"><i class="ph ph-user-plus"></i>Thêm</button>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-500 border-b">
                  <th class="py-2.5 px-3">MSSV</th>
                  <th class="py-2.5 px-3">Tên</th>
                  <th class="py-2.5 px-3">Đề tài</th>
                  <th class="py-2.5 px-3">GVHD</th>
                  <th class="py-2.5 px-3">Hành động</th>
                </tr>
              </thead>
              <tbody id="studentRows"></tbody>
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

// Mock data retrieval (in real app fetch by id)
const params=new URLSearchParams(location.search); const committeeId=params.get('id')||'CNTT-01';
const committee={ id:committeeId, name:'Hội đồng '+committeeId, date:'20/08/2025', time:'08:00', room:'P.A203' };
let members=[
  { id:'GV001', name:'PGS. Trần Thị A', role:'Chủ tịch', email:'a@uni.edu' },
  { id:'GV002', name:'TS. Nguyễn Văn B', role:'Thư ký', email:'b@uni.edu' },
  { id:'GV003', name:'ThS. Lê Văn C', role:'Ủy viên', email:'c@uni.edu' }
];
let students=[
  { id:'SV001', name:'Nguyễn Minh K', topic:'Ứng dụng AI trong y tế', supervisor:'TS. Phạm Thị D' },
  { id:'SV002', name:'Trần Quốc L', topic:'Hệ thống gợi ý học tập', supervisor:'ThS. Lê Văn C' },
  { id:'SV003', name:'Phạm Hải M', topic:'Blockchain truy xuất nguồn gốc', supervisor:'PGS. Trần Thị A' }
];

function initInfo(){
  document.getElementById('breadcrumbId').textContent=committee.id;
  document.getElementById('cName').textContent=committee.name;
  document.getElementById('cId').textContent='Mã: '+committee.id;
  document.getElementById('cDate').textContent='Ngày: '+committee.date;
  document.getElementById('cTime').textContent='Giờ: '+committee.time;
  document.getElementById('cRoom').textContent='Phòng: '+committee.room;
  document.getElementById('memberCount').textContent=members.length;
  document.getElementById('studentCount').textContent=students.length;
}

function renderMembers(){
  const rows=document.getElementById('memberRows');
  if(!members.length){ rows.innerHTML="<tr><td colspan='5' class='py-3 px-3 text-slate-500'>Không có thành viên.</td></tr>"; return; }
  rows.innerHTML=members.map(m=>`<tr class='border-b hover:bg-slate-50'>
    <td class='py-2.5 px-3 font-medium'>${m.id}</td>
    <td class='py-2.5 px-3'>${m.name}</td>
    <td class='py-2.5 px-3'>${m.role}</td>
    <td class='py-2.5 px-3'>${m.email}</td>
    <td class='py-2.5 px-3'><div class='flex gap-1'>
      <button class='px-2 py-1 border border-slate-200 rounded text-xs' data-edit-member='${m.id}'>Sửa</button>
      <button class='px-2 py-1 border border-rose-200 text-rose-600 rounded text-xs' data-del-member='${m.id}'>Xóa</button>
    </div></td>
  </tr>`).join('');
  rows.querySelectorAll('[data-del-member]').forEach(btn=>btn.addEventListener('click',()=>{const id=btn.getAttribute('data-del-member'); if(confirm('Xóa thành viên '+id+'?')){members=members.filter(x=>x.id!==id); document.getElementById('memberCount').textContent=members.length; renderMembers();}}));
  rows.querySelectorAll('[data-edit-member]').forEach(btn=>btn.addEventListener('click',()=>openMemberModal(members.find(x=>x.id===btn.getAttribute('data-edit-member')))));
}

function renderStudents(){
  const rows=document.getElementById('studentRows');
  if(!students.length){ rows.innerHTML="<tr><td colspan='5' class='py-3 px-3 text-slate-500'>Không có sinh viên.</td></tr>"; return; }
  rows.innerHTML=students.map(s=>`<tr class='border-b hover:bg-slate-50'>
    <td class='py-2.5 px-3 font-medium'>${s.id}</td>
    <td class='py-2.5 px-3'>${s.name}</td>
    <td class='py-2.5 px-3'>${s.topic}</td>
    <td class='py-2.5 px-3'>${s.supervisor}</td>
    <td class='py-2.5 px-3'><div class='flex gap-1'>
      <button class='px-2 py-1 border border-slate-200 rounded text-xs' data-edit-student='${s.id}'>Sửa</button>
      <button class='px-2 py-1 border border-rose-200 text-rose-600 rounded text-xs' data-del-student='${s.id}'>Xóa</button>
    </div></td>
  </tr>`).join('');
  rows.querySelectorAll('[data-del-student]').forEach(btn=>btn.addEventListener('click',()=>{const id=btn.getAttribute('data-del-student'); if(confirm('Xóa sinh viên '+id+'?')){students=students.filter(x=>x.id!==id); document.getElementById('studentCount').textContent=students.length; renderStudents();}}));
  rows.querySelectorAll('[data-edit-student]').forEach(btn=>btn.addEventListener('click',()=>openStudentModal(students.find(x=>x.id===btn.getAttribute('data-edit-student')))));
}

function openMemberModal(item){
  const isNew=!item; item=item||{id:'', name:'', role:'Chủ tịch', email:''};
  const wrap=document.createElement('div');
  wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
  wrap.innerHTML=`<div class='absolute inset-0 bg-black/40' data-close></div>
  <div class='bg-white w-full sm:max-w-lg rounded-t-2xl sm:rounded-2xl shadow-lg p-5 relative'>
    <h3 class='font-semibold mb-3'>${isNew?'Thêm thành viên':'Cập nhật thành viên'}</h3>
    <div class='grid grid-cols-1 md:grid-cols-2 gap-3 text-sm'>
      <div><label class='text-xs text-slate-500'>Mã</label><input id='mId' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${item.id}' ${isNew?'':'readonly'} /></div>
      <div><label class='text-xs text-slate-500'>Tên</label><input id='mName' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${item.name}' /></div>
      <div><label class='text-xs text-slate-500'>Vai trò</label><select id='mRole' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5'>
        ${['Chủ tịch','Thư ký','Phản biện','Ủy viên'].map(r=>`<option ${r===item.role?'selected':''}>${r}</option>`).join('')}
      </select></div>
      <div><label class='text-xs text-slate-500'>Email</label><input id='mEmail' type='email' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${item.email}' /></div>
    </div>
    <div class='mt-5 flex justify-end gap-2'>
      <button class='px-3 py-2 text-slate-600 hover:bg-slate-100 rounded text-sm' data-close>Hủy</button>
      <button id='saveMember' class='px-3 py-2 bg-indigo-600 text-white rounded text-sm'>Lưu</button>
    </div>
  </div>`;
  document.body.appendChild(wrap);
  wrap.addEventListener('click', e=>{ if(e.target.matches('[data-close]')) wrap.remove(); });
  document.getElementById('saveMember').addEventListener('click',()=>{
    const data={ id:document.getElementById('mId').value.trim(), name:document.getElementById('mName').value.trim(), role:document.getElementById('mRole').value, email:document.getElementById('mEmail').value.trim() };
    if(!data.id||!data.name){ alert('Nhập đủ mã và tên'); return; }
    if(isNew){ if(members.some(x=>x.id===data.id)){ alert('Mã tồn tại'); return;} members.push(data);} else { const idx=members.findIndex(x=>x.id===data.id); members[idx]=data; }
    document.getElementById('memberCount').textContent=members.length; wrap.remove(); renderMembers();
  });
}

function openStudentModal(item){
  const isNew=!item; item=item||{id:'', name:'', topic:'', supervisor:''};
  const wrap=document.createElement('div');
  wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
  wrap.innerHTML=`<div class='absolute inset-0 bg-black/40' data-close></div>
  <div class='bg-white w-full sm:max-w-xl rounded-t-2xl sm:rounded-2xl shadow-lg p-5 relative'>
    <h3 class='font-semibold mb-3'>${isNew?'Thêm sinh viên':'Cập nhật sinh viên'}</h3>
    <div class='grid grid-cols-1 md:grid-cols-2 gap-3 text-sm'>
      <div><label class='text-xs text-slate-500'>MSSV</label><input id='sId' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${item.id}' ${isNew?'':'readonly'} /></div>
      <div><label class='text-xs text-slate-500'>Tên</label><input id='sName' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${item.name}' /></div>
      <div class='md:col-span-2'><label class='text-xs text-slate-500'>Đề tài</label><input id='sTopic' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${item.topic}' /></div>
      <div class='md:col-span-2'><label class='text-xs text-slate-500'>GV hướng dẫn</label><input id='sSupervisor' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${item.supervisor}' /></div>
    </div>
    <div class='mt-5 flex justify-end gap-2'>
      <button class='px-3 py-2 text-slate-600 hover:bg-slate-100 rounded text-sm' data-close>Hủy</button>
      <button id='saveStudent' class='px-3 py-2 bg-indigo-600 text-white rounded text-sm'>Lưu</button>
    </div>
  </div>`;
  document.body.appendChild(wrap);
  wrap.addEventListener('click', e=>{ if(e.target.matches('[data-close]')) wrap.remove(); });
  document.getElementById('saveStudent').addEventListener('click',()=>{
    const data={ id:document.getElementById('sId').value.trim(), name:document.getElementById('sName').value.trim(), topic:document.getElementById('sTopic').value.trim(), supervisor:document.getElementById('sSupervisor').value.trim() };
    if(!data.id||!data.name){ alert('Nhập đủ MSSV và tên'); return; }
    if(isNew){ if(students.some(x=>x.id===data.id)){ alert('MSSV tồn tại'); return;} students.push(data);} else { const idx=students.findIndex(x=>x.id===data.id); students[idx]=data; }
    document.getElementById('studentCount').textContent=students.length; wrap.remove(); renderStudents();
  });
}

document.getElementById('addMemberBtn').addEventListener('click',()=>openMemberModal(null));
document.getElementById('addStudentBtn').addEventListener('click',()=>openStudentModal(null));

document.getElementById('editBtn').addEventListener('click',()=>{
  // quick edit: open simple modal to change date/time/room
  const wrap=document.createElement('div');
  wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
  wrap.innerHTML=`<div class='absolute inset-0 bg-black/40' data-close></div>
  <div class='bg-white w-full sm:max-w-md rounded-t-2xl sm:rounded-2xl shadow-lg p-5 relative'>
    <h3 class='font-semibold mb-3'>Chỉnh sửa thông tin</h3>
    <div class='grid grid-cols-1 gap-3 text-sm'>
    <title>Trưởng bộ môn - Chi tiết hội đồng</title>
      <div class='flex gap-3'>
        <div class='flex-1'><label class='text-xs text-slate-500'>Ngày</label><input id='eDate' type='date' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${committee.date.split('/').reverse().join('-')}' /></div>
        <div class='w-40'><label class='text-xs text-slate-500'>Giờ</label><input id='eTime' type='time' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${committee.time}' /></div>
      </div>
      <div><label class='text-xs text-slate-500'>Phòng</label><input id='eRoom' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${committee.room}' /></div>
    </div>
    <div class='mt-5 flex justify-end gap-2'>
      <button class='px-3 py-2 text-slate-600 hover:bg-slate-100 rounded text-sm' data-close>Hủy</button>
      <button id='saveInfo' class='px-3 py-2 bg-indigo-600 text-white rounded text-sm'>Lưu</button>
    </div>
  </div>`;
  document.body.appendChild(wrap);
  wrap.addEventListener('click', e=>{ if(e.target.matches('[data-close]')) wrap.remove(); });
  document.getElementById('saveInfo').addEventListener('click',()=>{
    committee.name=document.getElementById('eName').value.trim();
    committee.date=(()=>{const v=document.getElementById('eDate').value; const [y,m,d]=v.split('-'); return `${d}/${m}/${y}`; })();
    committee.time=document.getElementById('eTime').value.trim();
    committee.room=document.getElementById('eRoom').value.trim();
    initInfo(); wrap.remove();
  });
});

function exportList(){
  const lines=['MSSV,Ten,De tai,GVHD'];
  students.forEach(s=>lines.push(`${s.id},"${s.name}","${s.topic}","${s.supervisor}"`));
  const blob=new Blob([lines.join('\n')],{type:'text/csv;charset=utf-8;'});
  const a=document.createElement('a'); a.href=URL.createObjectURL(blob); a.download=`${committee.id}_students.csv`; a.click();
}

document.getElementById('exportBtn').addEventListener('click', exportList);

initInfo();
renderMembers();
renderStudents();
</script>
</body>
</html>
