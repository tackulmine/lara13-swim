<div class="mb-4">
  <table class="table table-borderless table-sm">
    <tr>
      {{-- <td rowspan="6" align="right">
        <div class="mt-1">
          <img src="{{ $user->photo_url }}" width="150" alt="{{ $user->name }}">
        </div>
      </td> --}}
      <th width="15%">{{ __('Nama Lengkap') }}</th>
      <td width="1%">: </td>
      <td><span id="chart-label-{{ $masterGayaId }}">{{ $user->name }}</span></td>
    </tr>
    <tr>
      <th>{{ __('No Induk') }}</th>
      <td>: </td>
      <td>{{ $user->userMember->nis ?? '-' }}</td>
    </tr>
    {{-- <tr>
      <th>Tanggal Lahir</th>
      <td>: </td>
      <td>{{ optional(optional($user->profile)->birth_date)->format('d/M/Y') }}</td>
    </tr>
    <tr>
      <th>{{ __('Sekolah') }}</th>
      <td>: </td>
      <td>{{ optional(optional(optional($user->educations)->first())->school)->name }}</td>
    </tr> --}}
    {{-- @if ($gaya)
      <tr>
        <th>{{ __('Gaya') }}</th>
        <td>: </td>
        <td><span id="chart-title">{{ $gaya->name }}</span></td>
      </tr>
    @endif --}}
    <tr>
      <th>{{ __('Gaya') }}</th>
      <td>: </td>
      <td><span
          id="chart-title-{{ $masterGayaId }}">{{ optional($userChampionshipGroups->first()->masterChampionshipGaya)->name }}</span>
      </td>
    </tr>
  </table>
  {{-- </div> --}}
</div>
