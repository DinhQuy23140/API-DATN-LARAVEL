<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đợt đồ án - Trợ lý khoa</title>
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
      .submenu {
        display: none;
      }
      .submenu.hidden {
        display: none;
      }
      .submenu:not(.hidden) {
        display: block;
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
      $avatarUrl = $user->avatar_url
        ?? $user->profile_photo_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    @endphp
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
            <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button bg-slate-100 font-semibold" aria-expanded="true">
              <span class="flex items-center gap-3">
                <i class="ph ph-graduation-cap"></i>
                <span class="sidebar-label">Học phần tốt nghiệp</span>
              </span>
              <i class="ph ph-caret-down"></i>
            </div>
            <div class="submenu hidden pl-6">
              <a href="internship.html" class="block px-3 py-2 hover:bg-slate-100"><i class="ph ph-briefcase"></i> Thực tập tốt nghiệp</a>
              <a href="{{ route('web.assistant.rounds') }}" class="block px-3 py-2 hover:bg-slate-100"><i class="ph ph-calendar"></i> Đồ án tốt nghiệp</a>
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
              <h1 class="text-lg md:text-xl font-semibold">Danh sách đợt đồ án</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp</nav>
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
              <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
                @csrf
              </form>
            </div>
          </div>
        </header>

        <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 space-y-5">
          <div class="max-w-6xl mx-auto space-y-5">
          <!-- Actions -->
          <div class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="relative w-full sm:max-w-sm">
              <input id="searchInput" class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tên đợt" />
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <button onclick="openCreateRoundModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><i class="ph ph-plus"></i>Tạo đợt mới</button>
          </div>

          <!-- Table -->
          <div class="bg-white border border-slate-200 rounded-xl">
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500">
                    <th data-sort-key="name" data-sort-type="string" class="py-3 px-4 border-b cursor-pointer select-none">Tên đợt <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="time" data-sort-type="date-range" class="py-3 px-4 border-b cursor-pointer select-none text-center">Thời gian <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th data-sort-key="committees" data-sort-type="number" class="py-3 px-4 border-b cursor-pointer select-none text-center">Số hội đồng <i class="ph ph-caret-up-down ml-1 text-slate-400"></i></th>
                    <th class="py-3 px-4 border-b text-right">Hành động</th>
                  </tr>
                </thead>
                <tbody id="tableBody">
                  @foreach ($terms as $term)
                    @php
                      $start_year = $term->start_date ? substr($term->start_date, 0, 4) : '';
                      $end_year = $term->end_date ? substr($term->end_date, 0, 4) : '';
                      $termName = "Đợt " . $term->stage . " " . $term->academy_year->year_name;
                      // build a simple array for the row data-term to avoid inline closures in attributes
                      $timelines = [];
                      if (!empty($term->stageTimelines)) {
                        foreach ($term->stageTimelines as $st) {
                          $timelines[] = [
                            'number' => $st->number_of_rounds ?? null,
                            'start' => $st->start_date ?? null,
                            'end' => $st->end_date ?? null,
                          ];
                        }
                      }
                      $termData = [
                        'id' => $term->id,
                        'academy_year_id' => $term->academy_year_id,
                        'stage' => $term->stage,
                        'start_date' => $term->start_date,
                        'end_date' => $term->end_date,
                        'description' => $term->description,
                        'stage_timelines' => $timelines,
                      ];
                    @endphp
                  <tr class="hover:bg-slate-50" data-term='@json($termData)'>
                    <td class="py-3 px-4">
                      <a href="{{ route('web.assistant.round_detail', ['round_id' => $term->id]) }}" class="flex items-center gap-2 text-blue-600 hover:underline">
                        <i class="ph ph-calendar-check text-slate-600"></i>
                        <span class="font-medium">{{$termName}}</span>
                      </a>
                    </td>
                    <td class="py-3 px-4 text-center">
                      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-blue-50 text-blue-700 text-sm">
                        <i class="ph ph-clock text-blue-600"></i>
                        <span>{{$term->start_date}} - {{$term->end_date}}</span>
                      </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                      <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">
                        <i class="ph ph-users-three"></i>
                        {{ $term->councils->count() }}
                      </span>
                    </td>
                    <td class="py-3 px-4 text-right space-x-2">
                      <a class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" href="{{ route('web.assistant.round_detail', ['round_id' => $term->id]) }}"><i class="ph ph-eye"></i></a>
                      <button type="button" class="btn-edit-round px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" title="Sửa đợt"><i class="ph ph-pencil"></i></button>
                      <button type="button" data-round-id="{{ $term->id }}" class="btn-delete-round px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600" title="Xóa đợt"><i class="ph ph-trash"></i></button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        </main>
      </div>
    </div>

    <!-- Modal (static) -->
    <div id="modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center z-50">
      <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">
        <div class="p-4 border-b flex items-center justify-between">
          <h3 id="modalTitle" class="font-semibold">Tạo đợt mới</h3>
          <button class="p-2 hover:bg-slate-100 rounded-lg" onclick="closeModal()" data-close aria-label="Đóng"><i class="ph ph-x"></i></button>
        </div>
  <form id="modalCreateForm" method="POST" action="{{ route('web.assistant.rounds.store') }}" class="p-4 space-y-5">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <!-- Năm học + Đợt (gọn) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="relative">
              <label class="text-sm font-medium">Năm học</label>
              <i class="ph ph-calendar text-slate-400 absolute left-3 bottom-3.5 pointer-events-none"></i>
              <select id="modalYearSelect" name="academy_year_id" required class="mt-1 w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                <!-- JS sẽ render options hoặc render Blade nếu có -->
              </select>
            </div>
            <div class="relative">
              <label class="text-sm font-medium">Đợt</label>
              <i class="ph ph-flag text-slate-400 absolute left-3 bottom-3.5 pointer-events-none"></i>
              <select id="modalStageSelect" name="stage" required class="mt-1 w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                <option value="1">Đợt 1</option>
                <option value="2">Đợt 2</option>
                <option value="Hè">Đợt Hè</option>
              </select>
            </div>
          </div>

          <!-- Thời gian đợt -->
          <div class="grid sm:grid-cols-2 gap-4">
            <div> <!-- Ngày bắt đầu (static modal) -->
              <label class="text-sm font-medium">Ngày bắt đầu</label>
              <div class="relative mt-1">
                <i class="ph ph-calendar-check text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input type="date" name="start_date" required class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm" />
              </div>
            </div>
            <div> <!-- Ngày kết thúc (static modal) -->
              <label class="text-sm font-medium">Ngày kết thúc</label>
              <div class="relative mt-1">
                <i class="ph ph-calendar-x text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input type="date" name="end_date" required class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm" />
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeModal()" data-close class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
            <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Lưu</button>
          </div>
        </form>
      </div>
    </div>

      <!-- Edit Modal (separate from create modal) -->
      <div id="modalEdit" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden flex items-end sm:items-center justify-center z-50">
        <div data-modal-container class="bg-white w-full sm:max-w-4xl max-h-[92vh] overflow-auto rounded-t-2xl sm:rounded-2xl shadow-xl relative z-10">
          <div class="p-4 border-b flex items-center justify-between sticky top-0 bg-white z-10">
            <h3 class="font-semibold">Sửa đợt đồ án</h3>
            <button class="p-2 hover:bg-slate-100 rounded-lg" onclick="closeEditModal()" data-close aria-label="Đóng"><i class="ph ph-x"></i></button>
          </div>
          <form id="modalEditForm" method="POST" action="#" class="p-4 space-y-5">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PUT">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="relative">
                <label class="text-sm font-medium">Năm học</label>
                <i class="ph ph-calendar text-slate-400 absolute left-3 bottom-3.5 pointer-events-none"></i>
                <select name="academy_year_id" required class="mt-1 w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm">
                  @foreach ($years as $year)
                    <option value="{{ $year->id }}">{{ $year->year_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="relative">
                <label class="text-sm font-medium">Đợt</label>
                <i class="ph ph-flag text-slate-400 absolute left-3 bottom-3.5 pointer-events-none"></i>
                <select name="stage" required class="mt-1 w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm">
                  <option value="1">Đợt 1</option>
                  <option value="2">Đợt 2</option>
                  <option value="Hè">Đợt Hè</option>
                </select>
              </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
              <div>
                <label class="text-sm font-medium">Ngày bắt đầu đợt</label>
                <div class="relative mt-1">
                  <i class="ph ph-calendar-check text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                  <input type="date" name="start_date" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm" />
                </div>
              </div>
              <div>
                <label class="text-sm font-medium">Ngày kết thúc đợt</label>
                <div class="relative mt-1">
                  <i class="ph ph-calendar-x text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                  <input type="date" name="end_date" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm" />
                </div>
              </div>
            </div>

            <div>
              <label class="text-sm font-medium">Mô tả</label>
              <input name="description" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 text-sm" placeholder="VD: Đợt 1 2025-2026" />
            </div>

            <!-- Timeline 1–8 (same inputs as create form) -->
            <div>
              <div class="flex items-center justify-between mb-2">
                <h4 class="font-semibold">Mốc timeline (1–8)</h4>
                <span class="text-xs text-slate-500">Nhập thời gian cho từng mốc</span>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-lg border border-slate-200 p-3 bg-slate-50/50">
                  <div class="font-medium mb-2">Giai đoạn 1</div>
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Bắt đầu</label>
                      <div class="relative">
                        <i class="ph ph-clock text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_1_start" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Kết thúc</label>
                      <div class="relative">
                        <i class="ph ph-clock-afternoon text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_1_end" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="rounded-lg border border-slate-200 p-3 bg-slate-50/50">
                  <div class="font-medium mb-2">Giai đoạn 2</div>
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Bắt đầu</label>
                      <div class="relative">
                        <i class="ph ph-clock text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_2_start" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Kết thúc</label>
                      <div class="relative">
                        <i class="ph ph-clock-afternoon text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_2_end" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="rounded-lg border border-slate-200 p-3 bg-slate-50/50">
                  <div class="font-medium mb-2">Giai đoạn 3</div>
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Bắt đầu</label>
                      <div class="relative">
                        <i class="ph ph-clock text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_3_start" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Kết thúc</label>
                      <div class="relative">
                        <i class="ph ph-clock-afternoon text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_3_end" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="rounded-lg border border-slate-200 p-3 bg-slate-50/50">
                  <div class="font-medium mb-2">Giai đoạn 4</div>
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Bắt đầu</label>
                      <div class="relative">
                        <i class="ph ph-clock text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_4_start" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Kết thúc</label>
                      <div class="relative">
                        <i class="ph ph-clock-afternoon text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_4_end" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="rounded-lg border border-slate-200 p-3 bg-slate-50/50">
                  <div class="font-medium mb-2">Giai đoạn 5</div>
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Bắt đầu</label>
                      <div class="relative">
                        <i class="ph ph-clock text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_5_start" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Kết thúc</label>
                      <div class="relative">
                        <i class="ph ph-clock-afternoon text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_5_end" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="rounded-lg border border-slate-200 p-3 bg-slate-50/50">
                  <div class="font-medium mb-2">Giai đoạn 6</div>
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Bắt đầu</label>
                      <div class="relative">
                        <i class="ph ph-clock text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_6_start" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Kết thúc</label>
                      <div class="relative">
                        <i class="ph ph-clock-afternoon text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_6_end" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="rounded-lg border border-slate-200 p-3 bg-slate-50/50">
                  <div class="font-medium mb-2">Giai đoạn 7</div>
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Bắt đầu</label>
                      <div class="relative">
                        <i class="ph ph-clock text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_7_start" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Kết thúc</label>
                      <div class="relative">
                        <i class="ph ph-clock-afternoon text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_7_end" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="rounded-lg border border-slate-200 p-3 bg-slate-50/50">
                  <div class="font-medium mb-2">Giai đoạn 8</div>
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Bắt đầu</label>
                      <div class="relative">
                        <i class="ph ph-clock text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_8_start" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                    <div>
                      <label class="block text-xs text-slate-600 mb-1">Kết thúc</label>
                      <div class="relative">
                        <i class="ph ph-clock-afternoon text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="date" name="stage_8_end" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
              <button type="button" onclick="closeEditModal()" data-close class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
              <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Lưu</button>
            </div>
          </form>
        </div>
      </div>

      <script>
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const mainArea = document.querySelector('.flex-1');
        if(c){
          html.classList.add('sidebar-collapsed');
          mainArea.classList.add('md:pl-[72px]');
          mainArea.classList.remove('md:pl-[260px]');
        } else {
          html.classList.remove('sidebar-collapsed');
          mainArea.classList.remove('md:pl-[72px]');
          mainArea.classList.add('md:pl-[260px]');
        }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed');setCollapsed(c);localStorage.setItem('assistant_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('assistant_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      // simple filter
      document.getElementById('searchInput').addEventListener('input', (e)=>{
        const q=e.target.value.toLowerCase();
        document.querySelectorAll('#tableBody tr').forEach(tr=> tr.style.display = tr.innerText.toLowerCase().includes(q)?'':'none');
      });

      // sorting
      const sortState = { key:null, dir:1 };
      function parseDateVN(d){ // dd/mm/yyyy
        const [dd,mm,yyyy] = d.split('/').map(Number);
        return new Date(yyyy, mm-1, dd).getTime();
      }
      function getSortValue(tr, key){
        const tds = tr.querySelectorAll('td');
        if(key==='name') return (tds[0]?.innerText||'').trim().toLowerCase();
        if(key==='time'){
          const txt=(tds[1]?.innerText||'').trim();
          const start = txt.split('-')[0]?.trim();
          return start? parseDateVN(start) : 0;
        }
        if(key==='committees') return Number((tds[2]?.innerText||'0').replace(/\D+/g,''));
        return '';
      }
      function applySort(th){
        const key = th.dataset.sortKey;
        if(!key) return;
        sortState.dir = sortState.key===key ? -sortState.dir : 1;
        sortState.key = key;
        const rows = Array.from(document.querySelectorAll('#tableBody tr')).filter(r=>r.style.display!=='none');
        rows.sort((a,b)=>{
          const va=getSortValue(a,key), vb=getSortValue(b,key);
          if(typeof va==='number' && typeof vb==='number') return (va-vb)*sortState.dir;
          return (va>vb?1:va<vb?-1:0)*sortState.dir;
        });
        const tbody=document.getElementById('tableBody');
        rows.forEach(r=>tbody.appendChild(r));
        // update indicators
        document.querySelectorAll('thead th[data-sort-key] i').forEach(i=>{i.className='ph ph-caret-up-down ml-1 text-slate-400';});
        const icon = th.querySelector('i');
        icon.className = sortState.dir===1 ? 'ph ph-caret-up ml-1 text-slate-600' : 'ph ph-caret-down ml-1 text-slate-600';
      }
      document.querySelectorAll('thead th[data-sort-key]').forEach(th=> th.addEventListener('click',()=>applySort(th)));

      // profile dropdown
      const profileBtn=document.getElementById('profileBtn');
      const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

      // auto active nav highlight
      (function(){
        const current = location.pathname.split('/').pop();
        document.querySelectorAll('aside nav a').forEach(a=>{
          const href=a.getAttribute('href')||'';
          const active=href.endsWith(current);
          a.classList.toggle('bg-slate-100', active);
          a.classList.toggle('font-semibold', active);
          if(active) a.classList.add('text-slate-900');
        });
      })();

      document.addEventListener('DOMContentLoaded', () => {
        const graduationItem = document.querySelector('.graduation-item');
        const toggleButton = graduationItem?.querySelector('.toggle-button');
        const submenu = graduationItem?.querySelector('.submenu');
        const caretIcon = toggleButton?.querySelector('.ph.ph-caret-down');

        if (toggleButton && submenu) {
          // Mở sẵn khi vào trang
          submenu.classList.remove('hidden');
          toggleButton.setAttribute('aria-expanded', 'true');
          caretIcon?.classList.add('transition-transform','rotate-180');

          toggleButton.addEventListener('click', (e) => {
            e.preventDefault();
            submenu.classList.toggle('hidden');
            const expanded = !submenu.classList.contains('hidden');
            toggleButton.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            caretIcon?.classList.toggle('rotate-180', expanded);
          });
        }
      });

      // ===== Dynamic Create-Round Modal with 8 fixed timeline stages =====
      const AU_STAGES=[
        {id:1, name:'Tiếp nhận yêu cầu'},
        {id:2, name:'Đề cương'},
        {id:3, name:'Nhật ký tuần'},
        {id:4, name:'Báo cáo'},
        {id:5, name:'Phân hội đồng'},
        {id:6, name:'Phản biện'},
        {id:7, name:'Công bố & thứ tự'},
        {id:8, name:'Bảo vệ & kết quả'}
      ];
      function au_registerModal(wrapper){
        const panel=wrapper.querySelector('[data-modal-container]');
        function close(){ wrapper.remove(); document.removeEventListener('keydown', esc); }
        function esc(e){ if(e.key==='Escape') close(); }
        // Sửa: dùng closest('[data-close]') để bắt cả click vào icon trong button
        wrapper.addEventListener('click',e=>{
          if (e.target.hasAttribute('data-overlay') || e.target.closest('[data-close]')) {
            close();
          }
        });
        panel.addEventListener('click',e=> e.stopPropagation());
        document.addEventListener('keydown', esc);
        const first=panel.querySelector('input,select,textarea,button'); first && first.focus();
      }
      // Tạo options năm học dạng 2025-2026, 2024-2025, ...
      function buildYearOptions(count = 6) {
        const now = new Date().getFullYear();
        const items = [];
        for (let i = 0; i < count; i++) {
          const start = now - i;
          const end = start + 1;
          const y = `${start}-${end}`;
          items.push(`<option value="${y}">${y}</option>`);
        }
        return items.join('');
      }

      // Khởi tạo options cho select Năm học trong modal tĩnh
      const modalYear = document.getElementById('modalYearSelect');
      if (modalYear) modalYear.innerHTML = buildYearOptions(6);

      // Thay thế hàm modal cũ
      function openCreateRoundModal(){
        const wrap=document.createElement('div');
        wrap.className='fixed inset-0 z-50 flex items-end sm:items-center justify-center';
        wrap.innerHTML=`
          <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" data-overlay></div>
          <div class="bg-white w-full sm:max-w-4xl max-h-[92vh] overflow-auto rounded-t-2xl sm:rounded-2xl shadow-xl relative z-10" data-modal-container>
            <div class="p-5 border-b flex items-center justify-between sticky top-0 bg-white">
              <h3 class="font-semibold text-base">Tạo đợt đồ án tốt nghiệp</h3>
              <button data-close class="p-2 hover:bg-slate-100 rounded-lg" aria-label="Đóng"><i class="ph ph-x"></i></button>
            </div>

            <form id="createRoundForm" method="POST" action="{{ route('web.assistant.rounds.store') }}" class="p-5 space-y-6">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <!-- Năm học + Đợt -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative">
                  <label class="text-sm font-medium">Năm học</label>
                  <i class="ph ph-calendar text-slate-400 absolute left-3 bottom-3.5 pointer-events-none"></i>
                  <select name="academy_year_id" required class="mt-1 w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm">
                    @foreach ($years as $year)
                      <option value="{{ $year->id }}">{{ $year->year_name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="relative">
                  <label class="text-sm font-medium">Đợt</label>
                  <i class="ph ph-flag text-slate-400 absolute left-3 bottom-3.5 pointer-events-none"></i>
                  <select name="stage" required class="mt-1 w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm">
                    <option value="1">Đợt 1</option>
                    <option value="2">Đợt 2</option>
                    <option value="Hè">Đợt Hè</option>
                  </select>
                </div>
              </div>

              <!-- Ngày bắt đầu/kết thúc -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-medium">Ngày bắt đầu đợt</label>
                  <div class="relative mt-1">
                    <i class="ph ph-calendar-check text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    <input type="date" name="start_date" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm" />
                  </div>
                </div>
                <div>
                  <label class="text-sm font-medium">Ngày kết thúc đợt</label>
                  <div class="relative mt-1">
                    <i class="ph ph-calendar-x text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    <input type="date" name="end_date" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm" />
                  </div>
                </div>
              </div>

              <!-- Mô tả -->
              <div>
                <label class="text-sm font-medium">Mô tả</label>
                <input name="description" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 text-sm" placeholder="VD: Đợt 1 2025-2026" />
              </div>

              <!-- Timeline 1–8 -->
              <div>
                <div class="flex items-center justify-between mb-2">
                  <h4 class="font-semibold">Mốc timeline (1–8)</h4>
                  <span class="text-xs text-slate-500">Nhập thời gian cho từng mốc</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  ${[1,2,3,4,5,6,7,8].map(i=>`
                    <div class="rounded-lg border border-slate-200 p-3 bg-slate-50/50">
                      <div class="font-medium mb-2">Giai đoạn ${i}</div>
                      <div class="grid grid-cols-2 gap-3">
                        <div>
                          <label class="block text-xs text-slate-600 mb-1">Bắt đầu</label>
                          <div class="relative">
                            <i class="ph ph-clock text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input type="date" name="stage_${i}_start" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                          </div>
                        </div>
                        <div>
                          <label class="block text-xs text-slate-600 mb-1">Kết thúc</label>
                          <div class="relative">
                            <i class="ph ph-clock-afternoon text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input type="date" name="stage_${i}_end" class="w-full pl-8 pr-2 py-1.5 border border-slate-200 rounded text-sm">
                          </div>
                        </div>
                      </div>
                    </div>`).join('')}
                </div>
              </div>

              <div class="flex items-center justify-end gap-2 pt-2 border-t">
                <button type="button" data-close class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Tạo đợt</button>
              </div>
            </form>
          </div>`;
        document.body.appendChild(wrap);
        au_registerModal(wrap);

        // Attach validation to the dynamically created form before it can be submitted
        const dynForm = wrap.querySelector('#createRoundForm');
        if(dynForm){
          dynForm.addEventListener('submit', function(e){
            // prevent default always and submit programmatically only when valid
            if (this.dataset.__submitting === '1') {
              // allow actual submission to proceed (flag set)
              return true;
            }
            e.preventDefault();
            if(!validateRoundForm(this)){
              return false;
            }
            // mark to avoid re-validation loop and submit
            this.dataset.__submitting = '1';
            this.submit();
          });
        }

        // LƯU Ý: Không chặn submit nữa (để POST về server) — validation above will block if invalid
      }

      // Close/Open static modal (#modal)
      const modalEl = document.getElementById('modal');
      const modalPanel = modalEl?.querySelector('div.bg-white');

      function openModal() {
        modalEl?.classList.remove('hidden');
      }

      function closeModal() {
        modalEl?.classList.add('hidden');
      }

      // Click outside to close
      modalEl?.addEventListener('click', (e) => {
        if (e.target === modalEl) closeModal();
      });
      // Prevent bubbling when clicking inside panel
      modalPanel?.addEventListener('click', (e) => e.stopPropagation());

      // ESC to close
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modalEl && !modalEl.classList.contains('hidden')) {
          closeModal();
        }
      });

      // Expose for buttons already using onclick
      window.openModal = openModal;
      window.closeModal = closeModal;

      // --- Validation helper used by both static and dynamic modals ---
      function clearValidationErrors(form){
        form.querySelectorAll('.field-error-message').forEach(n=>n.remove());
        form.querySelectorAll('.ring-2\.ring-rose-500, .border-rose-500').forEach(el=>{
          el.classList.remove('ring-2','ring-rose-500','border-rose-500');
        });
      }

      function getFieldLabel(form, el){
        if(!el) return '';
        if(el.id){
          const lbl = form.querySelector(`label[for="${el.id}"]`);
          if(lbl) return lbl.innerText.trim();
        }
        let p = el.parentElement;
        for(let i=0;i<4 && p;i++){
          const lbl = p.querySelector('label');
          if(lbl) return lbl.innerText.trim();
          p = p.parentElement;
        }
        return el.name || el.id || 'Trường';
      }

      function showFieldError(form, el, message){
        if(!el) return;
        el.classList.add('ring-2','ring-rose-500','border-rose-500');
        // insert message after the nearest container (prefer parent)
        const container = el.parentElement || el;
        // avoid duplicate
        if(container.querySelector('.field-error-message')) return;
        const p = document.createElement('p');
        p.className = 'field-error-message mt-1 text-rose-600 text-sm';
        p.textContent = message;
        container.appendChild(p);
      }

      function focusAndScroll(el){
        if(!el) return;
        try{ el.scrollIntoView({behavior:'smooth', block:'center'}); }catch(e){}
        try{ el.focus({preventScroll:true}); }catch(e){ el.focus(); }
      }

      function validateRoundForm(form){
        clearValidationErrors(form);
        const errors = [];

        const yearEl = form.querySelector('[name="academy_year_id"]');
        const stageEl = form.querySelector('[name="stage"]');
        const startEl = form.querySelector('[name="start_date"]');
        const endEl = form.querySelector('[name="end_date"]');
        const descEl = form.querySelector('[name="description"]');

        if(!yearEl || !yearEl.value){
          const label = getFieldLabel(form, yearEl) || 'Năm học';
          showFieldError(form, yearEl || form, `Thiếu: ${label}`);
          errors.push(yearEl || form);
        }
        if(!stageEl || !stageEl.value){
          const label = getFieldLabel(form, stageEl) || 'Đợt';
          showFieldError(form, stageEl || form, `Thiếu: ${label}`);
          errors.push(stageEl || form);
        }
        if(!startEl || !startEl.value){
          const label = getFieldLabel(form, startEl) || 'Ngày bắt đầu đợt';
          showFieldError(form, startEl || form, `Thiếu: ${label}`);
          errors.push(startEl || form);
        }
        if(!endEl || !endEl.value){
          const label = getFieldLabel(form, endEl) || 'Ngày kết thúc đợt';
          showFieldError(form, endEl || form, `Thiếu: ${label}`);
          errors.push(endEl || form);
        }

        // validate description (required, max length)
        if(!descEl || !descEl.value || !descEl.value.trim()){
          const label = getFieldLabel(form, descEl) || 'Mô tả';
          showFieldError(form, descEl || form, `Thiếu: ${label}`);
          errors.push(descEl || form);
        } else if(descEl.value.trim().length > 1000){
          showFieldError(form, descEl, 'Mô tả không được vượt quá 1000 ký tự.');
          errors.push(descEl);
        }

        if(startEl && endEl && startEl.value && endEl.value){
          if(new Date(startEl.value) > new Date(endEl.value)){
            showFieldError(form, startEl, 'Ngày bắt đầu phải trước hoặc bằng ngày kết thúc.');
            errors.push(startEl);
          }
        }

        // timeline stages: require both start and end for ALL stages and ensure start<=end
        for(let i=1;i<=8;i++){
          const sEl = form.querySelector(`[name="stage_${i}_start"]`);
          const eEl = form.querySelector(`[name="stage_${i}_end"]`);
          const s = sEl?.value || '';
          const e = eEl?.value || '';
          // require both values for every stage
          if(!s || !e){
            const labelS = getFieldLabel(form, sEl) || `Giai đoạn ${i} bắt đầu`;
            const labelE = getFieldLabel(form, eEl) || `Giai đoạn ${i} kết thúc`;
            if(!s) showFieldError(form, sEl || eEl || form, `Thiếu: ${labelS}`);
            if(!e) showFieldError(form, eEl || sEl || form, `Thiếu: ${labelE}`);
            errors.push(sEl || eEl || form);
            continue; // skip ordering check for this stage since it's incomplete
          }
          // both provided: check ordering
          if(new Date(s) > new Date(e)){
            showFieldError(form, sEl, `Giai đoạn ${i}: ngày bắt đầu phải trước hoặc bằng ngày kết thúc.`);
            errors.push(sEl);
          }
        }

        // Additional timeline validations:
        // 1) Each stage (if provided) must lie within the overall round start/end
        // 2) Stages must be in chronological order and non-overlapping: end(i) <= start(i+1)
        const roundStart = startEl?.value ? new Date(startEl.value) : null;
        const roundEnd = endEl?.value ? new Date(endEl.value) : null;
        if(roundStart && roundEnd){
          let prevEnd = null;
          for(let i=1;i<=8;i++){
            const sEl = form.querySelector(`[name="stage_${i}_start"]`);
            const eEl = form.querySelector(`[name="stage_${i}_end"]`);
            const s = sEl?.value ? new Date(sEl.value) : null;
            const e = eEl?.value ? new Date(eEl.value) : null;

            if(s && e){
              // inside round range
              if(s < roundStart){
                showFieldError(form, sEl, `Giai đoạn ${i}: ngày bắt đầu nhỏ hơn ngày bắt đầu đợt.`);
                errors.push(sEl);
              }
              if(e > roundEnd){
                showFieldError(form, eEl, `Giai đoạn ${i}: ngày kết thúc lớn hơn ngày kết thúc đợt.`);
                errors.push(eEl);
              }
              // ordering with previous stage
              if(prevEnd && s < prevEnd){
                showFieldError(form, sEl, `Giai đoạn ${i}: ngày bắt đầu phải sau hoặc bằng ngày kết thúc của giai đoạn trước.`);
                errors.push(sEl);
              }
              prevEnd = e;
            }
            // if stage not filled, keep prevEnd unchanged
          }
        }

        // NOTE: all stages are required now (handled above) so no "at least one" check here

        if(errors.length){
          // focus and scroll to first invalid element
          const first = errors.find(x => x instanceof Element) || errors[0];
          if(first && first instanceof Element) focusAndScroll(first);
          return false;
        }
        return true;
      }

      // Attach validation to static modal form: block submit if invalid
      document.getElementById('modalCreateForm')?.addEventListener('submit', function(e){
        // prevent default always and submit programmatically only when valid
        if (this.dataset.__submitting === '1') {
          return true;
        }
        e.preventDefault();
        if(!validateRoundForm(this)) return;
        // mark and submit so the route is called only when validation passes
        this.dataset.__submitting = '1';
        this.submit();
      });

      // Edit modal handling: open, populate and submit
      (function(){
        const modalEdit = document.getElementById('modalEdit');
        const editForm = document.getElementById('modalEditForm');
        const baseUrl = '{{ url("assistant/thesis/rounds") }}';

        function openEditModalForTerm(term){
          if(!editForm) return;
          clearValidationErrors(editForm);
          // set action to the update route
          editForm.action = baseUrl + '/' + encodeURIComponent(term.id);
          // fill basic fields
          editForm.querySelector('[name="academy_year_id"]').value = term.academy_year_id || '';
          editForm.querySelector('[name="stage"]').value = term.stage || '';
          editForm.querySelector('[name="start_date"]').value = term.start_date || '';
          editForm.querySelector('[name="end_date"]').value = term.end_date || '';
          editForm.querySelector('[name="description"]').value = term.description || '';

          // if stage timelines present, fill stage_{i}_start/end if inputs exist
          if(Array.isArray(term.stage_timelines) && term.stage_timelines.length){
            term.stage_timelines.forEach(st => {
              const idx = st.number || st.number_of_rounds || st.number_of_rounds === 0 ? st.number : st.number;
              if(!idx) return;
              const s = editForm.querySelector(`[name="stage_${idx}_start"]`);
              const e = editForm.querySelector(`[name="stage_${idx}_end"]`);
              if(s) s.value = st.start || '';
              if(e) e.value = st.end || '';
            });
          }

          modalEdit.classList.remove('hidden');
        }

        function closeEditModal(){
          modalEdit.classList.add('hidden');
        }

        // expose close for button
        window.closeEditModal = closeEditModal;

        // open when clicking an edit button; term data is stored on the row (<tr data-term='...'>)
        document.addEventListener('click', function(e){
          const btn = e.target.closest && e.target.closest('.btn-edit-round');
          if(!btn) return;
          // prefer data on the button if present, otherwise look up the closest row
          const tr = btn.closest('tr');
          const raw = btn.getAttribute('data-term') || (tr && tr.getAttribute('data-term'));
          if(!raw) {
            console.warn('No term data found for edit button');
            return;
          }
          let term = null;
          try{ term = JSON.parse(raw); }catch(err){ console.error('Failed to parse term data', err, raw); return; }
          openEditModalForTerm(term);
        });

        // validate & submit edit form
        editForm?.addEventListener('submit', function(e){
          if(this.dataset.__submitting === '1') return true;
          e.preventDefault();
          if(!validateRoundForm(this)) return;
          this.dataset.__submitting = '1';
          this.submit();
        });

      })();

      // Delete round handler (confirm box + DELETE request)
      (function(){
        const baseUrl = '{{ url("assistant/thesis/rounds") }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        document.addEventListener('click', function(e){
          const btn = e.target.closest && e.target.closest('.btn-delete-round');
          if(!btn) return;
          const roundId = btn.getAttribute('data-round-id');
          if(!roundId) return;
          const confirmed = confirm('Bạn có chắc chắn muốn xóa đợt đồ án này? Thao tác này không thể hoàn tác.');
          if(!confirmed) return;
          // disable to prevent double click
          btn.disabled = true;
          fetch(baseUrl + '/' + encodeURIComponent(roundId), {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
          }).then(async res => {
            if(res.ok){
              // remove row from table if possible, else reload
              const tr = btn.closest('tr');
              if(tr){ tr.remove(); }
              else { location.reload(); }
            } else {
              let msg = 'Xóa thất bại';
              try { const j = await res.json(); msg = j.message || JSON.stringify(j); } catch(e){}
              alert(msg);
              btn.disabled = false;
            }
          }).catch(err=>{
            console.error(err);
            alert('Lỗi khi xóa đợt. Vui lòng thử lại.');
            btn.disabled = false;
          });
        });
      })();
    </script>
  </body>
</html>
