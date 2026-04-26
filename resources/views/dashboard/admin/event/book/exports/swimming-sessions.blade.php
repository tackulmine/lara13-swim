<table>
  <thead>
    <tr>
      <td colspan="6" align="center"><strong>{{ strtoupper($event->name) }}</strong></td>
    </tr>
    @if (!empty($event->location))
      <tr>
        <td colspan="6" align="center"><strong>{{ strtoupper($event->location) }}</strong></td>
      </tr>
    @endif
    <tr>
      <td colspan="6" align="center"><strong>{!! parseBetweenDate($event->start_date, $event->end_date, 'F') !!}</strong></td>
    </tr>
  </thead>

  @php
    $j = 0;
  @endphp
  @foreach ($eventBook->sessions as $session)
    <thead>
      @if ($session->session_number == 1 && isset($eventBook->group[$j]))
        <tr>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
        </tr>
        <tr>
          <th><strong>ACARA {{ $eventBook->group[$j]->order_number + 100 }}</strong></th>
          <th colspan="4"><strong>{{ $eventBook->group[$j]->masterMatchType->name }}</strong></th>
          <th><strong>{{ $eventBook->group[$j]->masterMatchCategory->name }}</strong></th>
        </tr>
        @php
          $j++;
        @endphp
      @endif
      <tr>
        <td colspan="6"><strong>{{ strtoupper(__('Seri')) }} {{ $session->session_number }}</strong></td>
      </tr>
      <tr>
        <th><strong>LINT</strong></th>
        <th><strong>NAMA</strong></th>
        <th><strong>LAHIR</strong></th>
        <th><strong>NAMA TIM</strong></th>
        <th><strong>PRESTASI</strong></th>
        <th><strong>HASIL</strong></th>
      </tr>
    </thead>
    <tbody>
      @if ($session->lanes->pluck('lintasan')->min() > $event->start_track_number)
        @for ($i = $event->start_track_number; $i < $session->lanes->pluck('lintasan')->min(); $i++)
          <tr>
            <td align="center"><strong>{{ $i }}</strong></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        @endfor
      @endif
      @forelse ($session->lanes as $index => $lane)
        <tr>
          {{-- <td align="center"><strong>{{ $lane->lane_number }}</strong></td> --}}
          <td align="center"><strong>{{ $lane->lintasan }}</strong></td>
          <td>{{ strtoupper($lane->participant->name ?? '-') }}</td>
          <td>{{ $lane->participant->birth_year ?? '-' }}</td>
          <td>{{ $lane->school->name ?? '-' }}</td>
          <td>{{ $lane->achievement ?? 'NT' }}</td>
          <td>&nbsp;</td>
          {{-- <td>{{ $lane->coach_name ?? '-' }}</td>
        <td>{{ $lane->coach_phone ?? '-' }}</td> --}}
        </tr>
      @empty
        <tr>
          <td colspan="6" align="center">Tidak ada peserta</td>
        </tr>
      @endforelse
      @if ($session->lanes->pluck('lintasan')->max() < $event->total_track + $event->start_track_number - 1)
        @for ($i = $session->lanes->pluck('lintasan')->max() + 1; $i <= $event->total_track + $event->start_track_number - 1; $i++)
          <tr>
            <td align="center"><strong>{{ $i }}</strong></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        @endfor
      @endif
    </tbody>
  @endforeach
</table>
