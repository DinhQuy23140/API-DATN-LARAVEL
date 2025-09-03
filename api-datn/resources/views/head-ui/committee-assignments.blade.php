<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Phân sinh viên về hội đồng - Trưởng bộ môn</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
<style>
 body{font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial;}
 .sidebar-collapsed .sidebar-label{display:none;}
 .sidebar-collapsed .sidebar{width:72px;}
 .sidebar{width:260px;}
 .badge{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:500;background:#f1f5f9;color:#475569;}
 .table-fixed-layout td,.table-fixed-layout th{white-space:nowrap;}
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
          <h1 class="text-lg md:text-xl font-semibold">Phân sinh viên về hội đồng</h1>
          <nav class="text-xs text-slate-500 mt-0.5">
            <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
            <span class="mx-1">/</span>
            <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đợt đồ án</a>
            <span class="mx-1">/</span>
            <span class="text-slate-500">Phân sinh viên về hội đồng</span>
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
        <!-- Committees List -->
        <section class="bg-white border rounded-xl p-5">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
            <div class="flex flex-wrap gap-3 items-center">
              <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="committeeSearch" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-72" placeholder="Tìm theo mã / tên hội đồng" />
              </div>
              <select id="slotFilter" class="py-2 px-3 border border-slate-200 rounded text-sm">
                <option value="">-- Số SV --</option>
                <option value="0">0 SV</option>
                <option value="<5">< 5 SV</option>
                <option value=">=5">≥ 5 SV</option>
              </select>
            </div>
            <div class="flex items-center gap-2">
              <button id="assignStudentsBtn" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm flex items-center gap-1"><i class="ph ph-plus"></i> Phân SV</button>
              <button id="importStudentsBtn" class="px-3 py-2 border border-slate-200 rounded text-sm flex items-center gap-1"><i class="ph ph-upload"></i> Import SV</button>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm table-fixed-layout">
              <thead>
                <tr class="text-left text-slate-500 border-b">
                  <th class="py-3 px-3">Mã hội đồng</th>
                  <th class="py-3 px-3">Tên hội đồng</th>
                  <th class="py-3 px-3">Chủ tịch</th>
                  <th class="py-3 px-3">Thành viên</th>
                  <th class="py-3 px-3">Số SV</th>
                  <th class="py-3 px-3 text-right">Hành động</th>
                </tr>
              </thead>
              <tbody id="committeeRows"></tbody>
            </table>
          </div>
        </section>
      </div>
    </main>
  </div>
</div>
<!-- Modals injected -->
<script>
/* ========= Sidebar + Profile ========= */
(function(){
  const html=document.documentElement, sidebar=document.getElementById('sidebar');
  function setCollapsed(c){
    const h=document.querySelector('header'); const m=document.querySelector('main');
    if(c){
      html.classList.add('sidebar-collapsed');
      h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]');
      m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]');
    } else {
      html.classList.remove('sidebar-collapsed');
      h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]');
      m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]');
    }
  }
  document.getElementById('toggleSidebar')?.addEventListener('click',()=>{
    const c=!html.classList.contains('sidebar-collapsed');
    setCollapsed(c); localStorage.setItem('head_sidebar',c?'1':'0');
  });
  document.getElementById('openSidebar')?.addEventListener('click',()=> sidebar.classList.toggle('-translate-x-full'));
  if(localStorage.getItem('head_sidebar')==='1') setCollapsed(true);
  sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
  const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
  profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
  document.addEventListener('click', e=>{
    if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden');
  });
})();

/* ========= Toast ========= */
function toast(msg){
  let host=document.getElementById('toastHost');
  if(!host){
    host=document.createElement('div');
    host.id='toastHost';
    host.className='fixed bottom-4 right-4 space-y-2 z-50';
    document.body.appendChild(host);
  }
  const item=document.createElement('div');
  item.className='px-4 py-2 rounded-lg shadow bg-slate-800 text-white text-sm';
  item.textContent=msg;
  host.appendChild(item);
  setTimeout(()=>{ item.style.opacity='0'; item.style.transform='translateY(4px)'; item.style.transition='all .3s'; },2200);
  setTimeout(()=> item.remove(), 2600);
}

