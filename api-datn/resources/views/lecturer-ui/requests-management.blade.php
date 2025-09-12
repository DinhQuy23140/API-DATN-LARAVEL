<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tiếp nhận yêu cầu sinh viên</title>
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
    @php
      $user = auth()->user();
      $userName = $user->fullname ?? $user->name ?? 'Giảng viên';
      $email = $user->email ?? '';
      // Tùy mô hình dữ liệu, thay các field bên dưới cho khớp
      $dept = $user->department_name ?? optional($user->teacher)->department ?? '';
      $faculty = $user->faculty_name ?? optional($user->teacher)->faculty ?? '';
      $subtitle = trim(($dept ? "Bộ môn $dept" : '') . (($dept && $faculty) ? ' • ' : '') . ($faculty ? "Khoa $faculty" : ''));
      $degree = $user->teacher->degree ?? '';
      $expertise = $user->teacher->supervisor->expertise ?? 'null';
      $data_assignment_supervisors = $user->teacher->supervisor->assignment_supervisors ?? "null";
      $supervisorId = $user->teacher->supervisor->id ?? null;
      $teacherId = $user->teacher->id ?? null;
      $avatarUrl = $user->avatar_url
        ?? $user->profile_photo_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    @endphp
  <div class="flex min-h-screen">
    <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Lecturer</div>
          <div class="text-xs text-slate-500">Bảng điều khiển</div>
        </div>
      </div>
