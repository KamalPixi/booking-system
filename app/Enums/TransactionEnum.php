<?php

namespace App\Enums;

abstract class TransactionEnum {
    const TYPE = [
        'PAYMENT' => 'PAYMENT',
        'REFUND' => 'REFUND',
        'WITHDRAWAL' => 'WITHDRAWAL',
        'DEPOSIT' => 'DEPOSIT',
    ];

    const SIGN = [
        'PLUS' => '+',
        'MINUS' => '-'
    ];

    const STATUS = [
        'PAID' => 'PAID',
        'PARTIAL_PAID' => 'PARTIAL_PAID',
        'UNPAID' => 'UNPAID',
        'PENDING' => 'PENDING',
        'COMPLETED' => 'COMPLETED',
        'CANCELED' => 'CANCELED',
        'FAILED' => 'FAILED',
        'PROCESSING' => 'PROCESSING',
        'REFUNDED' => 'REFUNDED',

        # Used for different types of request for things
        'APPROVED' => 'APPROVED',
        'REJECTED' => 'REJECTED',
    ];

    
    const PURPOSE = [
        'AIR_TICKET_ISSUE' => 'AIR_TICKET_ISSUE', # in use
        'ACCOUNT_DEPOSIT' => 'ACCOUNT_DEPOSIT', # in use
        'AIR_BOOKING_REFUND' => 'AIR_BOOKING_REFUND', # in use
    ];

    const METHOD = [
        'CASH' => 'CASH',
        'BKASH' => 'BKASH',
        'NAGAD' => 'NAGAD',
        'BANK_DEPOSIT' => 'BANK_DEPOSIT', # in use
        'ONLINE_BANK_TRANSFER' => 'ONLINE_BANK_TRANSFER',
        'ONLINE_PAYMENT' => 'ONLINE_PAYMENT', # in use
        'ACCOUNT_BALANCE' => 'ACCOUNT_BALANCE', # In use
    ];

    const METHOD_FEE_TYPE = [
        'FIXED' => 'FIXED',
        'PERCENTAGE' => 'PERCENTAGE'
    ];

    const CURRENCY = [
        'BDT' => 'BDT'
    ];
}
