{{ Form::bs4HorCheckboxes(
    'types[]',
    $types,
    old(
        'types',
        optional($event->types)->pluck('id')->toArray(),
    ),
    'Pilih Tipe',
    null,
    'newline',
) }}
