<?php

namespace App\Http\Livewire\Agent\Flight;

use Livewire\Component;
use App\Models\AirBooking;
use App\Helpers\ConstantHelper;
use App\Services\SabreFlightGetBookingService;
use App\Services\SabreFlightBookingService;
use App\Events\AirTicketIssueReq;
use App\Enums\TransactionEnum;
use App\Enums\FlightEnum;
use App\Enums\RequestTypeEnum;
use Barryvdh\DomPDF\Facade\Pdf;

class FlightBookingShow extends Component {

    protected $flight_booking_id;
    public $airBooking;

    # belongs to modal
    public $isModalOpen = false;

    # belongs to get booking
    protected $getBookingAttempt = 0;
    public $isGetBookingSuccess = false;

    # make payment
    public $payment_amount = '';

    # belongs to print
    public $show_print = false;
    public $include_price = false;
    public $send_via_email = false;
    public $sending_email = '';

    # belongs to PrePurchasedAir
    public $flightJson;

    public function mount($flight_booking_id) {
        $this->flight_booking_id = $flight_booking_id;
        $this->airBooking = AirBooking::where('reference', $flight_booking_id)->first();
        if ($this->airBooking->source_api == ConstantHelper::PRE) {
            $this->flightJson = $this->airBooking->prePurchasedAirJson();
        }
    }

    public function updated($propertyName) {
        if ($propertyName == 'sending_email') {
            $this->send_via_email = !$this->send_via_email;
        }
    }

    
    public function issueTicket() {
        if (!auth()->user()->can('agent_issue booking')) {
            return session()->flash('failed', 'Unauthorized!');
        };

        # if already air ticket issued then, exit
        if ($this->airBooking->airTicket) {
            return session()->flash('failed', 'Ticket Issue request already exists.');
        }

        $agent = auth()->user()->agent;
        if (!$agent) {
            return session()->flash('failed', 'Agent not found!');
        }

        # calculate due
        $amount_paid = 0;
        $amount_left = 0;
        foreach ($this->airBooking->payments as $payment) {
            $amount_paid += $payment->amount;
        }
        $amount_left = $this->airBooking->amount - $amount_paid;

        # temporary
        $payment_amount = 0;
        $is_partial = false;

        if ($this->airBooking->bookingPartialRequest) {
            if ($this->airBooking->bookingPartialRequest->status == TransactionEnum::STATUS['APPROVED']) {
                if (!$this->airBooking->bookingPartialRequest->is_used) {
                    if ($amount_left > $this->airBooking->bookingPartialRequest->approved_amount) {
                        $is_partial = true;
                        $payment_amount = $this->airBooking->bookingPartialRequest->approved_amount;
                    }else {
                        $payment_amount = $amount_left;
                    }
                }
            }
        }

        if (!$is_partial) {
            $payment_amount = $amount_left;
        }

        if ($agent->account->balance < $payment_amount) {
            return session()->flash('failed', 'Insufficient account balance.');
        }

        # create ticket & payments
        try {
            \DB::beginTransaction();

            $status = $is_partial ? TransactionEnum::STATUS['PARTIAL_PAID'] : TransactionEnum::STATUS['PAID'];

            # create ticket
            $airTicket = $this->airBooking->airTicket()->create([
                'amount' => $this->airBooking->amount,
                'currency' => $this->airBooking->currency,
                'created_by' => auth()->user()->id,
                'agent_id' => $agent->id,
            ]);

            # update  booking payment status
            $this->airBooking->update([
                'payment_status' => $status
            ]);

            if ($is_partial) {
                $this->airBooking->bookingPartialRequest->update([
                    'is_used' => 1
                ]);
            }

            # do not create transaction, bcz fare amount paid in the past, now just issuing tha ticket
            if ($payment_amount < 1) {
                \DB::commit();
                return session()->flash('success', 'Ticket issue success.');
            }

            # handle full payment
            $payment = $this->airBooking->payments()->create([
                'agent_id' => $agent->id,
                'amount' => $payment_amount,
                'currency' => $this->airBooking->currency,
                'method' => TransactionEnum::METHOD['ACCOUNT_BALANCE'],
                'purpose' => TransactionEnum::PURPOSE['AIR_TICKET_ISSUE'],
                'status' => $status,
                'created_by' => auth()->user()->id,
            ]);

            # deduct agent account balance
            $account = $agent->account;
            $account->balance -= $payment_amount;
            $account->save();

            # add to transaction
            $payment->transactions()->create([
                'sign' => TransactionEnum::SIGN['MINUS'],
                'amount' => $payment_amount,
                'currency' => $payment->currency,
                'purpose' => TransactionEnum::PURPOSE['AIR_TICKET_ISSUE'],
                'method' => $payment->method,
                'remark' => $status,
                'initiated_by' => $payment->created_by,
            ]);

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return session()->flash('failed', 'Ticket issue request failed' . $e->getMessage());
        }

        # To update the balance component ui.
        $this->emit('ACCOUNT_BALANCE_UPDATED');
        return session()->flash('success', 'Ticket issue success.');
    }

