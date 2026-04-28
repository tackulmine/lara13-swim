{{-- {{ Form::bs4HorCheckboxes(
    'categories[]',
    $categories,
    old(
        'categories',
        optional($event->categories)->pluck('id')->toArray(),
    ),
    'Pilih '.__('Kategori'),
    null,
    'newline',
) }} --}}
<x-forms.bs4.horizontal.checkboxes name="categories[]" :checkboxes="$categories" :values="old('categories', optional($event->categories)->pluck('id')->toArray())" :label="'Pilih ' . __('Kategori')"
  :input-attributes="[]" separator="newline" />
