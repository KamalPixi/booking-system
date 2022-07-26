<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgentControllers\DashboardController;
use App\Http\Controllers\AgentControllers\FlightController;
use App\Http\Controllers\AgentControllers\HotelController;
use App\Http\Controllers\AgentControllers\UmrahController;
use App\Http\Controllers\AgentControllers\GroupRequestController;
use App\Http\Controllers\AgentControllers\HolidayController;
use App\Http\Controllers\AgentControllers\PaymentController;
use App\Http\Controllers\AgentControllers\PaymentGatewayController;
use App\Http\Controllers\AgentControllers\SettingController;
use App\Http\Controllers\AgentControllers\NotificationController;
use App\Http\Controllers\AgentControllers\AuthController as AgentAuthController;

use App\Http\Controllers\AdminControllers\AuthAdminController;
use App\Http\Controllers\AdminControllers\AgentAdminController;
use App\Http\Controllers\AdminControllers\DashboardAdminController;
use App\Http\Controllers\AdminControllers\BookingAdminController;
use App\Http\Controllers\AdminControllers\DepositAdminController;
use App\Http\Controllers\AdminControllers\PaymentAdminController;
use App\Http\Controllers\AdminControllers\TransactionAdminController;
use App\Http\Controllers\AdminControllers\SettingAdminController;
use App\Http\Controllers\AdminControllers\AddAdminController;


/**
 * Agent Routes
 */
Route::group(['domain' => 'b2b.' . env('APP_URL'), 'middleware' => ['auth:web', 'agentMiddleware'], 'as' => 'b2b.'], function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'dashboard')->name('dashboard');
        Route::get('/flight', 'showFlightSearchForm');
        Route::get('/pre-purchased-air', 'prePurchasedAir');
        Route::get('/hotel', 'showHotelSearchForm');
        Route::get('/holiday', 'showHolidaySearchForm');
        Route::get('/group', 'showGroupSearchForm');
        Route::get('/umrah', 'showUmrahSearchForm');
        Route::get('/build', 'build');
    });

    Route::controller(FlightController::class)->group(function () {
        Route::get('/flight-search', 'search')->name('flight.search');
        Route::get('/flight-search/details', 'flightSearchDetails')->name('flight.search.details');
        Route::get('/flight/history', 'flightHistory');
        Route::get('/flight/history/{id}', 'flightShow')->name('flight.show');
        Route::get('/flight/issued', 'flightsIssued');
        Route::get('/flight/canceled', 'flightsCanceled');
        Route::get('/flight/refunded', 'flightsRefunded');
        Route::get('/flight/changed', 'flightsChanged');
    });
    
    Route::controller(HotelController::class)->group(function () {
        Route::get('/hotel-search', 'search');
        Route::get('/hotel/history', 'hotelHistory');
    });
    
    Route::controller(HolidayController::class)->group(function () {
        Route::get('/holiday-search', 'search');
        Route::get('/holiday/history', 'holidayHistory');
    });
    
    Route::controller(UmrahController::class)->group(function () {
        Route::get('/umrah-search', 'search');
        Route::get('/umrah/history', 'umrahHistory');
    });
    
    Route::controller(GroupRequestController::class)->group(function () {
        Route::get('/group-search', 'search');
        Route::get('/group/history', 'groupHistory');
    });

    
    Route::controller(PaymentController::class)->group(function () {
        Route::get('/deposits', 'deposits');
        Route::get('/payments', 'payments');
        Route::get('/refunds', 'refunds');
        Route::get('/payment-method-list', 'paymentMethodList');
    });
    
    Route::controller(SettingController::class)->group(function () {
        Route::get('/profile', 'profile');
        Route::get('/profile/edit', 'profileEdit');
        Route::get('/passengers', 'passengers');
        Route::get('/company', 'company');
    });

    Route::controller(PaymentGatewayController::class)->group(function () {
        Route::get('/gateway/add-balance', 'addBalanceShow')->name('gateway.addBalance');
        Route::get('/gateway/add-balance-manual', 'addBalanceShowManual')->name('gateway.addBalanceManual');
        Route::post('/gateway/add-balance', 'addBalance');
        Route::post('/gateway/success', 'success');
        Route::post('/gateway/failed', 'failed');
        Route::post('/gateway/cancel', 'cancel');
    });

    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notifications', 'notifications');
        Route::get('/notification', 'notification');
    });
    
    Route::controller(AgentAuthController::class)->group(function () {
        Route::get('/logout', 'logout')->name('logout');
    });
});

