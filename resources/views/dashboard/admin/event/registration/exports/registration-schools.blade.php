<table>
  <tbody>
    <tr>
      <td><strong>{{ strtoupper($event->name) }}</strong></td>
    </tr>
    @if (!empty($event->location))
      <tr>
        <td><strong>{{ strtoupper($event->location) }}</strong></td>
      </tr>
    @endif
    <tr>
      <td><strong>{!! parseBetweenDate($event->start_date, $event->end_date, 'F') !!}</strong></td>
    </tr>
  </tbody>
</table>
<table>
  <thead>
    <tr>
      <th><strong>NO</strong></th>
      <th><strong>{{ strtoupper(__('Sekolah')) }}</strong></th>
      <th><strong>ATLET</strong></th>
      <th><strong>TAGIHAN</strong></th>
    </tr>
  </thead>
  <tbody>
    @php
      $i = 1;
    @endphp
    @forelse ($schools as $school)
      <tr>
        <td class="text-right">{!! $i++ !!}</td>
        <td>{{ strtoupper($school->name) ?? '-' }}</td>
        <td>{{ $school->master_participants_count ?? 0 }}</td>
        <td>{{ $totalTagihan[$school->id] }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="100" align="center">Data kosong!</td>
      </tr>
    @endforelse
  </tbody>
</table>
