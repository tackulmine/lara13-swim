@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-md-8 offset-md-2">

      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h5 class="m-0 font-weight-bold text-primary">{{ $pageTitle }}</h5>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {!! Form::open([
            'route' => [$baseRouteName . 'store', $event->id, $eventStage->id, $eventSession->id],
            'class' => 'form-horizontal',
            'autocomplete' => 'off',
        ]) !!}
        <div class="card-body">

          @include('layouts.partials._notif')

          {{-- include file --}}
          @include($baseViewPath . '_form')

        </div>
        <div class="card-footer">
          @include('layouts.partials.form._create-buttons')
        </div>
        <!-- /.box-footer -->
        {{ html()->form()->close() }}
      </div>

    </div>
  </div>

  @include($parentViewPath . 'event.stage.session._detail-session')

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
            <i class="fa fa-question-circle fa-lg"></i> Batal membuat {{ $moduleName ?? 'item' }} baru?
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
          <a href="{{ route($baseRouteName . 'index', [$event->id, $eventStage->id, $eventSession->id]) }}"
            class="btn btn-outline-warning">
            <i class="fa fa-undo"></i> Ya
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  @include($baseViewPath . '_js')
@endpush
