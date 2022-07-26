<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Helpers\SabreFlightHelper;
use App\Helpers\TokenHelper;
use App\Helpers\ConstantHelper;

class SabreFlightRevalidateService {

    public $response;
    public $responseJson;

    public $itinerary;
    public $body;

    public function revalidate($itinerary) {
        $this->itinerary = $itinerary;

        # empty body
        $this->body = SabreFlightHelper::flightRevalidateDataStructure();

        # add form to body
        $this->addOriginDestination();
        $this->addTravelPref();
        $this->addPassengerTypeQty();
        
        try {
            $this->response = Http::withHeaders(TokenHelper::getSabreHeader())
            ->post(ConstantHelper::SABRE_FLIGHT_REVALIDATE_API, $this->body);

            if (!$this->response->ok()) {
                return null;
            }
            $this->responseJson = $this->response->json();
            return $this->responseJson;

        } catch (\ErrorException $th) {
            return null;
        }

        return null;
    }


    public function getPrice() {
        return $this->responseJson['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][0]['fare']['totalFare']['totalPrice'] ?? 0;
    }

    public function hasPriceChanged() {
        $price = $this->getPrice();
        $totalPrice = 0;
        foreach ($this->itinerary['passengerInfoList'] as $passengerInfo) {
            $totalPrice += $passengerInfo['totalFare']['totalPrice'];     
        }

        if ($totalPrice != $price) {
            return true;
        }

        return false;
    }


    private function addOriginDestination() {
        foreach ($this->itinerary['legs'] as $leg) {
            $this->body['OTA_AirLowFareSearchRQ']['OriginDestinationInformation'][] = [
                'RPH' => '0',
                'DepartureDateTime' => $leg['schedules'][0]['departure']['date'] . 'T' . $leg['schedules'][0]['departure']['time'] . ':00',
                'OriginLocation' => [
                  'LocationCode' => $leg['schedules'][0]['departure']['city'],
                ],
                'DestinationLocation' => [
                  'LocationCode' => $leg['schedules'][count($leg['schedules']) - 1]['arrival']['city'],
                ],
                'TPA_Extensions' => [
                  'SegmentType' => [
                    'Code' => 'O',
                  ],
                  'Flight' => [],
                ],
            ];
        }

        foreach ($this->itinerary['legs'] as $k => $leg) {
            foreach ($leg['schedules'] as $j => $schedule) {
                $this->body['OTA_AirLowFareSearchRQ']['OriginDestinationInformation'][$k]['TPA_Extensions']['Flight'][] = [
                    'Number' => $schedule['carrier']['marketingFlightNumber'],
                    'DepartureDateTime' => $schedule['departure']['date'] . 'T' . $schedule['departure']['time'].':00',
                    'ArrivalDateTime' => $schedule['arrival']['date'] . 'T' . $schedule['arrival']['time'].':00',
                    'Type' => 'A',
                    'ClassOfService' => $this->itinerary['passengerInfoList'][0]['bookingCode'],
                    'OriginLocation' => [
                      'LocationCode' => $schedule['departure']['city'],
                    ],
                    'DestinationLocation' => [
                      'LocationCode' => $schedule['arrival']['city'],
                    ],
                    'Airline' => [
                      'Operating' => $schedule['carrier']['operating'],
                      'Marketing' => $schedule['carrier']['marketing'],
                    ],
                ];
            }
        }
    }


    private function addTravelPref() {
        $this->body['OTA_AirLowFareSearchRQ']['TravelPreferences']['CabinPref'][] = [
            'PreferLevel' => 'Preferred',
            'Cabin' => $this->itinerary['passengerInfoList'][0]['cabinCode'],
        ];
    }

    private function addPassengerTypeQty() {
        # CHD age 11yrs INF 2yrs
        $CHDCode = '';
        $totalADT = 0;
        $totalCHD = 0;
        $totalINF = 0;

        foreach ($this->itinerary['passengerInfoList'] as $p) {
            if ($p['passengerType'] == 'ADT') {
                $totalADT++;
            }
            if ($p['passengerType'][0] == 'C') {
                $totalCHD++;
                $CHDCode = $p['passengerType'];
            }
            if ($p['passengerType'][0] == 'I') {
                $totalINF++;
            }
        }

        $this->body['OTA_AirLowFareSearchRQ']['TravelerInfoSummary']['AirTravelerAvail'][0]['PassengerTypeQuantity'][] = [
            'Code' => 'ADT',
            'Quantity' => $totalADT,
        ];
        if ($totalCHD > 0) {
            $this->body['OTA_AirLowFareSearchRQ']['TravelerInfoSummary']['AirTravelerAvail'][0]['PassengerTypeQuantity'][] = [
                'Code' => $CHDCode,
                'Quantity' => $totalCHD,
            ];
        }
        if ($totalINF > 0) {
            $this->body['OTA_AirLowFareSearchRQ']['TravelerInfoSummary']['AirTravelerAvail'][0]['PassengerTypeQuantity'][] = [
                'Code' => 'INF',
                'Quantity' => $totalINF,
            ];
        }

    }
    
}
