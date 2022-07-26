<?php

namespace App\Helpers;

use App\Helpers\TokenHelper;
use App\Helpers\UtilityHelper;

class USBanglaFlightHelper {
    
    public static function flightSearchDataStructure() {
        return [
            'request' => [
              'Passengers' => [],
              'OriginDestinations' => [],
              'FareDisplaySettings' => [
                'RewardSearch' => false,
                'SaleCurrencyCode' => 'BDT',
                'ShowWebClasses' => true,
                'ManualCombination' => false,
              ],
              'RequestInfo' => [
                'AuthenticationKey' => TokenHelper::getUSBanglaToken(),
                'CultureName' => 'en-GB',
              ],
            ],
        ];
    }



    public static function flightBookingDataStructure() {
        return [
            'request' => [
                'Offer' => [],
                'SpecialServices' => [],
                'FareInfo' => [
                    'EMDTicketFares' => [],
                ],
                'Passengers' => [],
                'RequestInfo' => [
                    'AuthenticationKey' => TokenHelper::getUSBanglaToken(),
                    'CultureName' => 'en-GB',
                ],
            ]
        ];
    }

    public static function flightPrepareDataStructure() {
        return [
            'request' => [
                "Offer" => [
                    "RefItinerary" => "",
                    "Ref" => ""
                ],
                  "RequestInfo" => [
                    "AuthenticationKey" => TokenHelper::getUSBanglaToken(),
                    "CultureName" => "en-GB"
                ]
            ]
        ];
    }

    // oneway single flight data structure
    public static function cabinCodeToName($code) {
        $cabinNames = [
          'P' => 'Premium Economy',
          'C' => 'Business',
          'Y' => 'Economy'
        ];
  
        return $cabinNames[$code];
    }
  
      
    public static function passengerTypeCodeToName($code) {
        $names = [
            'AD' => 'Adult',
            'ADT' => 'Adult',
            'INF' => 'Infant',
            'CHD' => 'Child'
        ];

        return $names[$code];
    }

    public static function passengerTypeCodeMap($code) {
        $codes = [
            'AD' => 'ADT',
            'INF' => 'INF',
            'CHD' => 'CHD'
        ];

        return $codes[$code];
    }

    // /Date(1654872600000+0200)/ -> 1654872600000 -> timestamp
    public static function usBanglaDateTimeParse($date) {
        $timestamp = explode('(', explode('+', $date)[0])[1] / 1000;
        $dateTime = date('Y-m-d H:m:s', $timestamp);
        // 2hrs got removed during explode, that's why again adding 2hrs
        return strtotime("+2 hours", strtotime($dateTime));
    }

}
