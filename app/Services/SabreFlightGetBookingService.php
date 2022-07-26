<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Helpers\TokenHelper;
use App\Helpers\ConstantHelper;

class SabreFlightGetBookingService {

    public $response;
    public $responseJson;
    public $body;

    public function getBooking($bookingPNR) {

        $this->body = [
            'confirmationId' => $bookingPNR
        ];
        
        try {
            $this->response = Http::withHeaders(TokenHelper::getSabreHeader())
            ->post(ConstantHelper::SABRE_FLIGHT_GET_BOOKING_API, $this->body);        
            if (!$this->response->ok()) {
                return null;
            }

            $this->responseJson = $this->response->json();
            if (isset($this->responseJson['startDate'])) {
                return $this->responseJson; 
            }

            return null;            
        } catch (\ErrorException $th) {
            return null;
        }
    }
}
