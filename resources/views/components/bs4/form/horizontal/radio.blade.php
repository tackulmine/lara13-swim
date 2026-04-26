<div class="form-group row">
  <div class="{{ $divClasses ? $divClasses : 'col offset-sm-3' }}">
    @php
      $defaultInputAttributes = [];
      if (!empty($inputAttributes)) {
          $defaultInputAttributes = $inputAttributes;
      }
      $inputAttributes = array_merge($defaultInputAttributes, [
          'class' => 'custom-control-input',
          'id' => \Illuminate\Support\Str::slug($name),
      ]);
    @endphp
    <div class="custom-control custom-radio">
      {{ Form::radio($name, $value, $checked, $inputAttributes) }}
      {{ Form::label($inputAttributes['id'] ?? '', ucwords($display), ['class' => 'custom-control-label']) }}
    </div>
  </div>
</div>
