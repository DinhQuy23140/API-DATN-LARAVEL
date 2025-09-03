<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đợt đồ án - Trợ lý khoa</title>
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
      .submenu {
        display: none;
      }
      .submenu.hidden {
        display: none;
      }
      .submenu:not(.hidden) {
        display: block;
      }
    </style>
  </head>
  <body class="bg-slate-50 text-slate-800">
    <div class="flex min-h-screen">
      <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
        <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
          <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-buildings"></i></div>
          <div class="sidebar-label">
            <div class="font-semibold">Assistant</div>
            <div class="text-xs text-slate-500">Quản trị khoa</div>
          </div>
        </div>
        <nav class="flex-1 overflow-y-auto p-3">
          <a href="dashboard.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span class="sidebar-label">Bảng điều khiển</span></a>
          <a href="manage-departments.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-buildings"></i><span class="sidebar-label">Bộ môn</span></a>
          <a href="manage-majors.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
          <a href="manage-staff.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
          <a href="assign-head.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Gán trưởng bộ môn</span></a>
          <div class="sidebar-label text-xs uppercase text-slate-400 px-3 mt-3">Học phần tốt nghiệp</div>
          <div class="graduation-item">
            <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button">
              <span class="flex items-center gap-3">
                <i class="ph ph-folder"></i>
                <span class="sidebar-label">Học phần tốt nghiệp</span>
              </span>
              <i class="ph ph-caret-down"></i>
            </div>
            <div class="submenu hidden pl-6">
              <a href="internship.html" class="block px-3 py-2 hover:bg-slate-100">Thực tập tốt nghiệp</a>
              <a href="rounds.html" class="block px-3 py-2 hover:bg-slate-100">Đồ án tốt nghiệp</a>
            </div>
          </div>
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
              <h1 class="text-lg md:text-xl font-semibold">Danh sách đợt đồ án</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp</nav>
            </div>
          </div>
          <div class="relative">
            <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
              <img class="h-9 w-9 rounded-full object-cover" src="https://i.pravatar.cc/100?img=6" alt="avatar" />
              <div class="hidden sm:block text-left">
                <div class="text-sm font-semibold leading-4">Assistant</div>
                <div class="text-xs text-slate-500">assistant@uni.edu</div>
              </div>
              <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
            </button>
            <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
              <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông tin</a>
              <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
            </div>
          </div>
        </header>

        <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 space-y-5">
          <div class="max-w-6xl mx-auto space-y-5">
          <!-- Actions -->
          <div class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="relative w-full sm:max-w-sm">
              <input id="searchInput" class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tên đợt" />
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <button onclick="openCreateRoundModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><i class="ph ph-plus"></i>Tạo đợt mới</button>
          </div>

          <!-- Table -->
          <div class="bg-white border border-slate-200 rounded-xl">
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500">
                    <th data-sort-key="name" data-sort-type="string" class="py-3 px-4 border-b cursor-pointer select-none">Tên đợt <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="time" data-sort-type="date-range" class="py-3 px-4 border-b cursor-pointer select-none">Thời gian <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="committees" data-sort-type="number" class="py-3 px-4 border-b cursor-pointer select-none">Số hội đồng <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th class="py-3 px-4 border-b text-right">Hành động</th>
                  </tr>
                </thead>
                <tbody id="tableBody">
                  <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4"><a href="round-detail.html" class="text-blue-600 hover:underline">Đợt HK1 2025-2026</a></td>
                    <td class="py-3 px-4">01/08/2025 - 30/10/2025</td>
                    <td class="py-3 px-4">12</td>
                    <td class="py-3 px-4 text-right space-x-2">
                      <a class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" href="round-detail.html"><i class="ph ph-eye"></i></a>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" onclick="openModal('edit')"><i class="ph ph-pencil"></i></button>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"><i class="ph ph-trash"></i></button>
                    </td>
                  </tr>
                  <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4"><a href="round-detail.html" class="text-blue-600 hover:underline">Đợt HK2 2025-2026</a></td>
                    <td class="py-3 px-4">15/12/2025 - 30/03/2026</td>
                    <td class="py-3 px-4">10</td>
                    <td class="py-3 px-4 text-right space-x-2">
                      <a class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" href="round-detail.html"><i class="ph ph-eye"></i></a>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" onclick="openModal('edit')"><i class="ph ph-pencil"></i></button>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"><i class="ph ph-trash"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        </main>
      </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">
      <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">
        <div class="p-4 border-b flex items-center justify-between">
          <h3 id="modalTitle" class="font-semibold">Tạo đợt mới</h3>
          <button class="p-2 hover:bg-slate-100 rounded-lg" onclick="closeModal()"><i class="ph ph-x"></i></button>
        </div>
        <form class="p-4 space-y-4" onsubmit="event.preventDefault(); closeModal();">
          <div>
            <label class="text-sm font-medium">Tên đợt</label>
            <input required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600" placeholder="VD: Đợt HK1 2025-2026" />
          </div>
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-medium">Ngày bắt đầu</label>
              <input type="date" required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" />
            </div>
            <div>
              <label class="text-sm font-medium">Ngày kết thúc</label>
              <input type="date" required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" />
            </div>
          </div>
          <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
            <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Lưu</button>
          </div>
        </form>
      </div>
    </div>

    <script>
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const mainArea = document.querySelector('.flex-1');
        if(c){
          html.classList.add('sidebar-collapsed');
          mainArea.classList.add('md:pl-[72px]');
          mainArea.classList.remove('md:pl-[260px]');
        } else {
          html.classList.remove('sidebar-collapsed');
          mainArea.classList.remove('md:pl-[72px]');
          mainArea.classList.add('md:pl-[260px]');
        }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed');setCollapsed(c);localStorage.setItem('assistant_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      // simple filter
      document.getElementById('searchInput').addEventListener('input', (e)=>{
        const q=e.target.value.toLowerCase();
        document.querySelectorAll('#tableBody tr').forEach(tr=> tr.style.display = tr.innerText.toLowerCase().includes(q)?'':'none');
      });

      // sorting
      const sortState = { key:null, dir:1 };
      function parseDateVN(d){ // dd/mm/yyyy
        const [dd,mm,yyyy] = d.split('/').map(Number);
        return new Date(yyyy, mm-1, dd).getTime();
      }
      function getSortValue(tr, key){
        const tds = tr.querySelectorAll('td');
        if(key==='name') return (tds[0]?.innerText||'').trim().toLowerCase();
        if(key==='time'){
          const txt=(tds[1]?.innerText||'').trim();
          const start = txt.split('-')[0]?.trim();
          return start? parseDateVN(start) : 0;
        }
        if(key==='committees') return Number((tds[2]?.innerText||'0').replace(/\D+/g,''));
        return '';
      }
      function applySort(th){
        const key = th.dataset.sortKey;
        if(!key) return;
        sortState.dir = sortState.key===key ? -sortState.dir : 1;
        sortState.key = key;
        const rows = Array.from(document.querySelectorAll('#tableBody tr')).filter(r=>r.style.display!=='none');
        rows.sort((a,b)=>{
          const va=getSortValue(a,key), vb=getSortValue(b,key);
          if(typeof va==='number' && typeof vb==='number') return (va-vb)*sortState.dir;
          return (va>vb?1:va<vb?-1:0)*sortState.dir;
        });
        const tbody=document.getElementById('tableBody');
        rows.forEach(r=>tbody.appendChild(r));
        // update indicators
        document.querySelectorAll('thead th[data-sort-key] i').forEach(i=>{i.className='ph ph-caret-up-down ml-1 text-slate-400';});
        const icon = th.querySelector('i');
        icon.className = sortState.dir===1 ? 'ph ph-caret-up ml-1 text-slate-600' : 'ph ph-caret-down ml-1 text-slate-600';
      }
      document.querySelectorAll('thead th[data-sort-key]').forEach(th=> th.addEventListener('click',()=>applySort(th)));

      // profile dropdown
      const profileBtn=document.getElementById('profileBtn');
      const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

      // auto active nav highlight
      (function(){
        const current = location.pathname.split('/').pop();
        document.querySelectorAll('aside nav a').forEach(a=>{
          const href=a.getAttribute('href')||'';
          const active=href.endsWith(current);
          a.classList.toggle('bg-slate-100', active);
          a.classList.toggle('font-semibold', active);
          if(active) a.classList.add('text-slate-900');
        });
      })();

      document.addEventListener('DOMContentLoaded', () => {
        const graduationItem = document.querySelector('.graduation-item');
        const toggleButton = graduationItem.querySelector('.toggle-button');
        const submenu = graduationItem.querySelector('.submenu');

        if (toggleButton && submenu) {
          toggleButton.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default link behavior
            submenu.classList.toggle('hidden');
          });
        }
      });

      // ===== Dynamic Create-Round Modal with 8 fixed timeline stages =====
      const AU_STAGES=[
        {id:1, name:'Tiếp nhận yêu cầu'},
        {id:2, name:'Đề cương'},
        {id:3, name:'Nhật ký tuần'},
        {id:4, name:'Báo cáo'},
        {id:5, name:'Phân hội đồng'},
        {id:6, name:'Phản biện'},
        {id:7, name:'Công bố & thứ tự'},
        {id:8, name:'Bảo vệ & kết quả'}
      ];
      function au_registerModal(wrapper){
        const panel=wrapper.querySelector('[data-modal-container]');
        function close(){ wrapper.remove(); document.removeEventListener('keydown', esc); }
        function esc(e){ if(e.key==='Escape') close(); }
        wrapper.addEventListener('click',e=>{ if(e.target.hasAttribute('data-overlay')||e.target.hasAttribute('data-close')) close(); });
        panel.addEventListener('click',e=> e.stopPropagation());
        document.addEventListener('keydown', esc);
        const first=panel.querySelector('input,select,textarea,button'); first && first.focus();
      }
      function openCreateRoundModal(){
        const wrap=document.createElement('div');
        wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
        wrap.innerHTML=`
          <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" data-overlay></div>
          <div class="bg-white w-full sm:max-w-4xl max-h-[92vh] overflow-auto rounded-t-2xl sm:rounded-2xl shadow-xl relative z-10" data-modal-container>
            <div class="p-5 border-b flex items-center justify-between sticky top-0 bg-white">
              <h3 class="font-semibold text-base">Tạo đợt đồ án tốt nghiệp</h3>
              <button data-close class="p-2 hover:bg-slate-100 rounded-lg"><i class="ph ph-x"></i></button>
            </div>
            <form id="createRoundForm" class="p-5 space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-medium">Tên đợt</label>
                  <input name="name" required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600" placeholder="VD: Đợt HK1 2025-2026" />
                </div>
                <div>
                  <label class="text-sm font-medium">Học kỳ</label>
                  <select name="semester" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200">
                    <option value="HK1">HK1</option>
                    <option value="HK2">HK2</option>
                    <option value="HK3">HK3</option>
                  </select>
                </div>
                <div>
                  <label class="text-sm font-medium">Năm học</label>
                  <input name="schoolYear" required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" placeholder="2025-2026" />
                </div>
                <div>
                  <label class="text-sm font-medium">Ngày bắt đầu đợt (tuỳ chọn)</label>
                  <input type="date" name="roundStart" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" />
                </div>
                <div>
                  <label class="text-sm font-medium">Ngày kết thúc đợt (tuỳ chọn)</label>
                  <input type="date" name="roundEnd" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" />
                </div>
                <div class="md:col-span-2">
                  <label class="text-sm font-medium">Mô tả</label>
                  <textarea name="description" rows="2" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" placeholder="Ghi chú / phạm vi triển khai"></textarea>
                </div>
              </div>

              <div>
                <div class="flex items-center justify-between mb-2">
                  <h4 class="font-semibold">Mốc timeline (1–8)</h4>
                  <span class="text-xs text-slate-500">Chỉ cần nhập thời gian bắt đầu và kết thúc cho từng mốc</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  ${AU_STAGES.map(s=>`
                    <div class="border rounded-lg p-3">
                      <div class="font-medium mb-2">Timeline ${s.id}: ${s.name}</div>
                      <div class="grid grid-cols-2 gap-3">
                        <div>
                          <label class="block text-xs text-slate-600 mb-1">Bắt đầu</label>
                          <input type="date" name="stage_${s.id}_start" class="w-full border border-slate-200 rounded px-2 py-1.5 focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600" required>
                        </div>
                        <div>
                          <label class="block text-xs text-slate-600 mb-1">Kết thúc</label>
                          <input type="date" name="stage_${s.id}_end" class="w-full border border-slate-200 rounded px-2 py-1.5 focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600" required>
                        </div>
                      </div>
                    </div>`).join('')}
                </div>
              </div>

              <div class="flex items-center justify-end gap-2 pt-2 border-t">
                <button type="button" data-close class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
                <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Tạo đợt</button>
              </div>
            </form>
          </div>`;
        document.body.appendChild(wrap);
        au_registerModal(wrap);

        const form=wrap.querySelector('#createRoundForm');
        form.addEventListener('submit',(e)=>{
          e.preventDefault();
          const fd=new FormData(form);
          const stages=AU_STAGES.map(s=>{
            const start=fd.get(`stage_${s.id}_start`);
            const end=fd.get(`stage_${s.id}_end`);
            return { id:s.id, name:s.name, start:String(start||''), end:String(end||'') };
          });
          // validate
          let ok=true;
          stages.forEach(st=>{
            const sEl=form.querySelector(`[name=stage_${st.id}_start]`);
            const eEl=form.querySelector(`[name=stage_${st.id}_end]`);
            sEl.classList.remove('border-rose-300'); eEl.classList.remove('border-rose-300');
            if(!st.start || !st.end || st.start>st.end){ ok=false; sEl.classList.add('border-rose-300'); eEl.classList.add('border-rose-300'); }
          });
          if(!ok) return;

          const round={
            id:'r_'+Date.now(),
            name:String(fd.get('name')||''),
            semester:String(fd.get('semester')||''),
            schoolYear:String(fd.get('schoolYear')||''),
            description:String(fd.get('description')||''),
            roundStart:String(fd.get('roundStart')||''),
            roundEnd:String(fd.get('roundEnd')||''),
            stages,
            createdAt:new Date().toISOString()
          };
          const key='assistant_rounds';
          const list=JSON.parse(localStorage.getItem(key)||'[]');
          list.push(round);
          localStorage.setItem(key, JSON.stringify(list));
          // Notify and close
          window.dispatchEvent(new CustomEvent('assistant:round:created',{detail:round}));
          wrap.remove();
          // Optional: navigate to detail page
          // location.href='round-detail.html?id='+encodeURIComponent(round.id);
        });
      }

      // Expose for button
      window.openCreateRoundModal=openCreateRoundModal;
    </script>
  </body>
</html>
