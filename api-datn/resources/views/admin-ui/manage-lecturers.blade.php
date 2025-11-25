<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Quản lý giảng viên</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <style>
    body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
    .sidebar { width: 260px; }
    .sidebar-collapsed .sidebar { width: 72px; }
    .sidebar-collapsed .sidebar-label { display: none; }
  </style>
</head>
<body class="bg-slate-50 text-slate-800">
@php
  $user = auth()->user();
  $userName = $user->fullname ?? $user->name ?? 'Quản trị viên';
  $email = $user->email ?? '';
  $avatarUrl = $user->avatar_url ?? $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';

  // Data mẫu khi chưa có $lecturers từ controller
  $hasData = isset($lecturers);
  $items = $hasData ? $lecturers : collect([
    (object)['id'=>1,'lecturer_code'=>'GV001','fullname'=>'TS. Nguyễn Văn D','department_name'=>'KTPM','email'=>'d@univ.edu','status'=>'active'],
    (object)['id'=>2,'lecturer_code'=>'GV002','fullname'=>'ThS. Trần Thị E','department_name'=>'HTTT','email'=>'e@univ.edu','status'=>'inactive'],
    (object)['id'=>3,'lecturer_code'=>'GV003','fullname'=>'ThS. Lê Văn F','department_name'=>'CNPM','email'=>'f@univ.edu','status'=>'probation'],
  ]);
@endphp

