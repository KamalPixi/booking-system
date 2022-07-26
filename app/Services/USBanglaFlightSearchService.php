<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Helpers\USBanglaFlightHelper;
use App\Helpers\FlightHelper;
use App\Helpers\TokenHelper;
use App\Helpers\UtilityHelper;
use App\Helpers\ConstantHelper;
use App\Models\Airline;
use DateTime;
use Carbon\Carbon;


class USBanglaFlightSearchService {
    # belongs to response
    public $response;
    public $flights = [];

    # belongs to request
    public $form;
    public $body;

    public function prepareOneWay() {
        $this->body = USBanglaFlightHelper::flightSearchDataStructure();
        $this->addCommonData();
        $this->addOneWayData();
    }

    public function prepareRoundTrip() {
        $this->body = USBanglaFlightHelper::flightSearchDataStructure();
        $this->addCommonData();
        $this->addRoundTripData();
    }

    public function prepareMultiCity() {
        $this->body = USBanglaFlightHelper::flightSearchDataStructure();
        $this->addCommonData();
        $this->addMultiCityData();
    }


    // adds common form inputs to request data structure
    public function addCommonData() {
        // add adult
        if ($this->form['people']['adults'] > 0) {
            $this->body['request']['Passengers'][] = [
                'Ref' => 'P1',
                'PassengerQuantity' => intval($this->form['people']['adults']),
                'PassengerTypeCode' => 'AD',
            ];
        }
        // add child
        if ($this->form['people']['children'] > 0) {
            $this->body['request']['Passengers'][] = [
                'Ref' => 'P2',
                'PassengerQuantity' => intval($this->form['people']['children']),
                'PassengerTypeCode' => 'CHD',
            ];
        }
        // add infant
        if ($this->form['people']['infants'] > 0) {
            $this->body['request']['Passengers'][] = [
                'Ref' => 'P3',
                'PassengerQuantity' => intval($this->form['people']['infants']),
                'PassengerTypeCode' => 'INF',
            ];
        }
    }


    public function addOneWayData() {
        $this->body['request']['OriginDestinations'][] = [
            'TargetDate' => date('Y-m-d', strtotime($this->form['depart_date'])) . 'T00:00:00',
            'OriginCode' => $this->form['from'],
            'DestinationCode' => $this->form['to'],
        ];
    }

    public function addRoundTripData() {
        $this->body['request']['OriginDestinations'][] = [
            'TargetDate' => date('Y-m-d', strtotime($this->form['depart_date'])) . 'T00:00:00',
            'OriginCode' => $this->form['from'],
            'DestinationCode' => $this->form['to'],
        ];
        $this->body['request']['OriginDestinations'][] = [
            'TargetDate' => date('Y-m-d', strtotime($this->form['return_date'])) . 'T00:00:00',
            'OriginCode' => $this->form['to'],
            'DestinationCode' => $this->form['from'],
        ];
    }

    public function addMultiCityData() {
        foreach ($this->form['multi_cities'] as $city) {
            $this->body['request']['OriginDestinations'][] = [
                'TargetDate' =>  date('Y-m-d', strtotime($city['depart_date'])) . 'T00:00:00',
                'OriginCode' => $city['from'],
                'DestinationCode' => $city['to'],
            ];
        }
    }


