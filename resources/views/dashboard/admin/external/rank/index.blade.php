@extends('layouts.app')

@section('content')
  <!-- Page Heading -->

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      <div class="float-right">
      <a href="{!! route($baseRouteName . 'import') !!}" title="Import Data Baru" class="btn btn-outline-primary btn-sm btn-create">
        <i class="far fa-file-excel"></i> Import Data
      </a>
      <a href="{!! route($baseRouteName . 'export') !!}" title="Download Data" class="btn btn-outline-success btn-sm">
        <i class="fa fa-download"></i> Download Data
      </a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @include('layouts.partials._notif')
          <table class="table table-striped table-bordered" id="dataTableCustom" width="100%" cellspacing="0"
            data-order="[[ 3, &quot;asc&quot; ], [ 10, &quot;asc&quot; ]]"
            >
            <thead>
              @include($baseViewPath . '_table-head')
            </thead>
            <tfoot>
              @include($baseViewPath . '_table-head')
            </tfoot>
            <tbody>
              @forelse ($bestTimes as $row)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $row->externalSwimmingAthlete->nisnas }}</td>
                  <td>{{ $row->externalSwimmingAthlete->name }}</td>
                  <td data-order="{{ $row->externalSwimmingStyle->id }}">{{ $row->externalSwimmingStyle->name }}</td>
                  <td>{{ $row->externalSwimmingAthlete->dob->format('j-M-Y') }}</td>
                  <td>{{ $row->externalSwimmingAthlete->externalSwimmingClub->name }}</td>
                  <td>{{ $row->externalSwimmingAthlete->externalSwimmingClub->masterCity->name }}</td>
                  <td>{{ $row->externalSwimmingAthlete->externalSwimmingClub->masterCity->masterProvince->name }}</td>
                  <td>{{ $row->externalSwimmingEvent->name }}</td>
                  <td>{{ $row->year }}</td>
                  <td data-order="{{ $row->point }}">{{ $row->point_text }}</td>
                  <td>{{ $row->fp }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="100" align="center">No data found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('js')
  @include($baseViewPath . '_table-js')
@endpush
