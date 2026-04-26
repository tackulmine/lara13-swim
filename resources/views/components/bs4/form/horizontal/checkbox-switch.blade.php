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
            $inputAttributes = array_merge($defaultInputAttributes, ['class' => "custom-control-input"]);
        @endphp
        {{ Form::checkbox($name, $value, $checked, $inputAttributes) }}
        {{ Form::label($defaultInputAttributes['id'] ?? $name, ucwords($display), ['class' => 'custom-control-label']) }}
    </div>
  </div>
</div>
