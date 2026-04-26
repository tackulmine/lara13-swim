@extends('layouts.app')

@section('content')
  <!-- Page Heading -->
  {{-- <h1 class="h3 mb-4 text-gray-800">Kompetisi</h1> --}}

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      <a href="{!! route($baseRouteName . 'create') !!}" title="Tambah Baru" class="btn btn-outline-primary btn-sm btn-create float-right">
        <i class="fas fa-plus"></i> Baru
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @include('layouts.partials._notif')
        {{-- <table class="table table-striped table-bordered dt-responsive nowrap" --}}
        <table class="table table-striped table-bordered" id="dataTableCustom" width="100%" cellspacing="0"
          data-order="[[ 1, &quot;asc&quot; ]]">
          <thead>
            @include($baseViewPath . '_table-head')
          </thead>
          <tfoot>
            @include($baseViewPath . '_table-head')
          </tfoot>
          <tbody>
            @foreach ($classes as $class)
              @php
                $usernames = '';
                if ($class->user_members_count > 0) {
                    $usernames = $class->userMembers
                        ->map(function ($userMember) {
                            return optional($userMember->user)->username;
                        })
                        ->filter()
                        ->sort()
                        ->implode(', ');
                }
              @endphp
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td data-order="{{ intval($class->name) }}">{{ $class->name }}</td>
                <td class="text-right">{!! $class->user_members_count > 0
                    ? '<a href="javascript:;" data-toggle="tooltip" title="' . $usernames . '">' . $class->user_members_count . '</a>'
                    : '-' !!}</td>
                <td class="text-center">
                  <a href="{!! route($baseRouteName . 'edit', $class->id) !!}" title="Edit {{ $class->name }}"
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

@push('js')
  <script>
    $(document).ready(function() {
      var t = $('#dataTableCustom').DataTable({
        // initComplete: function () {
        //     this.api().columns([2]).every( function () {
        //         var column = this;
        //         var select = $('<select class="select2me" style="padding-right:20px;"><option value="">---</option></select>')
        //             .appendTo( $(column.footer()).empty() )
        //             .on( 'change', function () {
        //                 var val = $.fn.dataTable.util.escapeRegex(
        //                     $(this).val()
        //                 );

        //                 column
        //                     .search( val ? '^'+val+'$' : '', true, false )
        //                     .draw();
        //             } );

        //         column.data().unique().sort().each( function ( d, j ) {
        //             select.append( '<option value="'+d+'">'+d+'</option>' )
        //         } );
        //     } );
        // }
      });
      t.on('order.dt search.dt', function() {
        t.column(0, {
          search: 'applied',
          order: 'applied'
        }).nodes().each(function(cell, i) {
          cell.innerHTML = i + 1;
        });
      }).draw();
    });
  </script>
@endpush
