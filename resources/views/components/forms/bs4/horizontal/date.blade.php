<div class="form-group row">
  {{-- {{ Form::label($name, empty($label) ? $name : $label, ['class' => 'col-sm-3 col-form-label']) }} --}}
  <label for="{{ $name }}" class="col-sm-3 col-form-label">{!! empty($label) ? ucwords($name) : $label !!}</label>

  <div class="col">
    {{-- {{ Form::date($name, $value, array_merge(['class' => 'form-control'], $attributes)) }} --}}
    {{ html()->date($name, $value, $format)->attributes($inputAttributes)->class('form-control') }}
  </div>
</div>
