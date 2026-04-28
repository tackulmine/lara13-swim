{{-- {{ Form::bs4HorCheckboxes('types[]', $types, old('types', $typeIds), 'Pilih Tipe', null, 'newline') }} --}}
<x-forms.bs4.horizontal.checkboxes name="types[]" :checkboxes="$types" :values="old('types', $typeIds)" :label="Pilih Tipe" :input-attributes="[]"
  separator="newline" />
