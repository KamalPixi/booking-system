<?php

namespace App\Http\Livewire\Agent\Settings;

use Livewire\Component;
use App\Enums\SettingEnum;
use App\Enums\TransactionEnum;
use App\Models\ProfitMargin;

class Setting extends Component {
    public $editProfit = false;

    public $air_booking_margin_amount = '';
    public $air_booking_margin_type = '';
    public $hotel_booking_margin_amount = '';
    public $hotel_booking_margin_type = '';
    public $umrah_booking_margin_amount = '';
    public $umrah_booking_margin_type = '';
    public $group_booking_margin_amount = '';
    public $group_booking_margin_type = '';
    public $holiday_booking_margin_amount = '';
    public $holiday_booking_margin_type = '';

    public $airM;
    public $hotelM;
    public $umrahM;
    public $groupM;
    public $holidayM;

    protected $rules = [
        'air_booking_margin_amount' => 'bail|required|numeric|digits_between:1,12',
        'air_booking_margin_type' => 'bail|required|max:255',
        'hotel_booking_margin_amount' => 'bail|required|numeric|digits_between:1,12',
        'hotel_booking_margin_type' => 'bail|required|max:255',
        'umrah_booking_margin_amount' => 'bail|required|numeric|digits_between:1,12',
        'umrah_booking_margin_type' => 'bail|required|max:255',
        'group_booking_margin_amount' => 'bail|required|numeric|digits_between:1,12',
        'group_booking_margin_type' => 'bail|required|max:255',
        'holiday_booking_margin_amount' => 'bail|required|numeric|digits_between:1,12',
        'holiday_booking_margin_type' => 'bail|required|max:255',
    ];

    public function mount() {
        $margins = auth()->user()->agent->profitMargins;

        $this->airM = $margins->filter(function($m) {
            if ($m->key == SettingEnum::PROFIT_MARGIN_KEY[0]) {
                return true;
            }
        })->first();
        $this->hotelM = $margins->filter(function($m) {
            if ($m->key == SettingEnum::PROFIT_MARGIN_KEY[1]) {
                return true;
            }
        })->first();
        $this->umrahM = $margins->filter(function($m) {
            if ($m->key == SettingEnum::PROFIT_MARGIN_KEY[2]) {
                return true;
            }
        })->first();
        $this->groupM = $margins->filter(function($m) {
            if ($m->key == SettingEnum::PROFIT_MARGIN_KEY[3]) {
                return true;
            }
        })->first();
        $this->holidayM = $margins->filter(function($m) {
            if ($m->key == SettingEnum::PROFIT_MARGIN_KEY[4]) {
                return true;
            }
        })->first();

        $this->fill([
            'air_booking_margin_amount' => $this->airM->amount,
            'air_booking_margin_type' => $this->airM->type,
            'hotel_booking_margin_amount' => $this->hotelM->amount,
            'hotel_booking_margin_type' => $this->hotelM->type,
            'umrah_booking_margin_amount' => $this->umrahM->amount,
            'umrah_booking_margin_type' => $this->umrahM->type,
            'group_booking_margin_amount' => $this->groupM->amount,
            'group_booking_margin_type' => $this->groupM->type,
            'holiday_booking_margin_amount' => $this->holidayM->amount,
            'holiday_booking_margin_type' => $this->holidayM->type,
        ]);
    }

    public function update() {
        if (!auth()->user()->can('agent_edit setting')) {
            return session()->flash('failed', 'Unauthorized!');
        };

        $form = $this->validate();
        $agent = auth()->user()->agent;

        # validate key
        $keys = [
            $form['air_booking_margin_type'],
            $form['hotel_booking_margin_type'],
            $form['umrah_booking_margin_type'],
            $form['group_booking_margin_type'],
            $form['holiday_booking_margin_type'],
        ];
        foreach ($keys as $key) {
            if (!in_array($key, [
                TransactionEnum::METHOD_FEE_TYPE['FIXED'],
                TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE']
            ])) {
                return session()->flash('failed', 'We got you!');
            }
        }

        $agent->profitMargins()->updateOrCreate([
                'key' => SettingEnum::PROFIT_MARGIN_KEY[0]
            ],[
                'type' => $form['air_booking_margin_type'],
                'amount' => $form['air_booking_margin_amount'],
            ]
        );
        $agent->profitMargins()->updateOrCreate([
                'key' => SettingEnum::PROFIT_MARGIN_KEY[1]
            ], [
                'type' => $form['hotel_booking_margin_type'],
                'amount' => $form['hotel_booking_margin_amount']
            ]
        );
        $agent->profitMargins()->updateOrCreate([
                'key' => SettingEnum::PROFIT_MARGIN_KEY[2]
            ],[
                'type' => $form['umrah_booking_margin_type'],
                'amount' => $form['umrah_booking_margin_amount']
            ]
        );
        $agent->profitMargins()->updateOrCreate([
                'key' => SettingEnum::PROFIT_MARGIN_KEY[3]
            ], [
                'type' => $form['group_booking_margin_type'],
                'amount' => $form['group_booking_margin_amount'],
            ]
        );
        $agent->profitMargins()->updateOrCreate([
                'key' => SettingEnum::PROFIT_MARGIN_KEY[4]
            ], [
                'type' => $form['holiday_booking_margin_type'],
                'amount' => $form['holiday_booking_margin_amount']
            ]
        );

        return session()->flash('success', 'Profile updated');
    }

    public function setEditProfit() {
        if (!auth()->user()->can('agent_edit setting')) {
            return session()->flash('failed', 'Unauthorized!');
        };

        $this->editProfit = true;
    }

    public function unsetEditProfit() {
        $this->editProfit = false;
    }

    public function render() {
        return view('livewire.agent.settings.setting');
    }

}
