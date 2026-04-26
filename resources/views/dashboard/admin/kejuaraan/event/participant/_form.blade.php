{{ Form::bs4HorSelect('user_id', $participantOptions, $participant->user_id, ['required' => true], __('Nama Lengkap Atlet')) }}
{{ Form::bs4HorSelect('master_championship_gaya_id', $gayaOptions, $participant->master_championship_gaya_id, ['required' => true], __('Gaya')) }}
{{ Form::bs4HorText(
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
) }}
{{ Form::bs4HorText('rank', $participant->rank, [], 'Ranking') }}
