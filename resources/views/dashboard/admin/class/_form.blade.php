{{ Form::bs4HorText('name', $class->name) }}
{{ Form::bs4HorSelect(
    'users[][id]',
    $memberOptions,
    $class->users,
    [
        'class' => 'selectpicker form-control',
        'title' => 'Kosongi atau pilih  ' . __('Atlet'),
        'data-live-search' => 'true',
        'data-selected-text-format' => 'count > 5',
        'multiple' => true,
    ],
    __('Atlet'),
) }}
