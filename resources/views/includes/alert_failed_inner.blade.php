@if (session()->has('failed'))
    <div class="alert alert-danger d-flex justify-content-between">
        {{ session('failed') }}
    </div>
@endif
