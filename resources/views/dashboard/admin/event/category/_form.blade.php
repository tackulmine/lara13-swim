{{ Form::bs4HorCheckboxes(
    'categories[]',
    $categories,
    old(
        'categories',
        optional($event->categories)->pluck('id')->toArray(),
    ),
    'Pilih '.__('Kategori'),
    null,
    'newline',
) }}
