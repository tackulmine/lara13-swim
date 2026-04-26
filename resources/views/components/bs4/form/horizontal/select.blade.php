<div class="form-group row">
  {{ Form::label(empty($label) ? str_replace(["[","]","_id"], "", $name) : $label, null, ['class' => 'col-sm-3 col-form-label']) }}

  <div class="col-sm-9">
    {{ Form::select($name, $options, $value, array_merge(['class' => 'form-control'], $attributes)) }}
  </div>
</div>
