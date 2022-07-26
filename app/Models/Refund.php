<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'refundable_type',
        'refundable_id',
        'agent_id',
        'requested_by',
        'amount',
        'fee',
        'currency',
        'refund_method',
        'remark',
        'admin_remark',
        'refunded_by',
        'refunded_at',
        'status',
    ];

    public function refundable() {
        return $this->morphTo();
    }

    public function requestedBy() {
        return $this->belongsTo(User::class, 'requested_by');
    }
    public function refundedBy() {
        return $this->belongsTo(User::class, 'refunded_by');
    }

    public function transaction() {
        return $this->morphOne(Transaction::class, 'transactionable', 'transactionable_type', 'transactionable_id');
    }

    public function refundableTypeModelName() {
        return explode('\\', $this->refundable_type)[2] ?? '';
    }
}
