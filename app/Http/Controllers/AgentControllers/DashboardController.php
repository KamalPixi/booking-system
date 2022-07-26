<?php

namespace App\Http\Controllers\AgentControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller {

    public function dashboard() {
        return redirect('/flight');
    }

    public function showFlightSearchForm() {
        return view('agent.flight.flight', [
            'searchDashboard' => true
        ]);
    }
    
    public function prePurchasedAir() {
        return view('agent.flight.pre-purchased-air', [
            'searchDashboard' => true
        ]);
    }

    public function showHotelSearchForm() {
        return view('agent.hotel.hotel', [
            'searchDashboard' => true
        ]);
    }

    public function showHolidaySearchForm() {
        return view('agent.holiday.holiday', [
            'searchDashboard' => true
        ]);
    }

    public function showGroupSearchForm() {
        return view('agent.group.group', [
            'searchDashboard' => true
        ]);
    }

    public function showUmrahSearchForm() {
        return view('agent.umrah.umrah', [
            'searchDashboard' => true
        ]);
    }

    public function build() {
        return view('agent.build');
    }

}