<div class="flex min-h-screen">
  <!-- Sidebar -->
      <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
        <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
          <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-buildings"></i></div>
          <div class="sidebar-label">
            <div class="font-semibold">UniAdmin</div>
            <div class="text-xs text-slate-500">Quản trị hệ thống</div>
          </div>
        </div>
        <nav class="flex-1 overflow-y-auto p-3">
          <a href="{{ route('web.admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">
            <i class="ph ph-gauge"></i> <span class="sidebar-label">Bảng điều khiển</span>
          </a>
          <a href="{{ route('web.admin.manage_faculties') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">
            <i class="ph ph-graduation-cap"></i> <span class="sidebar-label">Quản lý Khoa</span>
          </a>
          <a href="{{ route('web.admin.manage_assistants') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">
            <i class="ph ph-users-three"></i> <span class="sidebar-label">Trợ lý khoa</span>
          </a>
          <a href="{{ route('web.admin.manage_students') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-100 font-medium">
            <i class="ph ph-users"></i> <span class="sidebar-label">Quản lý Sinh viên</span>
          </a>
          <a href="{{ route('web.admin.manage_lecturers') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-700 bg-slate-100 font-bold">
            <i class="ph ph-chalkboard-teacher"></i> <span class="sidebar-label">Quản lý Giảng viên</span>
          </a>
        </nav>
        <div class="p-3 border-t border-slate-200">
          <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
        </div>
      </aside>

  <!-- Main -->
  <div class="flex-1 h-screen overflow-hidden flex flex-col">
    <!-- Header -->
    <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
      <div class="flex items-center gap-3 flex-1">
        <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
        <div>
          <h1 class="text-lg md:text-xl font-semibold">Quản lý giảng viên</h1>
          <nav aria-label="Breadcrumb" class="mt-0.5 text-xs text-slate-500">
            <ol class="flex items-center gap-1">
              <li><a href="{{ route('web.admin.dashboard') }}" class="hover:text-slate-700">Dashboard</a><span class="text-slate-400">/</span></li>
              <li class="text-slate-700 font-medium">Giảng viên</li>
            </ol>
          </nav>
        </div>
      </div>
      <div class="relative">
        <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100" aria-expanded="false">
          <img class="h-9 w-9 rounded-full object-cover" src="{{ $avatarUrl }}" alt="avatar" />
          <div class="hidden sm:block text-left">
            <div class="text-sm font-semibold leading-4">{{ $userName }}</div>
            <div class="text-xs text-slate-500">{{ $email }}</div>
          </div>
          <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
        </button>
        <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
          <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Thông tin</a>
          <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
          <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
      </div>
    </header>

    <!-- Content -->
    <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
      <div class="max-w-7xl mx-auto space-y-6">
        <!-- Toolbar -->
        <section class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
          <div class="flex items-center gap-2">
            <button id="btnAdd" type="button" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm">
              <i class="ph ph-plus"></i> Thêm giảng viên
            </button>
            <button id="btnAssignAssistant" type="button" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm">
              <i class="ph ph-user-list"></i> Phân trợ lý khoa
            </button>
            <button id="btnExport" type="button" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border text-slate-700 hover:bg-slate-50 text-sm">
              <i class="ph ph-download-simple"></i> Xuất danh sách
            </button>
          </div>
          <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
            <div class="relative">
              <input id="searchInput" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo mã GV, họ tên, email">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <select id="filterStatus" class="px-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
              <option value="">Tất cả trạng thái</option>
              <option value="active">Đang công tác</option>
              <option value="probation">Thử việc</option>
              <option value="inactive">Ngừng công tác</option>
            </select>
          </div>
        </section>

        <!-- Current faculty assistant session -->
        <section id="assistantSession" class="bg-white rounded-xl border border-slate-200 p-4 mt-4 hidden md:block">
          <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-full bg-emerald-50 text-emerald-700 grid place-items-center text-xl"><i class="ph ph-user-circle"></i></div>
            <div>
              <div class="text-sm text-slate-500">Trợ lý khoa hiện tại</div>
              <div id="assistantName" class="font-medium text-slate-800">Chưa có</div>
              <div id="assistantEmail" class="text-xs text-slate-500">—</div>
            </div>
          </div>
        </section>

        <!-- Table -->
        <section class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
              <tr>
                <th class="text-left px-4 py-3 w-12"><input type="checkbox" id="chkAll" class="h-4 w-4"></th>
                <th class="text-left px-4 py-3">Mã GV</th>
                <th class="text-left px-4 py-3">Họ tên</th>
                <th class="text-left px-4 py-3">Bộ môn</th>
                <th class="text-left px-4 py-3">Email</th>
                <th class="text-center px-4 py-3">Trạng thái</th>
                <th class="text-right px-4 py-3">Hành động</th>
              </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-slate-100">
              @foreach ($teachers as $gv)
                @php
                  $status = $gv->status ?? 'active';
                  $pill = ['active'=>'bg-emerald-50 text-emerald-700','probation'=>'bg-amber-50 text-amber-700','inactive'=>'bg-slate-100 text-slate-700'][$status] ?? 'bg-slate-100 text-slate-700';
                  $pillText = ['active'=>'Đang công tác','probation'=>'Thử việc','inactive'=>'Ngừng công tác'][$status] ?? 'Khác';
                @endphp
                <tr class="hover:bg-slate-50" data-status="{{ $status }}">
                  <td class="px-4 py-3"><input type="checkbox" class="rowChk h-4 w-4"></td>
                  <td class="px-4 py-3 font-mono">{{ $gv->teacher_code ?? ($gv->code ?? '-') }}</td>
                  <td class="px-4 py-3 font-medium text-slate-800">{{ $gv->fullname ?? ($gv->user->fullname ?? '-') }}</td>
                  <td class="px-4 py-3">{{ $gv->department_name ?? ($gv->department->name ?? '-') }}</td>
                  <td class="px-4 py-3">{{ $gv->email ?? ($gv->user->email ?? '-') }}</td>
                  <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $pill }}">{{ $pillText }}</span>
                  </td>
                  <td class="px-4 py-3 text-right space-x-2">
        <button class="btnEdit px-2 py-1.5 rounded-lg border hover:bg-slate-50 text-indigo-600"
          data-id="{{ $gv->id }}"
          data-code="{{ $gv->teacher_code ?? ($gv->code ?? '') }}"
          data-name="{{ $gv->fullname ?? ($gv->user->fullname ?? '') }}"
          data-dept="{{ $gv->department_name ?? ($gv->department->name ?? '') }}"
          data-email="{{ $gv->email ?? ($gv->user->email ?? '') }}"
          data-dob="{{ $gv->dob ?? $gv->date_of_birth ?? '' }}"
          data-address="{{ $gv->address ?? '' }}"
          data-status="{{ $status }}">
                      <i class="ph ph-pencil"></i>
                    </button>
                    <button class="btnDelete px-2 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600" data-id="{{ $gv->id }}">
                      <i class="ph ph-trash"></i>
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </section>

        @if($hasData && method_exists($lecturers, 'links'))
          <div>{{ $lecturers->links() }}</div>
        @endif
      </div>
    </main>
  </div>
</div>

