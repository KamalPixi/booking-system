<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank',
        'account_no',
        'account_name',
        'branch',
        'status',
    ];

    public function logo() {
        return $this->morphOne(File::class, 'fileable', 'fileable_type', 'fileable_id');
    }
}
