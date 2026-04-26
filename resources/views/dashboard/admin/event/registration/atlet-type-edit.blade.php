@extends('layouts.app')

@section('content')
  @include('layouts.partials._breadcrumbs')

  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 font-weight-bold text-primary">{{ $pageTitle }}</h5>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {!! Form::open([
        'route' => [$baseRouteName . 'atlet-type-update', [$event, $eventRegistration]],
        'class' => 'form-horizontal',
        'method' => 'put',
    ]) !!}
    {{ Form::hidden('id', $id) }}
    <div class="card-body">

      @include('layouts.partials._notif')

      @include($baseViewPath . '_form-atlet-type-edit')

    </div>
    <!-- /.box-body -->
    <div class="card-footer">
      @include('layouts.partials.form._edit-buttons')
    </div>
    <!-- /.box-footer -->
    {{ html()->form()->close() }}
  </div>

  {{-- @include('layouts.partials.form._edit-modal') --}}
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
            <i class="fa fa-question-circle fa-lg"></i> Batal mengubah {{ $moduleName ?? 'item' }} ini?
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
          <a href="{{ route($baseRouteName . 'atlet', $event) . getQueryHttpBuilder() }}" class="btn btn-warning">
            <i class="fa fa-undo"></i> Ya
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  {{-- <script>
    var initMask = function() {
      // $('.input-mask-time').mask('00:00:00');
      $(":input").inputmask();
    };

    var initToggleDisabled = function() {
      $(document).on("change", "[data-toggle-disabled-id]", function() {
        var $target = $(this);
        var $id = $target.data('toggle-disabled-id');
        var $toggle = $('#' + $id);
        $toggle.prop('disabled', !$target.prop('checked'));
      });
    };

    $(document).on("ajaxComplete", function() {
      initMask();
    });

    $(document).ready(function() {
      initMask();
      initToggleDisabled();
    });
  </script> --}}
  <!-- Laravel Javascript Validation -->
  {{-- <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
  {!! JsValidator::formRequest('App\Http\Requests\EventRegistrationRequest', '#event-registration') !!} --}}
@endpush
