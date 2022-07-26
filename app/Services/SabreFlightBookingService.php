<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Helpers\SabreFlightHelper;
use App\Helpers\TokenHelper;
use App\Helpers\ConstantHelper;
use App\Helpers\UtilityHelper;

class SabreFlightBookingService {

    public $response;
    public $itinerary;
    public $form;
    public $body;


    public function book($itinerary, $form) {
        $this->itinerary = $itinerary;
        $this->form = $form;

        $this->body = SabreFlightHelper::flightBookingDataStructure();
        $this->addExtraFields();
        $this->addCustomerInfo();
        $this->addFlights();
        $this->addAirPrice();
        $this->AddSpecialReq();

        try {
            $this->response = Http::withHeaders(TokenHelper::getSabreHeader())
            ->post(ConstantHelper::SABRE_FLIGHT_BOOKING_API, $this->body);
        } catch (\ErrorException $th) {
            return null;
        }

        // dd($this->response->json());

        if (!$this->response->ok()) {
            return null;
        }

        $responseJson = $this->response->json();
        if ($responseJson['CreatePassengerNameRecordRS']['ApplicationResults']['status'] == 'Complete') {
            return [$this->body, $responseJson];
        }

        return null;
    }



    public function cancelBooking($pnr) {
        $this->body = SabreFlightHelper::bookingCancelDS();
        $this->body['confirmationId'] = $pnr;

        try {
            $this->response = Http::withHeaders(TokenHelper::getSabreHeader())
            ->post(ConstantHelper::SABRE_FLIGHT_BOOKING_CANCEL_API, $this->body);
        } catch (\ErrorException $th) {
            return null;
        }

        if (!$this->response->ok()) {
            return null;
        }

        $json = $this->response->json();

        # error key exists in response, if booking is already canceled 
        if (isset($json['errors'])) {
            return null;
        }

        return $json;
    }

    private function addExtraFields() {
        $flag = 0;
        $adtNameNumbers = [];

        foreach($this->form['passengers'] as $k => &$passenger) {
            $passenger['nameNumber'] = (string) ($k+1) . '.1';
            $passenger['isInfant'] = false;
            $passenger['nameReference'] = '';
            if ($passenger['type'] == 'ADT') {
                $passenger['nameReference'] = ''; // not required for ADT, thats y making it empty.
                $adtNameNumbers[] = (string) ($k+1) . '.1';
            }

            if ($passenger['type'][0] == 'C') {
                $passenger['nameReference'] = 'C' . UtilityHelper::dobToAge($passenger['dob']);
            }
        }

        foreach($this->form['passengers'] as $k => &$passenger) {
            if ($passenger['type'] == 'INF') {
                $passenger['isInfant'] = true;
                $passenger['nameReference'] = 'I' . UtilityHelper::dobToTotalMonths($passenger['dob']);
                $passenger['belongsToNameNumber'] = $adtNameNumbers[$flag];
                $passenger['gender'] = $passenger['gender'] . 'I';
                $flag++;
            }
        }
    }


    private function addCustomerInfo() {
        // contact numbers for 1st passenger only
        $this->body['CreatePassengerNameRecordRQ']['TravelItineraryAddInfo']['CustomerInfo']['ContactNumbers']['ContactNumber'][] = [
            'NameNumber' => $this->form['passengers'][0]['nameNumber'],
            'Phone' =>  $this->form['passengers'][0]['phone_no'] ?? '8801777997703', # TODO
            'PhoneUseType' => 'M',
        ];

        foreach ($this->form['passengers'] as $k => $passenger) {
            $this->body['CreatePassengerNameRecordRQ']['TravelItineraryAddInfo']['CustomerInfo']['PersonName'][] = [
                'Infant' => $passenger['isInfant'],
                'NameNumber' => $passenger['nameNumber'],
                'NameReference' => $passenger['nameReference'],
                'PassengerType' => $passenger['type'],
                'GivenName' => $passenger['first_name'] . ' ' . $passenger['title'],
                'Surname' => $passenger['surname'],
            ];
        }
    }

