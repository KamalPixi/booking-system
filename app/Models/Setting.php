<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'settingable_type',
        'settingable_id',
        'setting_key',
        'value',
        'type',
    ];

    public function settingable() {
        return $this->morphTo();
    }
}
