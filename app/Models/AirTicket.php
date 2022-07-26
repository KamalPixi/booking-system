<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'air_booking_id',
        'agent_id',
        'confirmation_id',
        'amount',
        'currency',
        'status',
        'remark',
        'issued_at',
        'issued_by',
        'created_by',
        'updated_by',
    ];

    public function airBooking() {
        return $this->belongsTo(AirBooking::class, 'air_booking_id');
    }
    
    # agent user
    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }

    # admin user
    public function issuedBy() {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
