<!DOCTYPE html>

<html lang="vi">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Chi tiết đợt đồ án - Giảng viên</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
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
  </style>
</head>

<body class="bg-slate-50 text-slate-800">
  <div class="flex min-h-screen">
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
      <nav class="flex-1 overflow-y-auto p-3">
        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100" href="overview.html"><i
            class="ph ph-gauge"></i><span class="sidebar-label">Tổng quan</span></a>
        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100" href="profile.html"><i
            class="ph ph-user"></i><span class="sidebar-label">Hồ sơ</span></a>
        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100" href="research.html"><i
            class="ph ph-flask"></i><span class="sidebar-label">Nghiên cứu</span></a>
        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100" href="students.html"><i
            class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span></a>
        <div class="sidebar-label text-xs uppercase text-slate-400 px-3 mt-3">Học phần tốt nghiệp</div>
        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 pl-10"
          href="thesis-internship.html"><i class="ph ph-briefcase"></i><span class="sidebar-label">Thực tập tốt
            nghiệp</span></a>
        <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold pl-10"
          href="thesis-rounds.html"><i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt
            nghiệp</span></a>
      </nav>
      <div class="p-3 border-t border-slate-200">
        <button
          class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"
          id="toggleSidebar"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
      </div>
    </aside>
    <div class="flex-1 h-screen overflow-hidden flex flex-col">
      <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 flex-shrink-0">
        <div class="flex items-center gap-3 flex-1">
          <button class="md:hidden p-2 rounded-lg hover:bg-slate-100" id="openSidebar"><i
              class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Chi tiết đợt đồ án</h1>
            <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Giảng viên / Học phần tốt nghiệp / Đồ án tốt nghiệp /
              Chi tiết</nav>
          </div>
        </div>
        <div class="relative">
          <button class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-100" id="profileBtn">
            <img alt="avatar" class="h-9 w-9 rounded-full object-cover" src="{{ $avatarUrl }}" />
            <div class="hidden sm:block text-left">
              <div class="text-sm font-semibold leading-4">{{ $userName }}</div>
              <div class="text-xs text-slate-500">{{ $email }}</div>
            </div>
            <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
          </button>
          <div
            class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm"
            id="profileMenu">
            <a class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50" href="#"><i class="ph ph-user"></i>Xem thông
              tin</a>
            <a class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600" href="#"><i
                class="ph ph-sign-out"></i>Đăng xuất</a>
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </header>
      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6 space-y-6">
        <div class="max-w-6xl mx-auto space-y-6">
          <!-- Round Info -->
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between">
              <div>
                <div class="text-sm text-slate-500">Mã đợt: <span
                    class="font-medium text-slate-700">ROUND-2025-01</span></div>
                <h2 class="font-semibold text-lg mt-1">Đợt HK1 2025-2026</h2>
                <div class="text-sm text-slate-600">01/08/2025 - 30/10/2025</div>
              </div>
              <div class="text-right">
                <div class="text-sm text-slate-500">Vai trò của bạn</div>
                <div class="font-medium text-blue-600">Giảng viên hướng dẫn • Thành viên hội đồng</div>
                <div class="text-xs text-slate-500 mt-1">8 sinh viên hướng dẫn • 3 hội đồng tham gia</div>
              </div>
            </div>
          </section>
          <!-- Timeline -->
          <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-6">
              <h3 class="font-semibold">Tiến độ giai đoạn hướng dẫn</h3>
              <div class="flex items-center gap-2 text-sm">
                <span class="font-medium" id="progressText">25%</span>
                <div class="w-40 h-2 rounded-full bg-slate-100 overflow-hidden">
                  <div class="h-full bg-blue-600" id="progressBar" style="width:25%"></div>
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
                <div class="timeline-stage cursor-pointer" data-stage="1" onclick="showStageDetails(1)">
                  <div
                    class="w-12 h-12 mx-auto bg-emerald-600 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    1</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Tiếp nhận</div>
                    <div class="text-xs text-emerald-600 mt-1">Hoàn thành</div>
                  </div>
                </div>
                <!-- Stage 2 -->
                <div class="timeline-stage cursor-pointer" data-stage="2" onclick="showStageDetails(2)">
                  <div
                    class="w-12 h-12 mx-auto bg-blue-600 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    2</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Đề cương</div>
                    <div class="text-xs text-blue-600 mt-1">Đang diễn ra</div>
                  </div>
                </div>
                <!-- Stage 3 -->
                <div class="timeline-stage cursor-pointer" data-stage="3" onclick="showStageDetails(3)">
                  <div
                    class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    3</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Nhật ký</div>
                    <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                  </div>
                </div>
                <!-- Stage 4 -->
                <div class="timeline-stage cursor-pointer" data-stage="4" onclick="showStageDetails(4)">
                  <div
                    class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    4</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Chấm báo cáo</div>
                    <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                  </div>
                </div>
                <!-- Stage 5 -->
                <div class="timeline-stage cursor-pointer" data-stage="5" onclick="showStageDetails(5)">
                  <div
                    class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    5</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Hội đồng</div>
                    <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                  </div>
                </div>
                <!-- Stage 6 -->
                <div class="timeline-stage cursor-pointer" data-stage="6" onclick="showStageDetails(6)">
                  <div
                    class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    6</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Phản biện</div>
                    <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                  </div>
                </div>
                <!-- Stage 7 -->
                <div class="timeline-stage cursor-pointer" data-stage="7" onclick="showStageDetails(7)">
                  <div
                    class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    7</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Công bố</div>
                    <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                  </div>
                </div>
                <!-- Stage 8 -->
                <div class="timeline-stage cursor-pointer" data-stage="8" onclick="showStageDetails(8)">
                  <div
                    class="w-12 h-12 mx-auto bg-slate-300 rounded-full flex items-center justify-center text-white font-medium text-sm relative z-10 hover:scale-110 transition-transform">
                    8</div>
                  <div class="text-center mt-2">
                    <div class="text-xs font-medium text-slate-900">Bảo vệ</div>
                    <div class="text-xs text-slate-600 mt-1">Sắp tới</div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Timeline Details Panel -->
            <div class="mt-8 p-6 bg-slate-50 rounded-lg" id="timelineDetails">
              <div id="stageContent">
                <div class="text-center text-slate-500">
                  <i class="ph ph-cursor-click text-2xl mb-2"></i>
                  <p>Click vào một giai đoạn để xem chi tiết chức năng</p>
                </div>
              </div>
            </div>
            <!-- Legend -->
            <div class="mt-6 text-xs text-slate-500 flex flex-wrap gap-4">
              <span class="inline-flex items-center gap-1"><span
                  class="h-2.5 w-2.5 rounded-full bg-emerald-600"></span>Hoàn thành</span>
              <span class="inline-flex items-center gap-1"><span
                  class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>Đang diễn ra</span>
              <span class="inline-flex items-center gap-1"><span
                  class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>Sắp tới</span>
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>
  <script></script>
  <script>
    function showStageDetails(stageNum) {
      const contentBox = document.getElementById("stageContent");
      if (!contentBox) return;
      switch (stageNum) {
        case 1:
          contentBox.innerHTML = "<h3 class='text-lg font-semibold mb-2'>Giai đoạn 01: Chuẩn bị</h3><p>Chuẩn bị danh sách sinh viên, phân công đề tài, và thông báo lịch trình.</p>";
          break;
        case 2:
          contentBox.innerHTML = "<h3 class='text-lg font-semibold mb-2'>Giai đoạn 02: Đề cương</h3><p>Tiếp nhận và duyệt đề cương từ sinh viên, thông báo kết quả.</p>";
          break;
        case 3:
          contentBox.innerHTML = "<h3 class='text-lg font-semibold mb-2'>Giai đoạn 03: Thực hiện học phần đồ án</h3><ul class='list-disc pl-5'><li>SV thực hiện theo từng tuần và khai báo nhật kí</li><li>GVHD theo dõi và phê duyệt nhật kí</li><li>Trợ lý khoa theo dõi tiến độ, nhắc nhở, hỗ trợ xử lý vấn đề</li></ul>";
          break;
        case 4:
          contentBox.innerHTML = "<h3 class='text-lg font-semibold mb-2'>Giai đoạn 04: Báo cáo</h3><p>Tổng hợp và duyệt báo cáo trước khi bảo vệ.</p>";
          break;
        default:
          contentBox.innerHTML = "<p>Chưa có thông tin cho giai đoạn này.</p>";
      }
      // Highlight active stage
      document.querySelectorAll('.timeline-stage').forEach(el => el.classList.remove('active'));
      const activeStage = document.querySelector('.timeline-stage:nth-child(' + stageNum + ')');
      if (activeStage) activeStage.classList.add('active');
    }
  </script>
</body>

</html>