<?php

namespace App\Enums;

abstract class FlightEnum {
    const STATUS = [
        'CONFIRMED' => 'CONFIRMED',
        'CANCELED' => 'CANCELED',
        'PENDING' => 'PENDING',
        'IN_PROGRESS' => 'IN_PROGRESS',
        'CHANGED' => 'CHANGED',
        'REFUNDED' => 'REFUNDED',
    ];

    const TYPE = [
        'ONE_WAY',
        'ROUND_TRIP',
        'MULTI_CITY',
    ];
}
