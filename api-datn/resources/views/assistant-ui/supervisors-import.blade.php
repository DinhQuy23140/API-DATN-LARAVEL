<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Thêm giảng viên hướng dẫn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
      html, body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
      .sidebar { width: 260px; }
      .sidebar-collapsed .sidebar { width: 72px; }
      .sidebar-collapsed .sidebar-label { display: none; }
      .submenu { display: none; }
      .submenu.open { display: block; }
    </style>
  </head>
  <body class="bg-slate-50 text-slate-800">
    <div class="min-h-screen flex">
      <!-- Sidebar -->
      <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-transform transform -translate-x-full md:translate-x-0">
        <div class="h-16 px-4 border-b border-slate-200 flex items-center gap-3">
          <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-buildings"></i></div>
          <div class="sidebar-label">
            <div class="font-semibold leading-5">Assistant</div>
            <div class="text-xs text-slate-500">Quản trị khoa</div>
          </div>
        </div>
        <nav class="flex-1 overflow-y-auto p-3">
          <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span class="sidebar-label">Bảng điều khiển</span></a>
          <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-buildings"></i><span class="sidebar-label">Bộ môn</span></a>
          <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
          <a href="{{ route('web.assistant.manage_staffs') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
          <a href="assign-head.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Gán trưởng bộ môn</span></a>
          <div class="mt-3 px-3 sidebar-label text-xs uppercase text-slate-400">Học phần tốt nghiệp</div>
          <button id="gradToggle" class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-slate-100">
            <span class="flex items-center gap-3"><i class="ph ph-folder"></i><span class="sidebar-label">Học phần tốt nghiệp</span></span>
            <i class="ph ph-caret-down"></i>
          </button>
          <div id="gradMenu" class="submenu pl-6">
            <a href="#" class="block px-3 py-2 rounded hover:bg-slate-100">Thực tập tốt nghiệp</a>
            <a href="{{ route('web.assistant.rounds') }}"
               class="block px-3 py-2 rounded hover:bg-slate-100 bg-slate-100 font-semibold"
               aria-current="page">Đồ án tốt nghiệp</a>
          </div>
        </nav>
        <div class="p-3 border-t border-slate-200">
          <button id="collapseBtn" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700">
            <i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span>
          </button>
        </div>
      </aside>

      <!-- Content -->
      <div class="flex-1">
        <header class="fixed top-0 left-0 md:left-[260px] right-0 h-16 bg-white border-b border-slate-200 z-20">
          <div class="h-full flex items-center justify-between px-4 md:px-6">
            <div class="flex items-center gap-3">
              <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
              <div>
                <h1 class="text-lg md:text-xl font-semibold">Thêm giảng viên hướng dẫn</h1>
                <p class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp / Thêm giảng viên</p>
              </div>
            </div>
            <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
              <img class="h-9 w-9 rounded-full object-cover" src="https://i.pravatar.cc/100?img=8" alt="avatar">
              <span class="hidden sm:block text-left">
                <span class="text-sm font-semibold leading-4">Assistant</span>
                <span class="block text-xs text-slate-500">assistant@uni.edu</span>
              </span>
              <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
            </button>
          </div>
        </header>
        <main class="pt-20 px-4 md:px-6 pb-10 md:pl-[284px] space-y-6">
          <!-- Card: Tải tệp Excel -->
          <section class="bg-white border border-slate-200 rounded-xl p-5">
            <h2 class="font-semibold">Chọn tệp Excel</h2>
            <div class="mt-3">
              <label class="block">
                <input id="fileExcel" type="file" accept=".xlsx,.xls"
                       class="block w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
              </label>
              <p class="text-xs text-slate-500 mt-2">Mẫu cột: Email, Họ tên, Học vị, Bộ môn, Trạng thái.</p>
            </div>
            <div class="mt-4 flex items-center gap-2">
              <button id="btnUpload" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Tải lên</button>
              <a href="#" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Tải mẫu</a>
            </div>
          </section>

          <!-- Card: Thêm thủ công -->
          <section class="bg-white border border-slate-200 rounded-xl p-5">
            <div class="flex items-center justify-between">
              <h2 class="font-semibold">Thêm thủ công</h2>
              <div class="relative w-72 max-w-full">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="qInput" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600"
                       placeholder="Tìm email / họ tên / bộ môn" />
              </div>
            </div>

            <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Danh sách ứng viên -->
              <div class="rounded-xl border border-slate-200 overflow-hidden">
                <div class="h-10 bg-slate-50 border-b border-slate-200 px-3 text-xs text-slate-500 flex items-center">Danh sách giảng viên</div>
                <div id="pool" class="max-h-96 overflow-auto divide-y">
                  <!-- render by script -->
                </div>
              </div>

              <!-- Danh sách đã chọn -->
              <div class="rounded-xl border border-slate-200 flex flex-col">
                <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                  <div class="font-medium">Giảng viên đã chọn</div>
                  <button id="btnCommit"
                          class="px-4 py-2 rounded-lg bg-blue-400 text-white text-sm disabled:opacity-60 disabled:cursor-not-allowed"
                          disabled>Thêm giảng viên (0)</button>
                </div>
                <div class="flex-1 overflow-auto">
                  <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-slate-50 border-b border-slate-200">
                      <tr>
                        <th class="text-left py-2 px-3 font-medium text-slate-600">Email</th>
                        <th class="text-left py-2 px-3 font-medium text-slate-600">Họ tên</th>
                        <th class="text-left py-2 px-3 font-medium text-slate-600">Bộ môn</th>
                        <th class="text-left py-2 px-3 font-medium text-slate-600">Học vị</th>
                        <th class="text-right py-2 px-3 font-medium text-slate-600">Thao tác</th>
                      </tr>
                    </thead>
                    <tbody id="chosenTbody">
                      <tr><td colspan="5" class="py-4 px-3 text-slate-500">Chưa chọn giảng viên nào.</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>
        </main>
      </div>
    </div>

    <!-- Toast -->
    <div id="toastHost" class="fixed bottom-4 right-4 z-50 space-y-2 pointer-events-none"></div>

    <script>
      // Sidebar
      const htmlEl=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const header=document.querySelector('header');
        const main=document.querySelector('main');
        if(c){
          htmlEl.classList.add('sidebar-collapsed');
          header?.classList.add('md:left-[72px]');  header?.classList.remove('md:left-[260px]');
          main?.classList.add('md:pl-[72px]');      main?.classList.remove('md:pl-[260px]');
        } else {
          htmlEl.classList.remove('sidebar-collapsed');
          header?.classList.remove('md:left-[72px]'); header?.classList.add('md:left-[260px]');
          main?.classList.remove('md:pl-[72px]');     main?.classList.add('md:pl-[260px]');
        }
      }
      document.getElementById('collapseBtn')?.addEventListener('click',()=>{
        const c=!htmlEl.classList.contains('sidebar-collapsed');
        setCollapsed(c); localStorage.setItem('assistant_sidebar', c?'1':'0');
      });
      document.getElementById('openSidebar')?.addEventListener('click',()=> sidebar.classList.toggle('-translate-x-full'));
      document.getElementById('gradToggle')?.addEventListener('click',()=>{
        const m=document.getElementById('gradMenu');
        m?.classList.toggle('open');
        const expanded = m?.classList.contains('open');
        const caret = document.querySelector('#gradToggle .ph.ph-caret-down');
        if (expanded) caret?.classList.add('rotate-180'); else caret?.classList.remove('rotate-180');
      });
      // Init theo breakpoint
      sidebar.classList.add('transition-transform','md:translate-x-0');
      if (window.matchMedia('(min-width:768px)').matches) sidebar.classList.remove('-translate-x-full');
      if (localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);

      // Toast
      function pushToast(msg){
        const host=document.getElementById('toastHost');
        const el=document.createElement('div');
        el.className='px-4 py-2 rounded-lg bg-slate-800 text-white text-sm shadow pointer-events-auto';
        el.textContent=msg;
        host.appendChild(el);
        setTimeout(()=>{ el.style.opacity='0'; el.style.transform='translateY(4px)'; el.style.transition='all .25s'; }, 1800);
        setTimeout(()=> el.remove(), 2100);
      }

      // Data store keys
      const POOL_KEY='assistant_candidate_supervisors';
      const SAVED_KEY='assistant_supervisors';

      // Seed demo data if empty
      function getKV(key, fallback){ try{const v=localStorage.getItem(key); return v?JSON.parse(v):fallback;}catch{return fallback;} }
      function setKV(key, val){ localStorage.setItem(key, JSON.stringify(val)); }

      let candidates=getKV(POOL_KEY, []);
      if (!candidates.length) {
        candidates=[
          {email:'gv.a@uni.edu', name:'TS. Nguyễn Văn A', dept:'CNTT', title:'Tiến sĩ'},
          {email:'gv.b@uni.edu', name:'ThS. Trần Thị B', dept:'CNTT', title:'Thạc sĩ'},
          {email:'gv.c@uni.edu', name:'PGS. Lê Văn C', dept:'HTTT', title:'Phó Giáo sư'},
          {email:'gv.d@uni.edu', name:'TS. Phạm Thị D', dept:'KTPM', title:'Tiến sĩ'},
          {email:'gv.e@uni.edu', name:'ThS. Nguyễn Thị E', dept:'MMT', title:'Thạc sĩ'},
          {email:'gv.f@uni.edu', name:'TS. Đỗ Văn F', dept:'KHMT', title:'Tiến sĩ'}
        ];
        setKV(POOL_KEY, candidates);
      }
      let saved=getKV(SAVED_KEY, []);

      // UI refs
      const qInput=document.getElementById('qInput');
      const pool=document.getElementById('pool');
      const chosenTbody=document.getElementById('chosenTbody');
      const btnCommit=document.getElementById('btnCommit');

      // Selection state
      const selected=new Set();

      function alreadySaved(email){ return saved.some(x=>x.email===email); }

      function renderPool(){
        const q=(qInput?.value||'').toLowerCase();
        const data=candidates.filter(x=>{
          if(!q) return true;
          return x.email.toLowerCase().includes(q) || x.name.toLowerCase().includes(q) || (x.dept||'').toLowerCase().includes(q);
        });
        if(!data.length){
          pool.innerHTML='<div class="p-4 text-slate-500 text-sm">Không tìm thấy giảng viên phù hợp.</div>';
          return;
        }
        pool.innerHTML=data.map(x=>{
          const dis=alreadySaved(x.email);
          const checked=selected.has(x.email);
          return `<label class="flex items-center gap-3 p-3 hover:bg-slate-50 ${dis?'opacity-60 cursor-not-allowed':''}">
            <input type="checkbox" data-email="${x.email}" class="rounded border-slate-300" ${checked?'checked':''} ${dis?'disabled':''}>
            <div class="flex-1">
              <div class="font-medium">${x.name} <span class="text-slate-500 font-normal">(${x.email})</span></div>
              <div class="text-xs text-slate-500">${x.title||'-'} • ${x.dept||'-'}</div>
            </div>
            ${dis?'<span class="text-xs px-2 py-1 rounded-full bg-green-50 text-green-700 border border-green-100">Đã có</span>':''}
          </label>`;
        }).join('');
        pool.querySelectorAll('input[type=checkbox][data-email]').forEach(cb=>{
          cb.addEventListener('change', ()=>{
            const em=cb.getAttribute('data-email');
            if(cb.checked) selected.add(em); else selected.delete(em);
            refreshChosen();
            syncCommitBtn();
          });
        });
      }

      function refreshChosen(){
        if(!selected.size){
          chosenTbody.innerHTML='<tr><td colspan="5" class="py-4 px-3 text-slate-500">Chưa chọn giảng viên nào.</td></tr>';
          return;
        }
        const rows=[...selected].map(em=>{
          const t=candidates.find(x=>x.email===em);
          if(!t) return '';
          return `<tr class="border-b">
            <td class="py-2 px-3 font-medium">${t.email}</td>
            <td class="py-2 px-3">${t.name}</td>
            <td class="py-2 px-3">${t.dept||'-'}</td>
            <td class="py-2 px-3">${t.title||'-'}</td>
            <td class="py-2 px-3 text-right">
              <button class="px-2 py-1 text-xs rounded border border-slate-200 hover:bg-slate-50" data-remove="${t.email}">Bỏ chọn</button>
            </td>
          </tr>`;
        }).join('');
        chosenTbody.innerHTML=rows;
        chosenTbody.querySelectorAll('[data-remove]').forEach(btn=>{
          btn.addEventListener('click', ()=>{
            selected.delete(btn.getAttribute('data-remove'));
            refreshChosen();
            renderPool();
            syncCommitBtn();
          });
        });
      }

      function syncCommitBtn(){
        const n=selected.size;
        btnCommit.disabled = n===0;
        btnCommit.textContent = `Thêm giảng viên (${n})`;
        btnCommit.classList.toggle('bg-blue-400', n===0);
        btnCommit.classList.toggle('bg-blue-600', n>0);
        btnCommit.classList.toggle('hover:bg-blue-700', n>0);
      }

      btnCommit?.addEventListener('click', ()=>{
        if(!selected.size) return;
        const toAdd=[...selected]
          .map(em=> candidates.find(x=>x.email===em))
          .filter(Boolean)
          .filter(x=> !alreadySaved(x.email));
        if(!toAdd.length){ pushToast('Không có giảng viên mới để thêm.'); return; }
        saved = getKV(SAVED_KEY, []);
        saved.push(...toAdd);
        setKV(SAVED_KEY, saved);
        const count=toAdd.length;
        selected.clear();
        refreshChosen();
        renderPool();
        syncCommitBtn();
        pushToast(`Đã thêm ${count} giảng viên`);
      });

      qInput?.addEventListener('input', renderPool);
      document.getElementById('btnUpload')?.addEventListener('click', ()=> pushToast('Đang xử lý tệp...'));

      // Init
      renderPool();
      refreshChosen();
      syncCommitBtn();

      // Mở sẵn submenu "Học phần tốt nghiệp" + đánh dấu mục "Đồ án tốt nghiệp"
      document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('gradToggle');
        const menu = document.getElementById('gradMenu');
        const caret = btn?.querySelector('.ph.ph-caret-down');
        // Open submenu on load
        menu?.classList.add('open');
        btn?.setAttribute('aria-expanded', 'true');
        caret?.classList.add('transition-transform','rotate-180');
        // Ensure highlight for "Đồ án tốt nghiệp"
        const thesisLink = menu?.querySelector('a[href*="rounds"]');
        thesisLink?.classList.add('bg-slate-100','font-semibold');
      });
    </script>
  </body>
</html>