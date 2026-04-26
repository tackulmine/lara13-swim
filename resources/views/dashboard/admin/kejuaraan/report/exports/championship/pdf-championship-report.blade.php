<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}

  @include($baseViewPath . 'exports/championship/pdf-championship-css')
</head>

@php
  $periodeStart = explode('-', request()->periode_start);
  $periodeEnd = explode('-', request()->periode_end);
@endphp

<body>

  <!-- Define header and footer blocks before your main content -->
  <header>
    {{-- <h1>Your Company Header</h1> --}}
    {{-- You can also include dynamic data here --}}
    <h4 style="margin:0;padding:0;">Rapor {{ __('Atlet') }}</h4>
    <h5>Periode {!! parseBetweenDateCustom(
        date($periodeStart[1] . '-' . $periodeStart[0] . '-01'),
        date($periodeEnd[1] . '-' . $periodeEnd[0] . '-01'),
    ) !!}</h5>
  </header>

  <footer>
    <div class="pagenum"></div>
  </footer>

  <!-- The watermark div (background image) -->
  <div id="watermark"></div>

  <div class="content">

    {{-- <center class="mb-3">
      <h4 style="margin:0;padding:0;">Rapor {{ __('Atlet') }}</h4>
      <h3 style="margin:0;padding:0;"><span id="chart-label">{{ $user->name }}</span></h3>
      <h4 style="margin:0;padding:0;"><span id="chart-title">{{ $gaya->name }}</span></h4>
      <h5 style="margin:0;padding:0;">Periode {!! parseBetweenDateCustom(
          date($periodeStart[1] . '-' . $periodeStart[0] . '-01'),
          date($periodeEnd[1] . '-' . $periodeEnd[0] . '-01'),
      ) !!}</h5>
      <hr>
    </center> --}}

    @foreach ($userChampionships->groupBy('master_championship_gaya_id') as $masterGayaId => $userChampionshipGroups)
      <h6 class="text-underline">Profil {{ __('Atlet') }}</h6>
      @include($baseRouteName . '_result-atlit')

      <h6 class="text-underline">Tabel Limit</h6>
      @include($baseRouteName . '_result-table')

      <h6 class="text-underline">Grafik Limit</h6>
      @include($baseRouteName . '_result-chart-image')

      @if ($loop->remaining > 0)
        <div class="page-break"></div>
      @endif
    @endforeach
  </div>

  {{-- Here's the magic. This MUST be inside body tag. Page count / total, centered at bottom of page --}}
  <script type="text/php">
    if (isset($pdf)) {
      $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
      $size = 9;
      $font = $fontMetrics->getFont("Bahnschrift");
      $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
      $x = ($pdf->get_width() - $width) / 2;
      $y = $pdf->get_height() - 35;
      $color = [0.17, 0.17, 0.17];
      $pdf->page_text($x, $y, $text, $font, $size, $color);
    }
  </script>
  {{-- page_text(
    float $x,
    float $y,
    string $text,
    string $font,
    float $size,
    array $color = [0, 0, 0], //$color: An array of RGB values as floats between 0 and 1 (e.g., [0, 0, 0] for black, [1, 1, 1] for white).
    float $word_space = 0.0,
    float $char_space = 0.0,
    float $angle = 0.0
  ) --}}
</body>

</html>