    /**
     * Every Itinerary has its price breakdown(many prices), so we added those price breakdowns
     * inside the respective Itinerary.
     */
    public function organize() {
        $obj = $this->response->object();

        // add passengers
        foreach ($obj->Passengers as $passenger) {
            $this->flights['Passengers'][] = [
                'Ref' => $passenger->Ref,
                'PassengerQuantity' => $passenger->PassengerQuantity,
                'PassengerTypeCode' => $passenger->PassengerTypeCode,
            ];
        }

        // add flights routes
        foreach ($obj->Segments as $segment) {
            $this->flights['Segments'][] = [
                'BookingClasses' => [],
                'Ref' => $segment->Ref,
                'AirlineDesignator' => $segment->AirlineDesignator,
                'DestinationCode' => $segment->DestinationCode,
                'OriginCode' => $segment->OriginCode,

                'FlightInfo' => [
                    'FlightNumber' => $segment->FlightInfo->FlightNumber,
                    'OperatingFlightNumber' => $segment->FlightInfo->OperatingFlightNumber,
                    'OperatingAirlineDesignator' => $segment->FlightInfo->OperatingAirlineDesignator,
                    'EquipmentCode' => $segment->FlightInfo->EquipmentCode,
                    'EquipmentText' => $segment->FlightInfo->EquipmentText,
                    'DurationMinutes' => $segment->FlightInfo->DurationMinutes,
                    'DepartureDate' => $segment->FlightInfo->DepartureDate,
                    'ArrivalDate' => $segment->FlightInfo->ArrivalDate,
                    'Stops' => $segment->FlightInfo->Stops,
                ],
            ];
        }
        // add booking classes
        foreach ($obj->Segments as $k => $segment) {
            foreach ($segment->BookingClasses as $bookingClass) {
                $this->flights['Segments'][$k]['BookingClasses'][] = [
                    'CabinClassCode' => $bookingClass->CabinClassCode,
                    'code' => $bookingClass->Code,
                    'OperatingCode' => $bookingClass->OperatingCode,
                    'Quantity' => $bookingClass->Quantity,
                    'StatusCode' => $bookingClass->StatusCode,
                ];
            }
        }


        /**
         * add Itineraries, if one Itinerary has 1 or more flights, it will have total amount
         * in SaleCurrencyAmount field
         * Every Itinerary has its price breakdown, in ETTicketFares field
         */ 
        foreach ($obj->FareInfo->Itineraries as $itinerary) {
            $this->flights['Itineraries'][] = [
                'AirOriginDestinations' => [],
                'SaleCurrencyCode' => $obj->FareInfo->SaleCurrencyCode,
                'SaleCurrencyAmount' => [
                    'BaseAmount' => $itinerary->SaleCurrencyAmount->BaseAmount,
                    'TaxAmount' => $itinerary->SaleCurrencyAmount->TaxAmount,
                    'TotalAmount' => $itinerary->SaleCurrencyAmount->TotalAmount,
                ],
                'ETTicketFares' => []
            ];
        }
        foreach ($obj->FareInfo->Itineraries as $k => $itinerary) {
            foreach ($itinerary->AirOriginDestinations as $airOriginDestination) {
                $this->flights['Itineraries'][$k]['AirOriginDestinations'][] = [
                    'OriginDestinationOrder' => $airOriginDestination->OriginDestinationOrder,
                    'RefFareRule' => $airOriginDestination->RefFareRule,
                    'AirCoupons' => [
                        [
                            'CouponOrder' => $airOriginDestination->AirCoupons[0]->CouponOrder,
                            'RefSegment' => $airOriginDestination->AirCoupons[0]->RefSegment,
                            'BookingClassCode' => $airOriginDestination->AirCoupons[0]->BookingClassCode
                        ]
                    ]
                ];
            }
        }

        /**
         * add prices breakdowns for an Itinerary
         * bcz one Itinerary can have multiple prices
         * its due to having multiple routes in a single Itinerary
         */
        foreach ($obj->FareInfo->ETTicketFares as $eTTicketFare) {
            $refItineraryIndex = explode("_", $eTTicketFare->RefItinerary)[1];
            $this->flights['Itineraries'][$refItineraryIndex]['ETTicketFares'][] = [
                'RefPassenger' => $eTTicketFare->RefPassenger,
                'RefItinerary' => $eTTicketFare->RefItinerary,
                'OriginDestinationFares' => json_decode(json_encode($eTTicketFare->OriginDestinationFares), true),
                'SaleCurrencyAmount' => [
                    'BaseAmount' => $eTTicketFare->SaleCurrencyAmount->BaseAmount,
                    'TaxAmount' => $eTTicketFare->SaleCurrencyAmount->TaxAmount,
                    'TotalAmount' => $eTTicketFare->SaleCurrencyAmount->TotalAmount,
                ]
            ];
        }
    }

    // returns only those Itineraries has specified classType(Y,B,P)
    public function filterBasedOnClassType() {
        $this->flights['Itineraries'] = array_filter($this->flights['Itineraries'], function($itinerary) {
            /**
             * if it is round or multi-city trip, 
             * the US-Bangla API single itinerary contains multiple fares & booking codes,
             * so, they keeps same booking code(U,T,V,X,Z etc) for both routes,
             * as a result, we can relay on first index's(ETTicketFares[0]) BookingClassCode for all routes.
             */
            $bookingClassCode = $itinerary['ETTicketFares'][0]['OriginDestinationFares'][0]['CouponFares'][0]['BookingClassCode'] ?? null;
            
            // keep this itinerary, if booking class code match from form classType
            foreach ($this->flights['Segments'][0]['BookingClasses'] as $bookingClass) {
                if ($bookingClass['code'] == $bookingClassCode) {
                    // we are just looking for specified classType
                    if ($bookingClass['CabinClassCode'] == $this->form['class']) {
                        return true;
                    }
                }
            }

            return false;
        });

        if (!isset($this->flights['Segments'][1])) {
            return;
        }

        // second segment
        $this->flights['Itineraries'] = array_filter($this->flights['Itineraries'], function($itinerary) {
            /**
             * if it is round or multi-city trip, 
             * the US-Bangla API single itinerary contains multiple fares & booking codes,
             * so, they keeps same booking code(U,T,V,X,Z etc) for both routes,
             * as a result, we can relay on first index's(ETTicketFares[0]) BookingClassCode for all routes.
             */
            $bookingClassCode = $itinerary['ETTicketFares'][0]['OriginDestinationFares'][1]['CouponFares'][0]['BookingClassCode'] ?? null;
            
            // keep this itinerary, if booking class code match $classType
            foreach ($this->flights['Segments'][1]['BookingClasses'] as $bookingClass) {
                if ($bookingClass['code'] == $bookingClassCode) {
                    // we are just looking for specified $classType
                    if ($bookingClass['CabinClassCode'] == $this->form['class']) {
                        return true;
                    }
                }
            }

            return false;
        });
    }


