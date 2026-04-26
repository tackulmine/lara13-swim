<div class="row">
  {{-- Left --}}
  <div class="col-lg-6">

    {{-- Profil --}}
    <div class="card mb-4">
      <div class="card-header py-3">
        <h6 class="m-0"><em>Profil</em></h6>
      </div>
      <div class="card-body">
        {{ Form::bs4HorFile(
            'photo',
            [
                'id' => 'customFilePhoto',
                // 'accept' => 'image/*'
                'accept' => 'image/gif, image/jpeg, image/png',
            ],
            'Foto',
            'Pilih file',
            $staff->preview_photo,
        ) }}
        {{ Form::bs4HorNumber('nik', $staff->nik, [], 'N I K') }}
        {{ Form::bs4HorText('name', $staff->name, ['required' => 'true', 'class' => 'form-control'], 'N a m a <span class="text-danger">*</span>') }}
        {{ Form::bs4HorRadios('gender', ['male' => 'Laki-laki', 'female' => 'Perempuan'], $staff->gender, __('Gender'), ['required' => 'required'], 'newline') }}
        {{-- {{ Form::bs4HorSelect('marital_status', ['single' => 'Belum Menikah', 'married' => 'Menikah'], $staff->marital_status, [], 'Status Nikah') }} --}}
        {{-- {{ Form::bs4HorSelect('nationality', ['indonesia' => 'Indonesia', 'foreign' => 'Asing'], $staff->nationality, [], 'Warga Negara') }} --}}
        {{-- {{ Form::bs4HorText('profession', $staff->profession, [], 'Pekerjaan') }} --}}
        {{ Form::bs4HorText('birth_place', $staff->birth_place, [], 'Tempat Lahir') }}
        {{-- {{ Form::bs4HorText('birth_date', (!empty($staff->birth_date)) ? $staff->birth_date->format('d/M/Y') : '', ['required' => 'true', 'class' => 'form-control dateOfBirth'], 'Tanggal Lahir') }} --}}
        <div class="form-group row">
          {{ Form::label('Tanggal Lahir', null, ['class' => 'col-sm-3 col-form-label']) }}
          <div class="col">
            <div class="input-group">
              {{ Form::text('birth_date', !empty($staff->birth_date) ? $staff->birth_date->format('d/M/Y') : '', ['class' => 'form-control dateOfBirth']) }}
              <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
              </div>
            </div>
          </div>
        </div>
        {{-- {{ Form::bs4HorTextarea('bio', $staff->bio, ['rows' => 2], 'Bio') }} --}}
      </div>
    </div>

    {{-- Position --}}
    <div class="card mb-4">
      <div class="card-header py-3">
        <h6 class="m-0"><em>Staff</em></h6>
      </div>
      <div class="card-body">
        {{ Form::bs4HorSelect('master_staff_type_id', $masterStaffTypeOptions, $staff->master_staff_type_id, ['required' => 'required'], 'Position') }}
      </div>
    </div>

  </div>

  {{-- Right --}}
  <div class="col-lg-6">

    {{-- Address n Contact --}}
    <div class="card mb-4">
      <div class="card-header py-3">
        <h6 class="m-0"><em>Alamat & Kontak</em></h6>
      </div>
      <div class="card-body">
        {{ Form::bs4HorText('address', $staff->address, [], 'Alamat') }}
        {{ Form::bs4HorText('location', $staff->location, [], 'Kec, Kab/Kota') }}
        {{-- {{ Form::bs4HorText('phone_number', $staff->phone_number, [], 'No Telp') }} --}}
        <div class="form-group row">
          {{ Form::label('No Telp', null, ['class' => 'col-sm-3 col-form-label']) }}
          <div class="col">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-phone fa-fw"></i></span>
              </div>
              {{ Form::number('phone_number', $staff->phone_number, ['class' => 'form-control']) }}
            </div>
          </div>
        </div>
        {{-- {{ Form::bs4HorText('second_phone_number', $staff->second_phone_number, [], 'No Telp 2') }} --}}
        <div class="form-group row">
          {{ Form::label('No Telp 2', null, ['class' => 'col-sm-3 col-form-label']) }}
          <div class="col">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-phone fa-fw"></i></span>
              </div>
              {{ Form::number('second_phone_number', $staff->second_phone_number, ['class' => 'form-control']) }}
            </div>
          </div>
        </div>
        {{-- {{ Form::bs4HorText('whatsapp_number', $staff->whatsapp_number, [], 'No Whatsapp') }} --}}
        <div class="form-group row">
          {{ Form::label('No Whatsapp', null, ['class' => 'col-sm-3 col-form-label']) }}
          <div class="col">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fab fa-whatsapp fa-fw"></i></span>
              </div>
              {{ Form::number('whatsapp_number', $staff->whatsapp_number, ['class' => 'form-control']) }}
            </div>
          </div>
        </div>
        {{-- {{ Form::bs4HorText('second_whatsapp_number', $staff->second_whatsapp_number, [], 'No Whatsapp 2') }} --}}
        <div class="form-group row">
          {{ Form::label('No Telegram', null, ['class' => 'col-sm-3 col-form-label']) }}
          <div class="col">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fab fa-telegram fa-fw"></i></span>
              </div>
              {{ Form::number('telegram_number', $staff->telegram_number, ['class' => 'form-control']) }}
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Akun --}}
    <div class="card mb-4">
      <div class="card-header py-3">
        <h6 class="m-0"><em>Akun</em></h6>
      </div>
      <div class="card-body">
        <div class="form-group row">
          {{ Form::label('Username', null, ['class' => 'col-sm-3 col-form-label']) }}
          <div class="col">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
              </div>
              {{ Form::text('username', $staff->username, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
          </div>
        </div>
        {{-- {{ Form::bs4HorEmail('email', $staff->email, [], 'Email <span class="text-danger">*</span>') }} --}}
        <div class="form-group row">
          {{ Form::label('Email', null, ['class' => 'col-sm-3 col-form-label']) }}
          <div class="col">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              {{ Form::email('email', $staff->email, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
          </div>
        </div>
        {{ Form::bs4HorPassword(
            'password',
            [],
            request()->is('*create') ? 'Password <span class="text-danger">*</span>' : 'Password',
            request()->is('*create') ? 'Password hanya berisi angka dan huruf.' : 'Biarkan kosong jika tidak mengganti!',
        ) }}
        {{ Form::bs4HorPassword(
            'password_confirmation',
            [],
            request()->is('*create') ? 'Konfirmasi Password <span class="text-danger">*</span>' : 'Konfirmasi Password',
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
                            {{ Form::text('facebook_id', $staff->facebook_id, ['class' => 'form-control']) }}
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
                            {{ Form::text('twitter_id', $staff->twitter_id, ['class' => 'form-control']) }}
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
                            {{ Form::text('instagram_id', $staff->instagram_id, ['class' => 'form-control']) }}
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
                            {{ Form::text('tiktok_id', $staff->tiktok_id, ['class' => 'form-control']) }}
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fab fa-tiktok fa-fw"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

  </div>
</div>
