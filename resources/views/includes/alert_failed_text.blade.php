@if(session()->has('failed'))
    <br>
    <span class="small text-danger">{{ session('failed') }}</span>
@endif
