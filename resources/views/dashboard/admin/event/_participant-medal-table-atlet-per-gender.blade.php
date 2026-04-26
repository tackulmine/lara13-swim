@foreach ($medalParticipantsPerGender as $gender => $participants)
  <h5 style="color: #858796; border-bottom: 1px solid #858796;">Rekap Medali {{ parseGender($gender) }}</h5>

  <table class="table table-light table-striped table-sm">
    <thead>
      <tr>
        <th width="5%" class="text-center">No</th>
        <th>Atlet</th>
        <th width="25%">{{ __('Sekolah') }}</th>
        <th width="12%" class="text-right">Emas</th>
        <th width="12%" class="text-right">Perak</th>
        <th width="12%" class="text-right">Perunggu</th>
      </tr>
    </thead>
    <tbody>
      @php
        $peringkat = 0;
      @endphp
      @foreach ($participants as $key => $participant)
        @php
          $totalMedal = $participant['gold'] . $participant['silver'] . $participant['bronze'];
          if ($loop->iteration == 1) {
              $peringkat = 1;
              $currMedalTotal = $totalMedal;
          }
          // if ($peringkat < 4) {
          if ($currMedalTotal != $totalMedal) {
              $peringkat++;
              $currMedalTotal = $totalMedal;
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
          <td>{{ $participant['data']['school_name'] }}</td>
          <td class="text-right">{{ $participant['gold'] }}</td>
          <td class="text-right">{{ $participant['silver'] }}</td>
          <td class="text-right">{{ $participant['bronze'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endforeach
