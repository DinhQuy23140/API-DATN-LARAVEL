<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đề xuất danh sách đề tài</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
  <!-- SheetJS for Excel import -->
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
    .sidebar-collapsed .sidebar-label { display:none; }
    .sidebar-collapsed .sidebar { width:72px; }
    .sidebar { width:260px; }
    /* Modern modal */
    .modal-overlay {
      animation: modalFade .25s ease;
    }
    .modal-shell {
      animation: modalPop .28s cubic-bezier(.4,.2,.2,1);
    }
    @keyframes modalFade {
      from { opacity:0; }
      to { opacity:1; }
    }
    @keyframes modalPop {
      0% { opacity:0; transform:translateY(8px) scale(.96); }
      100% { opacity:1; transform:translateY(0) scale(1); }
    }
    .floating-label { position:relative; }
    .floating-label input,
    .floating-label textarea,
    .floating-label select {
      padding-top:1.35rem;
    }
    .floating-label label {
      position:absolute; left:.75rem; top:.65rem;
      font-size:.70rem; letter-spacing:.5px;
      font-weight:500; text-transform:uppercase;
      color:rgb(100 116 139);
      pointer-events:none;
    }
    .tag-chip {
      @apply px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700 border border-slate-200;
    }
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
      $teacherId = $user->teacher->id ?? null;
      $avatarUrl = $user->avatar_url
        ?? $user->profile_photo_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
      $departmentRole = $user->teacher->departmentRoles->where('role', 'head')->first() ?? null;
      $departmentId = $departmentRole?->department_id ?? 0;
    @endphp