# B2B Open Routes
Route::group(['domain' => 'b2b.' . env('APP_URL'), 'as' => 'b2b.'], function() {
    Route::controller(AgentAuthController::class)->group(function () {
        Route::get('/login', 'loginShow')->name('login');
        Route::get('/register', 'register')->name('register');
        Route::get('/logout', 'logout')->name('logout');

    });
});



/**
 * Admin Protected Routes
 */
Route::group(['domain' => 'admin.' . env('APP_URL'), 'middleware' => ['auth:web', 'adminMiddleware'], 'as' => 'admin.'], function () {     
    Route::controller(DashboardAdminController::class)->group(function () {
        Route::get('/', 'dashboard');
        Route::get('/flight', 'showFlightSearchForm');
        Route::get('/hotel', 'showHotelSearchForm');
        Route::get('/holiday', 'showHolidaySearchForm');
        Route::get('/group', 'showGroupSearchForm');
        Route::get('/umrah', 'showUmrahSearchForm');
        Route::get('/agent', 'agents');
    });

    Route::controller(AgentAdminController::class)->group(function () {
        Route::get('/agent', 'agents')->name('agents');
        Route::get('/agent-requests', 'agentRequests')->name('agents.requests');
    });

    Route::controller(BookingAdminController::class)->group(function () {
        Route::get('/booking/flights', 'flights')->name('booking.flights');
        Route::get('/booking/flights/{id}', 'flight')->name('booking.flights.view');
        Route::get('/booking/umrah', 'umrah')->name('booking.umrah');
        Route::get('/booking/holidays', 'holidays')->name('booking.holidays');
        Route::get('/booking/groups', 'groups')->name('booking.groups');
        
        Route::get('/booking/flight-issue-requests', 'flightIssueRequests')->name('booking.flight-issue-requests');
        Route::get('/booking/flights-issued', 'flightsIssued')->name('booking.flights-issued');
        Route::get('/booking/flights-refunded', 'flightsRefunded')->name('booking.flights-refunded');
        Route::get('/booking/flights-canceled', 'flightsCanceled')->name('booking.flights-canceled');
        
        Route::get('/booking/umrah-issue-requests', 'umrahIssueRequests')->name('booking.umrah-issue-requests');
    });

    Route::controller(DepositAdminController::class)->group(function () {
        Route::get('/deposits', 'deposits')->name('deposits');
        Route::get('/deposits/online', 'depositsOnline')->name('deposits.online');
        Route::get('/deposits/manual', 'depositsManual')->name('deposits.manual');
        Route::get('/deposits/canceled', 'depositsCanceled')->name('deposits.canceled');
    });

    Route::controller(PaymentAdminController::class)->group(function () {
        Route::get('/payments', 'payments')->name('payments');
        Route::get('/payments/partials-requests', 'partialsRequests')->name('payments.partial-requests');
    });

    Route::controller(TransactionAdminController::class)->group(function () {
        Route::get('/transactions', 'transactions')->name('transactions');
        Route::get('/refunds', 'refunds')->name('refunds');
        Route::get('/refunds/requests', 'refundRequests')->name('refunds.requests');
    });

    Route::controller(SettingAdminController::class)->group(function () {
        Route::get('/users', 'users')->name('users');
        Route::get('/profile', 'profile')->name('profile');
        Route::get('/banks', 'banks')->name('banks');
        Route::get('/admin-fees', 'adminFees')->name('admin-fees');
        Route::get('/payment-methods', 'paymentMethods')->name('payment-methods');
    });

    Route::controller(AuthAdminController::class)->group(function () {
        Route::get('/logout', 'logout')->name('logout');
    });

    Route::controller(AddAdminController::class)->group(function () {
        Route::get('/add-pre-air', 'addAirPre')->name('add-pre-air');
    });
});

/**
 * Admin Open Routes
 */
Route::group(['domain' => 'admin.' . env('APP_URL'), 'as' => 'admin.'], function () {     
    Route::controller(AuthAdminController::class)->group(function () {
        Route::get('/login', 'loginForm')->name('login');;
        Route::post('/login', 'login');
    });
});


Route::get('/', function () {
    return view('welcome');
});


# SSLCommerz IPN
Route::post('/ssl-gateway-ipn', [PaymentGatewayController::class, 'sslCommerzIPN']);
