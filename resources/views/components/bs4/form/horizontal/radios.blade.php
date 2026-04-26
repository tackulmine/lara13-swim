@php
  $labelName = str_replace(['[', ']', '_id'], '', $name);
@endphp
<div class="form-group row {{ $labelName }}-radios {{ $formGroupClasses ?? '' }}">
  {{-- {{ Form::label(empty($label) ? $labelName : $label, null, ['class' => 'col-sm-3 col-form-label pt-0']) }} --}}
  <label class="col-sm-3 col-form-label pt-0 {{ $formLabelClasses ?? '' }}">{!! empty($label) ? ucwords($labelName) : $label !!}</label>
  <div class="col">
    <div class="radio-list">
      @php
        $i = 0;
      @endphp
      @foreach ($radios as $rvalue => $rname)
        @php
          $rid = $labelName . '_' . $i;
          $attributes = [
            'class' => 'custom-control-input',
            'id' => $rid,
          ];
          if (isset($inputAttributes) && count($inputAttributes)) {
              $attributes = array_merge(
                  $attributes,
                  $inputAttributes,
              );
          }
          if ($i > 0 && isset($attributes['required'])) {
              unset($attributes['required']);
          }
        @endphp
        <div
          class="custom-control custom-radio {{ !empty($separator) && $separator == 'inline' ? 'custom-control-inline' : '' }} {{ $formItemClasses ?? '' }}">
          {{ Form::radio($name, $rvalue, $rvalue == $value, $attributes) }}
          <label class="custom-control-label" for="{{ $rid }}">
            {{ ucwords($rname) }}
          </label>
        </div>
        @php
          $i++;
        @endphp
      @endforeach
    </div>
  </div>
</div>
