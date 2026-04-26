<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}

  <style type="text/css">
    {{ file_get_contents(public_path('assets/back/css/bootstrap-v4.0.0.min.css')) }} @font-face {
      font-family: 'Bahnschrift';
      src: url({{ storage_path('fonts/Bahnschrift.ttf') }}) format("truetype");
      font-weight: normal;
      font-style: normal;
    }

    body {
      font-family: "Bahnschrift", sans-serif;
      font-size: .9rem;
      line-height: 1rem;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .h1,
    .h2,
    .h3,
    .h4,
    .h5,
    .h6 {
      line-height: 1;
    }

    .table td,
    .table th {
      border-color: #000000;
      vertical-align: baseline;
    }

    .table th {
      font-weight: 500;
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

    /** Define the margins of your page **/
    @page {
      margin: 230px 50px 70px 50px;
    }

    header {
      position: fixed;
      top: -210px;
      left: 0px;
      right: 0px;
      height: 100px;
      text-align: center;
      line-height: 1rem;
    }

    footer {
      position: fixed;
      bottom: -40px;
      left: 0px;
      right: 0px;
      height: 50px;
      text-align: center;
      font-size: smaller;
    }
  </style>
</head>

<body>
  <!-- Define header and footer blocks BEFORE main content -->
  <header>
    <div class="pt-2 pb-3">
      <table class="w-100">
        <tr>
          <td width="20%" class="text-left">
            {{-- {!! $event->preview_photo !!} --}}
          </td>
          <td class="text-center" style="vertical-align: middle;">
            <h5 class="mb-0">{{ $event->name }}</h5>
            <h6 class="mb-0">{{ $event->location }}</h6>
            <h6>{!! parseBetweenDate($event->start_date, $event->end_date, 'F') !!}</h6>
            <h4 style="font-weight: bold;">BUKU ACARA</h4>
          </td>
          <td width="20%" class="text-right">
            {{-- {!! $event->preview_photo_right !!} --}}
          </td>
        </tr>
      </table>
    </div>
  </header>

  <footer>
    {{-- Copyright © <?php echo app()->name(); ?> <?php echo date('Y'); ?> --}}
    @if (!empty($event->is_has_copyright) && !empty($event->copyright_text))
      &copy; {{ $event->copyright_text }}
    @elseif (empty($event->is_has_copyright))
      &copy; {{ config('app.name') }}
    @endif
  </footer>

  <!-- Main Content -->
  <main>

    @if (request()->filled('view_only'))
      <table class="w-100">
        <tr>
          <td width="20%" class="text-left">
            {!! $event->preview_photo !!}
          </td>
          <td class="text-center" style="vertical-align: middle;">
            <h5 class="mb-0">{{ $event->name }}</h5>
            <h6 class="mb-0">{{ $event->location }}</h6>
            <h6>{!! parseBetweenDate($event->start_date, $event->end_date) !!}</h6>
            <h4 style="font-weight: bold;">BUKU ACARA</h4>
          </td>
          <td width="20%" class="text-right">
            {!! $event->preview_photo_right !!}
          </td>
        </tr>
      </table>
      {{-- <p>&nbsp;</p> --}}
    @endif

    @foreach ($eventStages as $eventStage)
      <table class="table table-sm table-borderless mb-0">
        <tr style="font-size: larger;">
          <th style="font-size: x-large;">Acara {{ $eventStage->number_format }}</th>
          <th width="37%">{{ strtoupper($eventStage->masterMatchType->name) }}</th>
          <th width="20%" class="text-right">{{ $eventStage->masterMatchCategory->name }}</th>
        </tr>
      </table>
      <hr style="border:0; border-bottom: 1px solid #000000;" class="mt-0 mb-1">
      {{-- <table class="table table-sm table-bordered"> --}}
      {{-- <tdead> --}}
      {{-- <tr>
      <td colspan="3" style="vertical-align: middle;"><strong style="font-size: x-large;">Acara
          {{ $eventStage->number_format }}.</strong>
        {{ strtoupper($eventStage->masterMatchType->name) }}</td>
      <td colspan="10" style="vertical-align: middle;" class="text-right">{{ __('Kategori') }}
        {{ $eventStage->masterMatchCategory->name }}</td>
    </tr> --}}
      {{-- <tr>
      <td width="5%" class="text-center">{{ __('Seri') }}</td>
      <td class="text-center">{{ __('Lint') }}</td>
      <td width="30%">{{ __('Nama Lengkap Atlet') }}</td>
      <td width="7%" class="text-center">{{ __('Lahir') }}</td>
      <td width="33%">{{ __('Sekolah') }}</td>
      <td width="10%" class="text-center">{{ __('Prestasi') }}</td>
      <td width="10%" class="text-center">{{ __('Hasil') }}</td>
    </tr> --}}
      {{-- </thead> --}}
      {{-- <tbody> --}}
      @foreach ($eventStage->eventSessions as $eventSession)
        {{-- @foreach ($eventSession->eventSessionParticipants as $participant)
        <tr>
          @if ($loop->index == 0)
            <td class="text-center" rowspan="{{ $eventSession->event_session_participants_count }}"
              style="vertical-align: middle;">{!! $eventSession->session !!}</td>
          @endif
          <td class="text-center">{!! $participant->track !!}</td>
          <td>{{ $participant->masterParticipant->masterSchool->name ?? '-' }}</td>
          <td class="text-center">{{ optional($participant->masterParticipant)->birth_year }}</td>
          <td>{{ $participant->masterParticipant->masterSchool->name ?? '-' }}</td>
          <td class="text-center">
            {{ optional(optional($participant->masterParticipant->styles->where('id', $eventStage->masterMatchType->id)->first())->pivot)->point_text ?? 'NT' }}
          </td>
          <td></td>
        </tr>
      @endforeach --}}
        {{-- @for ($i = $minTrack; $i <= $maxTrack; $i++)
        @php
          $participant = $eventSession->eventSessionParticipants->where('track', $i)->first() ?? null;
        @endphp
        <tr>
          @if ($i == $minTrack)
            <td class="text-center" rowspan="{{ $maxTrack }}" style="vertical-align: middle;">
              {!! $eventSession->session !!}</td>
          @endif
          <td class="text-center">{!! $i !!}</td>
          @if ($participant && $participant->track == $i)
            <td>{{ strtoupper(optional($participant->masterParticipant)->name) }}</td>
            <td class="text-center">{{ optional($participant->masterParticipant)->birth_year }}</td>
            <td>{{ $participant->masterParticipant->masterSchool->name ?? '-' }}</td>
            <td class="text-center">
              {{ optional(optional($participant->masterParticipant->styles->where('id', $eventStage->masterMatchType->id)->first())->pivot)->point_text ?? 'NT' }}
            </td>
          @else
            <td>-</td>
            <td class="text-center">-</td>
            <td>-</td>
            <td class="text-center">-</td>
          @endif
          <td></td>
        </tr>
      @endfor --}}
        <table class="table table-sm table-borderless mb-1">
          {{-- <thead> --}}
          <tr>
            <th colspan="6">{{ __('Seri') }} {{ $eventSession->session }}</th>
          </tr>
          <tr>
            {{-- <th width="5%" class="text-center">{{ __('Seri') }}</th> --}}
            <th width="5%" class="text-center">{{ __('Lint') }}</th>
            <th>{{ __('Nama Lengkap Atlet') }}</th>
            <th width="7%" class="text-center">{{ __('Lahir') }}</th>
            <th width="30%">{{ __('Sekolah') }}</th>
            <th width="10%" class="text-center">{{ __('Prestasi') }}</th>
            <th width="10%" class="text-center">{{ __('Hasil') }}</th>
          </tr>
          {{-- </thead> --}}
          {{-- <tbody> --}}
          @for ($i = $minTrack; $i <= $maxTrack; $i++)
            {{-- @for ($i = $minTrack; $i <= $maxTracks[$eventStage->id]; $i++) --}}
            @php
              $participant = $eventSession->eventSessionParticipants->where('track', $i)->first() ?? null;
            @endphp
            <tr>
              {{-- @if ($i == $minTrack)
              <td class="text-center" rowspan="{{ $maxTrack }}" style="vertical-align: middle;">
                {!! $eventSession->session !!}</td>
            @endif --}}
              <td class="text-center">{!! $i !!}</td>
              @if ($participant && $participant->track == $i)
                <td>{{ strtoupper(optional($participant->masterParticipant)->name) }}</td>
                <td class="text-center">{{ optional($participant->masterParticipant)->birth_year }}</td>
                <td>{{ $participant->masterParticipant->masterSchool->name ?? '-' }}</td>
                <td class="text-center">
                  {{ optional(optional($participant->masterParticipant->styles->where('id', $eventStage->masterMatchType->id)->first())->pivot)->point_text ?? 'NT' }}
                </td>
              @else
                <td>-</td>
                <td class="text-center">-</td>
                <td>-</td>
                <td class="text-center">-</td>
              @endif
              <td></td>
            </tr>
          @endfor
          {{-- </tbody> --}}
        </table>
      @endforeach
      {{-- </tbody> --}}
      {{-- </table> --}}
    @endforeach

  </main>

  <script type="text/php">
      if (isset($pdf)) {
          $pdf->page_script('
              $text = __("Page :pageNum/:pageCount", ["pageNum" => $PAGE_NUM, "pageCount" => $PAGE_COUNT]);
              $font = "Bahnschrift";
              $size = 9;
              $color = array(0,0,0);
              $word_space = 0.0;  //  default
              $char_space = 0.0;  //  default
              $angle = 0.0;   //  default

              // Compute text width to center correctly
              $textWidth = $fontMetrics->getTextWidth($text, $font, $size);

              $x = ($pdf->get_width() - $textWidth) - 25;
              $y = $pdf->get_height() - 35;

              $pdf->text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
          ');
      }
  </script>

</body>

</html>