    public function cancelBooking(SabreFlightBookingService $sabreService) {
        if ($this->airBooking->source_api == ConstantHelper::SABRE) {
            $response = $sabreService->cancelBooking($this->airBooking->confirmation_id);
            if ($response == null) {
                $this->airBooking->update([
                    'status' => FlightEnum::STATUS['CANCELED']
                ]);
                return session()->flash('failed', 'Booking not found on GDS!');
            }
            $this->airBooking->update([
                'status' => FlightEnum::STATUS['CANCELED']
            ]);
            $this->airBooking->jsons()->create([
                'type' => RequestTypeEnum::TYPE['RESPONSE'],
                'type_key' => FlightEnum::STATUS['CANCELED'],
                'json' => json_encode($response),
            ]);

            return session()->flash('success', 'Booking has been canceled.');
        }
    }

    public function makePartialPayment() {
        if (!auth()->user()->can('agent_create payment')) {
            return session()->flash('failed', 'Unauthorized!');
        };

        $this->validate([
            'payment_amount' => 'bail|required|numeric|min:1|digits_between:1,12'
        ]);

        $agent = auth()->user()->agent;
        if (!$agent) {
            return session()->flash('failed', 'Agent not found!');
        }

        if ($agent->account->balance < $this->payment_amount) {
            return session()->flash('failed', 'Insufficient account balance.');
        }

        $amount_paid = 0;
        foreach ($this->airBooking->payments as $payment) {
            $amount_paid += $payment->amount;
        }
        $amount_left = $this->airBooking->amount - $amount_paid;
        if ($this->payment_amount < $amount_left) {
            if (!$this->airBooking->bookingPartialRequest) {
                return session()->flash('failed', 'You have no partial payment request.');
            }

            if ($this->airBooking->bookingPartialRequest->status != TransactionEnum::STATUS['APPROVED']) {
                return session()->flash('failed', 'Your partial payment request is not approved.');
            }

            if ($this->payment_amount < $this->airBooking->bookingPartialRequest->approved_amount) {
                if ($this->payment_amount < $amount_left) {
                    return session()->flash('failed', 'You are paying less than due or approved amount!');
                }
            }
        }

        # more then due amount is not allowed
        if ($this->payment_amount > $amount_left) {
            return session()->flash('failed', 'You are paying more than due amount!');
        }

        // dd([
        //     $this->payment_amount,
        //     ($this->payment_amount < $amount_left) ? TransactionEnum::STATUS['PARTIAL_PAID'] : TransactionEnum::STATUS['PAID']
        // ]);

        try {
            \DB::beginTransaction();

            $payment = $this->airBooking->payments()->create([
                'agent_id' => $agent->id,
                'amount' => $this->payment_amount,
                'currency' => $this->airBooking->currency,
                'method' => TransactionEnum::METHOD['ACCOUNT_BALANCE'],
                'purpose' => TransactionEnum::PURPOSE['AIR_TICKET_ISSUE'],
                'status' => ($this->payment_amount < $amount_left) ? TransactionEnum::STATUS['PARTIAL_PAID'] : TransactionEnum::STATUS['PAID'],
                'created_by' => auth()->user()->id,
            ]);

            # deduct agent account balance
            $account = $agent->account;
            $account->balance -= $this->payment_amount;
            $account->save();

            # update  booking payment status
            $this->airBooking->update([
                'payment_status' => ($this->payment_amount < $amount_left) ? TransactionEnum::STATUS['PARTIAL_PAID'] : TransactionEnum::STATUS['PAID']
            ]);

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return session()->flash('failed', 'Payment failed, Please try again later');
        }

        $this->reset([
            'payment_amount'
        ]);

        $this->emit('ACCOUNT_BALANCE_UPDATED');
        return session()->flash('success', 'Payment success.');
    }


