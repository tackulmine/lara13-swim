<div class="row">
  <div class="col-lg-6">
    {{-- {{ Form::bs4HorText('name', $event->name, ['required' => 'true'], 'Nama') }} --}}
    <x-forms.bs4.horizontal.text name="name" :value="$event->name" :input-attributes="['required' => 'true']" />
    {{-- {{ Form::bs4HorText('slug', $event->slug, [], 'Slug') }} --}}
    <x-forms.bs4.horizontal.text name="slug" :value="$event->slug" :input-attributes="[]" />
    {{-- {{ Form::bs4HorText('address', $event->address, [], 'Alamat') }} --}}
    <x-forms.bs4.horizontal.text name="address" :value="$event->address" :input-attributes="[]" label="Alamat" />
    {{-- {{ Form::bs4HorText('location', $event->location, ['required' => 'true'], 'Lokasi') }} --}}
    <x-forms.bs4.horizontal.text name="location" :value="$event->location" :input-attributes="['required' => 'true']" label="Lokasi" />
    {{-- {{ Form::bs4HorText('date', (!empty($event->start_date) and !empty($event->end_date)) ? $event->start_date->format('d/m/Y H:i') . ' - ' . $event->end_date->format('d/m/Y H:i') : '', ['required' => 'true', 'class' => 'form-control daterange'], 'Tanggal') }} --}}
    <x-forms.bs4.horizontal.text name="date" :value="(!empty($event->start_date) and !empty($event->end_date))
        ? $event->start_date->format('d/m/Y H:i') . ' - ' . $event->end_date->format('d/m/Y H:i')
        : ''" :input-attributes="['required' => 'true', 'class' => 'form-control daterange']" label="Tanggal" />

    {{-- {{ Form::bs4HorFile(
        'photo',
        [
            'id' => 'customFilePhoto',
            // 'accept' => 'image/*'
            'accept' => 'image/gif, image/jpeg, image/png',
        ],
        'Logo',
        'Pilih file',
        $event->preview_photo,
    ) }} --}}
    <x-forms.bs4.horizontal.file name="photo" :input-attributes="[
        'id' => 'customFilePhoto',
        // 'accept' => 'image/*'
        'accept' => 'image/gif, image/jpeg, image/png',
    ]" label="Logo" file-label="Pilih file"
      :old-preview-file-html="$event->preview_photo" />
    {{-- {{ Form::bs4HorFile(
        'photo_right',
        [
            'id' => 'customFilePhotoRight',
            // 'accept' => 'image/*'
            'accept' => 'image/gif, image/jpeg, image/png',
        ],
        'Logo Kanan',
        'Pilih file',
        $event->preview_photo_right,
    ) }} --}}
    <x-forms.bs4.horizontal.file name="photo_right" :input-attributes="[
        'id' => 'customFilePhotoRight',
        // 'accept' => 'image/*'
        'accept' => 'image/gif, image/jpeg, image/png',
    ]" label="Logo Kanan" file-label="Pilih file"
      :old-preview-file-html="$event->preview_photo_right" />

    @if (auth()->user()->hasRole('coach'))
      {{-- {{ Form::bs4HorCheckboxSwitch('is_external', 1, $event->is_external, 'Kompetisi Eksternal?', []) }} --}}
      <x-forms.bs4.horizontal.checkbox-switch name="is_external" :value=1 :checked="$event->is_external" :input-attributes="[]"
        label="Kompetisi Eksternal?" />
      {{-- {{ Form::bs4HorCheckboxSwitch('is_has_mix_gender', 1, $event->is_has_mix_gender, 'Gaya Mix?', []) }} --}}
      <x-forms.bs4.horizontal.checkbox-switch name="is_has_mix_gender" :value=1 :checked="$event->is_has_mix_gender" :input-attributes="[]"
        label="Gaya Mix?" />
    @endif

    @if (auth()->user()->hasRole('coach'))
      <div class="card mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Jenis Lintasan Kolam</h6>
        </div>
        <div class="card-body">
          {{-- {{ Form::bs4HorNumber('start_track_number', $event->start_track_number, ['min' => 0], __('Lintasan mulai dari')) }}
          {{ Form::bs4HorNumber('total_track', $event->total_track, ['min' => 5], __('Jumlah Lintasan')) }} --}}
          <x-forms.bs4.horizontal.number name="start_track_number" :value="$event->start_track_number" :input-attributes="['min' => 0, 'max' => 1, 'step' => 1]"
            label="Lintasan mulai dari" />
          <x-forms.bs4.horizontal.number name="total_track" :value="$event->total_track" :input-attributes="['min' => 5, 'max' => 10, 'step' => 1]"
            label="Jumlah Lintasan" />
        </div>
      </div>
    @endif
    @if (auth()->user()->hasRole('coach'))
      <div class="card mb-4 mb-lg-0">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Copyright</h6>
        </div>
        <div class="card-body">
          {{-- {{ Form::bs4HorCheckboxSwitch('is_has_copyright', 1, $event->is_has_copyright, 'Edit Footer Copyright?', []) }} --}}
          <x-forms.bs4.horizontal.checkbox-switch name="is_has_copyright" :value=1 :checked="$event->is_has_copyright" :input-attributes="[]"
            label="Edit Footer Copyright?" />
          {{-- {{ Form::bs4HorText(
              'copyright_text',
              $event->copyright_text,
              [
                  'class' => 'form-control',
                  'disabled' => !$event->is_has_copyright ? true : false,
                  // 'autocomplete' => 'off',
              ],
              'Copyright Text',
          ) }} --}}
          <x-forms.bs4.horizontal.text name="copyright_text" :value="$event->copyright_text" :input-attributes="['class' => 'form-control', 'disabled' => !$event->is_has_copyright ? true : false]"
            label="Copyright Text" />

        </div>
      </div>
    @endif
  </div>

  <div class="col-lg-6">
    <div class="card">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pendaftaran</h6>
      </div>
      <div class="card-body">
        {{-- {{ Form::bs4HorCheckboxSwitch('is_reg', 1, $event->is_reg, 'Aktifkan', []) }} --}}
        <x-forms.bs4.horizontal.checkbox-switch name="is_reg" :value=1 :checked="$event->is_reg" :input-attributes="[]"
          label="Aktifkan" />

        @if (!empty($event->preview_fancy_qr_code))
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">QR Code Registrasi</label>

            <div class="col">
              {!! $event->preview_fancy_qr_code !!}
            </div>
          </div>
        @endif

        {{-- {{ Form::bs4HorText(
            'reg_end_date',
            !empty($event->reg_end_date) ? $event->reg_end_date->format('d/m/Y') : '',
            [
                'class' => 'form-control date',
                'disabled' => !$event->is_reg ? true : false,
                'autocomplete' => 'off',
            ],
            'Tanggal Berakhir',
        ) }} --}}
        <x-forms.bs4.horizontal.text name="reg_end_date" :value="!empty($event->reg_end_date) ? $event->reg_end_date->format('d/m/Y') : ''" :input-attributes="[
            'class' => 'form-control date',
            'disabled' => !$event->is_reg ? true : false,
            'autocomplete' => 'off',
        ]"
          label="Tanggal Berakhir" />
        {{-- {{ Form::bs4HorNumber('reg_quota', $event->reg_quota, ['min' => 1, 'disabled' => !$event->is_reg ? true : false], 'Max Kuota') }} --}}
        <x-forms.bs4.horizontal.number name="reg_quota" :value="$event->reg_quota" :input-attributes="[
            'min' => 1,
            'step' => 1,
            'disabled' => !$event->is_reg ? true : false,
        ]" label="Max Kuota" />
        {{-- {{ Form::bs4HorNumber('reg_style_min', $event->reg_style_min, ['min' => 1], 'Min ' . __('Gaya') . '') }} --}}
        <x-forms.bs4.horizontal.number name="reg_style_min" :value="$event->reg_style_min" :input-attributes="['min' => 1, 'step' => 1]" label="Min Gaya" />

        @if ($eventCatagories)
          <div class="form-group row">
            <label for="reg_cat_style_min" class="col-sm-3 col-form-label">Kustom Min {{ __('Gaya') }} per
              {{ __('Kategori') }}</label>

            <div class="col">
              @include('dashboard.admin.event._checkboxes-form', [
                  'checkboxes' => $eventCatagories ?? [],
                  'values' => $selectedRegCatStyleMinValues ? array_keys($selectedRegCatStyleMinValues) : [],
                  'name' => 'reg_cat_style_min_check',
                  'additionalName' => 'reg_cat_style_min[]',
                  'additionalValues' => $selectedRegCatStyleMinValues ?? [],
                  'separator' => 'block',
              ])
            </div>
          </div>
        @endif

        {{-- {{ Form::bs4HorNumber('reg_style_per_price', $event->reg_style_per_price, ['min' => 10000], 'Harga Per ' . __('Gaya') . ' (Non-Relay)') }} --}}
        <x-forms.bs4.horizontal.number name="reg_style_per_price" :value="$event->reg_style_per_price" :input-attributes="['min' => 10000, 'step' => 1000]"
          label="Harga Per Gaya (Non-Relay)" />

        {{-- {{ Form::bs4HorNumber('reg_style_max_price', $event->reg_style_max_price, ['min' => 10000], 'Harga Max ' . __('Gaya') . ' (Non-Relay)') }} --}}
        <x-forms.bs4.horizontal.number name="reg_style_max_price" :value="$event->reg_style_max_price" :input-attributes="['min' => 10000, 'step' => 1000]"
          label="Harga Max Gaya (Non-Relay)" />

        {{-- {{ Form::bs4HorNumber('reg_style_max_price_count', $event->reg_style_max_price_count, ['min' => 1], 'Jumlah Max ' . __('Gaya') . ' (Non-Relay)') }} --}}
        <x-forms.bs4.horizontal.number name="reg_style_max_price_count" :value="$event->reg_style_max_price_count" :input-attributes="['min' => 1, 'step' => 1]"
          label="Jumlah Max Gaya (Non-Relay)" />

        {{-- {{ Form::bs4HorNumber('reg_relay_per_price', $event->reg_relay_per_price, ['min' => 10000], 'Harga Per ' . __('Gaya') . ' (Relay)') }} --}}
        <x-forms.bs4.horizontal.number name="reg_relay_per_price" :value="$event->reg_relay_per_price" :input-attributes="['min' => 10000, 'step' => 1000]"
          label="Harga Per Gaya (Relay)" />

      </div>
    </div>
  </div>
</div>
