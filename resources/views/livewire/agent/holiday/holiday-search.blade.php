<div class="col-lg-5 mx-auto mb-4 mb-lg-0">
    <h2 class="text-4 mb-3">Search Holiday Packages</h2>
    <form id="bookingHotels">

    @foreach($cities as $k => $city)
    <div class="form-row" wire:key="{{ $k }}">
        <small for="" style="padding-left:0.5rem">Destination</small>
        <div class="col-lg-12 form-group">
        <input type="text" class="form-control" id="hotelsFrom" required placeholder="Search For City">
        <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
    </div>
    @endforeach
    <div class="form-row align-items-center pl-2 mb-4">
        <i class="fa-solid fa-circle-plus title-icon color-primary"></i>
        <button type="button" class="btn btn-sm btn-primary rounded-pill ml-2 px-5" wire:click="addCity">Add City</button>
        @if(count($cities) > 1)
        <button type="button" class="btn btn-sm btn-primary rounded-pill ml-2 px-5" wire:click="removeCity">Remove</button>
        @endif
    </div>

    <button class="btn btn-primary btn-block" type="submit">Search Holiday</button>
    </form>
</div>
