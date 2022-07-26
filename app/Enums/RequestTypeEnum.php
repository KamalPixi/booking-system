<?php

namespace App\Enums;

abstract class RequestTypeEnum {
    const TYPE = [
        'REQUEST' => 'REQUEST',
        'RESPONSE' => 'RESPONSE',
        'GET_RESPONSE' => 'GET_RESPONSE'
    ];
}
