@if (session()->has('success'))
    <div class="alert alert-success d-flex justify-content-between">
        {{ session('success') }}
    </div>
@endif
