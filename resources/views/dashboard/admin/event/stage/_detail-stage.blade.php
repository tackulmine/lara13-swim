<div class="row">
  <div class="col-lg-10 offset-lg-1">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 pt-1 font-weight-bold text-primary float-left">Detil Acara</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th class="text-right">Nomor Acara</th>
                <th>Tipe</th>
                <th>{{ __('Kategori') }}</th>
                <th>Urutan</th>
                <th class="text-right">Total Seri</th>
                <th class="text-right">Total Peserta</th>
                <th class="text-center">Selesai?</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-right">{{ $eventStage->number_format }}</td>
                <td>{{ $eventStage->masterMatchType->name }}</td>
                <td>{{ $eventStage->masterMatchCategory->name }}</td>
                <td>{{ $eventStage->order_number }}</td>
                <td class="text-right">
                  {{-- {!! $eventStage->eventSessions->count() !!} --}}
                  <a href="{!! route($parentRouteName . 'event.stage.session.index', [$event->id, $eventStage->id]) !!}">
                    {!! $eventStage->eventSessions->count() !!}
                  </a>
                </td>
                <td class="text-right">
                  <a href="{!! route($parentRouteName . 'event.stage.participant.index', [$event->id, $eventStage->id]) !!}">
                    {!! $eventStage->eventSessionParticipants->count() !!}
                  </a>
                </td>
                <td class="text-center">{{ $eventStage->completed ? 'Sudah' : 'Belum' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@php
  $event->load([
      'eventStages',
      'eventStages.masterMatchType',
      'eventStages.masterMatchCategory',
      'eventSessions',
      'eventSessions.eventSessionParticipants',
  ]);
@endphp
@include($parentViewPath . 'event._detail-event')
