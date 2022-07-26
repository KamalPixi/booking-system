<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrePurchasedAir extends Model
{
    use HasFactory;

    protected $fillable = [
        'airline',
        'from',
        'to',
        'fare',
        'depart_date',
        'arrival_date',
        'baggage',
        'transit_location',
        'transit_time',
        'reference_remark',
        'count',
        'created_by',
        'manage_by',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function airlineName() {
        return Airline::where('code', $this->airline)->first();
    }

    public function explodeLocation() {
        return explode(',', $this->transit_location);
    }

    public function countLocations() {
        return count($this->explodeLocation());
    }
}
