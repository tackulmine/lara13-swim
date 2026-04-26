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
      font-size: .9rem;
    }
  </style>
</head>

<body>
  <div class="pt-2 pb-3">
    <table class="w-100">
      <tr>
        <td width="20%" class="text-left">
          {!! $event->preview_photo !!}
        </td>
        <td class="text-center" style="vertical-align: middle;">
          <h5 class="mb-0">{{ $event->name }}</h5>
          @if (!empty($event->location))
            <h6 class="mb-0">{{ $event->location }}</h6>
          @endif
          <h6>{!! parseBetweenDate($event->start_date, $event->end_date, 'F') !!}</h6>
          <h4 style="font-weight: bold;">LIST PENDAFTARAN</h4>
        </td>
        <td width="20%" class="text-right">
          {!! $event->preview_photo_right !!}
        </td>
      </tr>
    </table>
  </div>
</body>

</html>
