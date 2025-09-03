<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Theo dõi tiến độ - Trợ lý khoa</title>
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
              <h1 class="text-lg md:text-xl font-semibold">Theo dõi tiến độ</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Đợt đồ án / Theo dõi tiến độ</nav>
            </div>
          </div>
        </header>

        <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
          <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-4">
              <div class="text-sm text-slate-600">Theo dõi tiến độ sinh viên trong đợt</div>
              <div class="hidden md:flex gap-2 text-sm">
                <span id="countStarted" class="px-2 py-1 rounded-full bg-blue-50 text-blue-700">Đã bắt đầu: 0</span>
              </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <section class="lg:col-span-1 bg-white rounded-xl border border-slate-200 p-4">
                <div class="relative mb-3">
                  <input id="progressSearch" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tên, mã SV" />
                  <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
                <div id="progressStudentList" class="divide-y divide-slate-200 max-h-[520px] overflow-y-auto"></div>
              </section>

              <section class="lg:col-span-2 space-y-4">
                <div id="progressDetail" class="bg-white rounded-xl border border-slate-200 p-4 min-h-[420px] grid place-items-center text-slate-500">
                  Chọn một sinh viên ở danh sách bên trái để xem chi tiết.
                </div>
              </section>
            </div>
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

      // graduation submenu toggle
      document.addEventListener('DOMContentLoaded', () => {
        const graduationItem = document.querySelector('.graduation-item');
        const toggleButton = graduationItem?.querySelector('.toggle-button');
        const submenu = graduationItem?.querySelector('.submenu');
        if (toggleButton && submenu) {
          toggleButton.addEventListener('click', (e) => {
            e.preventDefault();
            submenu.classList.toggle('hidden');
          });
        }
      });

      // Data and rendering for progress tracking
      const students = [
        {
          id:'20123456', name:'Nguyễn Văn A', class:'KTPM2025', email:'20123456@sv.uni.edu',
          topic:{ title:'Hệ thống quản lý thư viện', supervisor:'TS. Đặng Hữu T', status:'Tốt' },
          progress:75, lastUpdate:'2 ngày trước',
          logs:[
            { date:'10/08/2025', text:'Hoàn thành phân tích yêu cầu, bắt đầu thiết kế CSDL.' },
            { date:'05/08/2025', text:'Thiết lập repo, dựng khung dự án.' }
          ],
          outline: { title: 'Đề cương đồ án', submittedAt: '02/08/2025 14:20', status: 'Đã duyệt', fileName: 'de-cuong-20123456.pdf' },
          reports: [
            { name: 'Báo cáo tiến độ 1', submittedAt: '09/08/2025 10:45', status: 'Chờ duyệt', fileName: 'bao-cao-1-20123456.pdf' },
            { name: 'Báo cáo giữa kỳ', submittedAt: '11/08/2025 09:05', status: 'Đã duyệt', fileName: 'bao-cao-giuaky-20123456.pdf' }
          ],
          attachments: [
            { name: 'Tai-lieu-tham-khao-A.pdf', size: '1.2 MB', submittedAt: '03/08/2025 08:12' },
            { name: 'Mo-ta-ca-truc-nang.docx', size: '340 KB', submittedAt: '07/08/2025 16:33' }
          ]
        },
        {
          id:'20124567', name:'Trần Thị B', class:'KTPM2025', email:'20124567@sv.uni.edu',
          topic:{ title:'Ứng dụng mobile bán hàng', supervisor:'ThS. Lưu Lan', status:'Chậm' },
          progress:45, lastUpdate:'5 ngày trước',
          logs:[
            { date:'08/08/2025', text:'Hoàn tất UI trang chính, đang kết nối Firebase.' },
            { date:'30/07/2025', text:'Khảo sát yêu cầu người dùng.' }
          ],
          outline: { title: 'Đề cương đồ án', submittedAt: '01/08/2025 18:05', status: 'Chờ duyệt', fileName: 'de-cuong-20124567.pdf' },
          reports: [
            { name: 'Báo cáo tiến độ 1', submittedAt: '06/08/2025 20:01', status: 'Bị từ chối', fileName: 'bao-cao-1-20124567.pdf' }
          ],
          attachments: [
            { name: 'Danh-sach-usecase.xlsx', size: '95 KB', submittedAt: '05/08/2025 11:22' }
          ]
        },
        {
          id:'20125678', name:'Lê Văn C', class:'HTTT2025', email:'20125678@sv.uni.edu',
          topic:{ title:'Website thương mại điện tử', supervisor:'TS. Nguyễn Văn A', status:'Xuất sắc' },
          progress:90, lastUpdate:'1 ngày trước',
          logs:[
            { date:'11/08/2025', text:'Tối ưu hiệu năng, đạt TTFB < 200ms.' },
            { date:'01/08/2025', text:'Hoàn thành chức năng giỏ hàng, thanh toán Sandbox.' }
          ],
          outline: { title: 'Đề cương đồ án', submittedAt: '28/07/2025 09:40', status: 'Đã duyệt', fileName: 'de-cuong-20125678.pdf' },
          reports: [
            { name: 'Báo cáo tiến độ 1', submittedAt: '03/08/2025 09:20', status: 'Đã duyệt', fileName: 'bao-cao-1-20125678.pdf' },
            { name: 'Báo cáo tiến độ 2', submittedAt: '10/08/2025 17:55', status: 'Đã nộp', fileName: 'bao-cao-2-20125678.pdf' }
          ],
          attachments: [
            { name: 'Spec-API-v1.pdf', size: '870 KB', submittedAt: '29/07/2025 15:03' },
            { name: 'Test-cases.xlsx', size: '210 KB', submittedAt: '08/08/2025 13:47' },
            { name: 'Kien-truc-he-thong.drawio', size: '120 KB', submittedAt: '09/08/2025 19:22' }
          ]
        }
      ];

      const badge = (status)=>{
        if(status==='Tốt') return 'bg-green-50 text-green-600';
        if(status==='Xuất sắc') return 'bg-emerald-50 text-emerald-700';
        if(status==='Chậm') return 'bg-yellow-50 text-yellow-700';
        return 'bg-slate-100 text-slate-700';
      };

      // Status badge for documents/reports
      const docStatusBadge = (status)=>{
        if(status==='Đã duyệt') return 'bg-green-50 text-green-700';
        if(status==='Chờ duyệt' || status==='Đã nộp') return 'bg-blue-50 text-blue-700';
        if(status==='Bị từ chối') return 'bg-red-50 text-red-700';
        return 'bg-slate-100 text-slate-700';
      };

      const listEl = document.getElementById('progressStudentList');
      const detailEl = document.getElementById('progressDetail');
      const searchEl = document.getElementById('progressSearch');
      const countStarted = document.getElementById('countStarted');

      function getQuery(){
        const p=new URLSearchParams(location.search);
        return { studentId: p.get('studentId')||'' };
      }
      const preselect = getQuery();
      let filtered = [...students];
      let selectedId = preselect.studentId || filtered[0]?.id || null;

      function fmtProgressBar(pct, color){
        return `<div class="w-full bg-gray-200 rounded-full h-2"><div class="${color} h-2 rounded-full" style="width:${pct}%"></div></div>`;
      }

      function renderList(){
        listEl.innerHTML = filtered.map(s=>{
          const active = s.id===selectedId;
          const color = s.progress>=80?'bg-green-600':s.progress>=60?'bg-blue-600':s.progress>=40?'bg-yellow-600':'bg-red-600';
          return `
            <button data-id="${s.id}" class="w-full text-left py-3 px-2 ${active?'bg-slate-50':''} hover:bg-slate-50">
              <div class="flex items-start justify-between">
                <div>
                  <div class="font-medium">${s.name}</div>
                  <div class="text-xs text-slate-500">${s.id} • ${s.class}</div>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs ${badge(s.topic.status)}">${s.topic.status}</span>
              </div>
              <div class="mt-2">
                ${fmtProgressBar(s.progress, color)}
                <span class="text-xs text-slate-500">${s.progress}% • Cập nhật ${s.lastUpdate}</span>
              </div>
            </button>`;
        }).join('');

        // bind events
        listEl.querySelectorAll('button[data-id]').forEach(btn=>{
          btn.addEventListener('click', ()=>{
            selectedId = btn.getAttribute('data-id');
            renderList();
            const s = filtered.find(x=>x.id===selectedId);
            if(s) renderDetail(s);
          });
        });

        countStarted.textContent = `Đã bắt đầu: ${filtered.length}`;
      }

      function renderDetail(s){
        const color = s.progress>=80?'bg-green-600':s.progress>=60?'bg-blue-600':s.progress>=40?'bg-yellow-600':'bg-red-600';
        detailEl.classList.remove('grid','place-items-center','text-slate-500');
        detailEl.innerHTML = `
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white border border-slate-200 rounded-lg p-4">
              <div class="font-semibold mb-2">Thông tin sinh viên</div>
              <div class="text-sm text-slate-700">${s.name}</div>
              <div class="text-sm text-slate-600">Mã SV: ${s.id}</div>
              <div class="text-sm text-slate-600">Lớp: ${s.class}</div>
              <div class="text-sm text-slate-600">Email: ${s.email}</div>
            </div>
            <div class="bg-white border border-slate-200 rounded-lg p-4">
              <div class="font-semibold mb-2">Thông tin đề tài</div>
              <div class="text-sm text-slate-700">${s.topic.title}</div>
              <div class="text-sm text-slate-600">GVHD: ${s.topic.supervisor}</div>
              <div class="mt-2">${fmtProgressBar(s.progress, color)}</div>
              <div class="text-xs text-slate-500 mt-1">Tiến độ: ${s.progress}% • ${s.lastUpdate}</div>
            </div>
          </div>

          <div class="bg-white border border-slate-200 rounded-lg p-4 mt-4">
            <div class="flex items-center justify-between">
              <div class="font-semibold">Nhật kí đồ án</div>
              <div class="text-xs text-slate-500">${s.logs.length} mục</div>
            </div>
            <div class="mt-3 space-y-3">
              ${s.logs.map((l,idx)=>`
                <a href="progress-week-detail.html?studentId=${s.id}&week=${idx+1}" class="block border rounded-lg p-3 hover:bg-slate-50">
                  <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-700">${l.text}</div>
                    <div class="text-xs text-slate-500">${l.date}</div>
                  </div>
                </a>
              `).join('')}
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
            <div class="bg-white border border-slate-200 rounded-lg p-4">
              <div class="flex items-center justify-between">
                <div class="font-semibold">Đề cương</div>
                ${s.outline ? `<span class="px-2 py-0.5 rounded-full text-xs ${docStatusBadge(s.outline.status)}">${s.outline.status}</span>` : ''}
              </div>
              ${s.outline ? `
                <div class="mt-2 text-sm text-slate-700">${s.outline.title}</div>
                <div class="text-xs text-slate-500">Nộp lúc: ${s.outline.submittedAt}</div>
                <div class="mt-2 flex items-center gap-2 text-sm">
                  <i class="ph ph-file-pdf text-slate-500"></i>
                  <span class="text-slate-700">${s.outline.fileName}</span>
                </div>
                <div class="mt-3 flex gap-2">
                  <a href="outline-management.html?studentId=${s.id}" class="px-3 py-1.5 text-xs rounded-lg bg-slate-100 hover:bg-slate-200">Xem</a>
                  <button class="px-3 py-1.5 text-xs rounded-lg bg-blue-600 text-white hover:bg-blue-700">Tải xuống</button>
                </div>
              ` : `<div class="mt-2 text-sm text-slate-500">Chưa nộp đề cương</div>`}
            </div>

            <div class="bg-white border border-slate-200 rounded-lg p-4">
              <div class="flex items-center justify-between">
                <div class="font-semibold">Báo cáo đã nộp</div>
                <div class="text-xs text-slate-500">${(s.reports||[]).length} báo cáo</div>
              </div>
              <div class="mt-2 space-y-2">
                ${(s.reports||[]).map(r=>`
                  <div class="flex items-center justify-between border rounded-lg p-2">
                    <div>
                      <div class="text-sm text-slate-700">${r.name}</div>
                      <div class="text-xs text-slate-500">Nộp lúc: ${r.submittedAt}</div>
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="px-2 py-0.5 rounded-full text-xs ${docStatusBadge(r.status)}">${r.status}</span>
                      <button class="p-2 rounded-lg hover:bg-slate-100" title="Xem"><i class="ph ph-eye"></i></button>
                      <button class="p-2 rounded-lg hover:bg-slate-100" title="Tải xuống"><i class="ph ph-download-simple"></i></button>
                    </div>
                  </div>
                `).join('')}
                ${(s.reports||[]).length===0 ? `<div class="text-sm text-slate-500">Chưa có báo cáo nào</div>` : ''}
              </div>
              <div class="mt-3">
                <a href="report-management.html?studentId=${s.id}" class="text-xs text-blue-600 hover:underline">Xem tất cả trong quản lý báo cáo</a>
              </div>
            </div>
          </div>

          <div class="bg-white border border-slate-200 rounded-lg p-4 mt-4">
            <div class="flex items-center justify-between">
              <div class="font-semibold">Tài liệu liên quan đã nộp</div>
              <div class="text-xs text-slate-500">${(s.attachments||[]).length} tệp</div>
            </div>
            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
              ${(s.attachments||[]).map(f=>`
                <div class="flex items-center justify-between border rounded-lg p-2">
                  <div class="flex items-center gap-2">
                    <i class="ph ph-paperclip text-slate-500"></i>
                    <div>
                      <div class="text-sm text-slate-700">${f.name}</div>
                      <div class="text-xs text-slate-500">${f.size||''} • ${f.submittedAt}</div>
                    </div>
                  </div>
                  <div class="flex items-center gap-1">
                    <button class="p-2 rounded-lg hover:bg-slate-100" title="Xem"><i class="ph ph-eye"></i></button>
                    <button class="p-2 rounded-lg hover:bg-slate-100" title="Tải xuống"><i class="ph ph-download-simple"></i></button>
                  </div>
                </div>
              `).join('')}
              ${(s.attachments||[]).length===0 ? `<div class="text-sm text-slate-500">Chưa có tài liệu nào</div>` : ''}
            </div>
          </div>
        `;
      }

      // search
      searchEl.addEventListener('input', ()=>{
        const q = (searchEl.value||'').toLowerCase();
        filtered = students.filter(s=> s.name.toLowerCase().includes(q) || s.id.toLowerCase().includes(q));
        selectedId = filtered[0]?.id || null;
        renderList();
        if(selectedId){ renderDetail(filtered[0]); }
        else { detailEl.classList.add('grid','place-items-center','text-slate-500'); detailEl.innerHTML='Không tìm thấy sinh viên.'; }
      });

      // initial render
      renderList();
      if(selectedId){ renderDetail(filtered.find(x=>x.id===selectedId)); }
    </script>
  </body>
</html>
