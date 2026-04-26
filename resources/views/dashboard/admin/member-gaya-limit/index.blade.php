@extends('layouts.app')

@section('content')
  <!-- Page Heading -->
  {{-- <h1 class="h3 mb-4 text-gray-800">Kompetisi</h1> --}}

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      <div class="tools float-right">
        <a href="{!! route($baseRouteName . 'create') !!}" title="Tambah Baru" class="btn btn-outline-primary btn-sm btn-create">
          <i class="fas fa-plus"></i> Baru
        </a>
        <a href="{!! route($baseRouteName . 'create-batch') !!}" title="Tambah Baru Batch" class="btn btn-outline-primary btn-sm btn-create">
          <i class="fas fa-plus"></i> Baru Batch
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-filters">
        {!! Form::open([
            'route' => $baseRouteName . 'index',
            'class' => 'needs-validation',
            'novalidate' => true,
            'method' => 'get',
        ]) !!}
        <div class="row">
          <div class="col-sm-8">
            <div class="form-group row">
              <label for="" class="col-sm-3 col-form-label">Periode</label>
              <div class="col">
                <div class="input-group input-daterange" data-provide="datepicker" data-date-format="mm-yyyy"
                  data-date-view-mode="months" data-date-min-view-mode="months" data-date-end-date="0d"
                  data-date-autoClose="true" data-date-language="id" autocomplete="false">
                  {!! Form::text('periode_start', request('periode_start') ?? '', [
                      'class' => 'form-control',
                      'required' => 'required',
                      'autocomplete' => 'off',
                  ]) !!}
                  <div class="input-group-append"><span class="input-group-text">sampai</span></div>
                  {!! Form::text('periode_end', request('periode_end') ?? '', [
                      'class' => 'form-control',
                      'required' => 'required',
                      'autocomplete' => 'off',
                  ]) !!}
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
            </div>
          </div>
        </div>
        {{ html()->form()->close() }}
      </div>
      <div class="table-responsive">
        @include('layouts.partials._notif')
        <form action="{{ route('dashboard.admin.member-gaya-limit.destroy-batch') }}" method="post" class="mb-4"
          id="gaya-limit-form">
          @csrf
          @method('delete')
          <button type="submit" class="btn btn-danger mb-4" data-toggle="tooltip" title="Delete selected rows">Batch
            Delete</button>

          <table class="table table-striped table-bordered" id="dataTableCustom" width="100%" cellspacing="0"
            data-order="[[ 2, &quot;asc&quot; ],[ 4, &quot;asc&quot; ],[ 3, &quot;asc&quot; ]]">
            <thead>
              @include($baseViewPath . '_table-head')
            </thead>
            <tfoot>
              @include($baseViewPath . '_table-head')
            </tfoot>
            <tbody>
              @foreach ($limits as $limit)
                @php
                  $user = optional($limit->user);
                  $gaya = optional($limit->gaya);
                @endphp
                <tr>
                  <td>{{ $limit->id }}</td>
                  <td>{{ $loop->iteration }}</td>
                  {{-- <td>{{ $user->username ?? $user->name }}</td> --}}
                  <td><a href="#!" data-order="{{ $user->username }}" data-toggle="tooltip"
                      title="{{ $user->name }}">{{ $user->username }}</a>
                  </td>
                  <td data-order="{{ intval($gaya->name) }}">{{ $gaya->name }}</td>
                  <td data-order="{{ $limit->periode_to_timestamp }}">{{ $limit->periode_to_date }}</td>
                  <td>{{ $limit->point_text }}</td>
                  <td class="text-center">
                    <a href="{!! route($baseRouteName . 'edit', $limit->id) !!}" title="Edit {{ $limit->point }}"
                      class="btn btn-primary btn-sm btn-circle btn-edit"><i class="fas fa-edit"></i></a>
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

@push('css')
  <link rel="stylesheet" href="/assets/plugins/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker3.min.css" />
@endpush

@push('js')
  <script src="/assets/plugins/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
  <script src="/assets/plugins/bootstrap-datepicker/1.10.0/locales/bootstrap-datepicker.id.min.js"></script>
  <script>
    $(document).ready(function() {
      var t = $('#dataTableCustom').DataTable({
        columnDefs: [{
          targets: 0,
          searchable: false,
          orderable: false,
          className: 'select-checkbox',
          'render': function(data, type, full, meta) {
            return '<input type="checkbox" name="ids[]" value="' + $('<div/>').text(data).html() + '">';
          }
        }],
        initComplete: function() {
          this.api().columns([2, 3, 4]).every(function() {
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
              d = removeHTMLTags(d);
              select.append('<option value="' + d + '">' + d + '</option>')
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

      // new $.fn.dataTable.FixedHeader( t, {
      //     footer: false
      // } );

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
      $('#gaya-limit-form').on('submit', function(e) {
        var form = this;

        if (confirm(
            'Yakin menghapus semua data? \n\nHATI-HATI!! Data yang terhapus tidak akan bisa dikembalikan!')) {
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
