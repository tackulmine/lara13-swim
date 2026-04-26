<div class="form-group row">
  {{ Form::label(empty($label) ? str_replace(["[","]","_id"], "", $name) : $label, null, ['class' => 'col-sm-3 col-form-label']) }}
  @php
    $attributes = array_merge(['class' => 'custom-file-input', 'id' => str_replace(["[","]","_id"], "", $name)], $attributes);
  @endphp
  <div class="col">
    {!! !empty($oldPreviewFileHtml) ? '<div class="mb-2">'.$oldPreviewFileHtml.'</div>' : '' !!}
    <div class="custom-file">
      {{ Form::file($name, $attributes) }}
      <label class="custom-file-label" for="{{ $attributes['id'] }}">{{ $fileLabel ?? 'Choose file' }}</label>
    </div>
  </div>
</div>
