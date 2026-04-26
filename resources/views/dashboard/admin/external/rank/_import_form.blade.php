{{ Form::bs4HorFile(
    'file',
    [
        'required' => 'required',
        // 'accept' => '.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'
        'accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel',
    ],
    'File Excel',
    'Pilih file excel',
) }}

@canrole('superuser')
{{ Form::bs4HorCheckboxSwitch('delete_all_data', 1, false, 'Hapus Semua Data Lama?', ['id' => 'delete_all_data']) }}
@endcanrole
