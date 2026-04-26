<header class="header-section">
  <div class="header-top d-none d-lg-block">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-3">
          <ul class="header-top-info">
            <li>
              <div class="left">
                <i class="flaticon-phone-call"></i>
              </div>
              <div class="right">
                <span class="d-block">Call Now</span>
                <a href="tel:+6283849423959">0838 4942 3959</a>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-md-4">
          <ul class="header-top-info">
            <li>
              <div class="left">
                <i class="flaticon-placeholder"></i>
              </div>
              <div class="right">
                <span class="d-block">Location</span>
                <a href="#0">Jl Megare Ngelom RT/RW 02/01, Sepanjang, Taman - Sidoarjo, Jawa Timur</a>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-md-5">
          <ul class="header-top-info">
            <li>
              <div class="left">
                <i class="flaticon-clock"></i>
              </div>
              <div class="right">
                <span class="d-block">Office Hours</span>
                <a href="#0">14:00-16:00 WIB (Selasa & Kamis) | 7:00-9:00 WIB (Sabtu) | 7:00-9:00 WIB (Minggu)</a>
              </div>
            </li>
          </ul>
        </div>
        {{-- <div class="col-md-3">
                    <ul class="d-flex justify-content-end account">
                        <li>
                            <a href="sign-in.html">Login</a>
                        </li>
                        <li>
                            <a href="sign-up.html">Register</a>
                        </li>
                    </ul>
                </div> --}}
      </div>
    </div>
  </div>
  <div class="header-bottom">
    <div class="container">
      <div class="header-wrapper">
        <div class="logo">
          <a href="{{ request()->is('/') ? '#top' : '/' }}" class="d-flex align-items-center strong">
            <img src="/assets/front/images/favicon.png" alt="{{ config('app.name') }} logo" height="40">
            <span class="h5 ml-2 my-0"><strong>{{ config('app.name') }}</strong></span>
          </a>
        </div>
        <ul class="menu ml-auto">
          <li><a href="#about">about</a></li>
          <li><a href="#why">why us?</a></li>
          <li><a href="#course">course</a></li>
          <li><a href="#event">event</a></li>
          <li><a href="#coach">coach</a></li>
          <li><a href="#feedback">feedback</a></li>
          <li><a href="#contact">contact</a></li>
          {{-- <li>
                        <a href="/">Home</a>
                        <ul class="submenu">
                            <li>
                                <a href="index.html">Home One</a>
                            </li>
                            <li>
                                <a href="index-two.html">Home Two</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{!! url('about') !!}">About</a>
                    </li>
                    <li>
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
                    </li>
                    <li>
                        <a href="#0">Account</a>
                        <ul class="submenu">
                            <li>
                                <a href="sign-in.html">sign in</a>
                            </li>
                            <li>
                                <a href="sign-up.html">sign up</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{!! url('contact') !!}">Contact</a>
                    </li> --}}
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
        <a href="#" class="logo">
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
