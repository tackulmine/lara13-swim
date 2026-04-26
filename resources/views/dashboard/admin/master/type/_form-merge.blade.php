{{ Form::bs4HorCheckboxes('match_type_ids[]', $matchTypes, old('match_type_ids'), 'Pilih ' . __('Tipe Gaya'), [], 'newline') }}

{{ Form::bs4HorSelect(
    'destination_id',
    $matchTypes,
    old('destination_id'),
    [],
    'Pilih ' . __('Tipe Gaya') . ' Tujuan',
) }}
