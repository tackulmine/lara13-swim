<div class="form-group row {{ $formGroupClasses ?? '' }}">
  {{-- {{ Form::label($name, empty($label) ? $name : $label, ['class' => 'col-sm-3 col-form-label']) }} --}}
  <label for="{{ $name }}"
    class="col-sm-3 col-form-label {{ $formLabelClasses ?? '' }}">{!! empty($label) ? ucwords($name) : $label !!}</label>

  <div class="col">
    {{-- {{ Form::text($name, $value, array_merge(['class' => 'form-control'], $attributes)) }} --}}
    {{ html()->text($name, $value)->attributes(array_merge(['class' => 'form-control'], $inputAttributes)) }}
    @if ($formHelpText)
      <small class="form-text text-muted">{{ $formHelpText }}</small>
    @endif
  </div>
</div>
