<?php

namespace App\Helpers;

/**
 * Token Manager
 */

class TokenHelper {

    // const SABER_TOKEN = '';

    public static function getSabreHeader() {
        return [
            'Authorization' => 'Bearer ' . self::SABER_TOKEN,
            'Content-Type' => 'application/json'
        ];    
    }

    public static function getUSBanglaToken() {
        return '';
    }
    
}
