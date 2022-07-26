@if (session()->has('info'))
    <div class="alert alert-info d-flex justify-content-between">
        {{ session('info') }}
    </div>
@endif
