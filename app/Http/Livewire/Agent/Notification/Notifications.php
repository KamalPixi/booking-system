<?php

namespace App\Http\Livewire\Agent\Notification;

use Livewire\Component;

class Notifications extends Component {
    public $notifications;
    public $count = 0;

    public function mount() {
        $this->notifications = auth()->user()->agent->notifications->filter(function($n) {
            if(!$n->status) {
                return true;
            }
        });

        $this->count = $this->notifications->count();
    }

    public function seen() {
        foreach($this->notifications as $notification) {
            $notification->update([
                'status' => 1
            ]);
        }
        $this->count = 0;
    }

    public function render() {
        return view('livewire.agent.notification.notifications');
    }
}
