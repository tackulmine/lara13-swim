@if (!empty($eventSessions))
  @foreach ($eventSessions as $eventSession)
    @php
      $isCurrentSession = $currentEventSession->id == $eventSession->id && !$currentEventSession->completed;
    @endphp

    {!! $isCurrentSession ? '<strong><em>' : '' !!}

    <p class="m-0">
      # Seri {{ $eventSession->session }}
      {!! $isCurrentSession ? '( Sedang Berlangsung )' : ($eventSession->completed ? '( Berakhir )' : '') !!}
    </p>
    {{-- @foreach ($eventSession->eventSessionParticipants as $participant)
            <div
                class="row no-gutters{{ $participant->disqualification ? ' text-danger' : '' }}"{{ $participant->disqualification ? ' title=Diskualifikasi!' : '' }}>
                <div class="col-lg-10">{{ $participant->track }}. {{ $participant->masterParticipant->name }}
                    <small>({{ $participant->masterParticipant->masterSchool->name }})</small>
                </div>
                <div class="col-lg-2 text-right">
                    {{ $participant->point_text ?? '00:00.00' }}
                </div>
            </div>
        @endforeach --}}

    @for ($i = $minTrack; $i <= $maxTrack; $i++)
      @php
        $participant = $eventSession->eventSessionParticipants->where('track', $i)->first() ?? null;
      @endphp
      @if (!$participant)
        <div class="row no-gutters">
          <div class="col-lg-10">{{ $i }}. -</div>
          <div class="col-lg-2 text-right"></div>
        </div>
      @else
        <div
          class="row no-gutters{{ $participant->disqualification ? ' text-danger' : '' }}"{{ $participant->disqualification ? ' title=Diskualifikasi!' : '' }}>
          <div class="col-lg-10">{{ $i }}.
            {{ strtoupper(optional($participant->masterParticipant)->name) }}
            <small>({{ optional($participant->masterParticipant->masterSchool)->name ?? '-' }})</small>
          </div>
          <div class="col-lg-2 text-right">
            {{ $participant->point_text ?? '00:00.00' }}
          </div>
        </div>
      @endif
    @endfor

    {!! $isCurrentSession ? '</em></strong>' : '' !!}

    @if (!$loop->last)
      <hr class="my-1">
    @endif
  @endforeach
@endif