/* ========= Modal Utilities (focus trap, stack, ESC) ========= */
const OPEN_MODALS=[];
function focusable(root){
  return [...root.querySelectorAll(
    'a[href],button:not([disabled]),textarea,input:not([type=hidden]),select,[tabindex]:not([tabindex="-1"])'
  )].filter(el=>!el.hasAttribute('disabled') && !el.getAttribute('aria-hidden'));
}
function trapFocus(panel){
  const els=focusable(panel);
  if(!els.length) return;
  let first=els[0], last=els[els.length-1];
  first.focus();
  function handler(e){
    if(e.key==='Tab'){
      if(e.shiftKey && document.activeElement===first){ e.preventDefault(); last.focus(); }
      else if(!e.shiftKey && document.activeElement===last){ e.preventDefault(); first.focus(); }
    } else if(e.key==='Escape'){
      closeModal(panel.closest('[data-modal-wrapper]'));
    }
  }
  panel.addEventListener('keydown', handler);
  panel._focusHandler=handler;
}
function registerModal(wrapper){
  wrapper.setAttribute('data-modal-wrapper','');
  const panel=wrapper.querySelector('[data-modal-container]');
  OPEN_MODALS.push(wrapper);
  trapFocus(panel);
  wrapper.addEventListener('click',e=>{
    if(e.target.matches('[data-close]') || e.target.matches('[data-overlay]')) closeModal(wrapper);
  });
  panel.addEventListener('click',e=> e.stopPropagation());
  function esc(e){
    if(e.key==='Escape' && OPEN_MODALS[OPEN_MODALS.length-1]===wrapper) closeModal(wrapper);
  }
  document.addEventListener('keydown', esc);
  wrapper._escHandler=esc;
  panel.classList.add('opacity-0','translate-y-4');
  requestAnimationFrame(()=>{
    panel.classList.remove('opacity-0','translate-y-4');
    panel.classList.add('transition','duration-200');
  });
}
function closeModal(wrapper){
  if(!wrapper) return;
  const panel=wrapper.querySelector('[data-modal-container]');
  if(panel){
    panel.classList.add('opacity-0','translate-y-4');
    setTimeout(()=> wrapper.remove(),180);
  } else wrapper.remove();
  const i=OPEN_MODALS.indexOf(wrapper);
  if(i>-1) OPEN_MODALS.splice(i,1);
  document.removeEventListener('keydown', wrapper._escHandler);
}

/* ========= Storage ========= */
const COM_KEY='head_committees';
const COM_STU_KEY='head_committee_students';
const ALL_STU_KEY='head_all_students_for_committee_assign';
function load(k){
  try{
    const d=localStorage.getItem(k);
    return d?JSON.parse(d):(k===COM_STU_KEY?{}:[]);
  }catch{
    return k===COM_STU_KEY?{}:[];
  }
}
function save(k,v){ localStorage.setItem(k,JSON.stringify(v)); }

let committees=load(COM_KEY);
let commStudents=load(COM_STU_KEY);
let allStudents=load(ALL_STU_KEY);

/* ========= Seed ========= */
if(!committees.length){
  committees=[
    {id:'CNTT-01', name:'Hội đồng CNTT 01', chair:'PGS.TS. Trần Văn B', members:'TS. Lê Thị C; TS. Phạm Văn D; ThS. Nguyễn Văn G; TS. Nguyễn Thị E'},
    {id:'CNTT-02', name:'Hội đồng CNTT 02', chair:'TS. Phạm Văn D', members:'TS. Lê Thị C; ThS. Trần Thị F; ThS. Nguyễn Văn G; TS. Nguyễn Thị E'},
    {id:'CNTT-03', name:'Hội đồng CNTT 03', chair:'TS. Nguyễn Văn K', members:'TS. Lê Thị C; TS. Phạm Văn D; ThS. Nguyễn Văn G; TS. Nguyễn Thị E'}
  ];
  save(COM_KEY, committees);
}
if(!allStudents.length){
  allStudents=[
    {studentId:'20210001', studentName:'Nguyễn Văn A'},
    {studentId:'20210002', studentName:'Trần Thị B'},
    {studentId:'20210003', studentName:'Lê Văn C'},
    {studentId:'20210004', studentName:'Phạm Thị D'},
    {studentId:'20210005', studentName:'Nguyễn Thị E'},
    {studentId:'20210006', studentName:'Đỗ Văn F'}
  ];
  save(ALL_STU_KEY, allStudents);
}
if(Object.keys(commStudents).length===0){
  commStudents={
    'CNTT-01':['20210001','20210003'],
    'CNTT-02':['20210002'],
    'CNTT-03':[]
  };
  save(COM_STU_KEY, commStudents);
}

