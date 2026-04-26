<div class="row">
  <div class="col-lg-8 offset-lg-2">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 pt-1 font-weight-bold text-primary float-left">Detil Peserta</h6>
      </div>
      <div class="card-body">
        <table class="table table-bordered dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Nama Peserta</th>
              <th>{{ __('Sekolah') }}</th>
              <th class="text-right">{{ __('Prestasi') }}</th>
              <th class="text-right">{{ __('Lint') }}</th>
              <th class="text-right">{{ __('Poin') }}</th>
              <th class="text-right">{{ __('Poin MiliSec') }}</th>
              <th class="text-center">Dis?</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Nama Peserta</th>
              <th>{{ __('Sekolah') }}</th>
              <th class="text-right">{{ __('Prestasi') }}</th>
              <th class="text-right">{{ __('Lint') }}</th>
              <th class="text-right">{{ __('Poin') }}</th>
              <th class="text-right">{{ __('Poin MiliSec') }}</th>
              <th class="text-center">Dis?</th>
            </tr>
          </tfoot>
          <tbody>
            <tr>
              <td>
                {{ $eventSessionParticipant->masterParticipant->name . " ({$eventSessionParticipant->masterParticipant->gender_initial}/{$eventSessionParticipant->masterParticipant->birth_year_text})" }}
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
@include($parentViewPath . 'event.stage.session._detail-session')
