<table>
  <thead>
    <tr>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
    <tr>
      <th>&nbsp;</th>
      <th colspan="10" align="center"><strong>BESTTIME (RANK)</strong></th>
    </tr>
    <tr>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($styles as $style)

      <tr>
        <th>&nbsp;</th>
        <th colspan="10" align="center"><strong>({{ $style->code }}) {{ $style->name }}, LCM</strong></th>
      </tr>

      <tr>
        <th>&nbsp;</th>
        <th><strong>Rnk</strong></th>
        <th><strong>NISNAS</strong></th>
        <th><strong>Athlete Name</strong></th>
        <th><strong>DoB</strong></th>
        <th><strong>Club</strong></th>
        <th><strong>City</strong></th>
        <th><strong>Province</strong></th>
        <th><strong>Meet ID</strong></th>
        <th><strong>BestTime</strong></th>
        <th><strong>FP</strong></th>
      </tr>

      @php
        $rows = $style->bestTimes;
        $i = 1;
      @endphp
      @forelse ($rows as $row)
        <tr>
          <td>&nbsp;</td>
          <td align="right">{!! $i++ !!}</td>
          <td>{{ $row->externalSwimmingAthlete->nisnas }}</td>
          <td>{{ $row->externalSwimmingAthlete->name }}</td>
          <td>{{ $row->externalSwimmingAthlete->dob->format('n/j/Y') }}</td>
          <td>{{ $row->externalSwimmingAthlete->externalSwimmingClub->name }}</td>
          <td>{{ str_replace('KABUPATEN', 'KAB.', $row->externalSwimmingAthlete->externalSwimmingClub->masterCity->name) }}</td>
          <td>{{ $row->externalSwimmingAthlete->externalSwimmingClub->masterCity->masterProvince->name }}</td>
          <td>{{ $row->externalSwimmingEvent->name }}</td>
          <td align="right">{{ $row->point_text }}</td>
          <td>{{ $row->fp }}</td>
        </tr>
      @empty
        <tr>
          <td>&nbsp;</td>
          <td colspan="10" align="center">Data kosong!</td>
        </tr>
      @endforelse
    @endforeach
  </tbody>
</table>
