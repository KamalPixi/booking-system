<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\FlightSearchTemp;
use App\Models\Airline;
use App\Helpers\SabreFlightHelper;
use App\Helpers\FlightHelper;
use App\Helpers\TokenHelper;
use App\Helpers\UtilityHelper;
use App\Helpers\ConstantHelper;
use DateTime;
use Carbon\Carbon;


class SabreFlightSearchService {
    # belongs to response
    public $response;
    public $flights = [];

    # belongs to request
    public $form;
    public $body;

    public function prepareOneWay() {
        $this->body = SabreFlightHelper::flightSearchDataStructure();
        $this->addCommonData();
        $this->addOneWayData();
    }

    public function prepareRoundTrip() {
        $this->body = SabreFlightHelper::flightSearchDataStructure();
        $this->addCommonData();
        $this->addRoundTripData();
    }

    public function prepareMultiCity() {
        $this->body = SabreFlightHelper::flightSearchDataStructure();
        $this->addCommonData();
        $this->addMultiCityData();
    }


    private function addCommonData() {
        # add adult
        if ($this->form['people']['adults'] > 0) {
            $this->body['OTA_AirLowFareSearchRQ']['TravelerInfoSummary']['AirTravelerAvail'][0]['PassengerTypeQuantity'][] = [
                'Code' => 'ADT',
                'Quantity' => intval($this->form['people']['adults']),
            ];
        }
        # add child
        if ($this->form['people']['children'] > 0) {
            foreach ($this->form['children_dob'] as $dob) {
                $this->body['OTA_AirLowFareSearchRQ']['TravelerInfoSummary']['AirTravelerAvail'][0]['PassengerTypeQuantity'][] = [
                    'Code' => 'C' . UtilityHelper::dobToAge($dob),
                    'Quantity' => 1,
                ];
            }
        }
        # add infant
        if ($this->form['people']['infants'] > 0) {
            $this->body['OTA_AirLowFareSearchRQ']['TravelerInfoSummary']['AirTravelerAvail'][0]['PassengerTypeQuantity'][] = [
                'Code' => 'INF',
                'Quantity' => intval($this->form['people']['infants']),
            ];
        }
        $this->body['OTA_AirLowFareSearchRQ']['TravelPreferences']['CabinPref'][] = [
            'PreferLevel' => 'Preferred',
            'Cabin' => $this->form['class'],
        ];
    }

    # adds only oneway required data to request data structure
    private function addOneWayData() {
        $this->body['OTA_AirLowFareSearchRQ']['OriginDestinationInformation'][] = [
            'DepartureDateTime' =>  date('Y-m-d', strtotime($this->form['depart_date'])).'T00:00:00',
            'DestinationLocation' => [
              'LocationCode' => $this->form['to'],
            ],
            'OriginLocation' => [
              'LocationCode' => $this->form['from'],
            ],
            'RPH' => '1',
        ];
    }


    # adds only roundtrip required data to request data structure
    public function addRoundTripData() {
        # point A -> B
        $this->body['OTA_AirLowFareSearchRQ']['OriginDestinationInformation'][] = [
            'DepartureDateTime' =>  date('Y-m-d', strtotime($this->form['depart_date'])).'T00:00:00',
            'DestinationLocation' => [
              'LocationCode' => $this->form['to'],
            ],
            'OriginLocation' => [
              'LocationCode' => $this->form['from'],
            ],
            'RPH' => '1',
        ];

        # point A <- B
        $this->body['OTA_AirLowFareSearchRQ']['OriginDestinationInformation'][] = [
            'DepartureDateTime' =>  date('Y-m-d', strtotime($this->form['return_date'])).'T00:00:00',
            'DestinationLocation' => [
              'LocationCode' => $this->form['from'],
            ],
            'OriginLocation' => [
              'LocationCode' => $this->form['to'],
            ],
            'RPH' => '2',
        ];
    }


