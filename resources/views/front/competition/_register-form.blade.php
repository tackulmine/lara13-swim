{{-- {{ Form::bs4HorText(
    'school',
    old('school', $registrationSchool ?? null),
    [
        'required' => 'required',
        'class' => 'form-control toUppercase',
        // 'readonly' => $registrationSchool ? true : false
    ],
    __('Sekolah'),
) }} --}}

@if ($event->eventSpecialTypes->isNotEmpty())
  @php
    $specialOptions = $event->eventSpecialTypes->pluck('name', 'name');
    $specialOptions->prepend(__('Sekolah'), '');
  @endphp

  {{ Form::bs4HorRadios('register_as', $specialOptions, old('register_as', $registrationAs ?? null), __('Daftar sebagai'), ['required', 'required'], 'block') }}
@endif

{{ Form::bs4HorSelect(
    'school',
    $schoolOptions,
    old('school', $registrationSchool ?? null),
    [
        'class' => 'form-control text-uppercase',
        'required' => 'required',
        'data-tags' => 'true',
        'data-placeholder' => 'pilih atau ketik baru + enter',
        'data-allow-clear' => 'true',
    ],
    __('Nama Sekolah'),
) }}

{{ Form::bs4HorText(
    'coach_name',
    old('coach_name', $registrationCoachName ?? null),
    [
        'required' => 'required',
        'class' => 'form-control toUppercase',
        // 'readonly' => $registrationCoachName ? true : false
    ],
    __('Nama Pelatih'),
) }}

{{-- {{ Form::bs4HorNumber('coach_phone', old('coach_phone'), ['max' => 999999999999], 'Nomor HP/WA Pelatih') }} --}}

{{ Form::bs4HorText(
    'coach_phone',
    old('coach_phone', $registrationCoachPhone ?? null),
    [
        'required' => 'required',
        'data-inputmask' => "'mask': '9999-9999-9999-9'",
        'inputmode' => 'text',
        'placeholder' => '08xx-xxxx-xxxx-x',
        // 'readonly' => $registrationCoachPhone ? true : false,
    ],
    'No. HP atau WA Pelatih',
) }}

{{-- {{ Form::bs4HorText(
    'name',
    old('name'),
    [
        'class' => 'form-control toUppercase',
        'required' => 'required',
    ],
    'Nama Lengkap',
    null,
    null,
    'Pastikan ejaan nama sudah benar' . ($event->is_has_mix_gender ? '/ tulis nama tim untuk estafet' : '') . '.',
) }} --}}

{{ Form::bs4HorSelect(
    'name',
    $participantOptions,
    old('name'),
    [
        'class' => 'form-control',
        'required' => 'required',
        'data-tags' => 'true',
        'data-placeholder' => 'Isi Tim dulu + pilih atau ketik baru + enter',
        'data-allow-clear' => 'true',
    ],
    $event->isHasRelayType() ? __('Nama Lengkap Atlet/ Tim Estafet') : __('Nama Lengkap Atlet'),
) }}

{{ Form::bs4HorNumber(
    'birth_year',
    old('birth_year'),
    [
        'class' => 'form-control',
        'required' => 'required',
        'min' => now()->subYear(50)->year,
        'max' => now()->subYear(2)->year,
        'step' => 1,
    ],
    __('Tahun Lahir'),
) }}

{{ Form::bs4HorRadios('gender', $genderOptions, old('gender'), __('Gender'), [
    'required' => 'required',
]) }}

{{ Form::bs4HorRadios(
    'category',
    $categories,
    old('category'),
    __('Kategori'),
    [
        'required' => 'required',
    ],
    'block',
) }}

{{ Form::bs4HorCheckboxes(
    'style[]',
    $types,
    old('style[]'),
    __('Gaya'),
    [
        'required' => 'required',
    ],
    'block',
) }}

{{-- {{ Form::bs4HorFile(
    'school_certificate',
    ['accept' => 'image/png, image/jpeg, .pdf', 'lang' => 'id'],
    'Upload Surat Keterangan Sekolah',
    'Pilih file surat (max. 2MB)',
) }} --}}

@php
  $attributes = ['accept' => 'image/png, image/jpeg', 'required' => 'required', 'lang' => 'id'];
  if (auth()->check()) {
      unset($attributes['required']);
  }
@endphp

{{ Form::bs4HorFile(
    'birth_certificate',
    $attributes,
    'Upload Akta Kelahiran',
    'Pilih file (min: 600x800 pixel, max: 2MB)',
) }}

{{ Form::bs4HorFile(
    'photo',
    $attributes,
    'Upload Foto Atlet',
    'Pilih file foto (min: 300x400 pixel, max: 2MB)',
) }}
