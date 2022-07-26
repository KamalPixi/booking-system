<div class="notification-box ml-15 d-none d-md-flex">
    <div>
        <button wire:ignore.self wire:click="seen" class="dropdown-toggle bg-white" type="button" id="notification" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="lni lni-alarm"></i>
            <span>{{ $count }}</span>
        </button>
        <ul wire:ignore.self class="dropdown-menu dropdown-menu-end notification-ul" aria-labelledby="notification">
            @if($notifications->count() > 0)
                @foreach($notifications as $notifcation)
                    <li>
                        <a href="{{ $notifcation->cta_url }}">
                            <div class="content">
                                <h6>{{ $notifcation->title }}</h6>
                                <p>{{ mb_strimwidth($notifcation->message, 0, 100, "..."); }}</p>
                                <span>{{ $notifcation->created_at->diffForHumans() }}</span>
                            </div>
                        </a>
                    </li>
                @endforeach
            @else
                <li>
                    <div class="content text-center">
                        <h6>No new notifications!</h6>
                    </div>
                </li>
            @endif

            <li>
                <a href="{{ url('/notifications') }}" class="d-block text-center">View All</a>
            </li>
        </ul>
    </div>
</div>
