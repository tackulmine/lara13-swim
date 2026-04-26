@extends('layouts.print')

@section('content')
  <center class="mb-5">
    <h2 class="mt-2">Identitas {{ __('Atlet') }}</h2>
    {{-- <h3>{{ $member->name }}</h3> --}}
    @if (!empty(optional($member->userMember)->nis))
      <h4>{{ optional($member->userMember)->nis }}</h4>
    @endif
    <h5>{{ config('app.name') }}</h5>
    <hr>
  </center>

  @include($baseViewPath . '_show')

  <br>
  <br>

  <div class="row">
    <div class="col-md-6 offset-md-6">
      <center>
        <p>TTD<br>
          Ketua perkumpulan renang <br>
          {{ config('app.name') }}</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>{{ $coach->name }}</p>
      </center>
    </div>
  </div>
@endsection

@push('css')
  {{-- @include($baseRouteName . '_css') --}}
  <style>
    @media print {
      .container-bg {
        padding: 1rem;
        background:
          linear-gradient(white, white) padding-box,
          linear-gradient(to bottom, darkgray, lightgray) border-box;
        border: 3px solid transparent;
        border-radius: .2rem 2rem;
        height: 99vh;
      }
    }
  </style>
@endpush
@push('js')
  {{-- @include($baseViewPath . '_js') --}}
@endpush
