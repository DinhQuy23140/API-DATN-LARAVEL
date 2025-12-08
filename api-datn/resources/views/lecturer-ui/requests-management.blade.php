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
      $teacherId = $user->teacher->id ?? null;
      $avatarUrl = $user->avatar_url
        ?? $user->profile_photo_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
      $departmentRole = $user->teacher->departmentRoles->where('role', 'head')->first() ?? null;
      $departmentId = $departmentRole?->department_id ?? 0;
    @endphp

  <div class="flex min-h-screen">
    <aside class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all"
      id="sidebar">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i
            class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Giảng viên</div>
          <div class="text-xs text-slate-500">Bảng điều khiển</div>
        </div>
      </div>
      @php
        // Luôn mở nhóm "Học phần tốt nghiệp"
        $isThesisOpen = true;
        // Active item "Đồ án tốt nghiệp" trong submenu (giữ logic cũ)
        $isThesisRoundsActive = request()->routeIs('web.teacher.thesis_rounds')
          || request()->routeIs('web.teacher.thesis_round_detail');
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
          <a id="menuStudents"
            href="{{ route('web.teacher.students', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"
            data-skip-active="1">
             <i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span>
           </a>
        @else
          <span class="text-slate-400">Chưa có supervisor</span>
        @endif

        @php $isThesisOpen = true; @endphp
        <button type="button" id="toggleThesisMenu" aria-controls="thesisSubmenu"
          aria-expanded="{{ $isThesisOpen ? 'true' : 'false' }}"
          class="w-full flex items-center justify-between px-3 py-2 rounded-lg mt-3 {{ $isThesisOpen ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <span class="flex items-center gap-3">
            <i class="ph ph-graduation-cap"></i>
            <span class="sidebar-label">Học phần tốt nghiệp</span>
          </span>
          <i id="thesisCaret" class="ph ph-caret-down transition-transform {{ $isThesisOpen ? 'rotate-180' : '' }}"></i>
        </button>

        <div id="thesisSubmenu" class="mt-1 pl-3 space-y-1">
          <a href="{{ route('web.teacher.thesis_internship') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_internship') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
            @if(request()->routeIs('web.teacher.thesis_internship')) aria-current="page" @endif>
            <i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span>
          </a>
          @if ($departmentRole)
          <a href="{{ route('web.teacher.thesis_rounds', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold {{ $isThesisRoundsActive ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
            @if($isThesisRoundsActive) aria-current="page" @endif>
            <i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span>
          </a>
          @else
          <a href="{{ route('web.teacher.thesis_rounds', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold {{ $isThesisRoundsActive ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
            @if($isThesisRoundsActive) aria-current="page" @endif>
            <i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span>
          </a>
          @endif
        </div>
      </nav>
      <div class="p-3 border-t border-slate-200">
        <button
          class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"
          id="toggleSidebar"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
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
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đồ án tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-round-detail.html" class="hover:underline text-slate-600">Chi tiết đợt đồ án</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Tiếp nhận yêu cầu hướng dẫn</span>
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
          <div id="profileMenu"
            class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
            <a href="{{ route('web.teacher.profile') }}" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50">
              <i class="ph ph-user"></i>Xem thông tin
            </a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
              class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600">
              <i class="ph ph-sign-out"></i>Đăng xuất
            </a>
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
        <div class="max-w-6xl mx-auto">
          <div class="flex items-center justify-between mb-4">
            <div></div>
            <a href="thesis-round-detail.html" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại đợt</a>
          </div>
    <!-- Stage info (modern card, consistent with other pages) -->
    @php
      $stageName = $timeStage->name ?? 'Giai đoạn 01: Tiếp nhận yêu cầu sinh viên';
      $startLabel = $timeStage->start_date ? \Carbon\Carbon::parse($timeStage->start_date)->format('d/m/Y') : '—';
      $endLabel = $timeStage->end_date ? \Carbon\Carbon::parse($timeStage->end_date)->format('d/m/Y') : '—';
      $now = \Carbon\Carbon::now();
      $start = $timeStage->start_date ? \Carbon\Carbon::parse($timeStage->start_date) : null;
      $end = $timeStage->end_date ? \Carbon\Carbon::parse($timeStage->end_date) : null;
      if ($start && $end) {
        if ($now->lt($start)) { $statusText = 'Sắp diễn ra'; $badge = 'bg-yellow-50 text-yellow-700'; $iconClass = 'text-yellow-600'; }
        elseif ($now->gt($end)) { $statusText = 'Đã kết thúc'; $badge = 'bg-slate-100 text-slate-600'; $iconClass = 'text-slate-500'; }
        else { $statusText = 'Đang diễn ra'; $badge = 'bg-emerald-50 text-emerald-700'; $iconClass = 'text-emerald-600'; }
      } else { $statusText = 'Đang diễn ra'; $badge = 'bg-emerald-50 text-emerald-700'; $iconClass = 'text-emerald-600'; }

      $totalRequests = isset($items) ? $items->count() : 0;
      $pendingRequests = isset($items) ? $items->filter(function ($item) { return optional($item->assignment_supervisors->first())->status === 'pending'; })->count() : 0;
    @endphp

    <section class="rounded-3xl overflow-hidden mb-6">
      <div class="bg-gradient-to-r from-indigo-50 to-white border border-slate-200 p-4 md:p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="h-14 w-14 rounded-lg bg-indigo-600/10 grid place-items-center">
            <i class="ph ph-chalkboard-teacher text-indigo-600 text-2xl"></i>
          </div>
          <div>
            <div class="text-sm text-slate-500">Giai đoạn</div>
            <div class="text-lg md:text-xl font-semibold text-slate-900">{{ $stageName }}</div>
            <div class="mt-1 text-sm text-slate-600 flex items-center gap-2">
              <i class="ph ph-calendar-dots text-slate-400"></i>
              <span>Thời gian: {{ $startLabel }} — {{ $endLabel }}</span>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-3 md:gap-4">
          <div class="hidden md:flex items-center gap-3">
            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-lg {{ $badge }} text-sm">
              <i class="ph ph-circle {{ $iconClass }}"></i>
              {{ $statusText }}
            </span>
          </div>

          <div class="grid gap-3">
            <div class="flex items-center gap-3 bg-white border border-slate-100 rounded-lg px-3 py-2 shadow-sm">
              <div class="p-2 rounded-md bg-indigo-50 text-indigo-600">
                <i class="ph ph-clock text-lg"></i>
              </div>
              <div>
                <div class="text-xs text-slate-500">Hạn phản hồi</div>
                <div class="text-sm font-semibold text-slate-800">7 ngày</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    @php
      $items = $rows->assignments ?? collect();
    @endphp

    <!-- Quick stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
      <div class="p-4 rounded-2xl flex items-center gap-3 bg-white shadow-sm hover:shadow-md transition">
        <div class="h-12 w-12 rounded-lg bg-indigo-50 text-indigo-600 grid place-items-center"><i class="ph ph-inbox text-xl"></i></div>
        <div>
          <div class="text-2xl font-bold text-slate-800">{{ $items->count() }}</div>
          <div class="text-sm text-slate-500">Tổng yêu cầu</div>
        </div>
      </div>
      <div class="p-4 rounded-2xl flex items-center gap-3 bg-white shadow-sm hover:shadow-md transition">
        <div class="h-12 w-12 rounded-lg bg-amber-50 text-amber-600 grid place-items-center"><i class="ph ph-hourglass text-xl"></i></div>
        <div>
          <div class="text-2xl font-bold text-slate-800">{{ $items->filter(function ($item) {return optional($item->assignment_supervisors->first())->status === 'pending';})->count() }}</div>
          <div class="text-sm text-slate-500">Chờ duyệt</div>
        </div>
      </div>
      <div class="p-4 rounded-2xl flex items-center gap-3 bg-white shadow-sm hover:shadow-md transition">
        <div class="h-12 w-12 rounded-lg bg-emerald-50 text-emerald-600 grid place-items-center"><i class="ph ph-check-circle text-xl"></i></div>
        <div>
          <div class="text-2xl font-bold text-slate-800">{{ $items->filter(function ($item) {return optional($item->assignment_supervisors->first())->status === 'accepted';})->count() }}</div>
          <div class="text-sm text-slate-500">Đã chấp nhận</div>
        </div>
      </div>
      <div class="p-4 rounded-2xl flex items-center gap-3 bg-white shadow-sm hover:shadow-md transition">
        <div class="h-12 w-12 rounded-lg bg-rose-50 text-rose-600 grid place-items-center"><i class="ph ph-x-circle text-xl"></i></div>
        <div>
          <div class="text-2xl font-bold text-slate-800">{{ $items->filter(function ($item) {return optional($item->assignment_supervisors->first())->status === 'rejected';})->count() }}</div>
          <div class="text-sm text-slate-500">Từ chối</div>
        </div>
      </div>
    </div>

    <!-- Filters and bulk actions (modernized) -->
  <div class="bg-white border rounded-2xl p-3 mb-3 shadow-sm">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
          <div class="relative">
            <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
            <input class="pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm w-64 focus:outline-none focus:ring-2 focus:ring-sky-200" placeholder="Tìm theo tên/MSSV/đề tài" />
          </div>
          <select class="px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white">
            <option value="">Tất cả trạng thái</option>
            <option>Chờ duyệt</option>
            <option>Đã chấp nhận</option>
            <option>Từ chối</option>
          </select>
          <input type="date" class="px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white" />
          <button class="px-3 py-1.5 text-sm text-slate-600 hover:text-slate-800">Đặt lại</button>
        </div>
        <div class="flex items-center gap-2">
          <button class="px-3 py-1.5 bg-emerald-600 text-white rounded-full text-sm shadow-sm hover:shadow-md disabled:opacity-50 flex items-center gap-2"><i class="ph ph-check text-base"></i><span>Chấp nhận đã chọn</span></button>
          <button class="px-3 py-1.5 bg-rose-600 text-white rounded-full text-sm shadow-sm hover:shadow-md disabled:opacity-50 flex items-center gap-2"><i class="ph ph-x text-base"></i><span>Từ chối đã chọn</span></button>
        </div>
      </div>
    </div>

    <!-- Requests table (modernized) -->
    <div class="overflow-x-auto bg-white border rounded-2xl shadow-sm">
      <div class="p-3 overflow-x-auto">
        <table class="w-full text-sm table-auto">
          <thead class="bg-slate-50 sticky top-0">
            <tr class="text-left text-slate-500 border-b">
              <th class="py-3 px-4 w-6"><input type="checkbox" class="text-sky-500" /></th>
              <th class="py-3 px-4">Sinh viên</th>
              <th class="py-3 px-4">MSSV</th>
              <th class="py-3 px-4">Đề tài đề xuất</th>
              <th class="py-3 px-4">Ngày gửi</th>
              <th class="py-3 px-4">Hạn phản hồi</th>
              <th class="py-3 px-4">Trạng thái</th>
              <th class="py-3 px-4 text-right">Hành động</th>
            </tr>
          </thead>
          <tbody>
            @if (count($items) > 0)
              @foreach ($items as $item)
                <tr class="border-b hover:bg-slate-50 group transition-colors">
                  <td class="py-3 px-4 align-top"><input type="checkbox" class="rounded text-sky-500" /></td>
                  <td class="py-3 px-4 align-top">{{ $item->student->user->fullname }}</td>
                  <td class="py-3 px-4 align-top">{{ $item->student->student_code }}</td>
                  <td class="py-3 px-4 align-top text-slate-700">{{ $item->project_id ? $item->project->name : 'Chưa có đề tài' }}</td>
                  <td class="py-3 px-4 align-top">{{ $item->created_at->format('d/m/Y') }}</td>
                  <td class="py-3 px-4 align-top">{{ $item->created_at->addDays(7)->format('d/m/Y') }}</td>
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
                    $status = $item->assignment_supervisors->first()->status;
                    $statusClass = $statusColors[$status] ?? 'bg-slate-100 text-slate-800';
                    $statusLabel = $statusLabels[$status] ?? ucfirst($status);
                  @endphp
                  <td class="py-3 px-4 align-top" data-col="status">
                    <span class="status-pill inline-flex items-center gap-2 px-2 py-1 rounded-full text-xs {{ $statusClass }}">
                      @if($status === 'pending')
                        <i class="ph ph-hourglass text-sm"></i>
                      @elseif($status === 'accepted')
                        <i class="ph ph-check-circle text-sm"></i>
                      @elseif($status === 'rejected')
                        <i class="ph ph-x-circle text-sm"></i>
                      @endif
                      <span>{{ $statusLabel }}</span>
                    </span>
                  </td>
                  <td class="py-3 px-4 align-top text-right" data-col="actions">
                    @if ($item->assignment_supervisors->first()->status === 'pending')
                      @php $as = $item->assignment_supervisors->first(); @endphp
                      <button
                        type="button"
                        class="accept-btn inline-flex items-center gap-2 px-3 py-1.5 text-sm bg-emerald-600 text-white rounded-full mr-2 hover:bg-emerald-700 transition shadow-sm"
                        data-id="{{ $item->id }}"
                        data-name="{{ $item->student->user->fullname}}"
                        data-url="{{ route('web.teacher.requests.accept', $as->id) }}">
                        <i class="ph ph-check text-sm"></i>
                        <span class="hidden sm:inline">Chấp nhận</span>
                      </button>
                      <button
                        type="button"
                        class="reject-btn inline-flex items-center gap-2 px-3 py-1.5 text-sm bg-rose-600 text-white rounded-full hover:bg-rose-700 transition shadow-sm"
                        data-id="{{ $item->id }}"
                        data-name="{{ $item->student->user->fullname }}"
                        data-url="{{ route('web.teacher.requests.reject', $as->id) }}">
                        <i class="ph ph-x text-sm"></i>
                        <span class="hidden sm:inline">Từ chối</span>
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