    // format to our own data-structure
    public function format() {
        /**
         * Note: itineraries keys are not sequentially, but we need to access sequentially,
         * We have to depend on foreach not for-loop, bcz our itineraries keys are different
         * it happened due to array_filter during filtering itineraries classType, in here repository->format().
         */
        foreach ($this->flights['Itineraries'] as $itinerary) {

            // create empty single flight with our data structure
            $flight = FlightHelper::flightDataStructure();
            $flight['apiSource'] = ConstantHelper::US_BANGLA;

            // add legs, here a segment is a leg(flight)
            foreach ($this->flights['Segments'] as $k => $segment) {

                $timeDept = new Carbon($segment['FlightInfo']['DepartureDate']);
                $timeArr = new Carbon($segment['FlightInfo']['ArrivalDate']);

                $flight['legs'][] = [
                    'schedules' => [
                        [
                            'stopCount' => 0, // TODO
                            'eTicketable' => false, // TODO
                            'elapsedTime' => $segment['FlightInfo']['DurationMinutes'],
                            'departure' => [
                                'airport' => $segment['OriginCode'], // since US-Bangla does not provides airport
                                'city' => $segment['OriginCode'],
                                'country' => '',
                                'time' => $timeDept->format('H:i'),
                                'terminal' => '',
                                'date' => date('Y-m-d', strtotime($segment['FlightInfo']['DepartureDate'])),
                            ],
                            'arrival' => [
                                'airport' => $segment['DestinationCode'],
                                'city' => $segment['DestinationCode'],
                                'country' => '',
                                'time' => $timeArr->format('H:i'),
                                'terminal' => '',
                                'dateAdjustment' => 0, // TODO
                                'date' => date('Y-m-d', strtotime($segment['FlightInfo']['ArrivalDate'])),
                            ],
                            'carrier' => [
                                'marketing' => $segment['FlightInfo']['OperatingAirlineDesignator'],
                                'marketing_name' => Airline::where('code', $segment['FlightInfo']['OperatingAirlineDesignator'])->first()->name ?? $segment['FlightInfo']['OperatingAirlineDesignator'],
                                'operating' => $segment['FlightInfo']['OperatingAirlineDesignator'],
                                'marketingFlightNumber' => $segment['FlightInfo']['FlightNumber'],
                                'operatingFlightNumber' => $segment['FlightInfo']['OperatingFlightNumber'],
                            ],
                        ]
                    ],
                    'stops' => 0, // TODO
                ];
            }

            // add passengers
            foreach ($this->flights['Passengers'] as $pKey => $passenger) {
                $flight['passengerInfoList'][] = [
                    'passengerType' => USBanglaFlightHelper::passengerTypeCodeMap($passenger['PassengerTypeCode']),
                    'passengerNumber' => $passenger['PassengerQuantity'],
                    'nonRefundable'	=> false, // TODO
                    'totalFare' => [],
                    'baggageAllowances' => [], // will hold legs(flights) baggage allowances
                    'bookingCode' => '',
                    'cabinCode' => '',
                    'vendorCode' => '', // empty, bcz does not requires in us-bangla flight
                    'seatsAvailable' => 0,
                ];
            }

            // add fares
            for ($pKey=0; $pKey < count($flight['passengerInfoList']); $pKey++) {
                $flight['passengerInfoList'][$pKey]['totalFare']['totalPrice'] = $itinerary['ETTicketFares'][$pKey]['SaleCurrencyAmount']['TotalAmount'] * $flight['passengerInfoList'][$pKey]['passengerNumber'];
                $flight['passengerInfoList'][$pKey]['totalFare']['totalTaxAmount'] = $itinerary['ETTicketFares'][$pKey]['SaleCurrencyAmount']['TaxAmount'] * $flight['passengerInfoList'][$pKey]['passengerNumber'];
                $flight['passengerInfoList'][$pKey]['totalFare']['currency'] = $itinerary['SaleCurrencyCode'];
                $flight['passengerInfoList'][$pKey]['totalFare']['baseFareAmount'] = $itinerary['ETTicketFares'][$pKey]['SaleCurrencyAmount']['BaseAmount'];
                $flight['passengerInfoList'][$pKey]['totalFare']['totalBaseFareAmount'] = $itinerary['ETTicketFares'][$pKey]['SaleCurrencyAmount']['BaseAmount'] * $flight['passengerInfoList'][$pKey]['passengerNumber'];
            }


            # add total fare
            $totalPrice = 0;
            $totalTaxAmount = 0;
            $totalBaseFare = 0;
            foreach ($flight['passengerInfoList'] as $pInfo) {
                $totalPrice += $pInfo['totalFare']['totalPrice'];
                $totalTaxAmount += $pInfo['totalFare']['totalTaxAmount'];
                $totalBaseFare += $pInfo['totalFare']['totalBaseFareAmount'];
            }
            $flight['totalFare'] = [
                'totalPrice' => $totalPrice,
                'currency' => 'BDT',
                'totalTaxAmount' => $totalTaxAmount,
                'totalBaseFare' => $totalBaseFare,
            ];


            /**
             * add baggages & booking code
             * ETTicketFares array size and passengerInfoList array size will be always equals
             */
            foreach ($itinerary['ETTicketFares'] as $eKey => $eTTicketFare) {
                foreach ($eTTicketFare['OriginDestinationFares'] as $originDestinationFare) {
                    $baggage = $originDestinationFare['CouponFares'][0]['BagAllowances'][0] ?? [
                        'WeightMeasureQualifier' => 'kg',
                        'Weight' => 0,
                    ];
                    $flight['passengerInfoList'][$eKey]['baggageAllowances'][] = [
                        'weight' => $baggage['Weight'],
                        'unit' => $baggage['WeightMeasureQualifier']
                    ];
                    /**
                     * All BookingClassCode are not same for a single itinerary.
                     * Ex: X,U,K,B
                     */
                    $bookingCode = $originDestinationFare['CouponFares'][0]['BookingClassCode'];
                    $flight['passengerInfoList'][$eKey]['bookingCode'] = $bookingCode;
                }

                // needs for flight booking, API specific data
                $flight['passengerInfoList'][$eKey][ConstantHelper::US_BANGLA]['RefPassenger'] = $eTTicketFare['RefPassenger'];
                $flight['passengerInfoList'][$eKey][ConstantHelper::US_BANGLA]['RefItinerary'] = $eTTicketFare['RefItinerary'];
            }

            /**
             * add cabin code
             * TODO : little bit messy need improvements 
             */
            $bookingClassCodesArr = [];
            foreach ($this->flights['Segments'] as $segment) {
                $bookingClassCodes = [];
                foreach ($segment['BookingClasses'] as $bookingClass) {
                    $bookingClassCodes[] = [
                        'code' => $bookingClass['code'],
                        'CabinClassCode' => $bookingClass['CabinClassCode']
                    ];
                }
                $bookingClassCodesArr[] = $bookingClassCodes;
            }
            foreach ($flight['passengerInfoList'] as &$passengerInfo) {
                foreach ($bookingClassCodesArr as $bookingClassCodes) {
                    $flag = false;
                    foreach ($bookingClassCodes as $bookingClassCode) {
                        if ($bookingClassCode['code'] == $passengerInfo['bookingCode']) {
                            $passengerInfo['cabinCode'] = $bookingClassCode['CabinClassCode'];
                            $flag = true;
                            break;
                        }
                    }
                    if ($flag) { break; }
                }
            }

            // push to flights
            $this->flights['itineraries'][] = $flight;
        }

        // add legs(flights)
        foreach ($this->flights['Segments'] as $k => $segment) {
            $this->flights['legDescriptions'][] = [
                'departureDate' => date('Y-m-d', strtotime($segment['FlightInfo']['DepartureDate'])), 
                'departureLocation' => $segment['OriginCode'],
                'arrivalLocation' => $segment['DestinationCode'],
            ];
        }
    }

    public function addOfferData() {
        $this->flights[ConstantHelper::US_BANGLA] = [
            'offer' => $this->response->json()['Offer'],
        ];
    }

    public function hasFlights() {
        if ($this->response->object()->Passengers) { 
            return true;
        }
        return false;
    }
}
