{{-- {{ Form::bs4HorSelect('user_id', $participantOptions, $participant->user_id, ['required' => true], __('Nama Lengkap Atlet')) }} --}}
<x-forms.bs4.horizontal.select name="user_id" :options="$participantOptions" :value="$participant->user_id" :input-attributes="['required' => true]"
  label="Nama Lengkap Atlet" />
{{-- {{ Form::bs4HorSelect('master_championship_gaya_id', $gayaOptions, $participant->master_championship_gaya_id, ['required' => true], __('Gaya')) }} --}}
<x-forms.bs4.horizontal.select name="master_championship_gaya_id" :options="$gayaOptions" :value="$participant->master_championship_gaya_id" :input-attributes="['required' => true]"
  label="Gaya" />
{{-- {{ Form::bs4HorText(
    'point_text',
    $participant->point_text,
    [
        'class' => 'form-control text-right input-mask-time',
        'data-inputmask-alias' => 'datetime',
        'data-inputmask-inputformat' => 'MM:ss.L',
        'data-inputmask-placeholder' => '00:00.00',
        'placeholder' => '00:00.00',
        'value' => old('point_text') ?? $participant->point_text,
        'required' => true,
    ],
    'Poin',
) }} --}}
<x-forms.bs4.horizontal.text name="point_text" :value="$participant->point_text" :input-attributes="[
    'class' => 'form-control text-right input-mask-time',
    'data-inputmask-alias' => 'datetime',
    'data-inputmask-inputformat' => 'MM:ss.L',
    'data-inputmask-placeholder' => '00:00.00',
    'placeholder' => '00:00.00',
    'value' => old('point_text') ?? $participant->point_text,
    'required' => true,
]" label="Poin" />
{{-- {{ Form::bs4HorText('rank', $participant->rank, [], 'Ranking') }} --}}
<x-forms.bs4.horizontal.text name="rank" :value="$participant->rank" :input-attributes="[]" label="Ranking" />