/* ========= Render Committees ========= */
function renderCommittees(){
  const q=(document.getElementById('committeeSearch').value||'').toLowerCase();
  const slot=document.getElementById('slotFilter').value;
  const rows=document.getElementById('committeeRows');
  const list=committees
    .filter(c=> c.id.toLowerCase().includes(q) || c.name.toLowerCase().includes(q))
    .filter(c=>{
      const cnt=(commStudents[c.id]||[]).length;
      if(!slot) return true;
      if(slot==='0') return cnt===0;
      if(slot==='<5') return cnt>0 && cnt<5;
      if(slot==='>=5') return cnt>=5;
      return true;
    });

  if(!list.length){
    rows.innerHTML='<tr><td colspan="6" class="py-4 px-3 text-slate-500">Không có dữ liệu.</td></tr>';
    return;
  }

  rows.innerHTML=list.map(c=>{
    const cnt=(commStudents[c.id]||[]).length;
    return `<tr class="border-b hover:bg-slate-50">
      <td class="py-3 px-3 font-medium">${c.id}</td>
      <td class="py-3 px-3"><a class="text-indigo-600 hover:underline" href="committee-detail.html?id=${encodeURIComponent(c.id)}">${c.name}</a></td>
      <td class="py-3 px-3">${c.chair}</td>
      <td class="py-3 px-3">${c.members}</td>
      <td class="py-3 px-3"><span class="badge">${cnt} SV</span></td>
      <td class="py-3 px-3 text-right">
        <button data-assign="${c.id}" class="px-2 py-1 border border-slate-200 rounded text-xs hover:bg-slate-100">Phân SV</button>
      </td>
    </tr>`;
  }).join('');

  rows.querySelectorAll('[data-assign]').forEach(btn=>{
    btn.addEventListener('click', e=>{
      e.stopPropagation();
      openAssignModal(btn.getAttribute('data-assign'));
    });
  });
}

