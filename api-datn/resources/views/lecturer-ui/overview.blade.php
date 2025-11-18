<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tổng quan Giảng viên</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <style>
    body {
      font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
    }

    .sidebar-collapsed .sidebar-label {
      display: none;
    }

    .sidebar-collapsed .sidebar {
      width: 72px;
    }

    .sidebar {
      width: 260px;
    }
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
    $data_assignment_supervisors = $assignmentSupervisors;
    $teacherId = $user->teacher->id ?? 0;
    $avatarUrl = $user->avatar_url
      ?? $user->profile_photo_url
      ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    $departmentRole = $user->teacher->departmentRoles->where('role', 'head')->first() ?? null;
    $departmentId = $departmentRole?->department_id ?? 0;
  @endphp
  <div class="flex min-h-screen">
    <aside id="sidebar"
      class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i
            class="ph ph-chalkboard-teacher"></i></div>
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

        @if($user->teacher && $user->teacher->supervisor)
            <a href="{{ route('web.teacher.students', ['teacherId' => $teacherId]) }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.students') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
              <i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span>
            </a>
        @else
            <a href="#"
               class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.students') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
              <i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span>
            </a>
        @endif


        <!-- Mục cha: Học phần tốt nghiệp (accordion) -->
        <button type="button" id="toggleThesisMenu"
                class="w-full flex items-center justify-between px-3 py-2 rounded-lg mt-3
                       {{ $isThesisOpen ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <span class="flex items-center gap-3">
            <i class="ph ph-graduation-cap"></i>
            <span class="sidebar-label">Học phần tốt nghiệp</span>
          </span>
          <i id="thesisCaret" class="ph ph-caret-down transition-transform {{ $isThesisOpen ? 'rotate-180' : '' }}"></i>
        </button>

        <!-- Submenu -->
        <div id="thesisSubmenu" class="mt-1 pl-3 space-y-1 {{ $isThesisOpen ? '' : 'hidden' }}">
          <a href="{{ route('web.teacher.thesis_internship') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_internship') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span>
          </a>
          @if ($departmentRole)
            <a href="{{ route('web.teacher.all_thesis_rounds', ['teacherId' => $teacherId]) }}"
              class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.all_thesis_rounds') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
              <i class="ph ph-calendar"></i><span class="sidebar-label">Học phần tốt nghiệp</span>
            </a>
          @else
            <a href="{{ route('web.teacher.thesis_rounds', ['teacherId' => $teacherId]) }}"
              class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_rounds') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
              <i class="ph ph-calendar"></i><span class="sidebar-label">Học phần tốt nghiệp</span>
            </a>
          @endif
        </div>
      </nav>
      <div class="p-3 border-t border-slate-200">
        <button id="toggleSidebar"
          class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i
            class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
      </div>
    </aside>

    <div class="flex-1 h-screen overflow-hidden">
      <header
        class="fixed left-0 md:left-[260px] right-0 top-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-20">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i
              class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Tổng quan</h1>
            <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Giảng viên / Tổng quan</nav>
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

      <main class="pt-20 px-4 md:px-6 pb-10 space-y-6 h-[calc(100vh-4rem)] overflow-auto">
        <div class="max-w-6xl mx-auto space-y-6">
          <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile card -->
            <div class="bg-white rounded-xl border border-slate-200 p-5">
              <div class="flex items-center gap-4">
                <img class="h-16 w-16 rounded-full object-cover" src="{{ $avatarUrl }}" alt="avatar" />
                <div>
                  <div class="font-semibold">{{ $userName }}</div>
                  <div class="text-sm text-slate-500">
                    <!-- {{ $subtitle !== '' ? $subtitle : 'Giảng viên' }} -->
                      {{ $degree }}
                  </div>
                  <div class="text-xs text-slate-500">{{ $email }}</div>
                  <div class="mt-1">
                    <span class="px-2 py-1 text-xs rounded-full bg-emerald-50 text-emerald-700">
                      Đã cập nhật {{ optional($user->updated_at)->diffForHumans() ?? 'gần đây' }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Research interests card -->
            <div class="bg-white rounded-xl border border-slate-200 p-5 lg:col-span-2">
              <div class="flex items-center justify-between">
                <h2 class="font-semibold">Hướng nghiên cứu</h2>
                <div class="flex items-center gap-2">
                  <a class="px-3 py-1.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm"
                     href="{{ route('web.teacher.research') }}"><i class="ph ph-pencil"></i> Chỉnh sửa</a>
                </div>
              </div>
              <div id="researchContainer" class="mt-4">
                @php
                  $researches = $user->userResearches ?? collect();
                @endphp

                @if($researches->isEmpty())
                  <div class="text-sm text-slate-600 bg-slate-50 border border-slate-100 rounded-lg px-4 py-3 text-center">
                    <i class="ph ph-info text-indigo-500 text-base mr-1"></i>
                    Chưa có hướng nghiên cứu.
                  </div>
                @else
                  <div class="mt-3 grid gap-3 sm:grid-cols-1">
                    @foreach($researches as $uS)
                      @php $r = $uS->research ?? null; @endphp
                      <div class="flex items-center gap-3 bg-white border border-slate-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="p-2 rounded-md bg-indigo-50 text-indigo-600">
                          <i class="ph ph-flask text-lg"></i>
                        </div>
                        <div class="flex-1">
                          <div class="font-medium text-slate-900 leading-snug">
                            {{ $r?->name ?? ($uS->title ?? '—') }}
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @endif
              </div>
            </div>
          </section>

          <!-- Supervised students card -->
          @php
            $currentYear = (int) date('Y');
            $years = [];
            for ($i = 0; $i < 6; $i++) {
              $start = $currentYear - $i;
              $end = $start + 1;
              $years[] = "{$start}-{$end}";
            }
            $semesters = ['HK1', 'HK2', 'HK Hè'];
          @endphp
          <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl border border-slate-200 p-5 lg:col-span-3">
              <div class="flex items-center justify-between gap-3 flex-wrap">
                <h2 class="font-semibold">SV đã hướng dẫn</h2>
                <a href="{{ route('web.teacher.students', ['teacherId' => $user->teacher?->id ?? 0]) }}"
                  class="text-blue-600 hover:underline text-sm">
                  Xem tất cả
                </a>
              </div>

              <!-- Filters -->
              <div class="mt-4">
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                  <div class="overflow-x-auto">
                    <table class="w-full text-sm table-auto">
                      <thead class="bg-gradient-to-r from-slate-50 to-white text-slate-600 text-xs uppercase sticky top-0 z-10">
                        <tr>
                          <th class="px-4 py-3 text-left whitespace-nowrap">
                            <div class="flex items-center gap-2"><i class="ph ph-hash text-lg text-slate-400"></i><span>MSSV</span></div>
                          </th>
                          <th class="px-4 py-3 text-left">
                            <div class="flex items-center gap-2"><i class="ph ph-user text-lg text-slate-400"></i><span>Họ tên</span></div>
                          </th>
                          <th class="px-4 py-3 text-left">
                            <div class="flex items-center gap-2"><i class="ph ph-envelope text-lg text-slate-400"></i><span>Email</span></div>
                          </th>
                          <th class="px-4 py-3 text-left whitespace-nowrap">
                            <div class="flex items-center gap-2"><i class="ph ph-calendar text-lg text-slate-400"></i><span>Đợt</span></div>
                          </th>
                          <th class="px-4 py-3 text-left whitespace-nowrap">
                            <div class="flex items-center gap-2"><i class="ph ph-calendar-check text-lg text-slate-400"></i><span>Năm học</span></div>
                          </th>
                          <th class="px-4 py-3 text-left">
                            <div class="flex items-center gap-2"><i class="ph ph-notebook text-lg text-slate-400"></i><span>Đề tài</span></div>
                          </th>
                          <th class="px-4 py-3 text-left whitespace-nowrap">
                            <div class="flex items-center gap-2"><i class="ph ph-flag text-lg text-slate-400"></i><span>Trạng thái</span></div>
                          </th>
                        </tr>
                      </thead>
                      <tbody id="studentsTbody" class="divide-y divide-slate-100">
                      @php
                        // Lọc danh sách chỉ lấy các supervisor có status = accepted
                        $accepted_supervisors = collect($data_assignment_supervisors)->filter(function ($item) {
                            return $item->status != "pending";
                        });
                      @endphp

                      @forelse ($accepted_supervisors as $assignment_supervisor)
                        @php
                          $student   = optional(optional($assignment_supervisor->assignment))->student;
                          $userStu   = optional($student)->user;
                          $code      = $student->student_code ?? '—';
                          $name      = $userStu->fullname ?? '—';
                          $emailStu  = $userStu->email ?? '—';

                          $stage     = data_get($assignment_supervisor, 'assignment.project_term.stage', '');
                          $yearName  = data_get($assignment_supervisor, 'assignment.project_term.academy_year.year_name', '');
                          $topic     = data_get($assignment_supervisor, 'assignment.project.name', '');
                          $status    = data_get($assignment_supervisor, 'assignment.status', '');
                          $statusKey = strtolower((string) $status);

                          $statusMap = [
                              'pending' => ['class' => 'bg-amber-50 text-amber-700', 'label' => 'Chờ duyệt'],
                              'actived' => ['class' => 'bg-emerald-50 text-emerald-700', 'label' => 'Đang hoạt động'],
                              'stopped' => ['class' => 'bg-rose-50 text-rose-700', 'label' => 'Đã dừng'],
                              'cancelled' => ['class' => 'bg-slate-200 text-slate-600', 'label' => 'Đã hủy'],
                          ];

                          $statusClass = $statusMap[$statusKey]['class'] ?? 'bg-slate-100 text-slate-700';
                          $statusLabel = $statusMap[$statusKey]['label'] ?? 'Không xác định';
                        @endphp

                        <tr class="hover:bg-slate-50" data-name="{{ $name }}" data-year="{{ $yearName }}" data-term="{{ $stage }}">
                          <td class="px-4 py-3 font-medium text-slate-800 whitespace-nowrap">{{ $code }}</td>
                          <td class="px-4 py-3 text-slate-700">
                            <div class="flex items-center gap-2 min-w-[160px]">
                              <i class="ph ph-user text-slate-400"></i>
                              <span class="break-words">{{ $name }}</span>
                            </div>
                          </td>
                          <td class="px-4 py-3 max-w-[36ch] break-words">
                            @if ($emailStu && $emailStu !== '—')
                              <a href="mailto:{{ $emailStu }}" class="text-blue-600 hover:underline inline-flex items-center gap-2"><i class="ph ph-envelope text-slate-400"></i><span class="truncate" style="max-width:24ch;">{{ $emailStu }}</span></a>
                            @else
                              <span class="text-slate-500">—</span>
                            @endif
                          </td>
                          <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs">
                              {{ $stage ?: '—' }}
                            </span>
                          </td>
                          <td class="px-4 py-3 whitespace-nowrap">{{ $yearName ?: '—' }}</td>
                          <td class="px-4 py-3 max-w-[40ch]">
                            <div class="inline-flex items-center gap-2">
                              <i class="ph ph-notebook text-slate-400"></i>
                              <div class="truncate" title="{{ $topic }}">{{ $topic ?: '—' }}</div>
                            </div>
                          </td>
                          <td class="px-4 py-3">
                            <div class="inline-flex items-center gap-2">
                              <i class="ph ph-flag text-slate-400"></i>
                              <span class="px-2 py-1 rounded-full text-xs {{ $statusClass }} whitespace-nowrap">{{ $statusLabel }}</span>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="7" class="px-4 py-8">
                            <div class="flex flex-col items-center justify-center gap-2 text-slate-500">
                              <i class="ph ph-users text-2xl"></i>
                              <div>Chưa có sinh viên nào trong danh sách hướng dẫn.</div>
                            </div>
                          </td>
                        </tr>
                      @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>

  <script>
    const html = document.documentElement, sidebar = document.getElementById('sidebar');
    function setCollapsed(c) { const h = document.querySelector('header'); const m = document.querySelector('main'); if (c) { html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); } else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); } }
    document.getElementById('toggleSidebar')?.addEventListener('click', () => { const c = !html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar', '' + (c ? 1 : 0)); });
    document.getElementById('openSidebar')?.addEventListener('click', () => sidebar.classList.toggle('-translate-x-full'));
    if (localStorage.getItem('lecturer_sidebar') === '1') setCollapsed(true);
    sidebar.classList.add('md:translate-x-0', '-translate-x-full', 'md:static');
    // profile dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    profileBtn?.addEventListener('click', () => profileMenu.classList.toggle('hidden'));
    document.addEventListener('click', (e) => { if (!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

    // Research overview dynamic render
    const STORAGE_KEY = 'lecturer_research_items';
    function loadItems() { try { const s = localStorage.getItem(STORAGE_KEY); return s ? JSON.parse(s) : []; } catch { return []; } }
    function renderOverview() {
      const list = document.getElementById('researchList');
      const empty = document.getElementById('researchEmpty');
      if (!list || !empty) return;
      const items = loadItems();
      list.innerHTML = '';
      if (!items || items.length === 0) { empty.classList.remove('hidden'); return; } else { empty.classList.add('hidden'); }
      // show top 3
      for (const it of items.slice(0, 3)) {
        const li = document.createElement('li');
        li.className = 'flex items-start gap-3';
        const keywords = (it.keywords || []).join(', ');
        const fields = (it.fields || []).map(f => `<span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700 mr-1">${f}</span>`).join('');
        li.innerHTML = `
            <i class="ph ph-dot text-xl text-blue-600"></i>
            <div>
              <div class="font-medium">${it.title}</div>
              <div class=\"text-slate-600\">${keywords || ''} ${fields ? `• ${fields}` : ''}</div>
            </div>
            <button class=\"ml-auto px-2 py-1 rounded-lg border hover:bg-slate-50 text-slate-600 text-xs\" onclick=\"openQuickEdit('${it.id}')\"><i class=\"ph ph-pencil\"></i> Sửa</button>`;
        list.appendChild(li);
      }
    }
    // live update when storage changes in another tab
    window.addEventListener('storage', (e) => {
      if (e.key === 'lecturer_research_items' || e.key === 'lecturer_research_last_update') renderOverview();
    });
    // initial
    renderOverview();

    // Quick add/edit modals
    const quickModals = `
      <div id="quickAddModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">
          <div class="p-4 border-b flex items-center justify-between">
            <h3 class="font-semibold">Thêm hướng nghiên cứu</h3>
            <button class="p-2 hover:bg-slate-100 rounded-lg" onclick="closeQuickAdd()"><i class='ph ph-x'></i></button>
          </div>
          <form class="p-4 space-y-4" onsubmit="event.preventDefault(); saveQuickAdd();">
            <div>
              <label class="text-sm font-medium">Tiêu đề</label>
              <input id="qa_title" required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" />
            </div>
            <div>
              <label class="text-sm font-medium">Từ khóa</label>
              <input id="qa_keywords" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" placeholder="AI, NLP, ..." />
            </div>
            <div>
              <label class="text-sm font-medium">Lĩnh vực</label>
              <select id="qa_fields" multiple class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200">
                <option>AI</option><option>Data Science</option><option>Hệ phân tán</option><option>IoT</option>
              </select>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
              <button type="button" onclick="closeQuickAdd()" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
              <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Lưu</button>
            </div>
          </form>
        </div>
      </div>
      <div id="quickEditModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">
          <div class="p-4 border-b flex items-center justify-between">
            <h3 class="font-semibold">Sửa hướng nghiên cứu</h3>
            <button class="p-2 hover:bg-slate-100 rounded-lg" onclick="closeQuickEdit()"><i class='ph ph-x'></i></button>
          </div>
          <form class="p-4 space-y-4" onsubmit="event.preventDefault(); saveQuickEdit();">
            <input type="hidden" id="qe_id" />
            <div>
              <label class="text-sm font-medium">Tiêu đề</label>
              <input id="qe_title" required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" />
            </div>
            <div>
              <label class="text-sm font-medium">Từ khóa</label>
              <input id="qe_keywords" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" />
            </div>
            <div>
              <label class="text-sm font-medium">Lĩnh vực</label>
              <select id="qe_fields" multiple class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200">
                <option>AI</option><option>Data Science</option><option>Hệ phân tán</option><option>IoT</option>
              </select>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
              <button type="button" onclick="closeQuickEdit()" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
              <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Lưu</button>
            </div>
          </form>
        </div>
      </div>`;
    document.body.insertAdjacentHTML('beforeend', quickModals);
    document.getElementById('btnQuickAdd')?.addEventListener('click', () => { document.getElementById('quickAddModal').classList.remove('hidden'); document.getElementById('quickAddModal').classList.add('flex'); });
    function closeQuickAdd() { const m = document.getElementById('quickAddModal'); m.classList.add('hidden'); m.classList.remove('flex'); }
    function saveQuickAdd() {
      const title = document.getElementById('qa_title').value.trim();
      const keywords = document.getElementById('qa_keywords').value.split(',').map(s => s.trim()).filter(Boolean);
      const fields = [...document.getElementById('qa_fields').selectedOptions].map(o => o.value);
      if (!title) return;
      const items = loadItems();
      items.unshift({ id: crypto.randomUUID ? crypto.randomUUID() : ('id_' + Date.now()), title, keywords, fields });
      localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
      localStorage.setItem('lecturer_research_last_update', Date.now().toString());
      closeQuickAdd();
      renderOverview();
    }
    window.openQuickEdit = function (id) {
      const it = loadItems().find(x => x.id === id); if (!it) return;
      document.getElementById('qe_id').value = it.id;
      document.getElementById('qe_title').value = it.title;
      document.getElementById('qe_keywords').value = (it.keywords || []).join(', ');
      const sel = document.getElementById('qe_fields');
      [...sel.options].forEach(o => o.selected = (it.fields || []).includes(o.value));
      const m = document.getElementById('quickEditModal'); m.classList.remove('hidden'); m.classList.add('flex');
    }
    function closeQuickEdit() { const m = document.getElementById('quickEditModal'); m.classList.add('hidden'); m.classList.remove('flex'); }
    function saveQuickEdit() {
      const id = document.getElementById('qe_id').value;
      const title = document.getElementById('qe_title').value.trim();
      const keywords = document.getElementById('qe_keywords').value.split(',').map(s => s.trim()).filter(Boolean);
      const fields = [...document.getElementById('qe_fields').selectedOptions].map(o => o.value);
      const items = loadItems(); const idx = items.findIndex(x => x.id === id); if (idx < 0) return;
      items[idx] = { ...items[idx], title, keywords, fields };
      localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
      localStorage.setItem('lecturer_research_last_update', Date.now().toString());
      closeQuickEdit();
      renderOverview();
    }

    // Toggle submenu Học phần tốt nghiệp
    (function () {
      const btn = document.getElementById('toggleThesisMenu');
      const menu = document.getElementById('thesisSubmenu');
      const caret = document.getElementById('thesisCaret');
      btn?.addEventListener('click', () => {
        menu?.classList.toggle('hidden');
        caret?.classList.toggle('rotate-180');
        btn?.classList.toggle('bg-slate-100');
        btn?.classList.toggle('font-semibold');
      });
    })();

    // Filters: Năm học, Kỳ học, Tên sinh viên
    (function () {
      const yearEl = document.getElementById('filterYear');
      const termEl = document.getElementById('filterTerm');
      const nameEl = document.getElementById('filterName');
      const tbody = document.getElementById('studentsTbody');

      function normalize(v) {
        return (v || '').toString().trim().toLowerCase();
      }

      function filterStudents() {
        const y = normalize(yearEl?.value);
        const t = normalize(termEl?.value);
        const n = normalize(nameEl?.value);

        const rows = tbody?.querySelectorAll('tr') || [];
        rows.forEach(row => {
          const ry = normalize(row.getAttribute('data-year'));
          const rt = normalize(row.getAttribute('data-term'));
          const rn = normalize(row.getAttribute('data-name'));

          const okYear = !y || ry === y;
          const okTerm = !t || rt === t;
          const okName = !n || rn.includes(n);

          row.classList.toggle('hidden', !(okYear && okTerm && okName));
        });
      }

      yearEl?.addEventListener('change', filterStudents);
      termEl?.addEventListener('change', filterStudents);
      nameEl?.addEventListener('input', filterStudents);
    })();
  </script>
</body>

</html>