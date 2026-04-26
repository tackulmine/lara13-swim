@extends('layouts.app')

@section('content')
  <!-- Page Heading -->
  {{-- <h1 class="h3 mb-4 text-gray-800">Kompetisi</h1> --}}

  @include('layouts.partials._breadcrumbs')

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      <a href="{!! route($baseRouteName . 'create', $event->id) !!}" title="Tambah Baru" class="btn btn-outline-primary btn-sm btn-create float-right">
        <i class="fas fa-plus"></i> Baru
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @include('layouts.partials._notif')
        <table class="table table-striped table-bordered" id="dataTableFooterTotal" data-order="[[ 4, &quot;asc&quot; ]]"
          width="100%" cellspacing="0">
          <thead>
            @include($baseViewPath . '_thead')
          </thead>
          <tfoot>
            @include($baseViewPath . '_thead')
          </tfoot>
          <tbody>
            @foreach ($eventStages as $eventStage)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-right">{{ $eventStage->number_format }}</td>
                <td>{{ $eventStage->masterMatchType->name }}</td>
                <td>{{ $eventStage->masterMatchCategory->name }}</td>
                <td>{{ $eventStage->order_number }}</td>
                <td class="text-right" data-order="{!! $eventStage->eventSessions->count() !!}">
                  <a href="{{ route($baseRouteName . 'session.index', [$event->id, $eventStage->id]) }}">
                    {!! $eventStage->eventSessions->count() !!}
                  </a>
                </td>
                <td class="text-right" data-order="{!! $eventStage->eventSessionParticipants->count() !!}">
                  <a href="{{ route($baseRouteName . 'participant.index', [$event->id, $eventStage->id]) }}">
                    {!! $eventStage->eventSessionParticipants->count() !!}
                  </a>
                </td>
                <td class="text-center">{{ $eventStage->completed ? 'Sudah' : 'Belum' }}</td>
                <td>
                  <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownActionBtn"
                      data-toggle="dropdown" aria-expanded="false"></button>
                    <div class="dropdown-menu" aria-labelledby="dropdownActionBtn">
                      <a class="dropdown-item" href="{!! route($baseRouteName . 'edit', [$event->id, $eventStage->id]) !!}"><i class="far fa-fw fa-edit"></i> Edit Acara
                        {{ $eventStage->number_format }}</a>
                      <a class="dropdown-item" href="{!! route($baseRouteName . 'session.index', [$event->id, $eventStage->id]) !!}"><i class="far fa-fw fa-eye"></i> Lihat Seri
                        - Acara
                        {{ $eventStage->number_format }}</a>
                      <a class="dropdown-item" href="{!! route($baseRouteName . 'participant.index', [$event->id, $eventStage->id]) !!}"><i class="far fa-fw fa-eye"></i> Lihat
                        Peserta - Acara
                        {{ $eventStage->number_format }}</a>
                      @if ($eventStage->completed)
                        <a class="dropdown-item" href="{!! route($baseRouteName . 'download', [$event->id, $eventStage->id]) !!}?type=result" target="_blank"><i
                            class="far fa-fw fa-eye"></i> Lihat Hasil (Browser)</a>
                        <a class="dropdown-item" href="{!! route($baseRouteName . 'download', [$event->id, $eventStage->id]) !!}?type=result&ext=xls"><i
                            class="far fa-fw fa-file-excel"></i>
                          Download Hasil (Excel)</a>
                        <a class="dropdown-item" href="{!! route($baseRouteName . 'download', [$event->id, $eventStage->id]) !!}?type=result&ext=pdf"><i
                            class="far fa-fw fa-file-pdf"></i> Download Hasil (PDF)</a>
                        <a class="dropdown-item" href="{!! route($baseRouteName . 'download', [$event->id, $eventStage->id]) !!}?type=certificate" target="_blank"><i
                            class="far fa-fw fa-eye"></i> Lihat List Sertifikat (Browser)</a>
                        <a class="dropdown-item" href="{!! route($baseRouteName . 'download', [$event->id, $eventStage->id]) !!}?type=certificate&ext=xls"
                          target="_blank"><i class="far fa-fw fa-file-excel"></i> Download List Sertifikat (Excel)</a>
                      @endif
                    </div>
                  </div>
                  {{-- <a href="{!! route($baseRouteName . 'edit', [$event->id, $eventStage->id]) !!}"
                                        title="Edit Stage {{ $eventStage->number_format }}"
                                        class="btn btn-primary btn-sm btn-circle btn-edit"><i class="fas fa-fw fa-edit"></i></a>
                                    @if ($eventStage->completed)
                                        <a href="{!! route($baseRouteName . 'download', [$event->id, $eventStage->id]) !!}"
                                        title="Download Hasil Pertandingan Stage {{ $eventStage->number_format }}"
                                        class="btn btn-success btn-sm btn-circle btn-edit"><i class="fas fa-fw fa-download"></i></a>
                                        @canrole(['superuser'])
                                        <a href="{!! route($baseRouteName . 'download', [$event->id, $eventStage->id]) !!}?type=pdf"
                                        title="Download Hasil Pertandingan Stage {{ $eventStage->number_format }}"
                                        class="btn btn-success btn-sm btn-circle btn-edit"
                                        target="_blank"><i class="far fa-fw fa-file-pdf"></i></a>
                                        @endcanrole
                                    @endif --}}
                  {{-- <a href="{!! route($baseRouteName . 'show', [$event->id, $eventStage->id]) !!}"
                                        title="Lihat Stage {{ $eventStage->number }}"
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

  @include($parentViewPath . 'event._detail-event')
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
          colFourTotal = api
            .column(5)
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          // Total over this page
          colFourPageTotal = api
            .column(5, {
              page: 'current'
            })
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          // Update footer
          $(api.column(5).footer()).html(
            colFourPageTotal + ' ( ' + colFourTotal + ' total )'
          );

          // Total over all pages
          colFiveTotal = api
            .column(6)
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          // Total over this page
          colFivePageTotal = api
            .column(6, {
              page: 'current'
            })
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          // Update footer
          $(api.column(6).footer()).html(
            colFivePageTotal + ' ( ' + colFiveTotal + ' total )'
          );
        },
        initComplete: function() {
          this.api().columns([2, 3, 7]).every(function() {
            var column = this;
            var select = $(
                '<select class="select2me" style="padding-right: 20px"><option value="">---</option></select>'
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
              select.append('<option value="' + d + '">' + d +
                '</option>')
            });
          });
          initSelectToMe();
        }
      });
    });
  </script>
@endpush