/* ========= Modal: Assign Students ========= */
function openAssignModal(committeeId){
  if(!committees.some(c=>c.id===committeeId)){ toast('Không tìm thấy hội đồng'); return; }
  const wrapper=document.createElement('div');
  wrapper.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
  const current=new Set(commStudents[committeeId]||[]);
  wrapper.innerHTML=`
    <div class="absolute inset-0 bg-black/40" data-overlay data-close></div>
    <div class="bg-white w-full sm:max-w-3xl max-h-[90vh] overflow-hidden flex flex-col rounded-t-2xl sm:rounded-2xl shadow-lg relative z-10 outline-none"
         role="dialog" aria-modal="true" aria-label="Phân sinh viên" tabindex="-1" data-modal-container>
      <div class="p-5 border-b flex items-center justify-between">
        <h3 class="font-semibold text-base">Phân SV vào ${committeeId}</h3>
        <button data-close class="text-slate-500 hover:text-slate-700 p-1 rounded"><i class="ph ph-x text-lg"></i></button>
      </div>
      <div class="p-5 pt-4 flex-1 overflow-auto space-y-4">
        <div class="flex flex-col md:flex-row md:items-center gap-3 md:justify-between">
          <div class="flex flex-wrap gap-3 items-center">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="stuSearch" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500"
                     placeholder="Tìm theo tên / MSSV" />
            </div>
            <button id="addSelectedBtn" disabled
              class="px-3 py-2 bg-indigo-400 text-white rounded text-sm flex items-center gap-1 disabled:opacity-70 disabled:cursor-not-allowed">
              <i class="ph ph-user-plus"></i> Thêm đã chọn
            </button>
          </div>
          <div class="flex items-center gap-2">
            <button id="importInModalBtn"
              class="px-3 py-2 border border-slate-200 rounded text-sm flex items-center gap-1 hover:bg-slate-50">
              <i class="ph ph-upload"></i> Import SV
            </button>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-sm font-medium mb-2">Tất cả sinh viên</h4>
            <div id="allStuList" class="border rounded-lg divide-y max-h-80 overflow-auto text-sm bg-white"></div>
          </div>
            <div>
              <h4 class="text-sm font-medium mb-2">Đã trong hội đồng (${committeeId})</h4>
              <div id="assignedStuList" class="border rounded-lg divide-y max-h-80 overflow-auto text-sm bg-white"></div>
            </div>
        </div>
      </div>
      <div class="p-4 border-t flex justify-end gap-2 bg-slate-50">
        <button data-close class="px-3 py-2 text-slate-600 hover:bg-slate-100 rounded text-sm">Đóng</button>
        <button id="saveAssign" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm">Lưu thay đổi</button>
      </div>
    </div>`;
  document.body.appendChild(wrapper);
  registerModal(wrapper);

  const allList=wrapper.querySelector('#allStuList');
  const assignedList=wrapper.querySelector('#assignedStuList');
  const searchInput=wrapper.querySelector('#stuSearch');
  const addBtn=wrapper.querySelector('#addSelectedBtn');
  let tempAssigned=new Set(current);
  let selected=new Set();

  function updateAddBtn(){
    addBtn.disabled=selected.size===0;
    addBtn.classList.toggle('bg-indigo-600', selected.size>0);
    addBtn.classList.toggle('hover:bg-indigo-700', selected.size>0);
    addBtn.classList.toggle('bg-indigo-400', selected.size===0);
  }
  function renderLists(){
    const q=searchInput.value.toLowerCase();
    const avail=allStudents.filter(s=> !tempAssigned.has(s.studentId) && (!q || s.studentName.toLowerCase().includes(q) || s.studentId.includes(q)));
    allList.innerHTML=avail.length
      ? avail.map(s=>`
        <label class="flex items-center gap-2 p-2 hover:bg-slate-50 cursor-pointer">
          <input type="checkbox" data-sel="${s.studentId}" class="rounded border-slate-300">
          <span class="flex-1">${s.studentName} <span class="text-xs text-slate-500">(${s.studentId})</span></span>
        </label>`).join('')
      : `<div class="p-3 text-slate-500">Không còn sinh viên phù hợp.</div>`;
    assignedList.innerHTML=tempAssigned.size
      ? [...tempAssigned].map(id=>{
          const s=allStudents.find(x=>x.studentId===id); if(!s) return '';
          return `<div class="flex items-center justify-between gap-2 p-2 hover:bg-slate-50">
            <div>${s.studentName} <span class="text-xs text-slate-500">(${s.studentId})</span></div>
            <button data-remove="${s.studentId}" class="px-2 py-1 border border-slate-200 rounded text-xs hover:bg-rose-50 hover:border-rose-300 hover:text-rose-600">
              <i class="ph ph-x"></i> Bỏ
            </button>
          </div>`;
        }).join('')
      : `<div class="p-3 text-slate-500">Chưa có sinh viên.</div>`;
    allList.querySelectorAll('[data-sel]').forEach(cb=>{
      cb.addEventListener('change',()=>{
        const id=cb.getAttribute('data-sel');
        if(cb.checked) selected.add(id); else selected.delete(id);
        updateAddBtn();
      });
    });
    assignedList.querySelectorAll('[data-remove]').forEach(btn=>{
      btn.addEventListener('click',()=>{
        tempAssigned.delete(btn.getAttribute('data-remove'));
        renderLists();
      });
    });
    updateAddBtn();
  }

  searchInput.addEventListener('input', renderLists);
  addBtn.addEventListener('click',()=>{
    if(!selected.size) return;
    selected.forEach(id=> tempAssigned.add(id));
    const n=selected.size;
    selected.clear();
    renderLists();
    toast('Đã thêm '+n+' SV');
  });
  wrapper.querySelector('#saveAssign').addEventListener('click',()=>{
    commStudents[committeeId]=[...tempAssigned];
    save(COM_STU_KEY, commStudents);
    toast('Lưu phân công thành công');
    closeModal(wrapper);
    renderCommittees();
  });
  wrapper.querySelector('#importInModalBtn').addEventListener('click',()=>{
    openImportStudents(tempAssigned, ids=>{
      ids.forEach(id=> tempAssigned.add(id));
      renderLists();
      toast('Import SV xong');
    });
  });
  renderLists();
}

