<div>
    <section class="signin-section rounded m-5">
    <div class="container-fluid">
        <div class="row g-0 auth-row">
        <div class="col-lg-6">
            <div class="auth-cover-wrapper bg-primary-100">
            <div class="auth-cover">
                <div class="title text-center">
                <h1 class="text-primary mb-10">Welcome Back</h1>
                <p class="text-medium">
                    Sign in with your agent account to continue.
                </p>
                </div>
                <div class="cover-image">
                <img src="assets/images/auth/signin-image.svg" alt="" />
                </div>
                <div class="shape-image">
                <img src="assets/images/auth/shape.svg" alt="" />
                </div>
            </div>
            </div>
        </div>
        <!-- end col -->
        <div class="col-lg-6">
            <div class="signin-wrapper">
            <div class="form-wrapper position-relative">
                <a class="btn-link float-register" href="/register"><i class="fa-solid fa-user-plus d-inline-block pr-1"></i> Register As Agent</a>

    
                @include('includes.alert_failed_inner')
                @include('includes.alert_success_inner')
                @include('includes.errors')

                

                {{-- login form --}}
                @if($formToShow == 'login')
                    <h6 class="mb-15 text-center">
                        <span>Sign In</span>
                    </h6>
                    <form>
                        <div class="row">
                            <div class="col-12">
                            <div class="input-style-1">
                                <label>Email</label>
                                <input wire:model="email" type="email" placeholder="Email" />
                            </div>
                            </div>
                            <!-- end col -->
                            <div class="col-12">
                            <div class="input-style-1">
                                <label>Password</label>
                                <input wire:model="password" type="password" placeholder="Password" />
                            </div>
                            </div>
                            <!-- end col -->
                            <div class="col-xxl-6 col-lg-12 col-md-6">
                            <div class="form-check checkbox-style mb-30">
                                <input
                                class="form-check-input"
                                type="checkbox"
                                value=""
                                id="checkbox-remember"
                                />
                                <label
                                class="form-check-label"
                                for="checkbox-remember"
                                >
                                Remember me next time</label
                                >
                            </div>
                            </div>
                            <!-- end col -->
                            <div class="col-xxl-6 col-lg-12 col-md-6">
                            <div
                                class="
                                text-start text-md-end text-lg-start text-xxl-end
                                mb-30
                                "
                            >
                                <button type="button" wire:click="showResetForm" class="btn btn-sm btn-link hover-underline"
                                >Forgot Password?</button>
                            </div>
                            </div>
                            <!-- end col -->
                            <div class="col-12">
                            <div
                                class="
                                button-group
                                d-flex
                                justify-content-center
                                flex-wrap
                                "
                            >
                                <button
                                    wire:click="login"
                                    type="button"
                                    class="
                                        main-btn
                                        primary-btn
                                        btn-hover
                                        w-100
                                        text-center
                                    "
                                    >
                                    <div wire:loading.remove wire:target="login">Sign In</div>
                                    <div wire:loading wire:target="login" class="loader loader-for-btn">Loading...</div>
                                </button>
                            </div>
                            
                                <p class="text-2 text-center mb-0 mt-4"><a class="btn-link" href="/register"><i class="fa-solid fa-user-plus d-inline-block pr-1"></i> Register As Agent</a></p>
                            </div>
                        </div>
                    </form>
                @endif



                {{-- password reset form --}}
                @if($formToShow == 'resetForm')
                    <form>
                        <h6 class="mb-15 text-center">Password Reset</h6>
                        <div class="row">
                            <div class="col-12">
                            <div class="input-style-1">
                                <label>Email</label>
                                <input wire:model="email" type="email" placeholder="Email" />
                            </div>
                            </div>
                            <div class="col-12">
                            <div
                                class="
                                button-group
                                d-flex
                                justify-content-center
                                flex-wrap
                                "
                            >
                                <button
                                    wire:click="resetPassword"
                                    type="button"
                                    class="
                                        main-btn
                                        primary-btn
                                        btn-hover
                                        w-100
                                        text-center
                                    "
                                    >
                                    <div wire:loading.remove wire:target="resetPassword">Reset Password</div>
                                    <div wire:loading wire:target="resetPassword" class="loader loader-for-btn">Loading...</div>
                                </button>

                                <button type="button" wire:click="showLogin" class="btn btn-sm btn-link hover-underline"
                                >Go back</button>
                            </div>
                            </div>
                        </div>
                    </form>
                @endif

            </div>
            </div>
        </div>
        <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    </section>
</div>
