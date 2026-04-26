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
          $registrations = $event->eventRegistrations;
        @endphp
        <p>Total Pendaftaran:
          @if (!request()->filled('individual') && !request()->filled('estafet'))
            <strong>{{ $registrations->count() }}</strong>
          @else
            <a href="{{ route($baseRouteName . 'atlet', $event) }}">
              <strong>{{ $registrations->count() }}</strong>
            </a>
          @endif
          (
          @if (request()->filled('individual'))
            <strong>{{ $registrations->whereIn('master_match_category_id', $individualCatIds)->count() }}
              {{ __('Individu') }}</strong>
          @else
            <a href="{{ route($baseRouteName . 'atlet', $event) }}?individual=1">
              <strong>{{ $registrations->whereIn('master_match_category_id', $individualCatIds)->count() }}</strong>
            </a> {{ __('Individu') }}
          @endif
          ,
          @if (request()->filled('estafet'))
            <strong>{{ $registrations->whereIn('master_match_category_id', $estafetCatIds)->count() }}
              {{ __('Estafet') }} </strong>
          @else
            <a href="{{ route($baseRouteName . 'atlet', $event) }}?estafet=1">
              <strong>{{ $registrations->whereIn('master_match_category_id', $estafetCatIds)->count() }}</strong>
            </a> {{ __('Estafet') }}
          @endif
          ).
        </p>

        @include('layouts.partials._notif')

        <form action="{{ route($baseRouteName . 'destroy-batch-atlet', $event) }}" method="post" class="mb-4"
          id="participant-form">
          @csrf
          @method('delete')

          <button type="submit" class="btn btn-danger mb-4" data-toggle="tooltip" title="Menghapus baris terpilih">Batch
            Delete</button>

          <div id="dataTableCustom_filters" class="form-inline"><label>Filter: &nbsp;</label></div>

          <table class="table table-striped table-bordered" id="dataTableCustom" data-order="[[ 6, &quot;asc&quot; ]]"
            width="100%" cellspacing="0">
            <thead>
              @include($baseViewPath . '_atlet-table-head')
            </thead>
            <tfoot>
              @include($baseViewPath . '_atlet-table-head')
            </tfoot>
            <tbody>
              @php
                $i = 1;
              @endphp
              @forelse ($eventRegistrations as $eventRegistration)
                @php
                  $typesCount = $eventRegistration->types->count();

                  $gayaClass = Str::contains($eventRegistration->masterMatchCategory->name, 'RELAY')
                      ? 'gaya-estafet'
                      : 'gaya-individual';
                  $tagihanClass = Str::contains($eventRegistration->masterMatchCategory->name, 'RELAY')
                      ? 'tagihan-estafet'
                      : 'tagihan-individual';

                  $relay = $eventRegistration->types->filter(function ($type) use ($eventRegistration) {
                      return Str::contains($eventRegistration->masterMatchCategory->name, 'RELAY');
                  });
                  $type = count($relay) ? 'relay' : 'normal';

                  $totalTypesTagihan = $eventRegistration->getTotalTagihan($type);
                @endphp
                <tr>
                  <td>{{ $eventRegistration->id }}</td>
                  <td class="no">{{ $i++ }}</td>
                  <td>{{ $eventRegistration->masterParticipant->name ?? '-' }}</td>
                  <td>{{ $eventRegistration->masterParticipant->gender_text ?? '-' }}</td>
                  <td>{{ $eventRegistration->masterParticipant->birth_year ?? '-' }}</td>
                  <td>{{ $eventRegistration->masterParticipant->masterSchool->name ?? '-' }}</td>
                  <td>{{ $eventRegistration->masterMatchCategory->name ?? '-' }}</td>
                  <td class="jumlah-gaya {{ $gayaClass }} text-center" data-order="{{ intval($typesCount) }}"
                    data-search="{{ intval($typesCount) }}">
                    <a href="#!" data-toggle="tooltip" data-html="true"
                      title="{{ $eventRegistration->types->pluck('name')->sort()->implode('; ') }}">
                      {{ $typesCount }}
                    </a>
                    {{-- @if ($typesCount)
                      <span class="badge badge-secondary">{!! $eventRegistration->types->pluck('name')->implode('</span> <span class="badge badge-secondary">') !!}</span>
                    @else
                      -
                    @endif --}}
                  </td>
                  <td>{{ $eventRegistration->coach_name }}</td>
                  <td>{{ $eventRegistration->coach_phone }}</td>
                  <td class="tagihan {{ $tagihanClass }} text-right" data-order="{{ $totalTypesTagihan }}"
                    data-search="{{ $totalTypesTagihan }}">
                    {{ numberFormatIdn($totalTypesTagihan) }}</td>
                  {{-- <td class="text-center"><a href="{!! $eventRegistration->school_certificate_url !!}" target="_blank"><i class="fa fa-download"></i></a></td> --}}
                  {{-- <td class="text-center"><a href="{!! $eventRegistration->birth_certificate_url !!}" target="_blank"><i class="fa fa-download"></i></a></td> --}}
                  <td class="text-center">{!! $eventRegistration->preview_tiny_fancy_birth_certificate !!}</td>
                  <td class="text-center">{!! $eventRegistration->preview_tiny_fancy_photo !!}</td>
                  <td>
                    <a href="{!! route($baseRouteName . 'atlet-type-edit', [$event, $eventRegistration]) !!}" title="Edit {{ $moduleName }}"
                      data-title="Edit {{ $moduleName }}" data-action="{!! route($baseRouteName . 'atlet-type-update', [$event, $eventRegistration]) !!}" {{-- data-toggle="modal" --}}
                      data-target="#myEditModal" class="btn btn-warning btn-type-edit mb-0">Edit</a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="100" align="center">Data kosong!</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </form>
      </div>

      {{-- <table id="tagihan-info">
        <tr>
          <td>Individu</td>
          <td> : </td>
          <td class="text-right"><span class="tagihan-info-individu"></span></td>
        </tr>
        <tr>
          <td>Estafet</td>
          <td> : </td>
          <td class="text-right"><span class="tagihan-info-estafet"></span></td>
        </tr>
        <tr style="border-top: 4px double">
          <td>Total</td>
          <td> : </td>
          <td class="text-right"><strong class="tagihan-info-total"></strong></td>
        </tr>
      </table> --}}
    </div>
    <div class="card-footer text-center">
      {{-- <a href="{{ route($baseRouteName . 'download', $event) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Download
            </a> --}}
      <a href="{{ route($baseRouteName . 'index', $event) }}" class="btn btn-info">
        <i class="fas fa-fw fa-swimming-pool"></i> Lihat semua nomor {{ __('Gaya') }}
      </a>
      <a href="{{ route($baseRouteName . 'school', $event) }}" class="btn btn-info">
        <i class="fas fa-fw fa-school"></i> Lihat dalam daftar {{ __('Sekolah') }}
      </a>
    </div>
  </div>

  @component('components.bs4.modal', [
      'modalId' => 'myEditModal',
      'modalClass' => '',
      'modalSize' => '',
      'modalTitle' => '',
      'modalFormUrl' => '#!',
      'modalFormAttributes' => ['method' => 'put', 'class' => 'form-disabled-submit'],
  ])
    @method('put')
  @endcomponent
@endsection

@push('css')
  <link rel="stylesheet" href="/assets/plugins/datatables/buttons-datatables/2.4.2/css/buttons.dataTables.min.css">
@endpush

@push('js')
  @include($baseViewPath . '._atlet-js')
@endpush
