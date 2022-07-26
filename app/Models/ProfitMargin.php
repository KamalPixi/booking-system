<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitMargin extends Model
{
    use HasFactory;

    protected $fillable = [
        'profit_marginable_type',
        'profit_marginable_id',
        'key',
        'type',
        'amount',
    ];

    public function profitMarginAble() {
        return $this->morphTo();
    }
}
