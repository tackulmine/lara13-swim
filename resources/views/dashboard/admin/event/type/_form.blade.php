{{-- {{ Form::bs4HorCheckboxes(
    'types[]',
    $types,
    old(
        'types',
        optional($event->types)->pluck('id')->toArray(),
    ),
    'Pilih Tipe',
    null,
    'newline',
) }} --}}
<x-forms.bs4.horizontal.checkboxes name="types[]" :checkboxes="$types" :values="old('types', optional($event->types)->pluck('id')->toArray())" :label="'Pilih ' . __('Tipe')" :input-attributes="[]"
  separator="newline" />
