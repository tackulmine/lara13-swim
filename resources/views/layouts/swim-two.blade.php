<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <link rel="stylesheet" href="/assets/front/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/front/css/all.min.css">
  <link rel="stylesheet" href="/assets/front/css/animate.css">
  <link rel="stylesheet" href="/assets/front/css/flaticon.css">
  <link rel="stylesheet" href="/assets/front/css/lightcase.css">
  <link rel="stylesheet" href="/assets/front/css/odometer.css">
  <link rel="stylesheet" href="/assets/front/css/swiper.min.css">
  <link rel="stylesheet" href="/assets/front/css/nice-select.css">
  <link rel="stylesheet" href="/assets/front/css/main.css">

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
  <a href="#top" class="scrollToTop" title="ScrollToTop">
    <img src="/assets/front/images/rocket.png" alt="rocket">
  </a>
  <!-- ==========scrolltotop========== -->

  <!-- ==========header-section========== -->
  <header class="header-section">
    <div class="header-top d-none d-md-block">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6">
            <ul class="mail-call d-flex flex-wrap">
              <li class="mr-3 mr-lg-4">
                <a href="Tel:839394845">
                  <i class="flaticon-phone-call"></i>
                  <!-- <i class="fas fa-phone-square"></i> -->
                  +9999 - 222 - 333</a>
              </li>
              <li>
                <a href="Mailto:messon@gmail.com">
                  <i class="flaticon-envelope"></i>
                  <!-- <i class="fas fa-envelope"></i> -->
                  messon@gmail.com
                </a>
              </li>
            </ul>
          </div>
          <div class="col-md-3 col-xl-4">
            <ul class="social">
              <li>
                <a href="#0">
                  <i class="fab fa-facebook"></i>
                </a>
              </li>
              <li>
                <a href="#0">
                  <i class="fab fa-twitter"></i>
                </a>
              </li>
              <li>
                <a href="#0">
                  <i class="fab fa-linkedin-in"></i>
                </a>
              </li>
              <li>
                <a href="#0">
                  <i class="fab fa-instagram"></i>
                </a>
              </li>
            </ul>
          </div>
          <div class="col-md-3 col-xl-2">
            <ul class="d-flex flex-wrap justify-content-end account">
              <li>
                <a href="sign-in.html">Login</a>
              </li>
              <li>
                <a href="sign-up.html">Register</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="header-bottom">
      <div class="container">
        <div class="header-wrapper">
          <div class="logo">
            <a href="/">
              {{-- <img src="/assets/front/images/logo/logo.png" alt="logo">
                            --}}
              {{ $pageTitle ?? config('app.name') }}
            </a>
          </div>
          <ul class="menu ml-auto">
            <li>
              <a href="#0">Home</a>
              {{-- <ul class="submenu">
                                <li>
                                    <a href="/">Home One</a>
                                </li>
                                <li>
                                    <a href="index-two.html">Home Two</a>
                                </li>
                            </ul> --}}
            </li>
            <li>
              <a href="{!! url('about') !!}">About</a>
            </li>
            {{-- <li>
                            <a href="#0">Courses</a>
                            <ul class="submenu">
                                <li>
                                    <a href="course.html">Course</a>
                                </li>
                                <li>
                                    <a href="course-details.html">Course Details</a>
                                </li>
                                <li>
                                    <a href="classes-schedule.html">Class Schedule</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#0">Shop</a>
                            <ul class="submenu">
                                <li>
                                    <a href="shop.html">Shop</a>
                                </li>
                                <li>
                                    <a href="offer.html">Offer</a>
                                </li>
                                <li>
                                    <a href="cart.html">Cart</a>
                                </li>
                                <li>
                                    <a href="checkout.html">Checkout</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#0">Pages</a>
                            <ul class="submenu">
                                <li>
                                    <a href="#0">Instructors</a>
                                    <ul class="submenu">
                                        <li>
                                            <a href="instructors.html">Instructors</a>
                                        </li>
                                        <li>
                                            <a href="instructor-profile.html">Instructors Details</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="gallery.html">Gallery</a>
                                </li>
                                <li>
                                    <a href="#0">Event</a>
                                    <ul class="submenu">
                                        <li>
                                            <a href="event.html">Event</a>
                                        </li>
                                        <li>
                                            <a href="event-details.html">Event Details</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="admission.html">admission</a>
                                </li>
                                <li>
                                    <a href="wish-list.html">our wish list</a>
                                </li>
                                <li>
                                    <a href="privacy.html">privacy</a>
                                </li>
                                <li>
                                    <a href="faq.html">faq</a>
                                </li>
                                <li>
                                    <a href="404.html">404</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#0">Blog</a>
                            <ul class="submenu">
                                <li>
                                    <a href="blog-grid.html">blog grid</a>
                                </li>
                                <li>
                                    <a href="blog-classic.html">blog classic</a>
                                </li>
                                <li>
                                    <a href="blog-details.html">blog details</a>
                                </li>
                            </ul>
                        </li> --}}
            {{-- <li>
                            <a href="#0">Account</a>
                            <ul class="submenu">
                                <li>
                                    <a href="sign-in.html">sign in</a>
                                </li>
                                <li>
                                    <a href="sign-up.html">sign up</a>
                                </li>
                            </ul>
                        </li> --}}
            <li>
              <a href="{!! url('contact') !!}">Contact</a>
            </li>
          </ul>
          <div class="header-bar d-lg-none">
            <span></span>
            <span></span>
            <span></span>
          </div>
          {{-- <ul class="search-area">
                        <li>
                            <a class="search-bar" href="#0">
                                <i class="flaticon-magnifying-glass"></i>
                            </a>
                        </li>
                        <li>
                            <a id="cart-button" href="#0">
                                <i class="flaticon-shopping-cart"></i>
                            </a>
                        </li>
                    </ul> --}}
        </div>
      </div>
    </div>
  </header>
  {{-- <div class="search-form-area">
        <span class="hide-form">
            <i class="fas fa-times"></i>
        </span>
        <form class="search-form">
            <input type="text" placeholder="Search Here">
            <button type="submit"><i class="flaticon-search"></i></button>
        </form>
    </div> --}}
  {{--
    <!-- ===========Header Cart=========== -->
    <div id="body-overlay" class="body-overlay"></div>
    <div class="cart-sidebar-area" id="cart-sidebar-area">
        <div class="top-content">
            <a href="/" class="logo">
                <img src="/assets/front/images/logo/footer-logo.png" alt="logo">
            </a>
            <span class="side-sidebar-close-btn"><i class="fas fa-times"></i></span>
        </div>
        <div class="bottom-content">
            <div class="cart-products">
                <h4 class="title">Shopping cart</h4>
                <div class="single-product-item">
                    <div class="thumb">
                        <img src="/assets/front/images/shop/shop01.png" alt="shop">
                    </div>
                    <div class="content">
                        <h4 class="title">Swimming Shoes</h4>
                        <div class="price"><span class="pprice">$80.00</span> <del class="dprice">$120.00</del></div>
                        <a href="#" class="remove-cart">Remove</a>
                    </div>
                </div>
                <div class="single-product-item">
                    <div class="thumb">
                        <img src="/assets/front/images/shop/shop02.png" alt="shop">
                    </div>
                    <div class="content">
                        <h4 class="title">Water Glass</h4>
                        <div class="price"><span class="pprice">$80.00</span> <del class="dprice">$120.00</del></div>
                        <a href="#" class="remove-cart">Remove</a>
                    </div>
                </div>
                <div class="single-product-item">
                    <div class="thumb">
                        <img src="/assets/front/images/shop/shop03.png" alt="shop">
                    </div>
                    <div class="content">
                        <h4 class="title">Support Suit</h4>
                        <div class="price"><span class="pprice">$80.00</span> <del class="dprice">$120.00</del></div>
                        <a href="#" class="remove-cart">Remove</a>
                    </div>
                </div>
                <div class="single-product-item">
                    <div class="thumb">
                        <img src="/assets/front/images/shop/shop06.png" alt="shop">
                    </div>
                    <div class="content">
                        <h4 class="title">Stop Watch</h4>
                        <div class="price"><span class="pprice">$80.00</span> <del class="dprice">$120.00</del></div>
                        <a href="#" class="remove-cart">Remove</a>
                    </div>
                </div>
                <div class="single-product-item">
                    <div class="thumb">
                        <img src="/assets/front/images/shop/shop05.png" alt="shop">
                    </div>
                    <div class="content">
                        <h4 class="title">Support Ring</h4>
                        <div class="price"><span class="pprice">$80.00</span> <del class="dprice">$120.00</del></div>
                        <a href="#" class="remove-cart">Remove</a>
                    </div>
                </div>
                <div class="btn-wrapper text-center">
                    <a href="checkout.html" class="custom-button active">Checkout</a>
                </div>
            </div>
        </div>
    </div>
    <!-- ===========Header Cart=========== --> --}}
  <!-- ==========header-section========== -->

  @yield('content')

  <!-- ==========footer-section========== -->
  <footer class="style-two">
    <div class="footer-top padding-top padding-bottom bg-ash">
      <div class="container">
        <div class="row mb-40-none">
          <div class="col-md-4 col-sm-8">
            <div class="footer-widget footer-about">
              <div class="footer-logo">
                <a href="/">
                  {{-- <img src="/assets/front/images/logo/logo.png"
                                        alt="logo"> --}}
                  {{ $pageTitle ?? config('app.name') }}
                </a>
              </div>
              <p>Lorem ipsum dolor sit amet, porta feugiat odio nam ut magnis tempor. Vitae quis nisl
                viverra adipiscing in, integer penatibus elit luctus </p>
              <ul>
                <li>
                  <a href="tel:80930458459">
                    <!-- <i class="fas fa-phone"></i> -->
                    <i class="flaticon-telephone-handle-silhouette"></i>
                    9999 3333 8888
                  </a>
                </li>
                <li>
                  <a href="#0">
                    <!-- <i class="fas fa-map-marker-alt"></i>  -->
                    <i class="flaticon-maps-and-flags"></i>
                    Minnie Downs QLD 4478
                  </a>
                </li>
              </ul>
            </div>
          </div>
          {{-- <div class="col-md-3 col-sm-4">
                        <div class="footer-widget link-widget pl-lg-4">
                            <h4 class="title">Our Branches</h4>
                            <ul>
                                <li>
                                    <a href="#0">Alberta</a>
                                </li>
                                <li>
                                    <a href="#0">Columbia</a>
                                </li>
                                <li>
                                    <a href="#0">Manitoba</a>
                                </li>
                                <li>
                                    <a href="#0">Nunavut</a>
                                </li>
                                <li>
                                    <a href="#0">Yukon</a>
                                </li>
                                <li>
                                    <a href="#0">Onturio</a>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
          <div class="col-md-5">
            <div class="footer-widget footer-schedule">
              <h4 class="title">Opening Hours</h4>
              <ul>
                <li>
                  <a href="#0">
                    <span>Sat-Tues</span>
                    <span>08:00am-01:00pm</span>
                  </a>
                </li>
                <li>
                  <a href="#0">
                    <span>Wed-Thurs</span>
                    <span>12:00am-03:00pm</span>
                  </a>
                </li>
                <li>
                  <a href="#0">
                    <span>Friday</span>
                    <span>07:00am-09:00pm</span>
                  </a>
                </li>
                <li>
                  <a href="#0">
                    <span>Sunday</span>
                    <span>08:00am-01:00pm</span>
                  </a>
                </li>
              </ul>
              {{-- <div class="text-right calendar">
                                <a href="#0">Go to Calendar</a>
                            </div> --}}
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- <div class="footer-bottom py-4 bg-theme text-center">
            <div class="container">
                <p class="m-0"><a href="templateshub.net">Templateshub</a></p>
            </div>
        </div> --}}
  </footer>
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
  <script src="/assets/front/js/main.js"></script>

  @stack('js')
</body>

</html>
