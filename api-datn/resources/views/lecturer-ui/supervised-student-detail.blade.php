<!DOCTYPE html>
<html lang="vi">
    <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chi tiết sinh viên hướng dẫn</title>
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
                <h1 class="text-lg md:text-xl font-semibold">Chi tiết sinh viên hướng dẫn</h1>
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
                                    <span class="text-slate-500">Chi tiết</span>
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
                <a href="supervised-students.html" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại danh sách</a>
            </div>

        <div id="header" class="bg-white border rounded-xl p-4 mb-4"></div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <section class="md:col-span-2 bg-white border rounded-xl p-4">
            <h2 class="font-semibold mb-3">Thông tin đề tài</h2>
            <div id="topicInfo" class="text-sm text-slate-700 space-y-1"></div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="bg-blue-50 p-3 rounded">
                <div class="text-sm text-blue-800">Ngày bắt đầu</div>
                <div class="text-2xl font-bold text-blue-600" id="startDate">-</div>
            </div>
            <div class="bg-slate-50 p-3 rounded">
                <div class="text-sm text-slate-700">Trạng thái</div>
                <div class="text-2xl font-bold text-slate-800" id="status">-</div>
            </div>
            </div>

            <div class="mt-6">
            <h3 class="font-semibold mb-2">Đề cương đã nộp</h3>
            <div id="outlineBox" class="border rounded-lg p-3 bg-slate-50 text-sm">
                <div class="text-slate-500">Chưa có đề cương.</div>
            </div>
            </div>
        </section>
        <section class="bg-white border rounded-xl p-4">
            <h2 class="font-semibold mb-3">Liên hệ</h2>
            <div id="contact" class="text-sm text-slate-700 space-y-1"></div>
            <div class="mt-3 flex gap-2">
            <button class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm"><i class="ph ph-envelope"></i> Gửi email</button>
            <button class="px-3 py-1.5 border border-slate-200 rounded text-sm"><i class="ph ph-chat-text"></i> Nhắn tin</button>
            </div>
        </section>
        </div>

        <section class="bg-white border rounded-xl p-4 mt-4">
        <h2 class="font-semibold mb-3">Nhật ký theo tuần</h2>
        <div id="weeklyLogs" class="text-sm"></div>
        </section>

        <section class="bg-white border rounded-xl p-4 mt-4">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Báo cáo cuối đồ án</h2>
            <button id="btnGradeFinal" class="px-3 py-1.5 bg-emerald-600 text-white rounded text-sm"><i class="ph ph-check-circle"></i> Chấm điểm</button>
        </div>
        <div id="finalReport" class="text-sm text-slate-700"></div>
        </section>

        <section class="bg-white border rounded-xl p-4 mt-4">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Hội đồng & điểm số</h2>
            <button id="btnUpdateScores" class="px-3 py-1.5 border border-slate-200 rounded text-sm"><i class="ph ph-pencil"></i> Cập nhật điểm</button>
        </div>
        <div id="committee" class="text-sm text-slate-700"></div>
        </section>
    </div>

    <script>
        function qs(k){
        const params = new URLSearchParams(location.search);
        return params.get(k) || '';
        }
        const id = qs('id');
        const name = decodeURIComponent(qs('name')) || 'Sinh viên';
        const LS_KEY = `lecturer:student:${id}`;
        const defaultData = {
        id,
        name,
        class: 'KTPM2021',
        email: `${id}@sv.uni.edu`,
        phone: '09xx xxx xxx',
        topic: 'Hệ thống quản lý thư viện',
        startDate: '01/08/2025',
        status: 'Tốt',
        supervisor: 'TS. Nguyễn Văn A',
        outline: {
            status: 'Đã duyệt',
            lastReminder: undefined,
            // Lưu lịch sử các lần nộp (mới nhất ở đầu danh sách)
            submissions: [
                {
                    title: 'Đề cương: Hệ thống quản lý thư viện',
                    fileName: 'de-cuong-thu-vien.pdf',
                    fileUrl: '#',
                    submittedAt: '28/07/2025 16:20',
                    status: 'Đã duyệt',
                    note: undefined
                }
            ]
        },
        weeks: [
            {
            week: 1,
            range: '28/07 - 03/08',
            status: 'Đã nộp',
            tasks: [
                { name: 'Khảo sát yêu cầu', done: true },
                { name: 'Phân tích use case', done: true },
                { name: 'Thiết kế ERD', done: false }
            ],
            report: 'Hoàn thành khảo sát nghiệp vụ và phác thảo ERD sơ bộ.',
            files: ['bao-cao-tuan-1.pdf'],
            score: 8.5
            },
            {
            week: 2,
            range: '04/08 - 10/08',
            status: 'Chưa nộp',
            tasks: [],
            report: '',
            files: [],
            score: null
            }
        ],
        finalReport: {
            title: 'Báo cáo đồ án tốt nghiệp',
            fileName: 'bao-cao-cuoi.pdf',
            fileUrl: '#',
            submittedAt: '12/08/2025 09:00',
            similarity: '8% (Turnitin)',
            supervisorScore: 9.0,
            rubric: [
            { name: 'Tổng quan & mục tiêu', max: 2, score: 1.8 },
            { name: 'Nội dung & phương pháp', max: 3, score: 2.7 },
            { name: 'Kết quả & đánh giá', max: 3, score: 2.8 },
            { name: 'Trình bày & bố cục', max: 1, score: 0.8 },
            { name: 'Thái độ & tiến độ', max: 1, score: 0.9 }
            ]
        },
        committee: {
            code: 'CNTT-01',
            name: 'Hội đồng CNTT-01',
            date: '20/08/2025',
            time: '08:00',
            studentTime: '08:00',
            reviewOrder: '01',
            room: 'P.A203',
            members: [
            { role: 'Chủ tịch', name: 'PGS.TS. Trần Văn B' },
            { role: 'Ủy viên 1', name: 'TS. Lê Thị C' },
            { role: 'Ủy viên 2', name: 'TS. Phạm Văn D' },
            { role: 'Ủy viên 3', name: 'ThS. Trần Thị F' },
            { role: 'Thư ký', name: 'ThS. Nguyễn Văn G' },
            { role: 'Phản biện', name: 'TS. Nguyễn Thị E' }
            ],
            reviewer: { name: 'TS. Nguyễn Thị E', score: 8.7, note: 'Nhận xét tốt, cần bổ sung kiểm thử.' },
            defenseScores: [
            { by: 'Chủ tịch', score: 9.0 },
            { by: 'Ủy viên 1', score: 8.5 },
            { by: 'Ủy viên 2', score: 8.6 },
            { by: 'Ủy viên 3', score: 8.7 },
            { by: 'Thư ký', score: 8.8 }
            ]
        }
        };

        function loadData(){
        try {
            const raw = localStorage.getItem(LS_KEY);
            return raw ? JSON.parse(raw) : defaultData;
        } catch { return defaultData; }
        }
        function saveData(data){ localStorage.setItem(LS_KEY, JSON.stringify(data)); }

        let student = loadData();

            // Header
            document.getElementById('header').innerHTML = `
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-slate-500">MSSV: <span class="font-medium text-slate-700">${student.id}</span></div>
                        <h2 class="font-semibold text-lg mt-1">${student.name}</h2>
                        <div class="text-sm text-slate-600">Lớp: ${student.class}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-slate-500">GVHD</div>
                        <div class="font-medium text-blue-600">${student.supervisor}</div>
                    </div>
                </div>`;

            // Sidebar toggle/profile controls
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

        // Topic box
        document.getElementById('topicInfo').innerHTML = `
        <div><span class="text-slate-500">Đề tài: </span><span class="font-medium">${student.topic}</span></div>
        `;
        document.getElementById('startDate').textContent = student.startDate;
        document.getElementById('status').textContent = student.status;

        // Contact
        document.getElementById('contact').innerHTML = `
        <div><span class="text-slate-500">Email: </span><a class="text-blue-600 hover:underline" href="mailto:${student.email}">${student.email}</a></div>
        <div><span class="text-slate-500">SĐT: </span>${student.phone}</div>
        `;

                // Outline - show submitted outlines (no upload here)
                const outlineBox = document.getElementById('outlineBox');
                function outlineStatusPill(s){
                    switch(s){
                        case 'Đã duyệt': return 'bg-emerald-50 text-emerald-700';
                        case 'Đã nộp': return 'bg-amber-50 text-amber-700';
                        case 'Bị từ chối': return 'bg-rose-50 text-rose-700';
                        default: return 'bg-slate-100 text-slate-700'; // Chưa nộp
                    }
                }
                // Normalize existing data to submissions list
                (function normalizeOutline(){
                    const ol = student.outline || {};
                    if(!ol.submissions){
                        const hasLegacy = !!(ol.fileName || ol.fileUrl || ol.submittedAt || ol.title);
                        ol.submissions = hasLegacy ? [{
                            title: ol.title || 'Đề cương',
                            fileName: ol.fileName || 'de-cuong.pdf',
                            fileUrl: ol.fileUrl || '#',
                            submittedAt: ol.submittedAt || new Date().toLocaleString('vi-VN'),
                            status: ol.status || 'Đã nộp',
                            note: ol.note
                        }] : [];
                        // Set overall status
                        ol.status = ol.status || (ol.submissions[0]?.status || 'Chưa nộp');
                        student.outline = ol; saveData(student);
                    }
                })();

                function renderOutline(){
                    const ol = student.outline || {};
                    const subs = Array.isArray(ol.submissions) ? ol.submissions : [];
                    const latest = subs[0];
                    const overallStatus = ol.status || (latest?.status || 'Chưa nộp');
                    const hasAny = subs.length > 0 && !!(latest?.fileUrl && latest.fileUrl !== '#');

                    const headerHtml = `
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-medium">${latest?.title || 'Chưa có đề cương'}</div>
                                <div class="text-slate-600">Tệp: ${hasAny ? `<a href="${latest.fileUrl}" target="_blank" class="text-blue-600 hover:underline">${latest.fileName||'Tệp đề cương'}</a>` : '<span class="text-slate-500">-</span>'}</div>
                                <div class="text-slate-500">Nộp lúc: ${latest?.submittedAt || '-'}</div>
                                ${ol.lastReminder ? `<div class="text-xs text-slate-500">Nhắc gần nhất: ${ol.lastReminder}</div>` : ''}
                                ${latest?.status==='Bị từ chối' && latest?.note ? `<div class="text-sm text-rose-600 mt-1">Lý do từ chối: ${latest.note}</div>` : ''}
                            </div>
                            <div>
                                <span class="px-2 py-0.5 rounded-full text-xs ${outlineStatusPill(overallStatus)}">${overallStatus}</span>
                            </div>
                        </div>`;

                    const listHtml = subs.length ? `
                        <div class="mt-3">
                            <div class="text-slate-600 text-sm mb-1">Các lần nộp</div>
                            <div class="divide-y border rounded bg-white">
                                ${subs.map((s,idx)=>`
                                    <div class="p-2 flex items-center justify-between gap-3">
                                        <div>
                                            <div class="text-sm"><span class="text-slate-500">#${idx+1}${idx===0?' • mới nhất':''}:</span> ${s.title}</div>
                                            <div class="text-xs text-slate-600">${s.submittedAt} • <a class="text-blue-600 hover:underline" href="${s.fileUrl}" target="_blank">${s.fileName}</a></div>
                                            ${s.status==='Bị từ chối' && s.note ? `<div class="text-xs text-rose-600">Lý do từ chối: ${s.note}</div>` : ''}
                                        </div>
                                        <div>
                                            <span class=\"px-2 py-0.5 rounded-full text-xs ${outlineStatusPill(s.status)}\">${s.status}</span>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>` : '';

                    const actionsHtml = `
                        <div class="mt-3 flex flex-wrap gap-2">
                            ${hasAny ? `<a id="btnOutlineDownload" href="${latest.fileUrl}" target="_blank" class="px-3 py-1.5 border border-slate-200 rounded text-sm"><i class="ph ph-download-simple"></i> Tải đề cương</a>` : ''}
                            ${hasAny && overallStatus !== 'Đã duyệt' ? `<button id="btnOutlineApprove" class="px-3 py-1.5 bg-emerald-600 text-white rounded text-sm"><i class="ph ph-check"></i> Duyệt</button>` : ''}
                            ${hasAny && overallStatus !== 'Đã duyệt' ? `<button id="btnOutlineReject" class="px-3 py-1.5 border border-slate-200 rounded text-sm"><i class="ph ph-x"></i> Từ chối</button>` : ''}
                            ${overallStatus !== 'Đã duyệt' ? `<button id="btnOutlineRemind" class="px-3 py-1.5 border border-slate-200 rounded text-sm"><i class="ph ph-bell"></i> Nhắc nộp</button>` : ''}
                        </div>`;

                    outlineBox.innerHTML = `
                        <div class="flex flex-col">${headerHtml}${listHtml}${actionsHtml}</div>
                    `;

                    // Wire actions (approve/reject latest only)
                    document.getElementById('btnOutlineApprove')?.addEventListener('click', ()=>{
                        if(subs.length){ subs[0].status = 'Đã duyệt'; subs[0].note = undefined; }
                        student.outline.status = 'Đã duyệt';
                        saveData(student); renderOutline();
                    });
                    document.getElementById('btnOutlineReject')?.addEventListener('click', ()=>{
                        const body = `
                            <label class=\"block text-sm mb-1\">Lý do từ chối</label>
                            <textarea id=\"rejNote\" class=\"w-full px-3 py-2 border border-slate-200 rounded h-24\" placeholder=\"Nhập lý do...\"></textarea>`;
                        createModal('Từ chối đề cương', body, (close)=>{
                            const note = (document.getElementById('rejNote')?.value||'').trim();
                            if(subs.length){ subs[0].status = 'Bị từ chối'; subs[0].note = note; }
                            student.outline.status = 'Bị từ chối';
                            saveData(student); renderOutline(); close();
                        });
                    });
                    document.getElementById('btnOutlineRemind')?.addEventListener('click', ()=>{
                        student.outline.lastReminder = new Date().toLocaleString('vi-VN');
                        saveData(student);
                        alert('Đã gửi nhắc nộp đến sinh viên.');
                        renderOutline();
                    });
                }
                renderOutline();

        // Weekly logs
        function renderWeekly(){
        const wrap = document.getElementById('weeklyLogs');
        if(!student.weeks || student.weeks.length===0){
            wrap.innerHTML = '<div class="text-slate-500">Chưa có nhật ký tuần.</div>';
            return;
        }
        wrap.innerHTML = `
            <div class="overflow-x-auto border rounded-xl bg-white">
            <table class="w-full text-sm">
                <thead>
                <tr class="text-left text-slate-500 border-b">
                    <th class="py-2 px-3">Tuần</th>
                    <th class="py-2 px-3">Thời gian</th>
                    <th class="py-2 px-3">Trạng thái</th>
                    <th class="py-2 px-3">Điểm</th>
                    <th class="py-2 px-3">Hành động</th>
                </tr>
                </thead>
                <tbody>
                ${student.weeks.map(w => `
                    <tr class="border-b hover:bg-slate-50">
                    <td class="py-2 px-3">Tuần ${w.week}</td>
                    <td class="py-2 px-3">${w.range}</td>
                    <td class="py-2 px-3">${w.status==='Đã nộp' ? '<span class=\'px-2 py-0.5 rounded-full text-xs bg-blue-50 text-blue-600\'>Đã nộp</span>' : '<span class=\'px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-600\'>Chưa nộp</span>'}</td>
                    <td class="py-2 px-3">${w.score ?? '-'}</td>
                    <td class="py-2 px-3">
                        <a class="text-blue-600 hover:underline" href="weekly-log-detail.html?studentId=${encodeURIComponent(student.id)}&name=${encodeURIComponent(student.name)}&week=${w.week}">Xem chi tiết</a>
                    </td>
                    </tr>
                `).join('')}
                </tbody>
            </table>
            </div>`;
        }
        renderWeekly();

        // Final report
        function renderFinal(){
        const fr = student.finalReport;
        document.getElementById('finalReport').innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="border rounded-lg p-3 bg-slate-50">
                <div class="font-medium">${fr.title}</div>
                <div class="text-slate-600">Tệp: <a href="${fr.fileUrl}" class="text-blue-600 hover:underline">${fr.fileName}</a></div>
                <div class="text-slate-500">Nộp lúc: ${fr.submittedAt}</div>
                <div class="text-slate-500">Tương đồng: ${fr.similarity}</div>
            </div>
            <div class="border rounded-lg p-3">
                <div class="text-slate-600">Điểm GVHD</div>
                <div class="text-3xl font-bold">${fr.supervisorScore ?? '-'}</div>
            </div>
            </div>`;
        }
        renderFinal();

        // Committee
        function avg(arr){
        const nums = arr.map(x=>x.score).filter(x=>typeof x==='number');
        if(!nums.length) return '-';
        const s = nums.reduce((a,b)=>a+b,0)/nums.length;
        return Math.round(s*100)/100;
        }
        function renderCommittee(){
        const c = student.committee;
    // Fallbacks for older saved data
    const code = c.code || (typeof c.name === 'string' ? (c.name.match(/[A-ZĐ]+\w*-?\d+/)?.[0] || 'CNTT-01') : 'CNTT-01');
    const order = c.reviewOrder || '01';
    const stime = c.studentTime || c.time || '-';
        const avgDefense = avg(c.defenseScores);
        document.getElementById('committee').innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 border rounded-lg p-3 bg-slate-50">
                <div class="font-medium mb-1">${c.name}</div>
                <div class="text-slate-600">Thời gian: ${c.date} ${c.time} • Phòng: ${c.room}</div>
                <div class="mt-2">
                <div class="text-slate-600 mb-1">Thành viên:</div>
                <ul class="list-disc pl-5 space-y-0.5">
                    ${c.members.map(m=>`<li><span class="text-slate-500">${m.role}:</span> ${m.name}</li>`).join('')}
                </ul>
                </div>
            </div>
            <div class="border rounded-lg p-3">
                <div class="font-medium mb-2">Phản biện</div>
        <div class="text-slate-600">GV phản biện: ${c.reviewer.name}</div>
        <div class="text-slate-600">Chức vụ: <span class="font-medium">Phản biện</span></div>
        <div class="text-slate-600">Hội đồng: <a class="text-blue-600 hover:underline" href="committee-detail.html?id=${encodeURIComponent(code)}">${code}</a></div>
        <div class="text-slate-600">Số thứ tự PB: <span class="font-medium">${order}</span></div>
        <div class="text-slate-600">Thời gian: ${c.date} • ${stime}</div>
        <div class="text-slate-600 mt-2">Điểm phản biện: <span class="font-semibold">${c.reviewer.score ?? '-'}</span></div>
        <div class="text-slate-500">Nhận xét: ${c.reviewer.note || '-'}</div>
            </div>
            </div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
            ${c.defenseScores.map(s=>`
                <div class="border rounded-lg p-3 bg-white">
                <div class="text-slate-500">${s.by}</div>
                <div class="text-2xl font-bold">${s.score ?? '-'}</div>
                </div>`).join('')}
            <div class="border rounded-lg p-3 bg-emerald-50">
                <div class="text-emerald-700">Trung bình bảo vệ</div>
                <div class="text-3xl font-bold text-emerald-700">${avgDefense}</div>
            </div>
            </div>`;
        }
        renderCommittee();

        // Modal helper
        function createModal(title, bodyHtml, onSubmit){
        const wrap = document.createElement('div');
        wrap.className = 'fixed inset-0 z-50 flex items-end sm:items-center justify-center';
        wrap.innerHTML = `
            <div class="absolute inset-0 bg-black/40" data-close></div>
            <div class="relative bg-white w-full sm:max-w-md rounded-t-2xl sm:rounded-2xl shadow-lg p-4 m-0 sm:m-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold">${title}</h3>
                <button data-close class="text-slate-500"><i class="ph ph-x"></i></button>
            </div>
            <div class="text-sm">${bodyHtml}</div>
            <div class="mt-4 flex justify-end gap-2">
                <button data-close class="px-3 py-1.5 border border-slate-200 rounded text-sm">Hủy</button>
                <button data-submit class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm">Lưu</button>
            </div>
            </div>`;
        document.body.appendChild(wrap);
        const close = ()=>wrap.remove();
        wrap.addEventListener('click', (e)=>{ if(e.target.matches('[data-close]')) close(); });
        wrap.querySelector('[data-submit]').addEventListener('click', ()=>{ onSubmit(close); });
        return { close };
        }

        // Grade final report with rubric
        document.getElementById('btnGradeFinal').addEventListener('click', ()=>{
        const fr = student.finalReport;
        const rubric = (fr.rubric && Array.isArray(fr.rubric) && fr.rubric.length)
            ? fr.rubric
            : [
                { name: 'Tổng quan & mục tiêu', max: 2, score: null },
                { name: 'Nội dung & phương pháp', max: 3, score: null },
                { name: 'Kết quả & đánh giá', max: 3, score: null },
                { name: 'Trình bày & bố cục', max: 1, score: null },
                { name: 'Thái độ & tiến độ', max: 1, score: null }
            ];

        const rows = rubric.map((c,i)=>`
            <tr>
            <td class="py-2 px-2">${c.name}</td>
            <td class="py-2 px-2 text-center text-slate-500">${c.max}</td>
            <td class="py-2 px-2">
                <input data-ridx="${i}" type="number" min="0" max="${c.max}" step="0.1" value="${c.score ?? ''}" class="w-28 px-2 py-1 border border-slate-200 rounded" />
            </td>
            </tr>
        `).join('');

        const body = `
            <div class="overflow-x-auto">
            <table class="w-full text-sm border rounded">
                <thead>
                <tr class="text-left text-slate-600 border-b">
                    <th class="py-2 px-2">Tiêu chí</th>
                    <th class="py-2 px-2 text-center">Tối đa</th>
                    <th class="py-2 px-2">Điểm</th>
                </tr>
                </thead>
                <tbody>${rows}</tbody>
                <tfoot>
                <tr class="border-t bg-slate-50">
                    <td class="py-2 px-2 font-medium" colspan="2">Tổng</td>
                    <td class="py-2 px-2 font-bold"><span id="rubricTotal">0</span> / ${rubric.reduce((a,b)=>a+b.max,0)}</td>
                </tr>
                </tfoot>
            </table>
            </div>`;

        const modal = createModal('Chấm điểm báo cáo cuối', body, (close)=>{
            // Gather inputs
            const inputs = Array.from(document.querySelectorAll('[data-ridx]'));
            let valid = true;
            const newRubric = rubric.map((c,i)=>{
            const input = inputs.find(el=>parseInt(el.getAttribute('data-ridx'))===i);
            const v = parseFloat(input?.value);
            const score = isNaN(v) ? 0 : Math.max(0, Math.min(c.max, v));
            if(isNaN(v)) valid = false;
            return { ...c, score };
            });
            const total = newRubric.reduce((sum,c)=>sum + (c.score||0), 0);
            student.finalReport.rubric = newRubric;
            student.finalReport.supervisorScore = Math.round(total * 100) / 100;
            saveData(student);
            renderFinal();
            close();
        });

        // After modal mounted, wire live total calc
        setTimeout(()=>{
            function recalc(){
            const inputs = Array.from(document.querySelectorAll('[data-ridx]'));
            let t = 0;
            inputs.forEach((el)=>{
                const max = parseFloat(el.getAttribute('max')) || 0;
                let v = parseFloat(el.value);
                if(!isNaN(v)){
                v = Math.max(0, Math.min(max, v));
                t += v;
                }
            });
            const totEl = document.getElementById('rubricTotal');
            if(totEl) totEl.textContent = Math.round(t*100)/100;
            }
            document.querySelectorAll('[data-ridx]').forEach(el=>{
            el.addEventListener('input', recalc);
            el.addEventListener('change', recalc);
            });
            recalc();
        }, 0);
        });

        // Update reviewer/defense scores
        document.getElementById('btnUpdateScores').addEventListener('click', ()=>{
        const c = student.committee;
        const body = `
            <div class="space-y-3">
            <div>
                <div class="text-slate-600 mb-1">Điểm phản biện (${c.reviewer.name})</div>
                <input id="inpReviewer" type="number" min="0" max="10" step="0.1" value="${c.reviewer.score ?? ''}" class="w-full px-3 py-2 border border-slate-200 rounded" />
            </div>
            ${c.defenseScores.map((s,idx)=>`
                <div>
                <div class="text-slate-600 mb-1">${s.by}</div>
                <input data-def-index="${idx}" type="number" min="0" max="10" step="0.1" value="${s.score ?? ''}" class="w-full px-3 py-2 border border-slate-200 rounded" />
                </div>
            `).join('')}
            </div>`;
        createModal('Cập nhật điểm bảo vệ', body, (close)=>{
            const rv = parseFloat(document.getElementById('inpReviewer').value);
            if(!isNaN(rv)) student.committee.reviewer.score = rv;
            document.querySelectorAll('[data-def-index]').forEach(inp=>{
            const i = parseInt(inp.getAttribute('data-def-index'));
            const v = parseFloat(inp.value);
            if(!isNaN(v)) student.committee.defenseScores[i].score = v;
            });
            saveData(student);
            renderCommittee();
            close();
        });
        });
    </script>
    </body>
</html>