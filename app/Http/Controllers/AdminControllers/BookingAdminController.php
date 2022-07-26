<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingAdminController extends Controller {

    public function flights() {
        $active = [
            'menu' => 'flight_bookings',
            'sub_menu' => 'booking.flights',
        ];
        return view('admin.booking.flights', compact('active'));
    }

    public function flightIssueRequests() {
        $active = [
            'menu' => 'flight_bookings',
            'sub_menu' => 'booking.flight-issue-requests',
        ];
        return view('admin.booking.flight-issue-requests', compact('active'));
    }

    public function flightsIssued() {
        $active = [
            'menu' => 'flight_bookings',
            'sub_menu' => 'booking.flights-issued',
        ];
        return view('admin.booking.flights-issued', compact('active'));
    }

    public function flightsRefunded() {
        $active = [
            'menu' => 'flight_bookings',
            'sub_menu' => 'booking.flights-refunded',
        ];
        return view('admin.booking.flights-refunded', compact('active'));
    }

    public function flightsCanceled() {
        $active = [
            'menu' => 'flight_bookings',
            'sub_menu' => 'booking.flights-canceled',
        ];
        return view('admin.booking.flights-canceled', compact('active'));
    }

    public function flight($air_booking_id) {
        return view('admin.booking.flight', compact('air_booking_id'));
    }




    public function umrah() {
        return view('admin.booking.umrah');
    }

    public function holidays() {
        return view('admin.booking.holidays');
    }

    public function groups() {
        return view('admin.booking.groups');
    }

    public function umrahIssueRequests() {
        return view('admin.booking.umrah-issue-requests');
    }

}