<!-- Modal: Thêm/Sửa giảng viên -->
<div id="lecturerModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/40" data-close></div>
  <div class="relative z-10 bg-white w-full max-w-5xl rounded-2xl shadow-2xl overflow-hidden md:overflow-visible max-h-[calc(100vh-2rem)]">
    <div class="md:flex">
      <!-- Left accent panel -->
      <div class="hidden md:flex md:w-1/3 bg-gradient-to-b from-indigo-600 to-blue-600 text-white p-6 flex-col items-center justify-center gap-4">
        <div class="h-20 w-20 rounded-full bg-white/10 grid place-items-center text-white text-3xl font-bold">
          <i class="ph ph-chalkboard-teacher"></i>
        </div>
        <div class="text-center">
          <h3 id="modalTitle" class="text-lg font-semibold">Thêm giảng viên</h3>
          <p class="text-sm opacity-90">Quản lý thông tin giảng viên & tài khoản</p>
        </div>
        <div class="text-xs text-white/80">Các thay đổi sẽ cập nhật ngay sau khi lưu.</div>
      </div>

      <!-- Form area -->
      <div class="w-full md:w-2/3 bg-white p-6">
        <div class="flex items-start justify-between mb-4">
          <div class="flex items-center gap-3">
            <div class="md:hidden h-12 w-12 rounded-full bg-indigo-600 grid place-items-center text-white text-xl"><i class="ph ph-chalkboard-teacher"></i></div>
            <div>
              <h3 id="modalTitleMobile" class="font-semibold">Thêm giảng viên</h3>
              <p class="text-sm text-slate-500">Điền đầy đủ thông tin, sau đó nhấn Lưu</p>
            </div>
          </div>
          <button class="text-slate-500 hover:text-slate-700" data-close aria-label="Đóng"><i class="ph ph-x"></i></button>
        </div>

  <div class="space-y-4 overflow-y-auto md:overflow-visible max-h-[60vh] md:max-h-none pr-2">
          <input type="hidden" id="gvId">

          <div class="grid grid-cols-1 gap-3">
            <label class="text-xs text-slate-500">Mã GV</label>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-indigo-50 text-indigo-600"><i class="ph ph-hash"></i></span>
              <input id="gvCode" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="VD: GV001">
            </div>
          </div>

          <div class="grid grid-cols-1 gap-3">
            <label class="text-xs text-slate-500">Họ tên</label>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-emerald-50 text-emerald-600"><i class="ph ph-user"></i></span>
              <input id="gvName" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400" placeholder="Họ và tên">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="text-xs text-slate-500">Bộ môn</label>
              @php
                $deptOptions = isset($departments) ? $departments : collect([
                  (object)['code'=>'KTPM','name'=>'Kỹ thuật phần mềm'],
                  (object)['code'=>'HTTT','name'=>'Hệ thống thông tin'],
                  (object)['code'=>'CNPM','name'=>'Công nghệ phần mềm'],
                ]);
              @endphp
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-sky-50 text-sky-600"><i class="ph ph-building"></i></span>
                <select id="gvDept" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                  @foreach($deptOptions as $d)
                    @php
                      $val = is_object($d) ? ($d->code ?? $d->name ?? '') : (is_array($d) ? ($d['code'] ?? $d['name'] ?? '') : $d);
                      $label = is_object($d) ? ($d->name ?? $d->code ?? $d->id ?? '') : (is_array($d) ? ($d['name'] ?? $d['code'] ?? '') : $d);
                    @endphp
                    <option value="{{ $val }}">{{ $label }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div>
              <label class="text-xs text-slate-500">Email</label>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-amber-50 text-amber-600"><i class="ph ph-envelope"></i></span>
                <input id="gvEmail" type="email" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-400 focus:border-amber-400" placeholder="email@domain.edu">
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="text-xs text-slate-500">Mật khẩu (để trống nếu không đổi)</label>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-rose-50 text-rose-600"><i class="ph ph-key"></i></span>
                <input id="gvPassword" type="password" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-rose-400 focus:border-rose-400" placeholder="••••••••">
              </div>
            </div>

            <div>
              <label class="text-xs text-slate-500">Ngày sinh</label>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-violet-50 text-violet-600"><i class="ph ph-calendar"></i></span>
                <input id="gvDob" type="date" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-violet-400 focus:border-violet-400">
              </div>
            </div>
          </div>

          <div>
            <label class="text-xs text-slate-500">Địa chỉ</label>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-slate-50 text-slate-600"><i class="ph ph-map-pin"></i></span>
              <input id="gvAddress" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-400 focus:border-slate-400">
            </div>
          </div>

          <div>
            <label class="text-xs text-slate-500">Trạng thái</label>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-slate-50 text-slate-600"><i class="ph ph-activity"></i></span>
              <select id="gvStatus" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="active">Đang công tác</option>
                <option value="probation">Thử việc</option>
                <option value="inactive">Ngừng công tác</option>
              </select>
            </div>
          </div>
        </div>

        <div class="mt-4 flex items-center justify-end gap-3">
          <button class="px-4 py-2 rounded-lg border text-sm hover:bg-slate-50" data-close>Đóng</button>
          <button id="btnSave" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm">
            <i class="ph ph-check"></i> Lưu
          </button>
        </div>
      </div>
    </div>
  </div>

        <!-- Modal: Phân trợ lý khoa -->
        <div id="assignAssistantModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/40" data-close></div>
          <div class="relative z-10 bg-white w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6">
              <div class="flex items-start justify-between mb-4">
                <div>
                  <h3 id="assignAssistantModalTitle" class="text-lg font-semibold">Phân trợ lý khoa</h3>
                  <p class="text-sm text-slate-500">Xem trợ lý khoa hiện tại và chọn giảng viên làm trợ lý mới</p>
                </div>
                <button class="text-slate-500 hover:text-slate-700" data-close aria-label="Đóng"><i class="ph ph-x"></i></button>
              </div>

              <div class="space-y-4">
                <div>
                  <div class="text-xs text-slate-500">Trợ lý khoa hiện tại</div>
                  <div id="currentAssistantBlock" class="mt-2 p-3 border rounded-lg bg-slate-50">
                    <div id="currentAssistantName" class="font-medium">Chưa có</div>
                    <div id="currentAssistantEmail" class="text-xs text-slate-500">—</div>
                    <div id="currentAssistantPhone" class="text-xs text-slate-500">—</div>
                    <div id="currentAssistantDept" class="text-xs text-slate-500">—</div>
                  </div>
                </div>

                <div>
                  <label class="text-xs text-slate-500">Chọn tài khoản làm trợ lý khoa</label>
                  @php
                    // allow controller to pass $accounts (users or teacher accounts). fallback to $teachers
                    $accountOptions = $accounts ?? $teachers ?? collect();
                  @endphp
                  <select id="assistantSelect" class="mt-2 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                    <option value="">-- Chọn tài khoản --</option>
                    @foreach($accountOptions as $a)
                      @php
                        // normalize dataset values
                        $name = $a->fullname ?? ($a->name ?? ($a->user->fullname ?? ''));
                        $emailOpt = $a->email ?? ($a->user->email ?? '');
                        $phoneOpt = $a->phone ?? ($a->user->phone ?? '');
                        $deptOpt = $a->department_name ?? ($a->department->name ?? '');
                        $label = $name . (isset($a->teacher_code) ? ' — ' . ($a->teacher_code ?? '') : (isset($a->code) ? ' — ' . ($a->code ?? '') : ''));
                      @endphp
                      <option value="{{ $a->id }}" data-name="{{ $name }}" data-email="{{ $emailOpt }}" data-phone="{{ $phoneOpt }}" data-dept="{{ $deptOpt }}">{{ $label }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="text-right">
                  <button id="btnAssignAssistantConfirm" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm">Xác nhận</button>
                </div>
              </div>
            </div>
          </div>
        </div>
</div>

<script>
  // Sidebar collapse giống dashboard
  const html = document.documentElement, sidebar = document.getElementById('sidebar');
  function setCollapsed(c){
    const mainArea = document.querySelector('.flex-1');
    if(c){ html.classList.add('sidebar-collapsed');}
    else { html.classList.remove('sidebar-collapsed');}
  }
  document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('admin_sidebar',''+(c?1:0));});
  document.getElementById('openSidebar')?.addEventListener('click',()=> sidebar.classList.toggle('-translate-x-full'));
  if(localStorage.getItem('admin_sidebar')==='1') setCollapsed(true);
  sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

  // Profile dropdown: toggle + click outside + ESC (đồng bộ dashboard)
  const profileBtn = document.getElementById('profileBtn');
  const profileMenu = document.getElementById('profileMenu');
  function closeProfileMenu(){ profileMenu?.classList.add('hidden'); profileBtn?.setAttribute('aria-expanded','false'); }
  function openProfileMenu(){ profileMenu?.classList.remove('hidden'); profileBtn?.setAttribute('aria-expanded','true'); }
  profileBtn?.addEventListener('click',(e)=>{ e.stopPropagation(); const isHidden=profileMenu?.classList.contains('hidden'); if(isHidden) openProfileMenu(); else closeProfileMenu(); });
  document.addEventListener('click',(e)=>{ if(!profileMenu||!profileBtn) return; if(!profileMenu.contains(e.target)&&!profileBtn.contains(e.target)) closeProfileMenu(); });
  document.addEventListener('keydown',(e)=>{ if(e.key==='Escape') closeProfileMenu(); });

  // Modal helpers
  const modal = document.getElementById('lecturerModal');
  // Also grab assign modal early so modal-manager helpers can reference it safely
  const assignModal = document.getElementById('assignAssistantModal');
  // Normalize event target to an Element (skip text nodes) so .matches/.closest won't throw
  function getEventElement(e){
    let el = e.target;
    while(el && el.nodeType !== 1){ el = el.parentNode; }
    return el;
  }
  function openModal(){ modal.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); }
  function closeModal(){ modal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
  modal?.addEventListener('click',(e)=>{ const el = getEventElement(e); if(el && el.matches('[data-close]')) closeModal(); });

  // Ensure openModal hides assign modal so buttons open the correct modal only
  const origOpenModal = openModal;
  function openModalExclusive(){
    // hide assign modal if open
    assignModal?.classList.add('hidden');
    origOpenModal();
  }
  // Replace openModal references in local scope by pointing name to exclusive variant
  // (handlers below use openModal(), so overwrite it)
  openModal = openModalExclusive;

  // Toolbar actions
  document.getElementById('btnAdd')?.addEventListener('click', () => {
    document.getElementById('modalTitle').textContent = 'Thêm giảng viên';
    document.getElementById('gvId').value = '';
    document.getElementById('gvCode').value = '';
    document.getElementById('gvName').value = '';
    document.getElementById('gvDept').value = document.getElementById('gvDept').options[0]?.value || '';
    document.getElementById('gvEmail').value = '';
    document.getElementById('gvPassword').value = '';
    document.getElementById('gvDob').value = '';
    document.getElementById('gvAddress').value = '';
    document.getElementById('gvStatus').value = 'active';
    openModal();
  });

  // Search + filter (client-side)
  const searchInput = document.getElementById('searchInput');
  const filterStatus = document.getElementById('filterStatus');
  function applyFilter(){
    const q = (searchInput?.value || '').toLowerCase();
    const st = filterStatus?.value || '';
    document.querySelectorAll('#tableBody tr').forEach(tr => {
      const text = tr.innerText.toLowerCase();
      const okQ = !q || text.includes(q);
      const okS = !st || tr.getAttribute('data-status') === st;
      tr.style.display = okQ && okS ? '' : 'none';
    });
  }
  searchInput?.addEventListener('input', applyFilter);
  filterStatus?.addEventListener('change', applyFilter);

  // Edit row
  document.getElementById('tableBody')?.addEventListener('click', (e)=>{
    const el = getEventElement(e);
    const btn = el ? el.closest('.btnEdit') : null;
    if(!btn) return;
    document.getElementById('modalTitle').textContent = 'Sửa giảng viên';
    document.getElementById('gvId').value = btn.dataset.id || '';
    document.getElementById('gvCode').value = btn.dataset.code || '';
    document.getElementById('gvName').value = btn.dataset.name || '';
    document.getElementById('gvDept').value = btn.dataset.dept || document.getElementById('gvDept').options[0]?.value || '';
    document.getElementById('gvEmail').value = btn.dataset.email || '';
    // don't populate password for security
    document.getElementById('gvPassword').value = '';
    document.getElementById('gvDob').value = btn.dataset.dob || '';
    document.getElementById('gvAddress').value = btn.dataset.address || '';
    document.getElementById('gvStatus').value = btn.dataset.status || 'active';
    openModal();
  });

  // Delete row (confirm)
  document.getElementById('tableBody')?.addEventListener('click', async (e)=>{
    const el = getEventElement(e);
    const btn = el ? el.closest('.btnDelete') : null;
    if(!btn) return;
    const id = btn.dataset.id;
    if(!id) return alert('Thiếu ID giảng viên');
    if(!confirm('Xóa giảng viên này?')) return;

    const old = btn.innerHTML; btn.disabled = true; btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i>';
    try {
      btn.closest('tr')?.remove();
    } catch (err) {
      console.error(err);
      alert('Không thể xóa. Vui lòng thử lại.');
    } finally {
      btn.disabled = false; btn.innerHTML = old;
    }
  });

  // Save (create/update) — demo client-side; gắn API khi có route
  document.getElementById('btnSave')?.addEventListener('click', async ()=>{
    const id = document.getElementById('gvId').value.trim();
    const code = document.getElementById('gvCode').value.trim();
    const name = document.getElementById('gvName').value.trim();
    const dept = document.getElementById('gvDept').value.trim();
    const email = document.getElementById('gvEmail').value.trim();
    const status = document.getElementById('gvStatus').value;

    if(!code || !name){ alert('Vui lòng nhập Mã GV và Họ tên'); return; }

    try {
      if(id){
        const tr = [...document.querySelectorAll('#tableBody tr')].find(x => x.querySelector('.btnEdit')?.dataset.id === id);
        if(tr){
          tr.setAttribute('data-status', status);
          tr.children[1].textContent = code;
          tr.children[2].textContent = name;
          tr.children[3].textContent = dept || '-';
          tr.children[4].textContent = email || '-';
          const pillMap = {active:['Đang công tác','bg-emerald-50 text-emerald-700'], probation:['Thử việc','bg-amber-50 text-amber-700'], inactive:['Ngừng công tác','bg-slate-100 text-slate-700']};
          const [label, cls] = pillMap[status] || ['Khác','bg-slate-100 text-slate-700'];
          tr.children[5].querySelector('span').className = 'px-2 py-0.5 rounded-full text-xs ' + cls;
          tr.children[5].querySelector('span').textContent = label;
          const editBtn = tr.querySelector('.btnEdit');
          editBtn.dataset.code = code; editBtn.dataset.name = name; editBtn.dataset.dept = dept; editBtn.dataset.email = email; editBtn.dataset.status = status;
          // new fields
          editBtn.dataset.dob = document.getElementById('gvDob').value || '';
          editBtn.dataset.address = document.getElementById('gvAddress').value || '';
        }
      } else {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-slate-50';
        tr.setAttribute('data-status', status);
        const pillMap = {active:['Đang công tác','bg-emerald-50 text-emerald-700'], probation:['Thử việc','bg-amber-50 text-amber-700'], inactive:['Ngừng công tác','bg-slate-100 text-slate-700']};
        const [label, cls] = pillMap[status] || ['Khác','bg-slate-100 text-slate-700'];
        const newId = 'new-' + Date.now();
        tr.innerHTML = `
          <td class="px-4 py-3"><input type="checkbox" class="rowChk h-4 w-4"></td>
          <td class="px-4 py-3 font-mono">${code}</td>
          <td class="px-4 py-3 font-medium text-slate-800">${name}</td>
          <td class="px-4 py-3">${dept || '-'}</td>
          <td class="px-4 py-3">${email || '-'}</td>
          <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded-full text-xs ${cls}">${label}</span></td>
          <td class="px-4 py-3 text-right space-x-2">
            <button class="btnEdit px-2 py-1.5 rounded-lg border hover:bg-slate-50 text-indigo-600"
                    data-id="${newId}" data-code="${code}" data-name="${name}" data-dept="${dept}" data-email="${email}" data-status="${status}" data-dob="${''}" data-address="${''}">
              <i class="ph ph-pencil"></i>
            </button>
            <button class="btnDelete px-2 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600" data-id="${newId}">
              <i class="ph ph-trash"></i>
            </button>
          </td>`;
        document.getElementById('tableBody')?.prepend(tr);
      }
      closeModal();
    } catch (err) {
      console.error(err);
      alert('Không thể lưu. Vui lòng thử lại.');
    }
  });

  // Assign assistant modal behavior
  const assistantSession = document.getElementById('assistantSession');
  const assistantNameEl = document.getElementById('assistantName');
  const assistantEmailEl = document.getElementById('assistantEmail');
  const currentAssistantName = document.getElementById('currentAssistantName');
  const currentAssistantEmail = document.getElementById('currentAssistantEmail');

  function openAssignModal(){
    // hide other modal to avoid both showing at once
    modal?.classList.add('hidden');
    assignModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  }
  function closeAssignModal(){ assignModal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
  assignModal?.addEventListener('click',(e)=>{ const el = getEventElement(e); if(el && el.matches('[data-close]')) closeAssignModal(); });

  // Initialize session with server-provided assistant if present
  @if(isset($departmentAssistant) && $departmentAssistant)
    assistantNameEl.textContent = "{{ $departmentAssistant->fullname ?? $departmentAssistant->name ?? '—' }}";
    assistantEmailEl.textContent = "{{ $departmentAssistant->email ?? '—' }}";
    currentAssistantName.textContent = "{{ $departmentAssistant->fullname ?? $departmentAssistant->name ?? '—' }}";
    currentAssistantEmail.textContent = "{{ $departmentAssistant->email ?? '—' }}";
    const currPhone = document.getElementById('currentAssistantPhone');
    const currDept = document.getElementById('currentAssistantDept');
    if(currPhone) currPhone.textContent = "{{ $departmentAssistant->phone ?? ($departmentAssistant->user->phone ?? '—') }}";
    if(currDept) currDept.textContent = "{{ $departmentAssistant->department_name ?? ($departmentAssistant->department->name ?? '—') }}";
    assistantSession?.classList.remove('hidden');
  @endif

  // Click handler for 'Phân trợ lý khoa' — normalize event and open assign modal exclusively
  (function(){
    const btn = document.getElementById('btnAssignAssistant');
    btn?.addEventListener('click', (e)=>{
      e.preventDefault();
      e.stopPropagation();
      // reset select
      const sel = document.getElementById('assistantSelect');
      if(sel) sel.value = '';
      // show assign modal directly (safer) and trap errors
      try{
        if(assignModal) {
          assignModal.classList.remove('hidden');
          document.body.classList.add('overflow-hidden');
        } else {
          console.warn('assignModal element not found');
        }
        // focus the select for keyboard users
        setTimeout(()=>{ try{ sel?.focus(); }catch(err){ console.error(err); } }, 50);
      } catch (err) {
        console.error('Failed to open assign modal', err);
      }
    });
  })();

  document.getElementById('assistantSelect')?.addEventListener('change', (e)=>{
    const opt = e.target.selectedOptions[0];
    if(opt && opt.dataset){
      currentAssistantName.textContent = opt.dataset.name || '—';
      currentAssistantEmail.textContent = opt.dataset.email || '—';
      const phoneEl = document.getElementById('currentAssistantPhone');
      const deptEl = document.getElementById('currentAssistantDept');
      if(phoneEl) phoneEl.textContent = opt.dataset.phone ? ('SĐT: ' + opt.dataset.phone) : '—';
      if(deptEl) deptEl.textContent = opt.dataset.dept ? ('Bộ môn: ' + opt.dataset.dept) : '—';
    }
  });

  document.getElementById('btnAssignAssistantConfirm')?.addEventListener('click', async ()=>{
    const sel = document.getElementById('assistantSelect');
    const id = sel?.value;
    if(!id){ alert('Vui lòng chọn một giảng viên'); return; }
    // Call backend to persist assignment
    try {
      const url = '{{ route('web.admin.faculties.assign_assistant') }}';
      const resp = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ teacher_id: id })
      });
      const json = await resp.json();
      if(!resp.ok || !json.ok){
        console.error('Assign assistant error', json);
        alert(json.message || 'Không thể phân trợ lý.');
        return;
      }

      const name = json.assistant.fullname || (sel.selectedOptions[0]?.dataset?.name) || '—';
      const email = json.assistant.email || (sel.selectedOptions[0]?.dataset?.email) || '—';
      assistantNameEl.textContent = name;
      assistantEmailEl.textContent = email;
      currentAssistantName.textContent = name;
      currentAssistantEmail.textContent = email;
      const phone = json.assistant.phone || (sel.selectedOptions[0]?.dataset?.phone) || '—';
      const dept = json.assistant.department_name || (sel.selectedOptions[0]?.dataset?.dept) || '—';
      const currPhoneEl = document.getElementById('currentAssistantPhone');
      const currDeptEl = document.getElementById('currentAssistantDept');
      if(currPhoneEl) currPhoneEl.textContent = phone;
      if(currDeptEl) currDeptEl.textContent = dept;
      assistantSession?.classList.remove('hidden');
      closeAssignModal();
      // optionally show a brief success toast
      alert('Đã phân trợ lý khoa.');
    } catch (err) {
      console.error(err);
      alert('Lỗi khi liên hệ máy chủ. Vui lòng thử lại.');
    }
  });
</script>
</body>
</html>