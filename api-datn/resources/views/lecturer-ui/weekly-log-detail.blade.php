<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chi tiết nhật ký tuần</title>
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

    <div class="flex-1 md:pl-[260px] h-screen overflow-hidden flex flex-col">
      <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Chi tiết nhật ký tuần</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đồ án tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="supervised-students.html" class="hover:underline text-slate-600">SV hướng dẫn</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Nhật ký tuần</span>
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
        <div class="max-w-4xl mx-auto">
          <div class="flex items-center justify-between mb-4">
            <div></div>
            <a id="backLink" class="text-sm text-blue-600 hover:underline" href="#"><i class="ph ph-caret-left"></i> Quay lại chi tiết sinh viên</a>
          </div>

    <div id="header" class="bg-white border rounded-xl p-4 mb-4"></div>

    <section class="bg-white border rounded-xl p-4 mb-4">
      <h2 class="font-semibold mb-3">Tổng quan tuần</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
        <div class="md:col-span-2">
          <div class="text-slate-500">Tiêu đề</div>
          <div id="weekTitle" class="font-medium">-</div>
          <div class="text-slate-500 mt-2">Mô tả</div>
          <div id="weekDesc" class="text-slate-700"></div>
        </div>
        <div class="space-y-2">
          <div>
            <div class="text-slate-500">Thời gian bắt đầu</div>
            <div id="weekStart" class="font-medium">-</div>
          </div>
          <div>
            <div class="text-slate-500">Thời gian kết thúc</div>
            <div id="weekEnd" class="font-medium">-</div>
          </div>
          <div>
            <div class="text-slate-500">Tệp đính kèm</div>
            <div id="overviewFiles" class="text-slate-700">Không có tệp đính kèm.</div>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-white border rounded-xl p-4">
      <h2 class="font-semibold mb-3">Công việc trong tuần</h2>
      <ul id="taskList" class="text-sm list-disc pl-5 space-y-1"></ul>
    </section>

    <section class="bg-white border rounded-xl p-4 mt-4">
      <h2 class="font-semibold mb-3">Các báo cáo trong tuần</h2>
      <div id="reportsWrap" class="text-sm text-slate-700 space-y-3"></div>
    </section>

    <section class="bg-white border rounded-xl p-4 mt-4">
      <h2 class="font-semibold mb-3">Nhận xét gửi sinh viên</h2>
      <textarea id="commentText" rows="3" class="w-full px-3 py-2 border border-slate-200 rounded text-sm" placeholder="Viết nhận xét..."></textarea>
      <div class="mt-2 flex items-center justify-between">
        <div id="commentStatus" class="text-sm text-slate-500">Chưa có nhận xét.</div>
        <button id="btnSendComment" class="px-3 py-2 bg-emerald-600 text-white rounded text-sm"><i class="ph ph-paper-plane-tilt"></i> Gửi nhận xét</button>
      </div>
    </section>

    <section class="bg-white border rounded-xl p-4 mt-4">
      <div class="flex items-center justify-between mb-3">
        <h2 class="font-semibold">Chấm điểm tuần</h2>
        <div class="text-sm text-slate-600">Khoảng thời gian: <span id="weekRange">-</span></div>
      </div>
      <div class="flex items-center gap-2">
        <input id="inpScore" type="number" min="0" max="10" step="0.1" class="px-3 py-2 border border-slate-200 rounded w-32" placeholder="Điểm" />
        <button id="btnSave" class="px-3 py-2 bg-blue-600 text-white rounded text-sm"><i class="ph ph-check"></i> Lưu điểm</button>
      </div>
      <div class="mt-2 text-sm text-slate-500">Điểm hiện tại: <span id="currentScore">-</span></div>
    </section>
  </div>

  <script>
    // Sidebar/header interactions
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

    function qs(k){ const p = new URLSearchParams(location.search); return p.get(k) || ''; }
    const studentId = qs('studentId');
    const name = decodeURIComponent(qs('name')) || 'Sinh viên';
    const weekNo = parseInt(qs('week'));
    const LS_KEY = `lecturer:student:${studentId}`;

    function loadData(){ try { const raw = localStorage.getItem(LS_KEY); return raw ? JSON.parse(raw) : null; } catch { return null; } }
    function saveData(data){ localStorage.setItem(LS_KEY, JSON.stringify(data)); }

    const data = loadData();
    if(!data){
      document.body.innerHTML = '<div class="p-6 text-center text-slate-600">Không tìm thấy dữ liệu sinh viên.</div>';
    } else {
      const week = (data.weeks || []).find(w=>w.week===weekNo);
      const backHref = `supervised-student-detail.html?id=${encodeURIComponent(studentId)}&name=${encodeURIComponent(name)}`;
      document.getElementById('backLink').setAttribute('href', backHref);
      document.getElementById('header').innerHTML = `
        <div class="flex items-center justify-between">
          <div>
            <div class="text-sm text-slate-500">MSSV: <span class="font-medium text-slate-700">${data.id}</span></div>
            <h2 class="font-semibold text-lg mt-1">${data.name}</h2>
          </div>
          <div class="text-right">
            <div class="text-sm text-slate-500">Tuần</div>
            <div class="font-medium text-blue-600">#${week?.week ?? '-'}</div>
          </div>
        </div>`;

      document.getElementById('weekRange').textContent = week?.range || '-';

      // Overview
      const title = week?.title || `Công việc tuần ${week?.week ?? ''}`.trim();
      const desc = week?.description || (week?.tasks?.length
        ? ('- ' + week.tasks.map(t=>t.name).join('\n- '))
        : 'Chưa có mô tả.');
      document.getElementById('weekTitle').textContent = title;
      document.getElementById('weekDesc').textContent = desc;
      const [start, end] = (week?.range || '').split('-').map(s=>s?.trim());
      document.getElementById('weekStart').textContent = week?.start || start || '-';
      document.getElementById('weekEnd').textContent = week?.end || end || '-';
      const ovFiles = Array.isArray(week?.overviewFiles) ? week.overviewFiles : (Array.isArray(week?.files) ? week.files : []);
      document.getElementById('overviewFiles').innerHTML = ovFiles.length
        ? ovFiles.map(f=>`<a class="text-blue-600 hover:underline" href="#">${f}</a>`).join(', ')
        : 'Không có tệp đính kèm.';

      const tasks = week?.tasks?.length ? week.tasks : [{ name: 'Chưa có công việc', done: false }];
      document.getElementById('taskList').innerHTML = tasks.map(t=>`<li>${t.done? '✅' : '⬜'} ${t.name}</li>`).join('');

      // Reports
  const reports = Array.isArray(week?.reports) && week.reports.length
        ? week.reports
        : (week?.report || week?.files?.length) ? [{ time: '', content: week.report || '', files: (week.files||[]) }] : [];
      const reportsWrap = document.getElementById('reportsWrap');
      reportsWrap.innerHTML = reports.length ? reports.map(r=>`
        <div class="border rounded-lg p-3 bg-slate-50">
          <div class="text-xs text-slate-500 mb-1">${r.time || '—'}</div>
          <div>${r.content || 'Không có nội dung.'}</div>
          ${r.files && r.files.length ? `<div class=\"mt-1\">Tệp đính kèm: ${r.files.map(f=>`<a class=\"text-blue-600 hover:underline\" href=\"#\">${f}</a>`).join(', ')}</div>` : ''}
        </div>
      `).join('') : '<div class="text-slate-500">Chưa có báo cáo.</div>';

      document.getElementById('currentScore').textContent = week?.score ?? '-';
      const scoreInput = document.getElementById('inpScore');
      if(typeof week?.score === 'number') scoreInput.value = week.score;
      document.getElementById('btnSave').addEventListener('click', ()=>{
        const val = parseFloat(scoreInput.value);
        if(!isNaN(val)){
          const idx = data.weeks.findIndex(w=>w.week===weekNo);
          if(idx>=0){ data.weeks[idx].score = val; saveData(data); document.getElementById('currentScore').textContent = val; }
        }
      });

      // Comment to student
      const commentStatus = document.getElementById('commentStatus');
      function renderCommentStatus(){
        const c = week?.commentToStudent;
        commentStatus.textContent = c?.text ? `Đã gửi lúc ${c.sentAt}` : 'Chưa có nhận xét.';
      }
      renderCommentStatus();
      document.getElementById('btnSendComment').addEventListener('click', ()=>{
        const txt = (document.getElementById('commentText').value || '').trim();
        if(!txt) return;
        const idx = data.weeks.findIndex(w=>w.week===weekNo);
        if(idx>=0){
          data.weeks[idx].commentToStudent = { text: txt, sentAt: new Date().toLocaleString('vi-VN') };
          saveData(data);
          document.getElementById('commentText').value = '';
          renderCommentStatus();
        }
      });
    }
  </script>
</body>
</html>
