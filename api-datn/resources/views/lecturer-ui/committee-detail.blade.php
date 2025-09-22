<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chi tiết hội đồng</title>
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
    $data_assignment_supervisors = $user->teacher->supervisor->assignment_supervisors ?? collect();
    $supervisorId = $user->teacher->supervisor->id ?? 0;
    $teacherId = $user->teacher->id ?? 0;
    $avatarUrl = $user->avatar_url
      ?? $user->profile_photo_url
      ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
  @endphp

<body class="bg-slate-50 text-slate-800">
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
        <button
          type="button"
          id="toggleThesisMenu"
          aria-controls="thesisSubmenu"
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
        <button id="toggleSidebar"
          class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i
            class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
      </div>
    </aside>

    <div class="flex-1 h-screen overflow-hidden flex flex-col">
      <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
        <div class="flex items-center gap-3 flex-1">
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Chi tiết hội đồng</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-round-detail.html" class="hover:underline text-slate-600">Chi tiết đợt</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Hội đồng</span>
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
        <div class="max-w-6xl mx-auto space-y-4">
          <div class="flex items-center justify-between">
            <div class="text-sm text-slate-600">Mã hội đồng: .{{ $council->code }}</div>
            <a href="committees.html" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Danh sách hội đồng</a>
          </div>

          <!-- Thông tin đợt đồ án -->
          <section class="bg-white rounded-xl border border-slate-200 p-4">
            <h2 class="font-semibold text-lg mb-2">Thông tin đợt đồ án</h2>
            @php
              $council_code = $council->code;
              $term = $council->project_term ?? null;
              $termName = $term->stage ?? 'Chưa có';
              $year = $term->academy_year->year_name ?? 'N/A';
              $semester = $term->stage ?? 'N/A';
              $start = $term->start_date ? \Carbon\Carbon::parse($term->start_date)->format('d/m/Y') : 'N/A';
              $end = $term->end_date ? \Carbon\Carbon::parse($term->end_date)->format('d/m/Y') : 'N/A';
            @endphp
            <div class="text-slate-700 text-sm space-y-1">
              <div><strong>Đợt:</strong> {{ $termName }}</div>
              <div><strong>Năm học:</strong> {{ $year }}</div>
              <div><strong>Học kỳ:</strong> {{ $semester }}</div>
              <div><strong>Thời gian:</strong> {{ $start }} – {{ $end }}</div>
            </div>
          </section>

          <section class="bg-white border rounded-xl p-6 space-y-5">
            <!-- Header -->
            <div class="flex items-center justify-between">
              <h2 class="font-semibold text-lg">Thông tin hội đồng</h2>
              <span class="text-xs text-slate-500">Vị trí phòng bảo vệ đồ án</span>
            </div>

            <!-- Thông tin chính -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Tên hội đồng -->
              <div>
                <h3 class="text-sm font-semibold text-slate-700">Tên hội đồng</h3>
                <p class="mt-1 text-slate-600">{{ $council->name }} ({{ $council->code }})</p>
              </div>

              <!-- Trạng thái -->
              <div>
                <h3 class="text-sm font-semibold text-slate-700">Trạng thái</h3>
                <p class="mt-1">
                  <span class="px-2 py-1 text-xs rounded-full
                    {{ $council->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ ucfirst($council->status) }}
                  </span>
                </p>
              </div>

              <!-- Ngày -->
              <div>
                <h3 class="text-sm font-semibold text-slate-700">Ngày tổ chức</h3>
                <p class="mt-1 text-slate-600">{{ $council->date ?? '-' }}</p>
              </div>

              <!-- Phòng -->
              <div>
                <h3 class="text-sm font-semibold text-slate-700">Phòng bảo vệ</h3>
                <p class="mt-1 text-slate-600">{{ $council->address ?? '-' }}</p>
              </div>

              <!-- Khoa -->
              <div>
                <h3 class="text-sm font-semibold text-slate-700">Khoa / Viện</h3>
                <p class="mt-1 text-slate-600">{{ $council->department->name ?? '-' }}</p>
              </div>

              <!-- Đợt đồ án -->
              <div>
                <h3 class="text-sm font-semibold text-slate-700">Đợt đồ án</h3>
                <p class="mt-1 text-slate-600">{{ $council->project_term->name ?? '-' }}</p>
              </div>
            </div>

            <!-- Mô tả -->
            <div>
              <h3 class="text-sm font-semibold text-slate-700">Mô tả</h3>
              <p class="mt-1 text-slate-600">{{ $council->description ?? 'Không có mô tả' }}</p>
            </div>

            <!-- Sơ đồ phòng -->
            <div>
              <h3 class="text-sm font-semibold text-slate-700">Sơ đồ phòng</h3>
              <div class="mt-2 h-40 bg-slate-100 border border-dashed rounded grid place-items-center text-slate-500 text-sm">
                Sơ đồ phòng (placeholder)
              </div>
            </div>
          </section>
          @php
          $council_members = $council->council_members->sortByDesc('role') ?? collect();
          @endphp
          <section class="bg-white border rounded-xl p-4">
            <div class="flex items-center justify-between mb-2">
              <h3 class="font-semibold">Thành viên hội đồng</h3>
              <div class="text-xs text-slate-500">Xem thông tin chi tiết từng thành viên</div>
            </div>
            <div id="members" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
              @foreach ($council_members as $council_member)
              @php
              $liatRole = [
                '5' => 'Chủ tịch',
                '4' => 'Thư ký',
                '3' => 'Ủy viên 1',
                '2' => 'Ủy viên 2',
                '1' => 'Ủy viên 3',];
                $role = $liatRole[$council_member->role] ?? 'Thành viên';
                $name = $council_member->supervisor->teacher->user->fullname ?? 'N/A';
                $email = $council_member->supervisor->teacher->user->email ?? 'N/A';
                $phone = $council_member->supervisor->teacher->user->phone ?? 'N/A';
              @endphp
              <div class="border rounded-lg p-3 bg-white">
                <div class="text-slate-500 text-sm">{{ $role }}</div>
                <div class="font-medium">{{ $name }}</div>
                <div class="text-xs text-slate-600">{{ $email }} • {{ $phone }}</div>
                <div class="mt-2">
                  <button data-midx="${idx}" class="px-3 py-1.5 border border-slate-200 rounded text-sm"><i class="ph ph-user"></i> Xem thông tin</button>
                </div>
              </div>
              @endforeach
            </div>
          </section>

          <section class="bg-white border rounded-xl p-4">
            <div class="flex items-center justify-between mb-2">
              <h3 class="font-semibold">Sinh viên thuộc hội đồng</h3>
              <div class="text-xs text-slate-500">Danh sách sinh viên bảo vệ tại hội đồng này</div>
            </div>

            @php
            $council_projects = $council->council_projects ?? collect();
            @endphp
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500 border-b">
                    <th class="py-3 px-3">Sinh viên</th>
                    <th class="py-3 px-3">MSSV</th>
                    <th class="py-3 px-3">Đề tài</th>
                    <th class="py-3 px-3">Giảng viên hướng dẫn</th>
                    <th class="py-3 px-3">Thời gian</th>
                    <th class="py-3 px-3">Hành động</th>
                  </tr>
                </thead>
                <tbody id="studentRows">
                  @foreach ($council_projects as $council_project)
                    @php
                    $name = $council_project->assignment->student->user->fullname?? 'N/A';
                    $id = $council_project->assignment->student->student_code;
                    $topic = $council_project->assignment->project->name?? 'N/A';
                    $time = $council_project->time?? 'N/A';
                    @endphp
                    <tr class='border-b hover:bg-slate-50'>
                      <td class='py-3 px-3'><a class='text-blue-600 hover:underline' href='supervised-student-detail.html?id=${encodeURIComponent(s.id)}&name=${encodeURIComponent(s.name)}'>{{ $name }}</a></td>
                      <td class='py-3 px-3'>{{ $id }}</td>
                      <td class='py-3 px-3'>{{ $topic }}</td>
                      <td class='py-3 px-3'>
                        @php
                          $assignment_supervisors = $council_project->assignment->assignment_supervisors ?? collect();
                        @endphp
                        @foreach ($assignment_supervisors as $assignment_supervisor)
                          <div>{{ $assignment_supervisor->supervisor->teacher->user->fullname }}</div>
                        @endforeach
                      </td>
                      <td class='py-3 px-3'>{{ $time }}</td>
                      <td class='py-3 px-3'>
                        <div class="flex items-center gap-1">
                          <a class='px-2 py-1 border border-slate-200 rounded text-xs hover:bg-slate-50' href='supervised-student-detail.html?id=${encodeURIComponent(s.id)}&name=${encodeURIComponent(s.name)}'>Xem SV</a>
                          <a class='px-2 py-1 border border-slate-200 rounded text-xs hover:bg-slate-50' href='defense-student.html?studentId=${encodeURIComponent(s.id)}&name=${encodeURIComponent(s.name)}&committeeId=${encodeURIComponent(c.id)}'>Chấm bảo vệ</a>
                        </div>
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
    // Sidebar/profile wiring
    (function(){
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const h=document.querySelector('header'); const m=document.querySelector('main');
        if(c){ html.classList.add('sidebar-collapsed'); h.classList.add('md:left-[72px]'); h.classList.remove('md:left-[260px]'); m.classList.add('md:pl-[72px]') }
        else { html.classList.remove('sidebar-collapsed'); h.classList.remove('md:left-[72px]'); h.classList.add('md:left-[260px]'); m.classList.remove('md:pl-[72px]')}
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
      const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    })();

    function qs(k){ const p = new URLSearchParams(location.search); return p.get(k) || ''; }
    const cid = qs('id') || 'CNTT-01';
    document.getElementById('cId').textContent = cid;

    // Mock data for committees, members, students
    const DATA = {
      committees: {
        'CNTT-01': {
          id: 'CNTT-01', name: 'Hội đồng CNTT-01', date: '20/08/2025', time: '08:00', room: 'P.A203',
          members: [
            { role: 'Chủ tịch', name: 'PGS.TS. Trần Văn B', email: 'b@uni.edu', phone: '0900 001 001' },
            { role: 'Ủy viên', name: 'TS. Lê Thị C', email: 'c@uni.edu', phone: '0900 001 002' },
            { role: 'Ủy viên', name: 'TS. Phạm Văn D', email: 'd@uni.edu', phone: '0900 001 003' },
            { role: 'Thư ký', name: 'ThS. Nguyễn Văn G', email: 'g@uni.edu', phone: '0900 001 004' },
            { role: 'Phản biện', name: 'TS. Nguyễn Thị E', email: 'e@uni.edu', phone: '0900 001 005' }
          ],
          students: [
            { id: '20210001', name: 'Nguyễn Văn A', topic: 'Hệ thống quản lý thư viện', time: '08:00' },
            { id: '20210003', name: 'Lê Văn C', topic: 'Hệ thống đặt lịch khám', time: '08:45' }
          ]
        },
        'CNTT-02': {
          id: 'CNTT-02', name: 'Hội đồng CNTT-02', date: '20/08/2025', time: '09:30', room: 'P.A204',
          members: [
            { role: 'Chủ tịch', name: 'TS. Phạm Văn D', email: 'd@uni.edu', phone: '0900 002 001' },
            { role: 'Ủy viên', name: 'TS. Lê Thị C', email: 'c@uni.edu', phone: '0900 002 002' },
            { role: 'Ủy viên', name: 'ThS. Trần Thị F', email: 'f@uni.edu', phone: '0900 002 003' },
            { role: 'Thư ký', name: 'ThS. Nguyễn Văn G', email: 'g@uni.edu', phone: '0900 002 004' },
            { role: 'Phản biện', name: 'TS. Nguyễn Thị E', email: 'e@uni.edu', phone: '0900 002 005' }
          ],
          students: [
            { id: '20210002', name: 'Trần Thị B', topic: 'Ứng dụng quản lý công việc', time: '09:30' }
          ]
        }
      }
    };

    const c = DATA.committees[cid] || DATA.committees['CNTT-01'];
    document.getElementById('cName').textContent = c.name;
    document.getElementById('cDate').textContent = c.date;
    document.getElementById('cTime').textContent = c.time;
    document.getElementById('cRoom').textContent = c.room;

    // Members
    const membersBox = document.getElementById('members');
    membersBox.innerHTML = c.members.map((m,idx)=>`
      <div class="border rounded-lg p-3 bg-white">
        <div class="text-slate-500 text-sm">${m.role}</div>
        <div class="font-medium">${m.name}</div>
        <div class="text-xs text-slate-600">${m.email} • ${m.phone}</div>
        <div class="mt-2">
          <button data-midx="${idx}" class="px-3 py-1.5 border border-slate-200 rounded text-sm"><i class="ph ph-user"></i> Xem thông tin</button>
        </div>
      </div>
    `).join('');

    // Simple modal for member info
    function modal(title, body){
      const w = document.createElement('div');
      w.className = 'fixed inset-0 z-50 flex items-end sm:items-center justify-center';
      w.innerHTML = `
        <div class='absolute inset-0 bg-black/40' data-close></div>
        <div class='relative bg-white w-full sm:max-w-md rounded-t-2xl sm:rounded-2xl shadow-lg p-4 m-0 sm:m-4'>
          <div class='flex items-center justify-between mb-3'>
            <h3 class='font-semibold'>${title}</h3>
            <button data-close class='text-slate-500'><i class='ph ph-x'></i></button>
          </div>
          <div class='text-sm'>${body}</div>
          <div class='mt-3 flex justify-end'><button data-close class='px-3 py-1.5 bg-blue-600 text-white rounded text-sm'>Đóng</button></div>
        </div>`;
      document.body.appendChild(w); const close=()=>w.remove();
      w.addEventListener('click', e=>{ if(e.target.matches('[data-close]')) close(); });
      return { close };
    }

    document.querySelectorAll('[data-midx]').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const i = parseInt(btn.getAttribute('data-midx'));
        const m = c.members[i];
        modal('Thông tin thành viên', `
          <div class='space-y-1'>
            <div><span class='text-slate-500'>Chức vụ:</span> ${m.role}</div>
            <div><span class='text-slate-500'>Họ tên:</span> ${m.name}</div>
            <div><span class='text-slate-500'>Email:</span> <a class='text-blue-600 hover:underline' href='mailto:${m.email}'>${m.email}</a></div>
            <div><span class='text-slate-500'>Điện thoại:</span> ${m.phone}</div>
          </div>
        `);
      });
    });

    // Students table
    const sRows = document.getElementById('studentRows');
    sRows.innerHTML = c.students.map(s=>`
      <tr class='border-b hover:bg-slate-50'>
        <td class='py-3 px-3'><a class='text-blue-600 hover:underline' href='supervised-student-detail.html?id=${encodeURIComponent(s.id)}&name=${encodeURIComponent(s.name)}'>${s.name}</a></td>
        <td class='py-3 px-3'>${s.id}</td>
        <td class='py-3 px-3'>${s.topic}</td>
        <td class='py-3 px-3'>${c.date} • ${s.time}</td>
        <td class='py-3 px-3'>
          <div class="flex items-center gap-1">
            <a class='px-2 py-1 border border-slate-200 rounded text-xs hover:bg-slate-50' href='supervised-student-detail.html?id=${encodeURIComponent(s.id)}&name=${encodeURIComponent(s.name)}'>Xem SV</a>
            <a class='px-2 py-1 border border-slate-200 rounded text-xs hover:bg-slate-50' href='defense-student.html?studentId=${encodeURIComponent(s.id)}&name=${encodeURIComponent(s.name)}&committeeId=${encodeURIComponent(c.id)}'>Chấm bảo vệ</a>
          </div>
        </td>
      </tr>
    `).join('');
  </script>
</body>
</html>
