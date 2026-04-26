<div class="table-responsive mb-4">
  @include('layouts.partials._notif')
  <table class="table table-striped table-bordered" id="data-chart">
    <thead>
      @include($baseRouteName . '_table-head')
    </thead>
    {{-- <tfoot>
      @include($baseRouteName . '_table-head')
    </tfoot> --}}
    <tbody>
      @forelse ($memberLimits as $limit)
        @php
          // $user = optional($limit->user);
          // $gaya = optional($limit->gaya);
        @endphp
        <tr>
          <td>{{ $loop->iteration }}</td>
          {{-- <td>{{ $user->name }}</td>
        <td>{{ $gaya->name }}</td> --}}
          <td>{{ $limit->periode_to_date }}</td>
          <td>{{ $limit->periode_week }}</td>
          <td data-point="{{ $limit->point }}">{{ $limit->point_text }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="100" align="center">No data found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
