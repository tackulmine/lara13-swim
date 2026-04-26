{{-- <table>
  <tbody>
    <tr>
      <td><strong>{{ strtoupper($event->name) }}</strong></td>
    </tr>
    <tr>
      <td><strong>{!! parseBetweenDate($event->start_date, $event->end_date) !!}</strong></td>
    </tr>
    <tr>
      <td><strong>ACARA {{ $eventStage->number_format }}.
          {{ strtoupper($eventStage->masterMatchType->name) }}</strong></td>
    </tr>
    <tr>
      <td><strong>KATEGORI {{ $eventStage->masterMatchCategory->name }}</strong></td>
    </tr>
  </tbody>
</table> --}}
<table>
  <thead>
    <tr>
      <th><strong>{{ __('Nama Lengkap Atlet') }}</strong></th>
      <th><strong>{{ __('Ranking') }}</strong></th>
      <th><strong>{{ __('Acara') }}</strong></th>
    </tr>
  </thead>
  <tbody>
    @foreach ($participants as $participant)
      @php
        $rank = $loop->iteration;
      @endphp
      @if ($participant->participantDetails->count())
        @foreach ($participant->participantDetails as $detailParticipant)
          <tr>
            <td>{{ strtoupper($detailParticipant->name) }}</td>
            <td>JUARA {!! $rank !!}</td>
            <td>{{ $eventStage->match_type_category }}</td>
          </tr>
        @endforeach
      @else
        <tr>
          <td>{{ strtoupper(optional($participant->masterParticipant)->name) }}</td>
          <td>JUARA {!! $rank !!}</td>
          <td>{{ $eventStage->match_type_category }}</td>
        </tr>
      @endif
      @if ($loop->iteration == 3)
        @break
      @endif
    @endforeach
  </tbody>
</table>
