<div class="form-group">
  {{ Form::text('name', old('name'), [
      'class' => 'form-control form-control-user toUppercase',
      'placeholder' => __('Nama Lengkap Atlet'),
      'required' => 'required',
      'autocomplete' => 'name',
      'autofocus' => true,
  ]) }}
  {{-- <input type="text"
    class="form-control form-control-user toUppercase @error('name') is-invalid @enderror" id="name"
    placeholder="{{ __('Nama Lengkap Atlet') }}" name="name" value="{{ old('name') }}" required
    autocomplete="name" autofocus> --}}
  @error('name')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
  @enderror
</div>

<div class="form-group">
  {{ Form::text('username', old('username'), [
      'class' => 'form-control form-control-user toLowercase',
      'placeholder' => __('Username/Panggilan'),
      'required' => 'required',
      'autocomplete' => 'username',
  ]) }}
  {{-- <input type="text" class="form-control form-control-user @error('username') is-invalid @enderror"
    id="username" placeholder="{{ __('Username') }}" name="username" value="{{ old('username') }}" required
    autocomplete="username"> --}}
  @if ($errors->has('username'))
    <span class="invalid-feedback">
      <strong>{{ $errors->first('username') }}</strong>
    </span>
  @endif
</div>

<div class="form-group">
  <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" id="email"
    placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ old('email', $email ?? '') }}" required
    autocomplete="email" {{ $email ? 'disabled' : '' }}>
  @if ($email)
    <input type="hidden" name="email" value="{{ $email }}">
  @endif

  @error('email')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
  @enderror
</div>

<div class="form-group row">
  <div class="col-sm-6 mb-3 mb-sm-0">
    <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror" id="password"
      placeholder="{{ __('Password') }}" name="password" required autocomplete="new-password">

    @error('password')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
    @enderror
  </div>
  <div class="col-sm-6">
    <input type="password" class="form-control form-control-user" id="password-confirm"
      placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required autocomplete="new-password">
  </div>
</div>

<div class="form-group">
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="photo" name="photo" accept="image/png, image/jpeg"
      lang="id" required>
    <label class="custom-file-label small" for="photo" data-browse="Pilih {{ __('Foto') }}">
      {{ __('Foto Atlet') }} (min: 300x400 pixel, max: 2MB)
    </label>
  </div>

  @if ($errors->has('photo'))
    <span class="invalid-feedback">
      <strong>{{ $errors->first('photo') }}</strong>
    </span>
  @endif
</div>

{{-- <div class="form-group">
<input type="text" class="form-control form-control-user" id="nis"
    placeholder="No. Induk {{ __('Atlet') }}" name="nis" value="{{ old('nis') }}"
    autocomplete="nis">
</div> --}}

<div class="form-group">
  <label for="{{ 'gender_' . array_keys($genderOptions)[0] }}" class="small">
    {{ __('Gender') }}:</label>
  @foreach ($genderOptions as $key => $label)
    <div class="custom-control custom-radio small">
      {{ Form::radio('gender', $key, $key == old('gender'), [
          'class' => 'custom-control-input',
          'required' => 'required',
          'id' => 'gender_' . $key,
      ]) }}
      <label class="custom-control-label" for="{{ 'gender_' . $key }}">
        {{ ucwords($label) }}
      </label>
    </div>
  @endforeach
</div>

<div class="form-group row">
  <div class="col-sm-6 mb-3 mb-sm-0">
    {{ Form::text('birth_place', old('birth_place'), [
        'class' => 'form-control form-control-user toUppercase' . ($errors->has('birth_place') ? ' is-invalid' : ''),
        'id' => 'birth_place',
        'required' => 'required',
        'placeholder' => __('Tempat Lahir'),
    ]) }}

    @error('birth_place')
      <span class="invalid-feedback" style="display: block;" role="alert">
        <strong>{{ $message }}</strong>
      </span>
    @enderror
  </div>
  <div class="col-sm-6">
    {{ Form::date('birth_date', old('birth_date'), [
        'class' => 'form-control form-control-user' . ($errors->has('birth_date') ? ' is-invalid' : ''),
        'id' => 'birth_date',
        'required' => 'required',
        'min' => now()->subYear(50)->toDateString(),
        'max' => now()->subYear(2)->toDateString(),
        'placeholder' => __('Tanggal Lahir'),
    ]) }}

    @error('birth_date')
      <span class="invalid-feedback" style="display: block;" role="alert">
        <strong>{{ $message }}</strong>
      </span>
    @enderror
  </div>
</div>

<div class="form-group row">
  <div class="col-sm-6 mb-3 mb-sm-0">
    {{ Form::select('relegion', $relegionOptions, old('relegion'), [
        'class' => 'custom-select custom-select-sm',
        'id' => 'relegion',
        'required' => 'required',
        'placeholder' => __('-- Pilih Agama --'),
    ]) }}

    @error('relegion')
      <span class="invalid-feedback" style="display: block;" role="alert">
        <strong>{{ $message }}</strong>
      </span>
    @enderror
  </div>
  <div class="col-sm-6">
    {{ Form::select('last_education', $educationOptions, old('last_education'), [
        'class' => 'custom-select custom-select-sm',
        'id' => 'last_education',
        'required' => 'required',
        'placeholder' => __('-- Pilih Pendidikan Terakhir --'),
    ]) }}
    @error('last_education')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
    @enderror
  </div>
