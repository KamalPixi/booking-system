<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Developed By MdKamalUddin -->
    <!-- https://www.upwork.com/freelancers/~0158c003b34a97c29d -->

    <title>@yield('title', 'DreamTripBD - Dashboard')</title>
    
    <script src="{{ asset('admin_assets/js/alpinejs@3.2.2_dist.min.js') }}" defer></script>
    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin_assets/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="{{  asset('admin_assets/css/admin.min.css') }}">
    <link rel="stylesheet" href="{{  asset('admin_assets/css/custom.css') }}">
    @livewireStyles

    <!-- to push in css here -->
    @stack('push-css')
</head>
<body id="page-top">
    <div id="wrapper">
        @include('admin.includes.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('admin.includes.topbar')

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
            @include('admin.includes.footer')

        </div>
        <!-- End of Content Wrapper -->
    </div>

    <script src="{{ asset('admin_assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/admin.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/master.js') }}"></script>

    @livewireScripts
</body>
</html>
