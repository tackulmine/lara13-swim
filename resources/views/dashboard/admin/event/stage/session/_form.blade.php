{{-- {{ Form::bs4HorSelect('event_stage_id', $stageOptions, $eventSession->event_stage_id) }} --}}
{{-- {{ Form::bs4HorNumber('session', $eventSession->session, ['min' => 1], 'Seri') }} --}}
<x-forms.bs4.horizontal.number name="session" :value="$eventSession->session" :attributes="['min' => 1]" label="Seri" />
