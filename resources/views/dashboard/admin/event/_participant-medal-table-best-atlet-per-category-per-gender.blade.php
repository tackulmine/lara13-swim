<table class="table table-light table-striped table-sm">
  <thead>
    <tr>
      <th width="10%">{{ __('Kategori') }}</th>
      <th width="10%" class="text-center">Gender</th>
      <th>Nama Atlet</th>
      <th>Nama Tim/Club</th>
      <th width="5%" class="text-center">Emas</th>
      <th width="5%" class="text-center">Perak</th>
      <th width="5%" class="text-center">Perunggu</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($bestAthletesPerCategoryPerGender as $category => $genderGroups)
      @php
        $totalRowsInCategory = $genderGroups->sum(fn($athletes) => $athletes->count());
        $firstRowInCategory = true;
      @endphp
      @foreach ($genderGroups->sortKeysDesc() as $gender => $athletes)
        @if ($athletes->isNotEmpty())
          @foreach ($athletes as $key => $athlete)
            <tr>
              @if ($firstRowInCategory)
                <td rowspan="{{ $totalRowsInCategory }}">{{ $athlete['data']->category_name }}</td>
                @php $firstRowInCategory = false; @endphp
              @endif
              @if ($loop->first)
                <td rowspan="{{ $athletes->count() }}" class="text-center">{{ strtoupper(parseGender($gender)) }}</td>
              @endif
              <td>{{ $athlete['data']->participant_name }}</td>
              <td>{{ $athlete['data']->school_name }}</td>
              <td class="text-center">{{ $athlete['gold'] }}</td>
              <td class="text-center">{{ $athlete['silver'] }}</td>
              <td class="text-center">{{ $athlete['bronze'] }}</td>
            </tr>
          @endforeach
        @endif
      @endforeach
    @endforeach
  </tbody>
</table>

<div class="mt-3">
  <button type="button" class="btn btn-success" id="downloadExcelBestAthletes">
    <i class="fas fa-file-excel"></i> Download Excel
  </button>
</div>

@push('js')
  <script>
    $(document).ready(function() {
      $('#downloadExcelBestAthletes').on('click', function() {
        const wb = XLSX.utils.book_new();
        const data = [];

        data.push(['{{ __('Kategori') }}', 'Gender', 'Nama Atlet', 'Nama Tim/Club', 'Emas', 'Perak',
          'Perunggu'
        ]);

        @foreach ($bestAthletesPerCategoryPerGender as $category => $genderGroups)
          @foreach ($genderGroups->sortKeysDesc() as $gender => $athletes)
            @if ($athletes->isNotEmpty())
              @foreach ($athletes as $key => $athlete)
                data.push([
                  '{{ $athlete['data']->category_name }}',
                  '{{ strtoupper(parseGender($gender)) }}',
                  '{{ $athlete['data']->participant_name }}',
                  '{{ $athlete['data']->school_name }}',
                  {{ $athlete['gold'] }},
                  {{ $athlete['silver'] }},
                  {{ $athlete['bronze'] }}
                ]);
              @endforeach
            @endif
          @endforeach
        @endforeach

        const ws = XLSX.utils.aoa_to_sheet(data);
        XLSX.utils.book_append_sheet(wb, ws, 'Atlet Terbaik');
        XLSX.writeFile(wb, 'Atlet_Terbaik_Per_Kategori.xlsx');
      });
    });
  </script>
@endpush
