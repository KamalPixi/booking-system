<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transactionable_type',
        'transactionable_id',
        'sign',
        'amount',
        'currency',
        'purpose',
        'method',
        'initiated_by',
        'remark',
    ];


    public function transactionable() {
        return $this->morphTo();
    }
}
