@extends('layouts.app')

@section('content')
  <!-- Page Heading -->
  {{-- <h1 class="h3 mb-4 text-gray-800">Kompetisi</h1> --}}

  @include('layouts.partials._breadcrumbs')

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      <a href="{!! route($baseRouteName . 'create', $event) !!}" title="Tambah Baru" class="btn btn-outline-primary btn-sm btn-create float-right">
        <i class="fas fa-plus"></i> Baru
      </a>
      <a href="{!! route($baseRouteName . 'create-batch', $event) !!}" title="Tambah Batch Baru"
        class="btn btn-outline-primary btn-sm btn-create float-right mr-1">
        <i class="fas fa-plus"></i> Batch Baru
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @include('layouts.partials._notif')
        {{-- <table class="table table-striped table-bordered dt-responsive nowrap" --}}
        <table class="table table-striped table-bordered" id="dataTableCustom" width="100%" cellspacing="0"
          data-order="[[ 1, &quot;asc&quot; ], [ 2, &quot;asc&quot; ]]">
          <thead>
            @include($baseViewPath . '_table-head')
          </thead>
          <tfoot>
            @include($baseViewPath . '_table-head')
          </tfoot>
          <tbody>
            @foreach ($participants as $participant)
              <tr>
                <td>{{ $loop->iteration }}</td>
                {{-- <td>{{ $participant->user->name }}</td> --}}
                <td><a href="#!" data-toggle="tooltip" data-order="{{ optional($participant->user)->username }}"
                    title="{{ optional($participant->user)->name }}">{{ optional($participant->user)->username }}</a>
                </td>
                <td>{{ optional($participant->masterChampionshipGaya)->name }}</td>
                <td class="text-right" data-order="{!! $participant->point !!}">{{ $participant->point_text }}</td>
                <td class="text-right">{{ $participant->rank }}</td>
                <td>
                  <a href="{!! route($baseRouteName . 'edit', [$event, $participant]) !!}" title="Edit {{ $participant->name }}"
                    class="btn btn-primary btn-sm btn-circle btn-edit"><i class="fas fa-edit"></i></a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@push('css')
  <link rel="stylesheet" href="/assets/plugins/datatables/buttons-datatables/2.4.2/css/buttons.dataTables.min.css">
@endpush

@push('js')
  @include($baseViewPath . '_index-js')
@endpush
