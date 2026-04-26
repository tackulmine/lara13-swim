{{ Form::bs4HorSelect('master_match_type_id', $typeOptions, $eventStage->master_match_type_id, ['class' => 'form-control'], 'Tipe') }}
{{ Form::bs4HorSelect('master_match_category_id', $categoryOptions, $eventStage->master_match_category_id, ['class' => 'form-control'], __('Kategori')) }}
{{ Form::bs4HorNumber('number', $eventStage->number, ['min' => 1], 'Nomor Acara') }}
{{ Form::bs4HorNumber('order_number', $eventStage->order_number, ['min' => 0], 'Urutan') }}
