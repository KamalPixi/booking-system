<?php

namespace App\Http\Livewire\Agent\Flight;

use Livewire\Component;
use App\Helpers\FlightHelper;
use App\Helpers\ConstantHelper;
use App\Helpers\RefHelper;
use App\Enums\FlightEnum;
use App\Enums\FileEnum;
use App\Enums\SettingEnum;
use App\Enums\TransactionEnum;
use App\Services\SabreFlightBookingService;
use App\Services\USBanglaFlightBookingService;
use App\Services\SabreFlightRevalidateService;
use App\Services\SabreFlightGetBookingService;
use App\Models\AirBooking;
use App\Models\Agent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Mail;
use App\Mail\AgentAirInvoice;
use App\Enums\AdminFeeEnum;
use App\Models\AdminFee;


class FlightSearchDetails extends Component {
    use WithFileUploads;

    protected $queryString = ['session_index', 'session_key', 'children_dob'];

    # holds successful booking id
    public $booking_id = '';

    public $session_key;
    public $session_index;
    public $children_dob;
    public $flight = [];

    public $passengers = [];

    // protected $casts = [
    //     'passengers.*.dob' => 'date:Y-m-d',
    // ];
    
    protected $rules = [
        'passengers.*.title' => 'required',
        'passengers.*.first_name' => 'required',
        'passengers.*.surname' => 'required',
        'passengers.*.type' => 'required',
        'passengers.0.phone_no' => 'required',
        'passengers.*.dob' => 'required',
        'passengers.*.gender' => 'required',
        'passengers.*.passport_no' => 'required',
        'passengers.*.passport_type' => 'required',
        'passengers.*.passport_issuing_country' => 'required',
        'passengers.*.passport_issuance_date' => 'nullable',
        'passengers.*.passport_expiry_date' => 'required',
        'passengers.*.nationality_country' => 'required',
        'passengers.*.passport' => 'nullable',
        'passengers.*.visa' => 'nullable',
    ];

    protected $messages = [
        'passengers.*.title.required' => 'Title is required',
        'passengers.*.first_name.required' => 'First name is required',
        'passengers.*.surname.required' => 'Last name is required',
        'passengers.*.phone_no.required' => 'Phone no. is required',
        'passengers.*.dob.required' => 'Date of birth is required',
        'passengers.*.gender.required' => 'Gender is required',
        'passengers.*.passport_no.required' => 'Passport no. is required',
        'passengers.*.passport_type.required' => 'Passport type is required',
        'passengers.*.passport_issuing_country.required' => 'Passport issuing country is required',
        'passengers.*.passport_expiry_date.required' => 'Passport expiry date is required',
        'passengers.*.nationality_country.required' => 'Nationality country is required',
    ];

    public function mount() {
        // set flight from session
        if (!isset(session()->get($this->session_key)['itineraries'][$this->session_index])) {
            return;
        }

        $this->flight = session()->get($this->session_key)['itineraries'][$this->session_index];
        // add passengers empty structure
        $this->crateEmptyPassengers();
    }

    public function revalidate(SabreFlightRevalidateService $sabreRevalidate) {
        # stop checking when booking is completed
        if (!empty($this->booking_id)) {
            return;
        }

        if ($this->flight['apiSource'] == ConstantHelper::SABRE) {
            $revalidateJson = $sabreRevalidate->revalidate($this->flight);
            if (!$revalidateJson) {
                return;
            }

            if($sabreRevalidate->hasPriceChanged()) {
                return session()->flash('failed', "Warning! Price has been changed to " . number_format($sabreRevalidate->getPrice(), 2));
            }
        }
    }

