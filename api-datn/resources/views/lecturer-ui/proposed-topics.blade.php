<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đề xuất danh sách đề tài</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <!-- SheetJS for Excel import -->
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
    .sidebar-collapsed .sidebar-label { display:none; }
    .sidebar-collapsed .sidebar { width:72px; }
    .sidebar { width:260px; }
    /* Modern modal */
    .modal-overlay {
      animation: modalFade .25s ease;
    }
    .modal-shell {
      animation: modalPop .28s cubic-bezier(.4,.2,.2,1);
    }
    @keyframes modalFade {
      from { opacity:0; }
      to { opacity:1; }
    }
    @keyframes modalPop {
      0% { opacity:0; transform:translateY(8px) scale(.96); }
      100% { opacity:1; transform:translateY(0) scale(1); }
    }
    .floating-label { position:relative; }
    .floating-label input,
    .floating-label textarea,
    .floating-label select {
      padding-top:1.35rem;
    }
    .floating-label label {
      position:absolute; left:.75rem; top:.65rem;
      font-size:.70rem; letter-spacing:.5px;
      font-weight:500; text-transform:uppercase;
      color:rgb(100 116 139);
      pointer-events:none;
    }
    .tag-chip {
      @apply px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700 border border-slate-200;
    }
  </style>
</head>
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
<body class="bg-slate-50 text-slate-800">
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
          <a href="{{ route('web.teacher.all_thesis_rounds', ['teacherId' => $teacherId]) }}"
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

    <div class="flex-1 h-screen overflow-hidden flex flex-col ">
      <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Đề xuất danh sách đề tài</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đồ án tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="" class="hover:underline text-slate-600">Chi tiết đợt đồ án</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Đề xuất đề tài</span>
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
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 bg-white" style="background-color:#FFFFFF">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-4">
            <div></div>
            <a href="thesis-round-detail.html" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại đợt</a>
          </div>

          @php
            // Normalize project term info if provided by controller
            $termName = isset($projectTerm) ? ($projectTerm->academy_year->year_name ?? '—') . ' - Học kỳ ' . ($projectTerm->stage ?? '') : ($termName ?? 'Đợt');
            $startLabel = isset($projectTerm) && $projectTerm->start_date ? \Carbon\Carbon::parse($projectTerm->start_date)->format('d/m/Y') : '—';
            $endLabel   = isset($projectTerm) && $projectTerm->end_date ? \Carbon\Carbon::parse($projectTerm->end_date)->format('d/m/Y') : '—';
            $now = \Carbon\Carbon::now();
            if (isset($projectTerm) && $projectTerm->start_date && $projectTerm->end_date) {
              $start = \Carbon\Carbon::parse($projectTerm->start_date);
              $end = \Carbon\Carbon::parse($projectTerm->end_date);
              if ($now->lt($start)) { $statusText = 'Sắp diễn ra'; $badge = 'bg-yellow-50 text-yellow-700'; $iconClass = 'text-yellow-600'; }
              elseif ($now->gt($end)) { $statusText = 'Đã kết thúc'; $badge = 'bg-slate-100 text-slate-600'; $iconClass = 'text-slate-500'; }
              else { $statusText = 'Đang diễn ra'; $badge = 'bg-emerald-50 text-emerald-700'; $iconClass = 'text-emerald-600'; }
            } else { $statusText = 'Đang diễn ra'; $badge = 'bg-emerald-50 text-emerald-700'; $iconClass = 'text-emerald-600'; }

            // Aggregate proposed topics stats
            $topicCount = isset($proposedTopics) ? (is_countable($proposedTopics) ? count($proposedTopics) : $proposedTopics->count()) : (isset($topicsToShow) ? count($topicsToShow) : 0);
            $openCount = 0; $totalSlots = 0; $totalRegistered = 0;
            if (isset($proposedTopics)) {
              foreach ($proposedTopics as $pt) {
                $status = is_object($pt) ? ($pt->status ?? ($pt['status'] ?? 'Mở')) : ($pt['status'] ?? 'Mở');
                $slots = is_object($pt) ? ($pt->slots ?? ($pt['slots'] ?? 0)) : ($pt['slots'] ?? 0);
                $reg = is_object($pt) ? ($pt->registered ?? ($pt['registered'] ?? 0)) : ($pt['registered'] ?? 0);
                if (trim($status) === 'Mở') $openCount++;
                $totalSlots += (int) $slots;
                $totalRegistered += (int) $reg;
              }
            } elseif (isset($topicsToShow)) {
              foreach ($topicsToShow as $pt) {
                $status = $pt['status'] ?? 'Mở';
                $slots = $pt['slots'] ?? 0;
                $reg = $pt['registered'] ?? 0;
                if (trim($status) === 'Mở') $openCount++;
                $totalSlots += (int) $slots;
                $totalRegistered += (int) $reg;
              }
            }
          @endphp

          <section class="rounded-xl overflow-hidden mb-4">
            <div class="bg-gradient-to-r from-indigo-50 to-white border border-slate-200 p-4 md:p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
              <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-lg bg-indigo-600/10 grid place-items-center">
                  <i class="ph ph-graduation-cap text-indigo-600 text-2xl"></i>
                </div>
                <div>
                  <div class="text-sm text-slate-500">Đợt đồ án</div>
                  <div class="text-lg md:text-xl font-semibold text-slate-900">{{ $termName }}</div>
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

                <div class="grid">
                  <div class="flex items-center gap-3 bg-white border border-slate-100 rounded-lg px-3 py-2 shadow-sm">
                    <div class="p-2 rounded-md bg-indigo-50 text-indigo-600">
                      <i class="ph ph-list-bullets text-lg"></i>
                    </div>
                    <div>
                      <div class="text-xs text-slate-500">Đề tài</div>
                      <div class="text-sm font-semibold text-slate-800">{{ $topicCount }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
    <!-- Controls -->
    <div class="bg-white border rounded-xl p-3 mb-3">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
          <div class="relative">
            <i class="ph ph-magnifying-glass absolute left-2 top-2.5 text-slate-400"></i>
            <input id="searchBox" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tiêu đề/thẻ" />
          </div>
        </div>
        <div class="flex items-center gap-2">
          <button id="btnImport" class="px-3 py-1.5 border border-slate-200 rounded text-sm"><i class="ph ph-upload-simple"></i> Import Excel</button>
          <button id="btnAdd" class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm"><i class="ph ph-plus"></i> Thêm đề tài</button>
        </div>
      </div>
    </div>

    <!-- Topics list rendered server-side -->
    <div id="topicsList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      @php
        // provide fallback topics when controller does not pass $topics
        $defaultTopics = [
          ['id'=>'T001','title'=>'Hệ thống quản lý học tập trực tuyến (LMS)','description'=>'Xây dựng LMS với quản lý khóa học, bài tập, đánh giá; ưu tiên stack Node.js + React.','tags'=>['Web','Node.js','React'],'slots'=>2,'registered'=>1,'status'=>'Mở','updatedAt'=>'15/07/2025'],
          ['id'=>'T002','title'=>'Ứng dụng thương mại điện tử','description'=>'E-commerce full-stack, tích hợp thanh toán, quản lý đơn hàng.','tags'=>['Web','React'],'slots'=>3,'registered'=>2,'status'=>'Mở','updatedAt'=>'20/07/2025'],
        ];
        $topicsToShow = isset($topics) ? $topics : $defaultTopics;
      @endphp

      @foreach($proposedTopics as $t)
        @php
          // allow both array and object shapes
          $id = is_object($t) ? ($t->id ?? '') : ($t['id'] ?? '');
          $title = is_object($t) ? ($t->title ?? '') : ($t['title'] ?? '');
          $description = is_object($t) ? ($t->description ?? '') : ($t['description'] ?? '');
          $updatedAt = is_object($t) ? ($t->updatedAt ?? ($t->updated_at ?? '')) : ($t['updatedAt'] ?? ($t['updated_at'] ?? ''));
          $tags = is_object($t) ? ($t->tags ?? ($t->tags ?? [])) : ($t['tags'] ?? []);
        @endphp

        <article data-topic-id="{{ $id }}" class="bg-white rounded-2xl p-4 shadow hover:shadow-xl transition transform hover:-translate-y-1">
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
              <div class="h-12 w-12 rounded-lg bg-indigo-50 text-indigo-600 grid place-items-center"><i class="ph ph-notebook text-xl"></i></div>
            </div>
            <div class="flex-1">
              <div class="flex items-start justify-between gap-3">
                <div>
                  <h5 class="font-semibold text-slate-900 text-base">{{ $title }}</h5>
                  <div class="text-xs text-slate-400 mt-1"><i class="ph ph-calendar"></i> {{ $updatedAt }}</div>
                </div>
                <div class="text-right text-xs text-slate-500"> <!-- reserved for quick stats --> </div>
              </div>

              <p class="text-sm text-slate-600 mt-3">{{ $description }}</p>

              <div class="mt-3 flex items-center gap-2 flex-wrap">
                @if(!empty($tags) && is_array($tags))
                  @foreach($tags as $tag)
                    <span class="tag-chip">{{ $tag }}</span>
                  @endforeach
                @endif
              </div>
            </div>
          </div>

          <div class="mt-4 flex items-center justify-end gap-2">
            <button data-id="{{ $id }}" class="edit-topic-btn px-3 py-1 rounded-lg bg-yellow-50 text-yellow-700 text-sm hover:bg-yellow-100"><i class="ph ph-pencil"></i> <span class="hidden sm:inline">Sửa</span></button>
            <button data-id="{{ $id }}" class="delete-topic-btn px-3 py-1 rounded-lg bg-rose-50 text-rose-700 text-sm hover:bg-rose-100"><i class="ph ph-trash"></i> <span class="hidden sm:inline">Xóa</span></button>
          </div>
        </article>
      @endforeach
    </div>

    <!-- Add Topic Modal (static, simplified) -->
    <div id="addTopicModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
      <div class="absolute inset-0 bg-black/40" id="addTopicBackdrop"></div>
      <div id="addTopicContent" class="relative w-full max-w-2xl bg-gradient-to-br from-white via-indigo-50 to-white rounded-2xl shadow-2xl p-6 sm:p-8 transform scale-95 opacity-0 transition duration-200 max-h-[90vh] overflow-auto">
        <div class="flex items-start gap-4 mb-4">
          <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-indigo-600/10 grid place-items-center">
            <div class="w-11 h-11 rounded-lg bg-gradient-to-tr from-indigo-600 to-indigo-400 text-white grid place-items-center shadow-md">
              <i class="ph ph-notebook text-xl"></i>
            </div>
          </div>

            
          <div class="flex-1">
            <h3 class="text-lg sm:text-xl font-semibold text-slate-900">Thêm đề tài mới</h3>
            <p class="text-sm text-slate-500 mt-1">Chỉ cần tên đề tài và mô tả ngắn. Các thiết lập khác có thể chỉnh sau.</p>
          </div>
          <button id="closeAddTopic" class="ml-4 text-slate-500 hover:text-slate-700 text-2xl">✕</button>
        </div>

        <form id="addTopicForm" class="space-y-4 bg-white/50 p-3 rounded-lg">
          <div>
            <label class="text-xs text-slate-500">Tiêu đề *</label>
            <input name="title" required maxlength="180" class="mt-1 w-full px-3 py-3 rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-indigo-300" placeholder="Tiêu đề đề tài" />
          </div>

          <div>
            <label class="text-xs text-slate-500">Mô tả (tùy chọn)</label>
            <textarea name="desc" rows="4" class="mt-1 w-full px-3 py-3 rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-indigo-300" placeholder="Mô tả ngắn về mục tiêu / kết quả"></textarea>
          </div>

          <div class="text-right pt-2 flex items-center justify-end gap-3">
            <button type="button" id="cancelAddTopic" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Thêm đề tài</button>
          </div>
        </form>
      </div>
    </div>

      <!-- Edit Topic Modal (static, simplified: title + description) -->
      <div id="editTopicModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/40" id="editTopicBackdrop"></div>
        <div id="editTopicContent" class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl p-6 sm:p-8 transform scale-95 opacity-0 transition duration-200 max-h-[90vh] overflow-auto">
          <div class="flex items-start gap-4 mb-4">
            <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-indigo-600/10 grid place-items-center">
              <div class="w-11 h-11 rounded-lg bg-gradient-to-tr from-indigo-600 to-indigo-400 text-white grid place-items-center shadow-md">
                <i class="ph ph-pencil text-lg"></i>
              </div>
            </div>
            <div class="flex-1">
              <h3 class="text-lg sm:text-xl font-semibold text-slate-900">Chỉnh sửa đề tài</h3>
              <p class="text-sm text-slate-500 mt-1">Chỉ gồm tên đề tài và mô tả ngắn.</p>
            </div>
            <button id="closeEditTopic" class="ml-4 text-slate-500 hover:text-slate-700 text-2xl">✕</button>
          </div>

          <form id="editTopicForm" class="space-y-4 bg-white/50 p-3 rounded-lg">
            <div>
              <label class="text-xs text-slate-500">Tiêu đề *</label>
              <input name="title" required maxlength="180" class="mt-1 w-full px-3 py-3 rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-indigo-300" placeholder="Tiêu đề đề tài" />
            </div>

            <div>
              <label class="text-xs text-slate-500">Mô tả (tùy chọn)</label>
              <textarea name="desc" rows="4" class="mt-1 w-full px-3 py-3 rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-indigo-300" placeholder="Mô tả ngắn về mục tiêu / kết quả"></textarea>
            </div>

            <div class="text-right pt-2 flex items-center justify-end gap-3">
              <button type="button" id="cancelEditTopic" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
              <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Lưu thay đổi</button>
            </div>
          </form>
        </div>
      </div>

    </div>

    <script>
      // expose current supervisor id to JS; null when absent so server validation treats it as nullable
      const SUPERVISOR_ID = @json($supervisorId ?? null);
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

      //tonggle submenu
      const toggleBtn = document.getElementById('toggleThesisMenu');
      const thesisMenu = document.getElementById('thesisSubmenu');
      const thesisCaret = document.getElementById('thesisCaret');
      toggleBtn?.addEventListener('click', () => {
        const isHidden = thesisMenu?.classList.toggle('hidden');
        const expanded = !isHidden;
        toggleBtn?.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        thesisCaret?.classList.toggle('rotate-180', expanded);
      });

    // DOM-based topic handlers (server-rendered cards)
    const listEl = document.getElementById('topicsList');
    const searchEl = document.getElementById('searchBox');
    const statusEl = document.getElementById('statusFilter');

    function computeStats(){
      const cards = Array.from(document.querySelectorAll('#topicsList [data-topic-id]'));
      const total = cards.length;
      const open = cards.filter(c => (c.dataset.status||'').trim() === 'Mở').length;
      const slots = cards.reduce((s,c) => s + Number(c.dataset.slots||0), 0);
      const reg = cards.reduce((s,c) => s + Number(c.dataset.registered||0), 0);
      const stTotalEl = document.getElementById('stTotal');
      const stOpenEl = document.getElementById('stOpen');
      const stSlotsEl = document.getElementById('stSlots');
      const stRegEl = document.getElementById('stReg');
      if (stTotalEl) stTotalEl.textContent = total;
      if (stOpenEl) stOpenEl.textContent = open;
      if (stSlotsEl) stSlotsEl.textContent = slots;
      if (stRegEl) stRegEl.textContent = reg;
    }

    // Create article element from payload
    function createArticleElement(t){
      const wrap = document.createElement('article');
      wrap.className = 'border rounded-xl p-4 bg-white shadow-sm hover:shadow-md transition';
      wrap.setAttribute('data-topic-id', t.id);
      wrap.setAttribute('data-status', t.status || 'Mở');
      wrap.setAttribute('data-slots', t.slots || 0);
      wrap.setAttribute('data-registered', t.registered || 0);
      const tagsHtml = (t.tags||[]).length ? (t.tags||[]).map(tag=>`<span class="tag-chip">${tag}</span>`).join(' ') : '<span class="text-xs text-slate-400">Chưa có thẻ</span>';
      wrap.innerHTML = `
        <div class="flex items-start gap-4">
          <div class="flex-shrink-0">
            <div class="h-12 w-12 rounded-lg bg-indigo-50 text-indigo-600 grid place-items-center"><i class="ph ph-notebook text-xl"></i></div>
          </div>
          <div class="flex-1">
            <div class="flex items-start justify-between gap-3">
              <div>
                <h5 class="font-semibold text-slate-900 text-base">${escapeHtml(t.title)}</h5>
                <div class="text-xs text-slate-400 mt-1"><i class="ph ph-calendar"></i> ${t.updatedAt||''}</div>
              </div>
              <div class="text-right text-xs text-slate-500"></div>
            </div>

            <p class="text-sm text-slate-600 mt-3">${escapeHtml(t.description||'')}</p>

            <div class="mt-3 flex items-center gap-2 flex-wrap">
              ${tagsHtml}
            </div>
          </div>
        </div>

        <div class="mt-4 flex items-center justify-end gap-2">
          <button data-id="${t.id}" class="edit-topic-btn px-3 py-1 rounded-lg bg-yellow-50 text-yellow-700 text-sm hover:bg-yellow-100"><i class="ph ph-pencil"></i> <span class="hidden sm:inline">Sửa</span></button>
          <button data-id="${t.id}" class="delete-topic-btn px-3 py-1 rounded-lg bg-rose-50 text-rose-700 text-sm hover:bg-rose-100"><i class="ph ph-trash"></i> <span class="hidden sm:inline">Xóa</span></button>
        </div>
      `;
      return wrap;
    }

    // Delegated edit/delete handlers (operate on DOM)
    listEl.addEventListener('click', (e)=>{
      const editBtn = e.target.closest('.edit-topic-btn');
      if(editBtn){
        const id = editBtn.getAttribute('data-id');
        openEditModalForId(id);
        return;
      }
      const delBtn = e.target.closest('.delete-topic-btn');
      if(delBtn){
        const id = delBtn.getAttribute('data-id');
        if(!confirm('Bạn có chắc muốn xóa đề tài này?')) return;
        const el = document.querySelector(`#topicsList [data-topic-id="${id}"]`);
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        (async ()=>{
          try{
            const res = await fetch('/teacher/proposed-topics/' + encodeURIComponent(id), {
              method: 'DELETE',
              headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token || ''
              }
            });
            if(res.ok){
              // remove element from DOM
              el?.remove();
              computeStats();
            } else {
              // try to read response text/json for debug
              let text = '';
              try{ text = await res.text(); }catch(e){ text = String(e); }
              console.warn('Delete failed', res.status, text);
              alert('Không thể xóa đề tài. Server trả: ' + res.status + '\n' + (text||''));
            }
          }catch(err){
            console.error('Delete request failed', err);
            alert('Không thể xóa đề tài: ' + (err && err.message ? err.message : String(err)));
          }
        })();
        return;
      }
    });

    function openEditModalForId(id){
      const el = document.querySelector(`#topicsList [data-topic-id="${id}"]`);
      if(!el) return alert('Không tìm thấy đề tài');
      const editModal = document.getElementById('editTopicModal');
      const editContent = document.getElementById('editTopicContent');
      const editForm = document.getElementById('editTopicForm');
      const editBackdrop = document.getElementById('editTopicBackdrop');
      const editClose = document.getElementById('closeEditTopic');
      const editCancel = document.getElementById('cancelEditTopic');

      // populate values
      editForm.elements['title'].value = el.querySelector('h5')?.textContent?.trim() || '';
      editForm.elements['desc'].value = el.querySelector('p')?.textContent?.trim() || '';

      // store editing id
      editModal.dataset.editingId = id;

      // open modal
      editModal.classList.remove('hidden');
      setTimeout(()=>{
        editContent.classList.remove('scale-95','opacity-0');
        editContent.classList.add('scale-100','opacity-100');
        editForm.elements['title'].focus();
      },10);

      // initialize listeners once
      if(!editModal.dataset.init){
        editModal.dataset.init = '1';

        function closeEditModal(){
          editContent.classList.add('scale-95','opacity-0');
          setTimeout(()=>{
            editModal.classList.add('hidden');
            try{ editForm.reset(); }catch(e){}
            delete editModal.dataset.editingId;
          },180);
        }

        editClose?.addEventListener('click', closeEditModal);
        editCancel?.addEventListener('click', closeEditModal);
        editBackdrop?.addEventListener('click', closeEditModal);

        editForm.addEventListener('submit', async (e)=>{
          e.preventDefault();
          const id = editModal.dataset.editingId;
          const el = document.querySelector(`#topicsList [data-topic-id="${id}"]`);
          if(!el){ alert('Không tìm thấy đề tài'); closeEditModal(); return; }
          const fd = new FormData(editForm);
          const newTitle = String(fd.get('title')||'').trim();
          if(!newTitle) return alert('Tiêu đề không được để trống');

          const body = {
            title: newTitle,
            description: String(fd.get('desc')||'').trim(),
            supervisor_id: SUPERVISOR_ID
          };
          const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

          let updated = null;
            try{
            const res = await fetch('/teacher/proposed-topics/' + encodeURIComponent(id), {
              method: 'PATCH',
              headers: {
                'Content-Type':'application/json',
                'Accept':'application/json',
                'X-Requested-With':'XMLHttpRequest',
                'X-CSRF-TOKEN': token || ''
              },
              body: JSON.stringify(body)
            });
            if(res.ok){
              const j = await res.json();
              updated = j.topic || null;
            } else {
              console.warn('Server rejected update', res.status);
            }
          }catch(err){ console.warn('Update request failed, falling back to local DOM', err); }

          if(updated){
            el.querySelector('h5').textContent = updated.title || newTitle;
            el.querySelector('p').textContent = updated.description || body.description;
            const dateNode = el.querySelector('.text-xs.text-slate-400');
            if(dateNode) dateNode.textContent = updated.updated_at ? new Date(updated.updated_at).toLocaleDateString('vi-VN') : new Date().toLocaleDateString('vi-VN');
          } else {
            // fallback local update
            el.querySelector('h5').textContent = newTitle;
            el.querySelector('p').textContent = body.description;
            const dateNode = el.querySelector('.text-xs.text-slate-400');
            if(dateNode) dateNode.textContent = new Date().toLocaleDateString('vi-VN');
          }

          closeEditModal();
          computeStats();
        });
      }
    }

    // escape helper for inserting into HTML
    function escapeHtml(s){ return String(s||'').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;'); }

    // Add topic: open the static #addTopicModal, wire preview/submit/close
    (function(){
      const addBtn = document.getElementById('btnAdd');
      const addModal = document.getElementById('addTopicModal');
      const addBackdrop = document.getElementById('addTopicBackdrop');
      const addContent = document.getElementById('addTopicContent');
      const addForm = document.getElementById('addTopicForm');
      const addTagsInput = document.getElementById('addTagsInput');
      const addTagsPreview = document.getElementById('addTagsPreview');
      const closeBtn = document.getElementById('closeAddTopic');
      const cancelBtn = document.getElementById('cancelAddTopic');

      if(!addBtn || !addModal) return; // nothing to do

      function openAddModal(){
        addModal.classList.remove('hidden');
        // animate in
        setTimeout(()=>{
          addContent.classList.remove('scale-95','opacity-0');
          addContent.classList.add('scale-100','opacity-100');
          const t = addForm.querySelector('[name="title"]'); if(t) t.focus();
        },10);
      }

      function closeAddModal(){
        // animate out
        addContent.classList.add('scale-95','opacity-0');
        setTimeout(()=>{
          addModal.classList.add('hidden');
          try{ addForm.reset(); }catch(e){}
          if(typeof renderTagPreview === 'function') try{ renderTagPreview(addTagsPreview, ''); }catch(e){}
        }, 180);
      }

      // ensure we only attach listeners once
      if(!addModal.dataset.init){
        addModal.dataset.init = '1';

        addBtn.addEventListener('click', openAddModal);
        closeBtn?.addEventListener('click', closeAddModal);
        cancelBtn?.addEventListener('click', closeAddModal);
        addBackdrop?.addEventListener('click', closeAddModal);

        // tags preview (guarded: helper may not exist in simplified modal)
        if(addTagsInput && typeof renderTagPreview === 'function'){
          addTagsInput.addEventListener('input', ()=> renderTagPreview(addTagsPreview, addTagsInput.value || ''));
        }

        // submit (POST to server route, fallback to local DOM on error)
        addForm?.addEventListener('submit', async (e)=>{
          e.preventDefault();
          try{
            const fd = new FormData(addForm);
            const title = String(fd.get('title')||'').trim();
            if(!title) return alert('Tiêu đề không được để trống');
            const body = {
              title,
              description: String(fd.get('desc')||'').trim(),
              supervisor_id: SUPERVISOR_ID
            };

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            let created = null;
              try{
              const res = await fetch('/teacher/proposed-topics', {
                method: 'POST',
                headers: {
                  'Content-Type':'application/json',
                  'Accept':'application/json',
                  'X-Requested-With':'XMLHttpRequest',
                  'X-CSRF-TOKEN': token || ''
                },
                body: JSON.stringify(body)
              });
              if(res.ok){
                const j = await res.json();
                created = j.topic || null;
              } else {
                console.warn('Server rejected create', res.status);
              }
            } catch(fetchErr){
              console.warn('Create request failed, falling back to client DOM only', fetchErr);
            }

            if(created){
              const payload = {
                id: created.id || ('T' + Math.floor(Math.random()*900+100)),
                title: created.title || title,
                description: created.description || body.description,
                tags: created.tags || [],
                slots: created.slots || 1,
                status: created.status || 'Mở',
                registered: created.registered || 0,
                updatedAt: created.updated_at ? new Date(created.updated_at).toLocaleDateString('vi-VN') : new Date().toLocaleDateString('vi-VN')
              };
              const node = createArticleElement(payload);
              listEl.prepend(node);
            } else {
              // fallback: create client-only node
              const payload = {
                id: 'T' + Math.floor(Math.random()*900+100),
                title,
                description: body.description,
                tags: [],
                slots: 1,
                status: 'Mở',
                registered: 0,
                updatedAt: new Date().toLocaleDateString('vi-VN')
              };
              const node = createArticleElement(payload);
              listEl.prepend(node);
            }

            computeStats();
            closeAddModal();
            // reload page so server-state (pagination, counts, etc.) is authoritative
            try{ window.location.reload(); }catch(e){ /* ignore reload failure */ }
          }catch(err){
            console.error('Add topic failed:', err);
            // show more detailed message to help debugging while keeping user-friendly text
            try{
              const msg = err && err.message ? err.message : String(err);
              alert('Không thể thêm đề tài: ' + msg);
            }catch(e){
              alert('Không thể thêm đề tài');
            }
          }
        });

        // initialize preview empty (only if helper exists)
        if(typeof renderTagPreview === 'function'){
          try{ renderTagPreview(addTagsPreview, ''); }catch(e){}
        }
      }
    })();

    // Import from Excel: create DOM nodes per row
    document.getElementById('btnImport').addEventListener('click', ()=>{
      const html = `
        <div class="space-y-3">
          <div class="text-sm text-slate-600">Chọn file Excel (.xlsx) hoặc CSV với các cột: <strong>Title, Description, Tags, Slots, Status</strong>.</div>
          <input id="fileInput" type="file" accept=".xlsx,.csv" class="block w-full text-sm" />
          <div class="text-xs text-slate-500">Gợi ý: Tags phân cách bằng dấu phẩy hoặc chấm phẩy. Status: Mở/Đóng.</div>
          <div class="flex gap-2 pt-1">
            <button id="importBtn" class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm"><i class="ph ph-upload"></i> Import</button>
            <button id="cancelImp" class="px-3 py-1.5 border border-slate-200 rounded text-sm">Hủy</button>
          </div>
        </div>`;
      const m = createModal({ title: 'Import danh sách đề tài', content: html });
      document.body.appendChild(m.el || m);
      const root = m.el || m; // support earlier return shapes
      root.querySelector('#cancelImp').addEventListener('click',()=> root.remove());
      root.querySelector('#importBtn').addEventListener('click',()=>{
        const file = root.querySelector('#fileInput').files[0];
        if(!file){ alert('Vui lòng chọn file'); return; }
        const reader = new FileReader();
        reader.onload = (e)=>{
          try{
            let rows = [];
            if(file.name.toLowerCase().endsWith('.csv')){
              const text = e.target.result;
              const lines = String(text).split(/\r?\n/).filter(Boolean);
              const header = lines.shift().split(',').map(h=>h.trim());
              rows = lines.map(line=>{
                const cols = line.split(',');
                const obj = {};
                header.forEach((h,i)=>obj[h] = cols[i]);
                return obj;
              });
            } else {
              const data = new Uint8Array(e.target.result);
              const wb = XLSX.read(data, {type:'array'});
              const ws = wb.Sheets[wb.SheetNames[0]];
              rows = XLSX.utils.sheet_to_json(ws, {defval:'', raw:false});
            }
            const added = [];
            rows.forEach(r=>{
              const title = (r.Title||r.title||'').trim();
              if(!title) return;
              const desc = (r.Description||r.description||'').trim();
              const tags = String(r.Tags||r.tags||'').split(/[;,]/).map(x=>x.trim()).filter(Boolean);
              const slots = Math.max(1, Number(r.Slots||r.slots||1));
              const status = ((r.Status||r.status||'Mở').trim()==='Đóng')?'Đóng':'Mở';
              const payload = { id:'T'+Math.floor(Math.random()*900+100), title, description:desc, tags, slots, status, registered:0, updatedAt: new Date().toLocaleDateString('vi-VN') };
              const node = createArticleElement(payload);
              listEl.prepend(node);
              added.push(title);
            });
            root.remove();
            computeStats();
            if(added.length) alert(`Đã import ${added.length} đề tài.`);
          }catch(err){ console.error(err); alert('Không thể đọc file. Vui lòng kiểm tra định dạng.'); }
        };
        if(file.name.toLowerCase().endsWith('.csv')) reader.readAsText(file);
        else reader.readAsArrayBuffer(file);
      });
    });

    // Download template (CSV)
    document.getElementById('btnTemplate').addEventListener('click', ()=>{
      const header = 'Title,Description,Tags,Slots,Status\n';
      const example = 'Hệ thống quản lý thư viện,Mô tả ví dụ,Web;React;Node.js,2,Mở\n';
      const blob = new Blob([header + example], {type:'text/csv;charset=utf-8;'});
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url; a.download = 'topics_template.csv'; a.click();
      URL.revokeObjectURL(url);
    });

    // Filters: search and status filter operate on DOM elements
    document.getElementById('resetBtn').addEventListener('click',()=>{ if(searchEl) searchEl.value=''; if(statusEl) statusEl.value=''; applyFilters(); });
    searchEl?.addEventListener('input', applyFilters);
    statusEl?.addEventListener('change', applyFilters);

    // Press Enter in search box -> perform server-side search (reload with ?q=...)
    function performServerSearch(q){
      q = String(q||'').trim();
      const base = '/teacher/proposed-topics';
      if(!q) return window.location.href = base;
      window.location.href = base + '?q=' + encodeURIComponent(q);
    }

    searchEl?.addEventListener('keydown', (ev)=>{
      if(ev.key === 'Enter'){
        ev.preventDefault();
        performServerSearch(searchEl.value || '');
      }
    });

    function applyFilters(){
      const q = (searchEl?.value||'').toLowerCase().trim();
      const st = (statusEl?.value||'').trim();
      document.querySelectorAll('#topicsList [data-topic-id]').forEach(card=>{
        const text = (card.innerText||'').toLowerCase();
        const matchesQ = !q || text.includes(q);
        const matchesStatus = !st || (card.dataset.status||'') === st;
        card.style.display = (matchesQ && matchesStatus) ? '' : 'none';
      });
      computeStats();
    }

    // Initial compute
    computeStats();

    // Sidebar/header interactions (outside of templates)
    (function(){
      const html = document.documentElement;
      const sidebar = document.getElementById('sidebar');
      const headerEl = document.querySelector('header');
      const wrapper = headerEl ? headerEl.parentElement : null; // the container with md:pl-*

      function setCollapsed(c){
        if(c){
          html.classList.add('sidebar-collapsed');
          // adjust wrapper padding if available
          if(wrapper){
            wrapper.classList.remove('md:pl-[260px]');
            wrapper.classList.add('md:pl-[72px]');
          }
        } else {
          html.classList.remove('sidebar-collapsed');
          if(wrapper){
            wrapper.classList.remove('md:pl-[72px]');
            wrapper.classList.add('md:pl-[260px]');
          }
        }
      }

      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{
        const c = !html.classList.contains('sidebar-collapsed');
        setCollapsed(c);
        localStorage.setItem('lecturer_sidebar', c ? '1' : '0');
      });
      document.getElementById('openSidebar')?.addEventListener('click',()=> sidebar?.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      // Ensure sidebar is hidden on small screens and visible on md+ by default
      sidebar?.classList.add('md:translate-x-0');
      sidebar?.classList.add('-translate-x-full');
      sidebar?.classList.add('md:static');

      const profileBtn = document.getElementById('profileBtn');
      const profileMenu = document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu?.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    })();
  </script>
</body>
</html>