</div>

<div class="form-group">
  {{ Form::text('address', old('address'), [
      'class' => 'form-control form-control-user' . ($errors->has('address') ? ' is-invalid' : ''),
      'placeholder' => __('Alamat sesuai KTP'),
      'required' => 'required',
      'autocomplete' => 'address',
      'autofocus' => true,
  ]) }}
  @error('address')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
  @enderror
</div>

<div class="form-group row">
  <div class="col-sm-6 mb-3 mb-sm-0">
    <div class="input-group">
      {{ Form::number('weight', old('weight'), [
          'class' => 'form-control form-control-user',
          'min' => 10,
          'step' => 1,
          'placeholder' => __('Berat Badan'),
      ]) }}
      <div class="input-group-append">
        <span class="input-group-text">kg</span>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="input-group">
      {{ Form::number('height', old('height'), [
          'class' => 'form-control form-control-user',
          'min' => 30,
          'step' => 1,
          'placeholder' => __('Tinggi Badan'),
      ]) }}
      <div class="input-group-append">
        <span class="input-group-text">cm</span>
      </div>
    </div>
  </div>
</div>

<div class="form-group">
  <label for="phone_number" class="small">{{ __('No. Telp/ WhatsApp (WA)') }}</label>
  <div class="input-group">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="fas fa-phone fa-fw"></i></span>
    </div>
    {{ Form::text('phone_number', old('phone_number'), [
        'class' => 'form-control form-control-user',
        'id' => 'phone_number',
        'required' => 'required',
        // 'placeholder' => __('No. Telp/ WhatsApp (WA)'),
        'data-inputmask' => "'mask': '9999-9999-9999-9'",
        'placeholder' => '08xx-xxxx-xxxx-x',
    ]) }}
  </div>
</div>

<div class="form-group">
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="birth_certificate" name="birth_certificate"
      accept="image/png, image/jpeg" lang="id" required>
    <label class="custom-file-label small" for="birth_certificate" data-browse="Pilih {{ __('Akta') }}">
      {{ __('Akta Kelahiran') }} (min: 600x800 pixel, max: 2MB)
    </label>
  </div>

  @error('birth_certificate')
    <span class="invalid-feedback">
      <strong>{{ $message }}</strong>
    </span>
  @enderror
</div>

<div class="form-group">
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="family_card" name="family_card"
      accept="image/png, image/jpeg" lang="id" required>
    <label class="custom-file-label small" for="family_card" data-browse="Pilih {{ __('KK') }}">
      {{ __('KK (Kartu Keluarga)') }} (min: 800x600 pixel, max: 2MB)
    </label>
  </div>

  @error('family_card')
    <span class="invalid-feedback">
      <strong>{{ $message }}</strong>
    </span>
  @enderror
  {{-- @if ($errors->has('family_card'))
    <span class="invalid-feedback">
      <strong>{{ $message }}</strong>
    </span>
  @endif --}}
</div>

<div id="signature-pad" class="signature-pad mb-4">
  <div id="canvas-wrapper" class="signature-pad--body" style="height: 200px;">
    <canvas style="border: 1px solid #000;"></canvas>
  </div>
  <div class="signature-pad--footer">
    <div class="description">Sign above</div>

    <div class="signature-pad--actions">
      <div class="column">
        <button type="button" class="button clear" data-action="clear">Clear</button>
        <button type="button" class="button" data-action="undo" title="Ctrl-Z">Undo</button>
        <button type="button" class="button" data-action="redo" title="Ctrl-Y">Redo</button>
        {{-- <br />
        <button type="button" class="button" data-action="change-color">Change color</button>
        <button type="button" class="button" data-action="change-width">Change width</button>
        <button type="button" class="button" data-action="change-background-color">Change background
        color</button> --}}
      </div>
      <div class="column">
        <button type="button" class="button save" data-action="save-ok">OK</button>
        {{-- <button type="button" class="button save" data-action="save-png">Save as PNG</button>
        <button type="button" class="button save" data-action="save-jpg">Save as JPG</button>
        <button type="button" class="button save" data-action="save-svg">Save as SVG</button>
        <button type="button" class="button save" data-action="save-svg-with-background">Save as SVG
        with background</button> --}}
      </div>
    </div>

    {{-- <div>
      <button type="button" class="button" data-action="open-in-window">Open in Window</button>
    </div> --}}
  </div>
</div>
{{ Form::hidden('signature_data', null, ['id' => 'signature_data']) }}

<div class="form-group">
  <div class="custom-control custom-checkbox small">
    <input type="checkbox" name="agreement" value="1" class="custom-control-input" id="agreement" required>
    <label class="custom-control-label" for="agreement">Menyetujui <a href="javascript:;" data-toggle="modal"
        data-target="#atlet-promise-modal">Janji Atlet</a>, <a href="javascript:;" data-toggle="modal"
        data-target="#parent-promise-modal">Janji Wali Atlet
      </a>, dan <a href="javascript:;" data-toggle="modal" data-target="#tatib-modal">Tata
        Tertib</a> Centrum SC</label>
  </div>
</div>

<div class="text-center">
  <button type="submit" class="btn btn-primary btn-user btn-block">
    {{ __('Register') }}
  </button>
</div>
