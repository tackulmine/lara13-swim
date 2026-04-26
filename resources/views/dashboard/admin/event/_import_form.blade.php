{{-- {{ Form::bs4HorFile(
    'file',
    [
        'required' => 'required',
        // 'accept' => '.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'
        'accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel',
    ],
    'File Excel',
    'Pilih file excel',
) }} --}}
<x-forms.bs4.horizontal.file name="file" :input-attributes="[
    'required' => 'required',
    // 'accept' => '.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'
    'accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel',
]" label="File Excel" file-label="Pilih file" />

{{-- {{ Form::bs4HorCheckboxSwitch('multiple_sheets', 1, false, 'Beberapa Sheets?', ['id' => 'multiple_sheets']) }} --}}
<x-forms.bs4.horizontal.checkbox-switch name="multiple_sheets" :value=1 :checked=false :input-attributes="['id' => 'multiple_sheets']"
  label="Beberapa Sheets?" />
@php
  $totalSheetOptions = [
      'class' => 'form-control',
      'id' => 'total_sheets',
      'placeholder' => 'Total Sheet',
      'min' => 2,
      'required' => 'required',
  ];
  $totalSheetOptions = old('total_sheets')
      ? $totalSheetOptions
      : array_merge($totalSheetOptions, ['disabled' => 'disabled']);
@endphp
<div class="form-group row">
  <div class="col-sm-2 offset-sm-3">
    {{-- {{ Form::number('total_sheets', old('total_sheets', 2), $totalSheetOptions) }} --}}
    {{ html()->number('total_sheets', old('total_sheets', 2))->attributes($totalSheetOptions) }}
  </div>
</div>
{{-- {{ Form::bs4HorCheckboxSwitch('delete_old_data', 1, false, 'Hapus Data Lama?', ['id' => 'delete_old_data1']) }} --}}
<x-forms.bs4.horizontal.checkbox-switch name="delete_old_data" :value=1 :checked=false :input-attributes="['id' => 'delete_old_data1']"
  label="Hapus Data Lama?" />
@canrole('superuser')
{{-- {{ Form::bs4HorCheckboxSwitch('delete_all_data', 1, false, 'Hapus Semua Data Lama?', ['id' => 'delete_all_data']) }} --}}
<x-forms.bs4.horizontal.checkbox-switch name="delete_all_data" :value=1 :checked=false :input-attributes="['id' => 'delete_all_data']"
  label="Hapus Semua Data Lama?" />
@endcanrole
