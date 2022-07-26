<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirBookingPassenger extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'air_booking_id',
        'title',
        'first_name',
        'surname',
        'type',
        'dob',
        'gender',
        'nationality_country',
        'passport_no',
        'passport_type',
        'passport_issuing_country',
        'passport_issuance_date',
        'passport_expiry_date',
        'phone_no',
        'passport',
        'visa',
    ];


    public function airBooking() {
        # Params Class, foreign_key, owner_key
        return $this->belongsTo(AirBooking::class, 'air_booking_id');
    }

    public function fullName() {
        return $this->first_name . ' ' . $this->surname;
    }
    public function fullNameWithTitle() {
        return $this->first_name . ' ' . $this->surname .'/'.$this->title;
    }

    public function files() {
        return $this->morphMany(File::class, 'fille', 'fileable_type', 'fileable_id');
    }

    public function passport() {
        return $this->files->filter(function($file) {
            if ($file->file_key == \App\Enums\FileEnum::TYPE[0]) {
                return true;
            }
            return false;
        })->first();
    }

    public function visa() {
        return $this->files->filter(function($file) {
            if ($file->file_key == \App\Enums\FileEnum::TYPE[1]) {
                return true;
            }
            return false;
        })->first();
    }
}
