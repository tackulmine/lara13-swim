{{ Form::bs4HorText(
    'search_school',
    old('search_school', $registrationSchool ?? null),
    [
        'class' => 'form-control toUppercase',
        'readonly' => $registrationSchool ? true : false,
    ],
    __('Sekolah'),
) }}

{{ Form::bs4HorText(
    'search_coach_name',
    old('search_coach_name', $registrationCoachName ?? null),
    [
        'class' => 'form-control toUppercase',
        'readonly' => $registrationCoachName ? true : false,
    ],
    'Nama Pelatih',
) }}

{{-- {{ Form::bs4HorText(
    'search_coach_phone',
    old('search_coach_phone', $registrationCoachPhone ?? null),
    [
        'data-inputmask' => "'mask': '9999-9999-9999-9'",
        'inputmode' => 'text',
        'placeholder' => '08xx-xxxx-xxxx-x',
        'readonly' => $registrationCoachPhone ? true : false
    ],
    'No. HP/WA Pelatih',
) }} --}}