@php
        $isThesisOpen = request()->routeIs('web.teacher.thesis_internship') || request()->routeIs('web.teacher.thesis_rounds');
      @endphp
      <nav class="flex-1 overflow-y-auto p-3">
        <a href="{{ route('web.teacher.overview') }}"
          class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.overview') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <i class="ph ph-gauge"></i><span class="sidebar-label">Tổng quan</span>
        </a>

        <a href="{{ route('web.teacher.profile') }}"
          class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.profile') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <i class="ph ph-user"></i><span class="sidebar-label">Hồ sơ</span>
        </a>

        <a href="{{ route('web.teacher.research') }}"
          class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.research') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <i class="ph ph-flask"></i><span class="sidebar-label">Nghiên cứu</span>
        </a>

        @if ($user->teacher && $user->teacher->supervisor)
          <a href="{{ route('web.teacher.students', ['supervisorId' => $user->teacher->supervisor->id]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.students') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span>
          </a>
        @else
          <span class="text-slate-400">Chưa có supervisor</span>
        @endif

        @php
          $isThesisOpen = request()->routeIs('web.teacher.thesis_internship') || request()->routeIs('web.teacher.thesis_rounds');
        @endphp
        <button type="button" id="toggleThesisMenu" aria-controls="thesisSubmenu"
          aria-expanded="{{ $isThesisOpen ? 'true' : 'false' }}"
          class="w-full flex items-center justify-between px-3 py-2 rounded-lg mt-3 {{ $isThesisOpen ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <span class="flex items-center gap-3">
            <i class="ph ph-graduation-cap"></i>
            <span class="sidebar-label">Học phần tốt nghiệp</span>
          </span>
          <i id="thesisCaret" class="ph ph-caret-down transition-transform {{ $isThesisOpen ? 'rotate-180' : '' }}"></i>
        </button>

        <div id="thesisSubmenu" class="mt-1 pl-3 space-y-1 {{ $isThesisOpen ? '' : 'hidden' }}">
          <a href="{{ route('web.teacher.thesis_internship') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_internship') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
            @if(request()->routeIs('web.teacher.thesis_internship')) aria-current="page" @endif>
            <i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span>
          </a>
          <a href="{{ route('web.teacher.thesis_rounds', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_rounds') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
            @if(request()->routeIs('web.teacher.thesis_rounds')) aria-current="page" @endif>
            <i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span>
          </a>
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
            <h1 class="text-lg md:text-xl font-semibold">Tiếp nhận yêu cầu sinh viên</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đồ án tốt nghiệp</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Tiếp nhận yêu cầu</span>
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
            <a href="thesis-round-detail.html" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại đợt</a>
          </div>
    <!-- Stage info banner -->
    <section class="bg-white border rounded-xl p-4 mb-4">
      <div class="flex items-start justify-between">
        <div>
          <div class="text-xs uppercase text-slate-500">Giai đoạn</div>
          <h2 class="text-lg font-semibold">Giai đoạn 01: Tiếp nhận yêu cầu sinh viên</h2>
          <div class="text-sm text-slate-600">
              Thời gian: {{ \Carbon\Carbon::parse($timeStage->start_date)->format('d/m/Y') }}
              – {{ \Carbon\Carbon::parse($timeStage->end_date)->format('d/m/Y') }}
              • Hạn phản hồi chuẩn: 7 ngày
          </div>
        </div>
        <div class="text-right">
          <span class="px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-700">Đang diễn ra</span>
        </div>
      </div>
    </section>
    @php
      $items = $rows->first()->supervisors->first()->assignment_supervisors ?? [];
    @endphp
    <!-- Quick stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
      <div class="bg-blue-50 p-4 rounded-lg flex items-center gap-3">
        <div class="h-10 w-10 rounded-lg bg-blue-600/10 text-blue-600 grid place-items-center"><i class="ph ph-inbox"></i></div>
        <div><div class="text-2xl font-bold text-blue-600">{{ $items->count() }}</div><div class="text-sm text-blue-800">Tổng yêu cầu</div></div>
      </div>
      <div class="bg-yellow-50 p-4 rounded-lg flex items-center gap-3">
        <div class="h-10 w-10 rounded-lg bg-yellow-600/10 text-yellow-600 grid place-items-center"><i class="ph ph-hourglass"></i></div>
        <div><div class="text-2xl font-bold text-yellow-600">{{ $items->where('status', 'pending')->count() }}</div><div class="text-sm text-yellow-800">Chờ duyệt</div></div>
      </div>
      <div class="bg-green-50 p-4 rounded-lg flex items-center gap-3">
        <div class="h-10 w-10 rounded-lg bg-green-600/10 text-green-600 grid place-items-center"><i class="ph ph-check-circle"></i></div>
        <div><div class="text-2xl font-bold text-green-600">{{ $items->where('status', 'accepted')->count() }}</div><div class="text-sm text-green-800">Đã chấp nhận</div></div>
      </div>
      <div class="bg-red-50 p-4 rounded-lg flex items-center gap-3">
        <div class="h-10 w-10 rounded-lg bg-red-600/10 text-red-600 grid place-items-center"><i class="ph ph-x-circle"></i></div>
        <div><div class="text-2xl font-bold text-red-600">{{ $items->where('status', 'rejected')->count() }}</div><div class="text-sm text-red-800">Từ chối</div></div>
      </div>
    </div>

    <!-- Filters and bulk actions -->
    <div class="bg-white border rounded-xl p-3 mb-3">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
          <div class="relative">
            <i class="ph ph-magnifying-glass absolute left-2 top-2.5 text-slate-400"></i>
            <input class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tên/MSSV/đề tài" />
          </div>
          <select class="px-3 py-2 border border-slate-200 rounded text-sm">
            <option value="">Tất cả trạng thái</option>
            <option>Chờ duyệt</option>
            <option>Đã chấp nhận</option>
            <option>Từ chối</option>
          </select>
          <input type="date" class="px-3 py-2 border border-slate-200 rounded text-sm" />
          <button class="px-2 py-1 text-sm text-slate-600 hover:text-slate-800">Đặt lại</button>
        </div>
        <div class="flex items-center gap-2">
          <button class="px-3 py-1.5 bg-green-600 text-white rounded text-sm disabled:opacity-50"><i class="ph ph-check"></i> Chấp nhận đã chọn</button>
          <button class="px-3 py-1.5 bg-red-600 text-white rounded text-sm disabled:opacity-50"><i class="ph ph-x"></i> Từ chối đã chọn</button>
        </div>
      </div>
    </div>

    <!-- Requests table -->
    <div class="overflow-x-auto bg-white border rounded-xl">
      <table class="w-full text-sm">
        <thead class="bg-slate-50">
          <tr class="text-left text-slate-500 border-b">
            <th class="py-3 px-3"><input type="checkbox" /></th>
            <th class="py-3 px-3">Sinh viên</th>
            <th class="py-3 px-3">MSSV</th>
            <th class="py-3 px-3">Đề tài đề xuất</th>
            <th class="py-3 px-3">Ngày gửi</th>
            <th class="py-3 px-3">Hạn phản hồi</th>
            <th class="py-3 px-3">Trạng thái</th>
            <th class="py-3 px-3">Hành động</th>
          </tr>
        </thead>
        <tbody>
          @if (count($items) > 0)
            @foreach ($items as $item)
              <tr class="border-b hover:bg-slate-50">
                <td class="py-3 px-3"><input type="checkbox" /></td>
                <td class="py-3 px-3">{{ $item->assignment->student->user->fullname }}</td>
                <td class="py-3 px-3">{{ $item->assignment->student->student_code }}</td>
                <td class="py-3 px-3">{{ $item->assignment->project_id ? $item->assignment->project_id : 'Chưa có đề tài' }}</td>
                <td class="py-3 px-3">{{ $item->created_at->format('d/m/Y') }}</td>
                <td class="py-3 px-3">{{ $item->created_at->addDays(7)->format('d/m/Y') }}</td>
                @php
                  $statusColors = [
                    'approved' => 'bg-green-100 text-green-800',
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'accepted' => 'bg-green-100 text-green-800',
                    'rejected' => 'bg-red-100 text-red-800',
                  ];
                  $statusLabels = [
                    'approved' => 'Đã duyệt',
                    'pending' => 'Chờ duyệt',
                    'accepted' => 'Đã chấp nhận',
                    'rejected' => 'Từ chối',
                  ];
                  $statusClass = $statusColors[$item->status] ?? 'bg-slate-100 text-slate-800';
                  $statusLabel = $statusLabels[$item->status] ?? ucfirst($item->status);
                @endphp
                <td class="py-3 px-3" data-col="status">
                  <span class="status-pill px-2 py-0.5 rounded-full text-xs {{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
                <td class="py-3 px-3" data-col="actions">
                  @if ($item->status === 'pending')
                    <button
                     type="button"
                      class="accept-btn px-2 py-1 text-sm bg-green-600 text-white rounded mr-2 hover:bg-green-700"
                      data-id="{{ $item->id }}"
                      data-name="{{ $item->assignment->student->user->fullname }}"
                      data-url="{{ route('web.teacher.requests.accept', $item->id) }}">
                      Chấp nhận
                    </button>
                    <button
                     type="button"
                      class="reject-btn px-2 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700"
                      data-id="{{ $item->id }}"
                      data-name="{{ $item->assignment->student->user->fullname }}"
                      data-url="{{ route('web.teacher.requests.reject', $item->id) }}">
                      Từ chối
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
              <tr>
                <td colspan="8" class="py-6 px-3 text-center text-slate-500">Không có yêu cầu nào.</td>
              </tr>
          @endif
        </tbody>
      </table>
    </div>

    <!-- Legend / notes -->
    <div class="mt-4 bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm text-slate-600">
      <div class="mb-1 font-medium text-slate-700">Ghi chú giai đoạn</div>
      <div>• Hạn phản hồi tiêu chuẩn là trong vòng 7 ngày kể từ ngày nhận yêu cầu.</div>
      <div>• Có thể chấp nhận nhiều yêu cầu cùng lúc nếu phù hợp chỉ tiêu.</div>
          </div>
        </main>
      </div>
    </div>

    <script>
      const CSRF = `{{ csrf_token() }}`;
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const h=document.querySelector('header'); const m=document.querySelector('main');
        if(c){ html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); m.classList.add('md:pl-[72px]');}
        else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); m.classList.remove('md:pl-[72px]');}
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
      const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

      // Toggle submenu "Học phần tốt nghiệp"
      const toggleBtn = document.getElementById('toggleThesisMenu');
      const thesisMenu = document.getElementById('thesisSubmenu');
      const thesisCaret = document.getElementById('thesisCaret');
      toggleBtn?.addEventListener('click', () => {
        const isHidden = thesisMenu?.classList.toggle('hidden');
        const expanded = !isHidden;
        toggleBtn?.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        thesisCaret?.classList.toggle('rotate-180', expanded);
      });

      // Toast
      function toast(msg, type='info') {
        const host = document.getElementById('toastHost') || (() => {
          const d=document.createElement('div'); d.id='toastHost';
          d.className='fixed top-4 right-4 z-50 space-y-2'; document.body.appendChild(d); return d;
        })();
        const color = type==='success' ? 'bg-emerald-600' : type==='error' ? 'bg-rose-600' : 'bg-slate-800';
        const el=document.createElement('div');
        el.className=`px-4 py-2 rounded-lg text-white text-sm shadow ${color}`;
        el.textContent=msg; host.appendChild(el);
        setTimeout(()=>{ el.style.opacity='0'; el.style.transform='translateY(-4px)'; el.style.transition='all .25s'; }, 1800);
        setTimeout(()=> el.remove(), 2100);
      }

      // Decision Modal
      function openDecisionModal({type, id, name, onConfirm}) {
        const isAccept = type === 'accept';
        const wrap = document.createElement('div');
        wrap.className='fixed inset-0 z-50 flex items-center justify-center px-4';
        wrap.innerHTML = `
          <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" data-close></div>
          <div class="relative w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
            <div class="px-5 py-4 border-b bg-gradient-to-r from-white/90 to-white/40 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="h-9 w-9 grid place-items-center rounded-lg ${isAccept?'bg-emerald-50 text-emerald-600':'bg-rose-50 text-rose-600'}">
                  <i class="ph ${isAccept?'ph-check-circle':'ph-x-circle'}"></i>
                </div>
                <h3 class="font-semibold text-lg">${isAccept?'Chấp nhận yêu cầu':'Từ chối yêu cầu'}</h3>
              </div>
              <button class="p-2 rounded-lg hover:bg-slate-100" data-close><i class="ph ph-x"></i></button>
            </div>
            <div class="p-5 space-y-4">
              <p class="text-sm text-slate-600">
                Sinh viên: <span class="font-medium text-slate-800">${name||'-'}</span>
              </p>
              <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">
                  Ghi chú ${isAccept?'(tuỳ chọn)':'(bắt buộc)'}
                </label>
                <textarea id="decisionNote" rows="4" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="${isAccept?'Nhập ghi chú nếu cần...':'Vui lòng nêu lý do từ chối...'}"></textarea>
                <p id="noteError" class="hidden mt-1 text-xs text-rose-600">Vui lòng nhập lý do từ chối.</p>
              </div>
            </div>
            <div class="px-5 py-4 bg-slate-50 border-t flex items-center justify-end gap-2">
              <button class="px-4 py-2 rounded-lg border border-slate-300 hover:bg-slate-100 text-sm" data-close>Hủy</button>
              <button id="confirmDecision" class="px-4 py-2 rounded-lg text-sm text-white ${isAccept?'bg-emerald-600 hover:bg-emerald-700':'bg-rose-600 hover:bg-rose-700'}">
                ${isAccept?'Chấp nhận':'Từ chối'}
              </button>
            </div>
          </div>
        `;
        function close(){ wrap.remove(); document.removeEventListener('keydown', esc); }
        function esc(e){ if(e.key==='Escape') close(); }
        wrap.querySelectorAll('[data-close]').forEach(b=> b.addEventListener('click', close));
        wrap.addEventListener('click', e=> { if(e.target === wrap) close(); });
        document.addEventListener('keydown', esc);
        document.body.appendChild(wrap);
        wrap.querySelector('#confirmDecision')?.addEventListener('click', async ()=>{
          const note = (wrap.querySelector('#decisionNote')?.value||'').trim();
          if(!isAccept && !note){
            wrap.querySelector('#noteError')?.classList.remove('hidden'); return;
          }
          wrap.querySelector('#noteError')?.classList.add('hidden');
          await onConfirm?.({id, note});
          close();
        });
      }

      // Bind sau khi DOM sẵn sàng
      document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('click', (e) => {
           const btn = e.target.closest('.accept-btn, .reject-btn');
           if (!btn) return;
           e.preventDefault();
           const isAccept = btn.classList.contains('accept-btn');
           const tr = btn.closest('tr');
           const id = btn.getAttribute('data-id');
           const name = btn.getAttribute('data-name');
           const url = btn.dataset.url;
          if (!url) { toast('Thiếu URL xử lý, kiểm tra routes', 'error'); return; }
           openDecisionModal({
             type: isAccept ? 'accept' : 'reject',
             id, name,
             onConfirm: async ({ id, note }) => {
               try {
                 const res = await fetch(url, {
                   method: 'POST',
                   headers: {
                     'Content-Type': 'application/json',
                     'Accept': 'application/json',
                     'X-CSRF-TOKEN': CSRF
                   },
                   body: JSON.stringify({ status: isAccept ? 'accepted' : 'rejected', note })
                 });
                 if (!res.ok) {
                   if (res.status === 419) { toast('Phiên làm việc hết hạn, vui lòng tải lại trang', 'error'); return; }
                   toast('Thao tác thất bại', 'error'); return;
                 }
               } catch {
                 toast('Lỗi mạng', 'error'); return;
               }
               // Cập nhật UI
               const statusTd = tr.querySelector('[data-col="status"] .status-pill');
               const actionsTd = tr.querySelector('[data-col="actions"]');
               if (isAccept) {
                 statusTd.textContent = 'Đã chấp nhận';
                 statusTd.className = 'status-pill px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800';
               } else {
                 statusTd.textContent = 'Từ chối';
                 statusTd.className = 'status-pill px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-800';
               }
               actionsTd.innerHTML = '';
               toast(isAccept ? 'Đã chấp nhận yêu cầu' : 'Đã từ chối yêu cầu', 'success');
             }
           });
        });
      });
    </script>
  <!-- Toast host -->
  <div id="toastHost" class="fixed top-4 right-4 z-50 space-y-2"></div>
</html>
