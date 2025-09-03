<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Phân công phản biện kín - Trưởng bộ môn</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
<style>
  body { font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial; }
  .sidebar-collapsed .sidebar-label { display:none; }
  .sidebar-collapsed .sidebar { width:72px; }
  .sidebar { width:260px; }
</style>
</head>
<body class="bg-slate-50 text-slate-800">
<div class="flex min-h-screen">
  <!-- Sidebar (reuse simple structure) -->
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
          <h1 class="text-lg md:text-xl font-semibold">Phân công phản biện kín</h1>
          <nav class="text-xs text-slate-500 mt-0.5">
            <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
            <span class="mx-1">/</span>
            <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đợt đồ án</a>
            <span class="mx-1">/</span>
            <span class="text-slate-500">Phân công phản biện kín</span>
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
      <div class="max-w-7xl mx-auto space-y-8">
        <!-- Panel 1: Quản lý giảng viên phản biện kín -->
        <section class="bg-white border rounded-xl p-5">
          <div class="flex items-center justify-between mb-4 gap-3 flex-wrap">
            <h2 class="font-semibold flex items-center gap-2"><i class="ph ph-users"></i> Danh sách giảng viên phản biện kín</h2>
            <div class="flex items-center gap-2 flex-wrap">
              <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="lectSearch" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-56" placeholder="Tìm theo tên/email" />
              </div>
              <button id="addLecturerBtn" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm flex items-center gap-1"><i class="ph ph-plus"></i> Thêm</button>
              <button id="importLecturersBtn" class="px-3 py-2 border border-slate-200 rounded text-sm flex items-center gap-1"><i class="ph ph-upload"></i> Import</button>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-500 border-b">
                  <th class="py-3 px-3">Mã GV</th>
                  <th class="py-3 px-3">Họ tên</th>
                  <th class="py-3 px-3">Email</th>
                  <th class="py-3 px-3">Chuyên môn</th>
                  <th class="py-3 px-3">Số đề cương đã PB</th>
                  <th class="py-3 px-3 text-right">Hành động</th>
                </tr>
              </thead>
              <tbody id="blindLecturerRows"></tbody>
            </table>
          </div>
        </section>

        <!-- Panel 2: Phân công đề cương (ẩn GVHD) -->
        <section class="bg-white border rounded-xl p-5">
          <div class="flex items-center justify-between mb-4 gap-3 flex-wrap">
            <h2 class="font-semibold flex items-center gap-2"><i class="ph ph-file-text"></i> Phân công đề cương sinh viên</h2>
            <div class="flex items-center gap-2 flex-wrap">
              <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="assignSearch" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tên/MSSV/đề tài" />
              </div>
              <button id="addAssignBtn" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm flex items-center gap-1"><i class="ph ph-plus"></i> Thêm</button>
              <button id="importAssignBtn" class="px-3 py-2 border border-slate-200 rounded text-sm flex items-center gap-1"><i class="ph ph-upload"></i> Import</button>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-500 border-b">
                  <th class="py-3 px-3">MSSV</th>
                  <th class="py-3 px-3">Tên SV</th>
                  <th class="py-3 px-3">Đề tài (mã ẩn)</th>
                  <th class="py-3 px-3">GV phản biện kín</th>
                  <th class="py-3 px-3">Ngày phân công</th>
                  <th class="py-3 px-3">Trạng thái</th>
                  <th class="py-3 px-3 text-right">Hành động</th>
                </tr>
              </thead>
              <tbody id="blindAssignRows"></tbody>
            </table>
          </div>
        </section>
      </div>
    </main>
  </div>
</div>
<!-- Modals will be injected -->
<script>
(function(){
  const html=document.documentElement, sidebar=document.getElementById('sidebar');
  function setCollapsed(c){const h=document.querySelector('header'); const m=document.querySelector('main'); if(c){html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); } else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]'); }}
  document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('head_sidebar',''+(c?1:0));});
  document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
  if(localStorage.getItem('head_sidebar')==='1') setCollapsed(true);
  sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
  const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
  profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
  document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
})();

