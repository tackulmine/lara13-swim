@extends('layouts.app')

@section('content')
  @include('layouts.partials._breadcrumbs')

  <div class="row">
    <div class="col-xl-8 offset-xl-2 col-lg-8 offset-lg-2 col-md-10 offset-md-1">

      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h5 class="m-0 font-weight-bold text-primary">{{ $pageTitle }}</h5>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {{-- {!! Form::open([
            'route' => [$baseRouteName . 'store', $event],
            'class' => 'needs-validation',
            'novalidate' => true,
            // 'files' => true,
            'autocomplete' => 'off',
        ]) !!} --}}
        {{ html()->form('POST', route($baseRouteName . 'store', [$event]))->class('needs-validation')->novalidate()->attribute('autocomplete', 'off')->open() }}
        <div class="card-body">

          @include('layouts.partials._notif')

          {{-- include file --}}
          @include($baseViewPath . '_form')

        </div>
        <!-- /.box-body -->
        <div class="card-footer">
          @include('layouts.partials.form._create-buttons')
        </div>
        <!-- /.box-footer -->
        {{ html()->form()->close() }}
      </div>

    </div>
  </div>

  {{-- @include('layouts.partials.form._create-modal') --}}

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
          <a href="{{ route($baseRouteName . 'index', [$event]) }}" class="btn btn-outline-warning">
            <i class="fa fa-undo"></i> Ya
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  @include($baseViewPath . '_form-js')
@endpush
