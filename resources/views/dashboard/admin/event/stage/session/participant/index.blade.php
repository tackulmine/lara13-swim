@extends('layouts.app')

@section('content')
  <!-- Page Heading -->
  {{-- <h1 class="h3 mb-4 text-gray-800">Kompetisi</h1> --}}

  @include('layouts.partials._breadcrumbs')

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      <a href="{!! route($baseRouteName . 'create', [$event->id, $eventStage->id, $eventSession->id]) !!}" title="Tambah Baru" class="btn btn-outline-primary btn-sm btn-create float-right">
        <i class="fas fa-plus"></i> Baru
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @include('layouts.partials._notif')
        <table class="table table-bordered" id="dataTable" data-order="[[ 4, &quot;asc&quot; ]]" width="100%"
          cellspacing="0">
          <thead>
            @include($baseViewPath . '_thead')
          </thead>
          <tfoot>
            @include($baseViewPath . '_thead')
          </tfoot>
          <tbody>
            @foreach ($eventSessionParticipants as $eventSessionParticipant)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $eventSessionParticipant->masterParticipant->name }}
                  @if (
                      (Str::contains(strtolower($eventStage->masterMatchCategory->name), ['estafet', 'relay']) ||
                          Str::contains(strtolower($eventStage->masterMatchType->name), ['estafet', 'relay'])) &&
                          $eventSessionParticipant->participantDetails->count())
                    <small>
                      <ul>
                        <li>{!! $eventSessionParticipant->participantDetails->pluck('name_detail')->implode('</li><li>') !!}</li>
                      </ul>
                    </small>
                  @endif
                </td>
                <td>{{ optional($eventSessionParticipant->masterParticipant->masterSchool)->name ?? '-' }}</td>
                <td class="text-center">
                  {{ optional(optional($eventSessionParticipant->masterParticipant->styles->where('id', $eventStage->masterMatchType->id)->first())->pivot)->point_text ?? 'NT' }}
                </td>
                <td class="text-right">{!! $eventSessionParticipant->track !!}</td>
                <td class="text-right" data-order="{!! $eventSessionParticipant->point !!}">
                  {!! $eventSessionParticipant->point_text !!}
                </td>
                <td class="text-right" data-order="{!! $eventSessionParticipant->point_decimal !!}">
                  {!! $eventSessionParticipant->point_text_decimal !!}
                </td>
                <td class="text-center" data-order="{!! $eventSessionParticipant->disqualification ? 'Ya' : 'Tidak' !!}">
                  {!! $eventSessionParticipant->disqualification
                      ? '<span class="' .
                          $eventSessionParticipant->dis_level_text_class .
                          '"><strong>' .
                          $eventSessionParticipant->dis_level_text .
                          '</strong></span>'
                      : '<span class="text-success">Tidak</span>' !!}
                </td>
                {{-- <td>{!! $eventSessionParticipant->notes !!}</td> --}}
                <td>
                  <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownActionBtn"
                      data-toggle="dropdown" aria-expanded="false"></button>
                    <div class="dropdown-menu" aria-labelledby="dropdownActionBtn">
                      {{-- <a class="dropdown-item" href="{!! route($baseRouteName . 'edit', [$event->id, $eventStage->id, $eventSession->id]) !!}"><i class="far fa-fw fa-edit"></i> Edit Seri
                        {{ $eventSession->session }}</a>
                      <a class="dropdown-item" href="{!! route($baseRouteName . 'participant.index', [$event->id, $eventStage->id, $eventSession->id]) !!}"><i class="far fa-fw fa-eye"></i> Lihat
                        Peserta - Seri {{ $eventSession->session }}</a> --}}
                      <a class="dropdown-item" href="{!! route($baseRouteName . 'edit', [
                          $event->id,
                          $eventStage->id,
                          $eventSession->id,
                          $eventSessionParticipant->id,
                      ]) !!}"
                        title="Edit {{ $moduleName }} {{ $eventSessionParticipant->masterParticipant->name }}"
                        class="btn btn-primary btn-sm btn-circle btn-edit"><i class="fas fa-edit"></i> Edit Peserta</a>
                      @if (Str::contains(strtolower($eventStage->masterMatchCategory->name), ['estafet', 'relay']) ||
                              Str::contains(strtolower($eventStage->masterMatchType->name), ['estafet', 'relay']))
                        <a class="dropdown-item" href="{!! route($baseRouteName . 'detail.edit', [
                            $event->id,
                            $eventStage->id,
                            $eventSession->id,
                            $eventSessionParticipant->id,
                        ]) !!}"
                          title="Edit {{ $moduleName }} Estafet {{ $eventSessionParticipant->masterParticipant->name }}"
                          class="btn btn-primary btn-sm btn-circle btn-edit"><i class="fas fa-users"></i> Peserta
                          Estafet</a>
                      @endif
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @include($parentViewPath . 'event.stage.session._detail-session')
@endsection

{{-- @push('js')
@endpush --}}
