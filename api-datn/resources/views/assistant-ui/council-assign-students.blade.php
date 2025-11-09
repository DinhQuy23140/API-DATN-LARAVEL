<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Phân sinh viên vào hội đồng</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <style>
    body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial}
    .sidebar{width:260px}
  </style>
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
      $avatarUrl = $user->avatar_url
        ?? $user->profile_photo_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
    @endphp
<body class="bg-slate-50 text-slate-800">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
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
        <a href="{{ route('web.assistant.assign_head') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Gán trưởng bộ môn</span></a>

          <div class="graduation-item">
            <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button bg-slate-100 font-semibold">
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

    <!-- Content -->
    <div class="flex-1 h-screen overflow-hidden flex flex-col md:pl-[260px]">
      <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6">
        <div class="flex-1">
          <h1 class="text-lg md:text-xl font-semibold">Phân sinh viên vào hội đồng</h1>
          <div class="text-xs text-slate-500 mt-0.5">Đợt: 2025-Q3</div>
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

      <main class="flex-1 overflow-y-auto p-4 md:p-6">
        <div class="max-w-7xl mx-auto space-y-6">
          <!-- Thông tin đợt -->
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between">
              <div>
                <div class="text-sm text-slate-500">Mã đợt: <span class="font-medium text-slate-700">2025Q3</span></div>
                <h2 class="font-semibold text-lg mt-1">Đợt 1 năm học 2025-2026</h2>
                <div class="text-sm text-slate-600">2025-08-01 - 2025-10-31</div>
              </div>
              <div class="hidden md:flex items-center gap-2 text-sm">
                <span class="px-2 py-1 rounded-full bg-blue-50 text-blue-700">Hội đồng: {{ $projectTerm->councils->count() }}</span>
                <span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-700">Sinh viên: {{ $assignments->count() }}</span>
              </div>
            </div>
          </section>

        <!-- Hai bảng ngang -->
        <!-- Bộ lọc bộ môn -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <div class="flex items-center gap-2 text-slate-700 font-semibold">
            <i class="ph ph-buildings text-lg text-blue-500"></i>
            <span>Lọc theo bộ môn</span>
          </div>
          <div class="relative w-full sm:w-64">
            <i class="ph ph-funnel absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <select id="departmentFilter"
              class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 bg-white text-sm text-slate-700
                    focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 appearance-none">
              <option value="">-- Tất cả bộ môn --</option>
              @foreach ($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- Hai bảng chính -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Bảng hội đồng -->
          <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <!-- Header -->
            <div class="p-4 border-b flex flex-col md:flex-row md:items-center md:justify-between gap-3">
              <h3 class="font-semibold text-slate-700">Danh sách hội đồng</h3>
              <div class="flex items-center gap-3 w-full md:w-auto">
                <div class="relative flex-1 md:flex-none">
                  <input id="councilSearch"
                    class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm"
                    placeholder="Tìm hội đồng (mã/tên/ngày/phòng)" />
                  <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
                <div class="hidden md:block text-sm text-slate-500"></div>
              </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full table-auto text-sm">
                <thead class="bg-slate-50">
                  <tr class="text-left text-slate-600 border-b">
                    <th class="py-3 px-4 w-10"><input type="checkbox" disabled /></th>
                    <th class="py-3 px-4">Mã</th>
                    <th class="py-3 px-4">Tên</th>
                    <th class="py-3 px-4 text-right">Số sinh viên</th>
                  </tr>
                </thead>
                @php
                  $councils = $projectTerm->councils ?? [];
                @endphp

                <tbody id="councilTbody" class="divide-y divide-slate-200">
                  @foreach ($councils as $council)
                    @php
                      $code = $council->code ?? 'NA';
                      $name = $council->name ?? 'NA';
                      $date = $council->date ?? 'NA';
                      $room = $council->address ?? 'NA';
                      $count = $council->council_projects?->count() ?? 'NA';
                      $department_id = $council->department->id ?? '';
                    @endphp
                    <tr class="hover:bg-slate-50" data-council-id="{{ $council->id }}" data-department-id="{{ $department_id }}">
                      <td class="py-3 px-4"><input type="checkbox" class="council-check" value="{{ $council->id }}" /></td>
                      <td class="py-3 px-4 font-medium text-slate-700">{{ $code }}</td>
                      <td class="py-3 px-4">{{ $name }}</td>
                      <td class="py-3 px-4 text-right text-slate-600">{{ $count }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <!-- Footer -->
            <div class="p-4 border-t flex items-center justify-end gap-2">
              <button id="btnClearCouncil"
                class="px-3 py-1.5 rounded-lg border text-sm text-slate-600 hover:bg-slate-50 flex items-center gap-1">
                <i class="ph ph-x"></i> Bỏ chọn
              </button>
              <button
                class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm flex items-center gap-1">
                <i class="ph ph-check"></i> Chọn hội đồng
              </button>
            </div>
          </div>

          <!-- Bảng sinh viên -->
          <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <!-- Header -->
            <div class="p-4 border-b flex flex-col md:flex-row md:items-center md:justify-between gap-3">
              <h3 class="font-semibold text-slate-700">Danh sách sinh viên</h3>
              <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full md:w-auto">
                <div class="relative flex-1 sm:flex-none hidden lg:block">
                  <input id="studentSearch"
                    class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600 text-sm"
                    placeholder="Tìm SV (mã, tên, lớp, ngành)" />
                  <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
                <div class="flex gap-2">
                  <button id="btnCancelStudents"
                    class="px-3 py-1.5 rounded-lg border text-sm text-slate-600 hover:bg-slate-50 flex items-center gap-1">
                    <i class="ph ph-arrow-u-up-left"></i> Hủy
                  </button>
                  <button id="btnAssignStudents"
                    class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm flex items-center gap-1">
                    <i class="ph ph-arrow-right"></i> Phân sinh viên
                  </button>
                </div>
              </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full table-auto text-sm">
                <thead class="bg-slate-50">
                  <tr class="text-left text-slate-600 border-b">
                    <th class="py-3 px-4 w-10"><input type="checkbox" /></th>
                    <th class="py-3 px-4">Mã SV</th>
                    <th class="py-3 px-4">Họ tên</th>
                    <th class="py-3 px-4">Đề tài</th>
                    <th class="py-3 px-4">GVHD</th>
                  </tr>
                </thead>
                <tbody id="studentTbody" class="divide-y divide-slate-200">
                  @foreach ($assignments as $assignment)
                    @php
                      $assignment_id = $assignment->id;
                      $student_code = $assignment->student->student_code ?? 'NA';
                      $student_name = $assignment->student->user->fullname ?? 'NA';
                      $topic = $assignment->project?->name ?? 'Chưa có đề tài';
                      $assignment_supervisors = $assignment->assignment_supervisors->where('status', 'accepted') ?? [];
                      $department_id = $assignment->student->marjor->department->id ?? '';
                    @endphp
                    <tr class="hover:bg-slate-50" data-assignment-id="{{ $assignment_id }}" data-department-id="{{ $department_id }}">
                      <td class="py-3 px-4"><input type="checkbox" class="student-check" value="{{ $assignment_id }}" /></td>
                      <td class="py-3 px-4 font-medium text-slate-700">{{ $student_code }}</td>
                      <td class="py-3 px-4">{{ $student_name }}</td>
                      <td class="py-3 px-4">{{ $topic }}</td>
                      <td class="py-3 px-4">
                        @if ($assignment_supervisors->count() > 0)
                          @foreach ($assignment_supervisors as $as)
                            @php
                              $supervisor = $as->supervisor;
                              $teacher = $supervisor->teacher ?? null;
                              $teacher_name = $teacher?->user?->fullname ?? 'NA';
                            @endphp
                            <div class="text-slate-700">{{ $teacher_name }}</div>
                          @endforeach
                        @else
                          <div class="text-slate-500 italic">Chưa có GVHD</div>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <!-- Footer -->
            <div class="p-4 border-t text-xs text-slate-500">
              Chọn hội đồng ở bảng trái, chọn sinh viên ở bảng phải, sau đó bấm
              <span class="font-medium text-emerald-600">“Gán vào hội đồng đã chọn”</span>.
            </div>
          </div>
        </section>

        </div>
      </main>
    </div>
  </div>
<script>
  // Combined filters: text search + department filter
  const councilSearchInput = document.getElementById('councilSearch');
  const studentSearchInput = document.getElementById('studentSearch');
  const departmentFilter = document.getElementById('departmentFilter');

  function applyFilters() {
    const councilQ = (councilSearchInput?.value || '').trim().toLowerCase();
    const studentQ = (studentSearchInput?.value || '').trim().toLowerCase();
    const dept = (departmentFilter?.value || '').toString();

    // filter councils
    Array.from(document.querySelectorAll('#councilTbody tr')).forEach(tr => {
      const text = tr.innerText.toLowerCase();
      const rowDept = (tr.getAttribute('data-department-id') || '').toString();
      const matchesSearch = councilQ === '' || text.includes(councilQ);
      const matchesDept = dept === '' || rowDept === dept;
      tr.style.display = (matchesSearch && matchesDept) ? '' : 'none';
    });

    // filter students
    Array.from(document.querySelectorAll('#studentTbody tr')).forEach(tr => {
      const text = tr.innerText.toLowerCase();
      const rowDept = (tr.getAttribute('data-department-id') || '').toString();
      const matchesSearch = studentQ === '' || text.includes(studentQ);
      const matchesDept = dept === '' || rowDept === dept;
      tr.style.display = (matchesSearch && matchesDept) ? '' : 'none';
    });
  }

  // Wire inputs
  councilSearchInput?.addEventListener('input', applyFilters);
  studentSearchInput?.addEventListener('input', applyFilters);
  departmentFilter?.addEventListener('change', applyFilters);

  // Run once on load
  applyFilters();

// Giới hạn chỉ chọn 1 hội đồng
document.querySelectorAll('.council-check').forEach(cb => {
  cb.addEventListener('change', () => {
    if (cb.checked) {
      document.querySelectorAll('.council-check').forEach(other => {
        if (other !== cb) other.checked = false;
      });
    }
  });
});

function getCheckedCouncilId() {
  const cb = document.querySelector('.council-check:checked');
  return cb ? cb.value : null;
}
function getCheckedStudentIds() {
  return Array.from(document.querySelectorAll('.student-check:checked'))
    .map(x => x.value).filter(Boolean);
}

// Bỏ chọn: clear checkbox hội đồng
document.getElementById('btnClearCouncil')?.addEventListener('click', () => {
  document.querySelectorAll('.council-check').forEach(cb => cb.checked = false);
});
// Hủy: clear checkbox sinh viên
document.getElementById('btnCancelStudents')?.addEventListener('click', () => {
  document.querySelectorAll('.student-check').forEach(cb => cb.checked = false);
});

// Gán sinh viên -> gọi API tạo council_projects (council_member_id = null)
document.getElementById('btnAssignStudents')?.addEventListener('click', async () => {
  const btn = document.getElementById('btnAssignStudents');
  const councilId = getCheckedCouncilId();
  const assignmentIds = getCheckedStudentIds();
  if (!councilId) { alert('Vui lòng chọn một hội đồng.'); return; }
  if (!assignmentIds.length) { alert('Vui lòng chọn ít nhất một sinh viên.'); return; }

  const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const urlTpl = `{{ route('web.assistant.councils.assign_students', ['council' => 0]) }}`;
  const url = urlTpl.replace('/0','/'+councilId);

  // Loading state
  const oldText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> Đang gán...';

  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': token,'X-Requested-With':'XMLHttpRequest'},
      body: JSON.stringify({ assignment_ids: assignmentIds })
    });
    const data = await res.json().catch(()=> ({}));
    if (!res.ok || data.ok === false) {
      alert(data.message || 'Gán sinh viên thất bại.');
      btn.disabled = false;
      btn.innerHTML = oldText;
      return;
    }

    // Thành công: reload trang để đồng bộ lại 2 bảng và các chỉ số
    // (tránh sai lệch khi upsert không tăng số lượng do trùng)
    location.reload();

  } catch (e) {
    alert('Lỗi mạng, vui lòng thử lại.');
    btn.disabled = false;
    btn.innerHTML = oldText;
  }
});

  // profile dropdown
  const profileBtn=document.getElementById('profileBtn');
  const profileMenu=document.getElementById('profileMenu');
  profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
  document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
</script>
</body>
</html>