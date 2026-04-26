<div class="table-responsive mb-4">
  @include('layouts.partials._notif')
  <table class="table table-striped table-bordered table-sm data-chart" data-master-gaya="{{ $masterGayaId }}"
    id="data-chart-{{ $masterGayaId }}">
    <thead>
      @include($baseRouteName . '_table-head')
    </thead>
    {{-- <tfoot>
      @include($baseRouteName . '_table-head')
    </tfoot> --}}
    <tbody>
      @forelse ($userChampionshipGroups as $userChampionship)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ optional(optional($userChampionship->championshipEvent)->masterChampionship)->name }}</td>
          <td>{!! parseBetweenDate(
              optional($userChampionship->championshipEvent)->start_date,
              optional($userChampionship->championshipEvent)->end_date,
          ) !!}</td>
          <td data-point="{{ $userChampionship->point }}">{{ $userChampionship->point_text }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="100" align="center">No data found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
