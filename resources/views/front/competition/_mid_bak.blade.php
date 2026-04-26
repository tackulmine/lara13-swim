@if (!empty($eventSessions))
  @foreach ($eventSessions as $eventSession)
    {{-- @if ($eventSession->completed) --}}
    {{-- <div class="card mb-1 border-bottom-primary"> --}}
    {{-- <div class="card-header px-2 py-1"> --}}
    {{-- <h6 class="m-0 text-primary">Seri {{ $eventSession->session }}</h6> --}}
    {{-- </div> --}}
    {{-- <div class="card-body px-2 py-1"> --}}
    <p class="m-0">Seri {{ $eventSession->session }}</p>
    @foreach ($eventSession->eventSessionParticipants as $participant)
      <div
        class="row no-gutters{{ $participant->disqualification ? ' text-danger' : '' }}"{{ $participant->disqualification ? ' title=Diskualifikasi!' : '' }}>
        <div class="col-lg-10">{{ $participant->track }}. {{ $participant->masterParticipant->name }}
          <small>({{ $participant->masterParticipant->masterSchool->name }})</small>
        </div>
        <div class="col-lg-2 text-right">
          {{ $participant->point_text ?? '00:00.00' }}
        </div>
      </div>
    @endforeach
    @if (!$loop->last)
      <hr class="my-1">
    @endif
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- @endif --}}
  @endforeach
@endif
