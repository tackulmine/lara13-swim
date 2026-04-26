{{ Form::bs4HorSelect('event_stage_id', $stageOptions, null, ['class' => 'form-control'], __('Acara')) }}

{{ Form::bs4HorSelect('event_session_id', $sessionOptions, null, ['class' => 'form-control', 'data-selected-value' => old('event_session_id')], __('Seri')) }}

<div id="estafet-table"></div>
