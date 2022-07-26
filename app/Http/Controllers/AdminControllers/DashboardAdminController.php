<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class DashboardAdminController extends Controller {

    public function dashboard() {
        return view('admin.dashboard');
    }

    public function showFlightSearchForm(Request $request) {
        return 'showFlightSearchForm';
    }
}