<body class="bg-slate-50 text-slate-800">
  <div class="flex min-h-screen">
    <aside class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all"
      id="sidebar">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i
            class="ph ph-chalkboard-teacher"></i></div>
        <div class="sidebar-label">
          <div class="font-semibold">Giảng viên</div>
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
          @if ($departmentRole)
          <a href="{{ route('web.teacher.all_thesis_rounds', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold {{ $isThesisRoundsActive ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
            @if($isThesisRoundsActive) aria-current="page" @endif>
            <i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span>
          </a>
          @else
          <a href="{{ route('web.teacher.thesis_rounds', ['teacherId' => $teacherId]) }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold {{ $isThesisRoundsActive ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}"
            @if($isThesisRoundsActive) aria-current="page" @endif>
            <i class="ph ph-calendar"></i><span class="sidebar-label">Đồ án tốt nghiệp</span>
          </a>
          @endif
        </div>
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
          <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
          <div>
            <h1 class="text-lg md:text-xl font-semibold">Đề xuất danh sách đề tài</h1>
            <nav class="text-xs text-slate-500 mt-0.5">
              <a href="overview.html" class="hover:underline text-slate-600">Trang chủ</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Học phần tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="thesis-rounds.html" class="hover:underline text-slate-600">Đồ án tốt nghiệp</a>
              <span class="mx-1">/</span>
              <a href="" class="hover:underline text-slate-600">Chi tiết đợt đồ án</a>
              <span class="mx-1">/</span>
              <span class="text-slate-500">Đề xuất đề tài</span>
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
            <form id="logout-form" action="{{ route('web.auth.logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto px-4 md:px-6 py-6">
        <div class="max-w-6xl mx-auto">
          <div class="flex items-center justify-between mb-4">
            <div></div>
            <a href="thesis-round-detail.html" class="text-sm text-blue-600 hover:underline"><i class="ph ph-caret-left"></i> Quay lại đợt</a>
          </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4" id="stats">
      <div class="bg-blue-50 p-4 rounded-lg flex items-center gap-3"><div class="h-10 w-10 rounded-lg bg-blue-600/10 text-blue-600 grid place-items-center"><i class="ph ph-list-bullets"></i></div><div><div class="text-2xl font-bold text-blue-600" id="stTotal">0</div><div class="text-sm text-blue-800">Tổng đề tài</div></div></div>
      <div class="bg-green-50 p-4 rounded-lg flex items-center gap-3"><div class="h-10 w-10 rounded-lg bg-green-600/10 text-green-600 grid place-items-center"><i class="ph ph-play"></i></div><div><div class="text-2xl font-bold text-green-600" id="stOpen">0</div><div class="text-sm text-green-800">Đang mở</div></div></div>
      <div class="bg-slate-50 p-4 rounded-lg flex items-center gap-3"><div class="h-10 w-10 rounded-lg bg-slate-600/10 text-slate-700 grid place-items-center"><i class="ph ph-users-three"></i></div><div><div class="text-2xl font-bold text-slate-700" id="stSlots">0</div><div class="text-sm text-slate-700">Tổng chỉ tiêu</div></div></div>
      <div class="bg-purple-50 p-4 rounded-lg flex items-center gap-3"><div class="h-10 w-10 rounded-lg bg-purple-600/10 text-purple-600 grid place-items-center"><i class="ph ph-user-plus"></i></div><div><div class="text-2xl font-bold text-purple-600" id="stReg">0</div><div class="text-sm text-purple-800">Đã đăng ký</div></div></div>
    </div>

    <!-- Controls -->
    <div class="bg-white border rounded-xl p-3 mb-3">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
          <div class="relative">
            <i class="ph ph-magnifying-glass absolute left-2 top-2.5 text-slate-400"></i>
            <input id="searchBox" class="pl-8 pr-3 py-2 border border-slate-200 rounded text-sm w-64" placeholder="Tìm theo tiêu đề/thẻ" />
          </div>
          <select id="statusFilter" class="px-3 py-2 border border-slate-200 rounded text-sm">
            <option value="">Tất cả trạng thái</option>
            <option value="Mở">Mở</option>
            <option value="Đóng">Đóng</option>
          </select>
          <button id="resetBtn" class="px-2 py-1 text-sm text-slate-600 hover:text-slate-800">Đặt lại</button>
        </div>
        <div class="flex items-center gap-2">
          <button id="btnImport" class="px-3 py-1.5 border border-slate-200 rounded text-sm"><i class="ph ph-upload-simple"></i> Import Excel</button>
          <button id="btnTemplate" class="px-3 py-1.5 border border-slate-200 rounded text-sm"><i class="ph ph-download-simple"></i> Tải mẫu</button>
          <button id="btnAdd" class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm"><i class="ph ph-plus"></i> Thêm đề tài</button>
        </div>
      </div>
    </div>

    <!-- Topics list rendered server-side -->
    <div id="topicsList" class="grid grid-cols-1 gap-3">
      @php
        // provide fallback topics when controller does not pass $topics
        $defaultTopics = [
          ['id'=>'T001','title'=>'Hệ thống quản lý học tập trực tuyến (LMS)','description'=>'Xây dựng LMS với quản lý khóa học, bài tập, đánh giá; ưu tiên stack Node.js + React.','tags'=>['Web','Node.js','React'],'slots'=>2,'registered'=>1,'status'=>'Mở','updatedAt'=>'15/07/2025'],
          ['id'=>'T002','title'=>'Ứng dụng thương mại điện tử','description'=>'E-commerce full-stack, tích hợp thanh toán, quản lý đơn hàng.','tags'=>['Web','React'],'slots'=>3,'registered'=>2,'status'=>'Mở','updatedAt'=>'20/07/2025'],
        ];
        $topicsToShow = isset($topics) ? $topics : $defaultTopics;
      @endphp

      @foreach($proposedTopics as $t)
        @php
          // allow both array and object shapes
          $id = is_object($t) ? ($t->id ?? '') : ($t['id'] ?? '');
          $title = is_object($t) ? ($t->title ?? '') : ($t['title'] ?? '');
          $description = is_object($t) ? ($t->description ?? '') : ($t['description'] ?? '');
          $updatedAt = is_object($t) ? ($t->updatedAt ?? ($t->updated_at ?? '')) : ($t['updatedAt'] ?? ($t['updated_at'] ?? ''));
        @endphp

        <article data-topic-id="{{ $id }}"  class="border rounded-xl p-4 bg-white shadow-sm hover:shadow-md transition">
          <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 grid place-items-center"><i class="ph ph-notebook text-lg"></i></div>
                <div>
                  <h5 class="font-semibold text-slate-800">{{ $title }}</h5>
                  <div class="text-xs text-slate-500 mt-1 inline-flex items-center gap-2"><i class="ph ph-calendar text-slate-400"></i><span>Cập nhật: {{ $updatedAt }}</span></div>
                </div>
              </div>
              <p class="text-sm text-slate-600 mt-3">{{ $description }}</p>
            </div>
            <aside class="w-40 flex-shrink-0 text-right">
              <div class="mt-3 flex justify-end gap-2">
                <button data-id="{{ $id }}" class="edit-topic-btn px-2 py-1 text-sm bg-yellow-50 text-yellow-700 rounded hover:bg-yellow-100 flex items-center gap-2"><i class="ph ph-pencil"></i><span class="hidden sm:inline">Sửa</span></button>
                <button data-id="{{ $id }}" class="delete-topic-btn px-2 py-1 text-sm bg-rose-50 text-rose-700 rounded hover:bg-rose-100 flex items-center gap-2"><i class="ph ph-trash"></i><span class="hidden sm:inline">Xóa</span></button>
              </div>
            </aside>
          </div>
        </article>
      @endforeach
    </div>
  </div>

  <script>
    // DOM-based topic handlers (server-rendered cards)
    const listEl = document.getElementById('topicsList');
    const searchEl = document.getElementById('searchBox');
    const statusEl = document.getElementById('statusFilter');

    function computeStats(){
      const cards = Array.from(document.querySelectorAll('#topicsList [data-topic-id]'));
      const total = cards.length;
      const open = cards.filter(c => (c.dataset.status||'').trim() === 'Mở').length;
      const slots = cards.reduce((s,c) => s + Number(c.dataset.slots||0), 0);
      const reg = cards.reduce((s,c) => s + Number(c.dataset.registered||0), 0);
      document.getElementById('stTotal').textContent = total;
      document.getElementById('stOpen').textContent = open;
      document.getElementById('stSlots').textContent = slots;
      document.getElementById('stReg').textContent = reg;
    }

    // Create article element from payload
    function createArticleElement(t){
      const wrap = document.createElement('article');
      wrap.className = 'border rounded-xl p-4 bg-white shadow-sm hover:shadow-md transition';
      wrap.setAttribute('data-topic-id', t.id);
      wrap.setAttribute('data-status', t.status || 'Mở');
      wrap.setAttribute('data-slots', t.slots || 0);
      wrap.setAttribute('data-registered', t.registered || 0);
      const tagsHtml = (t.tags||[]).length ? (t.tags||[]).map(tag=>`<span class="tag-chip">${tag}</span>`).join(' ') : '<span class="text-xs text-slate-400">Chưa có thẻ</span>';
      wrap.innerHTML = `
        <div class="flex items-start justify-between gap-4">
          <div class="flex-1">
            <div class="flex items-center gap-3">
              <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 grid place-items-center"><i class="ph ph-notebook text-lg"></i></div>
              <div>
                <h5 class="font-semibold text-slate-800">${t.title}</h5>
                <div class="text-xs text-slate-500 mt-1 inline-flex items-center gap-2"><i class="ph ph-calendar text-slate-400"></i><span>Cập nhật: ${t.updatedAt||''}</span></div>
              </div>
            </div>
            <p class="text-sm text-slate-600 mt-3">${t.description||''}</p>
            <div class="mt-3 flex items-center gap-2 flex-wrap text-xs">
              <div class="inline-flex items-center gap-2 text-slate-500"><i class="ph ph-tag text-slate-400"></i>${tagsHtml}</div>
            </div>
          </div>
          <aside class="w-40 flex-shrink-0 text-right">
            <div class="text-sm text-slate-700"><i class="ph ph-users-three text-slate-400"></i> <span class="font-semibold">${t.registered||0}</span>/<span>${t.slots||0}</span></div>
            <div class="mt-2"><span class="px-2 py-1 rounded-full text-xs ${t.status==='Mở' ? 'bg-green-50 text-green-600' : 'bg-slate-100 text-slate-700'}">${t.status||'Mở'}</span></div>
            <div class="mt-3 flex justify-end gap-2">
              <button data-id="${t.id}" class="edit-topic-btn px-2 py-1 text-sm bg-yellow-50 text-yellow-700 rounded hover:bg-yellow-100 flex items-center gap-2"><i class="ph ph-pencil"></i><span class="hidden sm:inline">Sửa</span></button>
              <button data-id="${t.id}" class="delete-topic-btn px-2 py-1 text-sm bg-rose-50 text-rose-700 rounded hover:bg-rose-100 flex items-center gap-2"><i class="ph ph-trash"></i><span class="hidden sm:inline">Xóa</span></button>
            </div>
          </aside>
        </div>
      `;
      return wrap;
    }

    // Delegated edit/delete handlers (operate on DOM)
    listEl.addEventListener('click', (e)=>{
      const editBtn = e.target.closest('.edit-topic-btn');
      if(editBtn){
        const id = editBtn.getAttribute('data-id');
        openEditModalForId(id);
        return;
      }
      const delBtn = e.target.closest('.delete-topic-btn');
      if(delBtn){
        const id = delBtn.getAttribute('data-id');
        if(!confirm('Bạn có chắc muốn xóa đề tài này?')) return;
        const el = document.querySelector(`#topicsList [data-topic-id="${id}"]`);
        el?.remove();
        computeStats();
        return;
      }
    });

    function openEditModalForId(id){
      const el = document.querySelector(`#topicsList [data-topic-id="${id}"]`);
      if(!el) return alert('Không tìm thấy đề tài');
      const title = el.querySelector('h5')?.textContent || '';
      const desc = el.querySelector('p')?.textContent || '';
      const tags = Array.from(el.querySelectorAll('.tag-chip')).map(x=>x.textContent.trim()).join(', ');
      const slots = el.dataset.slots || 1;
      const status = el.dataset.status || 'Mở';

      const formHTML = `
        <form id="topicEditForm" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="floating-label">
              <label>Tiêu đề *</label>
              <input name="title" required maxlength="180" class="${baseInputCls()}" placeholder=" " value="${escapeHtml(title)}" />
            </div>
            <div class="floating-label">
              <label>Chỉ tiêu (SV)</label>
              <input type="number" name="slots" min="1" class="${baseInputCls()}" placeholder=" " value="${escapeHtml(slots)}" />
            </div>
          </div>
          <div class="floating-label">
            <label>Mô tả</label>
            <textarea name="desc" rows="5" class="${baseInputCls('resize-y')}" placeholder="">${escapeHtml(desc)}</textarea>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="floating-label md:col-span-2">
              <label>Thẻ (ngăn cách phẩy / chấm phẩy)</label>
              <input name="tags" class="${baseInputCls()}" placeholder=" " value="${escapeHtml(tags)}" />
              ${chipsPreviewTemplate()}
            </div>
            <div class="floating-label">
              <label>Trạng thái</label>
              <select name="status" class="${baseInputCls()}">
                <option value="Mở">Mở</option>
                <option value="Đóng">Đóng</option>
              </select>
            </div>
          </div>
        </form>
      `;
      const modal = createModal({ title: 'Sửa đề tài', content: formHTML });
      document.body.appendChild(modal.el);
      const form = modal.el.querySelector('#topicEditForm');
      const tagsInput = form.querySelector('input[name="tags"]');
      const tagsPreview = modal.el.querySelector('#tagsPreview');
      renderTagPreview(tagsPreview, tagsInput.value || '');
      tagsInput?.addEventListener('input', ()=> renderTagPreview(tagsPreview, tagsInput.value || ''));
      // set select value
      form.querySelector('select[name="status"]').value = status;

      form.addEventListener('submit', (e)=>{
        e.preventDefault();
        const fd = new FormData(form);
        const newTitle = String(fd.get('title')||'').trim();
        if(!newTitle) return alert('Tiêu đề không được để trống');
        el.querySelector('h5').textContent = newTitle;
        el.querySelector('p').textContent = String(fd.get('desc')||'').trim();
        const newTags = String(fd.get('tags')||'').split(/[;,]/).map(x=>x.trim()).filter(Boolean);
        const tagsContainer = el.querySelector('.tag-chip')?.parentElement || el.querySelector('.inline-flex');
        // rebuild tags
        const tagsWrap = el.querySelector('.inline-flex.items-center') || el.querySelector('.mt-3');
        const tagHtml = newTags.length ? newTags.map(t=>`<span class="tag-chip">${t}</span>`).join(' ') : '<span class="text-xs text-slate-400">Chưa có thẻ</span>';
        const tagsArea = el.querySelector('.mt-3 .inline-flex') || el.querySelector('.mt-3');
        if(tagsArea) tagsArea.innerHTML = `<i class="ph ph-tag text-slate-400"></i> ${tagHtml}`;
        el.dataset.slots = Number(fd.get('slots')||1);
        el.dataset.status = fd.get('status')||'Mở';
        // update status pill
        const pill = el.querySelector('aside .px-2.py-1');
        if(pill) pill.textContent = fd.get('status')||'Mở';
        modal.destroy();
        computeStats();
      });
    }

    // escape helper for inserting into HTML
    function escapeHtml(s){ return String(s||'').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;'); }

    // Add topic (uses createModal defined below)
    document.getElementById('btnAdd').addEventListener('click', () => {
      const formHTML = `
        <form id="topicForm" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="floating-label">
              <label>Tiêu đề *</label>
              <input name="title" required maxlength="180" class="${baseInputCls()}" placeholder=" " />
              <p class="mt-1 text-xs text-slate-500">Đặt tên rõ ràng, mô tả sản phẩm/kết quả cuối.</p>
            </div>
            <div class="floating-label">
              <label>Chỉ tiêu (SV)</label>
              <input type="number" name="slots" min="1" value="1" class="${baseInputCls()}" placeholder=" " />
            </div>
          </div>
          <div class="floating-label">
            <label>Mô tả</label>
            <textarea name="desc" rows="5" class="${baseInputCls('resize-y')}" placeholder=" "></textarea>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="floating-label md:col-span-2">
              <label>Thẻ (ngăn cách phẩy / chấm phẩy)</label>
              <input name="tags" class="${baseInputCls()}" placeholder=" " />
              ${chipsPreviewTemplate()}
            </div>
            <div class="floating-label">
              <label>Trạng thái</label>
              <select name="status" class="${baseInputCls()}">
                <option value="Mở">Mở</option>
                <option value="Đóng">Đóng</option>
              </select>
            </div>
          </div>
        </form>
      `;
      const modal = createModal({ title: 'Thêm đề tài mới', content: formHTML });
      document.body.appendChild(modal.el);

      // Preview tags
      const tagsInput = modal.el.querySelector('input[name="tags"]');
      const tagsPreview = modal.el.querySelector('#tagsPreview');
      const updateTags = () => renderTagPreview(tagsPreview, tagsInput.value || '');
      tagsInput?.addEventListener('input', updateTags);
      updateTags();

      modal.el.querySelector('#topicForm')?.addEventListener('submit', (e) => {
        e.preventDefault();
        const fd = new FormData(e.target);
        const title = String(fd.get('title')||'').trim();
        if(!title) return;
        const payload = {
          id: 'T' + Math.floor(Math.random()*900+100),
          title,
          description: String(fd.get('desc')||'').trim(),
          tags: String(fd.get('tags')||'').split(',').map(x=>x.trim()).filter(Boolean),
          slots: Math.max(1, Number(fd.get('slots')||1)),
          status: fd.get('status')||'Mở',
          registered: 0,
          updatedAt: new Date().toLocaleDateString('vi-VN')
        };
        const node = createArticleElement(payload);
        listEl.prepend(node);
        modal.destroy();
        computeStats();
      });
    });

    // Import from Excel: create DOM nodes per row
    document.getElementById('btnImport').addEventListener('click', ()=>{
      const html = `
        <div class="space-y-3">
          <div class="text-sm text-slate-600">Chọn file Excel (.xlsx) hoặc CSV với các cột: <strong>Title, Description, Tags, Slots, Status</strong>.</div>
          <input id="fileInput" type="file" accept=".xlsx,.csv" class="block w-full text-sm" />
          <div class="text-xs text-slate-500">Gợi ý: Tags phân cách bằng dấu phẩy hoặc chấm phẩy. Status: Mở/Đóng.</div>
          <div class="flex gap-2 pt-1">
            <button id="importBtn" class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm"><i class="ph ph-upload"></i> Import</button>
            <button id="cancelImp" class="px-3 py-1.5 border border-slate-200 rounded text-sm">Hủy</button>
          </div>
        </div>`;
      const m = createModal({ title: 'Import danh sách đề tài', content: html });
      document.body.appendChild(m.el || m);
      const root = m.el || m; // support earlier return shapes
      root.querySelector('#cancelImp').addEventListener('click',()=> root.remove());
      root.querySelector('#importBtn').addEventListener('click',()=>{
        const file = root.querySelector('#fileInput').files[0];
        if(!file){ alert('Vui lòng chọn file'); return; }
        const reader = new FileReader();
        reader.onload = (e)=>{
          try{
            let rows = [];
            if(file.name.toLowerCase().endsWith('.csv')){
              const text = e.target.result;
              const lines = String(text).split(/\r?\n/).filter(Boolean);
              const header = lines.shift().split(',').map(h=>h.trim());
              rows = lines.map(line=>{
                const cols = line.split(',');
                const obj = {};
                header.forEach((h,i)=>obj[h] = cols[i]);
                return obj;
              });
            } else {
              const data = new Uint8Array(e.target.result);
              const wb = XLSX.read(data, {type:'array'});
              const ws = wb.Sheets[wb.SheetNames[0]];
              rows = XLSX.utils.sheet_to_json(ws, {defval:'', raw:false});
            }
            const added = [];
            rows.forEach(r=>{
              const title = (r.Title||r.title||'').trim();
              if(!title) return;
              const desc = (r.Description||r.description||'').trim();
              const tags = String(r.Tags||r.tags||'').split(/[;,]/).map(x=>x.trim()).filter(Boolean);
              const slots = Math.max(1, Number(r.Slots||r.slots||1));
              const status = ((r.Status||r.status||'Mở').trim()==='Đóng')?'Đóng':'Mở';
              const payload = { id:'T'+Math.floor(Math.random()*900+100), title, description:desc, tags, slots, status, registered:0, updatedAt: new Date().toLocaleDateString('vi-VN') };
              const node = createArticleElement(payload);
              listEl.prepend(node);
              added.push(title);
            });
            root.remove();
            computeStats();
            if(added.length) alert(`Đã import ${added.length} đề tài.`);
          }catch(err){ console.error(err); alert('Không thể đọc file. Vui lòng kiểm tra định dạng.'); }
        };
        if(file.name.toLowerCase().endsWith('.csv')) reader.readAsText(file);
        else reader.readAsArrayBuffer(file);
      });
    });

    // Download template (CSV)
    document.getElementById('btnTemplate').addEventListener('click', ()=>{
      const header = 'Title,Description,Tags,Slots,Status\n';
      const example = 'Hệ thống quản lý thư viện,Mô tả ví dụ,Web;React;Node.js,2,Mở\n';
      const blob = new Blob([header + example], {type:'text/csv;charset=utf-8;'});
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url; a.download = 'topics_template.csv'; a.click();
      URL.revokeObjectURL(url);
    });

    // Filters: search and status filter operate on DOM elements
    document.getElementById('resetBtn').addEventListener('click',()=>{ if(searchEl) searchEl.value=''; if(statusEl) statusEl.value=''; applyFilters(); });
    searchEl?.addEventListener('input', applyFilters);
    statusEl?.addEventListener('change', applyFilters);

    function applyFilters(){
      const q = (searchEl?.value||'').toLowerCase().trim();
      const st = (statusEl?.value||'').trim();
      document.querySelectorAll('#topicsList [data-topic-id]').forEach(card=>{
        const text = (card.innerText||'').toLowerCase();
        const matchesQ = !q || text.includes(q);
        const matchesStatus = !st || (card.dataset.status||'') === st;
        card.style.display = (matchesQ && matchesStatus) ? '' : 'none';
      });
      computeStats();
    }

    // Initial compute
    computeStats();

    // Sidebar/header interactions (outside of templates)
    (function(){
      const html = document.documentElement;
      const sidebar = document.getElementById('sidebar');
      const headerEl = document.querySelector('header');
      const wrapper = headerEl ? headerEl.parentElement : null; // the container with md:pl-*

      function setCollapsed(c){
        if(c){
          html.classList.add('sidebar-collapsed');
          // adjust wrapper padding if available
          if(wrapper){
            wrapper.classList.remove('md:pl-[260px]');
            wrapper.classList.add('md:pl-[72px]');
          }
        } else {
          html.classList.remove('sidebar-collapsed');
          if(wrapper){
            wrapper.classList.remove('md:pl-[72px]');
            wrapper.classList.add('md:pl-[260px]');
          }
        }
      }

      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{
        const c = !html.classList.contains('sidebar-collapsed');
        setCollapsed(c);
        localStorage.setItem('lecturer_sidebar', c ? '1' : '0');
      });
      document.getElementById('openSidebar')?.addEventListener('click',()=> sidebar?.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      sidebar?.classList.add('md:translate-x-0','-translate-x-full','md:static');

      const profileBtn = document.getElementById('profileBtn');
      const profileMenu = document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu?.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });
    })();
  </script>
</body>
</html>
