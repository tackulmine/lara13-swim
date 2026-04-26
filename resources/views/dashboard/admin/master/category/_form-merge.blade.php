{{ Form::bs4HorCheckboxes('match_category_ids[]', $matchCategories, old('match_category_ids'), 'Pilih ' . __('Kategori'), [], 'newline') }}

{{ Form::bs4HorSelect(
    'destination_id',
    $matchCategories,
    old('destination_id'),
    [],
    'Pilih ' . __('Kategori') . ' Tujuan',
) }}
