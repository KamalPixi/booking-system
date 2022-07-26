<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Json extends Model
{
    use HasFactory;

    protected $fillable = [
        'jsonable_type',
        'jsonable_id',
        'type',
        'type_key',
        'json',
    ];

    public function jsonable() {
        return $this->morphTo();
    }
}
