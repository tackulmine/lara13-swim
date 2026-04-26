@extends('layouts.front')

@php
  if ($event->eventSpecialTypes->isNotEmpty()) {
      $registrationAs = \Cookie::get('registration_as');
  }
  $registrationSchool = \Cookie::get('registration_school');
  $registrationCoachName = \Cookie::get('registration_coach_name');
  $registrationCoachPhone = \Cookie::get('registration_coach_phone');
  $registrationQuota = $event->eventRegistrations()->count();
@endphp

@section('content')
  <!-- Page Heading -->
  <div class="media align-items-center mb-4">
    <div class="media-left">
      {!! $event->preview_photo !!}
    </div>
    <div class="media-body">
      <div class="text-center">
        <h1 class="h3 my-2 text-gray-800">{{ $event->name }}</h1>
        @if (!empty($event->location))
          <address class="h6 my-2 text-gray-800">
            <i class="fas fa-map-marker-alt"></i> {{ $event->location }}
          </address>
        @endif
        <time class="h6 mb-2">
          <i class="fas fa-calendar-alt"></i> {!! parseBetweenDate($event->start_date, $event->end_date, 'F') !!}
        </time>
        {{-- <p class="mb-2">{{ $event->description }}</p> --}}
        @if (!empty($eventStage))
          <p>
            Acara: <strong>{{ str_pad($eventStage->number, 3, 0, STR_PAD_LEFT) }}.
              {{ $eventStage->masterMatchType->name }}</strong> &nbsp;|&nbsp;
            {{ __('Kategori') }}: <strong>{{ $eventStage->masterMatchCategory->name }}</strong>
          </p>
        @endif
      </div>
    </div>
    <div class="media-right d-none d-md-inline-block">
      {!! $event->preview_photo_right !!}
    </div>
  </div>

  <!-- Content Row -->
  <div class="row">

    <!-- Form -->
    <div class="col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
      <div class="card shadow mb-4">
        <div class="card-header p-3">
          <h5 class="m-0 font-weight-bold">
            Form Pendaftaran
            @if (!empty($event->reg_quota))
              <small class="float-right text-muted">
                Kuota: {{ $registrationQuota }}/{{ $event->reg_quota }}
              </small>
            @endif
          </h5>
        </div>
        @if (!empty($event->reg_end_date) && now() >= $event->reg_end_date->toDateString() && !auth()->check())
          <div class="card-body p-3">
            @include('layouts.partials._notif')

            <div class="alert alert-primary" role="alert">
              Maaf, pendaftaran telah ditutup.
            </div>
          </div>
        @elseif (!empty($event->reg_quota) && $registrationQuota >= $event->reg_quota)
          <div class="card-body p-3">
            @include('layouts.partials._notif')

            <div class="alert alert-primary" role="alert">
              Maaf, pendaftaran telah mencapai kuota.
            </div>
          </div>
        @else
          {!! Form::open([
              'route' => ['competition.register-submit', $event->slug],
              // 'class' => 'needs-validation',
              // 'novalidate' => true,
              'id' => 'event-registration',
              'files' => true,
              'autocomplete' => 'off',
          ]) !!}
          <div class="card-body p-3">
            {{-- TESTING ONLY PURPOSE --}}
            {{-- @if (now()->addMinute(1)->format('Y-m-d H:i') == date('Y-m-d H:i', strtotime(config('general.event_registration_end_date')))) --}}
            @if (!empty($event->reg_end_date) && now()->addDay(1)->toDateString() == $event->reg_end_date->toDateString())
              {{-- COUNTDOWN --}}
              <div class="alert alert-warning text-center" role="alert">
                Pendaftaran berakhir dalam waktu <br>
                <strong data-countdown="{{ $event->reg_end_date->toDateString() }}"></strong>
              </div>
            @endif

            @if (!request()->filled('search_coach_name'))
              @include('layouts.partials._notif')
            @endif

            @include('front.competition._register-form')
          </div>
          <div class="card-footer p-3 text-center">
            <button type="submit" class="btn btn-outline-success">
              <i class="fas fa-paper-plane"></i>
              Submit Pendaftaran
            </button>
          </div>
          {{ html()->form()->close() }}
        @endif
      </div>
    </div>

    <!-- List -->
    <div class="@if (empty($registrationSchool) && empty($registrationCoachName)) col-lg col-xl-8 offset-xl-2 col-xxl-6 offset-xxl-3 @else col @endif">
      {!! Form::open([
          'route' => ['competition.register', $event->slug],
          // 'class' => 'needs-validation',
          // 'novalidate' => true,
          'id' => 'event-registration-search',
          // 'files' => true,
          'method' => 'get',
          'autocomplete' => 'off',
      ]) !!}
      <div class="card shadow mb-4">
        <div class="card-header p-3">
          <h5 class="m-0 font-weight-bold">
            List Pendaftaran Atlet
          </h5>
        </div>

        <div class="card-body p-3">
          @include('layouts.partials._search-notif')

          @if (empty($registrationSchool) && empty($registrationCoachName))
            @include('front.competition._register-list-form')
          @else
            @include('front.competition._register-list')
          @endif
        </div>

        <div class="card-footer p-3 text-center">
          @if (empty($registrationSchool) && empty($registrationCoachName))
            <button type="submit" class="btn btn-outline-info">
              <i class="fas fa-search"></i>
              Cari Pendaftaran
            </button>
          @else
            <a href="?download=1" target="_blank" class="btn btn-outline-info">
              <i class="fas fa-download"></i>
              Download Pendaftaran
            </a>
          @endif
        </div>
      </div>
      {{ html()->form()->close() }}
    </div>

  </div>
@endsection

@push('css')
  {{-- @if (!empty($registrationCoachName) && !empty($registrationCoachPhone))
  @endif --}}
@endpush

{{-- @guest
    @if (!$event->completed)
        @push('meta')
            <meta http-equiv="refresh" content="5" />
        @endpush
        @push('copyright-after')
            <div class="mt-1"><small><em>Auto-refresh in 5 seconds.</em></small></div>
        @endpush
    @endif
@endguest --}}

@push('js')
  @include('front.competition._register-js')
@endpush
