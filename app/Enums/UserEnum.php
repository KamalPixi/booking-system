<?php

namespace App\Enums;

abstract class UserEnum {
    const TYPE = [
        'ADMIN' => 'ADMIN',
        'ADMIN_USER' => 'ADMIN_USER',
        'AGENT' => 'AGENT',
        'AGENT_USER' => 'AGENT_USER',
        'CUSTOMER' => 'CUSTOMER'
    ];

    const STATUS = [
        'ACTIVE' => 1,
        'INACTIVE' => 0
    ];

    public static function getDefaultPassword() {
        return uniqid();
    }
}
