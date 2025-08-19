<h1>Sửa Progress Log #{{ $log->id }}</h1>
<form action="{{ route('web.progress_logs.update', $log) }}" method="post">
    @method('PUT')
    @include('progress_logs._form')
</form>
<a href="{{ route('web.progress_logs.show', $log) }}">Hủy</a>
