<div class="form-group row">
  {{-- {{ Form::label($name, empty($label) ? $name : $label, ['class' => 'col-sm-3 col-form-label']) }} --}}
  <label for="{{ $name }}" class="col-sm-3 col-form-label">{!! empty($label) ? ucwords($name) : $label !!}</label>

  <div class="col">
    <div class="input-group show-hide-password">
      {{-- {{ Form::password($name, array_merge(['class' => 'form-control'], $attributes)) }} --}}
      {{ html()->password($name)->attributes($inputAttributes)->class('form-control') }}

      <div class="input-group-append">
        <div class="input-group-text">
          <a href="#!" class="show-hide-btn"><i class="fa fa-eye-slash" data-toggle-showclass="fa-eye"
              data-toggle-hideclass="fa-eye-slash" aria-hidden="true"></i></a>
        </div>
      </div>
    </div>
    @if ($helpText)
      <small class="form-text text-muted">
        {{ $helpText == 'default' ? 'Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.' : $helpText }}
      </small>
    @endif
  </div>
</div>
