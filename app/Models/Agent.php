<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\FileEnum;


class Agent extends Model
{
    use HasFactory;
    public $password_plain = '';

    protected $fillable = [
        'full_name',
        'company',
        'address',
        'city',
        'state',
        'postcode',
        'phone',
        'email',
        'status',
    ];

    public function airBookings() {
        return $this->morphMany(AirBooking::class, 'airBookingable', 'air_bookingable_type', 'air_bookingable_id');
    }

    public function deposits() {
        return $this->morphMany(Deposit::class, 'depositable', 'depositable_type', 'depositable_id');
    }
    
    # Account Balance
    public function account() {
        return $this->morphOne(Account::class, 'accountable', 'accountable_type', 'accountable_id');
    }
    
    public function users() {
        return $this->hasMany(User::class, 'agent_id');
    }

    public function settings() {
        return $this->morphMany(Setting::class, 'settingable', 'settingable_type', 'settingable_id');
    }

    public function profitMargins() {
        return $this->morphMany(ProfitMargin::class, 'profitMarginAble', 'profit_marginable_type', 'profit_marginable_id');
    }

    public function notifications() {
        return $this->morphMany(Notification::class, 'notificationable', 'notificationable_type', 'notificationable_id');
    }

    public function files() {
        return $this->morphMany(File::class, 'fileable', 'fileable_type', 'fileable_id');
    }
    public function logo() {
        return $this->files->filter(function($file) {
            if ($file->file_key == FileEnum::TYPE[2]) {
                return true;
            }
            return false;
        })->first();
    }

}
