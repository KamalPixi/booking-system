<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserEnum;

class AgentAdminController extends Controller {
    public function agents() {
        return view('admin.agent.agents');
    }
    public function agentRequests() {
        return view('admin.agent.agent-requests');
    }
}
