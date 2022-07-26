@if (session()->has('failed'))
    <div class="alert-center alert alert-danger">
        <span type="button" onclick="document.querySelector('.alert-center').remove()" class="alert-close">&#10006;</span>
        <p class="m-0 text-small font-weight-bold">{{ session('failed') }}</p>
    </div>
@endif
