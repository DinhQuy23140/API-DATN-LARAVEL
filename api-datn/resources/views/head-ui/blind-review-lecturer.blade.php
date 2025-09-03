<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Thông tin giảng viên phản biện kín - Trưởng bộ môn</title>
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
          <h1 class="text-lg md:text-xl font-semibold">Giảng viên phản biện kín</h1>
          <nav class="text-xs text-slate-500 mt-0.5">
            <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
            <span class="mx-1">/</span>
            <a href="blind-review-assignments.html" class="hover:underline text-slate-600">Phản biện kín</a>
            <span class="mx-1">/</span>
            <span class="text-slate-500" id="breadcrumbLect">...</span>
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
      <div class="max-w-7xl mx-auto space-y-6" id="contentWrap">
        <section class="bg-white border rounded-xl p-5" id="lectInfo">
          <h2 class="font-semibold mb-4">Thông tin giảng viên</h2>
          <div class="grid sm:grid-cols-2 gap-4 text-sm" id="lectMeta"></div>
        </section>
        <section class="bg-white border rounded-xl p-5">
          <div class="flex items-center justify-between mb-4 gap-3 flex-wrap">
            <h2 class="font-semibold flex items-center gap-2"><i class="ph ph-file-text"></i> Đề cương sinh viên phản biện kín</h2>
            <div class="flex items-center gap-2 flex-wrap">
              <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="stuSearch" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-60" placeholder="Tìm theo tên/MSSV/mã đề cương" />
              </div>
              <button id="addStuBtn" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm flex items-center gap-1"><i class="ph ph-plus"></i> Thêm</button>
              <button id="importStuBtn" class="px-3 py-2 border border-slate-200 rounded text-sm flex items-center gap-1"><i class="ph ph-upload"></i> Import</button>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-500 border-b">
                  <th class="py-3 px-3">MSSV</th>
                  <th class="py-3 px-3">Tên SV</th>
                  <th class="py-3 px-3">Mã đề cương</th>
                  <th class="py-3 px-3">Ngày giao</th>
                  <th class="py-3 px-3">Trạng thái</th>
                  <th class="py-3 px-3 text-right">Hành động</th>
                </tr>
              </thead>
              <tbody id="lectStuRows"></tbody>
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
  function setCollapsed(c){const h=document.querySelector('header'); const m=document.querySelector('main'); if(c){html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); } else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]'); }}
  document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('head_sidebar',''+(c?1:0));});
  document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
  if(localStorage.getItem('head_sidebar')==='1') setCollapsed(true);
  sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
  const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
  profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
  document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
})();

const LECT_KEY='head_blind_review_lecturers';
const ASSIGN_KEY='head_blind_review_assignments';
const PER_LECT_KEY='head_blind_review_per_lecturer'; // map lecturerCode -> student assignments (masked)

function load(k){ try{ const d=localStorage.getItem(k); return d?JSON.parse(d): (k===PER_LECT_KEY?{}:[]);}catch{return k===PER_LECT_KEY?{}:[];} }
function save(k,v){ localStorage.setItem(k, JSON.stringify(v)); }

let lecturers=load(LECT_KEY);
let baseAssign=load(ASSIGN_KEY); // global assignments (for counts)
let perLect=load(PER_LECT_KEY);

const params=new URLSearchParams(location.search);
const code=params.get('code');

function init(){
  if(!code){ document.getElementById('contentWrap').innerHTML='<div class="bg-white border rounded-xl p-8 text-center text-slate-600">Thiếu mã giảng viên.</div>'; return; }
  const lect=lecturers.find(l=>l.code===code);
  if(!lect){ document.getElementById('contentWrap').innerHTML='<div class="bg-white border rounded-xl p-8 text-center text-slate-600">Không tìm thấy giảng viên.</div>'; return; }
  document.getElementById('breadcrumbLect').textContent=lect.code;
  document.getElementById('lectMeta').innerHTML=`
    <div><span class='text-slate-500'>Mã giảng viên</span><div class='font-medium mt-0.5'>${lect.code}</div></div>
    <div><span class='text-slate-500'>Họ tên</span><div class='font-medium mt-0.5'>${lect.name}</div></div>
    <div><span class='text-slate-500'>Email</span><div class='font-medium mt-0.5'>${lect.email||'-'}</div></div>
    <div><span class='text-slate-500'>Chuyên môn</span><div class='font-medium mt-0.5'>${lect.expertise||'-'}</div></div>
    <div><span class='text-slate-500'>Tổng đề cương đã được phân</span><div class='font-medium mt-0.5'>${baseAssign.filter(a=>a.lecturerCode===lect.code).length}</div></div>
  `;
  if(!perLect[code]) perLect[code]=[];
  renderStudents();
}

