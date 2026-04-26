{{ Form::bs4HorCheckboxes('school_ids[]', $schools, old('school_ids'), 'Pilih ' . __('Sekolah'), [], 'newline') }}

{{ Form::bs4HorSelect(
    'destination_id',
    $schools,
    old('destination_id'),
    [],
    'Pilih ' . __('Sekolah') . ' Tujuan',
) }}
