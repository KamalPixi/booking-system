<?php

use App\Enums\RefHelper;
use App\Enums\CurrencyEnum;
use App\Library\SslCommerz\SslCommerzNotification;

class SSLCommerzService {
    
    private $form;
    public $body;
    private $agent;

    private $payment_options;

    public function init($form, $agent) {
        $this->form = $form;
        $this->agent = $agent;
    }

    public function makePayment(SslCommerzNotification $sslC) {
        if (!$this->form || !$this->agent) {
            throw new ErrorException('Please call init method first');
        }

        $this->body = $this->emptyDataStructure();

        $this->addAmountInfo();
        $this->addCustomerInfo();
        $this->addShippingInfo();

        $this->payment_options = $sslC->makePayment($body, 'hosted');
        # don't know why ssl used this TODO
        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }


    public function validatePayment($request, SslCommerzNotification $sslC) {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        return $sslC->orderValidate($request->all(), $tran_id, $amount, $currency);
    }


    private function addAmountInfo() {
        $body['total_amount'] = (string) $this->form['amount'];
        $body['currency'] = CurrencyEnum::BDT;
        $body['tran_id'] = RefHelper::createDepositRef();
    }
    private function addCustomerInfo() {
        $body['cus_name'] = $this->agent->full_name;
        $body['cus_email'] = $this->agent->email;
        $body['cus_add1'] = $this->agent->address;
        $body['cus_city'] = $this->agent->city;
        $body['cus_state'] = $this->agent->state;
        $body['cus_postcode'] = $this->agent->postcode;
        $body['cus_phone'] = $this->agent->phone;
    }
    private function addShippingInfo() {
        $body['ship_name'] = $this->agent->company;
        $body['ship_add1'] = $this->agent->address;
        $body['ship_add2'] = $this->agent->state;
        $body['ship_city'] = $this->agent->city;
        $body['ship_state'] = $this->agent->state;
        $body['ship_postcode'] = $this->agent->postcode;
        $body['ship_phone'] = $this->agent->phone;
        $body['ship_country'] = "Bangladesh";
    }

    private function emptyDataStructure() {
        $post_data = array();
        $post_data['total_amount'] = '0'; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = '';

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
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        return $post_data;
    }
}
