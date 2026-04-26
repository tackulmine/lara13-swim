<div class="form-group row">
  <div class="col offset-sm-3">
    <div class="custom-control custom-switch">
      @php
        $defaultInputAttributes = [];
        if (!empty($inputAttributes)) {
            $defaultInputAttributes = $inputAttributes;
        }
        if (!isset($defaultInputAttributes['id'])) {
            $defaultInputAttributes['id'] = $name;
        }
        $inputAttributes = array_merge($defaultInputAttributes, ['class' => 'custom-control-input']);
      @endphp
      {{-- {{ Form::checkbox($name, $value, $checked, $inputAttributes) }} --}}
      {{-- checkbox($name = null, $checked = false, $value = '1') --}}
      {{ html()->checkbox($name, $checked, $value)->attributes(array_merge(['class' => 'form-control'], $inputAttributes)) }}

      {{-- {{ Form::label($defaultInputAttributes['id'] ?? $name, ucwords($label), ['class' => 'custom-control-label']) }} --}}
      {{-- label($contents = null, $for = null) --}}
      {{ html()->label(ucwords($label), $defaultInputAttributes['id'] ?? $name)->attributes(['class' => 'custom-control-label']) }}
    </div>
  </div>
</div>
