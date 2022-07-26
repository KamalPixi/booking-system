<?php

namespace App\Http\Controllers\AgentControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class SettingController extends Controller {

    public function profile() {
        $active = [6,1];
        return view('agent.setting.profile', compact('active'));
    }

    public function profileEdit() {
        $active = [6,1];
        return view('agent.setting.profile-edit', compact('active'));
    }

    public function company() {
        $active = [6,2];
        return view('agent.setting.company', compact('active'));
    }

    public function passengers() {
        $active = [6,3];
        return view('agent.setting.passengers', compact('active'));
    }
}