    # adds only multi-city required data to request data structure
    public function addMultiCityData() {
        foreach ($this->form['multi_cities'] as $city) {
            $this->body['OTA_AirLowFareSearchRQ']['OriginDestinationInformation'][] = [
                'DepartureDateTime' =>  date('Y-m-d', strtotime($city['depart_date'])).'T00:00:00',
                'DestinationLocation' => [
                  'LocationCode' => $city['to'],
                ],
                'OriginLocation' => [
                  'LocationCode' => $city['from'],
                ],
                'RPH' => '0',
            ];
        }
    }




    # transform sabre response to our own data-structure
    public function format($obj) {
        $obj = $this->response->object();

        $cabinCodeNames = [];

        if (!isset($obj->groupedItineraryResponse->itineraryGroups[0]->itineraries)) {
            throw new \ErrorException('No flights found!');
        }

        $itineraries = $obj->groupedItineraryResponse->itineraryGroups[0]->itineraries;
        $legDescriptions = $obj->groupedItineraryResponse->itineraryGroups[0]->groupDescription->legDescriptions;
        $fareComponentDescs = $obj->groupedItineraryResponse->fareComponentDescs;

        for ($i=0; $i < count($itineraries); $i++) { 
            # create empty flight with data structure
            $flight = FlightHelper::flightDataStructure();

            # total fare
            $totalFare = $itineraries[$i]->pricingInformation[0]->fare->totalFare;
            $flight['totalFare'] = [
                'totalPrice' => $totalFare->totalPrice,
                'totalTaxAmount' => $totalFare->totalTaxAmount,
                'currency' => $totalFare->currency,
                'totalBaseFare' => $totalFare->equivalentAmount,
            ];
            
            # set fare & baggage for individual
            $passengerInfoList = $itineraries[$i]->pricingInformation[0]->fare->passengerInfoList;
            $baggageAllowances = $obj->groupedItineraryResponse->baggageAllowanceDescs;
            foreach ($passengerInfoList as $pInfo) {
                $baggageRefId = $pInfo->passengerInfo->baggageInformation[0]->allowance->ref;
                $fareCompRef = $pInfo->passengerInfo->fareComponents[0]->ref;

                $flight['passengerInfoList'][] = [
                    'passengerType' => $pInfo->passengerInfo->passengerType,
                    'passengerNumber' => $pInfo->passengerInfo->passengerNumber,
                    'nonRefundable'	=> $pInfo->passengerInfo->nonRefundable,
                    'totalFare' => [ # individual passengers fare
                        'totalPrice' => $pInfo->passengerInfo->passengerTotalFare->totalFare,
                        'totalTaxAmount' => $pInfo->passengerInfo->passengerTotalFare->totalTaxAmount,
                        'currency' => $pInfo->passengerInfo->passengerTotalFare->currency,
                        'baseFareAmount' => $pInfo->passengerInfo->passengerTotalFare->equivalentAmount,
                        'totalBaseFareAmount' => $pInfo->passengerInfo->passengerTotalFare->equivalentAmount * $pInfo->passengerInfo->passengerNumber,
                    ],
                    'baggageAllowances' => [
                        [                        
                            'weight' => $baggageAllowances[$baggageRefId - 1]->weight ?? 0,
                            'unit' => $baggageAllowances[$baggageRefId - 1]->unit ?? 'KG'
                        ]
                    ],
                    'bookingCode' => $pInfo->passengerInfo->fareComponents[0]->segments[0]->segment->bookingCode,
                    'cabinCode' => $fareComponentDescs[$fareCompRef - 1]->cabinCode,
                    'vendorCode' => $fareComponentDescs[$fareCompRef - 1]->vendorCode,
                    'seatsAvailable' => $pInfo->passengerInfo->fareComponents[0]->segments[0]->segment->seatsAvailable,
                ];
            }

            /**
             * Adding legs.
             * legs are the flights, we are adding as much as flight we got from API.
             * Note: a flight can have multiple schedule so we are also adding those schedules
             * inside the respective leg(flight).
             */
            foreach ($itineraries[$i]->legs as $k => $leg) {
                $legRefId = $leg->ref;
                $schedules = $obj->groupedItineraryResponse->legDescs[$legRefId - 1]->schedules;
               
                /**
                 * One single flight can have multiple schedules
                 * Lets say DAC-KUL, now this single flight will go DAC-SIN then SIN-KUL
                 * Here DAC-SIN is a schedule & SIN-KUL is another schedule.
                 */

                $dateAdjDepart = 0;
                $dateAdjArrival = 0;
                foreach ($schedules as $schedule) {
                    $scheduleDesc = $obj->groupedItineraryResponse->scheduleDescs[$schedule->ref - 1];
                    
                    $tempDateAdjArrival = $scheduleDesc->arrival->dateAdjustment ?? 0;
                    if ($tempDateAdjArrival > 0) {
                        $dateAdjArrival = $tempDateAdjArrival;
                    }
                    
                    $timeDept = new Carbon($scheduleDesc->departure->time);
                    $timeArr = new Carbon($scheduleDesc->arrival->time);
                    
                    $flight['legs'][$k]['schedules'][] = [
                        'stopCount' => $scheduleDesc->stopCount,
                        'eTicketable' => $scheduleDesc->eTicketable,
                        'elapsedTime' => $scheduleDesc->elapsedTime,
                        'departure' => [
                            'airport' => $scheduleDesc->departure->airport,
                            'city' => $scheduleDesc->departure->city,
                            'country' => $scheduleDesc->departure->country,
                            'time' => $timeDept->format('H:i'),
                            'timeServer' => $scheduleDesc->departure->time, #for debug purpose
                            'terminal' => $scheduleDesc->departure->terminal ?? '',
                            'date' => ($dateAdjDepart > 0) ? UtilityHelper::addDayToDate($legDescriptions[$k]->departureDate, $dateAdjDepart) : $legDescriptions[$k]->departureDate, # custom added
                        ],
                        'arrival' => [
                            'airport' => $scheduleDesc->arrival->airport,
                            'city' => $scheduleDesc->arrival->city,
                            'country' => $scheduleDesc->arrival->country,
                            'time' => $timeArr->format('H:i'),
                            'timeServer' => $scheduleDesc->arrival->time, #for debug purpose
                            'terminal' => $scheduleDesc->arrival->terminal ?? '',
                            'dateAdjustment' => $tempDateAdjArrival,
                            'date' => ($dateAdjArrival > 0) ? UtilityHelper::addDayToDate($legDescriptions[$k]->departureDate, $dateAdjArrival) : $legDescriptions[$k]->departureDate,
                        ],
                        'carrier' => [
                            'marketing' => $scheduleDesc->carrier->marketing,
                            'marketing_name' => Airline::where('code', $scheduleDesc->carrier->marketing)->first()->name ?? $scheduleDesc->carrier->marketing,
                            'marketingFlightNumber' => $scheduleDesc->carrier->marketingFlightNumber,
                            'operating' => $scheduleDesc->carrier->operating,
                            'operatingFlightNumber' => $scheduleDesc->carrier->operatingFlightNumber ?? $scheduleDesc->carrier->marketingFlightNumber
                        ]
                    ];

                    if ($dateAdjArrival > 0) {
                        $dateAdjDepart = $dateAdjArrival;
                    }

                    /**
                     * Add stops (integer) for a single leg
                     * Means for a single flight how many transit this single flight will take.
                     * Lets say a flight DAC-KUL, Now this flight will go DAC-SIN then will go SIN-KUL
                     * so, here now it takes 1 stop(DAC-SIN). that is what we are counting here.
                     */
                    if (isset($flight['legs'][$k]['stops'])) {
                        $flight['legs'][$k]['stops']++;
                    }else {
                        $flight['legs'][$k]['stops'] = 0;
                    }
                }
            }

            # every flight will hold api source
            $flight['apiSource'] = ConstantHelper::SABRE;

            # push this single flight inside the array
            $this->flights['itineraries'][] = $flight;
        }

        # set legDescriptions
        foreach ($legDescriptions as $legD) {
            $this->flights['legDescriptions'][] = [
                'departureDate' => $legD->departureDate,
                'departureLocation' => $legD->departureLocation,
                'arrivalLocation' => $legD->arrivalLocation,
            ];
        }
    }
}
