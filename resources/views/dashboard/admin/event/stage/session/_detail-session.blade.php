<div class="row">
  <div class="col-lg-6 offset-lg-3">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 pt-1 font-weight-bold text-primary float-left">Detil Seri</h6>
      </div>
      <div class="card-body">
        <table class="table table-bordered dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th class="text-right">Seri</th>
              <th class="text-right">Total Peserta</th>
              <th class="text-center">Selesai?</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th class="text-right">Seri</th>
              <th class="text-right">Total Peserta</th>
              <th class="text-center">Selesai?</th>
            </tr>
          </tfoot>
          <tbody>
            <tr>
              <td class="text-right">{!! $eventSession->session !!}</td>
              <td class="text-right">
                {{-- {!! $eventSession->eventSessionParticipants->count() !!} --}}
                <a href="{!! route($parentRouteName . 'event.stage.session.participant.index', [
                    $event->id,
                    $eventStage->id,
                    $eventSession->id,
                ]) !!}">
                  {!! $eventSession->eventSessionParticipants->count() !!}
                </a>
              </td>
              <td class="text-center">{{ $eventSession->completed ? 'Sudah' : 'Belum' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@php
  $eventStage->load([
      // 'event',
      'masterMatchType',
      'masterMatchCategory',
      'eventSessionParticipants',
  ]);
@endphp
@include($parentViewPath . 'event.stage._detail-stage')
