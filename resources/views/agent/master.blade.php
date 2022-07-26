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

    <!-- inner -->
    <link rel="stylesheet" type="text/css" href="/assets/inner/vendor/bootstrap/css/bootstrap.min.css" />
    
    <!-- ========== All CSS files linkup ========= -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/css/lineicons.css" />
    <link rel="stylesheet" href="/assets/css/main.css" />
    <script src="/assets/js/kit.fontawesome.js"></script>
    <link href="/assets/inner/css/select2.min.css" rel="stylesheet" />

    <!-- for inner -->
    <link rel="stylesheet" type="text/css" href="/assets/inner/vendor/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="/assets/inner/css/stylesheet.css" />


    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>

    @livewireStyles
    <link rel="stylesheet" type="text/css" href="/assets/css/master.css" />
    @stack('css')


  </head>
  <body>
    @include('agent.includes.sidebar')

    <main class="main-wrapper">
      @include('agent.includes.header')
      <section class="section pt-0">
          @yield('content')
      </section>

      @include('agent.includes.footer')
    </main>

    <!-- ========= All Javascript files linkup ======== -->
    <script src="/assets/js/bootstrap.bundle.min.js"></script>

    <!-- for inner js -->
    <script src="/assets/inner/vendor/jquery/jquery.min.js"></script>
    <script src="/assets/inner/js/select2.min.js"></script>


    <script src="/assets/js/polyfill.js"></script>
    <script src="/assets/js/main.js"></script>

    <!-- inner -->
    <script src="/assets/inner/vendor/jquery-ui/jquery-ui.min.js"></script> 
    @stack('script')
    <script src="/assets/inner/js/theme.js"></script>
    <script src="/assets/inner/js/lottie-player.js"></script>
    @livewireScripts
    <script src="/assets/js/alpine.min.js"></script>
    <script src="/assets/js/master.js"></script>
  </body>
</html>
