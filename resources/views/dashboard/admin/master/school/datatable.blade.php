@extends('layouts.app')

@section('content')
  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      <div class="tools float-right">
        <a href="{!! route($baseRouteName . 'create') !!}" title="Tambah Baru" class="btn btn-outline-primary btn-sm btn-create">
          <i class="fas fa-plus"></i> Baru
        </a>
        {{-- <a href="{!! route($baseRouteName . 'create-batch') !!}" title="Tambah Baru Batch" class="btn btn-outline-primary btn-sm btn-create">
          <i class="fas fa-plus"></i> Baru Batch
        </a> --}}
      </div>
    </div>
    <div class="card-body">
      {{-- <div class="table-responsive"> --}}
      @include('layouts.partials._notif')

      <form action="{{ route($baseRouteName . 'destroy-batch') }}" method="post" class="mb-4" id="school-form">
        @csrf
        @method('delete')

        <div class="row">
          <div class="col text-center text-md-left">
            <button type="submit" class="btn btn-danger mb-2" data-toggle="tooltip" title="Delete selected rows">
              <i class="fa fa-trash-alt"></i> Batch Delete
            </button>
            <a href="{!! route($baseRouteName . 'merger') !!}" title="Merge selected rows {{ $moduleName }}"
              data-title="Merge {{ $moduleName }}" data-action="{!! route($baseRouteName . 'update-merger') !!}" data-target="#myEditModal"
              class="btn btn-warning btn-merger mb-2">
              <i class="fa fa-layer-group"></i> Gabungkan Data
            </a>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-2 float-md-right">
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text">Page</span>
              </div>
              <select name="pagelist" id="pagelist" class="form-control"></select>
              <div class="input-group-append">
                <span class="input-group-text">of&nbsp;<span id="totalpages"></span></span>
              </div>
            </div>
          </div>
        </div>

        {{ $dataTable->table() }}
      </form>
      {{-- </div> --}}
    </div>
  </div>
  @component('components.bs4.modal', [
      'modalId' => 'myEditModal',
      'modalClass' => '',
      'modalSize' => 'modal-lg',
      'modalTitle' => '',
      'modalFormUrl' => '#!',
      'modalFormAttributes' => ['method' => 'put', 'class' => 'form-disabled-submit'],
  ])
    @method('put')
  @endcomponent
@endsection

@push('css')
  {{-- <link rel="stylesheet" href="//cdn.datatables.net/buttons/3.2.4/css/buttons.bootstrap4.css"> --}}

  <link rel="stylesheet" href="/assets/plugins/datatables/buttons-datatables/2.4.2/css/buttons.dataTables.min.css">
@endpush
@push('js')
  {{-- <script src="//cdn.datatables.net/buttons/3.2.4/js/dataTables.buttons.js"></script>
  <script src="//cdn.datatables.net/buttons/3.2.4/js/buttons.bootstrap4.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="//cdn.datatables.net/buttons/3.2.4/js/buttons.html5.min.js"></script>
  <script src="//cdn.datatables.net/buttons/3.2.4/js/buttons.print.min.js"></script>
  <script src="//cdn.datatables.net/buttons/3.2.4/js/buttons.colVis.min.js"></script> --}}

  <script src="/assets/plugins/datatables/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
  <script src="/assets/plugins/datatables/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="/assets/plugins/datatables/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="/assets/plugins/datatables/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="/assets/plugins/datatables/buttons/2.4.2/js/buttons.html5.min.js"></script>
  <script src="/assets/plugins/datatables/buttons/2.4.2/js/buttons.print.min.js"></script>
  <script src="/assets/plugins/datatables/buttons/2.4.2/js/buttons.colVis.min.js"></script>

  <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
  {{ $dataTable->scripts() }}

  @include($baseViewPath . '_datatable-js')
@endpush
