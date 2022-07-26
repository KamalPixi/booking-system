<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoiceable_type',
        'invoiceable_id',
        'invoice_no',
        'services',
        'totals',
        'status',
        'for',
        'to',
    ];

    public function invoiceable() {
        return $this->morphTo();
    }
}
