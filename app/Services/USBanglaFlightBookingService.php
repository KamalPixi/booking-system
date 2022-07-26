<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Helpers\USBanglaFlightHelper;
use App\Helpers\TokenHelper;
use App\Helpers\ConstantHelper;

class USBanglaFlightBookingService {
    public $response;
    public $itinerary;
    public $form;
    public $body;

    public function book($itinerary, $form) {
        $this->itinerary = $itinerary;
        $this->form = $form;

        # store empty request body
        $this->body = USBanglaFlightHelper::flightBookingDataStructure();

        # add form inputs to body
        $this->addOffer();
        $this->addSpecialServices();
        $this->addPassengers();
        try {
            $this->response = Http::post(ConstantHelper::US_BANGLA_FLIGHT_BOOKING_API, $this->body);
        } catch (\ErrorException $ex) {
            return null;
        }

        if (!$this->response->ok()) {
            return null;
        }

        $responseJson = $this->response->json();
        if ($responseJson['ResponseInfo']['Error'] != null) {
            return null;
        }

        return [
            $this->body,
            $responseJson
        ];
    }


    private function addOffer() {
        if ($this->itinerary[ConstantHelper::US_BANGLA]['offer']) {
            $this->body['request']['Offer'] = [
                'RefItinerary' => $this->itinerary['passengerInfoList'][0][ConstantHelper::US_BANGLA]['RefItinerary'],
                'Ref' => $this->itinerary[ConstantHelper::US_BANGLA]['offer']['Ref']
            ];
        }
    }


    private function addSpecialServices() {

        foreach ($this->itinerary['passengerInfoList'] as $k => $pInfo) {
            # ADT
            if($pInfo['passengerType'] == 'ADT') {
                $this->body['request']['SpecialServices'][] = [
                    'Data' => [
                      'Adof' => [
                        'DateOfBirth' => $this->form['passengers'][$k]['dob'],
                      ],
                    ],
                    'RefPassenger' => 'Traveler_Type_1_Index_' . $k,
                    'Code' => 'EXT-ADOB',
                ];
                $this->body['request']['SpecialServices'][] = [
                    'Text' => $this->form['passengers'][0]['phone_no'],
                    'RefPassenger' => 'Traveler_Type_1_Index_' . $k,
                    'Code' => 'CTCM',
                ];
                $this->body['request']['SpecialServices'][] = [
                    'Data' => [
                        'Docs' => [
                            'Documents' => [
                                [
                                    'IssueCountryCode' => $this->form['passengers'][$k]['passport_issuing_country'],
                                    'NationalityCountryCode' => $this->form['passengers'][$k]['nationality_country'],
                                    'DateOfBirth' => $this->form['passengers'][$k]['dob'],
                                    'Gender' => $this->form['passengers'][$k]['gender'],
                                    'DocumentExpiryDate' => $this->form['passengers'][$k]['passport_expiry_date'],
                                    'DocumentIssuanceDate' => $this->form['passengers'][$k]['passport_issuance_date'],
                                    'Firstname' => $this->form['passengers'][$k]['first_name'],
                                    'Surname' => $this->form['passengers'][$k]['surname'],
                                    'DocumentTypeCode' => $this->form['passengers'][$k]['passport_type'].$this->form['passengers'][$k]['passport_type'],
                                    'DocumentNumber' => $this->form['passengers'][$k]['passport_no'],
                                ],
                            ],
                        ],
                    ],
                    'RefPassenger' => 'Traveler_Type_1_Index_' . $k,
                    'Code' => 'DOCS',
                ];
            }
            
            # CHD
            if($pInfo['passengerType'] == 'CHD') {
                $this->body['request']['SpecialServices'][] = [
                    'Data' => [
                      'Chld' => [
                        'DateOfBirth' => $this->form['passengers'][$k]['dob'],
                      ],
                    ],
                    'RefPassenger' => 'Traveler_Type_2_Index_' . $k,
                    'Code' => 'CHLD',
                ];
                $this->body['request']['SpecialServices'][] = [
                    'Data' => [
                        'Docs' => [
                            'Documents' => [
                                [
                                    'IssueCountryCode' => $this->form['passengers'][$k]['passport_issuing_country'],
                                    'NationalityCountryCode' => $this->form['passengers'][$k]['nationality_country'],
                                    'DateOfBirth' => $this->form['passengers'][$k]['dob'],
                                    'Gender' => $this->form['passengers'][$k]['gender'],
                                    'DocumentExpiryDate' => $this->form['passengers'][$k]['passport_expiry_date'],
                                    'DocumentIssuanceDate' => $this->form['passengers'][$k]['passport_issuance_date'],
                                    'Firstname' => $this->form['passengers'][$k]['first_name'],
                                    'Surname' => $this->form['passengers'][$k]['surname'],
                                    'DocumentTypeCode' => $this->form['passengers'][$k]['passport_type'].$this->form['passengers'][$k]['passport_type'],
                                    'DocumentNumber' => $this->form['passengers'][$k]['passport_no'],
                                ],
                            ],
                        ],
                    ],
                    'RefPassenger' => 'Traveler_Type_2_Index_' . $k,
                    'Code' => 'DOCS',
                ];
            }
            
            # INF
            if($pInfo['passengerType'] == 'INF') {
                $this->body['request']['SpecialServices'][] = [
                    'Data' => [
                      'Inft' => [
                        'DateOfBirth' => $this->form['passengers'][$k]['dob'],
                      ],
                    ],
                    'RefPassenger' => 'Traveler_Type_3_Index_' . $k,
                    'Code' => 'INFT',
                ];
                $this->body['request']['SpecialServices'][] = [
                    'Data' => [
                        'Docs' => [
                            'Documents' => [
                                [
                                    'IssueCountryCode' => $this->form['passengers'][$k]['passport_issuing_country'],
                                    'NationalityCountryCode' => $this->form['passengers'][$k]['nationality_country'],
                                    'DateOfBirth' => $this->form['passengers'][$k]['dob'],
                                    'Gender' => $this->form['passengers'][$k]['gender'],
                                    'DocumentExpiryDate' => $this->form['passengers'][$k]['passport_expiry_date'],
                                    'DocumentIssuanceDate' => $this->form['passengers'][$k]['passport_issuance_date'],
                                    'Firstname' => $this->form['passengers'][$k]['first_name'],
                                    'Surname' => $this->form['passengers'][$k]['surname'],
                                    'DocumentTypeCode' => $this->form['passengers'][$k]['passport_type'].$this->form['passengers'][$k]['passport_type'],
                                    'DocumentNumber' => $this->form['passengers'][$k]['passport_no'],
                                ],
                            ],
                        ],
                    ],
                    'RefPassenger' => 'Traveler_Type_3_Index_' . $k,
                    'Code' => 'DOCS',
                ];
            }
        }
    }


