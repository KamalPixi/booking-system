@extends('agent.master')
@section('content')
    <div class="my-3 p-3 bg-body rounded shadow-sm mx-3">
    <div class="border-bottom pb-2 mb-0 d-flex">
        <h6>Notifications</h6>
    </div>

      @foreach($notifications as $notifcation)
        <div class="border-bottom pt-3">
          <div class="row">
            <div class="col">
                <p class="text-muted text-gray text-2 font-weight-bold">
                  <a class="text-muted text-gray hover-underline" href="{{ $notifcation->cta_url }}">
                    {{ $notifcation->title }}
                  </a>
                </p>
                <p class="mb-0 small text-gray-dark lh-sm">
                {{ $notifcation->message }}
              </p>
            </div>
            <div class="col-2 text-right">
              <span>{{ $notifcation->created_at->diffForHumans() }}</span>
            </div>
          </div>
        </div>
      @endforeach
  </div>
  <div class="text-center w-100 mt-3">
    {{ $notifications->links() }}
  </div>
@endsection
