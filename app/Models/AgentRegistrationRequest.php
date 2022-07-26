<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentRegistrationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'company',
        'address',
        'city',
        'state',
        'postcode',
        'phone',
        'email',
    ];
}
