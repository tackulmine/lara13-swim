@extends('layouts.app')

@section('content')
  <!-- Page Heading -->
  {{-- <h1 class="h3 mb-4 text-gray-800">Kompetisi</h1> --}}

  @include('layouts.partials._breadcrumbs')

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      {{-- <a href="{!! route($baseRouteName . 'create', [$eventSession->id]) !!}"
                title="Tambah Baru"
                class="btn btn-outline-primary btn-sm btn-create float-right"
                >
                <i class="fas fa-plus"></i> Baru
            </a> --}}
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @include('layouts.partials._notif')
        <table class="table table-striped table-bordered" id="dataTableCustom"
          data-order="[[ 1, &quot;asc&quot; ], [ 6, &quot;asc&quot; ], [ 7, &quot;asc&quot; ]]" width="100%"
          cellspacing="0">
          <thead>
            @include($baseViewPath . '_thead')
          </thead>
          <tfoot>
            @include($baseViewPath . '_thead')
          </tfoot>
          <tbody>
            @foreach ($eventSessionParticipants as $eventSessionParticipant)
              {{-- @dd($eventSessionParticipant) --}}
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td data-order="{!! $eventSessionParticipant->eventSession->eventStage->number !!}">
                  <a
                    href="{{ route($parentRouteName . 'event.stage.participant.index', [$event->id, $eventSessionParticipant->eventSession->eventStage]) }}">
                    {!! $eventSessionParticipant->eventSession->eventStage->number_format !!}
                  </a>
                </td>
                <td>{{ $eventSessionParticipant->eventSession->eventStage->masterMatchType->name }}</td>
                <td>{{ $eventSessionParticipant->eventSession->eventStage->masterMatchCategory->name }}</td>
                <td>{{ strtoupper(optional($eventSessionParticipant->masterParticipant)->name) }}</td>
                <td>{{ optional($eventSessionParticipant->masterParticipant->masterSchool)->name ?? '-' }}
                </td>
                <td class="text-right">{!! $eventSessionParticipant->eventSession->session !!}</td>
                <td class="text-right">{!! $eventSessionParticipant->track !!}</td>
                <td class="text-right" data-order="{!! $eventSessionParticipant->point !!}">
                  {!! $eventSessionParticipant->point_text !!}
                </td>
                <td class="text-right" data-order="{!! $eventSessionParticipant->point_decimal !!}">
                  {!! $eventSessionParticipant->point_text_decimal !!}
                </td>
                <td class="text-center" data-order="{!! $eventSessionParticipant->disqualification ? 'Ya' : 'Tidak' !!}">
                  {!! $eventSessionParticipant->disqualification
                      ? '<span class="' .
                          $eventSessionParticipant->dis_level_text_class .
                          '"><strong>' .
                          $eventSessionParticipant->dis_level_text .
                          '</strong></span>'
                      : '<span class="text-success">Tidak</span>' !!}
                </td>
                {{-- <td>{!! $eventSessionParticipant->notes !!}</td> --}}
                <td>
                  {{-- <a href="{!! route($baseRouteName . 'edit', [$eventSession->id, $eventSessionParticipant->id]) !!}"
                    title="Edit Stage {{ $eventSessionParticipant->masterParticipant->name }}"
                    class="btn btn-primary btn-sm btn-circle btn-edit"><i class="fas fa-edit"></i></a> --}}
                  {{-- <a href="{!! route($baseRouteName . 'show', [$eventStage->id, $eventSessionParticipant->id]) !!}"
                    title="Lihat Stage {{ $eventSessionParticipant->number }}"
                    target="_blank"
                    class="btn btn-secondary btn-sm btn-circle btn-view"><i class="fas fa-eye"></i></a> --}}
                  <a href="{!! route($baseParentRouteName . 'stage.session.participant.edit', [
                      $event->id,
                      $eventSessionParticipant->eventSession->eventStage->id,
                      $eventSessionParticipant->eventSession->id,
                      $eventSessionParticipant->id,
                  ]) !!}"
                    title="Edit Peserta {{ $eventSessionParticipant->masterParticipant->name }}"
                    class="btn btn-primary btn-sm btn-circle btn-edit"><i class="fas fa-edit"></i></a>
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
    const agent = @json($agent);
    $(function() {
      $('#dataTableCustom').DataTable({
        fixedHeader: false,
        responsive: agent.isMobile ?? false,
        initComplete: function() {
          this.api().columns([1, 2, 3, 4, 5, 6, 7]).every(function(index) {
            var column = this;
            var select = $(
                '<select class="select2me" style="padding-right:20px;"><option value="">---</option></select>'
              )
              .appendTo($(column.footer()).empty())
              .on('change', function() {
                var val;
                if (index == 1) {
                  val = $(this).val();
                  column
                    .search(val ? val : '', true, false)
                    .draw();
                } else {
                  val = $.fn.dataTable.util.escapeRegex(
                    $(this).val()
                  );
                  column
                    .search(val ? '^' + val + '$' : '', true, false)
                    .draw();
                }
              });

            if (index == 1) {
              var columns = [];
              column.data().unique().sort().each(function(d, j) {
                d = removeHTMLTags(d);
                d = d.trim();
                columns.push(d);
              });
              columns.sort();
              $(columns).each(function(i, val) {
                select.append('<option value="' + val + '">' + val +
                  '</option>');
              });
            } else {
              column.data().unique().sort().each(function(d, j) {
                select.append('<option value="' + d + '">' + d +
                  '</option>');
              });
            }
          });
          initSelectToMe();
        }
      });
    });
  </script>
@endpush
