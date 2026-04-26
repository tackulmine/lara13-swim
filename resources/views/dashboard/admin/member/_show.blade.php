<div class="row">
  {{-- Left --}}
  <div class="col-12 col-sm-auto mb-4 mb-sm-0 text-center text-sm-left order-1">
    <div class="mt-1">
      <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="rounded-lg">
    </div>
  </div>
  <div class="col">

    @php
      $memberProfile = optional($member->profile);
      $userMember = optional($member->userMember);
    @endphp

    <table class="table table-borderless table-sm">
      {{-- <tr>
                <td colspan=100>Profil ---</td>
            </tr> --}}
      <tr>
        <th>No. Induk {{ __('Atlet') }}</th>
        <td>: </td>
        <td>{{ $userMember->nis ?? '-' }}</td>
      </tr>
      <tr>
        <th width="30%">{{ __('Nama Lengkap Atlet') }}</th>
        <td width="1%">: </td>
        <td>{{ $member->name }}</td>
      </tr>
      <tr>
        <th>{{ __('Gender') }}</th>
        <td>: </td>
        <td>{{ $memberProfile->gender_name }}</td>
      </tr>
      <tr>
        <th>{{ __('Agama') }}</th>
        <td>: </td>
        <td>{{ $memberProfile->relegion ? getRelegionNameBySlug($memberProfile->relegion) : '-' }}</td>
      </tr>
      <tr>
        <th>Tempat, Tanggal Lahir</th>
        <td>: </td>
        <td>{{ $memberProfile->birth_place ?? '-' }}, {{ $memberProfile->birth_date_format }}</td>
      </tr>
      <tr>
        <th>Alamat</th>
        <td>: </td>
        <td>{{ $memberProfile->address ?? '-' }}</td>
      </tr>
      {{-- <tr>
          <th>Umur</th>
          <td>: </td>
          <td>{{ $memberProfile->age }}</td>
      </tr> --}}
      {{-- <tr>
        <th>{{ __('Sekolah') }}</th>
        <td>: </td>
        <td>{{ optional(optional(optional($member->educations)->first())->school)->name ?? '-' }}</td>
      </tr> --}}
      {{-- <tr>
                <td colspan=100>{{ __('Atlet') }} ---</td>
            </tr> --}}
      {{-- <tr>
        <th>Status</th>
        <td>: </td>
        <td>{{ optional($userMember->type)->name }}</td>
      </tr> --}}
      <tr>
        <th>Berat badan</th>
        <td>: </td>
        <td>{{ $memberProfile->weight ? $memberProfile->weight . ' kg' : '-' }}</td>
      </tr>
      <tr>
        <th>Tinggi badan</th>
        <td>: </td>
        <td>{{ $memberProfile->height ? $memberProfile->height . ' cm' : '-' }}</td>
      </tr>
      {{-- <tr>
          <td colspan=100>Alamat dan Kontak ---</td>
      </tr> --}}
      {{-- <tr>
        <th>Kec, Kab/Kota</th>
        <td>: </td>
        <td>{{ $memberProfile->location ?? '-' }}</td>
      </tr> --}}
      {{-- <tr>
        <th>{{ __('Username') }}</th>
        <td>: </td>
        <td>{{ $member->username }}</td>
      </tr> --}}
      <tr>
        <th>Pendidikan Terakhir</th>
        <td>: </td>
        <td>{{ $memberProfile->last_education ? getEducationNameBySlug($memberProfile->last_education) : '-' }}</td>
      </tr>
      <tr>
        <th>No Telp/ WA</th>
        <td>: </td>
        <td>{{ $memberProfile->phone_number ?? '-' }}</td>
      </tr>
      <tr>
        <th>Email</th>
        <td>: </td>
        <td>{{ $member->email ?? '-' }}</td>
      </tr>
      {{-- <tr>
        <th>No Telp 2</th>
        <td>: </td>
        <td>{{ $memberProfile->second_phone_number ?? '-' }}</td>
      </tr>
      <tr>
        <th>Whatsapp</th>
        <td>: </td>
        <td>{{ $memberProfile->whatsapp_number ?? '-' }}</td>
      </tr>
      <tr>
        <th>Telegram</th>
        <td>: </td>
        <td>{{ $memberProfile->telegram_number ?? '-' }}</td>
      </tr>
      <tr>
          <th>Facebook ID</th>
          <td>: </td>
          <td>{{ $memberProfile->facebook_id }}</td>
      </tr>
      <tr>
          <th>Twitter ID</th>
          <td>: </td>
          <td>{{ $memberProfile->twitter_id }}</td>
      </tr>
      <tr>
          <th>Instagram ID</th>
          <td>: </td>
          <td>{{ $memberProfile->instagram_id }}</td>
      </tr>
      <tr>
          <th>Tiktok ID</th>
          <td>: </td>
          <td>{{ $memberProfile->tiktok_id }}</td>
      </tr>
      <tr>
          <td colspan=100>Orang Tua ---</td>
      </tr>
      <tr>
        <th>Ayah</th>
        <td>: </td>
        <td>{{ $memberProfile->ayah ?? '-' }}</td>
      </tr>
      <tr>
        <th>Ibu</th>
        <td>: </td>
        <td>{{ $memberProfile->ibu ?? '-' }}</td>
      </tr> --}}
    </table>
  </div>
</div>
