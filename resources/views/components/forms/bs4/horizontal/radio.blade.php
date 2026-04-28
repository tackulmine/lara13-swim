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
      {{-- {{ Form::radio($name, $value, $checked, $inputAttributes) }} --}}
      {{ html()->radio($name, $value == $checked, $value)->attributes($inputAttributes) }}
      {{-- {{ Form::label($inputAttributes['id'] ?? '', ucwords($display), ['class' => 'custom-control-label']) }} --}}
      <label class="custom-control-label" for="{{ $inputAttributes['id'] ?? '' }}">{{ ucwords($label) }}</label>
    </div>
  </div>
</div>
