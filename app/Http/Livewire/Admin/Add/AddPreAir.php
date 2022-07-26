<?php

namespace App\Http\Livewire\Admin\Add;

use Livewire\Component;
use App\Enums\UserEnum;
use App\Models\PrePurchasedAir;
use App\Models\Airport;
use Livewire\WithPagination;


class AddPreAir extends Component {

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $edit = false;
    public $showForm = false;
    public $model_id;

    public $airline = '';
    public $from = '';
    public $to = '';
    public $fare = '';
    public $depart_date = '';
    public $arrival_date = '';
    public $baggage = '';
    public $transit_location = '';
    public $transit_time = '';
    public $reference_remark = '';
    public $count = '';
    public $created_by = '';
    public $manage_by = '';


    # for suggestion purpose
    public $transit_hours = [];
    public $transit_hour;
    public $transit_minutes = [];
    public $transit_minute;
    
    public $baggages = [];


    protected $listeners = ['hideHours','hideMinutes'];

    protected $rules = [
        'airline' => 'required|max:255',
        'from' => 'required|max:255',
        'to' => 'required|max:255',
        'fare' => 'required|numeric',
        'depart_date' => 'required|max:255',
        'arrival_date' => 'nullable|max:255',
        'baggage' => 'nullable|max:255',
        'transit_location' => 'nullable|max:255',
        'transit_time' => 'nullable|max:255',
        'reference_remark' => 'nullable|max:255',
        'count' => 'required|numeric',
        'manage_by' => 'nullable|numeric',
    ];
    

    public function add() { 
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return session()->flash('failed', 'Only admin is allowed this operation!');
        }
        $form = $this->validate();
        $form['created_by'] = auth()->user()->id;
        if (empty($form['arrival_date'])) {
            $form['arrival_date'] = null;
        }
        PrePurchasedAir::create($form);
        return session()->flash('success', 'Add success.');
    }


    public function update() {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return session()->flash('failed', 'Only admin is allowed this operation!');
        }
        $form = $this->validate();
        $model = PrePurchasedAir::findOrFail($this->model_id);
        $model->update($form);
        $this->edit = false;
        return session()->flash('success', 'update success.');
    }

    public function delete($id) {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return session()->flash('failed', 'Only admin is allowed this operation!');
        }
        $model = PrePurchasedAir::findOrFail($id);
        $model->delete();
        $this->dispatchBrowserEvent('livewireCustomEvent', ['action' => 'scrollToTop']);
        return session()->flash('success', 'Delete success');
    }

    public function setEdit($id) {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            $this->dispatchBrowserEvent('livewireCustomEvent', ['action' => 'scrollToTop']);
            return session()->flash('failed', 'Only admin is allowed this operation!');
        }

        $this->showForm = true;
        $this->model_id = $id;
        $model = PrePurchasedAir::findOrFail($id);
        $this->fill($model->toArray());
        $this->edit = true;
        $this->dispatchBrowserEvent('livewireCustomEvent', ['action' => 'scrollToTop']);
        return session()->flash('info', 'Start editing.');
    }

    public function toggleForm() {
        $this->showForm = !$this->showForm;
    }


    /**
     * Belongs to Transit time
     */
    public function showHours() {
        $this->transit_hours = [];
        for ($i=0; $i < 24; $i++) { 
            $this->transit_hours[] = $i + 1 . 'h';
        }
    }
    public function hideHours() {
        $this->transit_hours = [];
    }
    public function setHour($h) {
        $this->transit_hour = $h;
        $this->hideHours();
    }
    public function showMinutes() {
        $this->transit_minutes = [];
        for ($i=0; $i < 60; $i++) { 
            $this->transit_minutes[] = $i + 1 . 'm';
        }
    }
    public function hideMinutes() {
        $this->transit_minutes = [];
    }
    public function setMinute($m) {
        $this->transit_minute = $m;
        $this->hideMinutes();
        $this->setTransitHour();
    }
    public function setTransitHour() {
        $this->transit_time = $this->transit_hour.' '.$this->transit_minute;
    }


    /**
     * Belongs to Baggage
     */
    public function setBaggage($h) {
        $this->baggage = $h;
        $this->hideBaggage();
    }
    public function hideBaggage() {
        $this->baggages = [];
    }
    public function showBaggage(){
        $this->baggages = $this->generateBaggage();
    }
    public function generateBaggage() {
        $kgs = [ '7KG' ];
        for ($i=3; $i < 13; $i++) {
            $kgs[] = 5 * $i . 'KG';
        }
        return $kgs;
    }

    public function render() {
        return view('livewire.admin.add.add-pre-air', [
            'flights' => PrePurchasedAir::paginate(10)
        ]);
    }
}
