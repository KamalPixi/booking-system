@if (session()->has('warning'))
    <div class="alert-center alert alert-warning">
        <span type="button" onclick="document.querySelector('.alert-center').remove()" class="alert-close">&#10006;</span>
        <p class="m-0 text-small font-weight-bold">{{ session('warning') }}</p>
    </div>
@endif
