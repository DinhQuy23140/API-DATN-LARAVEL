<h1>Progress Logs</h1>
@if(session('status'))<div style="color:green">{{ session('status') }}</div>@endif
<a href="{{ route('web.progress_logs.create') }}">Tạo mới</a>
<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>ID</th>
            <th>Project</th>
            <th>Title</th>
            <th>Attachments</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach($logs as $l)
        <tr>
            <td>{{ $l->id }}</td>
            <td>{{ $l->project_id }}</td>
            <td>{{ $l->title }}</td>
            <td>{{ $l->attachments_count }}</td>
            <td>
                <a href="{{ route('web.progress_logs.show', $l) }}">Xem</a> |
                <a href="{{ route('web.progress_logs.edit', $l) }}">Sửa</a>
                <form action="{{ route('web.progress_logs.destroy', $l) }}" method="post" style="display:inline" onsubmit="return confirm('Xóa?')">
                    @csrf @method('DELETE')
                    <button type="submit">Xóa</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $logs->links() }}
