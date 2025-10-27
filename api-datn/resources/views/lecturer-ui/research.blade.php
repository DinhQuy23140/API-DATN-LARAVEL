<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nghiên cứu - Giảng viên</title>
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
    @php
      $user = auth()->user();
      $user_id = $user->id ?? null;
      $userName = $user->fullname ?? $user->name ?? 'Giảng viên';
      $email = $user->email ?? '';
      // Tùy mô hình dữ liệu, thay các field bên dưới cho khớp
      $dept = $user->department_name ?? optional($user->teacher)->department ?? '';
      $faculty = $user->faculty_name ?? optional($user->teacher)->faculty ?? '';
      $subtitle = trim(($dept ? "Bộ môn $dept" : '') . (($dept && $faculty) ? ' • ' : '') . ($faculty ? "Khoa $faculty" : ''));
      $degree = $user->teacher->degree ?? '';
      $expertise = $user->teacher->supervisor->expertise ?? 'null';
      $data_assignment_supervisors = $user->teacher->supervisor->assignment_supervisors ?? "null";
      $supervisorId = $user->teacher->supervisor->id ?? null;
      $teacherId = $user->teacher->id ?? null;
      $avatarUrl = $user->avatar_url
        ?? $user->profile_photo_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0ea5e9&color=ffffff';
      $departmentRole = $user->teacher->departmentRoles->where('role', 'head')->first() ?? null;
      $departmentId = $departmentRole->department_id;
    @endphp
    <div class="flex min-h-screen">
      <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col transition-all">
        <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
          <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-chalkboard-teacher"></i></div>
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

          <button type="button" id="toggleThesisMenu"
                  class="w-full flex items-center justify-between px-3 py-2 rounded-lg mt-3
                         {{ $isThesisOpen ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <span class="flex items-center gap-3"><i class="ph ph-graduation-cap"></i><span class="sidebar-label">Học phần tốt nghiệp</span></span>
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
          <button id="toggleSidebar" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg"><i class="ph ph-sidebar"></i><span class="sidebar-label">Thu gọn</span></button>
        </div>
      </aside>

  <div class="flex-1">
        <header class="fixed left-0 md:left-[260px] right-0 top-0 h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6 z-20">
          <div class="flex items-center gap-3 flex-1">
            <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-slate-100"><i class="ph ph-list"></i></button>
            <div>
              <h1 class="text-lg md:text-xl font-semibold">Hướng nghiên cứu</h1>
              <nav class="text-xs text-slate-500 mt-0.5">Trang chủ / Giảng viên / Hướng nghiên cứu</nav>
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

<main class="pt-20 px-4 md:px-6 pb-10 bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
  <div class="max-w-6xl mx-auto space-y-8">

    <!-- 🔹 Section: Chọn hướng nghiên cứu -->
    <section class="bg-white/90 backdrop-blur-sm rounded-2xl border border-slate-200 shadow-lg p-6">
      <div class="flex items-center gap-3 mb-5">
        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 grid place-items-center text-white shadow">
          <i class="ph ph-brain text-lg"></i>
        </div>
        <h2 class="text-xl font-semibold text-slate-700">Chọn hướng nghiên cứu</h2>
      </div>

      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-medium text-slate-600">Chọn hướng nghiên cứu có sẵn</label>
          <div class="mt-1 flex gap-2">
            <select id="researchSelect" class="flex-1 px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
              <option value="" disabled selected>-- Chọn hướng nghiên cứu --</option>
              @foreach ($listResearch as $research)
                @php
                  $isSelected = $userResearch->userResearches->contains('research_id', $research->id);
                @endphp
                @unless ($isSelected)
                  <option value="{{ $research->id }}">{{ $research->name }}</option>
                @endunless
              @endforeach
            </select>
            <button id="addResearchBtn" class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">Thêm</button>
          </div>
        </div>
      </div>

      <!-- 🔸 Hiển thị thông tin hướng nghiên cứu -->
      <div id="researchInfo" class="hidden mt-6 border border-blue-100 bg-gradient-to-br from-blue-50 to-blue-100/50 p-4 rounded-xl shadow-inner">
        <div class="flex items-start gap-3">
          <div class="h-10 w-10 rounded-lg bg-blue-500 grid place-items-center text-white shadow-sm">
            <i class="ph ph-lightbulb text-lg"></i>
          </div>
          <div>
            <h3 id="researchTitle" class="font-semibold text-blue-800 text-lg">—</h3>
            <p id="researchDesc" class="text-sm text-slate-600 mt-1 leading-relaxed">—</p>
          </div>
        </div>
      </div>
    </section>

    <!-- 🔹 Section: Danh sách các hướng nghiên cứu đã có -->
    <section class="bg-white/95 rounded-2xl border border-slate-200 shadow-lg p-6">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 grid place-items-center text-white shadow">
            <i class="ph ph-list-bullets text-lg"></i>
          </div>
          <h2 class="text-xl font-semibold text-slate-700">Danh sách hướng nghiên cứu</h2>
        </div>

        <div class="relative">
          <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
          <input id="searchBox" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 text-sm" placeholder="Tìm theo tiêu đề, từ khóa..." />
        </div>
      </div>

<div class="overflow-x-auto rounded-lg border border-slate-100">
  <table class="w-full min-w-[600px] text-sm border-collapse">
    <thead class="bg-slate-50 text-slate-600">
      <tr>
        <th class="py-3 px-4 text-left font-medium whitespace-nowrap">Hướng nghiên cứu</th>
        <th class="py-3 px-4 text-left font-medium whitespace-nowrap">Mô tả</th>
        <th class="py-3 px-4 text-right font-medium whitespace-nowrap">Hành động</th>
      </tr>
    </thead>
    <tbody id="listBody" class="divide-y divide-slate-100">
      @if ($userResearch && $userResearch->userResearches->count() > 0)
        @foreach ($userResearch->userResearches as $result)
          <tr class="hover:bg-slate-50 transition">
            <td class="py-3 px-4 text-left font-medium align-top">{{ $result->research->name }}</td>
            <td class="py-3 px-4 text-left align-top break-words max-w-xs md:max-w-md">
              {{ $result->research->description }}
            </td>
            <td class="py-3 px-4 text-right whitespace-nowrap">
              <button class="text-blue-600 hover:text-blue-800 mr-2"><i class="ph ph-eye"></i></button>
              <button class="text-amber-500 hover:text-amber-700 mr-2"><i class="ph ph-pencil"></i></button>
              <!-- server-backed row: pass server id to openDelete -->
              <button onclick="openDelete('srv_{{ $result->id }}')" class="text-rose-600 hover:text-rose-700"><i class="ph ph-trash"></i></button>
            </td>
          </tr>
        @endforeach
      @else
        <tr>
          <td colspan="3" class="text-center py-6 text-slate-500 text-sm">
            <i class="ph ph-info text-lg text-blue-500"></i>
            <div class="mt-2">Chưa có hướng nghiên cứu nào được thêm vào.</div>
          </td>
        </tr>
      @endif
    </tbody>
  </table>
</div>

    </section>
  </div>
</main>

@php
$researchData = $listResearch->map(fn($r) => [
    'id' => $r->id,
    'title' => $r->name,
    'desc' => $r->description,
]);
@endphp

<script>
  const researchData = @json($researchData);

  // Server-rendered user research items. Use these when localStorage has no items
  const serverResearchItems = [
  @foreach ($userResearch->userResearches as $result)
    {
      // id uses the UserResearch pivot id so we can delete the correct record on server
      id: {!! json_encode('srv_' . ($result->id)) !!},
      title: {!! json_encode($result->research->name ?? '') !!},
      description: {!! json_encode($result->research->description ?? '') !!},
      fields: [],
      keywords: []
    },
  @endforeach
  ];
  // CSRF token and endpoint base URL used by client-side delete
  const CSRF_TOKEN = {!! json_encode(csrf_token()) !!};
  // Named routes for store/destroy actions
  const userResearchStoreUrl = {!! json_encode(route('web.teacher.user_research.store')) !!};
  const userResearchDestroyUrl = {!! json_encode(url('/teacher/user-research')) !!};

  const select = document.getElementById("researchSelect");
  const infoBox = document.getElementById("researchInfo");
  const titleEl = document.getElementById("researchTitle");
  const descEl = document.getElementById("researchDesc");

  select.addEventListener("change", (e) => {
    const selectedId = e.target.value;
    const data = researchData.find(r => r.id == selectedId);

    if (data) {
      titleEl.textContent = data.title || "Không có tiêu đề";
      descEl.textContent = data.desc || "Chưa có mô tả cho hướng nghiên cứu này.";
      infoBox.classList.remove("hidden");
      infoBox.classList.add("animate-fadeIn");
    } else {
      infoBox.classList.add("hidden");
    }
  });

  // Add selected research via AJAX to server
  document.getElementById('addResearchBtn')?.addEventListener('click', async (e) => {
    e.preventDefault();
    const sel = document.getElementById('researchSelect');
    const researchId = sel?.value;
    if(!researchId){ alert('Vui lòng chọn hướng nghiên cứu'); return; }
    const btn = document.getElementById('addResearchBtn');
    btn.disabled = true; btn.textContent = 'Đang thêm...';
    try{
      const res = await fetch(userResearchStoreUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ research_id: researchId })
      });
      if(!res.ok) throw new Error('Server returned ' + res.status);
      const data = await res.json();
      if(data.success === false){
        alert(data.message || 'Không thể thêm hướng nghiên cứu');
      } else {
        // accept several possible shapes from server: { item: { id } }, { id }, { user_research: { id } }
        const newId = (data.item && data.item.id) ? data.item.id : (data.id ? data.id : (data.user_research && data.user_research.id ? data.user_research.id : null));
        const rd = researchData.find(r => r.id == researchId) || { title: sel.selectedOptions[0]?.text || '', desc: '' };
        const entry = {
          id: newId ? ('srv_' + newId) : ('srv_' + Date.now()),
          title: rd.title || rd.name || sel.selectedOptions[0]?.text || '',
          description: rd.desc || rd.description || '' ,
          fields: [], keywords: []
        };
        // add to serverResearchItems and re-render
        serverResearchItems.unshift(entry);
        renderList(document.getElementById('searchBox').value||'');
        // optionally remove the added option from the select so it can't be added twice
        const opt = sel.querySelector('option[value="'+researchId+'"]'); if(opt) opt.remove();
      }
    }catch(err){
      console.error(err);
      alert('Lỗi khi thêm hướng nghiên cứu.');
    } finally {
      btn.disabled = false; btn.textContent = 'Thêm';
    }
  });
