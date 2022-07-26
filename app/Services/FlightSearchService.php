<?php

namespace App\Services;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use App\Services\SabreFlightSearchService;
use App\Helpers\TokenHelper;
use App\Helpers\ConstantHelper;
use App\Enums\SettingEnum;
use App\Enums\TransactionEnum;
use App\Enums\AdminFeeEnum;
use App\Enums\FlightEnum;
use App\Models\AdminFee;

class FlightSearchService {

    private $responses;
    private $flights = [
        'itineraries' => [],
        'legDescriptions' => []
    ];

    # services
    private $sabre;
    private $USBangla;

    public function __construct(SabreFlightSearchService $sabre, USBanglaFlightSearchService $USBangla) {
        $this->sabre = $sabre;
        $this->USBangla = $USBangla;
    }

    public function fetchOneWayFlights($form) {
        # prepare sabre flight info
        $this->sabre->form = $form;
        $this->sabre->prepareOneWay();

        # prepare USBangla flight info
        $this->USBangla->form = $form;
        $this->USBangla->prepareOneWay();

        # make request
        $this->responses = Http::pool(function (Pool $pool) {
            return [
                # Sabre
                $pool->withHeaders(TokenHelper::getSabreHeader())
                ->post(ConstantHelper::SABRE_FLIGHT_SEARCH_API, $this->sabre->body),

                # USBangla
                $pool->post(ConstantHelper::US_BANGLA_FLIGHT_SEARCH_API, $this->USBangla->body)
            ];
        });

        # set responses to respective service
        $this->sabre->response = $this->responses[0];
        $this->USBangla->response = $this->responses[1];

        # format flights
        $this->addSabreFlights();
        $this->addUSBanglaFlights();
        $this->addPricing();
        $this->addAdminFee();
        $this->addAgentMargin();
        $this->addTripType(FlightEnum::TYPE[0]);
        return $this->flights;
    }

    public function fetchRoundTripFlights($form) {
        # prepare sabre flight info
        $this->sabre->form = $form;
        $this->sabre->prepareRoundTrip();

        # prepare USBangla flight info
        $this->USBangla->form = $form;
        $this->USBangla->prepareRoundTrip();

        # make request
        $this->responses = Http::pool(function (Pool $pool) {
            return [
                # Sabre
                $pool->withHeaders(TokenHelper::getSabreHeader())
                ->post(ConstantHelper::SABRE_FLIGHT_SEARCH_API, $this->sabre->body),

                # USBangla
                $pool->post(ConstantHelper::US_BANGLA_FLIGHT_SEARCH_API, $this->USBangla->body)
            ];
        });

        # set responses to respective service
        $this->sabre->response = $this->responses[0];
        $this->USBangla->response = $this->responses[1];

        # format flights
        $this->addSabreFlights();
        $this->addUSBanglaFlights();
        $this->addPricing();
        $this->addAdminFee();
        $this->addAgentMargin();
        $this->addTripType(FlightEnum::TYPE[1]);
        return $this->flights;
    }

    public function fetchMultiCityFlights($form) {
        # prepare sabre flight info
        $this->sabre->form = $form;
        $this->sabre->prepareMultiCity();


        # prepare USBangla flight info
        $this->USBangla->form = $form;
        $this->USBangla->prepareMultiCity();

        # make request
        $this->responses = Http::pool(function (Pool $pool) {
            return [
                # Sabre
                $pool->withHeaders(TokenHelper::getSabreHeader())
                ->post(ConstantHelper::SABRE_FLIGHT_SEARCH_API, $this->sabre->body),

                # USBangla
                $pool->post(ConstantHelper::US_BANGLA_FLIGHT_SEARCH_API, $this->USBangla->body)
            ];
        });

        # set responses to respective service
        $this->sabre->response = $this->responses[0];
        $this->USBangla->response = $this->responses[1];

        # format flights
        $this->addSabreFlights();
        $this->addUSBanglaFlights();
        $this->addPricing();
        $this->addAdminFee();
        $this->addAgentMargin();
        $this->addTripType(FlightEnum::TYPE[2]);
        return $this->flights;
    }


    private function addSabreFlights() {
        if (!$this->sabre->response->ok()) { return; }
        $this->sabre->format($this->responses[0]->object());
        foreach ($this->sabre->flights['itineraries'] as $itinerary) {
            $this->flights['itineraries'][] = $itinerary;
        }
        $this->flights['legDescriptions'] = $this->sabre->flights['legDescriptions'];
    }

    private function addUSBanglaFlights() {
        if (!$this->USBangla->response->ok()) { return; }
        if (!$this->USBangla->hasFlights()) { return; }
        
        $this->USBangla->organize();
        $this->USBangla->filterBasedOnClassType();
        $this->USBangla->format();
        $this->USBangla->addOfferData();
        
        if (!isset($this->USBangla->flights['itineraries'])) { return; }
        foreach ($this->USBangla->flights['itineraries'] as $itinerary) {
            $this->flights['itineraries'][] = $itinerary;
        }
        $this->flights['legDescriptions'] = $this->USBangla->flights['legDescriptions'];
        $this->flights[ConstantHelper::US_BANGLA] = $this->USBangla->flights[ConstantHelper::US_BANGLA];
    }

