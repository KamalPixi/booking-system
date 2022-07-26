<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'paymentable_type',
        'paymentable_id',
        'agent_id',
        'amount',
        'currency',
        'method',
        'purpose',
        'status',
        'remark',
        'created_by',
        'updated_by',
    ];


    public function paymentable() {
        return $this->morphTo();
    }

    
    public function transactions() {
        return $this->morphMany(Transaction::class, 'transactionable', 'transactionable_type', 'transactionable_id');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
