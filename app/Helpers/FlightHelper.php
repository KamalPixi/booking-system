<?php

namespace App\Helpers;

/**
 * Flight Data Structure Manager 
 */

class FlightHelper {
    // single flight data structure
    public static function flightDataStructure() {
        return [
            'legs' => [],
            'passengerInfoList' => [],
            'totalFare' => [
                'totalPrice' => 0,
                'totalTaxAmount' => 0,
                'currency' => 'BDT',
                'totalBaseFare' => 0,
            ],
            'apiSource' => ''
        ];
    }


    
    /**
     * Data structure of passenger.
     * Will be used during flight creation.
     */
    public static function passengerDataStructure() {
        return [
          'title' => 'Mr',
          'first_name' => '',
          'surname' => '',
          'type' => '', // ADT,CHD,INF
          'phone_no' => '',
          'dob' => '',
          'gender' => 'M',
          'passport_no' => '',
          'passport_type' => 'P',
          'passport_issuing_country' => 'BD',
          'passport_expiry_date' => '',
          'nationality_country' => 'BD',
          'passport' => '',
          'visa' => '',
        ];
    }

}
