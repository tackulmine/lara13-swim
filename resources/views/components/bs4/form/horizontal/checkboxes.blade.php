@php
  $labelName = str_replace(['[', ']', '_id'], '', $name);
@endphp
<div class="form-group row {{ $labelName }}-checkboxes {{ $formGroupClasses ?? '' }}">
  {{-- {{ Form::label(empty($label) ? $labelName : $label, null, ['class' => 'col-sm-3 col-form-label']) }} --}}
  <label class="col-sm-3 col-form-label {{ $formLabelClasses ?? '' }}">{!! empty($label) ? ucwords($labelName) : $label !!}</label>
  <div class="col">
    <div class="checkbox-list">
      @php
        $i = 0;
      @endphp
      @foreach ($checkboxes as $cvalue => $cname)
        @php
          $rid = $labelName . '_' . $i;
          $attributes = [
              'class' => 'custom-control-input',
              'id' => $rid,
          ];
          if (isset($inputAttributes) && count($inputAttributes)) {
              $attributes = array_merge(
                  [
                      'class' => 'custom-control-input',
                      'id' => $rid,
                  ],
                  $inputAttributes,
              );
          }
          if ($i > 0 && isset($attributes['required'])) {
              unset($attributes['required']);
          }
        @endphp
        <div
          class="custom-control custom-checkbox {{ !empty($separator) && $separator == 'inline' ? 'custom-control-inline' : '' }} {{ $formItemClasses ?? '' }}">
          {{ Form::checkbox($name, $cvalue, is_array($values) && count($values) > 0 && in_array($cvalue, $values) ? true : false, $attributes) }}
          <label class="custom-control-label" for="{{ $rid }}">
            {{ ucwords($cname) }}
          </label>
        </div>
        @php
          $i++;
        @endphp
      @endforeach
    </div>
  </div>
</div>
