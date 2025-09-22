<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Danh sách phản biện được phân công</title>
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
  <div class="flex min-h-screen">
    <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Lecturer</div>
          <div class="text-xs text-slate-500">Bảng điều khiển</div>
        </div>
      </div>
      <nav class="flex-1 overflow-y-auto p-3">
        <a href="overview.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i><span class="sidebar-label">Tổng quan</span></a>
        <a href="profile.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user"></i><span class="sidebar-label">Hồ sơ</span></a>
        <a href="research.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-flask"></i><span class="sidebar-label">Nghiên cứu</span></a>
        <a href="students.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span></a>
        <div class="sidebar-label text-xs uppercase text-slate-400 px-3 mt-3">Học phần tốt nghiệp</div>
        <a href="thesis-internship.html" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 pl-5"><i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt nghiệp</span></a>
        <a href="thesis-rounds.html" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold pl-5"><i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span></a>
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
            <h1 class="text-lg md:text-xl font-semibold">Danh sách phản biện được phân công</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="overview.html" class="hover:underline text-slate-600">Giảng viên</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-round-detail.html" class="hover:underline text-slate-600">Chi tiết đợt</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Phản biện được phân công</span>
            </nav>
          </div>
        </div>
        <div class="relative">
          <button id="profileBtn" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100">
            <img class="h-9 w-9 rounded-full object-cover" src="https://i.pravatar.cc/100?img=20" alt="avatar" />
            <div class="hidden sm:block text-left">
              <div class="text-sm font-semibold leading-4">TS. Nguyễn Văn A</div>
              <div class="text-xs text-slate-500">lecturer@uni.edu</div>
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
        <div class="max-w-7xl mx-auto space-y-4">

          <div class="flex flex-col md:flex-row md:items-center justify-end gap-3">
            <a href="thesis-round-detail.html" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại chi tiết đợt</a>
          </div>

          <section class="bg-white rounded-xl border border-slate-200 p-4">
            <h2 class="font-semibold text-lg mb-2">Thông tin đợt đồ án</h2>
            <div class="text-slate-700 text-sm space-y-1">
              @php
              $stage = $rows->stage;
              $term = $rows->academy_year->name . ' - Học kỳ ' . $rows->stage;
              $semester = $rows->stage?? '';
              $date = date('d/m/Y', strtotime($rows->start_date ?? '')) . ' - ' . date('d/m/Y', strtotime($rows->end_date ?? ''));
              @endphp
              <div><strong>Đợt:</strong> {{ $term }} </div>
              <div><strong>Năm học:</strong> {{ $term }} </div>
              <div><strong>Học kỳ:</strong> {{ $semester }} </div>
              <div><strong>Thời gian:</strong> {{ $date }} </div>
            </div>
          </section>

          <section class="bg-white rounded-xl border border-slate-200 p-4 my-6">
            <h2 class="font-semibold text-lg mb-4">Thông tin hội đồng</h2>
            <div class="text-slate-700 text-sm space-y-2">
              @php
                $council = $rows->council_project?->council ?? null;
              @endphp
              @if ($council)
                <div>
                  <strong>Tên hội đồng:</strong> {{ $council->name }}
                </div>
                <div>
                  <strong>Mã hội đồng:</strong> {{ $council->code }}
                </div>
                <div>
                  <strong>Mô tả:</strong> {{ $council->description ?? 'Không có mô tả' }}
                </div>
                <div>
                  <strong>Khoa/Bộ môn:</strong> {{ $council->department->name ?? 'N/A' }}
                </div>
                <div>
                  <strong>Địa điểm:</strong> {{ $council->address ?? 'Chưa có' }}
                </div>
                <div>
                  <strong>Ngày bảo vệ:</strong> 
                  {{ $council->date ? date('d/m/Y', strtotime($council->date)) : 'Chưa có' }}
                </div>
                <div>
                  <strong>Trạng thái:</strong> 
                  <span class="px-2 py-1 text-xs rounded-full border
                    {{ $council->status === 'active' 
                        ? 'bg-green-50 text-green-700 border-green-200' 
                        : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                    {{ ucfirst($council->status) }}
                  </span>
                </div>
                <div>
                  <strong>Thuộc đợt đồ án:</strong> 
                  {{ $council->project_term->academy_year->name ?? '' }} - Học kỳ {{ $council->project_term->stage ?? '' }}
                </div>
              @else
                <div class="text-slate-500 italic">Chưa có thông tin hội đồng</div>
              @endif
            </div>
          </section>

          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input id="search" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-80" placeholder="Tìm theo tên/MSSV/hội đồng" />
            </div>
          </div>

          <div class="bg-white border rounded-xl p-4 shadow-sm">
            <div class="overflow-x-auto">
              <table id="studentTable" class="w-full text-sm table-fixed border-collapse">
                <thead>
                  <tr class="bg-slate-100 text-slate-600 text-left">
                    <th class="py-3 px-3 font-medium w-40">Sinh viên</th>
                    <th class="py-3 px-3 font-medium w-28">MSSV</th>
                    <th class="py-3 px-3 font-medium w-64">Đề tài</th>
                    <th class="py-3 px-3 font-medium w-28 text-center">Thứ tự</th>
                    <th class="py-3 px-3 font-medium w-32 text-center">Điểm</th>
                    <th class="py-3 px-3 font-medium w-32">Trạng thái</th>
                    <th class="py-3 px-3 font-medium w-40">Thời gian</th>
                    <th class="py-3 px-3 font-medium w-44">Hành động</th>
                  </tr>
                </thead>
                @php
                  $assignments = $rows->assignments;
                @endphp
                <tbody id="rows">
                  @foreach ($assignments as $assignment)
                    @php
                      $student = $assignment->student;
                      $studentName = $student ? $student->user->fullname : 'N/A';
                      $studentCode = $student ? $student->student_code : 'N/A';
                      $topic = $assignment->project?->name ?? 'N/A';
                      $index = $loop->index + 1;
                      $scoreReview = $assignment->council_project?->review_score ?? 'Chưa chấm';
                      $status = $scoreReview !== 'Chưa chấm' ? 'Đã chấm' : 'Chưa chấm';
                      $statusColor = $scoreReview !== 'Chưa chấm'
                                    ? 'bg-green-50 text-green-700 border-green-200'
                                    : 'bg-rose-50 text-rose-700 border-rose-200';
                      $time = $assignment->council_project?->time ?? 'N/A';
                      $councilId = $assignment->council_project?->council_id ?? null;
                    @endphp
                    <tr class="border-b hover:bg-slate-50 transition">
                      <td class="py-3 px-3 truncate max-w-[150px]" title="{{ $studentName }}">{{ $studentName }}</td>
                      <td class="py-3 px-3 whitespace-nowrap">{{ $studentCode }}</td>
                      <td class="py-3 px-3 truncate max-w-[250px]" title="{{ $topic }}">{{ $topic }}</td>
                      <td class="py-3 px-3 text-center">{{ $index }}</td>
                      <td class="py-3 px-3 text-center">{{ $scoreReview }}</td>
                      <td class="py-3 px-3">
                        <span class="px-2 py-1 text-xs font-medium rounded-full border {{ $statusColor }}">
                          {{ $status }}
                        </span>
                      </td>
                      <td class="py-3 px-3 whitespace-nowrap">{{ $time }}</td>
                      <td class="py-3 px-3">
                        <div class="flex items-center gap-2">
                          <a class="px-2 py-1 border border-slate-200 rounded text-xs hover:bg-slate-100 transition whitespace-nowrap" href="#">
                            Chấm phản biện
                          </a>
                          @if ($councilId)
                            <a class="px-2 py-1 border border-slate-200 rounded text-xs hover:bg-slate-100 transition whitespace-nowrap" 
                              href="{{ route('web.teacher.committee_detail', ['councilId' => $councilId, 'termId' => $rows->id]) }}">
                              Xem hội đồng
                            </a>
                          @endif
                        </div>
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

  <script>
    // Sidebar/profile wiring
    (function(){
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
      function setCollapsed(c){
        const h=document.querySelector('header'); const m=document.querySelector('main');
        if(c){ html.classList.add('sidebar-collapsed')}
        else { html.classList.remove('sidebar-collapsed')}
      }
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed'); setCollapsed(c); localStorage.setItem('lecturer_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');
      const profileBtn=document.getElementById('profileBtn'); const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    })();

    document.getElementById('search').addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll('#studentTable tbody tr');
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    });

  </script>
</body>
</html>
