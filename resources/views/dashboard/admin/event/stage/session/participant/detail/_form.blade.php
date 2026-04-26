<table class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>{{ __('Nama Atlet') }}</th>
      <th>Urutan</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($eventSessionParticipant->participantDetails as $i => $detail)
      <tr>
        <td>
          {{ Form::select('participants[' . $i . '][master_participant_id]', $masterParticipantOptions, $detail->id ?? (old('participants.' . $i . '.master_participant_id') ?? ''), ['class' => 'form-control']) }}
        </td>
        <td>
          {{ Form::number('participants[' . $i . '][ordering]', $detail->pivot->ordering ?? (old('participants.' . $i . '.ordering') ?? ''), ['class' => 'form-control', 'min' => 1, 'max' => 4]) }}
        </td>
      </tr>
    @endforeach

    @php
      $i = isset($i) ? $i + 1 : 0;
    @endphp
    @for ($j = $i; $j < 4; $j++)
      <tr>
        <td>
          {{ Form::select('participants[' . $j . '][master_participant_id]', $masterParticipantOptions, old('participants.' . $i . '.master_participant_id') ?? null, ['class' => 'form-control']) }}
        </td>
        <td>
          {{ Form::number('participants[' . $j . '][ordering]', old('participants.' . $i . '.master_participant_id') ?? $j + 1, ['class' => 'form-control', 'min' => 1, 'max' => 4]) }}
        </td>
      </tr>
    @endfor
  </tbody>
</table>
