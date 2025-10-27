<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Thông tin giảng viên phản biện kín - Trưởng bộ môn</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
 body{font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial;}
 .sidebar-collapsed .sidebar-label{display:none;}
 .sidebar-collapsed .sidebar{width:72px;}
 .sidebar{width:260px;}
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
  <!-- Sidebar -->
    <aside class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all"
      id="sidebar">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i
            class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Lecturer</div>
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
          <a href="{{ route('web.teacher.thesis_rounds', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold {{ $isThesisRoundsActive ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
            @if($isThesisRoundsActive) aria-current="page" @endif>
            <i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span>
          </a>
        </div>
      </nav>
      <div class="p-3 border-t border-slate-200">
        <button
          class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"
          id="toggleSidebar"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
      </div>
    </aside>

  <!-- Main -->
  <div class="flex-1 h-screen flex flex-col">
    <!-- Header -->
    <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
      <div class="flex items-center gap-3 flex-1">
        <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
        <div>
          <h1 class="text-lg md:text-xl font-semibold">Giảng viên phản biện kín</h1>
          <nav class="text-xs text-slate-500 mt-0.5">
            <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
            <span class="mx-1">/</span>
            <a href="blind-review-assignments.html" class="hover:underline text-slate-600">Phản biện kín</a>
            <span class="mx-1">/</span>
            <span class="text-slate-500" id="breadcrumbLect">GV001</span>
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
          <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Hồ sơ</a>
          <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
        </div>
      </div>
    </header>

    <!-- Content -->
    <main class="flex-1 overflow-y-auto max-w-7xl mx-auto px-4 md:px-6 py-6 space-y-6">
      <!-- Thông tin đợt đồ án -->
      <section class="bg-white border border-slate-200 rounded-xl p-4 md:p-5">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
          <div>
            <div class="text-sm text-slate-500">Đợt đồ án</div>
            <div class="text-base md:text-lg font-semibold">Đợt {{ $rows->stage }} - Năm học {{ $rows->academy_year->year_name }}</div>
            <div class="mt-1 text-sm text-slate-600 flex items-center gap-2">
              <i class="ph ph-calendar-dots text-slate-500"></i>
              <span>Thời gian: {{ \Carbon\Carbon::parse($rows->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($rows->end_date)->format('d/m/Y') }}</span>
            </div>
          </div>
          <div>
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
              @php
                $listStatus = ['inactive' => 'Sắp diễn ra', 'active' => 'Đang diễn ra', 'ended' => 'Đã kết thúc'];
                $status = $listStatus[$rows->status] ?? 'inactive';
              @endphp
              <i class="ph ph-circle"></i> {{ $status }}
            </span>
          </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
          <div class="rounded-lg border border-slate-200 p-3">
            <div class="text-xs text-slate-500">Giảng viên</div>
            <div class="mt-1 text-lg font-semibold">12</div>
          </div>
          <div class="rounded-lg border border-slate-200 p-3">
            <div class="text-xs text-slate-500">SV chưa có GVHD</div>
            <div class="mt-1 text-lg font-semibold">5</div>
          </div>
          <div class="rounded-lg border border-slate-200 p-3 col-span-2">
            <div class="flex items-center justify-between text-xs text-slate-500">
              <span>Chỉ tiêu sử dụng</span>
              <span>45/60</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2 mt-1.5">
              <div class="h-2 rounded-full bg-emerald-600" style="width: 75%"></div>
            </div>
          </div>
        </div>
      </section>
              

      <!-- Thanh hành động chính -->
      <div class="flex items-center justify-between bg-white border border-slate-200 rounded-xl px-4 py-3">
        <div class="text-sm text-slate-600">
          Chọn 1 giảng viên và các sinh viên cần phân công, sau đó bấm nút.
        </div>
        <button id="btnAssignMain" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">
          <i class="ph ph-user-switch"></i>
          Phân công sinh viên
        </button>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Bảng giảng viên -->
        <section class="bg-white rounded-xl border border-slate-200">
          <div class="p-4 border-b flex items-center justify-between">
            <div>
              <div class="font-semibold">Giảng viên</div>
              <div class="text-xs text-slate-500">Chọn 1 giảng viên để phân công</div>
            </div>
            <div class="relative">
              <input class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm" placeholder="Tìm theo tên, email">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
          </div>
          <div class="table-wrap">
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-500 border-b">
                  <th class="py-2 px-3 w-10">Chọn</th>
                  <th class="py-2 px-3">Giảng viên</th>
                  <th class="py-2 px-3">Chuyên môn</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $listSupervisors = $rows->supervisors ?? collect();
                @endphp
                @foreach ($listSupervisors as $supervisor)
                <tr class="border-b hover:bg-slate-50" data-supervisor-id="{{ $supervisor->id }}">
                  <td class="py-2 px-3"><input type="radio" name="teacher" class="teacher-radio"></td>
                  <td class="py-2 px-3">
                    <div class="font-medium">{{ $supervisor->teacher->user->fullname }}</div>
                    <div class="text-xs text-slate-500">{{ $supervisor->teacher->user->email }} • {{ $supervisor->teacher->position }}</div>
                  </td>
                  <td class="py-2 px-3">{{ $supervisor->teacher->degree }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </section>

        <!-- Bảng sinh viên chưa có GVHD -->
        <section class="bg-white rounded-xl border border-slate-200">
          <div class="p-4 border-b flex items-center justify-between">
            <div>
              <div class="font-semibold">Đề cương sinh viên chưa phân phản biện</div>
              <div class="text-xs text-slate-500">Chọn 1 hoặc nhiều đề cương</div>
            </div>
            <div class="relative">
              <input class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 text-sm" placeholder="Tìm theo tên, mã SV">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
          </div>
          <div class="overflow-x-auto rounded-lg border border-slate-200">
            <table class="w-full text-sm border-collapse">
              <thead>
                <tr class="bg-slate-100 text-slate-600">
                  <th class="py-3 px-3 w-10 text-center">
                    <input type="checkbox" class="rounded border-slate-300">
                  </th>
                  <th class="py-3 px-3 text-left">Sinh viên</th>
                  <th class="py-3 px-3 text-left">Email</th>
                  <th class="py-3 px-3 text-left">Đề tài</th>
                </tr>
              </thead>
              @php
                $listAssignments = $assignedAssignments ?? collect();
              @endphp
              <tbody class="divide-y divide-slate-200">
                @foreach ($listAssignments as $assignment)
                  @if ($assignment->counter_argument_id == null)
                    @php
                      $fullname = $assignment->student->user->fullname ?? '';
                      $studentCode = $assignment->student->student_code ?? '';
                      $email = $assignment->student->user->email ?? '';
                      $topic = $assignment->project->project_name ?? 'Chưa có đề tài';
                    @endphp
                    <tr class="hover:bg-blue-50 odd:bg-white even:bg-slate-50 transition-colors" data-assignment-id="{{ $assignment->id }}">
                      <td class="py-2 px-3 text-center">
                        <input type="checkbox" class="rounded border-slate-300 stuChk">
                      </td>
                      <td class="py-2 px-3">
                        <div class="font-medium text-slate-800">{{ $fullname }}</div>
                        <div class="text-xs text-slate-500">{{ $studentCode }}</div>
                      </td>
                      <td class="py-2 px-3 text-slate-700">{{ $email }}</td>
                      <td class="py-2 px-3 text-right">
                        <span class="inline-block max-w-xs truncate" title="{{ $topic }}">
                          {{ $topic }}
                        </span>
                      </td>
                    </tr>
                  @endif
                @endforeach
              </tbody>
            </table>
          </div>
        </section>
      </div>
    </main>
     <!-- Modal xác nhận -->
     <div id="assignModal" class="fixed inset-0 z-[60] hidden">
       <div class="absolute inset-0 bg-black/40"></div>
       <div class="relative mx-auto mt-28 w-[92%] max-w-md bg-white border border-slate-200 rounded-xl shadow-xl">
         <div class="p-4 border-b">
           <div class="font-semibold">Xác nhận phân công phản biện</div>
         </div>
         <div class="p-4 text-sm text-slate-700">
           <p>Giảng viên: <span id="mdlTeacher" class="font-medium">—</span></p>
           <p class="mt-1">Số đề cương sẽ phân công: <span id="mdlCount" class="font-medium">0</span></p>
           <p class="mt-2 text-slate-500">Bạn có chắc chắn muốn phân công các đề cương đã chọn cho giảng viên này?</p>
         </div>
         <div class="p-3 border-t flex items-center justify-end gap-2">
           <button id="btnCancelAssign" class="px-3 py-1.5 rounded-lg border border-slate-200">Hủy</button>
           <button id="btnConfirmAssign" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white">Xác nhận</button>
         </div>
       </div>
     </div>
</div>

<script>
      (function sidebarInit(){
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const h=document.querySelector('header'); const m=document.querySelector('main');
        if(c){
          html.classList.add('sidebar-collapsed');
          h?.classList.add('md:left-[72px]');
          m?.classList.add('md:pl-[72px]');
        } else {
          html.classList.remove('sidebar-collapsed');
            h?.classList.remove('md:left-[72px]');
            m?.classList.remove('md:pl-[72px]');
        }
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{
        const c=!html.classList.contains('sidebar-collapsed');
        setCollapsed(c);
        localStorage.setItem('head_sidebar', c?1:0);
      });
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('head_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      // Profile dropdown
      const profileBtn=document.getElementById('profileBtn');
      const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', e=>{
        if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden');
      });

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
    })();

    // Assign events
    (function assignEvents(){
      const btn = document.getElementById('btnAssignMain');
      const modal = document.getElementById('assignModal');
      const btnCancel = document.getElementById('btnCancelAssign');
      const btnOk = document.getElementById('btnConfirmAssign');
      const mdlTeacher = document.getElementById('mdlTeacher');
      const mdlCount = document.getElementById('mdlCount');
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      const termId = {{ $rows->id }};

      function getSelectedTeacher(){
        const r = document.querySelector('.teacher-radio:checked');
        if(!r) return null;
        const tr = r.closest('tr');
        const id = tr?.dataset?.supervisorId ? parseInt(tr.dataset.supervisorId,10) : null;
        const name = tr?.querySelector('.font-medium')?.textContent?.trim() || '';
        return id ? { id, name } : null;
      }
      function getSelectedAssignments(){
        return Array.from(document.querySelectorAll('.stuChk:checked'))
          .map(chk => chk.closest('tr')?.dataset?.assignmentId)
          .filter(Boolean)
          .map(v => parseInt(v,10));
      }
      function openModal(teacher, count){
        mdlTeacher.textContent = teacher?.name || '—';
        mdlCount.textContent = String(count || 0);
        modal.classList.remove('hidden');
      }
      function closeModal(){ modal.classList.add('hidden'); }

      btn?.addEventListener('click', ()=>{
        const teacher = getSelectedTeacher();
        const ids = getSelectedAssignments();
        if(!teacher){ alert('Vui lòng chọn giảng viên phản biện.'); return; }
        if(ids.length===0){ alert('Vui lòng chọn ít nhất 1 đề cương của sinh viên.'); return; }
        openModal(teacher, ids.length);
      });

      btnCancel?.addEventListener('click', closeModal);

      btnOk?.addEventListener('click', async ()=>{
        const teacher = getSelectedTeacher();
        const ids = getSelectedAssignments();
        if(!teacher || ids.length===0){ closeModal(); return; }
        btnOk.disabled = true; btnOk.textContent = 'Đang phân công...';
        try{
          const res = await fetch(`{{ route('web.head.blind_review.assign') }}`, {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' },
            body: JSON.stringify({ termId: termId, supervisorId: teacher.id, assignments: ids })
          });
          if(!res.ok){ throw new Error('Request failed'); }
          const data = await res.json();
          // Cập nhật UI tối thiểu: xóa các hàng đã phân công khỏi bảng sinh viên
          ids.forEach(id=>{
            const tr = document.querySelector(`tr[data-assignment-id="${id}"]`);
            tr?.parentElement?.removeChild(tr);
          });
          alert(data.message || 'Phân công thành công');
        } catch(e){
          alert('Có lỗi xảy ra khi phân công. Vui lòng thử lại.');
        } finally{
          btnOk.disabled = false; btnOk.textContent = 'Xác nhận';
          closeModal();
        }
      });
    })();
</script>

</body>
</html>
