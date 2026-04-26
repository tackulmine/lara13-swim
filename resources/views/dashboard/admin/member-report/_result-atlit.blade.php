<div class="row justify-content-start mb-4">
  {{-- <div class="col-12 col-sm-auto mb-4 mb-sm-0 text-center text-sm-left">
    <div class="mt-2">
      <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" height="200">
    </div>
  </div> --}}
  {{-- <div class="col"> --}}
  <table class="table table-borderless table-sm">
    <tr>
      {{-- <td rowspan="6" align="right">
        <div class="mt-1">
          <img src="{{ $user->photo_url }}" alt="{{ $user->name }}">
        </div>
      </td> --}}
      <th width="30%">N a m a</th>
      <td width="1%">: </td>
      <td><span id="chart-label">{{ $user->name }}</span></td>
    </tr>
    <tr>
      <th>N I S / NISDA</th>
      <td>: </td>
      <td>{{ optional($user->userMember)->nis }}</td>
    </tr>
    {{-- <tr>
      <th>Tanggal Lahir</th>
      <td>: </td>
      <td>{{ optional(optional($user->profile)->birth_date)->format('d/M/Y') }}</td>
    </tr> --}}
    <tr>
      <th>{{ __('Sekolah') }}</th>
      <td>: </td>
      <td>{{ optional(optional(optional($user->educations)->first())->school)->name }}</td>
    </tr>
    <tr>
      <th>{{ __('Gaya') }}</th>
      <td>: </td>
      <td><span id="chart-title">{{ $gaya->name }}</span></td>
    </tr>
    <tr>
      <th>Target Poin</th>
      <td>: </td>
      <td>
        {{ $user->gayaLimits
            ? optional(
                optional($user->gayaLimits()->where('master_gaya_id', request('master_gaya_id')))->when(request()->filled('periode_start') && request()->filled('periode_end'), function ($query) {
                        $periodeStart = explode('-', request()->periode_start);
                        $periodeEnd = explode('-', request()->periode_end);

                        $query->where(function ($query) use ($periodeStart) {
                                $query->where('periode_year', '>=', $periodeStart[1])->where('periode_month', '>=', $periodeStart[0]);
                            })->where(function ($query) use ($periodeEnd) {
                                $query->where('periode_year', '<=', $periodeEnd[1])->where('periode_month', '<=', $periodeEnd[0]);
                            });
                    })->orderByDesc('periode_year')->orderByDesc('periode_month')->first(),
            )->point_text
            : '-' }}
      </td>
    </tr>
  </table>
  {{-- </div> --}}
</div>
