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
      <th><strong>NAMA</strong></th>
      <th><strong>{{ strtoupper(__('Gender')) }}</strong></th>
      <th><strong>{{ strtoupper(__('Sekolah')) }}</strong></th>
      <th><strong>KATEGORI</strong></th>
      <th><strong>TIPE GAYA</strong></th>
      <th><strong>PRESTASI</strong></th>
      <th><strong>NAMA PELATIH</strong></th>
      <th><strong>NOMOR PELATIH</strong></th>
    </tr>
  </thead>
  <tbody>
    @php
      $i = 1;
    @endphp
    @forelse ($eventRegistrations as $eventRegistration)
      @foreach ($eventRegistration->types as $type)
        <tr>
          <td class="text-right">{!! $i++ !!}</td>
          <td>{{ strtoupper($eventRegistration->masterParticipant->name) ?? '-' }}</td>
          <td>{{ $eventRegistration->masterParticipant->gender_text }}</td>
          <td>{{ $eventRegistration->masterParticipant->masterSchool->name ?? '-' }}</td>
          <td>{{ $eventRegistration->masterMatchCategory->name ?? '-' }}</td>
          <td>{{ $type->name }}</td>
          <td>
            {{ !empty($type->pivot->is_no_point) ? 'NT' : $type->pivot->point_text }}
          </td>
          <td>{{ $eventRegistration->coach_name }}</td>
          <td>{{ $eventRegistration->coach_phone }}</td>
        </tr>
      @endforeach
    @empty
      <tr>
        <td colspan="100" align="center">Data kosong!</td>
      </tr>
    @endforelse
  </tbody>
</table>
