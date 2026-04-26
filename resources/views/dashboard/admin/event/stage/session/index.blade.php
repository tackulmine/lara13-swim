@extends('layouts.app')

@section('content')
  <!-- Page Heading -->
  {{-- <h1 class="h3 mb-4 text-gray-800">Kompetisi</h1> --}}

  @include('layouts.partials._breadcrumbs')

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      <a href="{!! route($baseRouteName . 'create', [$event->id, $eventStage->id]) !!}" title="Tambah Baru" class="btn btn-outline-primary btn-sm btn-create float-right">
        <i class="fas fa-plus"></i> Baru
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @include('layouts.partials._notif')
        <table class="table table-striped table-bordered" id="dataTableFooterTotal" data-order="[[ 1, &quot;asc&quot; ]]"
          width="100%" cellspacing="0">
          <thead>
            @include($baseViewPath . '_thead')
          </thead>
          <tfoot>
            @include($baseViewPath . '_thead')
          </tfoot>
          <tbody>
            @foreach ($eventSessions as $eventSession)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-right">{!! $eventSession->session !!}</td>
                {{-- <td class="text-right">{!! $eventSession->eventSessionParticipants->count() !!}</td> --}}
                <td class="text-right" data-order="{!! $eventSession->eventSessionParticipants->count() !!}">
                  <a
                    href="{{ route($baseRouteName . 'participant.index', [$event->id, $eventStage->id, $eventSession->id]) }}">
                    {!! $eventSession->eventSessionParticipants->count() !!}
                  </a>
                </td>
                <td class="text-center">{{ $eventSession->completed ? 'Sudah' : 'Belum' }}</td>
                <td>
                  <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownActionBtn"
                      data-toggle="dropdown" aria-expanded="false"></button>
                    <div class="dropdown-menu" aria-labelledby="dropdownActionBtn">
                      <a class="dropdown-item" href="{!! route($baseRouteName . 'edit', [$event->id, $eventStage->id, $eventSession->id]) !!}"><i class="far fa-fw fa-edit"></i> Edit Seri
                        {{ $eventSession->session }}</a>
                      <a class="dropdown-item" href="{!! route($baseRouteName . 'participant.index', [$event->id, $eventStage->id, $eventSession->id]) !!}"><i class="far fa-fw fa-eye"></i> Lihat
                        Peserta - Seri {{ $eventSession->session }}</a>
                    </div>
                  </div>

                  {{-- <a href="{!! route($baseRouteName . 'edit', [$event->id, $eventStage->id, $eventSession->id]) !!}"
                title="Edit Seri {{ $eventSession->session }}"
                class="btn btn-primary btn-sm btn-circle btn-edit"
                ><i class="fas fa-edit"></i></a> --}}
                  {{-- <a href="{!! route($baseRouteName . 'show', [$eventStage->id, $eventSession->id]) !!}"
                title="Lihat Seri {{ $eventSession->session }}"
                target="_blank"
                class="btn btn-secondary btn-sm btn-circle btn-view"><i class="fas fa-eye"></i></a> --}}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @include($parentViewPath . 'event.stage._detail-stage')
@endsection

@push('js')
  <script>
    // Call the dataTables jQuery plugin
    $(document).ready(function() {
      $('#dataTableFooterTotal').DataTable({
        // stateSave: true,
        "footerCallback": function(row, data, start, end, display) {
          var api = this.api(),
            data;

          // Remove the formatting to get integer data for summation
          var intVal = function(i) {
            return typeof i === 'string' ?
              i.replace(/[\$,]/g, '').replace(/<[^>]*>?/gm, '') * 1 :
              typeof i === 'number' ?
              i : 0;
          };

          // Total over all pages
          colTwoTotal = api
            .column(2)
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          // Total over this page
          colTwoPageTotal = api
            .column(2, {
              page: 'current'
            })
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          // Update footer
          $(api.column(2).footer()).html(
            colTwoPageTotal + ' ( ' + colTwoTotal + ' total )'
          );

        },
        initComplete: function() {
          this.api().columns([3]).every(function() {
            var column = this;
            var select = $(
                '<select class="d-inline-block select2me" style="padding-right: 20px;"><option value="">---</option></select>'
              )
              .appendTo($(column.footer()).empty())
              .on('change', function() {
                var val = $.fn.dataTable.util.escapeRegex(
                  $(this).val()
                );

                column
                  .search(val ? '^' + val + '$' : '', true, false)
                  .draw();
              });

            column.data().unique().sort().each(function(d, j) {
              select.append('<option value="' + d + '">' + d + '</option>')
            });
          });
          initSelectToMe();
        }
      });
    });
  </script>
@endpush