    private function addFlights() {
        $totalPassengers = 0;
        foreach ($this->itinerary['passengerInfoList'] as $pInfo) {
            if ($pInfo['passengerType'] == 'INF') {
                continue;
            }
            $totalPassengers += $pInfo['passengerNumber'];
        }

        foreach ($this->itinerary['legs'] as $k => $leg) {
            foreach ($leg['schedules'] as $schedule) {
                $this->body['CreatePassengerNameRecordRQ']['AirBook']['OriginDestinationInformation']['FlightSegment'][] = [
                    'DepartureDateTime' => $schedule['departure']['date'] . 'T' . $schedule['departure']['time'].':00',
                    'ArrivalDateTime' => $schedule['arrival']['date'] . 'T' . $schedule['arrival']['time'].':00',
                    'FlightNumber' => (string)  $schedule['carrier']['marketingFlightNumber'],
                    'NumberInParty' => (string) $totalPassengers,
                    'ResBookDesigCode' => $this->itinerary['passengerInfoList'][0]['bookingCode'], // all passengers booking code is same
                    'Status' => 'NN',
                    'OriginLocation' => [
                      'LocationCode' => $schedule['departure']['city'],
                    ],
                    'DestinationLocation' => [
                      'LocationCode' => $schedule['arrival']['city'],
                    ],
                    'MarketingAirline' => [
                      'Code' => $schedule['carrier']['marketing'],
                      'FlightNumber' => (string) $schedule['carrier']['marketingFlightNumber'],
                    ],
                ];
            }
        }
    }

    private function addAirPrice() {
        foreach ($this->itinerary['passengerInfoList'] as $passengerInfo) {
            $this->body['CreatePassengerNameRecordRQ']['AirPrice'][0]['PriceRequestInformation']['OptionalQualifiers']['PricingQualifiers']['PassengerType'][] = [
                'Code' => $passengerInfo['passengerType'],
                'Quantity' => (string) $passengerInfo['passengerNumber'],
            ];
        }
    }
    
    private function AddSpecialReq() {
        foreach ($this->form['passengers'] as $k => $passenger) {
            $this->body['CreatePassengerNameRecordRQ']['SpecialReqDetails']['SpecialService']['SpecialServiceInfo']['AdvancePassenger'][] = [
                'Document' => [
                    'IssueCountry' => $passenger['passport_issuing_country'], // BGD
                    'NationalityCountry' => $passenger['nationality_country'], // BD
                    'ExpirationDate' => $passenger['passport_expiry_date'], // Y-m-d
                    'Number' => $passenger['passport_no'],
                    'Type' => $passenger['passport_type'], // P
                ],
                'PersonName' => [
                    'GivenName' => $passenger['first_name'],
                    'Surname' => $passenger['surname'],
                    'DateOfBirth' => $passenger['dob'],
                    'Gender' => $passenger['gender'], // M
                    'NameNumber' => $passenger['nameNumber'],
                ],
                'SegmentNumber' => 'A', // Same for all
            ];

            // usually SecureFlight data requires if user visits to usa or travel over usa
            $this->body['CreatePassengerNameRecordRQ']['SpecialReqDetails']['SpecialService']['SpecialServiceInfo']['SecureFlight'][] = [
                'PersonName' => [
                    'DateOfBirth' => $passenger['dob'],
                    'Gender' => $passenger['gender'],
                    'NameNumber' => ($passenger['type'] == 'INF') ? $passenger['belongsToNameNumber'] : $passenger['nameNumber'],
                    'GivenName' => $passenger['first_name'],
                    'Surname' => $passenger['surname'],
                  ],
                  'SegmentNumber' => 'A',
                  'VendorPrefs' => [
                    'Airline' => [
                      'Hosted' => false,
                    ],
                  ],
            ];
        }


        // add services
        foreach ($this->form['passengers'] as $passenger) {
            if ($passenger['type'] == 'ADT') {
                continue;
            }

            // add for child
            if ($passenger['type'][0] == 'C') {
                $this->body['CreatePassengerNameRecordRQ']['SpecialReqDetails']['SpecialService']['SpecialServiceInfo']['Service'][] = [
                    'SSR_Code' => 'CHLD',
                    'Text' => 'CHLD/' . strtoupper(date('dMy', strtotime($passenger['dob']))) . '-' . $passenger['nameNumber'],
                    'PersonName' => [
                        'NameNumber' => $passenger['nameNumber'],
                    ],
                    'SegmentNumber' => 'A',
                ];
            }


            # add for infant
            # Infants are under two years old
            if ($passenger['type'][0] == 'I') { 
                $this->body['CreatePassengerNameRecordRQ']['SpecialReqDetails']['SpecialService']['SpecialServiceInfo']['Service'][] = [
                    'SSR_Code' => 'INFT',
                    'Text' => 'INFT/' . $passenger['surname'] . '/' . $passenger['first_name'] . '/' . strtoupper(date('dMy', strtotime($passenger['dob']))) . '-' . $passenger['belongsToNameNumber'],
                    'PersonName' => [
                        'NameNumber' => $passenger['belongsToNameNumber'],
                    ],
                    'SegmentNumber' => 'A',
                ];
            }
        }

    }
}
