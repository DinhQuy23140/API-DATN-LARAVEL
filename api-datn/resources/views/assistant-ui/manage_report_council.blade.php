<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng ký tham gia đợt đồ án</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial}
    .sidebar{width:260px}
    .sidebar-collapsed .sidebar{width:72px}
    .sidebar-collapsed .sidebar-label{display:none}
  /* Modern table row hover and compact status pills (indigo-accent) */
  .reg-row{ transition:transform .18s ease, box-shadow .18s ease, background-color .18s ease }
  .reg-row:hover{ background: linear-gradient(180deg, rgba(99,102,241,0.02), rgba(99,102,241,0.01)); transform: translateY(-4px); box-shadow: 0 8px 30px rgba(15,23,42,0.06); }
  .status-pill { display:inline-flex; align-items:center; gap:.5rem; padding:.28rem .6rem; border-radius:999px; font-weight:600; font-size:.78rem }
  .status-approved { background:#ecfeef; color:#065f46; box-shadow: inset 0 1px 0 rgba(255,255,255,0.4) }
  .status-rejected { background:#fff1f2; color:#9f1239; box-shadow: inset 0 1px 0 rgba(255,255,255,0.4) }
  .status-pending { background:#eef2ff; color:#3730a3; box-shadow: inset 0 1px 0 rgba(255,255,255,0.45) }
  .action-btn { width:38px; height:38px; border-radius:10px; display:inline-grid; place-items:center; border:1px solid rgba(99,102,241,0.12); background:white; transition:transform .14s ease, box-shadow .14s ease }
  .action-btn:hover { transform:translateY(-3px); box-shadow:0 10px 26px rgba(99,102,241,0.08) }
    @media (max-width:768px){
      .hide-sm { display:none }
      table thead { display:none }
      table, tbody, tr, td { display:block; width:100% }
      tr { margin-bottom: 0.75rem }
      td { padding: .75rem 1rem; display:flex; justify-content:space-between; align-items:center }
      td::before { content: attr(data-label); color:#64748b; font-size:.75rem; margin-right:1rem }
    }
  </style>
</head>
@php
  $user = auth()->user();
  $userName = $user->fullname ?? $user->name ?? 'Người dùng';
  $email = $user->email ?? '';
  $avatarUrl = $user->avatar_url ?? $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
@endphp
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
          <a href="{{ route('web.assistant.assign_head') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Phân trưởng bộ môn</span></a>

          <div class="graduation-item">
            <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button font-semibold bg-slate-100">
              <span class="flex items-center gap-3">
                <i class="ph ph-graduation-cap"></i>
                <span class="sidebar-label">Học phần tốt nghiệp</span>
              </span>
              <i class="ph ph-caret-down"></i>
            </div>
            <div id="gradMenu" class="submenu pl-6">
              <a href="#" class="block px-3 py-2 rounded hover:bg-slate-100"><i class="ph ph-briefcase"></i> Thực tập tốt nghiệp</a>
              <a href="{{ route('web.assistant.rounds') }}"
                 class="block px-3 py-2 rounded hover:bg-slate-100 bg-slate-100 font-semibold"
                 aria-current="page"><i class="ph ph-calendar"></i> Đồ án tốt nghiệp</a>
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
            <h1 class="text-lg md:text-xl font-semibold">Đăng ký tham gia đợt đồ án</h1>
            <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Đợt đồ án / Đăng ký</nav>
          </div>
        </div>
        <div class="relative">
          <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
            <img class="h-9 w-9 rounded-full object-cover" src="{{ $avatarUrl }}" alt="avatar" />
            <div class="hidden sm:block text-left">
              <div class="text-sm font-semibold leading-4">{{ $userName }}</div>
              <div class="text-xs text-slate-500">{{ $email }}</div>
            </div>
            <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
          </button>
          <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông tin</a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
          <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">@csrf</form>
          </div>
        </div>
      </header>

<main class="flex-1 overflow-y-auto px-4 md:px-6 py-8 bg-gradient-to-br from-slate-50 via-white to-blue-50/30">
  <div class="max-w-7xl mx-auto space-y-6">

    <!-- Header section -->
    <section class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm relative overflow-hidden">
      <div class="absolute top-0 right-0 w-40 h-40 bg-blue-100/30 rounded-bl-full opacity-50"></div>
      <div class="flex items-start gap-4 relative z-10">
        <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 grid place-items-center shadow-inner">
          <i class="ph ph-clipboard-text text-2xl"></i>
        </div>
        <div class="flex-1">
          <h2 class="text-lg font-semibold text-slate-800">Báo cáo sinh viên nộp sau phản biện đồ án</h2>
          <p class="text-sm text-slate-500 mt-1">
            Quản lý danh sách báo cáo sinh viên nộp sau khi bảo vệ đồ án hoàn tất. 
          </p>
        </div>
      </div>
    </section>

    <!-- Action bar -->
    <section class="bg-gradient-to-r from-indigo-600 to-indigo-500 rounded-xl shadow-md p-4 flex flex-wrap items-center justify-between text-white">
        <div class="flex items-center gap-3 mt-2 md:mt-0">
            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-white/70"></i>
                <input id="searchReg" placeholder="Tìm theo tên / MSSV / lớp / đề tài"
                    class="pl-9 pr-3 py-2 rounded-lg border border-white/20 bg-white/10 text-sm text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 transition" />
            </div>
        </div>
    </section>

    <!-- Table -->
          <section class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse">
          <thead class="bg-slate-100 text-slate-700 text-xs uppercase tracking-wide">
            <tr>
              <th class="px-4 py-3 text-left"><i class="ph ph-barcode mr-2"></i> MSSV</th>
              <th class="px-4 py-3 text-center"><i class="ph ph-user-circle mr-2"></i> Họ tên</th>
              <th class="px-4 py-3 text-left hide-sm"><i class="ph ph-books mr-2"></i> Lớp</th>
              <th class="px-4 py-3 text-center hide-sm"><i class="ph ph-at mr-2"></i> Đề tài</th>
              <th class="px-4 py-3 text-left hide-sm"><i class="ph ph-calendar mr-2"></i> Báo cáo </th>
            </tr>
          </thead>
          <tbody id="regList" class="divide-y divide-slate-100 bg-white">
            @foreach ($assignments as $assignment)
            @php
              $student = $assignment->student;
              $user = $student->user;
              $project = $assignment->project;
            @endphp
              <tr class="reg-row transition">
                <td class="px-4 py-3 font-mono" data-label="MSSV">{{ $student->student_code }}</td>
                <td class="px-4 py-3" data-label="Họ tên">
                  <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-full bg-blue-50 text-blue-600 grid place-items-center text-sm font-medium">{{ implode('', array_slice(array_map(fn($n) => $n[0] ?? '', explode(' ', $user->fullname)), -2)) }}</div>
                    <div class="min-w-0">
                      <div class="text-sm font-medium text-slate-800 truncate">{{ $user->fullname }}</div>
                      <div class="text-xs text-slate-400 hide-sm">{{ $student->class_code }} · {{ $user->email ?? '—' }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-4 py-3 hide-sm" data-label="Lớp">{{ $student->class_code }}</td>
                <td class="px-4 py-3 hide-sm text-center" data-label="Lớp">{{ $project->name ?? "-" }}</td>
                <td class="px-4 py-3 hide-sm" data-label="Báo cáo">
                    @php
                        $reportCouncil = $project ? $project->reportFiles->where('type_report', 'report_council')->sortByDesc('created_at')->first() : null;
                    @endphp
                    @if (optional($reportCouncil)->file_url)
                        <a class="inline-flex items-center gap-1 text-blue-600 hover:underline"
                        href="{{ $reportCouncil->file_url }}" target="_blank">
                        <i class="ph ph-download"></i> Tải báo cáo
                        </a>
                    @else
                        <span class="text-slate-400">Chưa nộp báo cáo</span>
                    @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </section>

  </div>
</main>

    </div>
  </div>

  <script>
    // Sidebar & profile handlers (reuse patterns)
    const html = document.documentElement, sidebar = document.getElementById('sidebar');
    document.getElementById('toggleSidebar')?.addEventListener('click', ()=>{ const c = !html.classList.contains('sidebar-collapsed'); html.classList.toggle('sidebar-collapsed', c); localStorage.setItem('assistant_sidebar', c? '1' : '0'); });
    document.getElementById('openSidebar')?.addEventListener('click', ()=> sidebar.classList.toggle('-translate-x-full'));
    if(localStorage.getItem('assistant_sidebar')==='1') html.classList.add('sidebar-collapsed');
    sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

    const profileBtn = document.getElementById('profileBtn'); const profileMenu = document.getElementById('profileMenu');
    profileBtn?.addEventListener('click', ()=> profileMenu?.classList.toggle('hidden'));

    function statusPillHtml(s){
      if (s==='approved') return '<span class="statusPill px-2 py-0.5 rounded-full text-xs bg-emerald-50 text-emerald-700">Đã duyệt</span>';
      if (s==='rejected') return '<span class="statusPill px-2 py-0.5 rounded-full text-xs bg-rose-50 text-rose-700">Đã hủy</span>';
      return '<span class="statusPill px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700">Chờ duyệt</span>';
    }

    function renderRegs(list){
      regList.innerHTML = (list||[]).map(r=>`<tr class="reg-row transition">
        <td class="px-4 py-3 font-mono" data-label="MSSV">${r.student_code}</td>
        <td class="px-4 py-3" data-label="Họ tên">
          <div class="flex items-center gap-3">
            <div class="h-8 w-8 rounded-full bg-blue-50 text-blue-600 grid place-items-center text-sm font-medium">${(r.fullname||'').split(' ').map(n=>n[0]||'').slice(-2).join('')}</div>
            <div class="min-w-0">
              <div class="text-sm font-medium text-slate-800 truncate">${r.fullname}</div>
              <div class="text-xs text-slate-400 hide-sm">${r.class} · ${r.email||'—'}</div>
            </div>
          </div>
        </td>
        <td class="px-4 py-3 hide-sm" data-label="Lớp">${r.class}</td>
        <td class="px-4 py-3 hide-sm" data-label="Email">${r.email||'<span class="text-slate-400">—</span>'}</td>
        <td class="px-4 py-3 text-sm text-slate-600" data-label="Lý do">${r.motivation || '<span class="text-slate-400">—</span>'}</td>
        <td class="px-4 py-3 hide-sm" data-label="Minh chứng">${(r.attachments||[]).length? (r.attachments.map(f=>`<a class="inline-flex items-center gap-1 text-blue-600 hover:underline" href="#"> <i class="ph ph-paperclip"></i> ${f} </a>`).join('<br/>')) : '<span class="text-slate-400">—</span>'}</td>
        <td class="px-4 py-3 text-center" data-label="Trạng thái">${statusPillHtml(r.status)}</td>
        <td class="px-4 py-3 hide-sm" data-label="Ngày đăng ký">${r.applied_at}</td>
        <td class="px-4 py-3 text-right" data-label="Hành động">
          <div class="flex items-center justify-end gap-2">
            <button title="Xem chi tiết" class="action-btn btnView" data-id="${r.id}"><i class="ph ph-eye"></i></button>
            <button title="Duyệt" class="action-btn btnApprove" data-id="${r.id}" data-student="${r.fullname}"><i class="ph ph-check" style="color:#059669"></i></button>
            <button title="Hủy" class="action-btn btnReject" data-id="${r.id}" data-student="${r.fullname}"><i class="ph ph-x" style="color:#dc2626"></i></button>
          </div>
        </td>
      </tr>`).join('');
    }


    // Search filter: filter the already-rendered table rows (server-side rendered)
    document.getElementById('searchReg')?.addEventListener('input', (e)=>{
      const q = (e.target.value||'').toLowerCase().trim();
      const rows = document.querySelectorAll('#regList tr');
      rows.forEach(tr => {
        const text = (tr.innerText || '').toLowerCase();
        tr.style.display = q === '' || text.includes(q) ? '' : 'none';
      });
    });

    // action handlers: call server routes for approve/reject
    document.getElementById('regList')?.addEventListener('click', async (e)=>{
      const approveBtn = e.target.closest('.btnApprove');
      const rejectBtn = e.target.closest('.btnReject');
      if(!approveBtn && !rejectBtn) return;
      const btn = approveBtn || rejectBtn;
      const id = Number(btn.dataset.id);
      if (!id) return;
      const isApprove = !!approveBtn;
      const studentName = btn.dataset.student || '';
      if(!confirm(`${isApprove? 'Duyệt' : 'Hủy'} đăng ký của ${studentName}?`)) return;

      const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
      const url = `/assistant/register-project-terms/${id}/${isApprove ? 'approve' : 'reject'}`;

      // UI feedback
      btn.disabled = true;
      btn.classList.add('opacity-60');

      try {
        const res = await fetch(url, { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json().catch(()=> ({}));
        if (!res.ok || data.ok === false) {
          alert(data.message || 'Thao tác thất bại.');
          btn.disabled = false; btn.classList.remove('opacity-60');
          return;
        }

        // update row status cell
        const tr = btn.closest('tr');
        if (tr) {
          const statusCell = tr.querySelector('td[data-label="Trạng thái"]');
          if (statusCell) {
            // replace inner span
            statusCell.innerHTML = isApprove
              ? '<span class="px-2 py-1 rounded-full text-xs bg-emerald-50 text-emerald-700">Đã duyệt</span>'
              : '<span class="px-2 py-1 rounded-full text-xs bg-rose-50 text-rose-700">Đã hủy</span>';
          }
        }

        // reload to sync lists/counts with server-side state
        try { window.location.reload(); } catch(e) { /* ignore reload failure */ }
      } catch (err) {
        alert('Lỗi mạng, thử lại.');
      } finally {
        btn.disabled = false; btn.classList.remove('opacity-60');
      }
    });

    // view detail
    document.getElementById('regList')?.addEventListener('click', (e)=>{
      const viewBtn = e.target.closest('.btnView');
      if(!viewBtn) return;
      const id = Number(viewBtn.dataset.id);
      const rec = registrations.find(r=>r.id===id);
      if(!rec) return;
      // simple detail modal (alert for demo) — replace with proper modal if needed
      alert(`Hồ sơ của ${rec.fullname}\nMSSV: ${rec.student_code}\nLớp: ${rec.class}\nEmail: ${rec.email}\nĐề tài: ${rec.project}\nGiảng viên ưa thích: ${rec.preferred}\nLý do: ${rec.motivation}\nMinh chứng: ${(rec.attachments||[]).join(', ')}`);
    });

    // select all / bulk actions
    document.getElementById('selectAll')?.addEventListener('change', (e)=>{
      const checked = !!e.target.checked;
      document.querySelectorAll('.reg-checkbox').forEach(cb=> cb.checked = checked);
    });

    function getSelectedIds(){
      return Array.from(document.querySelectorAll('.reg-checkbox:checked')).map(cb=> Number(cb.dataset.id));
    }

    document.getElementById('bulkApprove')?.addEventListener('click', ()=>{
      const ids = getSelectedIds(); if(!ids.length){ alert('Vui lòng chọn hồ sơ để duyệt'); return; }
      if(!confirm(`Duyệt ${ids.length} hồ sơ đã chọn?`)) return;
      registrations.forEach(r=>{ if(ids.includes(r.id)) r.status='approved'; }); renderRegs(registrations);
    });

    document.getElementById('bulkReject')?.addEventListener('click', ()=>{
      const ids = getSelectedIds(); if(!ids.length){ alert('Vui lòng chọn hồ sơ để hủy'); return; }
      if(!confirm(`Hủy ${ids.length} hồ sơ đã chọn?`)) return;
      registrations.forEach(r=>{ if(ids.includes(r.id)) r.status='rejected'; }); renderRegs(registrations);
    });
  </script>
</body>
</html>
