@extends('layouts.app')

@section('content')
  @include('layouts.partials._breadcrumbs')

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">

        @include('layouts.partials._notif')

        <table class="table table-striped table-bordered" id="dataTableCustom" data-order="[[ 1, &quot;asc&quot; ]]"
          width="100%" cellspacing="0">
          <thead>
            @include($baseViewPath . '_school-table-head')
          </thead>
          <tfoot>
            @include($baseViewPath . '_school-table-head')
          </tfoot>
          <tbody>
            @php
              $i = 1;
            @endphp
            @forelse ($schools as $school)
              <tr>
                <td class="no">{{ $i++ }}</td>
                <td>{{ $school->name ?? '-' }}</td>
                <td class="text-right">{{ $school->master_participants_count ?? '-' }}</td>
                @if (isset($totalTagihan[$school->id]))
                  <td class="text-right" data-order="{{ $totalTagihan[$school->id] }}"
                    data-search="{{ $totalTagihan[$school->id] }}">
                    {{ numberFormatIdn($totalTagihan[$school->id]) }}
                  </td>
                @else
                  <td class="text-right">0</td>
                @endif
              </tr>
            @empty
              <tr>
                <td colspan="100" align="center">Data kosong!</td>
              </tr>
            @endforelse
          </tbody>
        </table>

      </div>
    </div>
    <div class="card-footer text-center">
      <a href="{{ route($baseRouteName . 'school', $event) }}?download=1" class="btn btn-success">
        <i class="fas fa-fw fa-download"></i> Download semua
      </a>
      <a href="{{ route($baseRouteName . 'index', $event) }}" class="btn btn-info">
        <i class="fas fa-fw fa-swimming-pool"></i> Lihat semua nomor {{ __('Gaya') }}
      </a>
      <a href="{{ route($baseRouteName . 'atlet', $event) }}" class="btn btn-info">
        <i class="fas fa-fw fa-users"></i> Lihat dalam daftar Peserta
      </a>
    </div>
  </div>
@endsection

{{-- @push('css')
@endpush --}}

@push('js')
  @include($baseViewPath . '._school-js')
@endpush
