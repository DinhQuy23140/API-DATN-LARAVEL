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
          <a href="{{ route('web.teacher.students', ['supervisorId' => Auth::user()->teacher->supervisor->id]) }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.students') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
            <i class="ph ph-student"></i><span class="sidebar-label">Sinh viên</span>
          </a>
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
            <a href="{{ route('web.teacher.thesis_rounds') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('web.teacher.thesis_rounds') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-100' }}">
              <i class="ph ph-calendar"></i><span class="sidebar-label">Học phần tốt nghiệp</span>
            </a>
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
              <img class="h-9 w-9 rounded-full object-cover" src="https://i.pravatar.cc/100?img=20" alt="avatar" />
              <span class="hidden sm:block text-sm">gv.name@uni.edu</span>
              <i class="ph ph-caret-down text-slate-500 hidden sm:block"></i>
            </button>
            <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm">
              <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"><i class="ph ph-user"></i>Xem thông tin</a>
              <a href="#" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-rose-600"><i class="ph ph-sign-out"></i>Đăng xuất</a>
            </div>
          </div>
        </header>

  <main class="pt-20 px-4 md:px-6 pb-10">
          <div class="max-w-6xl mx-auto space-y-6">
          <!-- Add new research form -->
          <section class="bg-white rounded-xl border border-slate-200 p-5 max-w-4xl">
            <h2 class="font-semibold">Thêm hướng nghiên cứu</h2>
            <form id="createForm" class="space-y-4 mt-3" onsubmit="event.preventDefault(); addItem();">
              <div class="grid sm:grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-medium">Tiêu đề</label>
                  <input id="titleInput" required class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600" placeholder="VD: Trí tuệ nhân tạo trong y tế" />
                </div>
                <div>
                  <label class="text-sm font-medium">Từ khóa</label>
                  <input id="keywordsInput" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600" placeholder="VD: AI, NLP, MLOps" />
                  <p class="text-xs text-slate-500 mt-1">Nhập nhiều từ khóa, cách nhau bằng dấu phẩy.</p>
                </div>
              </div>
              <div>
                <label class="text-sm font-medium">Mô tả</label>
                <div class="mt-1 border border-slate-200 rounded-lg">
                  <div class="flex items-center gap-2 p-2 border-b text-slate-600 text-sm">
                    <button type="button" class="px-2 py-1 rounded hover:bg-slate-100" onclick="wrapSelection('descInput','**','**')"><i class="ph ph-text-b"></i></button>
                    <button type="button" class="px-2 py-1 rounded hover:bg-slate-100" onclick="wrapSelection('descInput','*','*')"><i class="ph ph-text-italic"></i></button>
                    <button type="button" class="px-2 py-1 rounded hover:bg-slate-100" onclick="insertBullet('descInput')"><i class="ph ph-list-bullets"></i></button>
                  </div>
                  <textarea id="descInput" rows="5" class="w-full p-3 rounded-b-lg focus:outline-none" placeholder="Mô tả chi tiết..."></textarea>
                </div>
              </div>
              <div>
                <label class="text-sm font-medium">Lĩnh vực quan tâm</label>
                <select id="fieldsInput" multiple class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600">
                  <option>AI</option>
                  <option>Data Science</option>
                  <option>Hệ phân tán</option>
                  <option>IoT</option>
                </select>
                <p class="text-xs text-slate-500 mt-1">Giữ Ctrl (Windows) để chọn nhiều.</p>
              </div>
              <div class="pt-2">
                <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Thêm</button>
              </div>
            </form>
          </section>

          <!-- Existing research list -->
          <section class="bg-white rounded-xl border border-slate-200 p-5 max-w-5xl">
            <div class="flex items-center justify-between">
              <h2 class="font-semibold">Danh sách hướng nghiên cứu</h2>
              <div class="relative">
                <input id="searchBox" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo tiêu đề, từ khóa" />
                <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
              </div>
            </div>
            <div id="emptyState" class="hidden text-sm text-slate-600 mt-4">Chưa có hướng nghiên cứu nào. Hãy thêm mới bên trên.</div>
            <div class="mt-4 overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500">
                    <th class="py-3 px-4 border-b">Tiêu đề</th>
                    <th class="py-3 px-4 border-b">Lĩnh vực</th>
                    <th class="py-3 px-4 border-b">Từ khóa</th>
                    <th class="py-3 px-4 border-b text-right">Hành động</th>
                  </tr>
                </thead>
                <tbody id="listBody"></tbody>
              </table>
            </div>
          </section>
        </div>
        </main>
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

      // UI helpers
      function renderList(filter=''){
        const tbody=document.getElementById('listBody');
        const empty=document.getElementById('emptyState');
        const items=loadItems();
        const q=filter.trim().toLowerCase();
        const filtered=items.filter(it=> it.title.toLowerCase().includes(q) || (it.keywords||[]).join(',').toLowerCase().includes(q));
        tbody.innerHTML='';
        if(filtered.length===0){ empty.classList.remove('hidden'); return; } else { empty.classList.add('hidden'); }
        for(const it of filtered){
          const tr=document.createElement('tr'); tr.className='hover:bg-slate-50';
          const fields=(it.fields||[]).map(f=>`<span class="px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-700 mr-1">${f}</span>`).join('');
          const keywords=(it.keywords||[]).map(k=>`<span class="px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-700 mr-1">${k}</span>`).join('');
          tr.innerHTML=`
            <td class="py-3 px-4">
              <div class="font-medium">${it.title}</div>
              ${it.description?`<div class="text-slate-600 line-clamp-2 max-w-xl">${it.description}</div>`:''}
            </td>
            <td class="py-3 px-4">${fields}</td>
            <td class="py-3 px-4">${keywords}</td>
            <td class="py-3 px-4 text-right space-x-2">
              <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-slate-600" onclick="openEdit('${it.id}')"><i class="ph ph-pencil"></i></button>
              <button class="px-3 py-1.5 rounded-lg border hover:bg-slate-50 text-rose-600" onclick="openDelete('${it.id}')"><i class="ph ph-trash"></i></button>
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
      document.getElementById('confirmDelBtn').addEventListener('click', ()=>{
        if(!pendingDeleteId) return; const items=loadItems().filter(x=>x.id!==pendingDeleteId); saveItems(items); closeDelete(); renderList(document.getElementById('searchBox').value||''); localStorage.setItem('lecturer_research_last_update', Date.now().toString());
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