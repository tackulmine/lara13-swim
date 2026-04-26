@extends('layouts.front')

@php
  $isEventStarted = $event->start_date->toDateString() < now();
  $isAllStagesCompleted = empty($event->eventStages()->where('completed', false)->count());
@endphp

@section('content')
  <div style="font-size: .8rem">

    <!-- Page Heading -->
    <div class="media align-items-center mb-2">
      <div class="media-left">
        {!! $event->preview_photo !!}
      </div>
      <div class="media-body">
        <div class="text-center">
          <h1 class="h4 my-2 text-gray-800">{{ $event->name }}</h1>
          <p class="h6 mb-2">
            {!! parseBetweenDate($event->start_date, $event->end_date) !!}
          </p>
          <p class="mb-2">{{ $event->location }}</p>
          {{-- <p class="mb-2">{{ $event->description }}</p> --}}
          {{-- @if (!empty($eventStage))
            <p>
              Acara: <strong>{{ str_pad($eventStage->number, 3, 0, STR_PAD_LEFT) }}.
                {{ $eventStage->masterMatchType->name }}</strong> &nbsp;|&nbsp;
              {{ __('Kategori') }}: <strong>{{ $eventStage->masterMatchCategory->name }}</strong>
            </p>
          @endif --}}
        </div>
      </div>
      <div class="media-right d-none d-md-inline-block">
        {!! $event->preview_photo_right !!}
      </div>
    </div>

    @if (auth()->check() || (!auth()->check() && $isEventStarted && !$event->completed))
      @if (!empty($eventStage))
        <p class="lead text-center">
          Acara: <strong>{{ str_pad($eventStage->number, 3, 0, STR_PAD_LEFT) }}.
            {{ $eventStage->masterMatchType->name }}</strong> &nbsp;|&nbsp;
          {{ __('Kategori') }}: <strong>{{ $eventStage->masterMatchCategory->name }}</strong>
        </p>
      @endif
    @endif

    <!-- Content Row -->
    {{-- <div class="row"> --}}
    <div class="card-deck">

      <!-- Check if event is active -->
      @if (!auth()->check() && ($event->completed || !$isEventStarted))
        <div class="card shadow">
          <div class="card-body">
            <div class="alert alert-warning mb-0">
              @if ($event->completed)
                Kompetisi Telah Berakhir!
              @elseif (!$isEventStarted)
                Acara masih belum berlangsung!
              @endif
            </div>
          </div>
        </div>
      @else
        <!-- First Column -->
        {{-- <div class="col-lg-4"> --}}

        <div class="card shadow mb-4">
          <div class="card-header p-2 bg-success">
            <h6 class="m-0 font-weight-bold text-white">
              @if ($event->completed)
                Kompetisi Telah Berakhir
              @elseif ($isAllStagesCompleted)
                Semua Acara Berakhir
              @elseif (!empty($eventSession) && empty($eventSession->completed))
                Sedang Berlangsung
                <span class="float-right">
                  (Seri: {{ $eventSession->session }})
                </span>
              @else
                Semua Seri di Acara ini Berakhir
              @endif
            </h6>
          </div>
          <div class="card-body p-2 bg-dark text-white">
            @include('front.competition._left')
          </div>
        </div>

        {{-- </div> --}}

        <!-- Second Column -->
        {{-- <div class="col-lg-4"> --}}

        <!-- Background Gradient Utilities -->
        <div class="card shadow mb-4">
          <div class="card-header p-2 bg-info">
            <h6 class="m-0 font-weight-bold text-white">Terjadwal</h6>
          </div>
          <div class="card-body p-2 bg-dark text-white">
            @include('front.competition._mid')
          </div>
        </div>

        {{-- </div> --}}

        <!-- Third Column -->
        {{-- <div class="col-lg-4"> --}}

        <!-- Grayscale Utilities -->
        <div class="card shadow mb-4">
          <div class="card-header p-2 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Ranking</h6>
          </div>
          <div class="card-body p-2 bg-dark text-white">
            @include('front.competition._right')
          </div>
        </div>

        {{-- </div> --}}
      @endif

    </div>

  </div>
@endsection

@push('css')
  {{-- expr --}}
@endpush

@guest
  {{-- @if (!$event->completed) --}}
  @if (!$event->completed && $isEventStarted)
    @push('meta')
      <meta http-equiv="refresh" content="5" />
    @endpush
    @push('copyright-after')
      <div class="mt-1"><small><em>Auto-refresh in 5 seconds.</em></small></div>
    @endpush
  @endif
@endguest

@auth
  @push('css')
    <style>
      .custom-control-label {
        line-height: 1.5rem;
      }
    </style>
  @endpush
  @push('js')
    <script>
      $(function() {
        $(document).on('change', '#competition-form input[type="radio"]', function(e) {
          e.preventDefault();
          var $parent = $(this).closest('.card');
          var labelText = $(this).parent().find('label').text().trim();
          var participantName = $parent.find('.participant-name').text().trim();
          var confirmText = 'Yakin mengubah nilai ' + participantName + ' ke ' + labelText + '?';
          if (parseInt($(this).val()) > 0 &&
            ($parent.find('.input-mask-time').val() != '00:00.010')
          ) {
            if (confirm(confirmText)) {
              $parent.find('.input-mask-time').val('00:00.010');
              $(this).prop('checked', true);
            } else {
              $parent.find('input[type="radio"]').first().prop('checked', true);
              $(this).prop('checked', false);
            }
          } else {
            if (parseInt($(this).val()) == 0 && $parent.find('.input-mask-time').val() != '00:00.000') {
              $parent.find('.input-mask-time').val('');
            }
          }
        });
      });
    </script>
  @endpush
@endauth
