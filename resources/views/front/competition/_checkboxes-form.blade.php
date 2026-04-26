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
    // $additionalValue = null;
    // if (! empty($additionalValues)) {
    //   $additionalValue = $additionalValues->where('id', $cvalue)->first();
    // }
  @endphp
  {{-- <div class="input-group mb-1">
    <div class="input-group-prepend">
      <div class="input-group-text" id="my-addon"> --}}
  <div
    class="custom-control custom-checkbox mb-1 {{ !empty($separator) && $separator == 'inline' ? 'custom-control-inline' : '' }}">
    {{ Form::checkbox(
        $name,
        $cvalue,
        is_array($values) && count($values) > 0 && in_array($cvalue, $values) ? true : false,
        $inputAttributes,
    ) }}
    <label class="custom-control-label" for="{{ $rid }}">
      {{ ucwords($cname) }}
    </label><br>
    <input type="text" class="form-control text-right input-mask-time" data-inputmask-alias="datetime"
      data-inputmask-inputformat="MM:ss.L" data-inputmask-placeholder="00:00.00" {{-- placeholder="00:00.00" --}}
      {{-- placeholder="Prestasi" value="{{ ! empty($additionalValue) ? $additionalValue->pivot->point_text : '' }}" name="{{ $additionalName }}" id="{{ $addtRid }}" {{ ! empty($additionalValue) ? '' : 'disabled' }}> --}}
      placeholder="Prestasi" value="{{ ! empty($additionalValues[$cvalue]) && in_array($cvalue, array_keys($additionalValues)) ? $additionalValues[$cvalue] : '' }}" name="{{ $additionalName }}" id="{{ $addtRid }}" {{ ! empty($additionalValues[$cvalue]) ? '' : 'disabled' }}>
      <small class="form-text text-muted text-right">Biarkan kosong jika belum ada.</small>
  </div>
  {{-- </div>
    </div>
    <input type="text" class="form-control text-right input-mask-time" data-inputmask-alias="datetime"
      data-inputmask-inputformat="MM:ss.L" data-inputmask-placeholder="00:00.00"
      placeholder="Prestasi" value="" name="{{ $additionalName }}" id="{{ $addtRid }}" disabled>
  </div> --}}
  @php
    $i++;
  @endphp
@endforeach
