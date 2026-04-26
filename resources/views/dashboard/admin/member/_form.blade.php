@php
  $uploadAttributes = ['accept' => 'image/png, image/jpeg', 'lang' => 'id'];
@endphp

{{-- Tabs --}}

<nav class="nav nav-tabs" id="myTab" role="tablist">
  <a class="nav-link active" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab"
    aria-controls="profile" aria-selected="true">Profil</a>
  <a class="nav-link" id="attachment-tab" data-toggle="tab" data-target="#attachment" type="button" role="tab"
    aria-controls="attachment" aria-selected="false">Dokuman Tambahan</a>
  <a class="nav-link" id="account-tab" data-toggle="tab" data-target="#account" type="button" role="tab"
    aria-controls="account" aria-selected="false">Akun</a>
</nav>

<div class="tab-content pt-4" id="myTabContent">
  <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    {{-- @if (!auth()->user()->isSuperuser() && auth()->user()->isMember()) --}}
    {{ Form::bs4HorHidden('master_school', 'CENTRUM SC') }}
    {{ Form::bs4HorHidden('master_member_type_id', App\Models\MasterMemberType::ATHLETE_ID) }}
    {{-- @else
      {{ Form::bs4HorSelect(
          'master_school',
          $masterSchoolOptions,
          $member->master_school,
          [
              'class' => 'form-control',
              'data-tags' => 'true',
              'data-placeholder' => 'Pilih atau ketik baru',
              'data-allow-clear' => 'true',
          ],
          __('Sekolah'),
      ) }}
      {{ Form::bs4HorSelect('master_member_type_id', $masterMemberTypeOptions, $member->master_member_type_id, ['class' => 'form-control', 'required' => 'required'], 'Status') }}
      {{ Form::bs4HorRadios('master_member_type_id', $masterMemberTypeOptions, $member->master_member_type_id, 'Status', ['required' => 'required'], 'newline') }}
    @endif --}}
    {{ Form::bs4HorFile(
        'photo',
        $uploadAttributes + (!empty($member->photo) || auth()->user()->isCoach() ? [] : ['required' => 'required']),
        'Foto Atlet',
        'Pilih file foto (min: 300x400 pixel, max: 2MB)',
        $member->preview_fancy_photo,
    ) }}
    {{-- {{ Form::bs4HorText('nik', $member->nik, [], 'N I K') }} --}}
    {{ Form::bs4HorText('nis', $member->nis, [], 'No. Induk ' . __('Atlet')) }}
    {{ Form::bs4HorText('name', $member->name, ['required' => 'true', 'class' => 'form-control toUppercase'], __('Nama Lengkap Atlet')) }}
    {{ Form::bs4HorRadios('gender', $genderOptions, $member->gender, __('Gender'), ['required' => 'required'], 'newline') }}
    {{-- {{ Form::bs4HorRadios('relegion', $relegionOptions, $member->relegion, __('Agama'), ['required' => 'required'], 'newline') }} --}}
    {{ Form::bs4HorSelect('relegion', $relegionOptions, $member->relegion, ['class' => 'form-control', 'required' => 'required'], __('Agama')) }}
    {{-- {{ Form::bs4HorSelect('marital_status', ['single' => 'Belum Menikah', 'married' => 'Menikah'], $member->marital_status, [], 'Status Nikah') }} --}}
    {{-- {{ Form::bs4HorSelect('nationality', ['indonesia' => 'Indonesia', 'foreign' => 'Asing'], $member->nationality, [], 'Warga Negara') }} --}}
    {{-- {{ Form::bs4HorText('profession', $member->profession, [], 'Pekerjaan') }} --}}
    {{-- {{ Form::bs4HorText('birth_place', $member->birth_place, ['class' => 'form-control toUppercase', 'required' => 'required'], __('Tempat Lahir')) }} --}}
    <div class="form-group row">
      {{ Form::label('Tempat Lahir', null, ['class' => 'col-sm-3 col-form-label']) }}
      <div class="col">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-map-marker fa-fw"></i></span>
          </div>
          {{ Form::text('birth_place', $member->birth_place ?? '', ['class' => 'form-control toUppercase' . ($errors->has('birth_place') ? ' is-invalid' : ''), 'required' => 'required']) }}
        </div>
        @error('birth_place')
          <span class="invalid-feedback" style="display: block;" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
    </div>
    {{-- {{ Form::bs4HorText('birth_date', (!empty($member->birth_date)) ? optional($member->birth_date)->format('d/M/Y') : '', ['required' => 'true', 'class' => 'form-control dateOfBirth'], 'Tanggal Lahir') }} --}}
    <div class="form-group row">
      {{ Form::label('Tanggal Lahir', null, ['class' => 'col-sm-3 col-form-label']) }}
      <div class="col">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-calendar-alt fa-fw"></i></span>
          </div>
          {{ Form::text('birth_date', !empty($member->birth_date) ? optional($member->birth_date)->format('d/M/Y') : '', ['class' => 'form-control dateOfBirth' . ($errors->has('birth_date') ? ' is-invalid' : ''), 'required' => 'required']) }}
        </div>
        @error('birth_date')
          <span class="invalid-feedback" style="display: block;" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
    </div>
    {{-- {{ Form::bs4HorTextarea('bio', $member->bio, ['rows' => 2], 'Bio') }} --}}
    {{ Form::bs4HorText('address', $member->address, [], __('Alamat sesuai KTP')) }}
    {{-- {{ Form::bs4HorText('phone_number', $member->phone_number, [], 'No Telp') }} --}}
    {{-- {{ Form::bs4HorText('weight', $member->weight, [], 'Berat') }} --}}
    <div class="form-group row">
      {{ Form::label('Berat badan', null, ['class' => 'col-sm-3 col-form-label']) }}
      <div class="col">
        <div class="input-group">
          {{ Form::number('weight', $member->weight, ['class' => 'form-control', 'min' => 0, 'step' => '.01']) }}
          <div class="input-group-append">
            <span class="input-group-text">kg</span>
          </div>
        </div>
      </div>
    </div>
    {{-- {{ Form::bs4HorText('height', $member->tinggi, [], 'Tinggi') }} --}}
    <div class="form-group row">
      {{ Form::label('Tinggi badan', null, ['class' => 'col-sm-3 col-form-label']) }}
      <div class="col">
        <div class="input-group">
          {{ Form::number('height', $member->height, ['class' => 'form-control', 'min' => 0, 'step' => 1]) }}
          <div class="input-group-append">
            <span class="input-group-text">cm</span>
          </div>
        </div>
      </div>
    </div>
    {{-- {{ Form::bs4HorRadios('last_education', $educationOptions, $member->last_education, __('Pendidikan Terakhir'), ['required' => 'required'], 'newline') }} --}}
    {{ Form::bs4HorSelect('last_education', $educationOptions, $member->last_education, ['class' => 'form-control', 'required' => 'required'], __('Pendidikan Terakhir')) }}
    <div class="form-group row">
      {{ Form::label('No. Telp/ WhatsApp (WA)', null, ['class' => 'col-sm-3 col-form-label']) }}
      <div class="col">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-phone fa-fw"></i></span>
          </div>
          {{ Form::number('phone_number', $member->phone_number, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
      </div>
    </div>
    {{-- {{ Form::bs4HorText('location', $member->location, ['class' => 'form-control toUppercase'], 'Kec, Kab/Kota') }} --}}
    {{-- {{ Form::bs4HorText('second_phone_number', $member->second_phone_number, [], 'No Telp 2') }} --}}
    {{-- <div class="form-group row">
      {{ Form::label('No Telp 2', null, ['class' => 'col-sm-3 col-form-label']) }}
      <div class="col">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-phone fa-fw"></i></span>
          </div>
          {{ Form::number('second_phone_number', $member->second_phone_number, ['class' => 'form-control']) }}
        </div>
      </div>
    </div> --}}
    {{-- {{ Form::bs4HorText('whatsapp_number', $member->whatsapp_number, [], 'No Whatsapp') }} --}}
    {{-- <div class="form-group row">
      {{ Form::label('No. Whatsapp', null, ['class' => 'col-sm-3 col-form-label']) }}
      <div class="col">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fab fa-whatsapp fa-fw"></i></span>
          </div>
          {{ Form::number('whatsapp_number', $member->whatsapp_number, ['class' => 'form-control']) }}
        </div>
      </div>
    </div> --}}
    {{-- {{ Form::bs4HorText('second_whatsapp_number', $member->second_whatsapp_number, [], 'No Whatsapp 2') }} --}}
    {{-- <div class="form-group row">
      {{ Form::label('No Telegram', null, ['class' => 'col-sm-3 col-form-label']) }}
      <div class="col">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fab fa-telegram fa-fw"></i></span>
          </div>
          {{ Form::number('telegram_number', $member->telegram_number, ['class' => 'form-control']) }}
        </div>
      </div>
    </div> --}}
    {{-- {{ Form::bs4HorText('ayah', $member->ayah, [], 'Ayah') }} --}}
    {{-- <div class="form-group row">
      {{ Form::label('Ayah', null, ['class' => 'col-sm-3 col-form-label']) }}
      <div class="col">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-male fa-fw"></i></span>
          </div>
          {{ Form::text('ayah', $member->ayah, ['class' => 'form-control toUppercase']) }}
        </div>
      </div>
    </div> --}}
    {{-- {{ Form::bs4HorText('ibu', $member->ibu, [], 'Ibu') }} --}}
    {{-- <div class="form-group row">
      {{ Form::label('Ibu', null, ['class' => 'col-sm-3 col-form-label']) }}
      <div class="col">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-female fa-fw"></i></span>
          </div>
          {{ Form::text('ibu', $member->ibu, ['class' => 'form-control toUppercase']) }}
        </div>
      </div>
    </div> --}}
  </div>

  <div class="tab-pane fade" id="attachment" role="tabpanel" aria-labelledby="attachment-tab">
    {{ Form::bs4HorFile(
        'birth_certificate',
        $uploadAttributes +
            (!empty($member->birth_certificate) || auth()->user()->isCoach() ? [] : ['required' => 'required']),
        'Akta Kelahiran',
        'Pilih file Akta (min: 600x800 pixel, max: 2MB)',
        !empty($member->birth_certificate)
            ? '<a href="' .
                getFileCustomSize($member->birth_certificate_url) .
                '" class="d-inline-block" data-fancybox data-caption="' .
                $member->name .
                '" data-toggle="tooltip" title="click to zoom">' .
                $member->preview_birth_certificate .
                '</a>'
            : '',
    ) }}
    {{ Form::bs4HorFile(
        'family_card',
        $uploadAttributes + (!empty($member->family_card) || auth()->user()->isCoach() ? [] : ['required' => 'required']),
        'KK (Kartu Keluarga)',
        'Pilih file KK (min: 800x600 pixel, max: 2MB)',
        !empty($member->family_card)
            ? '<a href="' .
                getFileCustomSize($member->family_card_url) .
                '" class="d-inline-block" data-fancybox data-caption="' .
                $member->name .
                '" data-toggle="tooltip" title="click to zoom">' .
                $member->preview_family_card .
                '</a>'
            : '',
    ) }}
    {{ Form::bs4HorFile(
        'kta_card',
        $uploadAttributes,
        'KTA (Kartu Tanda Anggota)',
        'Pilih file KTA (min: 400x300 pixel, max: 2MB)',
        !empty($member->kta_card)
            ? '<a href="' .
                getFileCustomSize($member->kta_card_url) .
                '" class="d-inline-block" data-fancybox data-caption="' .
                $member->name .
                '" data-toggle="tooltip" title="click to zoom">' .
                $member->preview_kta_card .
                '</a>'
            : '',
    ) }}
    @if (auth()->user()->isCoach())
      <div class="form-group row">
        {{ Form::label('Tanda Tangan', null, ['class' => 'col-sm-3 col-form-label']) }}
        <div class="col">
          {!! $member->signature ? $member->preview_fancy_signature : 'Tidak ada data' !!}
        </div>
      </div>
    @endif
  </div>

  <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
    <div class="form-group row">
      {{ Form::label('Email', null, ['class' => 'col-sm-3 col-form-label']) }}
      <div class="col">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          </div>
          {{ Form::email('email', $member->email, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
      </div>
    </div>
    {{ Form::bs4HorText('username', $member->username, ['required' => 'true', 'class' => 'form-control', 'placeholder' => 'satu kata'], __('Username/Panggilan')) }}
    {{ Form::bs4HorPassword(
        'password',
        request()->is('*create') ? ['required' => 'required'] : [],
        'Password',
        request()->is('*create') ? 'Password hanya berisi angka dan huruf.' : 'Biarkan kosong jika tidak mengganti!',
    ) }}
    {{ Form::bs4HorPassword(
        'password_confirmation',
        request()->is('*create') ? ['required' => 'required'] : [],
        'Konfirmasi Password',
        request()->is('*create')
            ? 'Ketik kembali password anda untuk konfirmasi!'
            : 'Password hanya berisi angka dan huruf.',
    ) }}
  </div>
</div>

{{-- Socmed --}}
{{-- <div class="card mb-4">
    <div class="card-header py-3">
        <h6 class="m-0"><em>Sosial Media</em></h6>
    </div>
    <div class="card-body">
        <div class="form-group row">
            {{ Form::label('Facebook ID', null, ['class' => 'col-sm-3 col-form-label']) }}
            <div class="col">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-at fa-fw"></i></span>
                    </div>
                    {{ Form::text('facebook_id', $member->facebook_id, ['class' => 'form-control']) }}
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fab fa-facebook fa-fw"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('Twitter ID', null, ['class' => 'col-sm-3 col-form-label']) }}
            <div class="col">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-at fa-fw"></i></span>
                    </div>
                    {{ Form::text('twitter_id', $member->twitter_id, ['class' => 'form-control']) }}
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fab fa-twitter fa-fw"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('Instagram ID', null, ['class' => 'col-sm-3 col-form-label']) }}
            <div class="col">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-at fa-fw"></i></span>
                    </div>
                    {{ Form::text('instagram_id', $member->instagram_id, ['class' => 'form-control']) }}
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fab fa-instagram fa-fw"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('Tiktok ID', null, ['class' => 'col-sm-3 col-form-label']) }}
            <div class="col">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-at fa-fw"></i></span>
                    </div>
                    {{ Form::text('tiktok_id', $member->tiktok_id, ['class' => 'form-control']) }}
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fab fa-tiktok fa-fw"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- .card-body -->
</div> --}}
