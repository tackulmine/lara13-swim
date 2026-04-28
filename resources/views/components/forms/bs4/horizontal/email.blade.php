<div class="form-group row">
  {{-- {{ Form::label($name, empty($label) ? $name : $label, ['class' => 'col-sm-3 col-form-label']) }} --}}
  <label for="{{ $name }}" class="col-sm-3 col-form-label">{!! empty($label) ? ucwords($name) : $label !!}</label>

  <div class="col">
    {{-- {{ Form::email($name, $value, array_merge(['class' => 'form-control'], $attributes)) }} --}}
    {{ html()->email($name, $value)->attributes($inputAttributes)->class('form-control') }}
  </div>
</div>
