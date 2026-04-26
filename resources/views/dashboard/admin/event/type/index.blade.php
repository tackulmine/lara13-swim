@extends('layouts.app')

@section('content')
  @include('layouts.partials._breadcrumbs')

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      <a href="{!! route($baseRouteName . 'edit', $event->id) !!}" title="Edit Tipe {{ $event->name }}" data-title="Edit Tipe {{ $event->name }}"
        data-action="{!! route($baseRouteName . 'update', $event->id) !!}" data-toggle="modal" data-target="#myEditModal"
        class="btn btn-outline-primary btn-sm float-right btn-edit">
        <i class="fas fa-edit"></i> Update Tipe
      </a>
    </div>
    <div class="card-body">
      <div class="alert alert-info">Untuk mengurutkan baris, silakan drag baris yang dipilih menggunakan mouse dan
        drop ke posisi baris yang diinginkan.</div>
      <div class="table-responsive">
        @include('layouts.partials._notif')
        <table class="table table-striped table-bordered" id="dataTableReorder" width="100%" cellspacing="0"
          {{-- data-order="[[ 2, &quot;asc&quot; ],[ 1, &quot;asc&quot; ]]"> --}} data-order="[[ 0, &quot;asc&quot; ]]">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>N a m a</th>
              {{-- <th width="10%">Jumlah {{ __('Kategori') }}</th> --}}
              <th width="10%">Urutan</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($types as $type)
              @php
                // dd($type->eventCategories()->where('event_id', $event->id)->first()->pivot->masterMatchCategory->name, $type);
                // $eventCategories = $type->eventCategories()->where('event_id', $event->id)->get();
                // $eventCategoryNames = [];
                // foreach ($eventCategories as $eventCategory) {
                //   $eventCategoryNames[] = $eventCategory->pivot->masterMatchCategory->name;
                // }
                // $eventCategories = $event->categoriesOnType($type->pivot->master_match_type_id);
                // $eventCategoryNames = $eventCategories->pluck('name')->unique();
              @endphp
              <tr id="{{ $type->pivot->master_match_type_id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $type->name }}</td>
                {{-- <td>
                  <a href="#!" data-toggle="tooltip" data-html="true"
                    data-original-title="{{ $eventCategoryNames->implode(';<br>') }}"
                    >{{ $eventCategoryNames->count() }}
                  </a>
                </td> --}}
                {{-- <td>
                  <a href="#!" data-toggle="tooltip" data-html="true"
                    data-original-title="{{ implode('; ', $eventCategoryNames) }}"
                    >{{ count($eventCategoryNames) }}
                  </a>
                </td> --}}
                <td>{{ $type->pivot->ordering }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="100" align="center">Data kosong!</td>
              </tr>
            @endforelse
          </tbody>
        </table>
        {!! Form::open([
            'route' => [$baseRouteName . 'set-ordering', $event->id],
            'id' => 'reorder-form',
            // 'class' => 'form-horizontal',
            'class' => 'form-disabled-submit',
            // 'files' => true,
            'method' => 'put',
        ]) !!}
        {{ Form::hidden('items_id') }}
        <div class="text-center">
          <button type="submit" class="btn btn-outline-primary mb-2 mb-lg-0">
            <i class="fas fa-save"></i>
            Simpan Urutan
          </button>
        </div>
        {{ html()->form()->close() }}
      </div>
    </div>
  </div>

  @component('components.bs4.modal', [
      'modalId' => 'myEditModal',
      'modalClass' => '',
      'modalSize' => '',
      'modalTitle' => '',
      'modalFormUrl' => '#!',
      'modalFormAttributes' => ['method' => 'put', 'class' => 'form-disabled-submit'],
  ])
    @method('put')
  @endcomponent
@endsection

@push('css')
  {{-- <link rel="stylesheet" href="/assets/plugins/datatables/rowreorder-datatables/1.5.0/css/rowReorder.dataTables.min.css"> --}}
  <link rel="stylesheet" href="/assets/plugins/datatables/rowreorder-bootstrap4/1.5.0/css/rowReorder.bootstrap4.min.css">
@endpush

@push('js')
  <script src="/assets/plugins/datatables/rowreorder/1.5.0/js/dataTables.rowReorder.min.js"></script>

  <script>
    var url = "{!! route($baseRouteName . 'set-ordering', $event->id) !!}";

    function updateOrder(id, order) {
      $.post(url, {
          _token: "{{ csrf_token() }}",
          _method: 'PUT',
          item_id: id,
          order: order
        })
        .done(function(data) {
          // console.log(data);
          window.location.reload();
        });
    }

    function updateOrderItems(items_id) {
      $('#reorder-form input[name="items_id"]').val(items_id);
    }

    function updateOrders(ids) {
      $.post(url, {
          _token: "{{ csrf_token() }}",
          _method: 'PUT',
          ids: ids
        })
        .done(function(data) {
          // console.log(data);
          window.location.reload();
        });
    }

    $(document).ready(function() {
      var dataTable = $('#dataTableReorder').DataTable({
        'columnDefs': [{
          'targets': [0],
          /* column index */
          'orderable': false,
          /* true or false */
        }],
        rowReorder: {
          selector: 'tr'
        },
        paging: false,
      });

      // dataTable.on('row-reorder', function(e, diff, edit) {
      //   for (var i = 0, ien = diff.length; i < ien; i++) {
      //     // get id row
      //     let idQ = diff[i].node.id;
      //     let idNewQ = idQ.replace("row_", "");
      //     // get position
      //     let position = diff[i].newPosition + 1;
      //     //call funnction to update data
      //     updateOrder(idNewQ, position);
      //   }
      //   window.location.reload();
      // });

      dataTable.on('row-reorder', function(e, diff, edit) {
        dataTable.one('draw', function() {
          console.log('Redraw occurred at: ' + new Date().getTime());

          ids = [];
          dataTable.rows().every(function(rowIdx, tableLoop, rowLoop) {
            console.log(rowIdx, this.data()['DT_RowId']);
            ids.push(this.data()['DT_RowId']);
          });
          console.log(ids);
          // updateOrders(ids);
          updateOrderItems(ids.join());
        });
      });
    });

    $(document).on('click', '.btn-edit', function() {
      // console.log('edit');
      var href = $(this).attr('href');
      var title = $(this).attr('data-title');
      var target = $(this).attr('data-target');
      var action = $(this).attr('data-action');

      $(target).find('.modal-title').html(title);
      $(target).find('form').first().attr('action', action);

      $.ajax({
        type: "get",
        url: href,
        success: function(response) {
          $(target).find('.modal-body').html(response);
          initDateRangePicker();
        }
      });

    });
    $(document).on('submit', '.form-disabled-submit', function() {
      $(this).find("button[type=submit]").each(function(el) {
        $(this).prop('disabled', true);
      });
    });
    $('#myEditModal').on('hidden.bs.modal', function(e) {
      var $form = $(this).find('form').first();
      $form.attr('action', '#!');
      $(this).find('.modal-body').html('');
    });
  </script>
@endpush
