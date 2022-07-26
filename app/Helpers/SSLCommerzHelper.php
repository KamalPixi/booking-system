<?php

namespace App\Helpers;
use App\Helpers\RefHelper;
use App\Enums\TransactionEnum;

class SSLCommerzHelper {
    public static function sslCommerzDS() {
        $post_data = array();
        $post_data['total_amount'] = '0'; # You cant not pay less than 10
        $post_data['currency'] = TransactionEnum::CURRENCY['BDT'];
        $post_data['tran_id'] = RefHelper::createDepositRef();

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = '';
        $post_data['cus_email'] = '';
        $post_data['cus_add1'] = '';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "";
        $post_data['cus_phone'] = '';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "";
        $post_data['ship_add1'] = "";
        $post_data['ship_add2'] = "";
        $post_data['ship_city'] = "";
        $post_data['ship_state'] = "";
        $post_data['ship_postcode'] = "";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Air Ticket";
        $post_data['product_category'] = "airline-tickets";
        $post_data['product_profile'] = "physical-goods";

        return $post_data;
    }
}
