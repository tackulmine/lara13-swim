@extends('layouts.app')

@section('content')
  @include('layouts.partials._breadcrumbs')

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      <a href="{!! route($baseRouteName . 'edit', $event->id) !!}" title="Edit {{ __('Kategori') }} {{ $event->name }}"
        data-title="Edit {{ __('Kategori') }} {{ $event->name }}" data-action="{!! route($baseRouteName . 'update', $event->id) !!}" data-toggle="modal"
        data-target="#myEditModal" class="btn btn-outline-primary btn-sm float-right btn-edit">
        <i class="fas fa-edit"></i> Update {{ __('Kategori') }}
      </a>
    </div>
    <div class="card-body">
      <div class="alert alert-info">Untuk mengurutkan baris, silakan drag baris yang dipilih menggunakan mouse dan
        drop ke posisi baris yang diinginkan.</div>
      <div class="table-responsive">
        @include('layouts.partials._notif')
        <table class="table table-striped table-bordered" id="dataTableReorder" width="100%" cellspacing="0"
          {{-- data-order="[[ 3, &quot;asc&quot; ],[ 1, &quot;asc&quot; ]]"> --}} data-order="[[ 0, &quot;asc&quot; ]]">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>N a m a</th>
              <th width="10%">Jumlah {{ __('Gaya') }}</th>
              <th width="10%">Urutan</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($categories as $category)
              <tr id="{{ $category->pivot->master_match_category_id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $event->typesOnCategory($category->pivot->master_match_category_id)->count() }}</td>
                <td>{{ $category->pivot->ordering }}</td>
                <td><a href="{!! route($baseRouteName . 'edit-type', [$event, $category]) !!}" title="Set Tipe {{ $category->name }}"
                    data-title="Edit {{ __('Gaya') }} {{ __('Kategori') }} {{ $category->name }}"
                    data-action="{!! route($baseRouteName . 'update-type', [$event, $category]) !!}" data-toggle="modal" data-target="#myEditModal"
                    class="btn btn-primary btn-sm btn-circle btn-edit">
                    <i class="fas fa-fw fa-swimming-pool"></i>
                  </a></td>
              </tr>
            @empty
              <tr>
                <td colspan="100" align="center">Data kosong!</td>
              </tr>
            @endforelse
          </tbody>
        </table>
        {{-- {!! Form::open([
            'route' => [$baseRouteName . 'set-ordering', $event->id],
            'id' => 'reorder-form',
            // 'class' => 'form-horizontal',
            'class' => 'form-disabled-submit',
            // 'files' => true,
            'method' => 'put',
        ]) !!} --}}
        {{-- {{ Form::hidden('items_id') }} --}}
        {{ html()->form('PUT')->route($baseRouteName . 'set-ordering', $event->id)->id('reorder-form')->class('form-disabled-submit')->open() }}
        {{ html()->hidden('items_id', old('items_id', isset($categories) ? $categories->pluck('pivot.master_match_category_id')->implode(',') : '')) }}
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
      'modalFormMethod' => 'PUT',
      'modalFormAttributes' => ['class' => 'form-disabled-submit'],
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
      //   // window.location.reload();
      //   window.setTimeout(function() {
      //     window.location.reload();
      //   }, 1000);
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
