<div class="form-group row">
  {{-- {{ Form::label(empty($label) ? str_replace(['[', ']', '_id'], '', $name) : $label, null, ['class' => 'col-sm-3 col-form-label']) }} --}}
  <label for="{{ str_replace(['[', ']', '_id'], '', $name) }}"
    class="col-sm-3 col-form-label">{{ empty($label) ? ucwords(str_replace(['[', ']', '_id'], '', $name)) : $label }}</label>
  @php
    $attributes = array_merge(
        ['class' => 'custom-file-input', 'id' => str_replace(['[', ']', '_id'], '', $name)],
        $inputAttributes,
    );
  @endphp
  <div class="col">
    {!! !empty($oldPreviewFileHtml) ? '<div class="mb-2">' . $oldPreviewFileHtml . '</div>' : '' !!}
    <div class="custom-file">
      {{-- {{ Form::file($name, $inputAttributes) }} --}}
      {{-- file($name = null) --}}
      {{ html()->file($name)->attributes($inputAttributes) }}
      <label class="custom-file-label" for="{{ $attributes['id'] }}">{{ $fileLabel ?? 'Choose file' }}</label>
    </div>
  </div>
</div>
