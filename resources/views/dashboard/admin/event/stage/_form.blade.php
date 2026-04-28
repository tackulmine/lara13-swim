{{-- {{ Form::bs4HorSelect('master_match_type_id', $typeOptions, $eventStage->master_match_type_id, ['class' => 'form-control'], 'Tipe') }}
{{ Form::bs4HorSelect('master_match_category_id', $categoryOptions, $eventStage->master_match_category_id, ['class' => 'form-control'], __('Kategori')) }}
{{ Form::bs4HorNumber('number', $eventStage->number, ['min' => 1], 'Nomor Acara') }}
{{ Form::bs4HorNumber('order_number', $eventStage->order_number, ['min' => 0], 'Urutan') }} --}}

<x-forms.bs4.horizontal.select name="master_match_type_id" :options="$typeOptions" :selected="$eventStage->master_match_type_id" label="Tipe" />
<x-forms.bs4.horizontal.select name="master_match_category_id" :options="$categoryOptions" :selected="$eventStage->master_match_category_id" :label="__('Kategori')" />
<x-forms.bs4.horizontal.number name="number" :value="$eventStage->number" :attributes="['min' => 1]" label="Nomor Acara" />
<x-forms.bs4.horizontal.number name="order_number" :value="$eventStage->order_number" :attributes="['min' => 0]" label="Urutan" />
