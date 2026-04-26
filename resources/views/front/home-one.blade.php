@extends('layouts.swim-one')

@section('content')
  <!-- ==========banner-section========== -->
  <section class="banner-section bg_img" data-background="/assets/front/images/banner/banner-bg02.jpg">
    <div class="container">
      <div class="banner-content mx-auto text-center">
        <span class="cate wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="1s">LATIHAN RENANG UNTUK SEMUA UMUR
          MIN. 1 TAHUN KE ATAS</span>
        <h1 class="title wow fadeInDown" data-wow-duration="1.5s">Pemula, Prestasi, Terapi</h1>
        <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">Dilatih oleh pelatih professional dan
          berlisensi</p>
        <div class="button-group justify-content-center wow fadeInUp" data-wow-delay="1s" data-wow-duration="1s">
          <a href="#contact" class="custom-button">Hubungi Kami</a>
          {{-- <a href="{!! url('contact') !!}" class="custom-button">Contact Us</a> --}}
          {{-- <a href="admission.html" class="custom-button active">admission
                        now</a> --}}
        </div>
      </div>
    </div>
  </section>
  <!-- ==========banner-section========== -->

  <!-- ==========welcome-section========== -->
  <section class="wellcome-section padding-bottom padding-top" id="about">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="welcome-thumb">
            <div class="thumb1">
              <img src="/assets/front/images/about/about04.jpg" alt="about">
            </div>
            <div class="thumb2">
              <img src="/assets/front/images/about/about05.jpg" alt="about">
            </div>
            <div class="thumb3">
              <img src="/assets/front/images/about/about06.jpg" alt="about">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="section-header left-style mb-low mw-100 mt-down">
            <h2 class="title">Selamat datang</h2>
            <span class="d-inline-block mx-auto shape-header"></span>
            <p>Selamat datang di website Centrum Swimming Club (SC).</p>
          </div>
          <div class="wellcome-area">
            <div class="wellcome-item">
              <span class="left-side">
                <i class="flaticon-swimming-silhouette"></i>
              </span>
              <div class="right-side">
                <h4 class="title">Siapa Kami</h4>
                <p>Centrum Swimming Club adalah perkumpulan renang yang dilatih oleh pelatih professional
                  dan berlisensi.</p>
              </div>
            </div>
            <div class="wellcome-item">
              <span class="left-side">
                <i class="flaticon-goggles"></i>
              </span>
              <div class="right-side">
                <h4 class="title">Program Kami</h4>
                <p>Kami melatih para perenang untuk berenang dengan cara yang benar dan baik untuk berbagai
                  macam gaya renang bebas, dada, punggung, kupu-kupu dan lain sebagainya</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ==========welcome-section========== -->

  <!-- ==========about-us-section========== -->
  <section class="about-us-section padding-bottom">
    <div class="container">
      <div class="row flex-wrap-reverse">
        <div class="col-lg-6">
          <div class="section-header left-style mb-low mw-100 mt-down">
            <h2 class="title">Tentang Centrum Swimming Club (SC)</h2>
            <span class="d-inline-block mx-auto shape-header"></span>
            <p>Centrum Swimming Club (SC) adalah perkumpulan renang yang menjunjung tinggi professional dan
              kekeluargaan. Pelatih yang melatih renang adalah pelatih professional dan berlisensi. Untuk
              lebih jelasnya silakan berkunjung ke alamat kami di Jl Megare Ngelom RT/RW 02/01, Sepanjang,
              Taman - Sidoarjo, Jawa Timur</p>
          </div>
          <div class="about-us-area">
            {{-- <div class="about-us-item">
                            <h4 class="title">Sejarah Berdiri</h4>
                            <p>Lorem ipsum dolor sit ampendisse illum elit nunc suspendisse dolor, sed nulla nec, tempor
                                amet ac venenatis praesent libero. Ante ea justo aptent</p>
                        </div>
                        <div class="about-us-item">
                            <h4 class="title">Misi Kami</h4>
                            <p>Lorem ipsum dolor sit ampendisse illum elit nunc suspendisse dolor, sed nulla nec, tempor
                                amet ac venenatis praesent libero. Ante ea justo aptent</p>
                        </div> --}}
            <div class="about-us-item">
              <h4 class="title">Cara Bergabung</h4>
              <p>Silakan menghubungi kami melalui Telp/ Whatsapp <a href="tel:+6283849423959">0838 4942 3959</a></p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="about-us-thumb">
            <div class="thumb1 wow slideInUp">
              <img src="/assets/front/images/about/about01.jpg" alt="about">
            </div>
            <div class="thumb2 wow bounceInRight" data-wow-delay=".5s">
              <img src="/assets/front/images/about/about02.jpg" alt="about">
            </div>
            <div class="thumb3 wow zoomIn" data-wow-delay=".5s">
              <img src="/assets/front/images/about/about03.jpg" alt="about">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ==========about-us-section========== -->

  <!-- ==========overview-two-section========== -->
  <section class="overview-two-section" id="why">
    <div class="container-fluid p-0">
      <div class="row m-0">
        <div class="col-lg-6 padding-top padding-bottom pb-lg-0 mb--30-max-sm px-0 bg_img"
          data-background="/assets/front/images/overview/overview-bg03.jpg">
          <div class="overview-counter-section h-100">
            <div class="overview-counter-wrapper h-100">
              <div class="counter-item">
                <div class="odometer" data-odometer-final="5">0</div>
                <h3 class="sub-title">Pelatih</h3>
              </div>
              <div class="counter-item">
                <div class="odometer" data-odometer-final="3">0</div>
                <h3 class="sub-title">Kolam Renang</h3>
              </div>
              <div class="counter-item">
                <div class="odometer plus" data-odometer-final="50">0 <span>+</span></div>
                <h3 class="sub-title">Perenang Puas</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 px-0">
          <div class="overview-two-content theme-overlay-deep w-100 h-100 padding-top padding-bottom bg_img"
            data-background="/assets/front/images/overview/overview-bg02.jpg">
            <div class="content">
              <div class="section-header mb-low light left-style">
                <h2 class="title">Mengapa memilih Centrum Swimming Club (SC)?</h2>
                <span class="d-inline-block mx-auto shape-header"></span>
              </div>
              <div class="choose-area light-color">
                <div class="choose-item">
                  <h4 class="title">Berpengalaman lebih dari 5 tahun</h4>
                  <p>Pengalaman kami melatih para perenang dari tingkat pemula, prestasi dan terapi sudah
                    lebih dari 5 tahun</p>
                </div>
                <div class="choose-item">
                  <h4 class="title">50+ perenang</h4>
                  <p>Kami memiliki lebih dari 50 perenang aktif yang telah mendapatkan juara di berbagai
                    kompetisi renang tingkat sekolah, kecamatan, kabupaten dan nasional</p>
                </div>
                <div class="choose-item">
                  <h4 class="title">Pelatih yang berlisensi</h4>
                  <p>Pelatih-pelatih renang kami sangat professional dan berlisensi renang</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ==========overview-two-section========== -->

  <!-- ==========course-section========== -->
  <section class="course-section padding-top padding-bottom" id="course">
    <div class="container">
      <div class="row align-items-end">
        <div class="col-md-7">
          <div class="section-header left-style">
            <h2 class="title">Biaya Kursus Centrum Swimming Club (SC)</h2>
            <span class="d-inline-block mx-auto shape-header"></span>
            <p>Biaya kursus di Centrum Swimming Club beragam dan terbagi dalam kategori berikut:</p>
          </div>
        </div>
        {{-- <div class="col-md-5 text-md-right discover-button">
                    <a href="course.html" class="custom-button">discover more</a>
                </div> --}}
      </div>
      <div class="row justify-content-center mb-30-none">
        <div class="col-md-6 col-sm-10 col-lg-4">
          <div class="course-item">
            <div class="c-thumb course-thumb">
              <a href="https://wa.me/+6283849423959?text=<?php echo urlencode('Saya tertarik dan ingin mengetahui biaya kursus kategori "Privat" di Centrum SC?'); ?>" target="_blank">
                <img src="/assets/front/images/course/course01.jpg" alt="course">
              </a>
              {{-- <div class="price-tag">
                                <h3 class="price">Rp.200K/Rp.350K</h3>
                                <span class="time">4X/8X</span>
                            </div> --}}
            </div>
            <div class="course-content">
              <h4 class="title"><a href="https://wa.me/+6283849423959?text=<?php echo urlencode('Saya tertarik dan ingin mengetahui biaya kursus kategori "Privat" di Centrum SC?'); ?>"
                  target="_blank">Privat{{-- <span>12-36 Month</span> --}}</a></h4>
              {{-- <p>Risus justo pede et, pellentesque convallis Tidunt tempus odio nisl justo aliqpraesent est
                                inceptos velitlorem orci conval</p>
                            <a href="#!">Read More</a> --}}
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-10 col-lg-4">
          <div class="course-item">
            <div class="c-thumb course-thumb">
              <a href="https://wa.me/+6283849423959?text=<?php echo urlencode('Saya tertarik dan ingin mengetahui biaya kursus kategori "Grup" di Centrum SC?'); ?>" target="_blank">
                <img src="/assets/front/images/course/course02.jpg" alt="course">
              </a>
              {{-- <div class="price-tag">
                                <h3 class="price">Rp.150K/Rp.200K</h3>
                                <span class="time">4X/8X</span>
                            </div> --}}
            </div>
            <div class="course-content">
              <h4 class="title"><a href="https://wa.me/+6283849423959?text=<?php echo urlencode('Saya tertarik dan ingin mengetahui biaya kursus kategori "Grup" di Centrum SC?'); ?>"
                  target="_blank">Grup{{-- <span>4-6 Years</span> --}}</a></h4>
              {{-- <p>Risus justo pede et, pellentesque convallis Tidunt tempus odio nisl justo aliqpraesent est
                                inceptos velitlorem orci conval</p>
                            <a href="#!">Read More</a> --}}
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-10 col-lg-4">
          <div class="course-item">
            <div class="c-thumb course-thumb">
              <a href="https://wa.me/+6283849423959?text=<?php echo urlencode('Saya tertarik dan ingin mengetahui biaya kursus kategori "Prestasi" di Centrum SC?'); ?>" target="_blank">
                <img src="/assets/front/images/course/course03.jpg" alt="course">
              </a>
              {{-- <div class="price-tag">
                                <h3 class="price">Rp.2xx K</h3>
                                <span class="time">1 Month</span>
                            </div> --}}
            </div>
            <div class="course-content">
              <h4 class="title"><a href="https://wa.me/+6283849423959?text=<?php echo urlencode('Saya tertarik dan ingin mengetahui biaya kursus kategori "Prestasi" di Centrum SC?'); ?>"
                  target="_blank">Prestasi{{-- <span>8-16 Years</span> --}}</a></h4>
              {{-- <p>Risus justo pede et, pellentesque convallis Tidunt tempus odio nisl justo aliqpraesent est
                                inceptos velitlorem orci conval</p>
                            <a href="#!">Read More</a> --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ==========course-section========== -->

  <!-- ==========overview-section========== -->
  <section class="overview-section" id="event">
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
              <div class="section-header left-style mw-100">
                <h2 class="title">Kompetisi Centrum Swimming Club (SC)</h2>
                <span class="d-inline-block mx-auto shape-header"></span>
                <p>Kompetisi-kompetisi yang telah dan akan berlangsung di Centrum Swimming Club</p>
              </div>
              <div class="faq-wrapper">
                @forelse ($events as $event)
                  <div class="faq-item @if ($event->end_date > now()) active open @endif">
                    <div class="faq-title">
                      <h4 class="title">{{ $event->name }}</h4>
                    </div>
                    <div class="faq-content">
                      <span class="schedule">{!! parseBetweenDate($event->start_date, $event->end_date) !!} , {{ $event->location }}</span>
                      {{-- <p>Maecenas finibus nec sem ut imperdiet. Ut tincidunt est ac dolor aliquam sodales.
                                            Phasellus sed mauris hendrerit, laoreet sem lrtis ante.sodales ultricies diam.
                                            Nullam justo leo</p> --}}
                    </div>
                  </div>
                @empty
                  <p>No events available.</p>
                @endforelse
                {{-- <div class="faq-item active open">
                                    <div class="faq-title">
                                        <h4 class="title">Junior Swimming Competition</h4>
                                    </div>
                                    <div class="faq-content">
                                        <span class="schedule">05 May,2019 - Current , Kids School</span>
                                        <p>Maecenas finibus nec sem ut imperdiet. Ut tincidunt est ac dolor aliquam sodales.
                                            Phasellus sed mauris hendrerit, laoreet sem lrtis ante.sodales ultricies diam.
                                            Nullam justo leo</p>
                                    </div>
                                </div>
                                <div class="faq-item">
                                    <div class="faq-title">
                                        <h4 class="title">Create Great Swimmers</h4>
                                    </div>
                                    <div class="faq-content">
                                        <span class="schedule">05 May,2019 - Current , Kids School</span>
                                        <p>Maecenas finibus nec sem ut imperdiet. Ut tincidunt est ac dolor aliquam sodales.
                                            Phasellus sed mauris hendrerit, laoreet sem lrtis ante.sodales ultricies diam.
                                            Nullam justo leo</p>
                                    </div>
                                </div>
                                <div class="faq-item">
                                    <div class="faq-title">
                                        <h4 class="title">Kids and Old Swimming Competition</h4>
                                    </div>
                                    <div class="faq-content">
                                        <span class="schedule">05 May,2019 - Current , Kids School</span>
                                        <p>Maecenas finibus nec sem ut imperdiet. Ut tincidunt est ac dolor aliquam sodales.
                                            Phasellus sed mauris hendrerit, laoreet sem lrtis ante.sodales ultricies diam.
                                            Nullam justo leo</p>
                                    </div>
                                </div>
                                <div class="faq-item">
                                    <div class="faq-title">
                                        <h4 class="title">Family Swimming Competition</h4>
                                    </div>
                                    <div class="faq-content">
                                        <span class="schedule">05 May,2019 - Current , Kids School</span>
                                        <p>Maecenas finibus nec sem ut imperdiet. Ut tincidunt est ac dolor aliquam sodales.
                                            Phasellus sed mauris hendrerit, laoreet sem lrtis ante.sodales ultricies diam.
                                            Nullam justo leo</p>
                                    </div>
                                </div> --}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ==========overview-section========== -->

  <!-- ==========instructor-section========== -->
  <section class="instructor-section padding-bottom padding-top" id="coach">
    <div class="container">
      <div class="section-header">
        <h2 class="title">Pelatih Centrum Swimming Club (SC)</h2>
        <span class="d-inline-block mx-auto shape-header"></span>
        {{-- <p>Amet consectetur adipiscing elit, sed do eiutempor incididunt ut labore et dolore magna aliquaad minvenia
                    quis nost</p> --}}
      </div>
      <div class="row mb-30-none justify-content-center">
        {{-- <div class="col-md-6 col-lg-4 col-sm-10">
                    <div class="instructor-item">
                        <div class="c-thumb">
                            <a href="#!">
                                <img src="/assets/front/images/instructor/instructor01.jpg" alt="instructor">
                            </a>
                        </div>
                        <div class="instructor-content">
                            <h4 class="sub-title">
                                <a href="#!">Qiqi</a>
                            </h4>
                            <span class="d-block">Senior Instructor</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-sm-10">
                    <div class="instructor-item">
                        <div class="c-thumb">
                            <a href="#!">
                                <img src="/assets/front/images/instructor/instructor02.jpg" alt="instructor">
                            </a>
                        </div>
                        <div class="instructor-content">
                            <h4 class="sub-title">
                                <a href="#!">Dwi</a>
                            </h4>
                            <span class="d-block">Senior Instructor</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-sm-10">
                    <div class="instructor-item">
                        <div class="c-thumb">
                            <a href="#!">
                                <img src="/assets/front/images/instructor/instructor03.jpg" alt="instructor">
                            </a>
                        </div>
                        <div class="instructor-content">
                            <h4 class="sub-title">
                                <a href="#!">Denan</a>
                            </h4>
                            <span class="d-block">Senior Instructor</span>
                        </div>
                    </div>
                </div> --}}
        @foreach ($coaches as $coach)
          <div class="col-md-6 col-lg-4 col-sm-10">
            <div class="instructor-item">
              <div class="c-thumb">
                <a href="#!">
                  <img src="{!! getAvatar($coach->photo_url, $coach->email, 360) !!}" alt="{{ $coach->name }}">
                </a>
              </div>
              <div class="instructor-content">
                <h4 class="sub-title">
                  <a href="#!">{{ $coach->name }}</a>
                </h4>
                <span class="d-block">{{ optional(optional($coach->userStaff)->type)->name }}</span>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  <!-- ==========instructor-section========== -->
  {{--
    <!-- ==========course-schedule-section========== -->
    <section class="course-schedule padding-bottom">
        <div class="container">
            <div class="section-header">
                <h2 class="title">Course Schedule</h2>
                <span class="d-inline-block mx-auto shape-header"></span>
                <p>Amet consectetur adipiscing elit, sed do eiutempor incididunt ut labore et dolore magna aliquaad minvenia
                    quis nost</p>
            </div>
            <div class="schedule-wrapper">
                <div class="schedule-header text-center">
                </div>
                <table class="schedule-table">
                    <thead class="t-header">
                        <tr>
                            <th>Course Name</th>
                            <th>Course Duration</th>
                            <th>Class Time</th>
                            <th>Instructor</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody class="t-body">
                        <tr>
                            <td data-input="Course Name">Family Swim</td>
                            <td data-input="Course Duration">06 Mounth</td>
                            <td data-input="Course Time">
                                <span class="class-date">Satday to Monday</span>
                                <span class="class-time">10.00 am - 02.00 pm</span>
                            </td>
                            <td data-input="Instructor">
                                <div class="instructor">
                                    <div class="thumb">
                                        <a href="#0"><img src="/assets/front/images/instructor/instructor05.png"
                                                alt="schedule"></a>
                                    </div>
                                    <div class="content">
                                        <a href="#0">Jack Paul</a>
                                        <span>Instructor</span>
                                    </div>
                                </div>
                            </td>
                            <td data-input="Note">pellentesque odio qu amet ut amet morbi </td>
                        </tr>
                        <tr>
                            <td data-input="Course Name">Juniors</td>
                            <td data-input="Course Duration">06 Mounth</td>
                            <td data-input="Course Time">
                                <span class="class-date">Satday to Monday</span>
                                <span class="class-time">10.00 am - 02.00 pm</span>
                            </td>
                            <td data-input="Instructor">
                                <div class="instructor">
                                    <div class="thumb">
                                        <a href="#0"><img src="/assets/front/images/instructor/instructor02.png"
                                                alt="schedule"></a>
                                    </div>
                                    <div class="content">
                                        <a href="#0">David James</a>
                                        <span>Instructor</span>
                                    </div>
                                </div>
                            </td>
                            <td data-input="Note">pellentesque odio qu amet ut amet morbi </td>
                        </tr>
                        <tr>
                            <td data-input="Course Name">Backstroke</td>
                            <td data-input="Course Duration">06 Mounth</td>
                            <td data-input="Course Time">
                                <span class="class-date">Satday to Monday</span>
                                <span class="class-time">10.00 am - 02.00 pm</span>
                            </td>
                            <td data-input="Instructor">
                                <div class="instructor">
                                    <div class="thumb">
                                        <a href="#0"><img src="/assets/front/images/instructor/instructor03.png"
                                                alt="schedule"></a>
                                    </div>
                                    <div class="content">
                                        <a href="#0">monica Wagse</a>
                                        <span>Instructor</span>
                                    </div>
                                </div>
                            </td>
                            <td data-input="Note">pellentesque odio qu amet ut amet morbi </td>
                        </tr>
                        <tr>
                            <td data-input="Course Name">Seniors</td>
                            <td data-input="Course Duration">06 Mounth</td>
                            <td data-input="Course Time">
                                <span class="class-date">Satday to Monday</span>
                                <span class="class-time">10.00 am - 02.00 pm</span>
                            </td>
                            <td data-input="Instructor">
                                <div class="instructor">
                                    <div class="thumb">
                                        <a href="#0"><img src="/assets/front/images/instructor/instructor01.png"
                                                alt="schedule"></a>
                                    </div>
                                    <div class="content">
                                        <a href="#0">Natalli Moe</a>
                                        <span>Instructor</span>
                                    </div>
                                </div>
                            </td>
                            <td data-input="Note">pellentesque odio qu amet ut amet morbi </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- ==========course-schedule-section========== -->
    --}}

  {{--
    <!-- ==========client-section========== -->
    <section class="client-section padding-bottom">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-md-7">
                    <div class="section-header left-style">
                        <h2 class="title">what client say ?</h2>
                        <span class="d-inline-block mx-auto shape-header"></span>
                        <p>Amet consectetur adipiscing elit, sed do eiutempor incididunt ut labore et dolore magna aliquaad
                            minvenia quis nost</p>
                    </div>
                </div>
                <div class="col-md-5 text-md-right discover-button">
                    <a href="#0" class="custom-button">discover more</a>
                </div>
            </div>
            <div class="row justify-content-center mb-30-none">
                <div class="col-md-6 col-lg-4 col-sm-10">
                    <div class="client-item">
                        <div class="client-thumb">
                            <div class="thumb">
                                <img src="/assets/front/images/client/client01.jpg" alt="client">
                            </div>
                        </div>
                        <div class="client-content">
                            <p>Aut at vestibulum aliquam in, proinon donec nec nisl consectet metu neque turpis aliquet
                                fermtum </p>
                            <h4 class="sub-title"><a href="#0">Jake Paul Bewn</a></h4>
                            <span>Senior Instructor</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-sm-10">
                    <div class="client-item">
                        <div class="client-thumb">
                            <div class="thumb">
                                <img src="/assets/front/images/client/client02.jpg" alt="client">
                            </div>
                        </div>
                        <div class="client-content">
                            <p>Aut at vestibulum aliquam in, proinon donec nec nisl consectet metu neque turpis aliquet
                                fermtum </p>
                            <h4 class="sub-title"><a href="#0">raihan Rafuj</a></h4>
                            <span>Senior Instructor</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-sm-10">
                    <div class="client-item">
                        <div class="client-thumb">
                            <div class="thumb">
                                <img src="/assets/front/images/client/client03.jpg" alt="client">
                            </div>
                        </div>
                        <div class="client-content">
                            <p>Aut at vestibulum aliquam in, proinon donec nec nisl consectet metu neque turpis aliquet
                                fermtum </p>
                            <h4 class="sub-title"><a href="#0">Fahad Bin</a></h4>
                            <span>Senior Instructor</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==========client-section========== -->

    <!-- ==========blog-section========== -->
    <section class="blog-section padding-top padding-bottom bg-ash">
        <div class="container">
            <div class="section-header">
                <h2 class="title">recent blog post</h2>
                <span class="d-inline-block mx-auto shape-header"></span>
                <p>Amet consectetur adipiscing elit, sed do eiutempor incididunt ut labore et dolore magna aliquaad minvenia
                    quis nost</p>
            </div>
            <div class="mb-none-50-lg-60">
                <div class="post-item">
                    <div class="post-thumb">
                        <a href="blog-grid.html">
                            <img src="/assets/front/images/blog/blog01.jpg" alt="blog">
                        </a>
                    </div>
                    <div class="post-content">
                        <div class="post-header">
                            <h4 class="title">
                                <a href="blog-grid.html">Non repellat lectus luctus rhoncus lectb eatae lorem enim
                                    vestibulum ridiculus neque. </a>
                            </h4>
                            <div class="meta-post">
                                <a href="#0">20 Nov, 2019</a><a href="#0">03 Comments</a>
                            </div>
                        </div>
                        <div class="entry-content">
                            <p>Pharetra aenean facilisis arcu aliquam, id diam mauris vivamus id vitae et. Eros mauris quis
                                leo. Vitae leo phasellus donec, integer rutrum aliquam elit, non etiam turpis nullam lectus
                                urna urna, ullamcorper sit, accuman maecenneque vel aliquam aliquam tincidunt. Congue et
                                etiam nastur, nulla odio, morbi eros lectus, vestibulum ut libero</p>
                            <div class="meta-author">
                                <div class="c-thumb">
                                    <a href="#0">
                                        <img src="/assets/front/images/blog/blog01.png" alt="blog">
                                    </a>
                                </div>
                                <div class="c-content">
                                    <a href="#0">Raihan Rafuj</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="post-item">
                    <div class="post-thumb">
                        <a href="blog-grid.html">
                            <img src="/assets/front/images/blog/blog02.jpg" alt="blog">
                        </a>
                    </div>
                    <div class="post-content">
                        <div class="post-header">
                            <h4 class="title">
                                <a href="blog-grid.html">Ollamcorper luctus ullamcorper semper nec erat nulla amet
                                    ringillavsed </a>
                            </h4>
                            <div class="meta-post">
                                <a href="#0">20 Nov, 2019</a><a href="#0">03 Comments</a>
                            </div>
                        </div>
                        <div class="entry-content">
                            <p>Pharetra aenean facilisis arcu aliquam, id diam mauris vivamus id vitae et. Eros mauris quis
                                leo. Vitae leo phasellus donec, integer rutrum aliquam elit, non etiam turpis nullam lectus
                                urna urna, ullamcorper sit, accuman maecenneque vel aliquam aliquam tincidunt. Congue et
                                etiam nastur, nulla odio, morbi eros lectus, vestibulum ut libero</p>
                            <div class="meta-author">
                                <div class="c-thumb">
                                    <a href="#0">
                                        <img src="/assets/front/images/blog/blog02.png" alt="blog">
                                    </a>
                                </div>
                                <div class="c-content">
                                    <a href="#0">ridoy rajoan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="post-item">
                    <div class="post-thumb">
                        <a href="blog-grid.html">
                            <img src="/assets/front/images/blog/blog03.jpg" alt="blog">
                        </a>
                    </div>
                    <div class="post-content">
                        <div class="post-header">
                            <h4 class="title">
                                <a href="blog-grid.html">Pharetra aenean facilisis arcu aliquam diam mauris vivamus vitae
                                </a>
                            </h4>
                            <div class="meta-post">
                                <a href="#0">20 Nov, 2019</a><a href="#0">03 Comments</a>
                            </div>
                        </div>
                        <div class="entry-content">
                            <p>Pharetra aenean facilisis arcu aliquam, id diam mauris vivamus id vitae et. Eros mauris quis
                                leo. Vitae leo phasellus donec, integer rutrum aliquam elit, non etiam turpis nullam lectus
                                urna urna, ullamcorper sit, accuman maecenneque vel aliquam aliquam tincidunt. Congue et
                                etiam nastur, nulla odio, morbi eros lectus, vestibulum ut libero</p>
                            <div class="meta-author">
                                <div class="c-thumb">
                                    <a href="#0">
                                        <img src="/assets/front/images/blog/blog01.png" alt="blog">
                                    </a>
                                </div>
                                <div class="c-content">
                                    <a href="#0">Raihan Rafuj</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==========blog-section========== --> --}}

  <!-- ==========client-section========== -->
  <section class="client-section padding-top padding-bottom bg-ash" id="feedback">
    <div class="container">
      <div class="row align-items-end">
        <div class="col-md-7">
          <div class="section-header left-style">
            <h2 class="title">Apa Kata Mereka ?</h2>
            <span class="d-inline-block mx-auto shape-header"></span>
            <p>Berikan feedback positif anda jika anda puas dengan layanan kami.</p>
          </div>
        </div>
        {{-- <div class="col-md-5 text-md-right discover-button">
          <a href="#0" class="custom-button">discover more</a>
        </div> --}}
      </div>
      <div class="mb-30-none">
        <div id="disqus_thread"></div>
        <script>
          /**
           *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
           *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables    */
          /*
          var disqus_config = function () {
          this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
          this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
          };
          */
          (function() { // DON'T EDIT BELOW THIS LINE
            var d = document,
              s = d.createElement('script');
            s.src = 'https://centrumsc.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
          })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by
            Disqus.</a></noscript>
      </div>
    </div>
  </section>
  <!-- ==========client-section========== -->

  <!-- ==========question-section========== -->
  <section class="call-in-action padding-bottom padding-top" id="contact">
    <div class="container">
      <div class="call-in-area">
        <h2 class="title">Ingin menghubungi Centrum Swimming Club (SC) untuk bertanya?</h2>
        <p>Masih memiliki pertanyaan seputar pelatihan, jadwal kursus dan lain sebagainya? Silakan kontak kami
          dengan menekan tombol di bawah ini.</p>
        {{-- <a href="{!! url('contact') !!}" class="custom-button active">contact us</a> --}}
        <a href="https://wa.me/+6283849423959?text=<?php echo urlencode('Saya tertarik daftar menjadi anggota club Centrum SC, apa syaratnya?'); ?>" target="_blank" class="custom-button active"><i
            class="flaticon-telephone-handle-silhouette mr-2"></i> whatsapp kami</a>
      </div>
    </div>
  </section>
  <!-- ==========question-section========== -->
@endsection