// Storage keys
const LECT_KEY='head_blind_review_lecturers';
const ASSIGN_KEY='head_blind_review_assignments';

function load(key){ try{ const d=localStorage.getItem(key); return d? JSON.parse(d):[];}catch{return [];} }
function save(key,val){ localStorage.setItem(key, JSON.stringify(val)); }

let lecturers=load(LECT_KEY);
let assignments=load(ASSIGN_KEY);

function renderLecturers(){
  const q=(document.getElementById('lectSearch').value||'').toLowerCase();
  const rows=document.getElementById('blindLecturerRows');
  const list=lecturers.filter(l=> l.name.toLowerCase().includes(q)||l.email.toLowerCase().includes(q));
  if(!list.length){ rows.innerHTML="<tr><td colspan='6' class='py-4 px-3 text-slate-500'>Không có dữ liệu.</td></tr>"; return; }
  rows.innerHTML=list.map(l=>`<tr class='border-b hover:bg-slate-50'>
    <td class='py-3 px-3 font-medium'>${l.code}</td>
    <td class='py-3 px-3'><a href='blind-review-lecturer.html?code=${encodeURIComponent(l.code)}' class='text-indigo-600 hover:underline'>${l.name}</a></td>
    <td class='py-3 px-3'>${l.email}</td>
    <td class='py-3 px-3'>${l.expertise||'-'}</td>
    <td class='py-3 px-3'>${assignments.filter(a=>a.lecturerCode===l.code).length}</td>
    <td class='py-3 px-3 text-right'>
      <button data-edit='${l.code}' class='px-2 py-1 border border-slate-200 rounded text-xs'>Sửa</button>
      <button data-del='${l.code}' class='px-2 py-1 border border-slate-200 rounded text-xs text-rose-600'>Xóa</button>
    </td>
  </tr>`).join('');
  rows.querySelectorAll('[data-edit]').forEach(b=>b.addEventListener('click',()=>openLecturerModal(lecturers.find(x=>x.code===b.getAttribute('data-edit')))));
  rows.querySelectorAll('[data-del]').forEach(b=>b.addEventListener('click',()=>{ if(confirm('Xóa giảng viên?')){ lecturers=lecturers.filter(x=>x.code!==b.getAttribute('data-del')); save(LECT_KEY, lecturers); renderLecturers(); }}));
}

function renderAssignments(){
  const q=(document.getElementById('assignSearch').value||'').toLowerCase();
  const rows=document.getElementById('blindAssignRows');
  const list=assignments.filter(a=> a.studentName.toLowerCase().includes(q)||a.studentId.includes(q)||(a.topicMasked||'').toLowerCase().includes(q));
  if(!list.length){ rows.innerHTML="<tr><td colspan='7' class='py-4 px-3 text-slate-500'>Không có dữ liệu.</td></tr>"; return; }
  rows.innerHTML=list.map(a=>`<tr class='border-b hover:bg-slate-50'>
    <td class='py-3 px-3 font-medium'>${a.studentId}</td>
    <td class='py-3 px-3'>${a.studentName}</td>
    <td class='py-3 px-3'>${a.topicMasked||'-'}</td>
    <td class='py-3 px-3'>${a.lecturerCode? (lecturers.find(l=>l.code===a.lecturerCode)?.name||a.lecturerCode):'<span class="text-slate-400">Chưa phân</span>'}</td>
    <td class='py-3 px-3'>${a.assignedDate||'-'}</td>
    <td class='py-3 px-3'>${a.status||'Chưa phản biện'}</td>
    <td class='py-3 px-3 text-right'>
      <button data-edit-assign='${a.id}' class='px-2 py-1 border border-slate-200 rounded text-xs'>Sửa</button>
      <button data-del-assign='${a.id}' class='px-2 py-1 border border-slate-200 rounded text-xs text-rose-600'>Xóa</button>
    </td>
  </tr>`).join('');
  rows.querySelectorAll('[data-edit-assign]').forEach(b=>b.addEventListener('click',()=>openAssignModal(assignments.find(x=>x.id===b.getAttribute('data-edit-assign')))));
  rows.querySelectorAll('[data-del-assign]').forEach(b=>b.addEventListener('click',()=>{ if(confirm('Xóa phân công?')){ assignments=assignments.filter(x=>x.id!==b.getAttribute('data-del-assign')); save(ASSIGN_KEY, assignments); renderAssignments(); }}));
}

