<?php

namespace App\Helpers;

/**
 * Constants Manager
 */

abstract class ConstantHelper {
    // sabre
    const SABRE = 'SABRE';
    const SABRE_PCC = ''; // Pseudo city code

    # Dev
    const SABRE_API_URL = 'https://api-crt.cert.havail.sabre.com';

    # Prod
    // const SABRE_API_URL = 'https://api.platform.sabre.com';
    const SABRE_FLIGHT_SEARCH_API = self::SABRE_API_URL . '/v4/offers/shop';
    const SABRE_FLIGHT_BOOKING_API = self::SABRE_API_URL . '/v2.4.0/passenger/records?mode=create';
    const SABRE_FLIGHT_BOOKING_CANCEL_API = self::SABRE_API_URL . '/v1/trip/orders/cancelBooking';
    const SABRE_FLIGHT_REVALIDATE_API = self::SABRE_API_URL . '/v4/shop/flights/revalidate';
    const SABRE_FLIGHT_GET_BOOKING_API = self::SABRE_API_URL . '/v1/trip/orders/getBooking';
    
    // USBangla
    const US_BANGLA = 'US_BANGLA';

    # Dev
    const US_BANGLA_API_URL = 'https://tstws2.ttinteractive.com/Zenith/TTI.PublicApi.Services/JsonSaleEngineService.svc';
    
    # Prod
    // const US_BANGLA_API_URL = 'https://wsapi-asia.ttinteractive.com/Zenith/TTI.PublicApi.Services/JsonSaleEngineService.svc';
    const US_BANGLA_FLIGHT_SEARCH_API = self::US_BANGLA_API_URL . '/SearchFlights?DateFormatHandling=IsoDateFormat';
    const US_BANGLA_FLIGHT_BOOKING_API = self::US_BANGLA_API_URL . '/CreateBooking?DateFormatHandling=IsoDateFormat';
    const US_BANGLA_FLIGHT_PREPARE_API = self::US_BANGLA_API_URL . '/PrepareFlights?DateFormatHandling=IsoDateFormat';
    
    # Pre-purchased 
    const PRE = 'PRE';

    // Agent details
    const AGENT = [
        'name' => '',
        'city' => '',
        'postal_code' => '',
        'country_code' => '',
        'address' => '67 Nayapaltan, City Heart, 13th Floor',
    ];


    /**
     * CreateBooking
     * CreateTicket
     * LoadBooking
     * PrepareFlights
     */

    const REF_PREFIX = 'DT';
}
