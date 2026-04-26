@php
  $labelName = str_replace(['[', ']', '_id'], '', $name);
  $addtLabelName = str_replace(['[', ']', '_id'], '', $additionalName);
  $i = 0;
@endphp
@foreach ($checkboxes as $cvalue => $cname)
  @php
    $rid = $labelName . '_' . $i;
    $addtRid = $addtLabelName . '_' . $i;
    $inputAttributes = [
        'class' => 'custom-control-input',
        'id' => $rid,
        'data-toggle-disabled-id' => $addtRid,
    ];
  @endphp
  <div
    class="custom-control custom-checkbox mb-1 {{ !empty($separator) && $separator == 'inline' ? 'custom-control-inline' : '' }}">
    {{-- {{ Form::checkbox(
        $name,
        $cvalue,
        is_array($values) && count($values) > 0 && in_array($cvalue, $values) ? true : false,
        $inputAttributes,
    ) }} --}}
    {{ html()->checkbox($name, is_array($values) && count($values) > 0 && in_array($cvalue, $values), $cvalue)->attributes($inputAttributes) }}
    <label class="custom-control-label" for="{{ $rid }}">
      {{ ucwords($cname) }}
    </label><br>
    <input type="number" min=1 class="form-control" placeholder="Min"
      value="{{ !empty($additionalValues[$cvalue]) && in_array($cvalue, array_keys($additionalValues)) ? $additionalValues[$cvalue] : '' }}"
      name="{{ $addtLabelName }}[{{ $cvalue }}]" id="{{ $addtRid }}"
      {{ !empty($additionalValues[$cvalue]) ? '' : 'disabled' }}>
  </div>
  @php
    $i++;
  @endphp
@endforeach
