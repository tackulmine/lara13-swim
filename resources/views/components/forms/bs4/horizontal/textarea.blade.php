<div>
  <!-- An unexamined life is not worth living. - Socrates -->
  <div class="form-group row">
    {{-- {{ Form::label($name, empty($label) ? $name : $label, ['class' => 'col-sm-3 col-form-label']) }} --}}
    <label for="{{ $name }}" class="col-sm-3 col-form-label">{!! empty($label) ? ucwords($name) : $label !!}</label>

    <div class="col">
      {{-- {{ Form::textarea($name, $value, array_merge(['class' => 'form-control', 'style' => 'resize:none;'], $attributes)) }} --}}
      {{ html()->textarea($name, $value)->class('form-control')->style('resize:none;')->attributes($inputAttributes) }}
    </div>
  </div>

</div>
