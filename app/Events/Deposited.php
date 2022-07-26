<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Deposited {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $deposit;

    public function __construct($deposit) {
        $this->deposit = $deposit;
    }


    public function broadcastOn() {
        return new PrivateChannel('channel-name');
    }
}
