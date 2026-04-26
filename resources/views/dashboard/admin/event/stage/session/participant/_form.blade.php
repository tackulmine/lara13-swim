{{ Form::bs4HorSelect('master_participant_id', $participantOptions, $eventSessionParticipant->master_participant_id, ['class' => 'form-control'], 'Peserta') }}
{{ Form::bs4HorSelect('event_session_id', $eventSessionOptions, $eventSession->id, ['class' => 'form-control'], 'Seri') }}
{{-- {{ Form::bs4HorNumber('', optional(optional($eventSessionParticipant->masterParticipant->styles->where('id', $eventStage->masterMatchType->id)->first())->pivot)->point_text ?? '', ['min' => 1], 'Lint.') }} --}}

<div class="form-group row">
  {{ Form::label(__('Prestasi'), null, ['class' => 'col-sm-3 col-form-label']) }}
  <div class="col-sm-9">

    <input type="text" class="form-control text-right input-mask-time" data-inputmask-alias="datetime"
      data-inputmask-inputformat="MM:ss.L" data-inputmask-placeholder="00:00.00" {{-- placeholder="00:00.00" --}}
      placeholder="Prestasi" value="{{ old('prestasi', $prestasi) }}" name="prestasi">
    <small class="form-text text-muted text-right">Biarkan kosong jika belum ada.</small>
  </div>
</div>

{{ Form::bs4HorNumber('track', $eventSessionParticipant->track, ['min' => 0], 'Lintasan') }}
{{ Form::bs4HorText(
    'point_text',
    old('point_text', $eventSessionParticipant->point_text_decimal ?? ($eventSessionParticipant->point_text ?? '')),
    [
        'class' => 'form-control text-right input-mask-time',
        'data-inputmask-alias' => 'datetime',
        'data-inputmask-inputformat' => 'MM:ss.l',
        'data-inputmask-placeholder' => '00:00.000',
        'placeholder' => '00:00.000',
        // 'value' => old(
        //     'point_text',
        //     $eventSessionParticipant->point_text_decimal ?? ($eventSessionParticipant->point_text ?? ''),
        // ),
    ],
    __('Poin'),
) }}
{{-- {{ Form::bs4HorCheckboxSwitch('disqualification', 1, $eventSessionParticipant->disqualification ? true : false, 'Diskualifikasi?') }} --}}
{{ Form::bs4HorRadios(
    'dis_level',
    [
        0 => '-',
        1 => 'SP',
        2 => 'DQ',
        3 => 'NS',
    ],
    $eventSessionParticipant->dis_level,
    'Dis?',
) }}
