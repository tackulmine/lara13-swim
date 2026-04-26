@extends('layouts.print')

@section('content')
  @php
    $periodeStart = explode('-', request()->periode_start);
    $periodeEnd = explode('-', request()->periode_end);
  @endphp

  <center class="mb-5">
    <h2>Rapor {{ __('Atlet') }}</h2>
    <h3><span id="chart-label">{{ $user->name }}</span></h3>
    <h4><span id="chart-title">{{ $gaya->name }}</span></h4>
    <h5>Periode {!! parseBetweenDateCustom(
        date($periodeStart[1] . '-' . $periodeStart[0] . '-01'),
        date($periodeEnd[1] . '-' . $periodeEnd[0] . '-01'),
    ) !!}</h5>
    <hr>
  </center>

  <h5>Profil {{ __('Atlet') }}</h5>
  @include($baseRouteName . '_result-atlit')

  <h5>Tabel Limit</h5>
  @include($baseRouteName . '_result-table')

  <h5>Grafik Limit</h5>
  @include($baseRouteName . '_result-chart')
@endsection

@push('css')
  {{-- @include($baseRouteName . '_css') --}}
@endpush
@push('js')
  @include($baseRouteName . '_js')
@endpush
