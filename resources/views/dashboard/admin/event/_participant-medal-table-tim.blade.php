<table class="table table-light table-striped table-sm">
  <thead>
    <tr>
      <th width="5%">No</th>
      <th>{{ __('Sekolah') }}</th>
      <th width="12%" class="text-right">Emas</th>
      <th width="12%" class="text-right">Perak</th>
      <th width="12%" class="text-right">Perunggu</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($medalTim as $tim)
      @php
        $rank = $tim['rank'];
        $trClass = in_array($rank, [1, 2, 3]) ? 'font-weight-bold' : '';
        $tdClass =
            $rank == 1
                ? 'text-center text-success'
                : ($rank == 2
                    ? 'text-center text-info'
                    : ($rank == 3
                        ? 'text-center text-warning'
                        : 'text-center'));
        $fontSize =
            $rank == 1
                ? 'font-size: 130%;'
                : ($rank == 2
                    ? 'font-size: 120%;'
                    : ($rank == 3
                        ? 'font-size: 110%;'
                        : ''));
      @endphp
      <tr class="{{ $trClass }}">
        <td class="{{ $tdClass }}" style="{{ $fontSize }}">{{ $rank }}</td>
        <td>{{ $tim['data']['school_name'] }}</td>
        <td class="text-right">{{ $tim['gold'] }}</td>
        <td class="text-right">{{ $tim['silver'] }}</td>
        <td class="text-right">{{ $tim['bronze'] }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<div class="mt-3">
  <button type="button" class="btn btn-success" id="downloadExcelRankingTim">
    <i class="fas fa-file-excel"></i> Download Excel
  </button>
</div>

@push('js')
  <script>
    $(document).ready(function() {
      $('#downloadExcelRankingTim').on('click', function() {
        const wb = XLSX.utils.book_new();
        const data = [];

        data.push(['No', '{{ __('Sekolah') }}', 'Emas', 'Perak', 'Perunggu']);

        @foreach ($medalTim as $tim)
          data.push([
            {{ $tim['rank'] }},
            '{{ $tim['data']['school_name'] }}',
            {{ $tim['gold'] }},
            {{ $tim['silver'] }},
            {{ $tim['bronze'] }}
          ]);
        @endforeach

        const ws = XLSX.utils.aoa_to_sheet(data);
        XLSX.utils.book_append_sheet(wb, ws, 'Ranking Tim');
        XLSX.writeFile(wb, 'Ranking_Tim.xlsx');
      });
    });
  </script>
@endpush
