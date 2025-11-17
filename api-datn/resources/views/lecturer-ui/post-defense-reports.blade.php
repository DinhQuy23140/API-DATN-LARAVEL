<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quản lý Báo cáo Sau Bảo vệ</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <style> body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; } .sidebar-collapsed .sidebar-label { display:none; } .sidebar-collapsed .sidebar { width:72px; } .sidebar { width:260px; } </style>
</head>
@php
  $user = auth()->user();
  $userName = $user->fullname ?? $user->name ?? 'Giảng viên';
  $email = $user->email ?? '';
  $teacherId = $user->teacher->id ?? 0;
  $avatarUrl = $user->avatar_url ?? $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($userName).'&background=0ea5e9&color=ffffff';
@endphp
<body class="bg-slate-50 text-slate-800">
  <div class="flex min-h-screen">
    <!-- Sidebar (copy of layout used elsewhere to keep UI consistent) -->
    <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Lecturer</div>
          <div class="text-xs text-slate-500">Bảng điều khiển</div>
        </div>
      </div>
      <nav class="flex-1 overflow-y-auto p-3">
        <a href="{{ route('web.teacher.overview') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span class="sidebar-label">Tổng quan</span></a>
        <a href="{{ route('web.teacher.profile') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user"></i><span class="sidebar-label">Hồ sơ</span></a>
        <a href="{{ route('web.teacher.research') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-flask"></i><span class="sidebar-label">Nghiên cứu</span></a>
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
            <h1 class="text-lg md:text-xl font-semibold">Quản lý Báo cáo Sau Bảo vệ</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="{{ route('web.teacher.overview') }}" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Báo cáo sau bảo vệ</span>
            </nav>
          </div>
        </div>
        <div class="relative">
          <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
            <img class="h-9 w-9 rounded-full object-cover" src="{{ $avatarUrl }}" alt="avatar" />
            <div class="hidden sm:block text-left">
              <div class="text-sm font-semibold leading-4">{{ $userName }}</div>
              <div class="text-xs text-slate-500">{{ $email }}</div>
            </div>
          </button>
          <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông tin</a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">@csrf</form>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
        <div class="max-w-6xl mx-auto space-y-6">

          <!-- Toolbar: search + filters + new actions -->
          <div class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col md:flex-row gap-3 md:items-center md:justify-between">
            <div class="flex items-center gap-3 w-full md:w-auto">
              <div class="relative w-full md:w-96">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="searchReports" class="w-full pl-10 pr-3 py-2 rounded-lg border border-slate-200 text-sm" placeholder="Tìm theo tên/sinh viên/mã" />
              </div>
              <select id="filterStatus" class="text-sm border border-slate-200 rounded-lg px-3 py-2">
                <option value="">Tất cả trạng thái</option>
                <option value="pending">Chờ duyệt</option>
                <option value="approved">Đã duyệt</option>
                <option value="rejected">Từ chối</option>
              </select>
            </div>
            <div class="flex items-center gap-3">
              <a href="#" class="inline-flex items-center gap-2 px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"><i class="ph ph-upload-simple"></i> Tải báo cáo</a>
              <a href="#" class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-lg hover:bg-slate-50"><i class="ph ph-faders"></i> Tùy chọn</a>
            </div>
          </div>

          <!-- Reports: server-rendered static cards (single source of truth) -->
          <div id="reportsArea" class="bg-white rounded-xl border border-slate-200 p-4">
            @php $__reports = $reports ?? []; @endphp
            @if(count($__reports) > 0)
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($__reports as $r)
                  @php $st = $r->status ?? 'pending'; @endphp
                  <article class="report-card bg-white border border-slate-100 rounded-xl p-4 shadow-sm flex flex-col justify-between">
                    <div>
                      <div class="text-sm font-semibold text-slate-800">{{ $r->student_name ?? 'N/A' }}</div>
                      <div class="text-xs text-slate-500">{{ $r->student_code ?? '—' }}</div>
                      <div class="text-sm text-slate-700 mt-2">{{ \Illuminate\Support\Str::limit($r->topic ?? '—', 140) }}</div>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                      <div class="text-xs text-slate-500">{{ $r->submitted_at ?? '—' }}</div>
                      <div class="ml-2">
                        @if($st === 'approved')
                          <span class="px-2 py-1 rounded-full text-xs bg-emerald-50 text-emerald-700">Đã duyệt</span>
                        @elseif($st === 'rejected')
                          <span class="px-2 py-1 rounded-full text-xs bg-rose-50 text-rose-700">Từ chối</span>
                        @else
                          <span class="px-2 py-1 rounded-full text-xs bg-yellow-50 text-yellow-700">Chờ duyệt</span>
                        @endif
                      </div>
                    </div>

                    <div class="mt-4 flex items-center gap-2">
                      <button onclick="openReportModal({{ $r->id ?? 0 }})" class="flex-1 text-sm px-3 py-2 rounded-lg bg-blue-600 text-white">Xem</button>
                      <a href="{{ $r->file_url ?? '#' }}" class="text-sm px-3 py-2 rounded-lg bg-white border border-slate-200">Tải</a>
                    </div>
                  </article>
                @endforeach
              </div>
            @else
              <div class="text-center text-slate-500 py-8">Chưa có báo cáo</div>
            @endif
          </div>

        </div>
      </main>
    </div>
  </div>

  <!-- Report modal (simple) -->
  <div id="reportModal" class="hidden fixed inset-0 z-40 bg-black/40 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-3xl overflow-auto max-h-[90vh] p-4">
      <div class="flex items-start justify-between">
        <h3 class="text-lg font-semibold">Chi tiết báo cáo</h3>
        <button onclick="closeReportModal()" class="text-slate-500 hover:text-slate-700"><i class="ph ph-x"></i></button>
      </div>
      <div id="reportModalBody" class="mt-4 text-sm text-slate-700">
        <!-- content loaded by JS or server render when possible -->
        <p>Chọn một báo cáo để xem chi tiết.</p>
      </div>
      <div class="mt-4 flex justify-end gap-2">
        <button onclick="closeReportModal()" class="px-3 py-2 rounded-lg bg-slate-100">Đóng</button>
        <button id="modalApproveBtn" class="px-3 py-2 rounded-lg bg-emerald-600 text-white">Duyệt</button>
      </div>
    </div>
  </div>

  <script>
    // Sidebar toggle
    const html = document.documentElement, sidebar = document.getElementById('sidebar');
    function setCollapsed(c){ if(c) html.classList.add('sidebar-collapsed'); else html.classList.remove('sidebar-collapsed'); }
    document.getElementById('toggleSidebar')?.addEventListener('click', ()=>{ const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar',''+(c?1:0)); });
    document.getElementById('openSidebar')?.addEventListener('click', ()=> sidebar.classList.toggle('-translate-x-full'));
    if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);

    // Profile menu
    const profileBtn=document.getElementById('profileBtn'), profileMenu=document.getElementById('profileMenu');
    profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
    document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

    // Search/filter behavior (client-side quick filter for static cards)
    document.getElementById('searchReports')?.addEventListener('input', function(){
      const q = this.value.toLowerCase().trim();
      // filter the Blade-rendered report cards
      document.querySelectorAll('#reportsArea .report-card').forEach(card => {
        const text = card.innerText.toLowerCase();
        card.style.display = text.includes(q) ? '' : 'none';
      });
    });

    function openReportModal(id){
      // For now do a simple client-side fill using DOM data attributes or fetch later via XHR
      const modal = document.getElementById('reportModal');
      const body = document.getElementById('reportModalBody');
      body.innerHTML = '<p class="text-sm text-slate-600">Đang tải nội dung báo cáo (id='+id+')…</p>';
      modal.classList.remove('hidden');
      // Optionally fetch report detail via fetch(`/api/reports/'+id+')` etc.
    }
    function closeReportModal(){ document.getElementById('reportModal')?.classList.add('hidden'); }
  </script>
</body>
</html>
