<?php

namespace App\Http\Controllers\AgentControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller {

    public function notifications(Request $request) {
        $notifications = auth()->user()->agent->notifications()->paginate(10);
        return view('agent.notification.notifications', compact('notifications'));
    }

}
