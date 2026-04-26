@php
  $formGroupClasses = 'mb-1';
  $formLabelClasses = 'col-form-label-sm';
  $attributes = ['class' => 'form-control-plaintext form-control-sm'];
@endphp
{{ Form::bs4HorPlain(__('Nama Lengkap'), optional($eventRegistration->masterParticipant)->name, $attributes, null, $formGroupClasses, $formLabelClasses) }}
{{ Form::bs4HorPlain(__('Lahir'), optional($eventRegistration->masterParticipant)->birth_year, $attributes, null, $formGroupClasses, $formLabelClasses) }}
{{ Form::bs4HorPlain(__('Gender'), optional($eventRegistration->masterParticipant)->gender_text, $attributes, null, $formGroupClasses, $formLabelClasses) }}
{{-- {{ Form::bs4HorPlain(__('Kategori'), $eventRegistration->masterMatchCategory->name ?? '-', $attributes, null, $formGroupClasses, $formLabelClasses) }} --}}
{{ Form::bs4HorRadios('master_match_category_id', $categories, $eventRegistration->master_match_category_id, __('Kategori'), [], 'block', $formGroupClasses, $formLabelClasses) }}
{{ Form::bs4HorPlain(__('Sekolah'), optional(optional($eventRegistration->masterParticipant)->masterSchool)->name, $attributes, null, $formGroupClasses, $formLabelClasses) }}
@php
  $formGroupClasses = 'mb-1';
  $formLabelClasses = 'col-form-label-sm';
  $attributes = ['class' => 'form-control form-control-sm'];
@endphp
{{ Form::bs4HorText(
    'coach_name',
    $eventRegistration->coach_name,
    array_merge($attributes, [
        'class' => $attributes['class'] . ' toUppercase',
    ]),
    __('Nama Pelatih'),
    $formGroupClasses,
    $formLabelClasses,
) }}
{{ Form::bs4HorText(
    'coach_phone',
    $eventRegistration->coach_phone,
    array_merge($attributes, [
        'data-inputmask' => "'mask': '9999-9999-9999-9'",
        'inputmode' => 'text',
        'placeholder' => '08xx-xxxx-xxxx-x',
    ]),
    __('No HP Pelatih'),
    $formGroupClasses,
    $formLabelClasses,
) }}

{{-- {{ Form::bs4HorCheckboxes(
    'type_ids[]',
    $eventRegistrationTypes,
    old('type_ids', $currentTypeIds),
    'Pilih '.__('Gaya'),
    [],
    'newline',
) }} --}}

<hr>

@include('front.competition._checkboxes-form', [
    'name' => 'style[]',
    'additionalName' => 'style_value[]',
    'separator' => 'block',
    'checkboxes' => $eventRegistrationTypes,
    'values' => $currentTypeIds,
    'additionalValues' => $additionalValues,
])
