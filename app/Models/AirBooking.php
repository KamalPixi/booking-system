<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Helpers\ConstantHelper;

class AirBooking extends Model
{
    use HasFactory;

    private $getBookingResponseJson;
    private $lastEndIndex = 0;

    protected $fillable = [
        'confirmation_id',
        'airline_confirmation_id',
        'reference',
        'trip_type',
        'request_json',
        'response_json',
        'get_booking_response_json',
        'flight_json',
        'currency',
        'amount',
        'amount_with_margin',
        'ticketing_last_datetime',
        'source_api',
        'status',
        'payment_status',
        'created_by',
    ];

    public function airBookingable() {
        return $this->morphTo();
    }

    public function airTicket() {
        return $this->hasOne(AirTicket::class, 'air_booking_id');
    }

    public function invoices() {
        return $this->morphMany(Invoice::class, 'invoiceable', 'invoiceable_type', 'invoiceable_id');
    }

    public function payments() {
        return $this->morphMany(Payment::class, 'paymentable', 'paymentable_type', 'paymentable_id');
    }

    public function airBookingPassengers() {
        return $this->hasMany(AirBookingPassenger::class, 'air_booking_id');
    }
    
    public function jsons() {
        return $this->morphMany(Json::class, 'jsonable', 'jsonable_type', 'jsonable_id');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function agent() {
        return $this->createdBy->agent;
    }

    public function bookingPartialRequest() {
        return $this->morphOne(BookingPartialRequest::class, 'bookingRequestable', 'booking_requestable_type', 'booking_requestable_id');
    }

    public function refund() {
        return $this->morphOne(Refund::class, 'refundable', 'refundable_type', 'refundable_id');
    }

    # for sabre get booking response
    public function sabreGetBookingResJsonDecode() {
        if ($this->getBookingResponseJson) {
            return $this->getBookingResponseJson;
        }
        
        try {
            $this->getBookingResponseJson = json_decode($this->get_booking_response_json, true);
        } catch (\ErrorException $th) {
            dd($this->get_booking_response_json);
        }
        return $this->getBookingResponseJson;
    }
    # for sabre get booking response
    public function sabreSegmentsBasedOnJourney($journey) {
        $segments = [];
        $responseJson = $this->sabreGetBookingResJsonDecode();

        # get departure flights
        for ($i=0; $i < $journey['numberOfFlights']; $i++) { 
            $segments[] = $responseJson['flights'][$this->lastEndIndex + $i];
        }
        
        $this->lastEndIndex = $journey['numberOfFlights'];

        # need reset, bcz others will use this instance again
        $this->lastEndIndex = 0;
        return $segments;
    }


    # booking response during booking
    public function responseJsonDecode() {
        return json_decode($this->response_json, true);
    }
    # booking response during booking
    public function requestJsonDecode() {
        return json_decode($this->request_json, true);
    }

    # decodes json response that stored after successful booking
    public function getBookingResponseJson() {
        return json_decode($this->get_booking_response_json, true);
    }
    
    # for sabre booking response during booking
    public function getSabreFlightSegmentByIndex($index) {
        $json = $this->responseJsonDecode();
        return $json['CreatePassengerNameRecordRS']['TravelItineraryRead']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'][$index]['FlightSegment'][0];
    }

    # for sabre booking response during booking
    public function getSabreFlightSegments() {
        $json = $this->responseJsonDecode();
        $segments = $json['CreatePassengerNameRecordRS']['AirBook']['OriginDestinationOption']['FlightSegment'];

        # format datetime
        foreach ($segments as $k => &$segment) {
            $segmentInfo = $this->getSabreFlightSegmentByIndex($k);
            $year = date('Y', strtotime($segmentInfo['DepartureDateTime']));

            $departTime = new Carbon($year . '-' . $segment['DepartureDateTime']);
            $arrivalTime = new Carbon($year . '-' . $segment['ArrivalDateTime']);

            $segment['DepartureDate'] = $departTime->format('Y-m-d');
            $segment['DepartureTime'] = $departTime->format('H:i');
            $segment['ArrivalDate'] = $arrivalTime->format('Y-m-d');
            $segment['ArrivalTime'] = $arrivalTime->format('H:i');
        }

        return $segments;
    }

    # for sabre booking response during booking
    public function airline() {
        $json = json_decode($this->response_json, true);

        if ($this->source_api == ConstantHelper::US_BANGLA) {
            return $json['Booking']['Segments'][0]['AirlineDesignator'];
        }

        if ($this->source_api == ConstantHelper::SABRE) {
            return $json['CreatePassengerNameRecordRS']['AirBook']['OriginDestinationOption']['FlightSegment'][0]['MarketingAirline']['Code'];
        }
        return 'Unknown';
    }

    public function origin() {
        $json = $this->responseJsonDecode();
        if ($this->source_api == ConstantHelper::US_BANGLA) {
            return $json['Booking']['Segments'][0];
        }

        if ($this->source_api == ConstantHelper::SABRE) {
            return $json['CreatePassengerNameRecordRS']['AirBook']['OriginDestinationOption']['FlightSegment'][0];
        }
    }

    public function destination() {
        $json = $this->responseJsonDecode();
        if ($this->source_api == ConstantHelper::US_BANGLA) {
            $count = count($json['Booking']['Segments'][0] ?? 0);
            return $json['Booking']['Segments'][$count - 1];
        }

        if ($this->source_api == ConstantHelper::SABRE) {
            $count = count($json['CreatePassengerNameRecordRS']['AirBook']['OriginDestinationOption']['FlightSegment'] ?? 0);
            return $json['CreatePassengerNameRecordRS']['AirBook']['OriginDestinationOption']['FlightSegment'][$count - 1];
        }
    }

    # flight origin destination & airline text in a single line
    public function oriDestAirText() {
        $origin = $this->origin();
        $destination = $this->destination();

        $origin_code = '';
        $destination_code = '';
        $airline_code = '';
        $airline_boeing = '';
        $text = '';
        if ($this->source_api == ConstantHelper::US_BANGLA) {
            $origin_code = $origin['OriginCode'];
            $destination_code = $destination['DestinationCode'];
            $airline_code = $origin['AirlineDesignator'];
            $airline_boeing = $origin['FlightInfo']['EquipmentText'];
        }

        if ($this->source_api == ConstantHelper::SABRE) {
            $origin_code = $origin['OriginLocation']['LocationCode'];
            $destination_code = $destination['DestinationLocation']['LocationCode'];
            $airline_code = $origin['MarketingAirline']['Code'];
            $airline_boeing = $origin['MarketingAirline']['FlightNumber'];
        }

        $text = $origin_code . ' to ' .  $destination_code . ' [' . $airline_code . ' ' . $airline_boeing .']';
        return $text;
    }



    /**
     * These are new methods for sabre bookings.
     * Journey->Flight->Segments
     * 
     * Note: Flights & segments array length are equal, means 2 flights has 2 segments!
     * note here in the future, if found they are not equal.
     */
    public function getJourneysSabre() {
        return $this->getBookingResponseJson()['journeys'] ?? null;
    }

    public $lastIndex = 0;
    public function getJourneyFlightsSabre($journeyIndex) {
        $journeys = $this->getJourneysSabre();
        $flights = $this->getBookingResponseJson()['flights'];
        
        $journey = $journeys[$journeyIndex];
        $num_of_flights = $journey['numberOfFlights'];

        $flightsNew = array_slice($flights, $this->lastIndex, $num_of_flights);
        $this->lastIndex = $num_of_flights;

        return $flightsNew;
    }

    public function getFlightSegmentsSabre($flightId) {
        $segments = collect($this->getBookingResponseJson()['allSegments'] ?? []);
        return $segments->filter(function ($segment) use($flightId) {
            if ($segment['id'] == $flightId) {
                return true;
            }
            return false;
        });
    }


    public function totalBaseFareSabre() {
        $json = $this->getBookingResponseJson();
        return number_format($json['fares'][0]['totals']['subtotal'], 2);
    }

    public function totalTaxSabre() {
        $json = $this->getBookingResponseJson();
        return number_format($json['fares'][0]['totals']['taxes'], 2);
    }

    public function totalFareSabre() {
        $json = $this->getBookingResponseJson();
        return number_format($json['fares'][0]['totals']['total'], 2);
    }


    public function totalBaseFareUSBangla() {
        $json = $this->responseJsonDecode();
        return number_format($json['Booking']['FareInfo']['SaleCurrencyAmountTotal']['BaseAmount'], 2);
    }

    public function totalTaxUSBangla() {
        $json = $this->responseJsonDecode();
        return number_format($json['Booking']['FareInfo']['SaleCurrencyAmountTotal']['TaxAmount'], 2);
    }

    public function totalFareUSBangla() {
        $json = $this->responseJsonDecode();
        return number_format($json['Booking']['FareInfo']['SaleCurrencyAmountTotal']['TotalAmount'], 2);
    }



    /**
     * Belongs to PrePurchaseTicket
     */
    public function prePurchasedAirJson() {
        return json_decode($this->flight_json, true);
    }

}
