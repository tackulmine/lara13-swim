@extends('layouts.app')

@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 font-weight-bold text-primary">{{ $pageTitle }}</h5>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {!! Form::open([
        'route' => [$baseRouteName . 'import-process'],
        'class' => 'form-horizontal form-disabled-submit',
        'files' => true,
    ]) !!}
    <div class="card-body">

      @include('layouts.partials._notif')

      {{-- include file --}}
      @include($baseViewPath . '_import_form')

    </div>
    <!-- /.box-body -->

    <div class="card-footer">
      <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#modal-discard">
        <i class="fa fa-undo"></i>
        Batal
      </button>
      <button type="submit" class="btn btn-outline-primary float-right">
        <i class="fa fa-save"></i>
        Import {{ $moduleName ?? 'item' }}
      </button>

    </div>
    <!-- /.box-footer -->
    {{ html()->form()->close() }}
  </div>

  {{-- Confirm Discard --}}
  <div class="modal fade" id="modal-discard" tabIndex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Silakan Konfirmasi</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <p class="lead">
            <i class="fa fa-question-circle fa-lg"></i> Batal meng-import {{ $moduleName ?? 'item' }}?
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
          <a href="{{ route($baseRouteName . 'index') }}" class="btn btn-outline-warning">
            <i class="fa fa-undo"></i> Ya
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script src="/assets/plugins/bs-custom-file-input/1.3.4/bs-custom-file-input.min.js"></script>
  <script>
    $(document).ready(function() {
      // init custom file input
      bsCustomFileInput.init();

      //set initial state.
      $('.form-disabled-submit').on('submit', function() {
        $(this).find("[type=submit]").prop('disabled', true);
      });
    });
  </script>
@endpush
