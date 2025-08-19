<h1>Progress Log #{{ $log->id }}</h1>
@if(session('status'))<div style="color:green">{{ session('status') }}</div>@endif
<ul>
    <li>Project ID: {{ $log->project_id }}</li>
    <li>Title: {{ $log->title }}</li>
    <li>Description: {{ $log->description }}</li>
    <li>Start: {{ $log->start_date_time }}</li>
    <li>End: {{ $log->end_date_time }}</li>
    <li>Student Status: {{ $log->student_status }}</li>
    <li>Instructor Status: {{ $log->instructor_status }}</li>
    <li>Instructor Comment: {{ $log->instructor_comment }}</li>
</ul>
<a href="{{ route('web.progress_logs.edit', $log) }}">Sửa</a> | <a href="{{ route('web.progress_logs.index') }}">Danh sách</a>
<hr>
<h2>Attachments</h2>
<a href="{{ route('web.attachments.create', $log) }}">Thêm tệp</a>
<table border="1" cellpadding="6">
    <thead><tr><th>ID</th><th>Tên</th><th>Type</th><th>Upload Time</th><th></th></tr></thead>
    <tbody>
    @foreach($log->attachments as $att)
        <tr>
            <td>{{ $att->id }}</td>
            <td><a href="{{ $att->file_url }}" target="_blank">{{ $att->file_name }}</a></td>
            <td>{{ $att->file_type }}</td>
            <td>{{ $att->upload_time }}</td>
            <td>
                <a href="{{ route('web.attachments.edit', $att) }}">Sửa</a>
                <form action="{{ route('web.attachments.destroy', $att) }}" method="post" style="display:inline" onsubmit="return confirm('Xóa tệp?')">
                    @csrf @method('DELETE')
                    <button type="submit">Xóa</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
