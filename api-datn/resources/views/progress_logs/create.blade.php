<h1>Tạo Progress Log</h1>
<form action="{{ route('web.progress_logs.store') }}" method="post">
    @include('progress_logs._form')
</form>
<a href="{{ route('web.progress_logs.index') }}">Quay lại</a>
