<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminFee extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'fee_key',
        'fee_key_sub',
        'fee',
        'type',
    ];
}
