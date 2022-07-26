<?php

namespace App\Http\Livewire\Admin\Booking;

use Livewire\Component;
use App\Models\AirBooking;
use App\Services\SabreFlightGetBookingService;
use App\Enums\FlightEnum;
use App\Enums\TransactionEnum;
use App\Enums\UserEnum;
use App\Helpers\ConstantHelper;

class BookingFlight extends Component {
    public $flight_booking_id;

    # belongs to get booking
    protected $getBookingAttempt = 0;
    public $isGetBookingSuccess = false;
    public $airBooking;

    # belongs to partial request handle
    public $showPartialForm = false;
    public $status;
    public $approved_amount;
    public $due_time;

    # belongs to refund 
    public $showRefundForm = false;
    public $refund_status = TransactionEnum::STATUS['PENDING'];
    public $refund_amount = '';
    public $refund_fee = '';
    public $refund_method = '';
    public $admin_remark = '';

    # For pre purchased tickets
    public $prePurchasedFlight;

    public function mount($booking_id) {
        $this->flight_booking_id = $booking_id;
        $this->setBooking();

        # we do this to prevent many calls to db in view
        if ($this->airBooking->source_api == ConstantHelper::PRE) {
            $this->prePurchasedFlight = $this->airBooking->prePurchasedAirJson();
        }
    }

    public function setBooking() {
        $this->airBooking = AirBooking::where('reference', $this->flight_booking_id)->first();
    }


    # pretrial request handle
    public function showPartialRequestModel() {
        if (!$this->airBooking->bookingPartialRequest) {
            return session()->flash('failed', 'Partial request not found!');
        }
        $this->showPartialForm = true;
    }
    public function hidePartialForm() {
        $this->showPartialForm = false;
    }
    public function handlePartialRequest() {
        $statusArr = [
            TransactionEnum::STATUS['APPROVED'],
            TransactionEnum::STATUS['REJECTED']
        ];

        $form = $this->validate([
            'status' => 'required|in:' . implode(',', $statusArr),
            'approved_amount' => 'bail|required|numeric|digits_between:1,12',
            'due_time' => 'nullable|date',
        ]);

        if (!$form['due_time']) {
            $form['due_time'] = NULL;
        }

        if (!$this->airBooking->bookingPartialRequest) {
            return session()->flash('failed', 'Partial request not found!');
        }

        $this->airBooking->bookingPartialRequest->update($form);
        $this->showPartialForm = false;
        return session()->flash('success', 'Partial request handle success');
    }


    # refund form
    public function showRefundForm() {
        if (!$this->airBooking->refund) {
            return session()->flash('failed', 'Refund request not found!');
        }

        $this->showRefundForm = true;
    }
    public function hideRefundForm() {
        $this->showRefundForm = false;
    }
    public function handleRefund() {
        $allowed_status = [
            TransactionEnum::STATUS['COMPLETED'],
            TransactionEnum::STATUS['CANCELED'],
            TransactionEnum::STATUS['PENDING']
        ];
        $form = $this->validate([
            'refund_status' => 'required|in:' . implode(',', $allowed_status),
            'refund_method' => 'nullable|in:' . implode(',', array_values(TransactionEnum::METHOD)),
            'refund_amount' => 'bail|nullable|numeric|digits_between:1,12',
            'refund_fee' => 'bail|nullable|numeric|digits_between:1,12',
            'admin_remark' => 'nullable|max:255',
        ]);
        if (!$this->airBooking->refund) {
            return session()->flash('failed', 'Refund request not found!');
        }

        $account = null;
        # try to get agent account
        if (isset($this->airBooking->createdBy->agent->account)) {
            $account = $this->airBooking->createdBy->agent->account;
        }else {
            # try to get customer account
            if ($this->airBooking->createdBy->type != UserEnum::TYPE['CUSTOMER']) {
                return session()->flash('failed', 'Account not found!');
            }
            $account = $this->airBooking->createdBy->account;
        }

        if ($form['refund_status'] == TransactionEnum::STATUS['COMPLETED']) {
            $form['refunded_by'] = auth()->user()->id;
            $form['refunded_at'] = now();

            # mark booking as refunded
            $this->airBooking->update([
                'payment_status' => TransactionEnum::STATUS['REFUNDED']
            ]);
            

            # create a transaction
            $this->airBooking->refund->transaction()->create([
                'sign' => TransactionEnum::SIGN['PLUS'],
                'amount' => $form['refund_amount'] ?? 0,
                'purpose' => TransactionEnum::PURPOSE['AIR_BOOKING_REFUND'],
                'method' => $form['refund_method'],
                'initiated_by' => auth()->user()->id,
                'remark' => 'bookingRef:'.$this->airBooking->reference.', refundId:'.$this->airBooking->refund->id.', fee:'.$form['refund_amount'],
            ]);

            # increment account
            if ($form['refund_method'] == TransactionEnum::METHOD['ACCOUNT_BALANCE']) {
                $account->balance += $form['refund_amount'] ?? 0;
                $account->save();
            }
        }

        # update refund's own status
        $form['status'] = $form['refund_status'];
        $form['amount'] = $form['refund_amount'];
        $form['fee'] = $form['refund_fee'];
        $this->airBooking->refund->update($form);

        return session()->flash('success', 'Refund request updated');
    }

    # mark booking as ticket issued
    public function markAsTicketIssued() {
        if (!$this->airBooking->airTicket) {
            return session()->flash('failed', 'Ticket issue request not found!');
        }

        if ($this->airBooking->airTicket->status == FlightEnum::STATUS['CONFIRMED']) {
            return session()->flash('failed', 'Already marked as issued!');
        }
        $this->airBooking->airTicket->update([
            'issued_at' => now(),
            'issued_by' => auth()->user()->id,
            'status' => FlightEnum::STATUS['CONFIRMED']
        ]);

        $this->setBooking();
        return session()->flash('success', 'Booking marked as success.');
    }

    public function markAsTicketCanceled() {

    }
    public function markAsTicketRefunded() {

    }
    public function markAsTicketChanged() {
        $this->airBooking->airTicket->update([
            'status' => FlightEnum::STATUS['CHANGED']
        ]);

        $this->setBooking();
        return session()->flash('success', 'Booking marked as changed.');
    }

    # fetch only SABRE flight booking from GDS
    public function getBookingByPNR(SabreFlightGetBookingService $service) {

        if ($this->airBooking->source_api != ConstantHelper::SABRE) {
            return;
        }

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

    
    public function render() {
        if (!auth()->user()->can('admin_view flight booking')) {
            return view('admin.includes.unauthorized');
        };

        if (!$this->airBooking) {
            return view('livewire.error', ['message' => 'Flight not found!']);
        }
        
        # sabre get booking
        return view('livewire.admin.booking.booking-flight');
    }
}
