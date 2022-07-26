<?php

namespace App\Http\Livewire\Agent\Flight;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\PrePurchasedAir;
use App\Helpers\ConstantHelper;
use App\Helpers\FlightHelper;
use App\Helpers\RefHelper;
use App\Enums\FlightEnum;
use App\Enums\FileEnum;
use App\Enums\TransactionEnum;

class PrePurchasedAirBook extends Component {
    use WithFileUploads;

    protected $queryString = ['pre_purchased_id'];
    public $pre_purchased_id;
    public $flight;
    public $ticket_count = 1;

    # form inputs
    public $passengers = [];

    public $booking_id = '';

    protected $listeners = ['hideTicketCount'];

    protected $rules = [
        'passengers.*.title' => 'nullable',
        'passengers.*.first_name' => 'nullable',
        'passengers.*.surname' => 'nullable',
        'passengers.*.type' => 'nullable',
        'passengers.*.phone_no' => 'nullable',
        'passengers.*.dob' => 'nullable',
        'passengers.*.gender' => 'nullable',
        'passengers.*.passport_no' => 'nullable',
        'passengers.*.passport_type' => 'nullable',
        'passengers.*.passport_issuing_country' => 'nullable',
        'passengers.*.passport_issuance_date' => 'nullable',
        'passengers.*.passport_expiry_date' => 'nullable',
        'passengers.*.nationality_country' => 'nullable',
        'passengers.*.passport' => 'required|mimes:png,jpg,pdf|max:1024',
        'passengers.*.visa' => 'required|mimes:png,jpg,pdf|max:1024',
    ];
    protected $messages = [
        'passengers.*.passport.required' => 'Passport copy is required',
        'passengers.*.visa.required' => 'Visa copy is required',
        'passengers.*.passport.mimes' => 'Only png,jpg,pdf are allowed',
        'passengers.*.visa.mimes' => 'Only png,jpg,pdf are allowed',
    ];

    public function mount() {
        $this->flight = PrePurchasedAir::find($this->pre_purchased_id);
        $this->crateEmptyPassengers($this->ticket_count);
    }

    public function updated($p) {
        if ($p == 'ticket_count') {
            $this->crateEmptyPassengers($this->ticket_count);
        }
    }

    public function book() {
        $passengers = $this->validate();
        
        $agent = auth()->user()->agent;
        $air_booking = $agent->airBookings()->create([
            'confirmation_id' => 'N\A',
            'reference' => RefHelper::createFlightRef(),
            'trip_type' => 'ONE_WAY',
            'amount' => $this->flight->fare, # amount have to by agent
            'amount_with_margin' => $this->flight->fare, # no use only display purpose
            'payment_status' => TransactionEnum::STATUS['UNPAID'],
            'status' => FlightEnum::STATUS['CONFIRMED'],
            'source_api' => ConstantHelper::PRE,
            'created_by' => auth()->user()->id,
            'flight_json' => json_encode($this->flight->toArray()),
        ]);

        foreach ($this->passengers as $k => $passenger) {
            if (empty($passenger['dob'])) {
                $passenger['dob'] = null;
            }
            if (empty($passenger['passport_expiry_date'])) {
                $passenger['passport_expiry_date'] = null;
            }
            $pModel = $air_booking->airBookingPassengers()->create($passenger);
            $this->storeFiles($pModel, $k);
            $this->flight->decrement('count');
        }

        $this->booking_id = $air_booking->reference;
        return $air_booking;
    }

    public function storeFiles($passengerModel, $k) {
        if (!empty($this->passengers[$k]['passport'])) {
            $filename = 'air_booking_passport_'. auth()->user()->id .'_'.time().'.'.$this->passengers[$k]['passport']->getClientOriginalExtension();  
            $path = $this->passengers[$k]['passport']->storePubliclyAs('files', $filename, 's3');
            
            $url = Storage::disk('s3')->url($path);
            $p = $passengerModel->files()->create([
                'file' => $url,
                'file_key' => FileEnum::TYPE[0],
            ]);
        }
        if (!empty($this->passengers[$k]['visa'])) {
            $filename = 'air_booking_visa_'. auth()->user()->id .'_'.time().'.'.$this->passengers[$k]['visa']->getClientOriginalExtension();  
            $path = $this->passengers[$k]['visa']->storePubliclyAs('files', $filename, 's3');
            $url = Storage::disk('s3')->url($path);
            $p = $passengerModel->files()->create([
                'file' => $url,
                'file_key' => FileEnum::TYPE[1],
            ]);
        }
    }

    public function issue() {
        if (!$this->isQtyLeft()) {
            return;
        }

        $this->navigateToTop();
        
        $airBooking = $this->book();
        $agent = auth()->user()->agent;
        $payment_amount = $airBooking->amount;

        # create ticket & payments
        try {
            \DB::beginTransaction();

            # create ticket
            $airTicket = $airBooking->airTicket()->create([
                'amount' => $payment_amount,
                'currency' => TransactionEnum::CURRENCY['BDT'],
                'created_by' => auth()->user()->id,
                'agent_id' => $agent->id,
            ]);

            # update  booking payment status
            $airBooking->update([
                'payment_status' => TransactionEnum::STATUS['PAID']
            ]);

            # handle full payment
            $payment = $airBooking->payments()->create([
                'agent_id' => $agent->id,
                'amount' => $payment_amount,
                'currency' => TransactionEnum::CURRENCY['BDT'],
                'method' => TransactionEnum::METHOD['ACCOUNT_BALANCE'],
                'purpose' => TransactionEnum::PURPOSE['AIR_TICKET_ISSUE'],
                'status' => TransactionEnum::STATUS['PAID'],
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
                'currency' => TransactionEnum::CURRENCY['BDT'],
                'purpose' => TransactionEnum::PURPOSE['AIR_TICKET_ISSUE'],
                'method' => $payment->method,
                'remark' => TransactionEnum::STATUS['PAID'],
                'initiated_by' => $payment->created_by,
            ]);

            \DB::commit();
            return session()->flash('success', 'Ticket issue success.');
        }catch(\Exception $e){
            \DB::rollback();
            return session()->flash('failed', 'Ticket issue request failed' . $e->getMessage());
        }
    }

    public function requestPartial() {
        if (!$this->isQtyLeft()) {
            return;
        }

        $this->navigateToTop();
        $airBooking = $this->book();

        $airBooking->bookingPartialRequest()->create([
            'requested_by' => auth()->user()->id
        ]);
        return session()->flash('success', 'Request has been made, You will be notified soon.');
    }

    public function navigateToTop() {
        $this->dispatchBrowserEvent('livewireCustomEvent', [
            'action' => 'scrollToTop'
        ]);
    }

    public function crateEmptyPassengers($c) {
        $this->passengers = [];
        for ($i = 0; $i < $c; $i++) {
            $emptyPassengerStruct = FlightHelper::PassengerDataStructure();
            $emptyPassengerStruct['type'] = 'ADT';
            $this->passengers[] = $emptyPassengerStruct;
        }
    }

    public function isQtyLeft(){
        $p = PrePurchasedAir::find($this->pre_purchased_id);
        if ($p && $p->count < count($this->passengers)) {
            session()->flash('failed', 'Sorry! only ' . $p->count .' ticket(s) left');
            return false;
        }

        return true;
    }

    public function removePassenger($i) {
        unset($this->passengers[$i]);
    }

    public function render() {
        if (!$this->flight) {
            return abort(404);
        }
        return view('livewire.agent.flight.pre-purchased-air-book');
    }
}
