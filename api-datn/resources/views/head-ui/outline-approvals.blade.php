<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Duyệt đề cương sinh viên - Trưởng bộ môn</title>
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
 .status-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:500;line-height:1;letter-spacing:.25px;box-shadow:0 0 0 1px rgba(0,0,0,0.04),0 1px 2px rgba(0,0,0,0.04);position:relative;}
 .status-badge i{font-size:13px;line-height:1;}
 .st-pending{background:linear-gradient(90deg,#fefce8,#fef3c7);color:#92400e;}
 .st-approved{background:linear-gradient(90deg,#ecfdf5,#d1fae5);color:#065f46;}
 .st-rejected{background:linear-gradient(90deg,#fef2f2,#fee2e2);color:#991b1b;}
 .st-draft{background:linear-gradient(90deg,#f1f5f9,#e2e8f0);color:#475569;}
 .status-badge:after{content:"";position:absolute;inset:0;border-radius:999px;box-shadow:inset 0 0 0 1px rgba(0,0,0,0.05);} 
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
          <h1 class="text-lg md:text-xl font-semibold">Duyệt đề cương sinh viên</h1>
          <nav class="text-xs text-slate-500 mt-0.5">
            <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
            <span class="mx-1">/</span>
            <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đợt đồ án</a>
            <span class="mx-1">/</span>
            <span class="text-slate-500">Duyệt đề cương</span>
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
          <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-6 md:justify-between mb-5">
            <div class="flex-1 flex flex-wrap gap-3 items-center">
              <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="searchBox" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-72" placeholder="Tìm theo tên/MSSV/đề tài" />
              </div>
              <select id="statusFilter" class="py-2 px-3 border border-slate-200 rounded text-sm">
                <option value="">-- Trạng thái --</option>
                <option value="pending">Chờ duyệt</option>
                <option value="approved">Đã duyệt</option>
                <option value="rejected">Từ chối</option>
                <option value="draft">Bản nháp</option>
              </select>
              <select id="lectFilter" class="py-2 px-3 border border-slate-200 rounded text-sm">
                <option value="">-- GVHD --</option>
              </select>
            </div>
            <div class="flex items-center gap-2">
              <button id="bulkApprove" class="px-3 py-2 bg-emerald-600 text-white rounded text-sm flex items-center gap-1 disabled:opacity-40 disabled:cursor-not-allowed"><i class="ph ph-check"></i> Duyệt nhanh</button>
              <button id="bulkReject" class="px-3 py-2 bg-rose-600 text-white rounded text-sm flex items-center gap-1 disabled:opacity-40 disabled:cursor-not-allowed"><i class="ph ph-x"></i> Từ chối nhanh</button>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-500 border-b">
                  <th class="py-3 px-3 w-10"><input id="chkAll" type="checkbox" class="rounded border-slate-300" /></th>
                  <th class="py-3 px-3">Sinh viên</th>
                  <th class="py-3 px-3">MSSV</th>
                  <th class="py-3 px-3">Đề tài</th>
                  <th class="py-3 px-3">GVHD</th>
                  <th class="py-3 px-3">File</th>
                  <th class="py-3 px-3">Ngày nộp</th>
                  <th class="py-3 px-3">Trạng thái</th>
                  <th class="py-3 px-3">Ghi chú</th>
                  <th class="py-3 px-3 text-right">Hành động</th>
                </tr>
              </thead>
              <tbody id="outlineRows"></tbody>
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
  function setCollapsed(c){const h=document.querySelector('header'); const m=document.querySelector('main'); if(c){html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]');} else {html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]');}}
  document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('head_sidebar',''+(c?1:0));});
  document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
  if(localStorage.getItem('head_sidebar')==='1') setCollapsed(true);
  sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
  const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
  profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
  document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
})();

// Storage key (simple demo, can integrate real data later)
const KEY='head_outline_approvals';
function load(){ try{const d=localStorage.getItem(KEY); return d?JSON.parse(d):[];}catch{return [];} }
function save(v){ localStorage.setItem(KEY, JSON.stringify(v)); }

let outlines=load();
if(!outlines.length){
  outlines=[
    {id:'OL1', studentId:'20210004', studentName:'Phạm Thị D', topic:'Ứng dụng điểm danh thông minh', advisor:'TS. Lê Thị C', file:'outline_20210004.pdf', submitDate:'2025-08-05', status:'pending', note:''},
    {id:'OL2', studentId:'20210005', studentName:'Nguyễn Thị E', topic:'Nền tảng học liệu số', advisor:'TS. Trần Văn B', file:'outline_20210005.pdf', submitDate:'2025-08-05', status:'approved', note:'Tốt'},
    {id:'OL3', studentId:'20210006', studentName:'Đỗ Văn F', topic:'Hệ thống phân tích log', advisor:'ThS. Nguyễn Văn G', file:'outline_20210006.pdf', submitDate:'2025-08-06', status:'pending', note:''},
    {id:'OL4', studentId:'20210007', studentName:'Vũ Thị H', topic:'Cổng thông tin nội bộ', advisor:'TS. Lê Thị C', file:'outline_20210007.pdf', submitDate:'2025-08-06', status:'rejected', note:'Thiếu mục tiêu rõ ràng'},
    {id:'OL5', studentId:'20210008', studentName:'Nguyễn Văn I', topic:'Chatbot hỗ trợ sinh viên', advisor:'TS. Trần Văn B', file:'outline_20210008.pdf', submitDate:'2025-08-07', status:'draft', note:'Chưa nộp bản chính thức'}
  ];
  save(outlines);
}

function statusBadge(st){
  const map={pending:'st-pending', approved:'st-approved', rejected:'st-rejected', draft:'st-draft'};
  const txt={pending:'Chờ duyệt', approved:'Đã duyệt', rejected:'Từ chối', draft:'Bản nháp'};
  const icon={pending:'ph-hourglass-high', approved:'ph-check-circle', rejected:'ph-x-circle', draft:'ph-note'};
  return `<span class="status-badge ${map[st]||'st-draft'}"><i class="ph ${icon[st]||'ph-note'}"></i>${txt[st]||st}</span>`;
}

function rebuildAdvisorFilter(){
  const lectFilter=document.getElementById('lectFilter');
  const advisors=[...new Set(outlines.map(o=>o.advisor))].sort();
  const current=lectFilter.value;
  lectFilter.innerHTML='<option value="">-- GVHD --</option>'+advisors.map(a=>`<option value="${a}">${a}</option>`).join('');
  if(advisors.includes(current)) lectFilter.value=current;
}

function render(){
  const q=document.getElementById('searchBox').value.toLowerCase();
  const st=document.getElementById('statusFilter').value;
  const adv=document.getElementById('lectFilter').value;
  const rows=document.getElementById('outlineRows');
  let list=outlines.filter(o=>
    (!q || o.studentName.toLowerCase().includes(q) || o.studentId.includes(q) || o.topic.toLowerCase().includes(q)) &&
    (!st || o.status===st) &&
    (!adv || o.advisor===adv)
  );
  if(!list.length){ rows.innerHTML='<tr><td colspan="10" class="py-4 px-3 text-slate-500">Không có dữ liệu.</td></tr>'; updateBulkButtons(); return; }
  rows.innerHTML=list.map(o=>`<tr class='border-b hover:bg-slate-50'>
    <td class='py-3 px-3 align-top'><input type='checkbox' data-chk='${o.id}' class='rounded border-slate-300' /></td>
    <td class='py-3 px-3 align-top'><a class='text-indigo-600 hover:underline' href='supervised-student-detail.html?id=${o.studentId}&name=${encodeURIComponent(o.studentName)}'>${o.studentName}</a></td>
    <td class='py-3 px-3 align-top'>${o.studentId}</td>
    <td class='py-3 px-3 align-top'>${o.topic}</td>
    <td class='py-3 px-3 align-top'>${o.advisor}</td>
    <td class='py-3 px-3 align-top'><a href='#' class='text-indigo-600 hover:underline text-xs'>${o.file}</a></td>
    <td class='py-3 px-3 align-top'>${o.submitDate||'-'}</td>
    <td class='py-3 px-3 align-top'>${statusBadge(o.status)}</td>
    <td class='py-3 px-3 align-top max-w-[200px] text-xs text-slate-600'>${o.note||''}</td>
    <td class='py-3 px-3 align-top text-right'>
      <div class='inline-flex items-center gap-1'>
        <button data-approve='${o.id}' class='px-2 py-1 border border-slate-200 rounded text-xs text-emerald-600 hover:bg-emerald-50 ${o.status==='approved'?'opacity-40 cursor-not-allowed':''}'>Duyệt</button>
        <button data-reject='${o.id}' class='px-2 py-1 border border-slate-200 rounded text-xs text-rose-600 hover:bg-rose-50 ${o.status==='rejected'?'opacity-40 cursor-not-allowed':''}'>Từ chối</button>
        <button data-revert='${o.id}' class='px-2 py-1 border border-slate-200 rounded text-xs ${o.status==='pending'?'text-slate-400 cursor-not-allowed opacity-40':'text-slate-600 hover:bg-slate-50'}'>Thu hồi</button>
      </div>
    </td>
  </tr>`).join('');
  // bind
  rows.querySelectorAll('[data-approve]').forEach(b=>b.addEventListener('click',()=>updateStatus(b.getAttribute('data-approve'),'approved')));
  rows.querySelectorAll('[data-reject]').forEach(b=>b.addEventListener('click',()=>updateStatus(b.getAttribute('data-reject'),'rejected')));
  rows.querySelectorAll('[data-revert]').forEach(b=>b.addEventListener('click',()=>{const id=b.getAttribute('data-revert'); const o=outlines.find(x=>x.id===id); if(!o||o.status==='pending') return; o.status='pending'; o.note=''; save(outlines); render();}));
  rows.querySelectorAll('input[type=checkbox][data-chk]').forEach(ch=>ch.addEventListener('change',updateBulkButtons));
  updateBulkButtons();
}

function updateStatus(id,newSt){
  const o=outlines.find(x=>x.id===id); if(!o) return; if(o.status===newSt) return; o.status=newSt; if(newSt==='approved'&& !o.note) o.note=''; if(newSt==='rejected'&&!o.note) o.note=''; save(outlines); render();
}

function updateBulkButtons(){
  const anySel=[...document.querySelectorAll('#outlineRows input[type=checkbox][data-chk]:checked')].length>0;
  document.getElementById('bulkApprove').disabled=!anySel;
  document.getElementById('bulkReject').disabled=!anySel;
  document.getElementById('chkAll').checked = anySel && [...document.querySelectorAll('#outlineRows input[type=checkbox][data-chk]')].every(c=>c.checked);
}

function bulkChange(st){
  const ids=[...document.querySelectorAll('#outlineRows input[type=checkbox][data-chk]:checked')].map(c=>c.getAttribute('data-chk'));
  outlines.forEach(o=>{ if(ids.includes(o.id)) o.status=st; });
  save(outlines); render();
}

document.getElementById('searchBox').addEventListener('input',render);
['statusFilter','lectFilter'].forEach(id=>document.getElementById(id).addEventListener('change',render));

document.getElementById('bulkApprove').addEventListener('click',()=>bulkChange('approved'));

document.getElementById('bulkReject').addEventListener('click',()=>bulkChange('rejected'));

document.getElementById('chkAll').addEventListener('change',e=>{
  const checked=e.target.checked; document.querySelectorAll('#outlineRows input[type=checkbox][data-chk]').forEach(c=>{c.checked=checked;}); updateBulkButtons();
});

rebuildAdvisorFilter();
render();
</script>
</body>
</html>
