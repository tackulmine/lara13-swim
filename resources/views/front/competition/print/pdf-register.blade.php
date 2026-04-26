<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}

  <style type="text/css">
    {{ file_get_contents(public_path('assets/back/css/bootstrap-v4.0.0.min.css')) }} @font-face {
      font-family: 'Bahnschrift';
      src: url('{{ asset('assets') }}/fonts/Bahnschrift.eot');
      src: url('{{ asset('assets') }}/fonts/Bahnschrift.eot?#iefix') format('embedded-opentype'),
        url('{{ asset('assets') }}/fonts/Bahnschrift.woff2') format('woff2'),
        url('{{ asset('assets') }}/fonts/Bahnschrift.woff') format('woff'),
        url('{{ asset('assets') }}/fonts/Bahnschrift.ttf') format('truetype'),
        url('{{ asset('assets') }}/fonts/Bahnschrift.svg#Bahnschrift') format('svg');
      font-weight: normal;
      font-style: normal;
      font-display: swap;
    }

    body {
      font-family: Bahnschrift;
      font-size: .8rem;
    }

    .table td,
    .table th {
      border-color: #000000;
      vertical-align: baseline;
    }

    .table th {
      font-weight: 500;
    }

    .table tr.heading th {
      border-bottom-width: 2px;
    }

    .table-sm td,
    .table-sm th {
      padding-top: .1rem;
      padding-bottom: .1rem;
    }

    .table-borderless td,
    .table-borderless th {
      border-top: none;
      border-bottom: none;
    }

    .table-borderless th {
      border-bottom: 1px solid #000000;
    }
  </style>
</head>

<body>

  @php
    $registrationSchool = \Cookie::get('registration_school');
    $registrationCoachName = \Cookie::get('registration_coach_name');
    $registrationCoachPhone = \Cookie::get('registration_coach_phone');
  @endphp

  {{-- <div id="print-area"> --}}

  @if (request()->filled('view_only'))
    <table class="w-100">
      <tr>
        <td width="20%" class="text-left">
          {!! $event->preview_photo !!}
        </td>
        <td class="text-center" style="vertical-align: middle;">
          <h5 class="mb-0">{{ $event->name }}</h5>
          @if (!empty($event->location))
            <address class="h6 mb-0">
              </i> {{ $event->location }}
            </address>
          @endif
          <h6>{!! parseBetweenDate($event->start_date, $event->end_date, 'F') !!}</h6>
          <h4 style="font-weight: bold;">LIST PENDAFTARAN</h4>
        </td>
        <td width="20%" class="text-right">
          {!! $event->preview_photo_right !!}
        </td>
      </tr>
    </table>
  @endif

  {{-- <h4 class="text-center">List Pendaftaran Atlet</h4> --}}

  @php
    $totalTypesTagihan = 0;
    $totalIndividualTagihan = 0;
    $totalEstafetTagihan = 0;
    if (!empty($eventRegistrations)):
        foreach ($eventRegistrations as $eventRegistration):
            $relay = $eventRegistration->types->filter(function ($type) use ($eventRegistration) {
                return Str::contains($eventRegistration->masterMatchCategory->name, 'RELAY');
            });
            $type = count($relay) ? 'relay' : 'normal';
            $totalTypesTagihan += $tagihan = $eventRegistration->getTotalTagihan($type);
            if($type == 'normal') $totalIndividualTagihan += $tagihan;
            if($type == 'relay') $totalEstafetTagihan += $tagihan;
        endforeach;
    endif;
  @endphp

  @if ($eventIndividualRegistrations->isNotEmpty())
    <h6>{{ __('Individu') }}</h6>
    @include('front.competition.print._register-list', ['eventRegistrations' => $eventIndividualRegistrations])
  @endif
  @if ($eventEstafetRegistrations->isNotEmpty())
    <h6>{{ __('Estafet') }}</h6>
    @include('front.competition.print._register-list', ['eventRegistrations' => $eventEstafetRegistrations])
  @endif

  <hr class="mt-0 mb-2" style="border:0; border-bottom: double #000000;">

  <table class="mb-2">
    <tbody>
      <tr>
        <td>{{ __('Sekolah') }}</td>
        <td>&nbsp;: &nbsp;</td>
        <td>{{ $registrationSchool }}</td>
      </tr>
      <tr>
        <td>Nama Pelatih</td>
        <td>&nbsp;: &nbsp;</td>
        <td>{{ $registrationCoachName }}</td>
      </tr>
      <tr>
        <td>Nomor Pelatih</td>
        <td>&nbsp;: &nbsp;</td>
        <td>{{ cleanPhoneNumber($registrationCoachPhone) }}</td>
      </tr>
      @if ($eventIndividualRegistrations->isNotEmpty())
        <tr>
          <td>Tagihan {{ __('Individu') }}</td>
          <td>&nbsp;: &nbsp;</td>
          <td class="text-right"><strong>{{ numberFormatIdn($totalIndividualTagihan) }}</strong></td>
        </tr>
      @endif
      @if ($eventEstafetRegistrations->isNotEmpty())
        <tr>
          <td>Tagihan {{ __('Estafet') }}</td>
          <td>&nbsp;: &nbsp;</td>
          <td class="text-right"><strong>{{ numberFormatIdn($totalEstafetTagihan) }}</strong></td>
        </tr>
      @endif
      <tr>
        <td>Total Tagihan</td>
        <td>&nbsp;: &nbsp;</td>
        <td class="text-right"><strong style="font-size: large">{{ numberFormatIdn($totalTypesTagihan) }}</strong></td>
      </tr>
    </tbody>
  </table>

  {{-- </div> --}}
</body>

</html>
