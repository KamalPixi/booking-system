<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'key',
        'fee',
        'fee_type',
        'remark',
        'status',
    ];

    public function logo() {
        return $this->morphOne(File::class, 'fileable', 'fileable_type', 'fileable_id');
    }
}
