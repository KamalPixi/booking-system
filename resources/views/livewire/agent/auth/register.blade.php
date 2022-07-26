<div>
    <section class="signin-section rounded m-4">

        <div class="container-fluid">

            <div class="row">
                <div class="col-8">
                    <div class="card-style settings-card-2 mb-30">
                        <div class="title mb-30">
                            <h6 class="mb-15 d-flex justify-content-between">
                                Agent Registration Form
                                <a href="/login" class="btn btn-sm btn-primary">Go to login</a>
                            </h6>
                            <p class="text-sm mb-25">
                                To create an agent account please fill and submit the form. We'll contact you soon.
                            </p>
                        </div>

                        @include('includes.alert_success_inner')
                        @include('includes.alert_failed_inner')
                        @include('includes.errors')

                        <form wire:submit.prevent="submit">
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-style-1">
                                        <label>Full Name*</label>
                                        <input wire:model="full_name" required type="text" placeholder="Full Name" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="input-style-1">
                                        <label>Company*</label>
                                        <input wire:model="company" required type="text" placeholder="Company" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="input-style-1">
                                        <label>Email*</label>
                                        <input wire:model="email" required type="email" placeholder="Email" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="input-style-1">
                                        <label>Phone*</label>
                                        <input wire:model="phone" required type="text" placeholder="Phone" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="input-style-1">
                                        <label>Address*</label>
                                        <input wire:model="address" required type="text" placeholder="Address" />
                                    </div>
                                </div>
                                <div class="col-xxl-4">
                                    <div class="input-style-1">
                                        <label>Division*</label>
                                        <select wire:model="division" required class="form-control">
                                            <option value="">Choose</option>
                                            @foreach(\App\Models\BangladeshDivision::all() as $d)
                                                <option value="{{$d->name}}">{{ $d->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xxl-4">
                                    <div class="input-style-1">
                                        <label>District*</label>
                                        <select wire:model="city" required placeholder="Districts" class="form-control">
                                            <option value="">Choose</option>
                                            @foreach(\App\Models\BangladeshDistrict::all() as $d)
                                                <option value="{{$d->name}}">{{ $d->name }}</option>
                                            @endforeach
                                        </select>  
                                    </div>
                                </div>
                                <div class="col-xxl-4">
                                    <div class="input-style-1">
                                        <label>Postal Code*</label>
                                        <input wire:model="postcode" required type="text" placeholder="Postal Code" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="main-btn primary-btn btn-hover">
                                        Submit Registration Form
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- end card -->
                </div>
                <div class="col mt-4 mt-md-0">
                    <div class="bg-white shadow-md rounded p-4">
                    <h2 class="text-6 mb-4">Get in touch</h2>
                    <hr class="mx-n4 mb-4">
                    <p class="text-3">For Customer Support and Query, Get in touch with us.</p>
                    <div class="featured-box style-1">
                        <div class="featured-box-icon text-primary"> <i class="fas fa-map-marker-alt"></i></div>
                        <h3>DreamTripBD</h3>
                        <p>
                            67 Nayapaltan, City Heart, 13th Floor<br>
                            Dhaka 1000 
                        </p>
                    </div>
                    <div class="featured-box style-1">
                        <div class="featured-box-icon text-primary"> <i class="fas fa-phone"></i> </div>
                        <h3>Telephone</h3>
                        <p>(+880) 1777-997703</p>
                    </div>
                    <div class="featured-box style-1">
                        <div class="featured-box-icon text-primary"> <i class="fas fa-envelope"></i> </div>
                        <h3>Business Inquiries</h3>
                        <p>info@dreamtripbd.com</p>
                    </div>
                    </div>
                </div>
            </div>


        </div>

    </section>

    <script>
        document.addEventListener('contentChanged', (e) => {
            $('html, body').animate({ scrollTop: 0 }, 'fast');
        });
    </script>
</div>