{{ Form::bs4HorCheckboxes(
    'participant_ids[]',
    $participants,
    old('participant_ids'),
    'Pilih Peserta',
    [],
    'newline',
) }}

{{ Form::bs4HorSelect('destination_id', $participants, old('destination_id'), [], 'Pilih Peserta Tujuan') }}
