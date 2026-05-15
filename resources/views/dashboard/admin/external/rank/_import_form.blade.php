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

@canrole('superuser')
{{-- {{ Form::bs4HorCheckboxSwitch('delete_all_data', 1, false, 'Hapus Semua Data Lama?', ['id' => 'delete_all_data']) }} --}}
<x-forms.bs4.horizontal.checkbox-switch name="delete_all_data" :value=1 :checked=false :input-attributes="['id' => 'delete_all_data']"
  label="Hapus Semua Data Lama?" />
@endcanrole