function renderStudents(){
  const q=(document.getElementById('stuSearch')?.value||'').toLowerCase();
  const rows=document.getElementById('lectStuRows');
  const list=(perLect[code]||[]).filter(it=> it.studentName.toLowerCase().includes(q)||it.studentId.includes(q)||(it.outlineCode||'').toLowerCase().includes(q));
  if(!list.length){ rows.innerHTML="<tr><td colspan='6' class='py-4 px-3 text-slate-500'>Không có dữ liệu.</td></tr>"; return; }
  rows.innerHTML=list.map(it=>`<tr class='border-b hover:bg-slate-50'>
    <td class='py-3 px-3 font-medium'>${it.studentId}</td>
    <td class='py-3 px-3'>${it.studentName}</td>
    <td class='py-3 px-3'>${it.outlineCode||'-'}</td>
    <td class='py-3 px-3'>${it.assignedDate||'-'}</td>
    <td class='py-3 px-3'>${it.status||'Chưa phản biện'}</td>
    <td class='py-3 px-3 text-right'>
      <button data-edit='${it.studentId}' class='px-2 py-1 border border-slate-200 rounded text-xs'>Sửa</button>
      <button data-del='${it.studentId}' class='px-2 py-1 border border-slate-200 rounded text-xs text-rose-600'>Xóa</button>
    </td>
  </tr>`).join('');
  rows.querySelectorAll('[data-edit]').forEach(b=>b.addEventListener('click',()=>openStuModal((perLect[code]||[]).find(x=>x.studentId===b.getAttribute('data-edit')))));
  rows.querySelectorAll('[data-del]').forEach(b=>b.addEventListener('click',()=>{ if(confirm('Xóa sinh viên?')){ perLect[code]=perLect[code].filter(x=>x.studentId!==b.getAttribute('data-del')); save(PER_LECT_KEY, perLect); renderStudents(); }}));
}

function openStuModal(data){
  data=data||{studentId:'', studentName:'', outlineCode:'', assignedDate:'', status:'Chưa phản biện'};
  const isNew=!data.studentId;
  const wrap=document.createElement('div');
  wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
  wrap.innerHTML=`<div class='absolute inset-0 bg-black/40' data-close></div>
  <div class='bg-white w-full sm:max-w-lg rounded-t-2xl sm:rounded-2xl shadow-lg p-5 relative'>
    <h3 class='font-semibold mb-3'>${isNew?'Thêm sinh viên':'Cập nhật sinh viên'}</h3>
    <div class='grid grid-cols-1 md:grid-cols-2 gap-3 text-sm'>
      <div><label class='text-xs text-slate-500'>MSSV</label><input id='sId' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${data.studentId}' ${isNew?'':'readonly'} /></div>
      <div><label class='text-xs text-slate-500'>Họ tên</label><input id='sName' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${data.studentName}' /></div>
      <div class='md:col-span-2'><label class='text-xs text-slate-500'>Mã đề cương (ẩn)</label><input id='sOutline' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${data.outlineCode}' placeholder='VD: O-045'/></div>
      <div><label class='text-xs text-slate-500'>Ngày giao</label><input id='sDate' type='date' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5' value='${data.assignedDate}' /></div>
      <div><label class='text-xs text-slate-500'>Trạng thái</label><select id='sStatus' class='mt-1 w-full border border-slate-200 rounded px-2 py-1.5'><option ${data.status==='Chưa phản biện'?'selected':''}>Chưa phản biện</option><option ${data.status==='Đang phản biện'?'selected':''}>Đang phản biện</option><option ${data.status==='Hoàn thành'?'selected':''}>Hoàn thành</option></select></div>
    </div>
    <div class='mt-5 flex justify-end gap-2'>
      <button class='px-3 py-2 text-slate-600 hover:bg-slate-100 rounded text-sm' data-close>Hủy</button>
      <button id='saveStu' class='px-3 py-2 bg-indigo-600 text-white rounded text-sm'>Lưu</button>
    </div>
  </div>`;
  document.body.appendChild(wrap);
  wrap.addEventListener('click', e=>{ if(e.target.matches('[data-close]')) wrap.remove(); });
  document.getElementById('saveStu').addEventListener('click',()=>{
    const item={
      studentId:document.getElementById('sId').value.trim(),
      studentName:document.getElementById('sName').value.trim(),
      outlineCode:document.getElementById('sOutline').value.trim(),
      assignedDate:document.getElementById('sDate').value||'',
      status:document.getElementById('sStatus').value
    };
    if(!item.studentId||!item.studentName){ alert('Nhập MSSV & họ tên'); return; }
    if(isNew){ if(perLect[code].some(x=>x.studentId===item.studentId)){ alert('SV đã tồn tại'); return;} perLect[code].push(item);} else { const idx=perLect[code].findIndex(x=>x.studentId===item.studentId); perLect[code][idx]=item; }
    save(PER_LECT_KEY, perLect); wrap.remove(); renderStudents();
  });
}

