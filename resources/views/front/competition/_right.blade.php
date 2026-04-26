@if (!empty($eventStageRangkingParticipants))
  @foreach ($eventStageRangkingParticipants as $participant)
    @php
      $i = $loop->iteration - 1;
      switch ($loop->iteration) {
          case 1:
              $class = 'text-success';
              break;
          case 2:
              $class = 'text-info';
              break;
          case 3:
              $class = 'text-warning';
              break;
          default:
              $class = 'text-success';
              break;
      }
    @endphp
    @if (!$participant->disqualification && $loop->iteration >= 1 && $loop->iteration <= 3)
      <strong class="{{ $class }}" style="font-size: {!! 130 - $i * 10 !!}%;">
    @endif
    <div class="px-1 py-0">
      <div class="row no-gutters{{ $participant->disqualification ? ' text-danger' : '' }}">
        <div class="col-lg-9">{{ $loop->iteration }}.
          {{ strtoupper(optional($participant->masterParticipant)->name) }}
          <small>({{ optional($participant->masterParticipant->masterSchool)->name }})</small>
        </div>
        <div class="col-lg text-right">
          {{ $participant->point_text ?? '00:00.00' }}
        </div>
      </div>
    </div>
    @if ($loop->iteration >= 1 && $loop->iteration <= 3)
      </strong>
    @endif
  @endforeach
@endif
