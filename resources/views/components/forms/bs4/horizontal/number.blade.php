<div class="form-group row">
  {{-- {{ Form::label($name, empty($label) ? $name : $label, ['class' => 'col-sm-3 col-form-label']) }} --}}
  <label for="{{ $name }}" class="col-sm-3 col-form-label">{!! empty($label) ? ucwords($name) : $label !!}</label>

  <div class="col">
    {{-- {{ Form::number($name, $value, array_merge(['class' => 'form-control'], $attributes)) }} --}}
    {{-- number($name = null, $value = null, $min = null, $max = null, $step = null) --}}
    {{ html()->number($name, $value)->attributes(array_merge(['class' => 'form-control'], $inputAttributes)) }}
  </div>
</div>
