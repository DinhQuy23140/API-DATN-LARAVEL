<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đề xuất danh sách đề tài</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <!-- SheetJS for Excel import -->
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
    .sidebar-collapsed .sidebar-label { display:none; }
    .sidebar-collapsed .sidebar { width:72px; }
    .sidebar { width:260px; }
    /* Modern modal */
    .modal-overlay {
      animation: modalFade .25s ease;
    }
    .modal-shell {
      animation: modalPop .28s cubic-bezier(.4,.2,.2,1);
    }
    @keyframes modalFade {
      from { opacity:0; }
      to { opacity:1; }
    }
    @keyframes modalPop {
      0% { opacity:0; transform:translateY(8px) scale(.96); }
      100% { opacity:1; transform:translateY(0) scale(1); }
    }
    .floating-label { position:relative; }
    .floating-label input,
    .floating-label textarea,
    .floating-label select {
      padding-top:1.35rem;
    }
    .floating-label label {
      position:absolute; left:.75rem; top:.65rem;
      font-size:.70rem; letter-spacing:.5px;
      font-weight:500; text-transform:uppercase;
      color:rgb(100 116 139);
      pointer-events:none;
    }
    .tag-chip {
      @apply px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700 border border-slate-200;
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-800">
  <div class="flex min-h-screen">
    <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Lecturer</div>
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
        <a href="thesis-rounds.html" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold pl-10"><i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span></a>
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
            <h1 class="text-lg md:text-xl font-semibold">Đề xuất danh sách đề tài</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đồ án tốt nghiệp</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Đề xuất đề tài</span>
            </nav>
          </div>
        </div>
        <div class="relative">
          <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
            <img class="h-9 w-9 rounded-full object-cover" src="https://i.pravatar.cc/100?img=20" alt="avatar" />
            <div class="hidden sm:block text-left">
              <div class="text-sm font-semibold leading-4">TS. Nguyễn Văn A</div>
              <div class="text-xs text-slate-500">lecturer@uni.edu</div>
            </div>
            <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
          </button>
          <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông tin</a>
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
        <div class="max-w-6xl mx-auto">
          <div class="flex items-center justify-between mb-4">
            <div></div>
            <a href="thesis-round-detail.html" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại đợt</a>
          </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4" id="stats">
      <div class="bg-blue-50 p-4 rounded-lg flex items-center gap-3"><div class="h-10 w-10 rounded-lg bg-blue-600/10 text-blue-600 grid place-items-center"><i class="ph ph-list-bullets"></i></div><div><div class="text-2xl font-bold text-blue-600" id="stTotal">0</div><div class="text-sm text-blue-800">Tổng đề tài</div></div></div>
      <div class="bg-green-50 p-4 rounded-lg flex items-center gap-3"><div class="h-10 w-10 rounded-lg bg-green-600/10 text-green-600 grid place-items-center"><i class="ph ph-play"></i></div><div><div class="text-2xl font-bold text-green-600" id="stOpen">0</div><div class="text-sm text-green-800">Đang mở</div></div></div>
      <div class="bg-slate-50 p-4 rounded-lg flex items-center gap-3"><div class="h-10 w-10 rounded-lg bg-slate-600/10 text-slate-700 grid place-items-center"><i class="ph ph-users-three"></i></div><div><div class="text-2xl font-bold text-slate-700" id="stSlots">0</div><div class="text-sm text-slate-700">Tổng chỉ tiêu</div></div></div>
      <div class="bg-purple-50 p-4 rounded-lg flex items-center gap-3"><div class="h-10 w-10 rounded-lg bg-purple-600/10 text-purple-600 grid place-items-center"><i class="ph ph-user-plus"></i></div><div><div class="text-2xl font-bold text-purple-600" id="stReg">0</div><div class="text-sm text-purple-800">Đã đăng ký</div></div></div>
    </div>

    <!-- Controls -->
    <div class="bg-white border rounded-xl p-3 mb-3">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
          <div class="relative">
            <i class="ph ph-magnifying-glass absolute left-2 top-2.5 text-slate-400"></i>
            <input id="searchBox" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tiêu đề/thẻ" />
          </div>
          <select id="statusFilter" class="px-3 py-2 border border-slate-200 rounded text-sm">
            <option value="">Tất cả trạng thái</option>
            <option value="Mở">Mở</option>
            <option value="Đóng">Đóng</option>
          </select>
          <button id="resetBtn" class="px-2 py-1 text-sm text-slate-600 hover:text-slate-800">Đặt lại</button>
        </div>
        <div class="flex items-center gap-2">
          <button id="btnImport" class="px-3 py-1.5 border border-slate-200 rounded text-sm"><i class="ph ph-upload-simple"></i> Import Excel</button>
          <button id="btnTemplate" class="px-3 py-1.5 border border-slate-200 rounded text-sm"><i class="ph ph-download-simple"></i> Tải mẫu</button>
          <button id="btnAdd" class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm"><i class="ph ph-plus"></i> Thêm đề tài</button>
        </div>
      </div>
    </div>

    <!-- Topics list -->
    <div id="topicsList" class="grid grid-cols-1 gap-3"></div>
  </div>

  <script>
    // In-memory topics store
    const topics = [
      { id: 'T001', title: 'Hệ thống quản lý học tập trực tuyến (LMS)', description: 'Xây dựng LMS với quản lý khóa học, bài tập, đánh giá; ưu tiên stack Node.js + React.', tags: ['Web','Node.js','React'], slots: 2, registered: 1, status: 'Mở', updatedAt: '15/07/2025' },
      { id: 'T002', title: 'Ứng dụng thương mại điện tử', description: 'E-commerce full-stack, tích hợp thanh toán, quản lý đơn hàng.', tags: ['Web','React'], slots: 3, registered: 2, status: 'Mở', updatedAt: '20/07/2025' },
      { id: 'T003', title: 'AI Chatbot tư vấn', description: 'Chatbot NLP tư vấn hỗ trợ người dùng; ưu tiên Python.', tags: ['AI','NLP','Python'], slots: 1, registered: 0, status: 'Đóng', updatedAt: '05/07/2025' }
    ];

    const listEl = document.getElementById('topicsList');
    const searchEl = document.getElementById('searchBox');
    const statusEl = document.getElementById('statusFilter');

    function statusPill(s){
      return s==='Mở'?'bg-green-50 text-green-600':'bg-slate-100 text-slate-700';
    }

    function updateStats(){
      document.getElementById('stTotal').textContent = topics.length;
      document.getElementById('stOpen').textContent = topics.filter(t=>t.status==='Mở').length;
      document.getElementById('stSlots').textContent = topics.reduce((a,t)=>a+(t.slots||0),0);
      document.getElementById('stReg').textContent = topics.reduce((a,t)=>a+(t.registered||0),0);
    }

    function render(){
      updateStats();
      const q = (searchEl?.value||'').toLowerCase();
      const st = statusEl?.value || '';
      const filtered = topics.filter(t=>{
        const hit = t.title.toLowerCase().includes(q) || (t.tags||[]).join(' ').toLowerCase().includes(q);
        const ok = !st || t.status===st;
        return hit && ok;
      });
      listEl.innerHTML = filtered.map(t=>`
        <div class="border rounded-lg p-4 bg-white">
          <div class="flex items-start justify-between gap-2">
            <div>
              <h5 class="font-medium">${t.title}</h5>
              <p class="text-sm text-slate-600 mt-1">${t.description||''}</p>
              <div class="mt-2 flex flex-wrap gap-1">
                ${(t.tags||[]).map(tag=>`<span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700">${tag}</span>`).join('')}
              </div>
              <div class="text-xs text-slate-500 mt-2">Cập nhật: ${t.updatedAt||'-'}</div>
            </div>
            <div class="text-right">
              <div class="text-sm"><span class="font-semibold">${t.registered||0}</span>/<span>${t.slots||0}</span> SV</div>
              <div><span class="px-2 py-0.5 rounded-full text-xs ${statusPill(t.status)}">${t.status}</span></div>
            </div>
          </div>
        </div>
      `).join('');
    }

    // Modern modal helper
    function createModal(opts){
      const { title, content, width='max-w-3xl' } = opts;
      const wrap = document.createElement('div');
      wrap.className = 'modal-overlay fixed inset-0 z-50 flex items-center justify-center px-4';
      wrap.innerHTML = `
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
        <div class="modal-shell relative bg-white/95 supports-[backdrop-filter]:bg-white/80 border border-slate-200 shadow-xl rounded-2xl w-full ${width} max-h-[92vh] flex flex-col overflow-hidden">
          <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between bg-gradient-to-r from-white/90 to-white/40 backdrop-blur-md">
            <div class="flex items-center gap-2">
              <div class="h-9 w-9 rounded-lg bg-blue-600/10 text-blue-600 grid place-items-center"><i class="ph ph-plus"></i></div>
              <h3 class="font-semibold text-lg">${title}</h3>
            </div>
            <button type="button" data-close class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-slate-700 transition"><i class="ph ph-x text-lg"></i></button>
          </div>
          <div class="flex-1 overflow-y-auto custom-scroll px-6 py-6">
            ${content}
          </div>
          <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/80 backdrop-blur-sm flex items-center justify-end gap-3">
            <button type="button" data-close class="px-4 py-2 text-sm font-medium rounded-lg border border-slate-300 hover:bg-slate-100">Hủy</button>
            <button type="submit" form="topicForm" class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-sm flex items-center gap-2">
              <i class="ph ph-check-circle"></i><span>Lưu đề tài</span>
            </button>
          </div>
        </div>
      `;
      wrap.addEventListener('click', e => { if(e.target === wrap) destroy(); });
      function destroy(){ wrap.classList.add('opacity-0'); setTimeout(()=>wrap.remove(),150); }
      wrap.querySelectorAll('[data-close]').forEach(btn => btn.addEventListener('click', destroy));
      document.addEventListener('keydown', escHandler);
      function escHandler(e){ if(e.key==='Escape'){ destroy(); document.removeEventListener('keydown', escHandler);} }
      return { el: wrap, destroy };
    }

    // Enhanced input decorator
    function baseInputCls(extra=''){
      return `w-full rounded-lg border border-slate-200 bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition px-3 py-2 text-sm ${extra}`;
    }

    function chipsPreviewTemplate(){
      return `<div id="tagsPreview" class="flex flex-wrap gap-1 mt-1"></div>`;
    }

    function renderTagPreview(container, raw){
      const tags = raw.split(/[;,]/).map(t=>t.trim()).filter(Boolean);
      container.innerHTML = tags.map(t=>`<span class="tag-chip flex items-center gap-1">${t}</span>`).join('') || '<span class="text-xs text-slate-400">Chưa có thẻ</span>';
    }

    // Add topic (modern modal)
    document.getElementById('btnAdd').addEventListener('click', () => {
      const formHTML = `
        <form id="topicForm" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="floating-label">
              <label>Tiêu đề *</label>
              <input name="title" required maxlength="180" class="${baseInputCls()}" placeholder=" " />
              <p class="mt-1 text-xs text-slate-500">Đặt tên rõ ràng, mô tả sản phẩm/kết quả cuối.</p>
            </div>
            <div class="floating-label">
              <label>Chỉ tiêu (SV)</label>
              <input type="number" name="slots" min="1" value="1" class="${baseInputCls()}" placeholder=" " />
            </div>
          </div>
          <div class="floating-label">
            <label>Mô tả</label>
            <textarea name="desc" rows="5" class="${baseInputCls('resize-y')}" placeholder=" "></textarea>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="floating-label md:col-span-2">
              <label>Thẻ (ngăn cách phẩy / chấm phẩy)</label>
              <input name="tags" class="${baseInputCls()}" placeholder=" " />
              ${chipsPreviewTemplate()}
            </div>
            <div class="floating-label">
              <label>Trạng thái</label>
              <select name="status" class="${baseInputCls()}">
                <option value="Mở">Mở</option>
                <option value="Đóng">Đóng</option>
              </select>
            </div>
          </div>
          <fieldset class="border border-slate-200 rounded-xl p-4">
            <legend class="px-2 text-xs font-semibold text-slate-500">Tùy chọn nâng cao</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <label class="flex items-center gap-3 text-sm">
                <input type="checkbox" name="allow_edit" class="rounded border-slate-300">
                Cho phép SV tùy chỉnh tiêu đề
              </label>
              <label class="flex items-center gap-3 text-sm">
                <input type="checkbox" name="require_outline" class="rounded border-slate-300">
                Yêu cầu nộp đề cương sớm
              </label>
            </div>
          </fieldset>
        </form>
      `;
      const modal = createModal({ title: 'Thêm đề tài mới', content: formHTML });
      document.body.appendChild(modal.el);

      // Preview tags
      const tagsInput = modal.el.querySelector('input[name="tags"]');
      const tagsPreview = modal.el.querySelector('#tagsPreview');
      const updateTags = () => renderTagPreview(tagsPreview, tagsInput.value || '');
      tagsInput?.addEventListener('input', updateTags);
      updateTags();

      // Submit
      modal.el.querySelector('#topicForm')?.addEventListener('submit', (e) => {
        e.preventDefault();
        const fd = new FormData(e.target);
        const title = String(fd.get('title')||'').trim();
        if(!title) return;
        const payload = {
          id: 'T' + Math.floor(Math.random()*900+100),
          title,
          description: String(fd.get('desc')||'').trim(),
          tags: String(fd.get('tags')||'').split(',').map(x=>x.trim()).filter(Boolean),
          slots: Math.max(1, Number(fd.get('slots')||1)),
          status: fd.get('status')||'Mở',
          registered: 0,
          updatedAt: new Date().toLocaleDateString('vi-VN')
        };
        topics.unshift(payload);
        modal.destroy();
        render();
      });
    });

    // Import from Excel
    document.getElementById('btnImport').addEventListener('click', ()=>{
      const html = `
        <div class="space-y-3">
          <div class="text-sm text-slate-600">Chọn file Excel (.xlsx) hoặc CSV với các cột: <strong>Title, Description, Tags, Slots, Status</strong>.</div>
          <input id="fileInput" type="file" accept=".xlsx,.csv" class="block w-full text-sm" />
          <div class="text-xs text-slate-500">Gợi ý: Tags phân cách bằng dấu phẩy hoặc chấm phẩy. Status: Mở/Đóng.</div>
          <div class="flex gap-2 pt-1">
            <button id="importBtn" class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm"><i class="ph ph-upload"></i> Import</button>
            <button id="cancelImp" class="px-3 py-1.5 border border-slate-200 rounded text-sm">Hủy</button>
          </div>
        </div>`;
      const m = createModal('Import danh sách đề tài', html);
      document.body.appendChild(m);
      m.querySelector('#cancelImp').addEventListener('click',()=>m.remove());
      m.querySelector('#importBtn').addEventListener('click',()=>{
        const file = m.querySelector('#fileInput').files[0];
        if(!file){ alert('Vui lòng chọn file'); return; }
        const reader = new FileReader();
        reader.onload = (e)=>{
          try{
            let rows = [];
            if(file.name.toLowerCase().endsWith('.csv')){
              const text = e.target.result;
              const lines = String(text).split(/\r?\n/).filter(Boolean);
              const header = lines.shift().split(',').map(h=>h.trim());
              rows = lines.map(line=>{
                const cols = line.split(',');
                const obj = {};
                header.forEach((h,i)=>obj[h] = cols[i]);
                return obj;
              });
            } else {
              const data = new Uint8Array(e.target.result);
              const wb = XLSX.read(data, {type:'array'});
              const ws = wb.Sheets[wb.SheetNames[0]];
              rows = XLSX.utils.sheet_to_json(ws, {defval:'', raw:false});
            }
            const added = [];
            rows.forEach(r=>{
              const title = (r.Title||r.title||'').trim();
              if(!title) return;
              const desc = (r.Description||r.description||'').trim();
              const tags = String(r.Tags||r.tags||'').split(/[;,]/).map(x=>x.trim()).filter(Boolean);
              const slots = Math.max(1, Number(r.Slots||r.slots||1));
              const status = ((r.Status||r.status||'Mở').trim()==='Đóng')?'Đóng':'Mở';
              topics.push({ id:'T'+Math.floor(Math.random()*900+100), title, description:desc, tags, slots, status, registered:0, updatedAt: new Date().toLocaleDateString('vi-VN') });
              added.push(title);
            });
            m.remove();
            render();
            if(added.length) alert(`Đã import ${added.length} đề tài.`);
          }catch(err){ console.error(err); alert('Không thể đọc file. Vui lòng kiểm tra định dạng.'); }
        };
        if(file.name.toLowerCase().endsWith('.csv')) reader.readAsText(file);
        else reader.readAsArrayBuffer(file);
      });
    });

    // Download template (CSV)
    document.getElementById('btnTemplate').addEventListener('click', ()=>{
      const header = 'Title,Description,Tags,Slots,Status\n';
  const example = 'Hệ thống quản lý thư viện,Mô tả ví dụ,Web;React;Node.js,2,Mở\n';
      const blob = new Blob([header + example], {type:'text/csv;charset=utf-8;'});
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url; a.download = 'topics_template.csv'; a.click();
      URL.revokeObjectURL(url);
    });

    // Filters
    document.getElementById('resetBtn').addEventListener('click',()=>{ if(searchEl) searchEl.value=''; if(statusEl) statusEl.value=''; render(); });
    searchEl?.addEventListener('input', render);
    statusEl?.addEventListener('change', render);

    // Initial render
    render();

    // Sidebar/header interactions (outside of templates)
    (function(){
      const html = document.documentElement;
      const sidebar = document.getElementById('sidebar');
      const headerEl = document.querySelector('header');
      const wrapper = headerEl ? headerEl.parentElement : null; // the container with md:pl-*

      function setCollapsed(c){
        if(c){
          html.classList.add('sidebar-collapsed');
          // adjust wrapper padding if available
          if(wrapper){
            wrapper.classList.remove('md:pl-[260px]');
            wrapper.classList.add('md:pl-[72px]');
          }
        } else {
          html.classList.remove('sidebar-collapsed');
          if(wrapper){
            wrapper.classList.remove('md:pl-[72px]');
            wrapper.classList.add('md:pl-[260px]');
          }
        }
      }

      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{
        const c = !html.classList.contains('sidebar-collapsed');
        setCollapsed(c);
        localStorage.setItem('lecturer_sidebar', c ? '1' : '0');
      });
      document.getElementById('openSidebar')?.addEventListener('click',()=> sidebar?.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      sidebar?.classList.add('md:translate-x-0','-translate-x-full','md:static');

      const profileBtn = document.getElementById('profileBtn');
      const profileMenu = document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu?.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    })();
  </script>
</body>
</html>
