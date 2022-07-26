<?php

namespace App\Helpers;

use App\Helpers\ConstantHelper;

/**
 * Flight Data Structure Manager 
 */

class SabreFlightHelper {
    // oneway single flight data structure
    public static function flightDataStructure() {
        return [
            'legs' => [],
            'passengerInfoList' => [],
            'apiSource' => ''
        ];
    }

    # Booking cancel data structure
    public static function bookingCancelDS() {
        return [
          "confirmationId" => "",
          "retrieveBooking" => true,
          "cancelAll" => true,
          "errorHandlingPolicy" => "ALLOW_PARTIAL_CANCEL"
        ];
    }

    # Cabin(Flight Class) Short code to full form
    public static function cabinCodeToName($code) {
      $cabinNames = [
        'P' => 'Premium First',
        'F' => 'First',
        'J' => 'Premium Business',
        'C' => 'Business',
        'S' => 'Premium Economy',
        'Y' => 'Economy'
      ];

      return $cabinNames[$code];
    }

    
    public static function passengerTypeCodeToName($code) {
      $names = [
        'ADT' => 'Adult',
        'INF' => 'Infant',
        'C' => 'Child'
      ];

      // C08 here C is child and 08 is age, so if C found as first index then return child
      if ($code[0] == 'C') {
        return $names['C'];
      }

      return $names[$code];
    }

    // flight search data structure
    public static function flightSearchDataStructure() {
        return [
            'OTA_AirLowFareSearchRQ' => [
              'OriginDestinationInformation' => [],
              'POS' => [
                'Source' => [
                  [
                    'PseudoCityCode' => ConstantHelper::SABRE_PCC,
                    'RequestorID' => [
                      'CompanyName' => [
                        'Code' => 'TN',
                      ],
                      'ID' => '1',
                      'Type' => '1',
                    ],
                  ],
                ],
              ],
              'TPA_Extensions' => [
                'IntelliSellTransaction' => [
                  'RequestType' => [
                    'Name' => '50ITINS', # 50 is limitation to this agent's PCC
                  ],
                ],
              ],
              'TravelPreferences' => [
                'CabinPref' => [],
                'TPA_Extensions' => [
                  'DataSources' => [
                    'ATPCO' => 'Enable',
                    'LCC' => 'Disable',
                    'NDC' => 'Disable',
                  ],
                ],
              ],
              'TravelerInfoSummary' => [
                'AirTravelerAvail' => [
                  [
                    'PassengerTypeQuantity' => [],
                  ],
                ],
                'SeatsRequested' => [1],
              ],
              'Version' => '4',
            ],
        ];
    }


    // flight booking
    public static function flightBookingDataStructure() {
      return [
        'CreatePassengerNameRecordRQ' => [
          'version' => '2.4.0',
          'targetCity' => ConstantHelper::SABRE_PCC,
          'haltOnAirPriceError' => true,
          'TravelItineraryAddInfo' => [
            'AgencyInfo' => [
              'Address' => [
                'AddressLine' => ConstantHelper::AGENT['name'],
                'CityName' => ConstantHelper::AGENT['city'],
                'CountryCode' => ConstantHelper::AGENT['country_code'],
                'PostalCode' => ConstantHelper::AGENT['postal_code'],
                'StateCountyProv' => [
                  'StateCode' => ConstantHelper::AGENT['country_code'],
                ],
                'StreetNmbr' => ConstantHelper::AGENT['address'],
              ],
              'Ticketing' => [
                'TicketType' => '7TAW',
              ],
            ],
            'CustomerInfo' => [
              'ContactNumbers' => [
                'ContactNumber' => [],
              ],
              'PersonName' => [],
            ],
          ],
          'AirBook' => [
            'HaltOnStatus' => [
              ['Code' => 'HL'],
              ['Code' => 'KK'],
              ['Code' => 'LL'],
              ['Code' => 'NN'],
              ['Code' => 'NO'],
              ['Code' => 'UC'],
              ['Code' => 'US']
            ],
            'OriginDestinationInformation' => [
              'FlightSegment' => [],
            ],
            'RedisplayReservation' => [
              'NumAttempts' => 10,
              'WaitInterval' => 300,
            ],
          ],
          'AirPrice' => [
            [
              'PriceRequestInformation' => [
                'Retain' => true,
                'OptionalQualifiers' => [
                  'FOP_Qualifiers' => [
                    'BasicFOP' => [
                      'Type' => 'INV',
                    ],
                  ],
                  'PricingQualifiers' => [
                    'PassengerType' => [],
                  ],
                ],
              ]
            ],
          ],
          'SpecialReqDetails' => [
            'SpecialService' => [
              'SpecialServiceInfo' => [
                'AdvancePassenger' => [],
                'SecureFlight' => [],
                'Service' => [],
              ],
            ],
          ],
          'PostProcessing' => [
            'EndTransaction' => [
              'Source' => [
                'ReceivedFrom' => ConstantHelper::AGENT['name'],
              ],
            ],
            'RedisplayReservation' => [
              'waitInterval' => 100,
            ],
          ],
        ],
      ];
    }


    public static function flightRevalidateDataStructure() {
        return [
            'OTA_AirLowFareSearchRQ' => [
              'OriginDestinationInformation' => [],
              'POS' => [
                'Source' => [
                  [
                    'PseudoCityCode' => ConstantHelper::SABRE_PCC,
                    'RequestorID' => [
                      'CompanyName' => [
                        'Code' => 'TN',
                      ],
                      'ID' => '1',
                      'Type' => '1',
                    ],
                  ],
                ],
              ],
              'TPA_Extensions' => [
                'IntelliSellTransaction' => [
                  'RequestType' => [
                    'Name' => '1ITINS',
                  ],
                ],
              ],
              'TravelPreferences' => [
                'CabinPref' => [],
                'TPA_Extensions' => [
                  'DataSources' => [
                    'ATPCO' => 'Enable',
                    'LCC' => 'Disable',
                  ],
                ],
              ],
              'TravelerInfoSummary' => [
                'AirTravelerAvail' => [
                  [
                    'PassengerTypeQuantity' => [],
                  ],
                ],
                'SeatsRequested' => [1],
              ],
              'Version' => '3',
            ],
        ];
    }
}
