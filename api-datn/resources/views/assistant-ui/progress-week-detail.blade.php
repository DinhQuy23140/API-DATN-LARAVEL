<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chi tiết hoạt động theo tuần - Trợ lý khoa</title>
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
      .submenu { display: none; }
      .submenu.hidden { display: none; }
      .submenu:not(.hidden) { display: block; }
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
          <a href="{{ route('web.assistant.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span class="sidebar-label">Bảng điều khiển</span></a>
          <a href="{{ route('web.assistant.manage_departments') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-buildings"></i><span class="sidebar-label">Bộ môn</span></a>
          <a href="{{ route('web.assistant.manage_majors') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
          <a href="{{ route('web.assistant.manage_staffs') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
          <a href="{{ route('web.assistant.assign_head') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Gán trưởng bộ môn</span></a>
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
              <a href="{{ route('web.assistant.rounds') }}" class="block px-3 py-2 hover:bg-slate-100">Đồ án tốt nghiệp</a>
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
              <h1 class="text-lg md:text-xl font-semibold">Chi tiết hoạt động theo tuần</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Đợt đồ án / Theo dõi tiến độ / Chi tiết tuần</nav>
            </div>
          </div>
        </header>

        <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
          <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Week Selector -->
            <section class="bg-white rounded-xl border border-slate-200 p-4 lg:col-span-1">
              <div class="font-semibold mb-3">Tuần</div>
              <div id="weekList" class="space-y-2 max-h-[520px] overflow-y-auto"></div>
            </section>

            <!-- Details -->
            <section class="lg:col-span-2 space-y-4">
              <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex items-center justify-between mb-3">
                  <div>
                    <div id="studentName" class="text-sm text-slate-600">Sinh viên</div>
                    <h2 id="weekTitle" class="font-semibold text-lg">Tiêu đề tuần</h2>
                    <div id="weekTime" class="text-sm text-slate-500">Thời gian</div>
                  </div>
                  <a id="backToProgress" href="progress-tracking.html" class="text-blue-600 text-sm hover:underline flex items-center gap-1"><i class="ph ph-arrow-left"></i> Quay lại theo dõi</a>
                </div>

                <div class="grid grid-cols-1 gap-4">
                  <div class="border rounded-lg p-3">
                    <div class="font-medium">Nội dung</div>
                    <div id="weekContent" class="text-sm text-slate-700 mt-1"></div>
                  </div>
                  <div class="border rounded-lg p-3">
                    <div class="font-medium">Báo cáo</div>
                    <div class="text-sm text-slate-700 mt-1">
                      <a id="weekReport" href="#" class="text-blue-600 hover:underline"></a>
                      <div id="weekReportTime" class="text-xs text-slate-500"></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex items-center justify-between mb-2">
                  <div class="font-semibold">Nhận xét</div>
                  <div class="text-xs text-slate-500" id="commentCount">0 ý kiến</div>
                </div>
                <div id="commentList" class="space-y-3"></div>
              </div>
            </section>
          </div>
        </main>
      </div>
    </div>

    <script>
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const mainArea = document.querySelector('.flex-1');
        if(c){ html.classList.add('sidebar-collapsed'); mainArea.classList.add('md:pl-[72px]'); mainArea.classList.remove('md:pl-[260px]'); }
        else { html.classList.remove('sidebar-collapsed'); mainArea.classList.remove('md:pl-[72px]'); mainArea.classList.add('md:pl-[260px]'); }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed');setCollapsed(c);localStorage.setItem('assistant_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      // query params
      function getQuery(){
        const p=new URLSearchParams(location.search);
        return { studentId: p.get('studentId')||'20123456', week: parseInt(p.get('week')||'1',10) };
      }

      // mock data per student
      const students = {
        '20123456': { name:'Nguyễn Văn A' },
        '20124567': { name:'Trần Thị B' },
        '20125678': { name:'Lê Văn C' },
      };

      const weekly = {
        1:{ title:'Tuần 1 - Khởi động', time:'05/08/2025 - 11/08/2025', content:'Phân tích yêu cầu, khảo sát hệ thống hiện tại. Dựng skeleton dự án và cấu hình CI.', report:{ file:'bao_cao_tuan1.pdf', href:'#', at:'11/08/2025 22:04' }, comments:[
          { by:'TS. Đặng Hữu T', role:'GVHD', text:'Cần mô tả rõ hơn các use case chính.', time:'11/08 10:12' },
          { by:'Nguyễn Văn A', role:'SV', text:'Em đã bổ sung phần use case chính.', time:'11/08 14:30' },
        ]},
        2:{ title:'Tuần 2 - Thiết kế', time:'12/08/2025 - 18/08/2025', content:'Thiết kế CSDL và sơ đồ kiến trúc. Tạo migration và entity.', report:{ file:'bao_cao_tuan2.pdf', href:'#', at:'18/08/2025 21:15' }, comments:[
          { by:'TS. Đặng Hữu T', role:'GVHD', text:'Xem lại chuẩn hóa bảng users.', time:'17/08 09:40' },
        ]},
        3:{ title:'Tuần 3 - Chức năng cốt lõi', time:'19/08/2025 - 25/08/2025', content:'Hoàn thiện module xác thực và phân quyền.', report:{ file:'bao_cao_tuan3.pdf', href:'#', at:'25/08/2025 20:00' }, comments:[] },
      };

      const weekList = document.getElementById('weekList');
      const weekTitle = document.getElementById('weekTitle');
      const weekTime = document.getElementById('weekTime');
      const weekContent = document.getElementById('weekContent');
      const weekReport = document.getElementById('weekReport');
      const weekReportTime = document.getElementById('weekReportTime');
      const studentName = document.getElementById('studentName');
      const backToProgress = document.getElementById('backToProgress');
  const commentList = document.getElementById('commentList');
  const commentCount = document.getElementById('commentCount');

      let { studentId, week } = getQuery();
      if(!(week in weekly)) week = 1;

      function renderWeekList(){
        weekList.innerHTML = Object.keys(weekly).map(k=>{
          const w = parseInt(k,10);
          const active = w===week;
          const data = weekly[w];
          return `
            <a href="?studentId=${studentId}&week=${w}" class="block border rounded-lg p-3 ${active?'bg-slate-50 border-slate-300':'hover:bg-slate-50'}">
              <div class="font-medium">${data.title}</div>
              <div class="text-xs text-slate-500">${data.time}</div>
            </a>
          `;
        }).join('');
      }

      function renderDetails(){
        const s = students[studentId] || { name:'Sinh viên' };
        const d = weekly[week];
        studentName.textContent = `${s.name} • ${studentId}`;
        weekTitle.textContent = d.title;
        weekTime.textContent = d.time;
        weekContent.textContent = d.content;
        weekReport.textContent = d.report.file;
        weekReport.href = d.report.href;
        weekReportTime.textContent = `Nộp: ${d.report.at}`;
        backToProgress.href = `progress-tracking.html?studentId=${studentId}`;

  const advisorComments = (d.comments||[]).filter(c=> (c.role||'').toUpperCase() === 'GVHD');
  commentList.innerHTML = advisorComments.map(c=>`
          <div class="border rounded-lg p-3">
            <div class="text-xs text-slate-500">${c.role} • ${c.by} • ${c.time}</div>
            <div class="text-sm text-slate-700">${c.text}</div>
          </div>
        `).join('');
  commentCount.textContent = `${advisorComments.length} ý kiến`;
      }

      renderWeekList();
      renderDetails();
    </script>
  </body>
</html>
