@extends('layouts.auth')

@section('content')

  <div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
      <!-- Nested Row within Card Body -->
      <div class="row">
        <div class="col-lg-5 d-none d-lg-block bg-register-image"
          style="background-image: url({{ asset('assets/front/images/arisa-chattasa-AZcNLJgO4XE-unsplash.jpg') }});"></div>
        <div class="col-lg-7">
          <div class="p-5">
            <div class="text-center">
              <h1 class="h4 text-gray-900 mb-4">{{ __('Pendaftaran Atlet') }}</h1>
            </div>
            @include('layouts.partials._notif')
            {{-- <form class="user" id="member-registration" method="POST" action="{{ route('register') }}"> --}}

            {{-- {!! Form::open([
                'route' => ['register'],
                // 'class' => 'needs-validation',
                // 'novalidate' => true,
                'id' => 'member-registration',
                'class' => 'user',
                'files' => true,
                'autocomplete' => 'off',
            ]) !!} --}}
            {{ html()->form('POST')->route('register')->attributes([
                    'id' => 'member-registration',
                    'class' => 'user',
                    'files' => true,
                    'autocomplete' => 'off',
                ])->open() }}

            @include('auth.register-form')

            </form>

            @if (Route::has('password.request') or Route::has('login'))
              <hr>
              @if (Route::has('password.request'))
                <div class="text-center">
                  <a class="small" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                  </a>
                </div>
              @endif

              @if (Route::has('login'))
                <div class="text-center">
                  <a class="small" href="{{ route('login') }}">
                    {{ __('Already have an Account? Login!') }}
                  </a>
                </div>
              @endif
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="atlet-promise-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">JANJI ATLET CENTRUM SC</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Saya berjanji telah siap bergabung di CENTRUM SWIMMING CLUB dengan aturan dan pendidikan yang diberikan oleh
          pelatih CENTRUM SC dan juga saya siap bertempur di kejuaraan/ perlombaan yang diikuti dengan ketentuan yang
          dipilihkan oleh pelatih didalam kejuaraan tersebut untuk membangun juga mengembangkan nama CENTRUM SC.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="parent-promise-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">JANJI WALI ATLET CENTRUM SC</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Berjanji memberikan sepenuhnya anak kami tersebut di atas sebagai atlet CENTRUM SC dan telah siap dijadikan
          atlet terbaik dan kami juga siap untuk mengikuti aturan-aturan CENTRUM SC. Kami selaku wali atlet tidak berhak
          ikut didalam kepelatihan Club ataupun didalam pembinaan. Apabila dikemudian hari terjadi permasalahan, kami siap
          mengikuti aturan yang berlaku di dalam club CENTRUM SC.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="tatib-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">TATA TERTIB CENTRUM SC</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <ol>
            <li>Latihan diadakan setiap hari sesuai dengan jadwal yang telah ditentukan.</li>
            <li>Perenang harus selalu hadir tepat waktu, kecuali dengan ijin pelatih.</li>
            <li>Peralatan semua disediakan sendiri oleh atlit.</li>
            <li>Atlit yang tidak dapat hadir harus melaporkan kepada pelatih atau pengurus.</li>
            <li>Uang iuran (SPP) dibayar paling lambat tanggal 5 setiap bulannya.</li>
            <li>Orang tua tidak diperkenankan ikut campur dalam memberikan materi latihan, mengatur jalannya
              latihan, dll.</li>
            <li>Segala masalah yang terjadi antara pelatih dan atlit diselesaikan dengan jalan musyawarah.
            </li>
            <li>Selama jam latihan atlit tidak diperkenankan meninggalkan kolam tanpa seijin pelatih maupun
              pengurus lainnya.</li>
            <li>Setiap kelas terdapat 1 penanggung jawab / pelatih.</li>
            <li>Evaluasi atlet diadakan setiap 3 bulan sekali paling lambat saat giat atlet per akhir tahun,
              sebagai bahan evaluasi atlet akan dilampirkan raport.</li>
            <li>Setiap setahun sekali atlet, wali atlet dan coach WAJIB MENGIKUTI GIAT ATLET sebagai ajang
              evaluasi tahunan dan silaturrahmi warga club.</li>
            <li>Atlet bersedia di daftarkan NISDA/NISNAS mengingat Perlunya Memiliki NISDA/NISNAS (Nomor Induk
              Siswa Daerah/Nasional) Sebagai Syarat Untuk Mengikuti Kejuaraan Yang Diadakan PRSI Jatim Dan
              Pusat.</li>
            <li>Atlit Yang Akan Keluar Dari Perkumpulan Harus Memberitahukan Terlebih Dahulu Kepada Pelatih
              Dan Pengurus (Mengurus Mutasi Atlit Untuk Pindah Club) Atau Surat Pengunduran Diri Bagi Yang
              Tidak Melanjutkan, Dan Menyelesaikan Administrasi Club Yang Tertunggak (Kas & Sosial), Apabila
              terdapat kompensasi Atlet Yang harus diselesaikan, maka harus Dilakukan Tanpa Ada Paksaan Dari
              Pihak Manapun.</li>
            <li>Penyelesaian Terkait Kompensasi Dilakukan berdasarkan Peraturan AQUATIK (bab kompensasi atlet
              ) yang di musyawarakan sehingga tidak merugikan Salah Satu Pihak.</li>
            <li>Semua Peraturan Berasaskan Pada Aturan PRSI (Persatuan Renang Seluruh Indonesia).</li>
            <li>Hal-Hal Yang Belum Tercantum Dalam Tata Tertib Ini Akan Diberitahukan Lebih Lanjut.</li>
          </ol>
          <p>Mohon untuk dibaca lebih detail serta tanyakan kepada pelatih apabila ada yang kurang dimengerti
            sehingga tidak ada kesalahpahaman dikemudian hari.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('css')
  @include('auth.register-css')
@endpush

@push('js')
  @include('auth.register-js')
@endpush
