@foreach ($eventSessionParticipants as $eventSessionParticipant)
  <table class="table table-bordered table-striped table-sm">
    <thead>
      <tr>
        <th width="20%">{{ __('Peserta Estafet') }}</th>
        <th width="20%">{{ __('Sekolah') }}</th>
        <th>{{ __('Lint') }}</th>
        <th>{{ __('Nama Atlet') }}</th>
        <th>{{ __('Urutan') }}</th>
      </tr>
    </thead>
    <tbody>
      @php
        $copyMasterGroupParticipants = $masterGroupParticipants->where(
            'master_school_id',
            $eventSessionParticipant->masterParticipant->masterSchool->id,
        );
        if (!Str::contains($eventStage->masterMatchType->name, 'MIX')) {
            $copyMasterGroupParticipants = $copyMasterGroupParticipants->where(
                'gender',
                $eventSessionParticipant->masterParticipant->gender,
            );
        }
        $masterGroupParticipantIds = $copyMasterGroupParticipants->pluck('id');

        $copyMasterIndividualParticipants = $masterIndividualParticipants->where(
            'master_school_id',
            $eventSessionParticipant->masterParticipant->masterSchool->id,
        );
        if (!Str::contains($eventStage->masterMatchType->name, 'MIX')) {
            $copyMasterIndividualParticipants = $copyMasterIndividualParticipants->where(
                'gender',
                $eventSessionParticipant->masterParticipant->gender,
            );
        }
        $masterParticipantOptions = $copyMasterIndividualParticipants
            ->whereNotIn('id', $masterGroupParticipantIds)
            ->pluck('name_detail_with_school', 'id')
            ->prepend('-- Pilih --', '');
      @endphp

      @foreach ($eventSessionParticipant->participantDetails as $i => $detail)
        <tr>
          @if ($loop->iteration == 1)
            <td rowspan=4>{{ $eventSessionParticipant->masterParticipant->name }}</td>
            <td rowspan=4>{{ $eventSessionParticipant->masterParticipant->masterSchool->name }}</td>
            <td rowspan=4>{{ $eventSessionParticipant->track }}</td>
          @endif
          <td>
            {{ Form::select('participants[' . $eventSessionParticipant->id . '][' . $i . '][master_participant_id]', $masterParticipantOptions, $detail->id ?? (old('participants.' . $i . '.master_participant_id') ?? ''), ['class' => 'form-control']) }}
          </td>
          <td>
            {{ Form::number('participants[' . $eventSessionParticipant->id . '][' . $i . '][ordering]', $detail->pivot->ordering ?? (old('participants.' . $i . '.ordering') ?? ''), ['class' => 'form-control', 'min' => 1, 'max' => 4]) }}
          </td>
        </tr>
      @endforeach

      @php
        $i = $eventSessionParticipant->participantDetails->count() && isset($i) ? $i + 1 : 0;
        $x = 0;
      @endphp
      @for ($j = $i; $j < 4; $j++)
        <tr>
          @if ($x++ == 0 && $j == 0)
            <td rowspan=4>{{ $eventSessionParticipant->masterParticipant->name }}</td>
            <td rowspan=4>{{ $eventSessionParticipant->masterParticipant->masterSchool->name }}</td>
            <td rowspan=4>{{ $eventSessionParticipant->track }}</td>
          @endif
          <td>
            {{ Form::select('participants[' . $eventSessionParticipant->id . '][' . $j . '][master_participant_id]', $masterParticipantOptions, old('participants.' . $i . '.master_participant_id') ?? null, ['class' => 'form-control']) }}
          </td>
          <td>
            {{ Form::number('participants[' . $eventSessionParticipant->id . '][' . $j . '][ordering]', old('participants.' . $i . '.master_participant_id') ?? $j + 1, ['class' => 'form-control', 'min' => 1, 'max' => 4]) }}
          </td>
        </tr>
      @endfor
    </tbody>
  </table>
@endforeach
