<table class="table table-light table-striped table-sm">
  <thead>
    <tr>
      <th width="5%" class="text-center">No</th>
      <th>Atlet</th>
      <th width="5%" class="text-center">G</th>
      <th width="25%">{{ __('Sekolah') }}</th>
      <th width="15%" class="text-right">Total Medali</th>
    </tr>
  </thead>
  <tbody>
    @php
      $peringkat = 0;
    @endphp
    @foreach ($participants as $key => $participant)
      @php
        $classStyle = '';
        if ($loop->iteration == 1) {
            $peringkat = 1;
            $currMedalTotal = $participant[$medal];
        }
        // if ($peringkat < 4) {
        if ($currMedalTotal != $participant[$medal]) {
            $peringkat++;
            $currMedalTotal = $participant[$medal];
        }
        // }
        $trClassStyle = in_array($peringkat, [1, 2, 3]) ? 'style="font-weight:bolder;"' : '';
        $tdClassStyle =
            $peringkat == 1
                ? 'class="text-center text-success" style="font-size: 130%;"'
                : ($peringkat == 2
                    ? 'class="text-center text-info" style="font-size: 120%;"'
                    : ($peringkat == 3
                        ? 'class="text-center text-warning" style="font-size: 110%;"'
                        : ''));
      @endphp
      <tr {!! $trClassStyle !!}>
        <td {!! $tdClassStyle ?: 'class="text-center"' !!}>{{ $peringkat }}</td>
        <td>{{ $participant['data']['participant_name'] }}</td>
        <td class="text-center">
          {{ parseGenderAbbr($participant['data']['participant_gender']) }}
        </td>
        <td>{{ $participant['data']['school_name'] }}</td>
        <td class="text-right">{{ $participant[$medal] }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
