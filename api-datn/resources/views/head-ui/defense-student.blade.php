<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chấm bảo vệ đồ án - Trưởng bộ môn</title>
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
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { appearance: textfield; -moz-appearance: textfield; }
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
            <h1 class="text-lg md:text-xl font-semibold">Chấm bảo vệ đồ án</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Trưởng bộ môn</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-round-detail.html" class="hover:underline text-slate-600">Chi tiết đợt</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Chấm bảo vệ</span>
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
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông tin</a>
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
        <div class="max-w-5xl mx-auto space-y-4">
          <a id="backLink" href="javascript:history.back()" class="text-sm text-indigo-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại</a>

          <section class="bg-white border rounded-xl p-4">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="font-semibold text-lg">Sinh viên: <span id="sName">-</span> (<span id="sId">-</span>)</h2>
                <div class="text-slate-600 text-sm">Hội đồng: <span id="cId">-</span></div>
              </div>
              <div class="text-xs text-slate-500">Ngày bảo vệ: <span id="defDate">20/08/2025</span></div>
            </div>
          </section>

          <section class="bg-white border rounded-xl p-4">
            <h3 class="font-semibold mb-3">Phiếu chấm bảo vệ</h3>
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500 border-b">
                    <th class="py-2 px-3">Tiêu chí</th>
                    <th class="py-2 px-3">Mô tả</th>
                    <th class="py-2 px-3">Tối đa</th>
                    <th class="py-2 px-3">Điểm</th>
                  </tr>
                </thead>
                <tbody id="criteriaRows"></tbody>
                <tfoot>
                  <tr class="border-t">
                    <td class="py-2 px-3 font-medium" colspan="3">Tổng điểm</td>
                    <td class="py-2 px-3"><input id="totalScore" class="w-24 border border-slate-200 rounded px-2 py-1 text-right" type="number" step="0.1" min="0" max="10" readonly /></td>
                  </tr>
                </tfoot>
              </table>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
              <div>
                <label class="text-sm text-slate-600">Kết quả</label>
                <select id="resultSelect" class="w-full border border-slate-200 rounded px-2 py-2 text-sm">
                  <option value="Đạt">Đạt</option>
                  <option value="Cần bổ sung">Cần bổ sung</option>
                  <option value="Không đạt">Không đạt</option>
                </select>
              </div>
              <div>
                <label class="text-sm text-slate-600">Ghi chú</label>
                <input id="noteInput" class="w-full border border-slate-200 rounded px-2 py-2 text-sm" placeholder="Nhận xét chung..." />
              </div>
            </div>

            <div class="mt-4 flex items-center gap-2">
              <button id="saveBtn" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm"><i class="ph ph-floppy-disk"></i> Lưu phiếu chấm</button>
              <span id="saveMsg" class="text-sm text-emerald-700 hidden">Đã lưu.</span>
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

    function qs(k){ const p=new URLSearchParams(location.search); return p.get(k)||''; }
    const studentId = qs('studentId')||qs('id')||'20210001';
    const studentName = qs('name')||'Nguyễn Văn A';
    const committeeId = qs('committeeId')||'CNTT-01';

    document.getElementById('sId').textContent = studentId;
    document.getElementById('sName').textContent = studentName;
    document.getElementById('cId').textContent = committeeId;

    const defaultCriteria = [
      { key:'presentation', name:'Trình bày', desc:'Phong thái, thời lượng, slide', max:2.0 },
      { key:'content', name:'Nội dung', desc:'Chất lượng nội dung báo cáo', max:3.0 },
      { key:'demo', name:'Minh hoạ/DEMO', desc:'Tính đúng, đầy đủ, ổn định', max:2.0 },
      { key:'answer', name:'Trả lời câu hỏi', desc:'Rõ ràng, thuyết phục', max:2.0 },
      { key:'novelty', name:'Đóng góp', desc:'Tính mới, đóng góp thực tiễn', max:1.0 }
    ];

    const STORAGE_KEY = `head_defense_student_${studentId}`;
    function loadData(){ try { return JSON.parse(localStorage.getItem(STORAGE_KEY)||'{}'); } catch { return {}; } }
    function saveData(data){ localStorage.setItem(STORAGE_KEY, JSON.stringify(data)); }

    const saved = loadData();
    const tbody = document.getElementById('criteriaRows');
    const crit = saved.criteria || defaultCriteria.map(c=>({ ...c, score: 0 }));

    function renderRows(){
      tbody.innerHTML = '';
      crit.forEach((c,idx)=>{
        const tr = document.createElement('tr');
        tr.className = 'border-b';
        tr.innerHTML = `
          <td class='py-2 px-3'>${c.name}</td>
          <td class='py-2 px-3 text-slate-600'>${c.desc}</td>
          <td class='py-2 px-3 text-slate-600'>${c.max.toFixed(1)}</td>
          <td class='py-2 px-3'>
            <input data-idx='${idx}' class='w-24 border border-slate-200 rounded px-2 py-1 text-right' type='number' step='0.1' min='0' max='${c.max}' value='${typeof c.score==='number'?c.score:0}' />
          </td>`;
        tbody.appendChild(tr);
      });
      bindInputs();
      computeTotal();
    }

    function computeTotal(){
      const total = crit.reduce((s,c)=> s + (Number(c.score)||0), 0);
      document.getElementById('totalScore').value = total.toFixed(1);
    }

    function bindInputs(){
      document.querySelectorAll('input[data-idx]').forEach(inp=>{
        inp.addEventListener('input', ()=>{
          const i = Number(inp.getAttribute('data-idx'));
          let val = parseFloat(inp.value);
            if(Number.isNaN(val)) val = 0;
            val = Math.max(0, Math.min(val, crit[i].max));
            inp.value = val.toFixed(1);
            crit[i].score = val;
            computeTotal();
        });
      });
    }

    renderRows();

    document.getElementById('resultSelect').value = saved.result || 'Đạt';
    document.getElementById('noteInput').value = saved.note || '';

    document.getElementById('saveBtn').addEventListener('click', ()=>{
      const data = {
        studentId, studentName, committeeId,
        criteria: crit,
        total: parseFloat(document.getElementById('totalScore').value)||0,
        result: document.getElementById('resultSelect').value,
        note: document.getElementById('noteInput').value,
        savedAt: new Date().toISOString()
      };
      saveData(data);
      try {
        const listKey = `head_defense_committee_${committeeId}`;
        const list = JSON.parse(localStorage.getItem(listKey)||'{}');
        list[studentId] = { total: data.total, result: data.result, note: data.note };
        localStorage.setItem(listKey, JSON.stringify(list));
      } catch {}
      const msg = document.getElementById('saveMsg');
      msg.classList.remove('hidden');
      setTimeout(()=>msg.classList.add('hidden'), 1500);
    });
  </script>
</body>
</html>
