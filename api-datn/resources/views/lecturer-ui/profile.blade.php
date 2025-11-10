<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hồ sơ Giảng viên</title>
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
    //$data_assignment_supervisors = $user->teacher->supervisor->assignment_supervisors ?? "null";
    //$supervisorId = $user->teacher->supervisor->id ?? null;
    $teacherId = $user->teacher->id ?? null;
    $avatarUrl = $user->avatar_url
      ?? $user->profile_photo_url
      ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    $departmentRole = $user->teacher->departmentRoles->where('role', 'head')->first() ?? null;
    $departmentId = $departmentRole?->department_id ?? 0;
    $phone = $user->phone ?? '';
    $address = $user->address ?? optional($user->teacher)->address ?? '';
    $gender = $user->gender ?? optional($user->teacher)->gender ?? '';
    $dob = $user->dob ?? optional($user->teacher)->dob ?? '';
    $teacher_code = optional($user->teacher)->teacher_code ?? $user->teacher_code ?? '';
    $position = optional($user->teacher)->position ?? $user->position ?? '';
    // Normalize positions into a list for display (supports array, Collection, comma-separated string)
    $positions_list = [];
    if (is_array($position)) {
      $positions_list = $position;
    } elseif ($position instanceof \Illuminate\Support\Collection) {
      $positions_list = $position->toArray();
    } elseif (is_string($position) && strpos($position, ',') !== false) {
      $positions_list = array_map('trim', explode(',', $position));
    } elseif (!empty($position)) {
      $positions_list = [$position];
    }
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

        <button type="button" id="toggleThesisMenu" class="w-full flex items-center justify-between px-3 py-2 rounded-lg mt-3
                         {{ $isThesisOpen ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
          <span class="flex items-center gap-3">
            <i class="ph ph-graduation-cap"></i>
            <span class="sidebar-label">Học phần tốt nghiệp</span>
          </span>
          <i id="thesisCaret" class="ph ph-caret-down transition-transform {{ $isThesisOpen ? 'rotate-180' : '' }}"></i>
        </button>

        <div id="thesisSubmenu" class="mt-1 pl-3 space-y-1 {{ $isThesisOpen ? '' : 'hidden' }}">
          <a href="{{ route('web.teacher.thesis_internship') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_internship') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span>
          </a>
          @if ($departmentRole)
          <a href="{{ route('web.teacher.all_thesis_rounds', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_rounds') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
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

    <div class="flex-1">
      <header
        class="fixed left-0 md:left-[260px] right-0 top-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-20">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i
              class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Hồ sơ cá nhân</h1>
            <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Giảng viên / Hồ sơ cá nhân</nav>
          </div>
        </div>
        <div class="relative">
          <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
            <img class="h-9 w-9 rounded-full object-cover" src="{{$avatarUrl}}}" alt="avatar" />
            <div class="hidden sm:block text-left">
              <div class="text-sm font-semibold leading-4">{{ $userName }}</div>
              <div class="text-xs text-slate-500">{{ $email }}</div>
            </div>
            <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
          </button>
          <div id="profileMenu"
            class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
            <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông
              tin</a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
              class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i
                class="ph ph-sign-out"></i>Đăng xuất</a>
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </header>

<main class="pt-20 px-4 md:px-6 pb-10">
  <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Sidebar card: Avatar -->
    <aside class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col items-center">
      <img id="avatarPreview" class="h-24 w-24 rounded-full object-cover" src="{{ $avatarUrl }}" alt="avatar" />
      <div class="mt-3 text-center">
        <div class="font-semibold text-lg text-slate-900">{{ $user->fullname }}</div>
        <div class="text-sm text-slate-500">{{ $user->teacher->position }}</div>
      </div>
    </aside>

    <!-- Profile details -->
    <section class="bg-white rounded-2xl border border-slate-200 p-6 lg:col-span-2 shadow-sm hover:shadow-md transition-all duration-200">
      <div class="flex items-start justify-between mb-4">
        <div>
          <h2 class="text-xl font-semibold text-slate-900 flex items-center gap-2">
            <i class="ph ph-user-circle text-indigo-500 text-lg"></i>
            Thông tin cá nhân
          </h2>
          <p class="text-sm text-slate-500 mt-1">Thông tin hiển thị công khai trên hồ sơ giảng viên.</p>
        </div>
        <div class="text-right">
          <button id="openEditProfile" type="button" class="text-sm text-blue-600 hover:underline">Chỉnh sửa</button>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Họ và tên -->
        <div class="flex items-center gap-2 p-4 rounded-lg border border-slate-100 bg-slate-50">
          <i class="ph ph-user text-indigo-500 text-lg"></i>
          <div>
            <div class="text-xs text-slate-500">Họ và tên</div>
            <div class="mt-1 font-medium text-slate-900">{{ $user->fullname ?? $userName }}</div>
          </div>
        </div>

        <!-- Email -->
        <div class="flex items-center gap-2 p-4 rounded-lg border border-slate-100 bg-slate-50">
          <i class="ph ph-envelope text-indigo-500 text-lg"></i>
          <div>
            <div class="text-xs text-slate-500">Email</div>
            <div class="mt-1 font-medium text-slate-900">{{ $email }}</div>
          </div>
        </div>

        <!-- Số điện thoại -->
        <div class="flex items-center gap-2 p-4 rounded-lg border border-slate-100 bg-slate-50">
          <i class="ph ph-phone text-indigo-500 text-lg"></i>
          <div>
            <div class="text-xs text-slate-500">Số điện thoại</div>
            <div class="mt-1 font-medium text-slate-900">{{ $phone ?: '-' }}</div>
          </div>
        </div>

        <!-- Mã giảng viên -->
        <div class="flex items-center gap-2 p-4 rounded-lg border border-slate-100 bg-slate-50">
          <i class="ph ph-identification-badge text-indigo-500 text-lg"></i>
          <div>
            <div class="text-xs text-slate-500">Mã giảng viên</div>
            <div class="mt-1 font-medium text-slate-900">{{ $user->teacher->teacher_code ?: '-' }}</div>
          </div>
        </div>

        <!-- Địa chỉ -->
        <div class="flex items-center gap-2 p-4 rounded-lg border border-slate-100 bg-slate-50">
          <i class="ph ph-map-pin text-indigo-500 text-lg"></i>
          <div>
            <div class="text-xs text-slate-500">Địa chỉ</div>
            <div class="mt-1 font-medium text-slate-900">{{ $address ?: '-' }}</div>
          </div>
        </div>

        <!-- Giới tính -->
        <div class="flex items-center gap-2 p-4 rounded-lg border border-slate-100 bg-slate-50">
          <i class="ph ph-gender-neuter text-indigo-500 text-lg"></i>
          <div>
            <div class="text-xs text-slate-500">Giới tính</div>
            <div class="mt-1 font-medium text-slate-900">{{ $gender ?: '-' }}</div>
          </div>
        </div>

        <!-- Ngày sinh -->
        <div class="flex items-center gap-2 p-4 rounded-lg border border-slate-100 bg-slate-50">
          <i class="ph ph-calendar text-indigo-500 text-lg"></i>
          <div>
            <div class="text-xs text-slate-500">Ngày sinh</div>
            <div class="mt-1 font-medium text-slate-900">
              @if($dob)
                {{ \Carbon\Carbon::parse($dob)->format('d/m/Y') }}
              @else
                -
              @endif
            </div>
          </div>
        </div>

        <!-- Học vị -->
        <div class="flex items-center gap-2 p-4 rounded-lg border border-slate-100 bg-slate-50">
          <i class="ph ph-graduation-cap text-indigo-500 text-lg"></i>
          <div>
            <div class="text-xs text-slate-500">Học vị</div>
            <div class="mt-1 font-medium text-slate-900">{{ $degree ?: ($user->teacher->degree ?? '-') }}</div>
          </div>
        </div>

        <!-- Chức vụ -->
        <div class="flex items-start gap-2 p-4 rounded-lg border border-slate-100 bg-slate-50">
          <i class="ph ph-briefcase text-indigo-500 text-lg mt-1"></i>
          <div>
            <div class="text-xs text-slate-500">Chức vụ</div>
            @if(count($positions_list))
              <ul class="mt-1 list-disc list-inside space-y-1 text-slate-900">
                @foreach($positions_list as $p)
                  <li class="font-medium">{{ $p }}</li>
                @endforeach
              </ul>
            @else
              <div class="mt-1 font-medium text-slate-900">-</div>
            @endif
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Edit profile modal -->
<div id="editProfileModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
  <!-- Backdrop -->
  <div class="absolute inset-0 bg-black/40" id="editProfileBackdrop"></div>

  <!-- Modal content -->
  <div class="relative w-full max-w-5xl bg-white rounded-2xl shadow-xl p-6 sm:p-8 transition-transform transform scale-95 opacity-0 duration-200 max-h-[90vh] overflow-auto lg:px-10"
    id="editProfileContent">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg sm:text-xl font-semibold text-slate-900 flex items-center gap-2">
        <i class="ph ph-pencil text-indigo-500 text-lg"></i>
        Chỉnh sửa thông tin cá nhân
      </h3>
      <button id="closeEditProfile" class="text-slate-500 hover:text-slate-700 text-2xl">✕</button>
    </div>

    <!-- Two-column modal: left summary, right form -->
    <form id="editProfileForm" method="POST" action="" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      @csrf
      <input type="hidden" name="_method" value="PUT">

      <!-- Left: compact summary / avatar -->
      <aside class="lg:col-span-4 bg-slate-50 rounded-xl p-4 border border-slate-100 flex flex-col items-center gap-4">
        <img class="h-24 w-24 rounded-full object-cover shadow-sm" src="{{ $avatarUrl }}" alt="avatar" />
        <div class="text-center">
          <div class="text-base font-semibold text-slate-900">{{ $user->fullname }}</div>
          <div class="text-sm text-slate-500">{{ $email }}</div>
        </div>

        <div class="w-full mt-1">
          <dl class="text-sm text-slate-600 space-y-2">
            <div class="flex justify-between"><dt class="text-slate-500">Mã</dt><dd class="font-medium text-slate-900">{{ $teacher_code ?: '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Học vị</dt><dd class="font-medium text-slate-900">{{ $degree ?: '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Giới tính</dt><dd class="font-medium text-slate-900">{{ $gender ?: '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Ngày sinh</dt><dd class="font-medium text-slate-900">@if($dob){{ \Carbon\Carbon::parse($dob)->format('d/m/Y') }}@else - @endif</dd></div>
          </dl>
        </div>

        <div class="w-full mt-2 text-xs text-slate-500">Lưu ý: thay đổi sẽ áp dụng sau khi lưu.</div>
      </aside>

      <!-- Right: grouped form -->
      <div class="lg:col-span-8 bg-white rounded-xl p-4 border border-slate-100 max-h-[72vh] overflow-auto">
        <section class="mb-4">
          <h4 class="text-sm font-medium text-slate-700 mb-2">Cơ bản</h4>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="text-xs text-slate-500">Họ và tên</label>
              <input name="fullname" value="{{ $user->fullname }}" placeholder="Họ và tên"
                     class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-1 focus:ring-indigo-500" />
            </div>
            <div>
              <label class="text-xs text-slate-500">Email</label>
              <input name="email" type="email" value="{{ $email }}" placeholder="Email"
                     class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-1 focus:ring-indigo-500" />
            </div>
            <div>
              <label class="text-xs text-slate-500">Số điện thoại</label>
              <input name="phone" value="{{ $phone }}" placeholder="Số điện thoại"
                     class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-1 focus:ring-indigo-500" />
            </div>
            <div>
              <label class="text-xs text-slate-500">Mã giảng viên</label>
              <input name="teacher_code" value="{{ $teacher_code }}" placeholder="Mã giảng viên"
                     class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-1 focus:ring-indigo-500" />
            </div>
          </div>
        </section>

        <section class="mb-2">
          <h4 class="text-sm font-medium text-slate-700 mb-2">Chức vụ & Học vị</h4>
          <div class="grid grid-cols-1 gap-3">
            <div>
              <label class="text-xs text-slate-500">Học vị</label>
              <input name="degree" value="{{ $degree }}" placeholder="Học vị"
                     class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-1 focus:ring-indigo-500" />
            </div>
            <div>
              <label class="text-xs text-slate-500">Chức vụ (phân cách bằng dấu phẩy)</label>
              <input name="position" value="{{ is_array($position) ? implode(', ', $position) : $position }}"
                     placeholder="Chức vụ (phân cách bằng dấu phẩy)"
                     class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-1 focus:ring-indigo-500" />
            </div>
          </div>
        </section>

        <section class="mb-4">
          <h4 class="text-sm font-medium text-slate-700 mb-2">Địa chỉ & Thông tin cá nhân</h4>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="sm:col-span-2">
              <label class="text-xs text-slate-500">Địa chỉ</label>
              <input name="address" value="{{ $address }}" placeholder="Địa chỉ"
                     class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-1 focus:ring-indigo-500" />
            </div>

            <div>
              <label class="text-xs text-slate-500">Giới tính</label>
              <select name="gender" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-1 focus:ring-indigo-500">
                <option value="" {{ $gender=='' ? 'selected' : '' }}>--</option>
                <option value="Nam" {{ $gender=='Nam' ? 'selected' : '' }}>Nam</option>
                <option value="Nữ" {{ $gender=='Nữ' ? 'selected' : '' }}>Nữ</option>
                <option value="Khác" {{ $gender=='Khác' ? 'selected' : '' }}>Khác</option>
              </select>
            </div>

            <div>
              <label class="text-xs text-slate-500">Ngày sinh</label>
              <input name="dob" type="date" value="{{ $dob ? \Carbon\Carbon::parse($dob)->format('Y-m-d') : '' }}"
                     class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-1 focus:ring-indigo-500" />
            </div>
          </div>
        </section>

        <!-- Sticky footer inside the scrollable right column -->
        <div class="mt-4 pt-4 border-t border-slate-100 sticky bottom-0 bg-white -mx-4 px-4 pb-4">
          <div class="flex items-center justify-end gap-3">
            <button type="button" id="cancelEditProfile" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Lưu thay đổi</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>


</main>

    </div>
  </div>

  <script>
    const html = document.documentElement, sidebar = document.getElementById('sidebar');
    function setCollapsed(c) { const h = document.querySelector('header'); const m = document.querySelector('main'); if (c) { html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]'); m.classList.remove('md:pl-[260px]'); } else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]'); m.classList.add('md:pl-[260px]'); } }
    document.getElementById('toggleSidebar')?.addEventListener('click', () => { const c = !html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar', '' + (c ? 1 : 0)); });
    document.getElementById('openSidebar')?.addEventListener('click', () => sidebar.classList.toggle('-translate-x-full'));
    if (localStorage.getItem('lecturer_sidebar') === '1') setCollapsed(true);
    sidebar.classList.add('md:translate-x-0', '-translate-x-full', 'md:static');

    // Avatar preview
    const input = document.getElementById('avatarInput'); const preview = document.getElementById('avatarPreview');
    input?.addEventListener('change', (e) => { const file = e.target.files?.[0]; if (!file) return; const reader = new FileReader(); reader.onload = () => { preview.src = reader.result; }; reader.readAsDataURL(file); });

    // profile dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    profileBtn?.addEventListener('click', () => profileMenu.classList.toggle('hidden'));
    document.addEventListener('click', (e) => { if (!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

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

  // Edit profile modal controls (animated show/hide)
  const openEditProfile = document.getElementById('openEditProfile');
  const editProfileModal = document.getElementById('editProfileModal');
  const editProfileContent = document.getElementById('editProfileContent');
  const closeEditProfile = document.getElementById('closeEditProfile');
  const cancelEditProfile = document.getElementById('cancelEditProfile');
  const editProfileBackdrop = document.getElementById('editProfileBackdrop');

  function showEditProfile() {
    if (!editProfileModal) return;
    // reveal modal container
    editProfileModal.classList.remove('hidden');
    // allow next frame then animate content to visible
    requestAnimationFrame(() => {
      if (editProfileContent) {
        editProfileContent.classList.remove('scale-95', 'opacity-0');
        editProfileContent.classList.add('scale-100', 'opacity-100');
      }
    });
  }

  function hideEditProfile() {
    if (!editProfileModal) return;
    // animate content out
    if (editProfileContent) {
      editProfileContent.classList.remove('scale-100', 'opacity-100');
      editProfileContent.classList.add('scale-95', 'opacity-0');
    }
    // after animation duration (match CSS 200ms) hide container
    setTimeout(() => { editProfileModal.classList.add('hidden'); }, 220);
  }

  openEditProfile?.addEventListener('click', showEditProfile);
  closeEditProfile?.addEventListener('click', hideEditProfile);
  cancelEditProfile?.addEventListener('click', hideEditProfile);
  editProfileBackdrop?.addEventListener('click', hideEditProfile);
  document.addEventListener('keyup', (e) => { if (e.key === 'Escape') hideEditProfile(); });
  </script>
</body>

</html>