function openLecturerModal(data){
  data=data||{code:'', name:'', email:'', expertise:''};
  const isNew=!data.code;
  const wrap=document.createElement('div');
  wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
  wrap.innerHTML=`<div class='absolute inset-0 bg-black/40' data-close></div>
  <div class='bg-white w-full sm:max-w-lg rounded-t-2xl sm:rounded-2xl shadow-lg p-5 relative'>
    <h3 class='font-semibold mb-3'>${isNew?'Thêm giảng viên':'Cập nhật giảng viên'}</h3>
    <div class='grid grid-cols-1 md:grid-cols-2 gap-3 text-sm'>
      <div><label class='text-xs text-slate-500'>Mã GV</label><input id='lCode' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${data.code}' ${isNew?'':'readonly'} /></div>
      <div><label class='text-xs text-slate-500'>Họ tên</label><input id='lName' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${data.name}' /></div>
      <div class='md:col-span-2'><label class='text-xs text-slate-500'>Email</label><input id='lEmail' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${data.email}' /></div>
      <div class='md:col-span-2'><label class='text-xs text-slate-500'>Chuyên môn</label><input id='lExpertise' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${data.expertise}' /></div>
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
      code:document.getElementById('lCode').value.trim(),
      name:document.getElementById('lName').value.trim(),
      email:document.getElementById('lEmail').value.trim(),
      expertise:document.getElementById('lExpertise').value.trim()
    };
    if(!item.code||!item.name){ alert('Nhập mã và họ tên'); return; }
    if(isNew){ if(lecturers.some(x=>x.code===item.code)){ alert('Mã đã tồn tại'); return;} lecturers.push(item);} else { const idx=lecturers.findIndex(x=>x.code===item.code); lecturers[idx]=item; }
    save(LECT_KEY, lecturers); wrap.remove(); renderLecturers();
  });
}

function openAssignModal(data){
  data=data||{id:'', studentId:'', studentName:'', topicMasked:'', lecturerCode:'', assignedDate:'', status:'Chưa phản biện'};
  const isNew=!data.id;
  const wrap=document.createElement('div');
  wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
  const lecturerOptions=lecturers.map(l=>`<option value='${l.code}' ${l.code===data.lecturerCode?'selected':''}>${l.name} (${l.code})</option>`).join('');
  wrap.innerHTML=`<div class='absolute inset-0 bg-black/40' data-close></div>
  <div class='bg-white w-full sm:max-w-xl rounded-t-2xl sm:rounded-2xl shadow-lg p-5 relative'>
    <h3 class='font-semibold mb-3'>${isNew?'Thêm phân công':'Cập nhật phân công'}</h3>
    <div class='grid grid-cols-1 md:grid-cols-2 gap-3 text-sm'>
      <div><label class='text-xs text-slate-500'>MSSV</label><input id='aStuId' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${data.studentId}' ${isNew?'':'readonly'} /></div>
      <div><label class='text-xs text-slate-500'>Tên SV</label><input id='aStuName' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${data.studentName}' /></div>
      <div class='md:col-span-2'><label class='text-xs text-slate-500'>Đề tài (mã ẩn)</label><input id='aTopic' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${data.topicMasked}' placeholder='VD: T-023 (ẩn thông tin thực)' /></div>
      <div><label class='text-xs text-slate-500'>GV phản biện</label><select id='aLecturer' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5'><option value=''>-- Chưa phân --</option>${lecturerOptions}</select></div>
      <div><label class='text-xs text-slate-500'>Ngày phân công</label><input id='aDate' type='date' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${data.assignedDate}' /></div>
      <div class='md:col-span-2'><label class='text-xs text-slate-500'>Trạng thái</label><select id='aStatus' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5'><option ${data.status==='Chưa phản biện'?'selected':''}>Chưa phản biện</option><option ${data.status==='Đang phản biện'?'selected':''}>Đang phản biện</option><option ${data.status==='Hoàn thành'?'selected':''}>Hoàn thành</option></select></div>
    </div>
    <div class='mt-5 flex justify-end gap-2'>
      <button class='px-3 py-2 text-slate-600 hover:bg-slate-100 rounded text-sm' data-close>Hủy</button>
      <button id='saveAssign' class='px-3 py-2 bg-indigo-600 text-white rounded text-sm'>Lưu</button>
    </div>
  </div>`;
  document.body.appendChild(wrap);
  wrap.addEventListener('click', e=>{ if(e.target.matches('[data-close]')) wrap.remove(); });
  document.getElementById('saveAssign').addEventListener('click',()=>{
    const item={
      id: isNew ? (crypto.randomUUID?crypto.randomUUID():('A_'+Date.now())) : data.id,
      studentId: document.getElementById('aStuId').value.trim(),
      studentName: document.getElementById('aStuName').value.trim(),
      topicMasked: document.getElementById('aTopic').value.trim(),
      lecturerCode: document.getElementById('aLecturer').value.trim(),
      assignedDate: document.getElementById('aDate').value || '',
      status: document.getElementById('aStatus').value
    };
    if(!item.studentId||!item.studentName){ alert('Nhập MSSV & tên SV'); return; }
    if(isNew){ if(assignments.some(x=>x.studentId===item.studentId)){ alert('SV đã được khai báo'); return;} assignments.push(item);} else { const idx=assignments.findIndex(x=>x.id===item.id); assignments[idx]=item; }
    save(ASSIGN_KEY, assignments); wrap.remove(); renderAssignments(); renderLecturers();
  });
}

// Import handlers (simple textarea paste)
function openImport(type){
  const wrap=document.createElement('div');
  wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
  const templateHint = type==='lect' ? 'code,name,email,expertise' : 'studentId,studentName,topicMasked,lecturerCode,assignedDate(YYYY-MM-DD),status';
  wrap.innerHTML=`<div class='absolute inset-0 bg-black/40' data-close></div>
  <div class='bg-white w-full sm:max-w-lg rounded-t-2xl sm:rounded-2xl shadow-lg p-5 relative'>
    <h3 class='font-semibold mb-3'>Import ${type==='lect'?'giảng viên':'phân công'}</h3>
    <div class='space-y-3 text-sm'>
      <p class='text-slate-600'>Chọn file CSV (UTF-8). Dòng tiêu đề (nếu có) sẽ tự bỏ qua.<br/>Định dạng cột: <span class='font-mono text-xs bg-slate-100 px-1 py-0.5 rounded'>${templateHint}</span></p>
      <input id='csvFile' type='file' accept='.csv,text/csv' class='block w-full text-sm border border-slate-200 rounded p-2 file:mr-3 file:py-2 file:px-3 file:rounded file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-xs hover:file:bg-indigo-100' />
      <div id='impStatus' class='text-xs text-slate-500'></div>
    </div>
    <div class='mt-5 flex justify-end gap-2'>
      <button class='px-3 py-2 text-slate-600 hover:bg-slate-100 rounded text-sm' data-close>Đóng</button>
      <button id='doImport' disabled class='px-3 py-2 bg-indigo-400 cursor-not-allowed text-white rounded text-sm'>Import</button>
    </div>
  </div>`;
  document.body.appendChild(wrap);
  const close=()=>wrap.remove();
  wrap.addEventListener('click', e=>{ if(e.target.matches('[data-close]')) close(); });
  const fileInput=wrap.querySelector('#csvFile');
  const statusEl=wrap.querySelector('#impStatus');
  const doBtn=wrap.querySelector('#doImport');

  let parsed=[]; // temp store

  function parseCSV(text){
    const lines=text.replace(/\r/g,'').split(/\n+/).filter(l=>l.trim());
    const out=[]; for(let i=0;i<lines.length;i++){ const cols=lines[i].split(',').map(c=>c.trim()); if(cols.length<2) continue; if(type==='lect'){ if(i===0 && cols[0].toLowerCase()==='code') continue; out.push({code:cols[0], name:cols[1]||'', email:cols[2]||'', expertise:cols[3]||''}); } else { if(i===0 && cols[0].toLowerCase()==='studentid') continue; out.push({id:crypto.randomUUID?crypto.randomUUID():('A_'+Date.now()+i), studentId:cols[0], studentName:cols[1]||'', topicMasked:cols[2]||'', lecturerCode:cols[3]||'', assignedDate:cols[4]||'', status:cols[5]||'Chưa phản biện'}); } }
    return out;
  }

  fileInput.addEventListener('change', ()=>{
    parsed=[]; statusEl.textContent=''; doBtn.disabled=true; doBtn.classList.add('bg-indigo-400','cursor-not-allowed'); doBtn.classList.remove('bg-indigo-600');
    const f=fileInput.files && fileInput.files[0]; if(!f) return;
    if(f.size>2*1024*1024){ statusEl.textContent='File quá lớn (>2MB)'; return; }
    const reader=new FileReader();
    reader.onload=e=>{
      try{ parsed=parseCSV(e.target.result||''); }catch(err){ statusEl.textContent='Lỗi đọc CSV'; return; }
      if(!parsed.length){ statusEl.textContent='Không tìm thấy dòng hợp lệ.'; return; }
      statusEl.textContent=`Đã đọc ${parsed.length} dòng. Sẵn sàng import.`;
      doBtn.disabled=false; doBtn.classList.remove('bg-indigo-400','cursor-not-allowed'); doBtn.classList.add('bg-indigo-600');
    };
    reader.onerror=()=>{ statusEl.textContent='Không thể đọc file.'; };
    reader.readAsText(f,'UTF-8');
  });

  doBtn.addEventListener('click', ()=>{
    if(!parsed.length) return;
    if(type==='lect'){
      let added=0; parsed.forEach(it=>{ if(it.code && !lecturers.some(x=>x.code===it.code)){ lecturers.push(it); added++; } });
      save(LECT_KEY, lecturers); renderLecturers(); alert('Import xong: thêm '+added+' mục mới.');
    } else {
      let added=0; parsed.forEach(it=>{ if(it.studentId && !assignments.some(x=>x.studentId===it.studentId)){ assignments.push(it); added++; } });
      save(ASSIGN_KEY, assignments); renderAssignments(); renderLecturers(); alert('Import xong: thêm '+added+' mục mới.');
    }
    close();
  });
}

// Event bindings
['lectSearch','assignSearch'].forEach(id=> document.getElementById(id).addEventListener('input', ()=>{ id==='lectSearch'?renderLecturers():renderAssignments(); }));

document.getElementById('addLecturerBtn').addEventListener('click',()=>openLecturerModal());
document.getElementById('importLecturersBtn').addEventListener('click',()=>openImport('lect'));
document.getElementById('addAssignBtn').addEventListener('click',()=>openAssignModal());
document.getElementById('importAssignBtn').addEventListener('click',()=>openImport('assign'));

// Initial sample data if empty
if(!lecturers.length){ lecturers=[
  { code:'GV001', name:'TS. Trần Văn B', email:'gvb@uni.edu', expertise:'AI' },
  { code:'GV002', name:'TS. Lê Thị C', email:'gvc@uni.edu', expertise:'Hệ thống' }
]; save(LECT_KEY, lecturers); }
if(!assignments.length){ assignments=[
  { id:'A1', studentId:'20210001', studentName:'Nguyễn Văn A', topicMasked:'T-001', lecturerCode:'GV001', assignedDate:'2025-08-05', status:'Đang phản biện' },
  { id:'A2', studentId:'20210002', studentName:'Trần Thị B', topicMasked:'T-002', lecturerCode:'GV002', assignedDate:'2025-08-06', status:'Chưa phản biện' }
]; save(ASSIGN_KEY, assignments); }

renderLecturers();
renderAssignments();
</script>
</body>
</html>
