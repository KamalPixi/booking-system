@if (session()->has('success'))
    <div class="alert-center alert alert-success">
        <span type="button" onclick="document.querySelector('.alert-center').remove()" class="alert-close">&#10006;</span>
        <p class="m-0 text-small font-weight-bold">{{ session('success') }}</p>
        
        @if(session()->has('href'))
            <a class="text-small" href="{{ session('href') }}">{{ session('text') }}</a>
        @endif
    </div>
@endif