    private function addPassengers() {

        foreach ($this->itinerary['passengerInfoList'] as $k => $pInfo) {
            // AD
            if ($pInfo['passengerType'] == 'ADT') {
                $this->body['request']['Passengers'][] = [
                    'Ref' => 'Traveler_Type_1_Index_' . $k,
                    'RefClient' => $pInfo[ConstantHelper::US_BANGLA]['RefPassenger'],
                    'PassengerQuantity' => 1,
                    'PassengerTypeCode' => 'AD',
                    'NameElement' => [
                      'CivilityCode' => $this->form['passengers'][$k]['title'],
                      'Firstname' => $this->form['passengers'][$k]['first_name'],
                      'Surname' => $this->form['passengers'][$k]['surname'],
                    ],
                    'Extensions' => [],
                ];
            }

            // CHD 
            if ($pInfo['passengerType'] == 'CHD') {
                $this->body['request']['Passengers'][] = [
                    'Ref' => 'Traveler_Type_2_Index_' . $k,
                    'RefClient' => $pInfo[ConstantHelper::US_BANGLA]['RefPassenger'],
                    'PassengerQuantity' => 1,
                    'PassengerTypeCode' => 'CHD',
                    'NameElement' => [
                      'CivilityCode' => $this->form['passengers'][$k]['title'],
                      'Firstname' => $this->form['passengers'][$k]['first_name'],
                      'Surname' => $this->form['passengers'][$k]['surname'],
                    ],
                    'Extensions' => [],
                ];
            }

            // INF
            if ($pInfo['passengerType'] == 'INF') {
                $this->body['request']['Passengers'][] = [
                    'Ref' => 'Traveler_Type_3_Index_' . $k,
                    'RefClient' => $pInfo[ConstantHelper::US_BANGLA]['RefPassenger'],
                    'PassengerQuantity' => 1,
                    'PassengerTypeCode' => 'INF',
                    'NameElement' => [
                      'CivilityCode' => $this->form['passengers'][$k]['title'],
                      'Firstname' => $this->form['passengers'][$k]['first_name'],
                      'Surname' => $this->form['passengers'][$k]['surname'],
                    ],
                    'Extensions' => [],
                ];
            }
        }
    }
}

