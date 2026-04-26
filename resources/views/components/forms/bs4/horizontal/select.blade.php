@php
  $labelName = str_replace(['[', ']', '_id'], '', $name);
  $defaultInputAttributes = [];
  if (!empty($inputAttributes)) {
      $defaultInputAttributes = $inputAttributes;
  }
  $inputAttributes = array_merge($defaultInputAttributes, [
      'class' => 'form-control',
      'id' => \Illuminate\Support\Str::slug($name),
  ]);
@endphp
<div class="form-group row">
  {{-- {{ Form::label(empty($label) ? str_replace(['[', ']', '_id'], '', $name) : $label, null, ['class' => 'col-sm-3 col-form-label']) }} --}}
  {{-- <label for="{{ $name }}" class="col-sm-3 col-form-label">{!! empty($label) ? ucwords($labelName) : $label !!}</label> --}}
  {{ html()->label(ucwords($label), $inputAttributes['id'] ?? '')->attributes(['class' => 'col-sm-3 col-form-label']) }}

  <div class="col-sm-9">
    {{-- {{ Form::select($name, $options, $value, array_merge(['class' => 'form-control'], $attributes)) }} --}}
    {{-- select($name = null, $options = [], $value = null) --}}
    {{ html()->select($name, $options, $value)->attributes($inputAttributes) }}
  </div>
</div>