    private function addTripType($type) {
        foreach ($this->flights['itineraries'] as &$itinerary) {
            $itinerary['tripType'] = $type;
        }
    }

    # adds margin amount & calculates total amount for ease use on blade
    private function addPricing() {
        foreach ($this->flights['itineraries'] as &$itinerary) {
            # sum to total
            /*
            $currency = '';
            $totalPrice = 0;
            $totalTaxAmount = 0;
            $totalBaseFareAmount = 0;

            foreach ($itinerary['passengerInfoList'] as $pInfo) {
                $totalPrice += $pInfo['totalFare']['totalPrice'];
                $totalTaxAmount += $pInfo['totalFare']['totalTaxAmount'];
                $totalBaseFareAmount += $pInfo['totalFare']['baseFareAmount'];
                $currency = $pInfo['totalFare']['currency'];
            }
            */

            $itinerary['pricingInfo'] = [
                'totalPrice' => $itinerary['totalFare']['totalPrice'], # price for agent's customer
                'currency' => $itinerary['totalFare']['currency'],
                'totalTaxAmount' => $itinerary['totalFare']['totalTaxAmount'],
                'totalBaseFareAmount' => $itinerary['totalFare']['totalBaseFare'],
                'totalAgentPrice' => 0, # price for agent
                'totalAgentPriceWithMargin' => 0, # total fare with agent margin.
                'marginAmount' => 0,
                'marginType' => '',
            ];
        }
    }


    public function addAdminFee() {
        # get admin fees
        $adminFees = AdminFee::all();
        $ait = AdminFee::where([
            'fee_key' => AdminFeeEnum::KEY[0],
            'fee_key_sub' => null,
        ])->first();
        $commission = AdminFee::where([
            'fee_key' => AdminFeeEnum::KEY[1],
            'fee_key_sub' => null,
        ])->first();

        # AIT will be used as default
        $ait_fee = $ait->fee ?? 0;
        $ait_fee_type = $ait->type ?? TransactionEnum::METHOD_FEE_TYPE['FIXED'];

        foreach ($this->flights['itineraries'] as &$itinerary) {
            $airline_code = $itinerary['legs'][0]['schedules'][0]['carrier']['marketing'] ?? '';
            $total_price = $itinerary['pricingInfo']['totalPrice'];
            $total_base_fare_amount = $itinerary['pricingInfo']['totalBaseFareAmount'];
            $total_agent_price = 0;

            # these will be used as default
            $commission_fee = $commission->fee ?? 0;
            $commission_fee_type = $commission->type ?? TransactionEnum::METHOD_FEE_TYPE['FIXED'];

            # get airline based fee
            $commissionModel = $adminFees->filter(function($fee) use($airline_code) {
                if ($fee->fee_key == AdminFeeEnum::KEY[1] && $fee->fee_key_sub === $airline_code) {
                    return true;
                }
                return false;
            })->first();

            # if airline based fee model not found then will be used default airline commission fee.
            if (isset($commissionModel->fee)) {
                $commission_fee = $commissionModel->fee;
                $commission_fee_type = $commissionModel->type;
            }

            # calculate agent price
            $commission_amount = $this->fixedPercentCal($commission_fee_type, $commission_fee, $total_base_fare_amount);
            $ait_amount = $this->fixedPercentCal($ait_fee_type, $ait_fee, $total_price);
            $total_agent_price = ($total_price + $ait_amount) - $commission_amount;
            $itinerary['pricingInfo']['totalAgentPrice'] = ceil($total_agent_price);

            $itinerary['pricingInfo']['commission_amount'] = $commission_amount;
            $itinerary['pricingInfo']['ait_amount'] = $ait_amount;
        }
    }

    public function addAgentMargin() {
        # get profit margin from db of this agent
        $airBookingMargin = null;
        foreach (auth()->user()->agent->profitMargins ?? [] as $profitMargin) {
            if ($profitMargin->key == SettingEnum::PROFIT_MARGIN_KEY[0]) {
                $airBookingMargin = $profitMargin;
                break;
            }
        }

        # airBookingMarginModel is required
        if (!$airBookingMargin) {
            return;
        }

        foreach ($this->flights['itineraries'] as &$itinerary) {
            # calculate profit margin & add with total price
            $totalPriceWithMargin = $this->fixedPercentCal($airBookingMargin->type, $airBookingMargin->amount, $itinerary['pricingInfo']['totalAgentPrice']);
            $itinerary['pricingInfo']['totalAgentPriceWithMargin'] = $itinerary['pricingInfo']['totalAgentPrice'] + $totalPriceWithMargin; # total fare with agent margin.
            $itinerary['pricingInfo']['marginAmount'] = $airBookingMargin->amount;
            $itinerary['pricingInfo']['marginType'] = $airBookingMargin->type;
        }
    }


    private function fixedPercentCal($type, $fee, $amount) {
        if ($type == TransactionEnum::METHOD_FEE_TYPE['FIXED']) {
            return $fee;
        }
        return $amount / 100 * floatval($fee);
    }

}
