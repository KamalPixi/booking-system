<?php

namespace App\Http\Controllers\AgentControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FlightController extends Controller {

    public function search(Request $request) {
        return view('agent.flight.search-result');
    }

    public function flightSearchDetails(Request $request) {
        return view('agent.flight.search-details');
    }

    public function flightHistory(Request $request) {
        $active = [2, 1];
        return view('agent.flight.history', compact('active'));
    }
    public function flightShow($flight_booking_id, Request $request) {
        $active = [2, 1];
        return view('agent.flight.booking-show', compact('active','flight_booking_id'));
    }
    

    public function flightsIssued(Request $request) {
        $active = [3, 1];
        return view('agent.flight.issued', compact('active'));
    }

    public function flightsCanceled(Request $request) {
        $active = [3, 2];
        return view('agent.flight.canceled', compact('active'));
    }

    public function flightsRefunded(Request $request) {
        $active = [3, 3];
        return view('agent.flight.refunded', compact('active'));
    }

    public function flightsChanged(Request $request) {
        $active = [3, 4];
        return view('agent.flight.changed', compact('active'));
    }
}
