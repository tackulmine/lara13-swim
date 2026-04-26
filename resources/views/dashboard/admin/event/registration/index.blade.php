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
        @php
          $registrationTypes = $event->eventRegistrationTypes;

          $indRegistrations = $event
              ->eventRegistrations()
              ->whereIn('master_match_category_id', $individualCatIds)
              ->get();
          $indRegistrations->load('types')->each(function ($reg) {
              $reg->type_total = $reg->types->count();
          });
          $indRegistrationTotal = $indRegistrations->sum('type_total');

          $estRegistrations = $event->eventRegistrations()->whereIn('master_match_category_id', $estafetCatIds)->get();
          $estRegistrations->load('types')->each(function ($reg) {
              $reg->type_total = $reg->types->count();
          });
          $estRegistrationTotal = $estRegistrations->sum('type_total');

          // dd($indRegistrationTotal, $estRegistrationTotal);

        @endphp
        <p>Total Nomor {{ __('Gaya') }}:
          @if (!request()->filled('individual') && !request()->filled('estafet'))
            <strong>{{ $registrationTypes->count() }}</strong>
          @else
            <a href="{{ route($baseRouteName . 'index', $event) }}">
              <strong>{{ $registrationTypes->count() }}</strong>
            </a>
          @endif
          (
          @if (request()->filled('individual'))
            <strong>{{ $indRegistrationTotal }} {{ __('Individu') }}</strong>
          @else
            <a href="{{ route($baseRouteName . 'index', $event) }}?individual=1">
              <strong>{{ $indRegistrationTotal }}</strong>
            </a> {{ __('Individu') }}
          @endif
          ,
          @if (request()->filled('estafet'))
            <strong>{{ $estRegistrationTotal }} {{ __('Estafet') }} </strong>
          @else
            <a href="{{ route($baseRouteName . 'index', $event) }}?estafet=1">
              <strong>{{ $estRegistrationTotal }}</strong>
            </a> {{ __('Estafet') }}
          @endif
          ).
        </p>

        @include('layouts.partials._notif')

        <form action="{{ route($baseRouteName . 'destroy-batch', $event) }}" method="post" class="mb-4"
          id="participant-form">
          @csrf
          @method('delete')

          <button type="submit" class="btn btn-danger mb-4" data-toggle="tooltip" title="Menghapus baris terpilih">Batch
            Delete</button>

          <div id="dataTableCustom_filters" class="form-inline"><label>Filter: &nbsp;</label></div>

          <table class="table table-striped table-bordered" id="dataTableCustom" data-order="[[ 6, &quot;asc&quot; ]]"
            width="100%" cellspacing="0">
            <thead>
              @include($baseViewPath . '_table-head')
            </thead>
            <tfoot>
              @include($baseViewPath . '_table-head')
            </tfoot>
            <tbody>
              @php
                $i = 1;
              @endphp
              @forelse ($eventRegistrations as $eventRegistration)
                @foreach ($eventRegistration->types as $type)
                  {{-- @dd($type->pivot) --}}
                  <tr>
                    <td>{{ $eventRegistration->id }}_{{ $type->id }}</td>
                    <td class="no">{{ $i++ }}</td>
                    <td>{{ $eventRegistration->masterParticipant->name ?? '-' }}</td>
                    <td>{{ $eventRegistration->masterParticipant->gender_text ?? '-' }}</td>
                    <td>{{ $eventRegistration->masterParticipant->birth_year ?? '-' }}</td>
                    <td>{{ $eventRegistration->masterParticipant->masterSchool->name ?? '-' }}</td>
                    <td>{{ $eventRegistration->masterMatchCategory->name ?? '-' }}</td>
                    <td>{{ $type->name }}</td>
                    <td class="text-right" data-order="{!! empty($type->pivot->is_no_point) && !empty($type->pivot->point) ? $type->pivot->point : 999999 !!}">
                      {{ empty($type->pivot->is_no_point) && !empty($type->pivot->point) ? $type->pivot->point_text : 'NT' }}
                    </td>
                    <td>{{ $eventRegistration->coach_name }}</td>
                    <td>{{ $eventRegistration->coach_phone }}</td>
                    {{-- <td class="text-center"><a href="{!! $eventRegistration->school_certificate_url !!}" target="_blank"><i class="fa fa-download"></i></a></td>
                                        <td class="text-center"><a href="{!! $eventRegistration->birth_certificate_url !!}" target="_blank"><i class="fa fa-download"></i></a></td>
                                        <td class="text-center">{!! $eventRegistration->preview_tiny_fancy_photo !!}</td> --}}
                  </tr>
                @endforeach
              @empty
                <tr>
                  <td colspan="100" align="center">Data kosong!</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </form>
      </div>
    </div>
    <div class="card-footer text-center">
      <a href="{{ route($baseRouteName . 'download', $event) }}" class="btn btn-success">
        <i class="fas fa-fw fa-download"></i> Download semua
      </a>
      <a href="{{ route($baseRouteName . 'atlet', $event) }}" class="btn btn-info">
        <i class="fas fa-fw fa-users"></i> Lihat dalam daftar Peserta
      </a>
      <a href="{{ route($baseRouteName . 'school', $event) }}" class="btn btn-info">
        <i class="fas fa-fw fa-school"></i> Lihat dalam daftar {{ __('Sekolah') }}
      </a>
    </div>
  </div>

@endsection

@push('css')
  <link rel="stylesheet" href="/assets/plugins/datatables/buttons-datatables/2.4.2/css/buttons.dataTables.min.css">
@endpush

@push('js')
  @include($baseViewPath . '._index-js')
@endpush
