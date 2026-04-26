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
        <form
          action="{{ route($baseRouteName . (request()->filled('trashed') ? 'restore-batch' : 'destroy-batch'), request()->filled('trashed') ? request()->only('trashed') : null) }}"
          method="post" class="mb-4" id="gaya-form">
          @csrf
          @if (request()->filled('trashed'))
            @method('put')
            <button type="submit" class="btn btn-success mb-4" data-toggle="tooltip"
              title="Aktifkan baris terpilih!">Batch Aktivasi</button>
          @else
            @method('delete')
            <button type="submit" class="btn btn-danger mb-4" data-toggle="tooltip"
              title="Non-aktifkan baris terpilih!">Batch Non-aktivasi</button>
          @endif
          {{-- <table class="table table-striped table-bordered dt-responsive nowrap" --}}
          <table class="table table-striped table-bordered" id="dataTableCustom" width="100%" cellspacing="0"
            data-order="[[ 2, &quot;asc&quot; ]]">
            <thead>
              @include($baseViewPath . '_table-head')
            </thead>
            <tfoot>
              @include($baseViewPath . '_table-head')
            </tfoot>
            <tbody>
              @foreach ($gayas as $gaya)
                <tr>
                  <td>{{ $gaya->id }}</td>
                  <td>{{ $loop->iteration }}</td>
                  <td data-order="{{ intval($gaya->name) }}">{{ $gaya->name }}</td>
                  <td class="text-right">{{ $gaya->user_championships_count }}</td>
                  <td>{!! empty($gaya->deleted_at)
                      ? '<span class="badge badge-success">Aktif</span>'
                      : '<span class="badge badge-danger">Non-aktif</span>' !!}</td>
                  <td>
                    @if (!request()->filled('trashed'))
                      <a href="{!! route($baseRouteName . 'edit', $gaya->id) !!}" title="Edit {{ $gaya->name }}"
                        class="btn btn-primary btn-sm btn-circle btn-edit"><i class="fas fa-edit"></i></a>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    $(document).ready(function() {
      var t = $('#dataTableCustom').DataTable({
        columnDefs: [{
          targets: 0,
          searchable: false,
          orderable: false,
          className: 'select-checkbox',
          'render': function(data, type, full, meta) {
            return '<input type="checkbox" name="ids[]" value="' + $('<div/>').text(
              data).html() + '">';
          }
        }],
        initComplete: function() {
          this.api().columns([3]).every(function() {
            var column = this;
            var select = $(
                '<select class="select2me" style="padding-right:20px;"><option value="">---</option></select>'
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

      t.on('order.dt search.dt', function() {
        t.column(1, {
          search: 'applied',
          order: 'applied'
        }).nodes().each(function(cell, i) {
          cell.innerHTML = i + 1;
        });
      }).draw();

      // Handle click on "Select all" control
      $('#select-all').on('click', function() {
        // Get all rows with search applied
        var rows = t.rows({
          'search': 'applied'
        }).nodes();
        // Check/uncheck checkboxes for all rows in the table
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
      });

      // Handle click on checkbox to set state of "Select all" control
      $('#dataTableCustom tbody').on('change', 'input[type="checkbox"]', function() {
        // If checkbox is not checked
        if (!this.checked) {
          var el = $('#select-all').get(0);
          // If "Select all" control is checked and has 'indeterminate' property
          if (el && el.checked && ('indeterminate' in el)) {
            // Set visual state of "Select all" control
            // as 'indeterminate'
            el.indeterminate = true;
          }
        }
      });

      // Handle form submission event
      $('#gaya-form').on('submit', function(e) {
        var form = this;
        var text = $(this).find('[type="submit"]').attr('data-original-title');

        if (confirm('Yakin ' + text + '?')) {
          // Iterate over all checkboxes in the table
          t.$('input[type="checkbox"]').each(function() {
            // If checkbox doesn't exist in DOM
            if (!$.contains(document, this)) {
              // If checkbox is checked
              if (this.checked) {
                // Create a hidden element
                $(form).append(
                  $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', this.name)
                  .val(this.value)
                );
              }
            }
          });

          return true;
        }

        return false;
      });
    });
  </script>
@endpush