    public function book(SabreFlightBookingService $sabreService, USBanglaFlightBookingService $usBanglaService) {                
        # permission check
        if (!auth()->user()->can('agent_create booking')) {
            return session()->flash('failed', 'Unauthorized!');
        };
        # will navigate user to top of browser
        $this->dispatchBrowserEvent('livewireCustomEvent', [
            'action' => 'scrollToTop'
        ]);

        # validation
        $form = $this->validate();

        # error, if INF > ADT
        if (!$this->isINFADTvalidate()) {
            return session()->flash('failed', 'Too many infants!');
        }

        # USBangla
        if ($this->flight['apiSource'] == ConstantHelper::US_BANGLA) {
            # passport issuance date is required
            $this->validate([
                'passengers.*.passport_issuance_date' => 'required',
            ]);

            # add API specific data
            $this->flight[ConstantHelper::US_BANGLA] = session()->get($this->session_key)[ConstantHelper::US_BANGLA];

            if($RQRSJson = $usBanglaService->book($this->flight, $form)) {
                $agent = auth()->user()->agent;
                
                $amount = $RQRSJson[1]['Booking']['FareInfo']['SaleCurrencyAmountTotal']['TotalAmount'] ?? $this->flight['pricingInfo']['totalPrice'];
                $base_amount =  $RQRSJson[1]['Booking']['FareInfo']['SaleCurrencyAmountTotal']['BaseAmount'];
                $airline_code = 'BS';
                $agent_payable_amount = $this->calculatePayableAmount($amount, $base_amount, $airline_code);
                $amount_with_margin = $this->calculateAirBookingMargin($agent_payable_amount);

                $air_booking = $agent->airBookings()->create([
                    'confirmation_id' => $RQRSJson[1]['Booking']['PnrInformation']['PnrCode'],
                    'trip_type' => $this->flight['tripType'],
                    'reference' => RefHelper::createFlightRef(),
                    'request_json' => json_encode($RQRSJson[0]),
                    'response_json' => json_encode($RQRSJson[1]),
                    'flight_json' => json_encode($this->flight),
                    'currency' => $RQRSJson[1]['Booking']['FareInfo']['SaleCurrencyCode'],
                    'amount' => $agent_payable_amount, # amount have to by agent
                    'amount_with_margin' => $amount_with_margin, # no use only display purpose
                    'payment_status' => TransactionEnum::STATUS['UNPAID'],
                    'ticketing_last_datetime' => Carbon::create($RQRSJson[1]['Booking']['PnrInformation']['TimeLimit'])->format('Y-m-d H:i'),
                    'status' => FlightEnum::STATUS['CONFIRMED'],
                    'source_api' => ConstantHelper::US_BANGLA,
                    'created_by' => auth()->user()->id
                ]);

                foreach ($form['passengers'] as $k => $passenger) {
                    $pModel = $air_booking->airBookingPassengers()->create($passenger);
                    $this->storeFiles($pModel, $k);
                }

                $this->booking_id = $air_booking->reference;
                session()->flash('href', route('b2b.flight.show', ['id' => $this->booking_id]));
                session()->flash('text', 'View Booking');

                # send invoice to agent
                // $this->sendEmail($air_booking);

                return session()->flash('success', 'Booking successful.');
            }
        }


        # sabre
        if ($this->flight['apiSource'] == ConstantHelper::SABRE) {
            if ($RQRSJson = $sabreService->book($this->flight, $form)) {
                $agent = auth()->user()->agent;

                $ticketing_last_datetime = $RQRSJson[1]['CreatePassengerNameRecordRS']['AirPrice'][0]['PriceQuote']['MiscInformation']['HeaderInformation'][0]['LastTicketingDate'] ?? null;

                $amount =  $RQRSJson[1]['CreatePassengerNameRecordRS']['TravelItineraryRead']['TravelItinerary']['ItineraryInfo']['ItineraryPricing']['PriceQuoteTotals']['TotalFare']['Amount'] ?? $this->flight['pricingInfo']['totalPrice'];
                $base_amount =  $RQRSJson[1]['CreatePassengerNameRecordRS']['TravelItineraryRead']['TravelItinerary']['ItineraryInfo']['ItineraryPricing']['PriceQuoteTotals']['EquivFare']['Amount'] ?? $this->flight['pricingInfo']['totalBaseFareAmount'];
                $airline_code = $this->flight['legs'][0]['schedules'][0]['carrier']['marketing'] ?? '';
                $agent_payable_amount = $this->calculatePayableAmount($amount, $base_amount, $airline_code);
                $amount_with_margin = $this->calculateAirBookingMargin($agent_payable_amount);

                $air_booking = $agent->airBookings()->create([
                    'confirmation_id' => $RQRSJson[1]['CreatePassengerNameRecordRS']['ItineraryRef']['ID'],
                    'reference' => RefHelper::createFlightRef(),
                    'trip_type' => $this->flight['tripType'],
                    'request_json' => json_encode($RQRSJson[0]),
                    'response_json' => json_encode($RQRSJson[1]),
                    'flight_json' => json_encode($this->flight),
                    'ticketing_last_datetime' => $ticketing_last_datetime,
                    'amount' => $agent_payable_amount, # amount have to by agent
                    'amount_with_margin' => $amount_with_margin, # no use only display purpose
                    'payment_status' => TransactionEnum::STATUS['UNPAID'],
                    'status' => FlightEnum::STATUS['CONFIRMED'],
                    'source_api' => ConstantHelper::SABRE,
                    'created_by' => auth()->user()->id,
                ]);
    
                foreach ($form['passengers'] as $k => $passenger) {
                    $pModel = $air_booking->airBookingPassengers()->create($passenger);
                    $this->storeFiles($pModel, $k);
                }

                $this->booking_id = $air_booking->reference;
                
                session()->flash('href', route('b2b.flight.show', ['id' => $this->booking_id]));
                session()->flash('text', 'View Booking');

                # send invoice to agent
                // $this->sendEmail($air_booking);

                return session()->flash('success', 'Booking successful.');
            }

        }

        dd($sabreService->response->json());
        session()->flash('failed', 'Booking not found!, Please try again after 1 min.');
    }

