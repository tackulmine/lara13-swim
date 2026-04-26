<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192" href="/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="manifest" href="/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">

  <link rel="stylesheet" href="/assets/front/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/front/css/all.min.css">
  <link rel="stylesheet" href="/assets/front/css/animate.css">
  <link rel="stylesheet" href="/assets/front/css/flaticon.css">
  <link rel="stylesheet" href="/assets/front/css/lightcase.css">
  <link rel="stylesheet" href="/assets/front/css/odometer.css">
  <link rel="stylesheet" href="/assets/front/css/swiper.min.css">
  <link rel="stylesheet" href="/assets/front/css/nice-select.css">
  <link rel="stylesheet" href="/assets/front/css/main.css">
  <style>
    .logo a {
      color: #2560d4;
    }

    /*.footer-logo a img {
            filter: invert(1) brightness(5);
        }*/
  </style>
  @if (request()->is('*contact'))
    <style>
      .position-1 {
        top: 64%;
        left: 36%;
      }
    </style>
  @endif

  @stack('css')

  <link rel="shortcut icon" href="/assets/front/images/favicon.png" type="image/x-icon">

  <title>{{ $pageTitle ?? config('app.name') }}</title>
</head>

<body id="top">
  <!-- ==========Preloader========== -->
  <div class="preloader">
    <div class="preloader-wrapper">
      <img src="/assets/front/css/ajax-loader.gif" alt="ajax-loader">
    </div>
  </div>
  <!-- ==========Preloader========== -->

  <!-- ==========scrolltotop========== -->
  <a href="#0" class="scrollToTop" title="ScrollToTop">
    <img src="/assets/front/images/rocket.png" alt="rocket">
  </a>
  <!-- ==========scrolltotop========== -->

  <!-- ==========header-section========== -->
  @include('layouts.swim-one.header')
  <!-- ==========header-section========== -->

  @yield('content')

  <!-- ==========footer-section========== -->
  @include('layouts.swim-one.footer')
  <!-- ==========footer-section========== -->


  <script src="/assets/front/js/jquery-3.3.1.min.js"></script>
  <script src="/assets/front/js/modernizr-3.6.0.min.js"></script>
  <script src="/assets/front/js/plugins.js"></script>
  <script src="/assets/front/js/bootstrap.min.js"></script>
  <script src="/assets/front/js/isotope.pkgd.min.js"></script>
  <script src="/assets/front/js/jquery.ripples-min.js"></script>
  <script src="/assets/front/js/lightcase.js"></script>
  <script src="/assets/front/js/swiper.min.js"></script>
  <script src="/assets/front/js/wow.min.js"></script>
  <script src="/assets/front/js/countdown.min.js"></script>
  <script src="/assets/front/js/odometer.min.js"></script>
  <script src="/assets/front/js/viewport.jquery.js"></script>
  <script src="/assets/front/js/nice-select.js"></script>
  @if (request()->is('*contact'))
    <script src="/assets/front/js/contact.js"></script>
    <script src="/assets/front/js/jquery.vmap.min.js"></script>
    <script src="/assets/front/js/jquery.vmap.indonesia.js"></script>
  @endif
  <script src="/assets/front/js/main.js"></script>
  @if (request()->is('*contact'))
    <script>
      jQuery(document).ready(function() {
        jQuery('#vmap').vectorMap({
          map: 'indonesia_id',
          color: '#ededff',
          backgroundColor: 'transparent',
          hoverOpacity: .8,
          selectedColor: '#ffca24',
          scaleColors: ['#f7fcff', '#f7fcff'],
          normalizeFunction: 'polynomial'
        });
      });
    </script>
  @endif

  @stack('js')

</body>

</html>
