<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="shortcut icon"
      href="/assets/images/favicon.svg"
      type="image/x-icon"
    />
    
    <!-- Developed By MdKamalUddin -->
    <!-- https://www.upwork.com/freelancers/~0158c003b34a97c29d -->
    <title>AirTicket Agent Panel</title>

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css" />
    <script src="/assets/js/kit.fontawesome.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/inner/css/stylesheet.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/master.css" />

    @livewireStyles
    @stack('css')
  </head>
  <body>

    @yield('content')

    <script src="/assets/inner/vendor/jquery/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    @stack('script')
    @livewireScripts
  </body>
</html>
