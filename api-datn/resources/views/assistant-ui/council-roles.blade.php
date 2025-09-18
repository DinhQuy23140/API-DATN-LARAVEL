<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Phân công vai trò hội đồng</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial}
    .sidebar{width:260px}
  </style>
</head>
<body class="bg-slate-50 text-slate-800">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="sidebar fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col">
      <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
        <div class="h-9 w-9 grid place-items-center rounded-lg bg-blue-600 text-white"><i class="ph ph-buildings"></i></div>
        <div>
          <div class="font-semibold">Assistant</div>
          <div class="text-xs text-slate-500">Quản trị khoa</div>
        </div>
      </div>
      <nav class="flex-1 overflow-y-auto p-3 text-sm">
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100"><i class="ph ph-gauge"></i>Bảng điều khiển</a>
        <div class="text-xs uppercase text-slate-400 px-3 mt-3">Học phần tốt nghiệp</div>
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-100 font-semibold"><i class="ph ph-folder"></i>Đồ án tốt nghiệp</a>
      </nav>
    </aside>

    <div class="flex-1 h-screen overflow-hidden flex flex-col md:pl-[260px]">
      <header class="h-16 bg-white border-b border-slate-200 flex items-center px-4 md:px-6">
        <div class="flex-1">
          <h1 class="text-lg md:text-xl font-semibold">Phân công vai trò hội đồng</h1>
          <div class="text-xs text-slate-500 mt-0.5">Đợt: 2025-Q3</div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto p-4 md:p-6">
        <div class="w-full flex flex-col md:flex-row gap-6">
          <!-- Trái: Danh sách hội đồng (50%) -->
          <section class="w-full md:w-1/2 bg-white border rounded-xl">
            <div class="p-4 border-b">
              <div class="flex items-center justify-between">
                <h2 class="font-semibold">Danh sách hội đồng</h2>
                <div class="relative">
                  <input id="q" class="pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 text-sm" placeholder="Tìm theo mã/tên hội đồng" />
                  <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
              </div>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-500 border-b">
                    <th class="py-3 px-4">Mã</th>
                    <th class="py-3 px-4">Tên</th>
                    <th class="py-3 px-4">Ngày</th>
                    <th class="py-3 px-4">Phòng</th>
                    <th class="py-3 px-4">Thành viên</th>
                  </tr>
                </thead>
                <tbody id="councilRows">
                  <tr class="hover:bg-slate-50 cursor-pointer" data-id="1">
                    <td class="py-3 px-4 font-medium">CNTT-01</td>
                    <td class="py-3 px-4">Hội đồng CNTT-01</td>
                    <td class="py-3 px-4">10/09/2025</td>
                    <td class="py-3 px-4">B204</td>
                    <td class="py-3 px-4" data-member-count>3</td>
                  </tr>
                  <tr class="hover:bg-slate-50 cursor-pointer" data-id="2">
                    <td class="py-3 px-4 font-medium">CNTT-02</td>
                    <td class="py-3 px-4">Hội đồng CNTT-02</td>
                    <td class="py-3 px-4">11/09/2025</td>
                    <td class="py-3 px-4">B205</td>
                    <td class="py-3 px-4" data-member-count>2</td>
                  </tr>
                  <tr class="hover:bg-slate-50 cursor-pointer" data-id="3">
                    <td class="py-3 px-4 font-medium">CNTT-03</td>
                    <td class="py-3 px-4">Hội đồng CNTT-03</td>
                    <td class="py-3 px-4">12/09/2025</td>
                    <td class="py-3 px-4">B206</td>
                    <td class="py-3 px-4" data-member-count>0</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>

          <!-- Phải: Panel + 5 select vai trò (phần còn lại) -->
          <section class="w-full md:flex-1 bg-white border rounded-xl p-4" id="detailPanel">
            <div class="flex items-center justify-between">
              <h2 class="font-semibold">Thông tin hội đồng</h2>
              <button id="btnSave" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-sm">Lưu</button>
            </div>
            <div class="mt-3 text-sm" id="councilInfo">Chọn một hội đồng ở bảng bên trái để chỉnh sửa.</div>

            <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
              <div>
                <label class="text-sm text-slate-700">Chủ tịch</label>
                <select id="sel_chairman" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                  <option value="">-- Chọn giảng viên --</option>
                  <option value="1">TS. Phạm Quốc C</option>
                  <option value="2">ThS. Trần D</option>
                  <option value="3">TS. Nguyễn E</option>
                  <option value="4">TS. Vũ Văn F</option>
                  <option value="5">PGS.TS. Lê Thị Hồng</option>
                  <option value="6">TS. Bùi Minh I</option>
                  <option value="7">ThS. Hoàng K</option>
                  <option value="8">TS. Lương M</option>
                </select>
              </div>
              <div>
                <label class="text-sm text-slate-700">Thư ký</label>
                <select id="sel_secretary" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                  <option value="">-- Chọn giảng viên --</option>
                  <option value="1">TS. Phạm Quốc C</option>
                  <option value="2">ThS. Trần D</option>
                  <option value="3">TS. Nguyễn E</option>
                  <option value="4">TS. Vũ Văn F</option>
                  <option value="5">PGS.TS. Lê Thị Hồng</option>
                  <option value="6">TS. Bùi Minh I</option>
                  <option value="7">ThS. Hoàng K</option>
                  <option value="8">TS. Lương M</option>
                </select>
              </div>
              <div>
                <label class="text-sm text-slate-700">Ủy viên 1</label>
                <select id="sel_member1" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                  <option value="">-- Chọn giảng viên --</option>
                  <option value="1">TS. Phạm Quốc C</option>
                  <option value="2">ThS. Trần D</option>
                  <option value="3">TS. Nguyễn E</option>
                  <option value="4">TS. Vũ Văn F</option>
                  <option value="5">PGS.TS. Lê Thị Hồng</option>
                  <option value="6">TS. Bùi Minh I</option>
                  <option value="7">ThS. Hoàng K</option>
                  <option value="8">TS. Lương M</option>
                </select>
              </div>
              <div>
                <label class="text-sm text-slate-700">Ủy viên 2</label>
                <select id="sel_member2" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                  <option value="">-- Chọn giảng viên --</option>
                  <option value="1">TS. Phạm Quốc C</option>
                  <option value="2">ThS. Trần D</option>
                  <option value="3">TS. Nguyễn E</option>
                  <option value="4">TS. Vũ Văn F</option>
                  <option value="5">PGS.TS. Lê Thị Hồng</option>
                  <option value="6">TS. Bùi Minh I</option>
                  <option value="7">ThS. Hoàng K</option>
                  <option value="8">TS. Lương M</option>
                </select>
              </div>
              <div>
                <label class="text-sm text-slate-700">Ủy viên 3</label>
                <select id="sel_member3" class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                  <option value="">-- Chọn giảng viên --</option>
                  <option value="1">TS. Phạm Quốc C</option>
                  <option value="2">ThS. Trần D</option>
                  <option value="3">TS. Nguyễn E</option>
                  <option value="4">TS. Vũ Văn F</option>
                  <option value="5">PGS.TS. Lê Thị Hồng</option>
                  <option value="6">TS. Bùi Minh I</option>
                  <option value="7">ThS. Hoàng K</option>
                  <option value="8">TS. Lương M</option>
                </select>
              </div>
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>

  <script>
    // Dữ liệu tĩnh
    const councils = [
      {
        id: 1, code: 'CNTT-01', name: 'Hội đồng CNTT-01', room: 'B204', defense_date: '10/09/2025',
        roles: {
          chairman:  { id: 1, name: 'TS. Phạm Quốc C' },
          secretary: { id: 2, name: 'ThS. Trần D' },
          member1:   { id: 3, name: 'TS. Nguyễn E' },
          member2:   null,
          member3:   null,
        }
      },
      {
        id: 2, code: 'CNTT-02', name: 'Hội đồng CNTT-02', room: 'B205', defense_date: '11/09/2025',
        roles: {
          chairman:  null,
          secretary: { id: 7, name: 'ThS. Hoàng K' },
          member1:   { id: 6, name: 'TS. Bùi Minh I' },
          member2:   null,
          member3:   null,
        }
      },
      {
        id: 3, code: 'CNTT-03', name: 'Hội đồng CNTT-03', room: 'B206', defense_date: '12/09/2025',
        roles: { chairman:null, secretary:null, member1:null, member2:null, member3:null }
      }
    ];

    // "Bảng" council_members dạng cục bộ để mô phỏng DB
    // Mỗi phần tử: { council_id, role, supervisor_id }
    let councilMembers = [];
    const ROLES = ['chairman','secretary','member1','member2','member3'];

    // Snapshot vai trò gốc để so sánh (theo council_id)
    const originalRoles = {};
    function initCouncilMembersFromCouncils(){
      councilMembers = [];
      councils.forEach(c=>{
        originalRoles[c.id] = {};
        ROLES.forEach(role=>{
          const r = c.roles?.[role];
          originalRoles[c.id][role] = r ? String(r.id) : '';
          if(r){
            councilMembers.push({ council_id: c.id, role, supervisor_id: r.id });
          }
        });
      });
    }
    initCouncilMembersFromCouncils();

    const detailEl = document.getElementById('councilInfo');
    const rowsEl = document.getElementById('councilRows');
    const sels = {
      chairman:  document.getElementById('sel_chairman'),
      secretary: document.getElementById('sel_secretary'),
      member1:   document.getElementById('sel_member1'),
      member2:   document.getElementById('sel_member2'),
      member3:   document.getElementById('sel_member3'),
    };
    let currentId = null;

    function fillPanel(c){
      detailEl.innerHTML = `
        <div class="text-sm">
          <div><span class="text-slate-500">Mã:</span> <span class="font-medium">${c.code||'-'}</span></div>
          <div><span class="text-slate-500">Tên:</span> <span class="font-medium">${c.name||'-'}</span></div>
          <div><span class="text-slate-500">Ngày:</span> ${c.defense_date || '-'}</div>
          <div><span class="text-slate-500">Phòng:</span> ${c.room || '-'}</div>
        </div>
      `;
      // reset selects
      Object.values(sels).forEach(s => s.value='');
      // set roles if any
      if(c.roles){
        if(c.roles.chairman)  sels.chairman.value  = String(c.roles.chairman.id);
        if(c.roles.secretary) sels.secretary.value = String(c.roles.secretary.id);
        if(c.roles.member1)   sels.member1.value   = String(c.roles.member1.id);
        if(c.roles.member2)   sels.member2.value   = String(c.roles.member2.id);
        if(c.roles.member3)   sels.member3.value   = String(c.roles.member3.id);
      }
      syncRoleSelects();
    }

    function syncRoleSelects(){
      const chosen = new Set(Object.values(sels).map(s=>s.value).filter(Boolean));
      Object.values(sels).forEach(sel=>{
        Array.from(sel.options).forEach(opt=>{
          if(!opt.value) return;
          const dup = chosen.has(opt.value) && sel.value !== opt.value;
          opt.disabled = dup;
          opt.hidden = dup;
        });
      });
    }
    Object.values(sels).forEach(s=> s.addEventListener('change', syncRoleSelects));

    rowsEl.querySelectorAll('tr[data-id]').forEach(tr=>{
      tr.addEventListener('click', ()=>{
        rowsEl.querySelectorAll('tr').forEach(r=> r.classList.remove('bg-slate-50'));
        tr.classList.add('bg-slate-50');
        const id = parseInt(tr.dataset.id,10);
        currentId = id;
        const c = councils.find(x=>x.id===id);
        if(c) fillPanel(c);
      })
    });

    document.getElementById('q')?.addEventListener('input', (e)=>{
      const q = (e.target.value||'').toLowerCase();
      rowsEl.querySelectorAll('tr[data-id]').forEach(tr=>{
        tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
      });
    });

    // Lưu cục bộ (không gọi API)
    document.getElementById('btnSave').addEventListener('click', ()=>{
      if(!currentId){ alert('Vui lòng chọn một hội đồng.'); return; }
      // validate duplicate
      const picked = Object.values(sels).map(s=>s.value).filter(Boolean);
      if(new Set(picked).size !== picked.length){ alert('Các vai trò không được trùng giảng viên.'); return; }

      // So sánh và áp dụng thay đổi theo quy tắc:
      // - Nếu role đổi từ A -> B: xóa bản ghi cũ (A) và chèn/cập nhật bản ghi (B).
      // - Nếu role từ có -> rỗng: xóa bản ghi.
      // - Nếu role từ rỗng -> có: tạo mới.
      const before = originalRoles[currentId] || {};
      const after = {
        chairman:  sels.chairman.value || '',
        secretary: sels.secretary.value || '',
        member1:   sels.member1.value || '',
        member2:   sels.member2.value || '',
        member3:   sels.member3.value || '',
      };

      ROLES.forEach(role=>{
        const prev = before[role] || '';
        const curr = after[role] || '';
        if(prev === curr){
          return; // không thay đổi
        }
        // Xóa bản ghi cũ nếu có
        if(prev){
          councilMembers = councilMembers.filter(m => !(m.council_id === currentId && m.role === role && String(m.supervisor_id) === prev));
        }
        // Thêm/Cập nhật bản ghi mới nếu có chọn mới
        if(curr){
          // Kiểm tra xem cùng supervisor + role đã tồn tại ở hội đồng khác?
          const existingIdx = councilMembers.findIndex(m => m.role === role && String(m.supervisor_id) === curr);
          if(existingIdx >= 0){
            // Cập nhật sang hội đồng hiện tại
            councilMembers[existingIdx].council_id = currentId;
          }else{
            // Tạo mới
            councilMembers.push({ council_id: currentId, role, supervisor_id: +curr });
          }
        }
        // Cập nhật snapshot
        before[role] = curr;
      });
      // Lưu lại snapshot mới
      originalRoles[currentId] = before;

      const c = councils.find(x=>x.id===currentId);
      const getNameById = (id)=>{
        const opt = document.querySelector(`select option[value="${id}"]`);
        return opt ? opt.textContent : '';
      };
      c.roles.chairman  = sels.chairman.value  ? { id: +sels.chairman.value,  name: getNameById(sels.chairman.value) }  : null;
      c.roles.secretary = sels.secretary.value ? { id: +sels.secretary.value, name: getNameById(sels.secretary.value) } : null;
      c.roles.member1   = sels.member1.value   ? { id: +sels.member1.value,   name: getNameById(sels.member1.value) }   : null;
      c.roles.member2   = sels.member2.value   ? { id: +sels.member2.value,   name: getNameById(sels.member2.value) }   : null;
      c.roles.member3   = sels.member3.value   ? { id: +sels.member3.value,   name: getNameById(sels.member3.value) }   : null;

      // Cập nhật số thành viên bên bảng trái
      const count = ['chairman','secretary','member1','member2','member3'].reduce((n,k)=> n + (c.roles[k]?1:0), 0);
      const row = rowsEl.querySelector(`tr[data-id="${currentId}"] [data-member-count]`);
      if(row) row.textContent = String(count);

      // Debug (tùy chọn): xem "cơ sở dữ liệu" hiện tại
      // console.table(councilMembers);
      alert('Đã lưu (dữ liệu tĩnh, xử lý thay đổi đúng quy tắc).');
    });
  </script>
</body>
</html>