<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chi tiết đợt đồ án - Trợ lý khoa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
      body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
      .sidebar-collapsed .sidebar-label { display:none; }
      .sidebar-collapsed .sidebar { width:72px; }
      .sidebar { width:260px; }
      .timeline-stage.active .w-12 { 
        transform: scale(1.1); 
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
      }
      .timeline-stage:hover .w-12 { 
        transform: scale(1.05); 
      }
      .timeline-stage .w-12 { 
        transition: all 0.2s ease; 
      }
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
      $expertise = $user->teacher->supervisor->expertise ?? 'null';
      $data_assignment_supervisors = $user->teacher->supervisor->assignment_supervisors ?? collect();;
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
          <a href="{{ route('web.assistant.assign_head') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-user-switch"></i><span class="sidebar-label">Gán trưởng bộ môn</span></a>
          <div class="sidebar-label text-xs uppercase text-slate-400 px-3 mt-3">Học phần tốt nghiệp</div>
          <div class="graduation-item">
            <div class="flex items-center justify-between px-3 py-2 cursor-pointer toggle-button">
              <span class="flex items-center gap-3">
                <i class="ph ph-folder"></i>
                <span class="sidebar-label">Học phần tốt nghiệp</span>
              </span>
              <i class="ph ph-caret-down"></i>
            </div>
            <div id="gradMenu" class="submenu pl-6">
              <a href="#" class="block px-3 py-2 rounded hover:bg-slate-100">Thực tập tốt nghiệp</a>
              <a href="{{ route('web.assistant.rounds') }}"
                 class="block px-3 py-2 rounded hover:bg-slate-100 bg-slate-100 font-semibold"
                 aria-current="page">Đồ án tốt nghiệp</a>
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
              <h1 class="text-lg md:text-xl font-semibold">Chi tiết đợt đồ án</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Trợ lý khoa / Học phần tốt nghiệp / Đồ án tốt nghiệp / Chi tiết</nav>
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

        <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 space-y-6">
          <div class="max-w-6xl mx-auto space-y-6">
          <section id="roundMeta" class="bg-white rounded-xl border border-slate-200 p-5"
                   data-start="{{ $round_detail->start_date }}" data-end="{{ $round_detail->end_date }}">
            <div class="flex items-center justify-between">
              <div>
                <div class="text-sm text-slate-500">Mã đợt: <span class="font-medium text-slate-700">{{ $round_detail->id }}</span></div>
                @php
                    $start_year = $round_detail->start_date ? substr($round_detail->start_date, 0, 4) : '';
                    $end_year = $round_detail->end_date ? substr($round_detail->end_date, 0, 4) : '';
                @endphp
                <h2 class="font-semibold text-lg mt-1">{{ "Đợt " . $round_detail->stage . " năm học " . $start_year . "-" . $end_year}}</h2>
                <div class="text-sm text-slate-600">{{ $round_detail->start_date }} - {{ $round_detail->end_date }}</div>
              </div>
            </div>
          </section>

        <!-- Timeline -->
        <section class="bg-white rounded-xl border border-slate-200 p-5">
          <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold">Tiến độ giai đoạn đồ án</h3>
            <div class="flex items-center gap-2 text-sm">
              <span id="progressText" class="font-medium">25%</span>
              <div class="w-40 h-2 rounded-full bg-slate-100 overflow-hidden">
                <div id="progressBar" class="h-full bg-blue-600" style="width:25%"></div>
              </div>
            </div>
          </div>

          <!-- Horizontal Timeline -->
          <div class="relative">
            <!-- Progress Line -->
            <div class="absolute top-6 left-8 right-8 h-0.5 bg-slate-200">
              <div class="h-full bg-blue-600" style="width: 25%"></div>
            </div>
            
            <!-- Timeline Items -->
            <div class="grid grid-cols-8 gap-4 relative">
              <!-- Stage 1 -->
              <div class="timeline-stage cursor-pointer" data-stage="1">
                <div class="w-12 h-12 mx-auto bg-emerald-600 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">1</div>
                <div class="text-center mt-2">
                  <div class="text-xs font-medium text-slate-900">Nhập DS SV</div>
                  <div class="text-xs text-emerald-600 mt-1">Hoàn thành</div>
                </div>
              </div>

              <!-- Stage 2 -->
              <div class="timeline-stage cursor-pointer" data-stage="2">
                <div class="w-12 h-12 mx-auto bg-blue-600 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">2</div>
                <div class="text-center mt-2">
                  <div class="text-xs font-medium text-slate-900">Đề cương</div>
                  <div class="text-xs text-blue-600 mt-1">Đang diễn ra</div>
                </div>
              </div>

              <!-- Stage 3 -->
              <div class="timeline-stage cursor-pointer" data-stage="3">
                <div class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">3</div>
                <div class="text-center mt-2">
                  <div class="text-xs font-medium text-slate-900">Thực hiện</div>
                  <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                </div>
              </div>

              <!-- Stage 4 -->
              <div class="timeline-stage cursor-pointer" data-stage="4">
                <div class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">4</div>
                <div class="text-center mt-2">
                  <div class="text-xs font-medium text-slate-900">Nộp báo cáo</div>
                  <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                </div>
              </div>

              <!-- Stage 5 -->
              <div class="timeline-stage cursor-pointer" data-stage="5">
                <div class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">5</div>
                <div class="text-center mt-2">
                  <div class="text-xs font-medium text-slate-900">Hội đồng</div>
                  <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                </div>
              </div>

              <!-- Stage 6 -->
              <div class="timeline-stage cursor-pointer" data-stage="6">
                <div class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">6</div>
                <div class="text-center mt-2">
                  <div class="text-xs font-medium text-slate-900">Phản biện</div>
                  <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                </div>
              </div>

              <!-- Stage 7 -->
              <div class="timeline-stage cursor-pointer" data-stage="7">
                <div class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">7</div>
                <div class="text-center mt-2">
                  <div class="text-xs font-medium text-slate-900">Công bố</div>
                  <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                </div>
              </div>

              <!-- Stage 8 -->
              <div class="timeline-stage cursor-pointer" data-stage="8">
                <div class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">8</div>
                <div class="text-center mt-2">
                  <div class="text-xs font-medium text-slate-900">Bảo vệ</div>
                  <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Timeline Details Panel -->
          <div id="timelineDetails" class="mt-8 p-6 bg-slate-50 rounded-lg">
            <div id="stageContent">
              <div class="text-center text-slate-500">
                <i class="ph ph-cursor-click text-2xl mb-2"></i>
                <p>Click vào một giai đoạn để xem chi tiết chức năng</p>
              </div>
            </div>
          </div>

          <!-- Legend -->
          <div class="mt-6 text-xs text-slate-500 flex flex-wrap gap-4">
            <span class="inline-flex items-center gap-1"><span class="h-2.5 w-2.5 rounded-full bg-emerald-600"></span>Hoàn thành</span>
            <span class="inline-flex items-center gap-1"><span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>Đang diễn ra</span>
            <span class="inline-flex items-center gap-1"><span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>Sắp tới</span>
          </div>
        </section>

          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between">
              <h3 class="font-semibold">Danh sách hội đồng</h3>
              <div class="flex items-center gap-3">
                <div class="relative">
                  <input id="searchInput" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tên hội đồng" />
                  <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
                <button id="btnCreateCommittee" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm"><i class="ph ph-plus"></i> Tạo hội đồng</button>
              </div>
            </div>
            <div class="mt-4 overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500">
                    <th class="py-3 px-4 border-b">Tên hội đồng</th>
                    <th class="py-3 px-4 border-b">Số thành viên</th>
                    <th class="py-3 px-4 border-b">Giảng viên</th>
                    <th class="py-3 px-4 border-b text-right">Hành động</th>
                  </tr>
                </thead>
                <tbody id="tableBody">
                  <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4"><a href="committee-detail.html" class="text-blue-600 hover:underline">HĐ-CNTT-01</a></td>
                    <td class="py-3 px-4">5</td>
                    <td class="py-3 px-4">
                      <div class="flex flex-wrap gap-1">
                        <a class="px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-700" href="../lecturer-ui/profile.html">TS. Đặng Hữu T</a>
                        <a class="px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-700" href="../lecturer-ui/profile.html">ThS. Lưu Lan</a>
                        <a class="px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-700" href="../lecturer-ui/profile.html">TS. Nguyễn Văn A</a>
                        <span class="px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-700">...</span>
                      </div>
                    </td>
                    <td class="py-3 px-4 text-right space-x-2">
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600"><i class="ph ph-pencil"></i></button>
                      <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600"><i class="ph ph-trash"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>
        </div>
        </main>
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

      // Ensure the script runs after the DOM is fully loaded
      document.addEventListener('DOMContentLoaded', () => {
        // Timeline interaction
        const timelineStages = document.querySelectorAll('.timeline-stage');
        const stageContent = document.getElementById('stageContent');

        // Helpers: parse ngày linh hoạt và format ngày
        function toDateAny(s) {
          s = (s || '').trim();
          // dd/mm/yyyy
          let m = s.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
          if (m) return new Date(+m[3], +m[2] - 1, +m[1]);
          // yyyy-mm-dd
          m = s.match(/^(\d{4})-(\d{2})-(\d{2})$/);
          if (m) return new Date(+m[1], +m[2] - 1, +m[3]);
          const d = new Date(s);
          return isNaN(d) ? null : d;
        }
        function formatVN(d) {
          if (!(d instanceof Date) || isNaN(d)) return '';
          const dd = String(d.getDate()).padStart(2, '0');
          const mm = String(d.getMonth() + 1).padStart(2, '0');
          const yy = d.getFullYear();
          return `${dd}/${mm}/${yy}`;
        }
        function getRoundDates() {
          // Ưu tiên lấy từ data-* để đảm bảo định dạng
          const meta = document.getElementById('roundMeta');
          let a = meta?.dataset.start || '';
          let b = meta?.dataset.end || '';
          // Fallback từ text nếu thiếu
          if (!a || !b) {
            const rangeEl = document.querySelector('main section:first-of-type .text-sm.text-slate-600');
            const txt = (rangeEl?.textContent || '').trim();
            [a, b] = txt.split('-').map(s => (s || '').trim());
          }
          const start = toDateAny(a);
          const end = toDateAny(b);
          return { start, end };
        }
        function getStagePeriod(stageNum, totalStages = 8) {
          const { start, end } = getRoundDates();
          if (!start || !end || end < start) return null;
          const totalMs = end.getTime() - start.getTime();
          const slice = Math.floor(totalMs / totalStages);
          const segStart = new Date(start.getTime() + slice * (stageNum - 1));
          // segEnd: nếu là stage cuối dùng end, ngược lại lấy mốc trước của stage tiếp theo
          const segEnd = stageNum === totalStages
            ? end
            : new Date(start.getTime() + slice * stageNum - 1);
          return { start: segStart, end: segEnd };
        }

        const stageData = {
          1: {
            title: 'Nhập danh sách sinh viên đủ điều kiện',
            description: 'Import danh sách sinh viên đủ điều kiện tham gia đồ án tốt nghiệp',
            actions: [
              { label: 'Import SV', href: 'students-import.html' },
              { label: 'Xem danh sách', href: 'students-detail.html' }
            ]
          },
          2: {
            title: 'Quản lý đề cương đồ án',
            description: 'Quản lý danh sách sinh viên nộp đề cương và đề tài đồ án',
            actions: [
              { label: 'Quản lý đề cương', href: 'outline-management.html' },
              { label: 'Danh sách đề tài', href: '#' }
            ]
          },
          3: {
            title: 'Thực hiện đồ án',
            description: 'Quản lý quá trình sinh viên thực hiện đồ án và báo cáo tiến độ',
            actions: [
              { label: 'Theo dõi tiến độ', href: 'progress-tracking.html' },
              { label: 'Báo cáo định kỳ', href: '#' },
            ]
          },
          4: {
            title: 'Nộp báo cáo cuối kỳ',
            description: 'Quản lý việc sinh viên nộp báo cáo và tài liệu cuối kỳ',
            actions: [
              { label: 'Quản lý báo cáo', href: 'report-management.html' },
              { label: 'Danh sách nộp bài', href: '#' }
            ]
          },
          5: {
            title: 'Thành lập hội đồng chấm',
            description: 'Tạo hội đồng, phân công vai trò và phân sinh viên vào hội đồng',
            actions: [
              { label: 'Tạo hội đồng', href: '#', action: 'createCouncil' },
              { label: 'Phân công vai trò', href: "{{ route('web.assistant.councils.roles', [$round_detail->id]) }}" },
              { label: 'Phân sinh viên', href: '#' }
            ]
          },
          6: {
            title: 'Phản biện',
            description: 'Quản lý quá trình phản biện đồ án',
            actions: [
              { label: 'Danh sách giáo viên phản biện', href: 'reviewers.html' },
              { label: 'Danh sách sinh viên phản biện', href: '#' }
            ]
          },
          7: {
            title: 'Công bố kết quả',
            description: 'Công bố kết quả phản biện đồ án tốt nghiệp',
            actions: [
              { label: 'Xem kết quả', href: '#' },
              { label: 'Cập nhật kết quả', href: '#' }
            ]
          },
          8: {
            title: 'Bảo vệ đồ án',
            description: 'Quản lý quá trình bảo vệ đồ án tốt nghiệp',
            actions: [
              { label: 'Lịch bảo vệ', href: '#' },
              { label: 'Kết quả bảo vệ', href: '#' }
            ]
          }
        };

// Thêm: helper tạo card hành động giống panel timeline 1
        function actionCard(action) {
          const label = action.label || 'Mở';
          const href = action.href || '#';
          const dataAction = action.action ? `data-action="${action.action}"` : '';
          // Gợi ý icon/màu theo nhãn
          let icon = 'ph ph-rocket', color = 'bg-slate-50 text-slate-600', colorHover = 'group-hover:bg-slate-100';
          const l = label.toLowerCase();
          if (l.includes('import')) { icon = 'ph ph-upload-simple'; color = 'bg-blue-50 text-blue-600'; colorHover = 'group-hover:bg-blue-100'; }
          else if (l.includes('danh sách') || l.includes('list')) { icon = 'ph ph-list-bullets'; color = 'bg-emerald-50 text-emerald-600'; colorHover = 'group-hover:bg-emerald-100'; }
          else if (l.includes('đề cương') || l.includes('outline')) { icon = 'ph ph-notebook'; color = 'bg-indigo-50 text-indigo-600'; colorHover = 'group-hover:bg-indigo-100'; }
          else if (l.includes('tiến độ') || l.includes('progress')) { icon = 'ph ph-chart-line-up'; color = 'bg-teal-50 text-teal-600'; colorHover = 'group-hover:bg-teal-100'; }
          else if (l.includes('báo cáo') || l.includes('report')) { icon = 'ph ph-file-text'; color = 'bg-amber-50 text-amber-600'; colorHover = 'group-hover:bg-amber-100'; }
          else if (l.includes('hội đồng') || l.includes('committee')) { icon = 'ph ph-users-three'; color = 'bg-fuchsia-50 text-fuchsia-600'; colorHover = 'group-hover:bg-fuchsia-100'; }
          else if (l.includes('lịch') || l.includes('schedule')) { icon = 'ph ph-calendar-check'; color = 'bg-cyan-50 text-cyan-600'; colorHover = 'group-hover:bg-cyan-100'; }

          return `
            <a href="${href}" ${dataAction} class="group border border-slate-200 hover:border-blue-300 rounded-xl p-4 bg-white hover:shadow-sm transition">
              <div class="flex items-start gap-3">
                <div class="h-10 w-10 rounded-lg grid place-items-center ${color} ${colorHover}">
                  <i class="${icon}"></i>
                </div>
                <div class="flex-1">
                  <div class="font-medium">${label}</div>
                  <div class="text-xs text-slate-500 mt-0.5">${action.desc || 'Đi tới chức năng'}</div>
                  <div class="mt-3">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-600 text-white text-sm">
                      <i class="ph ph-arrow-right"></i> Mở
                    </span>
                  </div>
                </div>
              </div>
            </a>`;
        }

        function renderStage(stageNum) {
          const data = stageData[stageNum];
          // highlight active bubble
          document.querySelectorAll('.timeline-stage').forEach(s => s.classList.remove('active'));
          document.querySelector(`.timeline-stage[data-stage="${stageNum}"]`)?.classList.add('active');

          const period = getStagePeriod(stageNum, 8);
          const timeHtml = period
            ? `<div class="mt-2 text-xs text-slate-500"><i class="ph ph-calendar"></i> Thời gian: ${formatVN(period.start)} - ${formatVN(period.end)}</div>`
            : '';

          // Panel hiện đại cho Stage 1 (đã có)
          if (stageNum === 1) {
            stageContent.innerHTML = `
              <div class="space-y-4">
                <div>
                  <h3 class="font-semibold text-lg">Nhập danh sách sinh viên đủ điều kiện</h3>
                  <p class="text-sm text-slate-600">Chuẩn bị dữ liệu cho kỳ đồ án: SV đủ điều kiện và giảng viên hướng dẫn.</p>
                  ${timeHtml}
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                  <!-- Thêm sinh viên -->
                  <a href="{{ route('web.assistant.students_import', [$round_detail->id]) }}" class="group border border-slate-200 hover:border-blue-300 rounded-xl p-4 bg-white hover:shadow-sm transition">
                    <div class="flex items-start gap-3">
                      <div class="h-10 w-10 rounded-lg grid place-items-center bg-blue-50 text-blue-600 group-hover:bg-blue-100">
                        <i class="ph ph-user-plus"></i>
                      </div>
                      <div class="flex-1">
                        <div class="font-medium">Thêm sinh viên</div>
                        <div class="text-xs text-slate-500 mt-0.5">Tạo mới/nhập danh sách sinh viên</div>
                        <div class="mt-3">
                          <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-600 text-white text-sm">
                            <i class="ph ph-plus"></i> Thêm
                          </span>
                        </div>
                      </div>
                    </div>
                  </a>

                  <!-- Thêm giảng viên hướng dẫn -->
                  <a href="{{ route('web.assistant.supervisors_import', [$round_detail->id]) }}" class="group border border-slate-200 hover:border-blue-300 rounded-xl p-4 bg-white hover:shadow-sm transition">
                    <div class="flex items-start gap-3">
                      <div class="h-10 w-10 rounded-lg grid place-items-center bg-indigo-50 text-indigo-600 group-hover:bg-indigo-100">
                        <i class="ph ph-users-three"></i>
                      </div>
                      <div class="flex-1">
                        <div class="font-medium">Thêm giảng viên hướng dẫn</div>
                        <div class="text-xs text-slate-500 mt-0.5">Tạo mới/nhập danh sách giảng viên</div>
                        <div class="mt-3">
                          <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-sm">
                            <i class="ph ph-plus"></i> Thêm
                          </span>
                        </div>
                      </div>
                    </div>
                  </a>

                  <!-- Xem danh sách giảng viên hướng dẫn -->
                  <a href="{{ route('web.assistant.supervisors_detail', [$round_detail->id]) }}" class="group border border-slate-200 hover:border-blue-300 rounded-xl p-4 bg-white hover:shadow-sm transition">
                    <div class="flex items-start gap-3">
                      <div class="h-10 w-10 rounded-lg grid place-items-center bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100">
                        <i class="ph ph-address-book"></i>
                      </div>
                      <div class="flex-1">
                        <div class="font-medium">Xem danh sách giảng viên hướng dẫn</div>
                        <div class="text-xs text-slate-500 mt-0.5">Quản lý hồ sơ và trạng thái GVHD</div>
                        <div class="mt-3">
                          <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-sm">
                            <i class="ph ph-list-bullets"></i> Xem danh sách
                          </span>
                        </div>
                      </div>
                    </div>
                  </a>

                  <!-- Xem danh sách sinh viên -->
                  <a href="{{ route('web.assistant.students_detail', [$round_detail->id]) }}" id="btnViewStudents" class="group border border-slate-200 hover:border-blue-300 rounded-xl p-4 bg-white hover:shadow-sm transition">
                    <div class="flex items-start gap-3">
                      <div class="h-10 w-10 rounded-lg grid place-items-center bg-teal-50 text-teal-600 group-hover:bg-teal-100">
                        <i class="ph ph-graduation-cap"></i>
                      </div>
                      <div class="flex-1">
                        <div class="font-medium">Xem danh sách sinh viên</div>
                        <div class="text-xs text-slate-500 mt-0.5">Sinh viên đủ điều kiện tham gia</div>
                        <div class="mt-3">
                          <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-teal-600 text-white text-sm">
                            <i class="ph ph-list-checks"></i> Xem danh sách
                          </span>
                        </div>
                      </div>
                    </div>
                  </a>

                </div>
              </div>
            `;

            // XÓA handler import GVHD cũ vì đã chuyển sang link
            // document.getElementById('btnImportAdvisors')?.addEventListener(...); // removed

            return;
          }

          // Mặc định: các stage khác render cùng layout card như stage 1
          if (data) {
            const cards = (data.actions || []).map(actionCard).join('');
            stageContent.innerHTML = `
              <div class="space-y-4">
                <div>
                  <h3 class="font-semibold text-lg">${data.title}</h3>
                  <p class="text-sm text-slate-600">${data.description || ''}</p>
                  ${timeHtml}
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                  ${cards || `<div class="text-sm text-slate-500">Chưa có hành động cho giai đoạn này.</div>`}
                </div>
              </div>
            `;
          } else {
            stageContent.innerHTML = `
              <div class="space-y-2">
                <p class="text-sm text-slate-500">Chưa có dữ liệu cho giai đoạn này.</p>
                ${timeHtml}
              </div>`;
          }
        }

        // Bind click
        timelineStages.forEach(stage => {
          stage.addEventListener('click', () => {
            const stageNum = parseInt(stage.dataset.stage);
            renderStage(stageNum);
          });
        });

        // Show stage 1 by default (kèm thời gian)
        renderStage(1);
      });

      // Timeline Stage 3 Functions
      function renderProgressTrackingUI(){
        const students = [
          {
            id:'20123456', name:'Nguyễn Văn A', class:'KTPM2025', email:'20123456@sv.uni.edu',
            topic:{ title:'Hệ thống quản lý thư viện', supervisor:'TS. Đặng Hữu T', status:'Tốt' },
            progress:75, lastUpdate:'2 ngày trước',
            logs:[
              { date:'10/08/2025', text:'Hoàn thành phân tích yêu cầu, bắt đầu thiết kế CSDL.' },
              { date:'05/08/2025', text:'Thiết lập repo, dựng khung dự án.' }
            ]
          },
          {
            id:'20124567', name:'Trần Thị B', class:'KTPM2025', email:'20124567@sv.uni.edu',
            topic:{ title:'Ứng dụng mobile bán hàng', supervisor:'ThS. Lưu Lan', status:'Chậm' },
            progress:45, lastUpdate:'5 ngày trước',
            logs:[
              { date:'08/08/2025', text:'Hoàn tất UI trang chính, đang kết nối Firebase.' },
              { date:'30/07/2025', text:'Khảo sát yêu cầu người dùng.' }
            ]
          },
          {
            id:'20125678', name:'Lê Văn C', class:'HTTT2025', email:'20125678@sv.uni.edu',
            topic:{ title:'Website thương mại điện tử', supervisor:'TS. Nguyễn Văn A', status:'Xuất sắc' },
            progress:90, lastUpdate:'1 ngày trước',
            logs:[
              { date:'11/08/2025', text:'Tối ưu hiệu năng, đạt TTFB < 200ms.' },
              { date:'01/08/2025', text:'Hoàn thành chức năng giỏ hàng, thanh toán Sandbox.' }
            ]
          }
        ];

        const badge = (status)=>{
          if(status==='Tốt') return 'bg-green-50 text-green-600';
          if(status==='Xuất sắc') return 'bg-emerald-50 text-emerald-700';
          if(status==='Chậm') return 'bg-yellow-50 text-yellow-700';
          return 'bg-slate-100 text-slate-700';
        };

        // layout
        document.getElementById('stageContent').innerHTML = `
          <div class="flex items-center justify-between">
            <div>
              <h3 class="font-semibold text-lg">Theo dõi tiến độ</h3>
              <p class="text-sm text-slate-600">Danh sách sinh viên và nhật ký thực hiện</p>
            </div>
            <div class="hidden md:flex gap-2 text-sm">
              <span class="px-2 py-1 rounded-full bg-blue-700 text-white">Đã bắt đầu: ${students.length}</span>
            </div>
          </div>

          <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- List -->
            <section class="lg:col-span-1 bg-white rounded-xl border border-slate-200 p-4">
              <div class="relative mb-3">
                <input id="progressSearch" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tên, mã SV" />
                <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              </div>
              <div id="progressStudentList" class="divide-y divide-slate-200 max-h-[420px] overflow-y-auto"></div>
            </section>

            <!-- Detail -->
            <section class="lg:col-span-2 space-y-4">
              <div id="progressDetail" class="bg-white rounded-xl border border-slate-200 p-4 min-h-[360px] grid place-items-center text-slate-500">
                Chọn một sinh viên ở danh sách bên trái để xem chi tiết.
              </div>
            </section>
          </div>
        `;

        const listEl = document.getElementById('progressStudentList');
        const detailEl = document.getElementById('progressDetail');
        const searchEl = document.getElementById('progressSearch');

        let filtered = [...students];
        let selectedId = filtered[0]?.id || null;

        function fmtProgressBar(pct, color){
          return `<div class=\"w-full bg-gray-200 rounded-full h-2\"><div class=\"${color} h-2 rounded-full\" style=\"width:${pct}%\"></div></div>`;
        }

        function renderList(){
          listEl.innerHTML = filtered.map(s=>{
            const active = s.id===selectedId;
            const color = s.progress>=80?'bg-green-600':s.progress>=60?'bg-blue-600':s.progress>=40?'bg-yellow-600':'bg-red-600';
            return `
              <button data-id="${s.id}" class="w-full text-left py-3 px-2 ${active?'bg-slate-50':''} hover:bg-slate-50">
                <div class="flex items-start justify-between">
                  <div>
                    <div class="font-medium">${s.name}</div>
                    <div class="text-xs text-slate-500">${s.id} • ${s.class}</div>
                  </div>
                  <span class="px-2 py-0.5 rounded-full text-xs ${badge(s.topic.status)}">${s.topic.status}</span>
                </div>
                <div class="mt-2">
                  ${fmtProgressBar(s.progress, color)}
                  <span class="text-xs text-slate-500">${s.progress}% • Cập nhật ${s.lastUpdate}</span>
                </div>
              </button>`;
          }).join('');

          // bind events
          listEl.querySelectorAll('button[data-id]').forEach(btn=>{
            btn.addEventListener('click', ()=>{
              selectedId = btn.getAttribute('data-id');
              renderList();
              const s = filtered.find(x=>x.id===selectedId);
              if(s) renderDetail(s);
            });
          });
        }

        function renderDetail(s){
          const color = s.progress>=80?'bg-green-600':s.progress>=60?'bg-blue-600':s.progress>=40?'bg-yellow-600':'bg-red-600';
          detailEl.classList.remove('grid','place-items-center','text-slate-500');
          detailEl.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <div class="bg-white border border-slate-200 rounded-lg p-4">
                <div class="font-semibold mb-2">Thông tin sinh viên</div>
                <div class="text-sm text-slate-700">${s.name}</div>
                <div class="text-sm text-slate-600">Mã SV: ${s.id}</div>
                <div class="text-sm text-slate-600">Lớp: ${s.class}</div>
                <div class="text-sm text-slate-600">Email: ${s.email}</div>
              </div>
              <div class="bg-white border border-slate-200 rounded-lg p-4">
                <div class="font-semibold mb-2">Thông tin đề tài</div>
                <div class="text-sm text-slate-700">${s.topic.title}</div>
                <div class="text-sm text-slate-600">GVHD: ${s.topic.supervisor}</div>
                <div class="mt-2">${fmtProgressBar(s.progress, color)}</div>
                <div class="text-xs text-slate-500 mt-1">Tiến độ: ${s.progress}% • ${s.lastUpdate}</div>
              </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-lg p-4 mt-4">
              <div class="flex items-center justify-between">
                <div class="font-semibold">Nhật kí đồ án</div>
                <div class="text-xs text-slate-500">${s.logs.length} mục</div>
              </div>
              <div class="mt-3 space-y-3">
                ${s.logs.map(l=>`
                  <div class=\"border rounded-lg p-3\">
                    <div class=\"text-xs text-slate-500\">${l.date}</div>
                    <div class=\"text-sm text-slate-700\">${l.text}</div>
                  </div>
                `).join('')}
              </div>
            </div>
          `;
        }

        // search
        searchEl.addEventListener('input', ()=>{
          const q = (searchEl.value||'').toLowerCase();
          filtered = students.filter(s=> s.name.toLowerCase().includes(q) || s.id.toLowerCase().includes(q));
          selectedId = filtered[0]?.id || null;
          renderList();
          if(selectedId){ renderDetail(filtered[0]); }
          else { detailEl.classList.add('grid','place-items-center','text-slate-500'); detailEl.innerHTML='Không tìm thấy sinh viên.'; }
        });

        // initial render
        renderList();
        if(selectedId){ renderDetail(filtered.find(x=>x.id===selectedId)); }
      }

      function showProgressTracking() {
        const modal = createModal('Theo dõi tiến độ thực hiện đồ án', `
          <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
              <div class="bg-blue-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">85</div>
                <div class="text-sm text-blue-800">Đã bắt đầu</div>
              </div>
              <div class="bg-green-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-green-600">32</div>
                <div class="text-sm text-green-800">Đang tiến triển tốt</div>
              </div>
              <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">18</div>
                <div class="text-sm text-yellow-800">Cần hỗ trợ</div>
              </div>
            </div>
            
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500 border-b">
                    <th class="py-2 px-3">Sinh viên</th>
                    <th class="py-2 px-3">Đề tài</th>
                    <th class="py-2 px-3">Tiến độ</th>
                    <th class="py-2 px-3">Cập nhật lần cuối</th>
                    <th class="py-2 px-3">Trạng thái</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="border-b">
                    <td class="py-3 px-3">Nguyễn Văn A</td>
                    <td class="py-3 px-3">Hệ thống quản lý thư viện</td>
                    <td class="py-3 px-3">
                      <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                      </div>
                      <span class="text-xs text-slate-500">75%</span>
                    </td>
                    <td class="py-3 px-3">2 ngày trước</td>
                    <td class="py-3 px-3"><span class="px-2 py-1 rounded-full text-xs bg-green-50 text-green-600">Tốt</span></td>
                  </tr>
                  <tr class="border-b">
                    <td class="py-3 px-3">Trần Thị B</td>
                    <td class="py-3 px-3">Ứng dụng mobile bán hàng</td>
                    <td class="py-3 px-3">
                      <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: 45%"></div>
                      </div>
                      <span class="text-xs text-slate-500">45%</span>
                    </td>
                    <td class="py-3 px-3">5 ngày trước</td>
                    <td class="py-3 px-3"><span class="px-2 py-1 rounded-full text-xs bg-yellow-50 text-yellow-600">Chậm</span></td>
                  </tr>
                  <tr>
                    <td class="py-3 px-3">Lê Văn C</td>
                    <td class="py-3 px-3">Website thương mại điện tử</td>
                    <td class="py-3 px-3">
                      <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 90%"></div>
                      </div>
                      <span class="text-xs text-slate-500">90%</span>
                    </td>
                    <td class="py-3 px-3">1 ngày trước</td>
                    <td class="py-3 px-3"><span class="px-2 py-1 rounded-full text-xs bg-green-50 text-green-600">Xuất sắc</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        `);
        document.body.appendChild(modal);
      }

      function showReports() {
        const modal = createModal('Báo cáo định kỳ', `
          <div class="space-y-4">
            <div class="flex justify-between items-center mb-4">
              <h4 class="font-medium">Danh sách báo cáo định kỳ</h4>
              <button class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                <i class="ph ph-plus"></i> Tạo yêu cầu báo cáo
              </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
              <div class="bg-slate-50 p-4 rounded-lg">
                <div class="text-lg font-semibold">Báo cáo tháng 8</div>
                <div class="text-sm text-slate-600">Hạn nộp: 31/08/2025</div>
                <div class="mt-2">
                  <span class="px-2 py-1 rounded-full text-xs bg-green-50 text-green-600">Đã nộp: 78/85</span>
                </div>
              </div>
              <div class="bg-slate-50 p-4 rounded-lg">
                <div class="text-lg font-semibold">Báo cáo tháng 9</div>
                <div class="text-sm text-slate-600">Hạn nộp: 30/09/2025</div>
                <div class="mt-2">
                  <span class="px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-600">Đang thu thập</span>
                </div>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500 border-b">
                    <th class="py-2 px-3">Sinh viên</th>
                    <th class="py-2 px-3">Báo cáo tháng 8</th>
                    <th class="py-2 px-3">Ngày nộp</th>
                    <th class="py-2 px-3">Đánh giá</th>
                    <th class="py-2 px-3">Hành động</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="border-b">
                    <td class="py-3 px-3">Nguyễn Văn A</td>
                    <td class="py-3 px-3">
                      <a href="#" class="text-blue-600 hover:underline">
                        <i class="ph ph-file-pdf"></i> bao_cao_thang8_NguyenVanA.pdf
                      </a>
                    </td>
                    <td class="py-3 px-3">28/08/2025</td>
                    <td class="py-3 px-3"><span class="px-2 py-1 rounded-full text-xs bg-green-50 text-green-600">Tốt</span></td>
                    <td class="py-3 px-3">
                      <button class="text-blue-600 hover:text-blue-800 text-xs">
                        <i class="ph ph-eye"></i> Xem
                      </button>
                    </td>
                  </tr>
                  <tr class="border-b">
                    <td class="py-3 px-3">Trần Thị B</td>
                    <td class="py-3 px-3 text-slate-400">Chưa nộp</td>
                    <td class="py-3 px-3">-</td>
                    <td class="py-3 px-3"><span class="px-2 py-1 rounded-full text-xs bg-red-50 text-red-600">Trễ hạn</span></td>
                    <td class="py-3 px-3">
                      <button class="text-orange-600 hover:text-orange-800 text-xs">
                        <i class="ph ph-bell"></i> Nhắc nhở
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        `);
        document.body.appendChild(modal);
      }

      function showMeetingSchedule() {
        const modal = createModal('Lịch họp hướng dẫn', `
          <div class="space-y-4">
            <div class="flex justify-between items-center mb-4">
              <h4 class="font-medium">Quản lý lịch họp hướng dẫn</h4>
              <button class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                <i class="ph ph-plus"></i> Đặt lịch họp
              </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-7 gap-2 mb-6">
              <div class="text-center p-2 border rounded-lg bg-blue-50">
                <div class="text-xs text-slate-600">T2</div>
                <div class="font-semibold">12</div>
                <div class="text-xs text-blue-600">3 cuộc họp</div>
              </div>
              <div class="text-center p-2 border rounded-lg">
                <div class="text-xs text-slate-600">T3</div>
                <div class="font-semibold">13</div>
                <div class="text-xs text-slate-400">Trống</div>
              </div>
              <div class="text-center p-2 border rounded-lg bg-green-50">
                <div class="text-xs text-slate-600">T4</div>
                <div class="font-semibold">14</div>
                <div class="text-xs text-green-600">2 cuộc họp</div>
              </div>
              <div class="text-center p-2 border rounded-lg">
                <div class="text-xs text-slate-600">T5</div>
                <div class="font-semibold">15</div>
                <div class="text-xs text-slate-400">Trống</div>
              </div>
              <div class="text-center p-2 border rounded-lg bg-yellow-50">
                <div class="text-xs text-slate-600">T6</div>
                <div class="font-semibold">16</div>
                <div class="text-xs text-yellow-600">1 cuộc họp</div>
              </div>
              <div class="text-center p-2 border rounded-lg bg-slate-100">
                <div class="text-xs text-slate-600">T7</div>
                <div class="font-semibold">17</div>
                <div class="text-xs text-slate-400">Nghỉ</div>
              </div>
              <div class="text-center p-2 border rounded-lg bg-slate-100">
                <div class="text-xs text-slate-600">CN</div>
                <div class="font-semibold">18</div>
                <div class="text-xs text-slate-400">Nghỉ</div>
              </div>
            </div>

            <div class="space-y-3">
              <div class="border rounded-lg p-3">
                <div class="flex justify-between items-start">
                  <div>
                    <div class="font-medium">Họp với Nguyễn Văn A</div>
                    <div class="text-sm text-slate-600">Thứ 2, 12/08 - 9:00 AM</div>
                    <div class="text-sm text-slate-500">Đề tài: Hệ thống quản lý thư viện</div>
                  </div>
                  <div class="flex gap-1">
                    <button class="text-blue-600 hover:text-blue-800 text-sm">
                      <i class="ph ph-pencil"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-800 text-sm">
                      <i class="ph ph-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
              
              <div class="border rounded-lg p-3">
                <div class="flex justify-between items-start">
                  <div>
                    <div class="font-medium">Họp nhóm - Review tiến độ</div>
                    <div class="text-sm text-slate-600">Thứ 4, 14/08 - 2:00 PM</div>
                    <div class="text-sm text-slate-500">5 sinh viên tham gia</div>
                  </div>
                  <div class="flex gap-1">
                    <button class="text-blue-600 hover:text-blue-800 text-sm">
                      <i class="ph ph-pencil"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-800 text-sm">
                      <i class="ph ph-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        `);
        document.body.appendChild(modal);
      }

      // Modal helper function
      function createModal(title, content) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center z-50';
        modal.innerHTML = `
          <div class="bg-white rounded-xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-4 border-b flex items-center justify-between sticky top-0 bg-white">
              <h3 class="font-semibold text-lg">${title}</h3>
              <button onclick="this.closest('.fixed').remove()" class="p-2 hover:bg-slate-100 rounded-lg">
                <i class="ph ph-x"></i>
              </button>
            </div>
            <div class="p-6">
              ${content}
            </div>
          </div>
        `;
        
        // Close on backdrop click
        modal.addEventListener('click', (e) => {
          if (e.target === modal) {
            modal.remove();
          }
        });
        
        return modal;
      }

      // simple filter
      document.getElementById('searchInput')?.addEventListener('input', (e)=>{
        const q=e.target.value.toLowerCase();
        document.querySelectorAll('#tableBody tr').forEach(tr=> tr.style.display = tr.innerText.toLowerCase().includes(q)?'':'none');
      });

      // Create Committee modal
      document.getElementById('btnCreateCommittee')?.addEventListener('click', ()=>{
        const content = `
<form id="createCommitteeForm" class="space-y-6">
  <!-- Thông tin chung -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
      <label class="block text-sm font-medium text-slate-700">Mã hội đồng</label>
      <input name="code" 
             class="mt-2 w-full border border-slate-300 rounded-xl px-3 py-2 text-sm shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
             placeholder="HĐ-CNTT-02" />
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700">Tên hội đồng</label>
      <input name="name" 
             class="mt-2 w-full border border-slate-300 rounded-xl px-3 py-2 text-sm shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
             placeholder="Hội đồng bảo vệ khóa luận CNTT" />
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700">Bộ môn</label>
      <input name="dept" 
             class="mt-2 w-full border border-slate-300 rounded-xl px-3 py-2 text-sm shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
             placeholder="CNTT" />
    </div>
  </div>

  <!-- Ngày & phòng -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
      <label class="block text-sm font-medium text-slate-700">Ngày bảo vệ</label>
      <input type="date" name="date" 
             class="mt-2 w-full border border-slate-300 rounded-xl px-3 py-2 text-sm shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700">Phòng bảo vệ</label>
      <input name="room" 
             class="mt-2 w-full border border-slate-300 rounded-xl px-3 py-2 text-sm shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
             placeholder="B203" />
    </div>
  </div>

  <!-- Mô tả -->
  <div>
    <label class="block text-sm font-medium text-slate-700">Mô tả</label>
    <textarea name="description" rows="3"
              class="mt-2 w-full border border-slate-300 rounded-xl px-3 py-2 text-sm shadow-sm 
                     focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
              placeholder="Nhập mô tả chi tiết về hội đồng..."></textarea>
  </div>

  <!-- Thành viên hội đồng -->
  <div class="border rounded-xl p-5 shadow-sm bg-slate-50">
    <div class="font-semibold text-slate-800 mb-4">Thành viên hội đồng</div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      
      <!-- Chủ tịch -->
      <div>
        <label class="block text-sm font-medium text-slate-700">Chủ tịch</label>
        <select name="chutich" 
                class="mt-2 w-full border border-slate-300 rounded-xl px-3 py-2 text-sm shadow-sm 
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none 
                       max-h-32 overflow-y-auto">
          <option value="">-- Chọn --</option>
          @foreach ($round_detail->supervisors as $supervisor)
            <option value="{{ $supervisor->id }}">{{ $supervisor->teacher->user->fullname }}</option>
          @endforeach
        </select>
      </div>

      <!-- Thư ký -->
      <div>
        <label class="block text-sm font-medium text-slate-700">Thư kí</label>
        <select name="thuki" 
                class="mt-2 w-full border border-slate-300 rounded-xl px-3 py-2 text-sm shadow-sm 
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none 
                       max-h-32 overflow-y-auto">
          <option value="">-- Chọn --</option>
          @foreach ($round_detail->supervisors as $supervisor)
            <option value="{{ $supervisor->id }}">{{ $supervisor->teacher->user->fullname }}</option>
          @endforeach
        </select>
      </div>

      <!-- Ủy viên 1 -->
      <div>
        <label class="block text-sm font-medium text-slate-700">Ủy viên 1</label>
        <select name="uyvien1" 
                class="mt-2 w-full border border-slate-300 rounded-xl px-3 py-2 text-sm shadow-sm 
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none 
                       max-h-32 overflow-y-auto">
          <option value="">-- Chọn --</option>
          @foreach ($round_detail->supervisors as $supervisor)
            <option value="{{ $supervisor->id }}">{{ $supervisor->teacher->user->fullname }}</option>
          @endforeach
        </select>
      </div>

      <!-- Ủy viên 2 -->
      <div>
        <label class="block text-sm font-medium text-slate-700">Ủy viên 2</label>
        <select name="uyvien2" 
                class="mt-2 w-full border border-slate-300 rounded-xl px-3 py-2 text-sm shadow-sm 
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none 
                       max-h-32 overflow-y-auto">
          <option value="">-- Chọn --</option>
          @foreach ($round_detail->supervisors as $supervisor)
            <option value="{{ $supervisor->id }}">{{ $supervisor->teacher->user->fullname }}</option>
          @endforeach
        </select>
      </div>

      <!-- Ủy viên 3 -->
      <div>
        <label class="block text-sm font-medium text-slate-700">Ủy viên 3</label>
        <select name="uyvien3" 
                class="mt-2 w-full border border-slate-300 rounded-xl px-3 py-2 text-sm shadow-sm 
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none 
                       max-h-32 overflow-y-auto">
          <option value="">-- Chọn --</option>
          @foreach ($round_detail->supervisors as $supervisor)
            <option value="{{ $supervisor->id }}">{{ $supervisor->teacher->user->fullname }}</option>
          @endforeach
        </select>
      </div>

    </div>
    <p class="mt-4 text-xs text-slate-500">Thành phần: 1 Chủ tịch, 1 Thư kí, 3 Ủy viên.</p>
  </div>

  <!-- Buttons -->
  <div class="flex items-center justify-end gap-3">
    <button type="button" 
            class="px-4 py-2 rounded-xl border text-sm text-slate-600 hover:bg-slate-100 transition" 
            onclick="this.closest('.fixed').remove()">Hủy</button>
    <button id="confirmCreateCommittee" type="submit" 
            class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 shadow-sm transition">Tạo</button>
  </div>
</form>

        `;
        const modal = createModal('Tạo hội đồng', content);
        document.body.appendChild(modal);

        // Đồng bộ các select vai trò: không cho chọn trùng giảng viên
        const roleSelects = Array.from(
          modal.querySelectorAll('select[name="chutich"], select[name="thuki"], select[name="uyvien1"], select[name="uyvien2"], select[name="uyvien3"]')
        );
        function syncRoleSelects(){
          const chosen = new Set(roleSelects.map(s => s.value).filter(Boolean));
          roleSelects.forEach(sel => {
            Array.from(sel.options).forEach(opt => {
              if (!opt.value) return; // bỏ qua option rỗng "-- Chọn --"
              const duplicate = chosen.has(opt.value) && sel.value !== opt.value;
              opt.disabled = duplicate;
              opt.hidden = duplicate; // ẩn khỏi dropdown
            });
          });
        }
        roleSelects.forEach(sel => sel.addEventListener('change', syncRoleSelects));
        // Gọi lần đầu để áp dụng trạng thái (nếu có preset)
        syncRoleSelects();

        const form = modal.querySelector('#createCommitteeForm');
        form.addEventListener('submit', async (e)=>{
          e.preventDefault();
          // Validate: không được trùng giảng viên giữa các vai trò
          const picked = roleSelects.map(s => s.value).filter(Boolean);
          const hasDup = new Set(picked).size !== picked.length;
          if (hasDup) { alert('Các vai trò không được trùng giảng viên. Vui lòng kiểm tra lại.'); return; }

          const fd = new FormData(form);
          const payload = {
            term_id: {{ $round_detail->id }},
            code: (fd.get('code')||'').toString().trim(),
            name: (fd.get('name')||'').toString().trim(),
            dept: (fd.get('dept')||'').toString().trim(),
            room: (fd.get('room')||'').toString().trim(),
            date: (fd.get('date')||'').toString().trim(),
            description: (fd.get('description')||'').toString().trim(),
            chutich: fd.get('chutich') || null,
            thuki: fd.get('thuki') || null,
            uyvien1: fd.get('uyvien1') || null,
            uyvien2: fd.get('uyvien2') || null,
            uyvien3: fd.get('uyvien3') || null,
          };
          const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
          const res = await fetch(`{{ route('web.assistant.councils.store') }}`, {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': token, 'Accept':'application/json' },
            body: JSON.stringify(payload)
          });
          if(!res.ok){ alert('Tạo hội đồng thất bại'); return; }
          // TODO: cập nhật bảng danh sách bằng data trả về
          e.target.closest('.fixed')?.remove();
        })
      });


      // profile dropdown
      const profileBtn=document.getElementById('profileBtn');
      const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

      // timeline logic
      function parseDateVN(d){ // dd/mm/yyyy
        const [dd,mm,yyyy] = (d||'').split('/').map(Number);
        return new Date(yyyy||1970, (mm||1)-1, dd||1);
      }
      function clamp(n,min,max){return Math.max(min, Math.min(max, n));}
      (function(){
        // get round date range from the first section
        const rangeEl = document.querySelector('main section:first-of-type .text-sm.text-slate-600');
        const rangeTxt = rangeEl?.textContent?.trim() || '';
        const [startStr, endStr] = rangeTxt.split('-').map(s=>s.trim());
        const start = parseDateVN(startStr);
        const end = parseDateVN(endStr);
        const today = new Date();

        // progress
        const total = end - start;
        const done = today - start;
        const pct = total>0 ? Math.round(clamp((done/total)*100, 0, 100)) : 0;
        const pt=document.getElementById('progressText'); if(pt) pt.textContent = pct + '%';
        const pb=document.getElementById('progressBar'); if(pb) pb.style.width = pct + '%';

        // milestones for the round
        const milestones = [
          { key:'open',    title:'Mở đăng ký đề tài',   date:'05/08/2025', desc:'Sinh viên đăng ký nguyện vọng và đề xuất đề tài.' },
          { key:'confirm', title:'Xác nhận GVHD',      date:'15/08/2025', desc:'Giảng viên xác nhận hướng dẫn; hoàn tất phân công.' },
          { key:'committee',title:'Phân công hội đồng', date:'10/09/2025', desc:'Thiết lập hội đồng chấm và lịch bảo vệ.' },
          { key:'report',  title:'Nộp báo cáo',        date:'05/10/2025', desc:'Sinh viên nộp báo cáo cuối cùng và slide.' },
          { key:'defense', title:'Bảo vệ',             date:'20/10/2025', desc:'Bảo vệ đồ án trước hội đồng.' },
          { key:'publish', title:'Công bố điểm',       date:'30/10/2025', desc:'Hoàn tất chấm và công bố kết quả.' }
        ].map(m=>({ ...m, when: parseDateVN(m.date) }));

        // determine status per milestone
        let currentMarked = false;
        const items = milestones.map(m=>{
          let status='upcoming';
          if(m.when < today) status='completed';
          else if(!currentMarked) { status='current'; currentMarked=true; }
          const dot = status==='completed'?'bg-emerald-600 border-emerald-600':status==='current'?'bg-blue-600 border-blue-600':'bg-slate-300 border-slate-300';
          const badge = status==='completed'?'bg-emerald-50 text-emerald-700':status==='current'?'bg-blue-50 text-blue-700':'bg-slate-100 text-slate-700';
          const label = status==='completed'?'Đã xong':status==='current'?'Đang diễn ra':'Sắp tới';
          return `
            <div class="relative pl-10 pb-6 last:pb-0">
              <span class="absolute left-[6px] top-1.5 h-3 w-3 rounded-full ${dot} ring-4 ring-white border"></span>
              <div class="flex items-center gap-2">
                <span class="text-sm text-slate-500">${m.date}</span>
                <span class="px-2 py-0.5 rounded-full text-xs ${badge}">${label}</span>
              </div>
              <div class="font-medium">${m.title}</div>
              <div class="text-sm text-slate-600">${m.desc}</div>
            </div>`;
        }).join('');
        const container=document.getElementById('timelineContainer');
        if(container) container.innerHTML = items;
      })();

          // auto active nav highlight
          (function(){
            const current = location.pathname.split('/').pop();
            document.querySelectorAll('aside nav a').forEach(a=>{
              // Giữ nguyên những link đã gắn aria-current (ví dụ: Đồ án tốt nghiệp)
              if (a.hasAttribute('aria-current')) return;
              const href=a.getAttribute('href')||'';
              const active=href.endsWith(current);
              a.classList.toggle('bg-slate-100', active);
              a.classList.toggle('font-semibold', active);
              a.classList.toggle('text-slate-900', active);
            });
          })();

  document.addEventListener('DOMContentLoaded', () => {
    const sidebarItems = document.querySelectorAll('.sidebar-item');

   
    sidebarItems.forEach(item => {
      const toggleButton = item.querySelector('.toggle-button');
      const submenu = item.querySelector('.submenu');

      if (toggleButton && submenu) {
        toggleButton.addEventListener('click', (e) => {
          e.preventDefault(); // Prevent default link behavior
          submenu.classList.toggle('hidden');

          // Redirect to the item's href if it exists
          const link = toggleButton.querySelector('a');
          if (link) {
            window.location.href = link.getAttribute('href');
          }
        });
      }
    });
  });

  document.addEventListener('DOMContentLoaded', () => {
    const nestedItems = document.querySelectorAll('.nested-item');

    nestedItems.forEach(item => {
      const toggleButton = item.querySelector('.toggle-button');
      const submenu = item.querySelector('.submenu');

      if (toggleButton && submenu) {
        toggleButton.addEventListener('click', (e) => {
          e.preventDefault(); // Prevent default link behavior
          submenu.classList.toggle('hidden');
        });
      }
    });
  });

  document.addEventListener('DOMContentLoaded', () => {
    const graduationItem = document.querySelector('.graduation-item');
    const toggleButton = graduationItem.querySelector('.toggle-button');
    const submenu = graduationItem.querySelector('.submenu');

    if (toggleButton && submenu) {
      toggleButton.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent default link behavior
        submenu.classList.toggle('hidden');
      });
    }
  });

// Mở sẵn submenu "Học phần tốt nghiệp"
document.addEventListener('DOMContentLoaded', () => {
  const wrap = document.querySelector('.graduation-item');
  if (!wrap) return;
  const toggleBtn = wrap.querySelector('.toggle-button');
  const submenu = wrap.querySelector('.submenu');
  const caret = wrap.querySelector('.ph.ph-caret-down');

  if (submenu && submenu.classList.contains('hidden')) submenu.classList.remove('hidden');
  if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
  caret?.classList.add('transition-transform', 'rotate-180');

  // Giữ hành vi toggle khi nhấn
  toggleBtn?.addEventListener('click', (e) => {
    e.preventDefault();
    submenu?.classList.toggle('hidden');
    const expanded = !submenu?.classList.contains('hidden');
    toggleBtn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    caret?.classList.toggle('rotate-180', expanded);
  });
});
    </script>
  </body>
</html>