/* ========= Import CSV (Generic) ========= */
function openImportStudents(targetSet,onAdd){
  const wrap=document.createElement('div');
  wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
  wrap.innerHTML=`
    <div class="absolute inset-0 bg-black/40" data-overlay data-close></div>
    <div class="bg-white w-full sm:max-w-lg rounded-t-2xl sm:rounded-2xl shadow-lg p-5 relative z-10 outline-none"
         role="dialog" aria-modal="true" aria-label="Import sinh viên" tabindex="-1" data-modal-container>
      <div class="flex items-start justify-between mb-3">
        <h3 class="font-semibold text-base">Import sinh viên</h3>
        <button data-close class="text-slate-500 hover:text-slate-700 p-1 rounded"><i class="ph ph-x text-lg"></i></button>
      </div>
      <div class="space-y-3 text-sm">
        <p class="text-slate-600">Chọn file CSV (cột: studentId,studentName). Dòng tiêu đề sẽ tự bỏ qua.</p>
        <input id="csvFileImp" type="file" accept=".csv,text/csv"
          class="block w-full text-sm border border-slate-200 rounded p-2 file:mr-3 file:py-2 file:px-3 file:rounded file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-xs hover:file:bg-indigo-100" />
        <div id="impStatus" class="text-xs text-slate-500"></div>
      </div>
      <div class="mt-5 flex justify-end gap-2">
        <button data-close class="px-3 py-2 text-slate-600 hover:bg-slate-100 rounded text-sm">Đóng</button>
        <button id="doImport" disabled class="px-3 py-2 bg-indigo-400 cursor-not-allowed text-white rounded text-sm">Import</button>
      </div>
    </div>`;
  document.body.appendChild(wrap);
  registerModal(wrap);

  const fileInput=wrap.querySelector('#csvFileImp');
  const statusEl=wrap.querySelector('#impStatus');
  const doBtn=wrap.querySelector('#doImport');
  let parsed=[];

  function parseCSV(t){
    return t.replace(/\r/g,'')
      .split(/\n+/)
      .filter(l=>l.trim())
      .map(l=>l.split(',').map(c=>c.trim()))
      .filter(r=>r.length>=2)
      .filter((r,i)=> !(i===0 && ['studentid','mssv'].includes(r[0].toLowerCase())));
  }

  fileInput.addEventListener('change',()=>{
    parsed=[]; statusEl.textContent=''; doBtn.disabled=true;
    doBtn.classList.add('bg-indigo-400','cursor-not-allowed');
    doBtn.classList.remove('bg-indigo-600','hover:bg-indigo-700');
    const f=fileInput.files?.[0]; if(!f) return;
    if(f.size>2*1024*1024){ statusEl.textContent='File quá lớn (>2MB)'; return; }
    const reader=new FileReader();
    reader.onload=e=>{
      try{
        const rows=parseCSV(e.target.result||'');
        parsed=rows.map(r=>({studentId:r[0], studentName:r[1]})).filter(o=>o.studentId);
      }catch{
        statusEl.textContent='Lỗi đọc CSV'; return;
      }
      if(!parsed.length){ statusEl.textContent='Không có dòng hợp lệ'; return; }
      statusEl.textContent=`Đã đọc ${parsed.length} dòng.`;
      doBtn.disabled=false;
      doBtn.classList.remove('bg-indigo-400','cursor-not-allowed');
      doBtn.classList.add('bg-indigo-600','hover:bg-indigo-700');
    };
    reader.onerror=()=> statusEl.textContent='Không thể đọc file';
    reader.readAsText(f,'UTF-8');
  });

  doBtn.addEventListener('click',()=>{
    let added=0;
    parsed.forEach(p=>{
      if(!allStudents.some(s=>s.studentId===p.studentId)){
        allStudents.push(p); added++;
      }
      if(targetSet) targetSet.add(p.studentId);
    });
    save(ALL_STU_KEY, allStudents);
    if(onAdd) onAdd(parsed.map(p=>p.studentId));
    toast('Import: thêm '+added+' SV');
    closeModal(wrap);
  });
}

/* ========= Import to pool (top button) ========= */
function openTopImport(){
  openImportStudents(null,null);
}

/* ========= Events ========= */
['committeeSearch','slotFilter'].forEach(id=>{
  document.getElementById(id).addEventListener('input', renderCommittees);
  document.getElementById(id).addEventListener('change', renderCommittees);
});

document.getElementById('assignStudentsBtn').addEventListener('click',()=>{
  if(!committees.length){ toast('Chưa có hội đồng'); return; }
  // default first committee
  openAssignModal(committees[0].id);
});

document.getElementById('importStudentsBtn').addEventListener('click', openTopImport);

renderCommittees();
</script>
</body>
</html>