</script>

<style>
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .animate-fadeIn {
    animation: fadeIn 0.3s ease-out forwards;
  }
</style>

      </div>
    </div>

    <script>
      const html=document.documentElement, sidebar=document.getElementById('sidebar');
  function setCollapsed(c){const h=document.querySelector('header');const m=document.querySelector('main'); if(c){html.classList.add('sidebar-collapsed');h.classList.add('md:left-[72px]');h.classList.remove('md:left-[260px]');m.classList.add('md:pl-[72px]');m.classList.remove('md:pl-[260px]');} else {html.classList.remove('sidebar-collapsed');h.classList.remove('md:left-[72px]');h.classList.add('md:left-[260px]');m.classList.remove('md:pl-[72px]');m.classList.add('md:pl-[260px]');}}
      document.getElementById('toggleSidebar')?.addEventListener('click',()=>{const c=!html.classList.contains('sidebar-collapsed');setCollapsed(c);localStorage.setItem('lecturer_sidebar',''+(c?1:0));});
      document.getElementById('openSidebar')?.addEventListener('click',()=>sidebar.classList.toggle('-translate-x-full'));
      if(localStorage.getItem('lecturer_sidebar')==='1') setCollapsed(true);
      sidebar.classList.add('md:translate-x-0','-translate-x-full','md:static');

      // profile dropdown
      const profileBtn=document.getElementById('profileBtn');
      const profileMenu=document.getElementById('profileMenu');
      profileBtn?.addEventListener('click', ()=> profileMenu.classList.toggle('hidden'));
      document.addEventListener('click', (e)=>{ if(!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) profileMenu?.classList.add('hidden'); });

      // Storage helpers
      const STORAGE_KEY='lecturer_research_items';
      function loadItems(){try{const s=localStorage.getItem(STORAGE_KEY);return s?JSON.parse(s):[];}catch{ return []}}
      function saveItems(items){localStorage.setItem(STORAGE_KEY, JSON.stringify(items));}

  function escapeHtml(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

      // UI helpers
      function renderList(filter=''){
            const tbody=document.getElementById('listBody');
            // Prefer client-side stored items; if none exist, use server-rendered items
            let items = loadItems();
            if(!items || items.length === 0){
              items = (typeof serverResearchItems !== 'undefined' && serverResearchItems.length) ? serverResearchItems.slice() : [];
            }
            const q=String(filter||'').trim().toLowerCase();
            const filtered = items.filter(it => {
              const title = String(it.title || '').toLowerCase();
              const desc = String(it.description || '').toLowerCase();
              const kwRaw = Array.isArray(it.keywords) ? it.keywords.join(',') : String(it.keywords || '');
              const keywords = String(kwRaw).toLowerCase();
              return title.includes(q) || desc.includes(q) || keywords.includes(q);
            });

            // clear existing rows before rendering
            tbody.innerHTML = '';

            if(filtered.length === 0){
              // show empty state row matching server-side markup
              const tr = document.createElement('tr');
              tr.innerHTML = `
                <td colspan="3" class="text-center py-6 text-slate-500 text-sm">
                  <i class="ph ph-info text-lg text-blue-500"></i>
                  <div class="mt-2">Chưa có hướng nghiên cứu nào được thêm vào.</div>
                </td>`;
              tbody.appendChild(tr);
              return;
            }

            for(const it of filtered){
              const tr=document.createElement('tr'); tr.className='hover:bg-slate-50 transition';
              tr.innerHTML=`
                <td class="py-3 px-4 text-left font-medium">
                  ${escapeHtml(it.title || '')}
                </td>
                <td class="py-3 px-4 text-left">${it.description ? escapeHtml(it.description) : ''}</td>
                <td class="py-3 px-4 text-right">
                  <button class="text-blue-600 hover:text-blue-800 mr-2"><i class="ph ph-eye"></i></button>
                  <button class="text-amber-500 hover:text-amber-700 mr-2"><i class="ph ph-pencil"></i></button>
                  <button onclick="openDelete('${it.id}')" class="text-rose-600 hover:text-rose-700"><i class="ph ph-trash"></i></button>
                </td>`;
              tbody.appendChild(tr);
            }
      }

      // Actions
      function addItem(){
        const title=document.getElementById('titleInput').value.trim();
        const desc=document.getElementById('descInput').value.trim();
        const fields=[...document.getElementById('fieldsInput').selectedOptions].map(o=>o.value);
        const keywords=document.getElementById('keywordsInput').value.split(',').map(s=>s.trim()).filter(Boolean);
        if(!title) return;
        const items=loadItems();
        items.unshift({id:crypto.randomUUID?crypto.randomUUID():('id_'+Date.now()), title, description:desc, fields, keywords});
        saveItems(items);
        renderList(document.getElementById('searchBox').value||'');
        document.getElementById('createForm').reset();
        localStorage.setItem('lecturer_research_last_update', Date.now().toString());
      }

      // Edit modal
      const editModalHtml=`
        <div id="editModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">
          <div class="bg-white rounded-xl w-full max-w-2xl shadow-xl">
            <div class="p-4 border-b flex items-center justify-between">
              <h3 class="font-semibold">Chỉnh sửa hướng nghiên cứu</h3>
              <button class="p-2 hover:bg-slate-100 rounded-lg" onclick="closeEdit()"><i class=\"ph ph-x\"></i></button>
            </div>
            <form class="p-4 space-y-4" onsubmit="event.preventDefault(); saveEdit();">
              <input type="hidden" id="editId" />
              <div>
                <label class="text-sm font-medium">Tiêu đề</label>
                <input id="editTitle" required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600" />
              </div>
              <div>
                <label class="text-sm font-medium">Mô tả</label>
                <textarea id="editDesc" rows="5" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200"></textarea>
              </div>
              <div>
                <label class="text-sm font-medium">Lĩnh vực</label>
                <select id="editFields" multiple class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200">
                  <option>AI</option><option>Data Science</option><option>Hệ phân tán</option><option>IoT</option>
                </select>
              </div>
              <div>
                <label class="text-sm font-medium">Từ khóa</label>
                <input id="editKeywords" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200" placeholder="AI, NLP, ..." />
              </div>
              <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeEdit()" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Hủy</button>
                <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Lưu</button>
              </div>
            </form>
          </div>
        </div>`;
      document.body.insertAdjacentHTML('beforeend', editModalHtml);
      function openEdit(id){
        const it=loadItems().find(x=>x.id===id); if(!it) return;
        document.getElementById('editId').value=it.id;
        document.getElementById('editTitle').value=it.title;
        document.getElementById('editDesc').value=it.description||'';
        const sel=document.getElementById('editFields');
        [...sel.options].forEach(o=> o.selected = (it.fields||[]).includes(o.value));
        document.getElementById('editKeywords').value=(it.keywords||[]).join(', ');
        const m=document.getElementById('editModal'); m.classList.remove('hidden'); m.classList.add('flex');
      }
      function closeEdit(){const m=document.getElementById('editModal'); m.classList.add('hidden'); m.classList.remove('flex');}
      function saveEdit(){
        const id=document.getElementById('editId').value;
        const title=document.getElementById('editTitle').value.trim();
        const desc=document.getElementById('editDesc').value.trim();
        const fields=[...document.getElementById('editFields').selectedOptions].map(o=>o.value);
        const keywords=document.getElementById('editKeywords').value.split(',').map(s=>s.trim()).filter(Boolean);
        const items=loadItems();
        const idx=items.findIndex(x=>x.id===id); if(idx<0) return;
        items[idx]={...items[idx], title, description:desc, fields, keywords};
        saveItems(items);
        closeEdit();
        renderList(document.getElementById('searchBox').value||'');
        localStorage.setItem('lecturer_research_last_update', Date.now().toString());
      }

      // Delete confirmation modal
      const delModalHtml=`
        <div id=\"delModal\" class=\"fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50\">
          <div class=\"bg-white rounded-xl w-full max-w-md shadow-xl\">
            <div class=\"p-4 border-b flex items-center justify-between\">
              <h3 class=\"font-semibold\">Xác nhận xóa</h3>
              <button class=\"p-2 hover:bg-slate-100 rounded-lg\" onclick=\"closeDelete()\"><i class=\"ph ph-x\"></i></button>
            </div>
            <div class=\"p-4 text-sm text-slate-700\">Bạn có chắc chắn muốn xóa hướng nghiên cứu này? Hành động không thể hoàn tác.</div>
            <div class=\"p-4 flex items-center justify-end gap-2\">
              <button onclick=\"closeDelete()\" class=\"px-4 py-2 rounded-lg border hover:bg-slate-50\">Hủy</button>
              <button id=\"confirmDelBtn\" class=\"px-4 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700\">Xóa</button>
            </div>
          </div>
        </div>`;
      document.body.insertAdjacentHTML('beforeend', delModalHtml);
      let pendingDeleteId=null;
      function openDelete(id){ pendingDeleteId=id; const m=document.getElementById('delModal'); m.classList.remove('hidden'); m.classList.add('flex'); }
      function closeDelete(){ pendingDeleteId=null; const m=document.getElementById('delModal'); m.classList.add('hidden'); m.classList.remove('flex'); }
      document.getElementById('confirmDelBtn').addEventListener('click', async ()=>{
        if(!pendingDeleteId) return;
        // If pendingDeleteId is a server-backed item (prefix 'srv_'), call server destroy route
        if(String(pendingDeleteId).startsWith('srv_')){
          const id = String(pendingDeleteId).replace(/^srv_/, '');
          try{
            const res = await fetch(`${userResearchDestroyUrl}/${encodeURIComponent(id)}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              }
            });
            if(!res.ok) throw new Error('Server returned ' + res.status);
            const data = await res.json();
            // remove from local serverResearchItems fallback and re-render
            const idx = serverResearchItems.findIndex(s => String(s.id) === 'srv_' + String(id));
            if(idx >= 0) serverResearchItems.splice(idx,1);
            closeDelete();
            renderList(document.getElementById('searchBox').value||'');
          }catch(err){
            console.error('Failed to delete on server', err);
            alert('Xóa không thành công trên server. Vui lòng thử lại.');
          }
          pendingDeleteId = null;
          return;
        }

        // local item -> remove from localStorage
        const items=loadItems().filter(x=>x.id!==pendingDeleteId);
        saveItems(items);
        closeDelete();
        renderList(document.getElementById('searchBox').value||'');
        localStorage.setItem('lecturer_research_last_update', Date.now().toString());
        pendingDeleteId = null;
      });

      // Search box
      document.getElementById('searchBox').addEventListener('input', (e)=> renderList(e.target.value));

      // Simple editor helpers
      function wrapSelection(id, pre, post){ const ta=document.getElementById(id); const s=ta.selectionStart, e=ta.selectionEnd; const v=ta.value; ta.value = v.slice(0,s)+pre+v.slice(s,e)+post+v.slice(e); ta.focus(); ta.selectionStart=s+pre.length; ta.selectionEnd=e+pre.length; }
      function insertBullet(id){ const ta=document.getElementById(id); const s=ta.selectionStart; const v=ta.value; ta.value = v.slice(0,s)+"\n• "+v.slice(s); ta.focus(); ta.selectionStart=ta.selectionEnd=s+3; }

      // Initial render
      renderList();

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
    </script>
  </body>
</html