    public function sendEmail($airBooking) {
        sleep(2);
        # to fetch newly created booking 
        $service = new SabreFlightGetBookingService();
        $service->getBooking($airBooking->confirmation_id);
        $airBooking->update([
            'get_booking_response_json' => json_encode($service->responseJson)
        ]);

        $agent = auth()->user()->agent;
        $invoice_no = RefHelper::createInvoiceRef();
        Mail::to($agent->email)->send(new AgentAirInvoice($airBooking, $this->flight, $invoice_no));
        $airBooking->invoices()->create([
            'invoice_no' => $invoice_no
        ]);
    }

    public function storeFiles($passengerModel, $i) {
        if (!empty($this->passengers[$i]['passport'])) {
            $filename = 'air_booking_passport_'. auth()->user()->id .'_'.time().'.'.$this->passengers[$i]['passport']->getClientOriginalExtension();  
            $path = $this->passengers[$i]['passport']->storePubliclyAs('files', $filename, 's3');
            
            $url = Storage::disk('s3')->url($path);
            $p = $passengerModel->files()->create([
                'file' => $url,
                'file_key' => FileEnum::TYPE[0],
            ]);
        }
        if (!empty($this->passengers[$i]['visa'])) {
            $filename = 'air_booking_visa_'. auth()->user()->id .'_'.time().'.'.$this->passengers[$i]['visa']->getClientOriginalExtension();  
            $path = $this->passengers[$i]['visa']->storePubliclyAs('files', $filename, 's3');
            $url = Storage::disk('s3')->url($path);
            $p = $passengerModel->files()->create([
                'file' => $url,
                'file_key' => FileEnum::TYPE[1],
            ]);
        }
    }

    public function render() {
        if (!auth()->user()->can('agent_view booking')) {
            return view('agent.includes.unauthorized');
        };

        if (count($this->flight) < 1) {
            return view('livewire.error', ['message' => 'Flight not found']);
        }
        
        return view('livewire.agent.flight.flight-search-details');
    }


    public function crateEmptyPassengers() {
        $childIndex = 0;
        foreach ($this->flight['passengerInfoList'] as $pKey => $passengerInfo) {
            for ($i = 0; $i < $passengerInfo['passengerNumber']; $i++) {
                $emptyPassengerStruct = FlightHelper::PassengerDataStructure();
                $emptyPassengerStruct['type'] = $passengerInfo['passengerType'];
                // add child dob
                if ($passengerInfo['passengerType'][0] == 'C') {
                    $emptyPassengerStruct['dob'] = $this->children_dob[$childIndex] ?? '';
                    $childIndex++;
                }
                $this->passengers[] = $emptyPassengerStruct;
            }
        }
    }

    protected function isINFADTvalidate() {
        $ADTCount = 0;
        $INFCount = 0;
        foreach ($this->passengers as $passenger) {
            if ($passenger['type'] == 'ADT') {
                $ADTCount++;
            }
            if ($passenger['type'] == 'INF') {
                $INFCount++;
            }
        }
        if ($INFCount > $ADTCount) {
            return false;
        }
        return true;
    }

    protected function calculateAirBookingMargin($amount) {
        $airBookingMargin = ''; 
        foreach (auth()->user()->agent->profitMargins ?? [] as $profitMargin) {
            if ($profitMargin->key == SettingEnum::PROFIT_MARGIN_KEY[0]) {
                $airBookingMargin = $profitMargin;
                break;
            }
        }

        if (!isset($airBookingMargin->type)) {
            return $amount;
        }

        # calculate profit margin & add with total price
        if ($airBookingMargin->type == TransactionEnum::METHOD_FEE_TYPE['FIXED']) {
            return $amount + $airBookingMargin->amount;
        }
        $profitAmount = $amount / 100 * $airBookingMargin->amount;
        return $amount + $profitAmount;
    }

    protected function calculatePayableAmount($amount, $base_amount, $airline_code) {
        # get admin fees
        $adminFees = AdminFee::all();
        $ait = AdminFee::where('fee_key', AdminFeeEnum::KEY[0])->first();
        $commission = AdminFee::where('fee_key', AdminFeeEnum::KEY[1])->first();

        # AIT will be used as default
        $ait_fee = $ait->fee ?? 0;
        $ait_fee_type = $ait->type ?? TransactionEnum::METHOD_FEE_TYPE['FIXED'];

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
        $commission_amount = $this->fixedPercentCal($commission_fee_type, $commission_fee, $base_amount);
        $ait_amount = $this->fixedPercentCal($ait_fee_type, $ait_fee, $amount);
        return ($amount + $ait_amount) - $commission_amount;
    }


    protected function fixedPercentCal($type, $fee, $amount) {
        if ($type == TransactionEnum::METHOD_FEE_TYPE['FIXED']) {
            return $fee;
        }
        return $amount / 100 * $fee;
    }
}
