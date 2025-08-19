@csrf
<div>
    <label>Project ID</label>
    <input type="number" name="project_id" value="{{ old('project_id', $log->project_id) }}" required>
</div>
<div>
    <label>Tiêu đề</label>
    <input type="text" name="title" value="{{ old('title', $log->title) }}" required>
</div>
<div>
    <label>Mô tả</label>
    <textarea name="description">{{ old('description', $log->description) }}</textarea>
</div>
<div>
    <label>Bắt đầu</label>
    <input type="datetime-local" name="start_date_time" value="{{ old('start_date_time', optional($log->start_date_time)->format('Y-m-d\TH:i')) }}" {{ $log->exists ? '' : 'required' }}>
</div>
<div>
    <label>Kết thúc</label>
    <input type="datetime-local" name="end_date_time" value="{{ old('end_date_time', optional($log->end_date_time)->format('Y-m-d\TH:i')) }}" {{ $log->exists ? '' : 'required' }}>
</div>
<div>
    <label>Student Status</label>
    <input type="text" name="student_status" value="{{ old('student_status', $log->student_status) }}" required>
</div>
<div>
    <label>Instructor Status</label>
    <input type="text" name="instructor_status" value="{{ old('instructor_status', $log->instructor_status) }}">
</div>
<div>
    <label>Instructor Comment</label>
    <textarea name="instructor_comment">{{ old('instructor_comment', $log->instructor_comment) }}</textarea>
</div>
<button type="submit">Lưu</button>
@if($errors->any())
<div style="color:red">
    <ul>
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif
