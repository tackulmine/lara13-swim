@extends('layouts.swim-one')

@section('content')
  <!-- ==========hero-area========== -->
  <section class="hero-section">
    <div class="hero-area bg_img" data-background="/assets/front/images/about/hero-bg.jpg">
      <div class="container">
        <h1 class="title">contact us</h1>
      </div>
    </div>
    <div class="container">
      <ul class="breadcrumb">
        <li>
          <a href="/">home</a>
        </li>
        <li>
          contact
        </li>
      </ul>
    </div>
  </section>
  <!-- ==========hero-area========== -->

  <!-- ==========Vector-Maps Section========== -->
  <section class="vector-maps-section padding-bottom padding-top">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div id="vmap">
            <div class="position-1">
              <span class="dot"></span>
              <div class="details">
                <h6 class="name">Kiki Centrum</h6>
                <p class="area">From: East Java</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="tab contact-tab">
            <ul class="tab-menu">
              <li>east java</li>
            </ul>
            <div class="tab-area">
              <div class="tab-item">
                <div class="east java">
                  <p>Roslacus mi, morbi rutrum facilisis interdum purus mattis sit. Quis malesuada
                    mauris convallis enim eu semper, dolor a amet massa sociis. Ipsum id tortor
                    viverra donec a, non et, vestibulum varius. Velit donec amet amet a npharetra.
                    In a augue, integer volutpat in turpis amet mus nisl posuere pede necessitati
                  </p>
                  <div class="address-area">
                    <div class="address-item">
                      <div class="icon">
                        <i class="flaticon-placeholder"></i>
                      </div>
                      <div class="content">
                        <h4 class="title">Address</h4>
                        <ul>
                          <li>Georgia, UK /589</li>
                          <li>Sector 07 Dhaka - 1230</li>
                        </ul>
                      </div>
                    </div>
                    <div class="address-item">
                      <div class="icon">
                        <i class="flaticon-paper-plane"></i>
                      </div>
                      <div class="content">
                        <h4 class="title">Email Address</h4>
                        <ul>
                          <li>
                            <a href="Mailto:diveon@gmail.com">diveon@gmail.com</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div class="address-item">
                      <div class="icon">
                        <i class="flaticon-phone-call"></i>
                      </div>
                      <div class="content">
                        <h4 class="title">phone number</h4>
                        <ul>
                          <li>
                            <a href="Tel:777788889999">7777-8888-9999</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div class="address-item">
                      <div class="icon">
                        <i class="flaticon-clock"></i>
                      </div>
                      <div class="content">
                        <h4 class="title">Opening Hours</h4>
                        <ul>
                          <li>
                            Sunday to Friday
                          </li>
                          <li>
                            10: 00 am to 06: 00 pm
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ==========Vector-Maps Section========== -->

  <!-- ==========Contact-Section========== -->
  <section class="contact-section">
    <div class="container-fluid p-0">
      <div class="row m-0">
        <div class="col-lg-6 p-0">
          <div class="w-100 h-100 overview-video theme-overlay bg_img"
            data-background="/assets/front/images/overview/overview-bg01.jpg">
            <a href="https://www.youtube.com/embed/BouxQd-ARV4" class="video" data-rel="lightcase:myCollection">
              <i class="flaticon-play-button"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-6 p-0">
          <div class="w-100 h-100 overview-event bg-ash padding-bottom padding-top">
            <div class="content">
              <div class="section-header left-style mw-100 mb-low">
                <h2 class="title">hello with us</h2>
                <span class="d-inline-block mx-auto shape-header"></span>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiutempor incididunt
                  ut labore et dolore magna aliqua t enim </p>
              </div>
              <form class="contact-form" id="contact_form_submit">
                <div class="form-group">
                  <input type="text" placeholder="Name" id="name" name="name">
                </div>
                <div class="form-group">
                  <input type="text" placeholder="Email" id="email" name="email">
                </div>
                <div class="form-group w-100">
                  <select class="select-bar" name="kisu" id="select">
                    <option value="">-Select Subject-</option>
                    <option value="s2">-Competetion-</option>
                    <option value="s3">-Swim Course-</option>
                    <option value="s4">-Want to be a Swimer-</option>
                    <option value="s5">-Kids Swiming-</option>
                    <option value="s6">-Buy Something-</option>
                  </select>
                </div>
                <div class="form-group w-100">
                  <textarea id="message" placeholder="Type Message"></textarea>
                </div>
                <div class="form-group w-100">
                  <input type="submit" value="Send Message">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ==========Contact-Section========== -->

  <!-- ==========call-in-action-section========== -->
  <section class="call-in-action padding-bottom padding-top">
    <div class="container">
      <div class="call-in-area">
        <h2 class="title">Wish You Clear About {{ config('app.name') }}</h2>
        <p>Amet consectetur adipiscing elit, sed do eiutempor incididunt ut labore et dolore magna aliquaad
          minvenia quis nosta</p>
        {{-- <a href="{!!  url('contact') !!}" class="custom-button active">contact
                    us</a> --}}
      </div>
    </div>
  </section>
  <!-- ==========call-in-action-section========== -->
@endsection
