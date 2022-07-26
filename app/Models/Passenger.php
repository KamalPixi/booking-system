<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'title',
        'first_name',
        'surname',
        'type',
        'phone_no',
        'email',
        'dob',
        'gender',
        'nationality_country',
        'passport_no',
        'passport_issuing_country',
        'passport_issuance_date',
        'passport_expiry_date',
    ];

    public function passportCopy() {
        return $this->morphOne(File::class, 'fileable', 'fileable_type', 'fileable_id');
    }
    public function passportVisa() {
        return $this->morphOne(File::class, 'fileable', 'fileable_type', 'fileable_id');
    }
}
