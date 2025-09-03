<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chấm phản biện - Sinh viên</title>
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
            <h1 id="pageTitle" class="text-lg md:text-xl font-semibold">Chấm phản biện - Sinh viên</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="review-assignments.html" class="hover:underline text-slate-600">Danh sách phản biện</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Chấm phản biện</span>
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
        <div class="max-w-4xl mx-auto space-y-4">
          <section class="bg-white border rounded-xl p-4">
            <div class="grid md:grid-cols-2 gap-3 text-sm">
              <div><span class="text-slate-500">Sinh viên:</span> <span id="sName" class="font-medium"></span> (<span id="sId"></span>)</div>
              <div><span class="text-slate-500">Hội đồng:</span> <span id="sCommittee" class="font-medium"></span></div>
              <div><span class="text-slate-500">Trạng thái:</span> <span id="sStatus" class="font-medium">Chưa chấm</span></div>
            </div>
          </section>

          <section class="bg-white border rounded-xl p-4">
            <div class="font-semibold mb-3">Phiếu chấm phản biện</div>
            <div class="text-sm text-slate-600 mb-2">Nhập điểm cho từng tiêu chí (tối đa theo cột "Tối đa"). Tổng điểm sẽ tự động tính ra 0-10.</div>
            <div id="criteriaWrap" class="overflow-x-auto">
              <!-- criteria table injected by script -->
            </div>
            <div class="grid md:grid-cols-1 gap-3 text-sm mt-4">
              <label>Tổng điểm (0-10) - tự động
                <input id="score" type="number" min="0" max="10" step="0.1" class="mt-1 w-full border rounded px-2 py-1 bg-slate-50" readonly />
              </label>
            </div>
            <label class="block text-sm mt-3">Nhận xét
              <textarea id="comment" rows="5" class="mt-1 w-full border rounded px-2 py-1" placeholder="Nhận xét chi tiết cho phản biện..."></textarea>
            </label>
            <div class="mt-3 flex justify-end gap-2">
              <button id="saveBtn" class="px-3 py-2 rounded bg-blue-600 text-white text-sm">Lưu chấm</button>
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>

  <script>
    // Sidebar/profile wiring
    (function(){
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const h=document.querySelector('header'); const m=document.querySelector('main');
        if(c){ html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); }
        else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]'); }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
      const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    })();

    // Load query params
    const url = new URL(location.href);
    const id = url.searchParams.get('id');
    const name = url.searchParams.get('name');
    const committee = url.searchParams.get('committee');

    // Load or init data
    const key = 'review_student_' + id;
    const data = JSON.parse(localStorage.getItem(key) || '{}');

    // Default criteria
    const defaultCriteria = [
      { key:'goals', name:'Mục tiêu & phạm vi', max:2 },
      { key:'method', name:'Cơ sở lý thuyết & phương pháp', max:2 },
      { key:'report', name:'Chất lượng báo cáo/viết', max:2 },
      { key:'novelty', name:'Tính mới & ứng dụng', max:2 },
      { key:'defense', name:'Trả lời phản biện', max:2 }
    ];
    let criteria = Array.isArray(data.criteria) && data.criteria.length
      ? data.criteria.map(c=>({ ...c, score: c.score ?? 0 }))
      : defaultCriteria.map(c=>({ ...c, score: 0 }));

    // Populate header
    document.getElementById('sId').textContent = id||'';
    document.getElementById('sName').textContent = name||'';
    document.getElementById('sCommittee').textContent = committee||'';
    document.getElementById('sStatus').textContent = data.status || 'Chưa chấm';

    // Render criteria table and bind events
    function renderCriteria(){
      const wrap = document.getElementById('criteriaWrap');
      wrap.innerHTML = `
        <table class="w-full text-sm border rounded-lg overflow-hidden">
          <thead>
            <tr class="text-left text-slate-500 border-b">
              <th class="py-2 px-3">Tiêu chí</th>
              <th class="py-2 px-3 w-24">Tối đa</th>
              <th class="py-2 px-3 w-40">Điểm</th>
            </tr>
          </thead>
          <tbody>
            ${criteria.map((c,i)=>`
              <tr class="border-b hover:bg-slate-50">
                <td class="py-2 px-3">${c.name}</td>
                <td class="py-2 px-3">${c.max.toFixed(1)}</td>
                <td class="py-2 px-3">
                  <input type="number" min="0" max="${c.max}" step="0.1" data-crit="${c.key}" class="w-28 border rounded px-2 py-1" value="${(Number(c.score)||0).toFixed(1)}"/>
                </td>
              </tr>
            `).join('')}
          </tbody>
        </table>`;
      // bind input events
      wrap.querySelectorAll('input[data-crit]').forEach(inp=>{
        inp.addEventListener('input', e=>{
          const key=e.target.getAttribute('data-crit');
          const item = criteria.find(x=>x.key===key);
          let val = parseFloat(e.target.value);
          if(isNaN(val)) val = 0;
          if(val < 0) val = 0;
          if(val > item.max) val = item.max;
          item.score = val;
          e.target.value = val.toFixed(1);
          calcTotal();
        });
      });
    }

    function calcTotal(){
      const total = criteria.reduce((s,c)=> s + (Number(c.score)||0), 0);
      document.getElementById('score').value = total.toFixed(1);
    }

    // Populate form
  document.getElementById('score').value = (data.score ?? criteria.reduce((s,c)=>s+(Number(c.score)||0),0)).toFixed ? (data.score ?? criteria.reduce((s,c)=>s+(Number(c.score)||0),0)).toFixed(1) : (data.score ?? 0);
    document.getElementById('comment').value = data.comment || '';
    renderCriteria();
    calcTotal();

    document.getElementById('saveBtn').addEventListener('click', ()=>{
      const status = 'Đã chấm';
      const payload = {
        id,
        name,
        committee,
        score: parseFloat(document.getElementById('score').value)||0,
        status,
        comment: document.getElementById('comment').value.trim(),
        criteria: criteria.map(c=>({ key:c.key, name:c.name, max:c.max, score: Number(c.score)||0 })),
        updatedAt: new Date().toISOString()
      };
      localStorage.setItem(key, JSON.stringify(payload));
      // also update list store for review-assignments
      try {
        const listKey = 'review_assignments';
        let list = JSON.parse(localStorage.getItem(listKey) || '[]');
        const i = list.findIndex(x=>x.id===id);
        if(i>=0){ list[i] = { ...list[i], status: payload.status }; }
        else { list.push({ id, name, committee, status: payload.status, time: '' }); }
        localStorage.setItem(listKey, JSON.stringify(list));
      } catch(e){}
  document.getElementById('sStatus').textContent = payload.status;
      alert('Đã lưu chấm phản biện');
    });
  </script>
</body>
</html>