    public function partialPaymentRequest() {
        $this->dispatchBrowserEvent('livewireCustomEvent', [
            'action' => 'scrollToTop'
        ]);

        if ($this->airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PAID']) {
            return session()->flash('failed', 'Already paid!');
        }
        if ($this->airBooking->bookingPartialRequest) {
            return session()->flash('failed', 'Partial payment request already exists.');
        }
        
        $this->airBooking->bookingPartialRequest()->create([
            'requested_by' => auth()->user()->id
        ]);

        return session()->flash('success', 'Request has been made, You will be notified soon.');
    }


    public function refundRequest() {
        if (!auth()->user()->can('agent_create payment')) {
            return session()->flash('failed', 'Unauthorized!');
        };

        # previous payment is required
        if ($this->airBooking->payments->count() < 1) {
            return session()->flash('failed', 'There is no payment found for refunding!');
        }

        # only single refund request is allowed
        if ($this->airBooking->refund) {
            return session()->flash('failed', 'Refund request already sent!');
        }

        # create refund request
        $this->airBooking->refund()->create([
            'requested_by' => auth()->user()->id,
            'agent_id' => auth()->user()->agent->id,
        ]);
        return session()->flash('success', 'Refund request sent, You\'ll be notified soon.');
    }

    # fetch booking from GDS
    public function getBookingByPNR(SabreFlightGetBookingService $service) {
        $this->isGetBookingSuccess = false;

        # if booking not exists then fetch it.
        if (strlen($this->airBooking->get_booking_response_json) < 5) {
            $this->getBookingAttempt++;
            $res = $service->getBooking($this->airBooking->confirmation_id);
            
            if ($res == null) {
                sleep(2);
                # try 3 times to get booking, if fails
                if ($this->getBookingAttempt < 4) {
                    sleep(2);
                    $this->getBookingByPNR();
                }
                return session()->flash('failed', 'Booking not found on GDS!');
            }

            # if found, then store for later use
            $this->airBooking->update([
                'get_booking_response_json' => json_encode($service->responseJson)
            ]);

            $this->isGetBookingSuccess = true;
            // return session()->flash('success', 'fetched from GDS ');
        }

        // return session()->flash('success', 'Exists in DB [DEV]');
    }


    /**
     * Belongs to Print
     */
    public function togglePrint() {
        $this->show_print = !$this->show_print;
    }
    public function printTicket() {
        $booking = $this->airBooking;
        $agent = auth()->user()->agent;
        $include_price = $this->include_price;

        if ($this->airBooking->source_api == ConstantHelper::US_BANGLA) {
            $pdf = Pdf::loadView('agent.prints.flight-ticket-usbangla', compact('booking', 'agent', 'include_price'));
        }
        if ($this->airBooking->source_api == ConstantHelper::SABRE) {
            $pdf = Pdf::loadView('agent.prints.flight-ticket-sabre', compact('booking', 'agent', 'include_price'));
        }
        if ($this->airBooking->source_api == ConstantHelper::PRE) {
            $pdf = Pdf::loadView('agent.prints.flight-ticket-pre', compact('booking', 'agent', 'include_price'));
        }

        $data = $pdf->setOptions(['isRemoteEnabled' => true, 'defaultFont' => 'sans-serif'])->output();

        return response()->streamDownload(
            fn () => print($data),
            $this->airBooking->reference . '.pdf'
        );

        // dd($pdf->download($this->airBooking->reference . '.pdf'));
    }
    public function sendTicket() {
        
    }



    public function render() {
        if (!auth()->user()->can('agent_view booking')) {
            return view('agent.includes.unauthorized');
        };

        if (!$this->airBooking) {
            return view('livewire.error', ['message' => 'Flight not found!']);
        }

        # USBangla
        if ($this->airBooking['source_api'] == ConstantHelper::US_BANGLA) {
            return view('livewire.agent.flight.flight-booking-show-usbangla');
        }

        # Pre Purchased tickets view
        if ($this->airBooking->source_api == ConstantHelper::PRE) {
            return view('livewire.agent.flight.flight-booking-show-pre');
        }
        
        # sabre get booking
        return view('livewire.agent.flight.flight-booking-get-show-sabre');

        # sabre booking response during booking [Now No USE]
        return view('livewire.agent.flight.flight-booking-show-sabre');
    }
}
