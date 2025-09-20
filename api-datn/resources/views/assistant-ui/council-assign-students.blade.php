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
<body class="bg-slate-50 text-slate-800">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-buildings"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Assistant</div>
          <div class="text-xs text-slate-500">Quản trị khoa</div>
        </div>
      </div>
      <nav class="flex-1 overflow-y-auto p-3">
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span class="sidebar-label">Bảng điều khiển</span></a>
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-buildings"></i><span class="sidebar-label">Bộ môn</span></a>
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-book-open-text"></i><span class="sidebar-label">Ngành</span></a>
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-chalkboard-teacher"></i><span class="sidebar-label">Giảng viên</span></a>
        <div class="sidebar-label text-xs uppercase text-slate-400 px-3 mt-3">Học phần tốt nghiệp</div>
        <div class="pl-6">
          <a href="#" class="block px-3 py-2 rounded hover:bg-slate-100">Thực tập tốt nghiệp</a>
          <a href="#" class="block px-3 py-2 rounded hover:bg-slate-100 bg-slate-100 font-semibold" aria-current="page">Đồ án tốt nghiệp</a>
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
                    <div class="hidden md:block text-sm text-slate-500">Chọn 1 hội đồng để gán</div>
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
                        <th class="py-3 px-4">Ngày</th>
                        <th class="py-3 px-4">Phòng</th>
                        <th class="py-3 px-4 text-right">SV đã gán</th>
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
                        @endphp
                        <tr class="hover:bg-slate-50" data-council-id="{{ $council->id }}">
                            <td class="py-3 px-4"><input type="checkbox" class="council-check" value="{{ $council->id }}" /></td>
                            <td class="py-3 px-4 font-medium text-slate-700">{{ $code }}</td>
                            <td class="py-3 px-4">{{ $name }}</td>
                            <td class="py-3 px-4">{{ $date }}</td>
                            <td class="py-3 px-4">{{ $room }}</td>
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
                                 <i class="ph ph-arrow-right"></i> Gán sinh viên
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
                            $assignment_supervisors = $assignment->assignment_supervisors ?? [];
                          @endphp
                          <tr class="hover:bg-slate-50" data-assignment-id="{{ $assignment_id }}">
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
  // Lọc bảng theo từ khóa (không render lại DOM)
  function attachSearch(inputId, tbodyId) {
    const input = document.getElementById(inputId);
    const tbody = document.getElementById(tbodyId);
    if (!input || !tbody) return;
    input.addEventListener('input', () => {
      const q = input.value.trim().toLowerCase();
      Array.from(tbody.querySelectorAll('tr')).forEach(tr => {
        const text = tr.innerText.toLowerCase();
        tr.style.display = text.includes(q) ? '' : 'none';
      });
    });
  }
  attachSearch('councilSearch', 'councilTbody');
  attachSearch('studentSearch', 'studentTbody');

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
</script>
</body>
</html>