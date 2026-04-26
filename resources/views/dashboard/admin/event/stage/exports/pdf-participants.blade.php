<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

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

    /* Define page size. Requires print-area adjustment! */
    /* body {
      margin: 0;
      padding: 0;
      width: 21cm;
      height: 29.7cm;
    }

    #print-area {
      position: relative;
      top: 1cm;
      left: 1cm;
      width: 19cm;
      height: 27.6cm;
      font-size: 10px;
      font-family: Arial;
    }

    #header {
      height: 3cm;
      background: #ccc;
    }

    #footer {
      position: absolute;
      bottom: 0;
      width: 100%;
      height: 3cm;
      background: #ccc;
    } */
  </style>
</head>

<body>
  {{-- <div id="print-area"> --}}

  @if (request()->filled('type') && request()->type == 'view_only')
    <table class="w-100">
      <tr>
        <td width="20%" class="text-left">
          {!! $event->preview_photo !!}
        </td>
        <td class="text-center" style="vertical-align: middle;">
          <h5 class="mb-0">{{ $event->name }}</h5>
          <h6 class="mb-0">{{ $event->location }}</h6>
          <h6>{!! parseBetweenDate($event->start_date, $event->end_date) !!}</h6>
          <h4 style="font-weight: bold;">HASIL PERLOMBAAN</h4>
          {{-- <h3>ACARA {{ $eventStage->number_format }}. {{ strtoupper($eventStage->masterMatchType->name) }}
                    </h3>
                    <h4>KATEGORI {{ $eventStage->masterMatchCategory->name }}</h4> --}}
        </td>
        <td width="20%" class="text-right">
          {!! $event->preview_photo_right !!}
        </td>
      </tr>
    </table>
    <p>&nbsp;</p>
  @endif

  <table class="table table-sm table-borderless mb-1">
    {{-- <thead> --}}
    <tr style="font-size: larger;" class="heading">
      <th colspan="2" style="font-size: x-large;">Acara {{ $eventStage->number_format }}</th>
      <th colspan="2">{{ strtoupper($eventStage->masterMatchType->name) }}</th>
      <th colspan="6" class="text-right">{{ $eventStage->masterMatchCategory->name }}</th>
    </tr>
    <tr>
      <th class="text-right">{{ __('Pos') }}</th>
      <th width="30%">{{ __('Nama Lengkap Atlet') }}</th>
      <th width="4%" class="text-center">{{ __('Lahir') }}</th>
      <th width="30%">{{ __('Sekolah') }}</th>
      {{-- <th>ACARA</th>
            <th>TIPE GAYA</th>
            <th>KATEGORI</th> --}}
      <th width="4%" class="text-center">{{ __('Seri') }}</th>
      <th width="4%" class="text-center">{{ __('Lint') }}</th>
      <th width="10%" class="text-center">{{ __('Prestasi') }}</th>
      <th width="10%" class="text-center">{{ __('Final') }}</th>
      <th width="4%" class="text-center">{{ __('Ket') }}</th>
    </tr>
    {{-- </thead> --}}
    {{-- <tbody> --}}
    @php
      $rank = 1;
    @endphp
    @foreach ($participants as $index => $participant)
      @php
        $pointBefore = null;
        if (!empty($participants[$index - 1])) {
            $pointBefore = !empty($participants[$index - 1]->point_decimal)
                ? $participants[$index - 1]->point_decimal
                : $participants[$index - 1]->point;
        }
        if (!empty($pointBefore) && $pointBefore !== $participant->point_decimal) {
            $rank++;
        }
      @endphp
      <tr {{-- @if ($loop->iteration == 1) style="font-size:130%" @elseif($loop->iteration == 2) style="font-size:120%" @elseif($loop->iteration == 3) style="font-size:110%" @endif> --}}>
        <td @if ($rank < 4) style="color: green; font-size: 130%; font-weight: 500;" @endif
          class="text-right">
          {{ $participant->disqualification ? '' : $rank }}
        </td>
        <td>{{ strtoupper(optional($participant->masterParticipant)->name) }}</td>
        <td class="text-center">{{ optional($participant->masterParticipant)->birth_year }}</td>
        <td>{{ $participant->masterParticipant->masterSchool->name ?? '-' }}</td>
        {{-- <td class="text-center">{!! $participant->eventSession->eventStage->number_format !!}</td>
                <td>{{ $participant->eventSession->eventStage->masterMatchType->name }}</td>
                <td>{{ $participant->eventSession->eventStage->masterMatchCategory->name }}</td> --}}
        <td class="text-center">{!! optional($participant->eventSession)->session !!}</td>
        <td class="text-center">{!! $participant->track !!}</td>
        <td class="text-center">
          {{ optional(optional($participant->masterParticipant->styles->where('id', $eventStage->masterMatchType->id)->first())->pivot)->point_text ?? 'NT' }}
        </td>
        <td class="text-center">{!! $participant->disqualification
            ? ($participant->dis_level == 2 || $participant->dis_level == 3
                ? // ? '<del>' . $participant->point_text . '</del>'
                '<del>99:99.99</del>'
                : $participant->point_text)
            : $participant->point_text !!}</td>
        <td class="text-center">{!! $participant->disqualification ? '<strong>' . $participant->dis_level_text . '</strong>' : '' !!}</td>
      </tr>
    @endforeach
    {{-- </tbody> --}}
  </table>

  {{-- </div> --}}

</body>

</html>
