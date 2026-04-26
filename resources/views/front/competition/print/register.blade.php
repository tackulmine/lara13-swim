@extends('layouts.print-normal')

@php
  $registrationSchool = \Cookie::get('registration_school');
  $registrationCoachName = \Cookie::get('registration_coach_name');
  $registrationCoachPhone = \Cookie::get('registration_coach_phone');
@endphp

@section('content')
  <!-- Page Heading -->
  <div class="media align-items-center mb-4">
    <div class="media-left">
      {!! $event->preview_photo !!}
    </div>
    <div class="media-body">
      <div class="text-center">
        <h1 class="h3 my-2">{{ $event->name }}</h1>
        @if (!empty($event->location))
          <address class="h6 my-2">
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

  <h4 class="text-center mb-0">List Pendaftaran Atlet</h4>
  <hr class="mt-0 mb-1" style="border:0; border-bottom: 1px solid #000000;">

  <p>{{ __('Sekolah') }}: {{ $registrationSchool }}
    <br>
    Nama Pelatih: {{ $registrationCoachName }}
    <br>
    Nomor Pelatih: {{ cleanPhoneNumber($registrationCoachPhone) }}
  </p>

  @include('front.competition.print._register-list')
@endsection