function openImport(){
  const wrap=document.createElement('div');
  wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
  wrap.innerHTML=`<div class='absolute inset-0 bg-black/40' data-close></div>
  <div class='bg-white w-full sm:max-w-lg rounded-t-2xl sm:rounded-2xl shadow-lg p-5 relative'>
    <h3 class='font-semibold mb-3'>Import sinh viên</h3>
    <div class='space-y-3 text-sm'>
      <p class='text-slate-600'>Chọn file CSV (UTF-8). Định dạng: <span class='font-mono text-xs bg-slate-100 px-1 py-0.5 rounded'>studentId,studentName,outlineCode,assignedDate(YYYY-MM-DD),status</span></p>
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
  let parsed=[];
  function parseCSV(text){
    const lines=text.replace(/\r/g,'').split(/\n+/).filter(l=>l.trim());
    const out=[]; for(let i=0;i<lines.length;i++){ const cols=lines[i].split(',').map(c=>c.trim()); if(cols.length<2) continue; if(i===0 && cols[0].toLowerCase()==='studentid') continue; out.push({studentId:cols[0], studentName:cols[1]||'', outlineCode:cols[2]||'', assignedDate:cols[3]||'', status:cols[4]||'Chưa phản biện'}); }
    return out;
  }
  fileInput.addEventListener('change',()=>{
    parsed=[]; statusEl.textContent=''; doBtn.disabled=true; doBtn.classList.add('bg-indigo-400','cursor-not-allowed'); doBtn.classList.remove('bg-indigo-600');
    const f=fileInput.files && fileInput.files[0]; if(!f) return;
    if(f.size>2*1024*1024){ statusEl.textContent='File quá lớn (>2MB)'; return; }
    const reader=new FileReader();
    reader.onload=e=>{ try{ parsed=parseCSV(e.target.result||''); }catch(err){ statusEl.textContent='Lỗi đọc CSV'; return; } if(!parsed.length){ statusEl.textContent='Không tìm thấy dòng hợp lệ.'; return; } statusEl.textContent=`Đã đọc ${parsed.length} dòng. Sẵn sàng import.`; doBtn.disabled=false; doBtn.classList.remove('bg-indigo-400','cursor-not-allowed'); doBtn.classList.add('bg-indigo-600'); };
    reader.onerror=()=>{ statusEl.textContent='Không thể đọc file.'; };
    reader.readAsText(f,'UTF-8');
  });
  doBtn.addEventListener('click',()=>{
    if(!parsed.length) return; let added=0; parsed.forEach(it=>{ if(it.studentId && !perLect[code].some(x=>x.studentId===it.studentId)){ perLect[code].push(it); added++; } });
    save(PER_LECT_KEY, perLect); renderStudents(); alert('Import xong: thêm '+added+' mục mới.'); close();
  });
}

document.getElementById('stuSearch').addEventListener('input', renderStudents);
document.getElementById('addStuBtn').addEventListener('click', ()=>openStuModal());
document.getElementById('importStuBtn').addEventListener('click', openImport);

init();
</script>
</body>
</html>
