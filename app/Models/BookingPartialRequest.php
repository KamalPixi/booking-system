<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPartialRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_requestable_type',
        'booking_requestable_id',
        'status',
        'approved_amount',
        'is_used',
        'due_time',
        'requested_by',
        'approved_by',
    ];

    public function bookingRequestable() {
        return $this->morphTo();
    }

    public function requestedBy() {
        return $this->belongsTo(User::class, 'requested_by');
    }
    public function approvedBy() {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
