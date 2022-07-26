<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'notificationable_type',
        'notificationable_id',
        'title',
        'message',
        'cta_url',
        'status',
    ];

    public function notificationable() {
        return $this->morphTo();
    }
}
