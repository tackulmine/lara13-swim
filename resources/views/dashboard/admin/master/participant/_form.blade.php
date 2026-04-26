{{-- {{ Form::bs4HorText('name', $participant->name, ['class' => 'form-control toUppercase'], __('Nama Lengkap Atlet')) }} --}}
<x-forms.bs4.horizontal.text name="name" :value="$participant->name" :input-attributes="['class' => 'form-control toUppercase']" :label="__('Nama Lengkap Atlet')" />
{{-- {{ Form::bs4HorRadios(
    'gender',
    [
        'male' => 'Laki-laki',
        'female' => 'Perempuan',
        'mix' => 'Mix',
    ],
    $participant->gender,
    __('Gender'),
    [],
) }} --}}
<x-forms.bs4.horizontal.radios name="gender" :value="$participant->gender" :radios="[
    'male' => 'Laki-laki',
    'female' => 'Perempuan',
    'mix' => 'Mix',
]" :label="__('Gender')" />

{{-- {{ Form::bs4HorSelect('master_school_id', $schoolOptions, $participant->master_school_id, [], __('Sekolah')) }} --}}
<x-forms.bs4.horizontal.select name="master_school_id" :value="$participant->master_school_id" :options="$schoolOptions" :input-attributes="[]"
  :label="__('Sekolah')" />

{{-- {{ Form::bs4HorText('address', $participant->address, [], __('Alamat')) }}
{{ Form::bs4HorText('location', $participant->location, ['class' => 'form-control toUppercase'], __('Lokasi')) }}
{{ Form::bs4HorText('birth_date', !empty($participant->birth_date) ? $participant->birth_date->format('d/M/Y') : '', ['class' => 'form-control dateOfBirth'], __('Tanggal Lahir')) }} --}}
{{-- {{ Form::bs4HorNumber(
    'birth_year',
    !empty($participant->birth_year) ? $participant->birth_year : '',
    [
        'class' => 'form-control',
        'min' => now()->subYear(50)->year,
        'max' => now()->subYear(2)->year,
        'step' => 1,
    ],
    __('Tahun Lahir'),
) }} --}}
<x-forms.bs4.horizontal.number name="birth_year" :value="!empty($participant->birth_year) ? $participant->birth_year : ''" :input-attributes="[
    'class' => 'form-control',
    'min' => now()->subYear(50)->year,
    'max' => now()->subYear(2)->year,
    'step' => 1,
]" :label="__('Tahun Lahir')" />
