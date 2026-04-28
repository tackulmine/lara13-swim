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
            'route' => [$baseRouteName . 'update', $event, $userChampionship],
            'class' => 'needs-validation',
            'novalidate' => true,
            'method' => 'put',
            // 'files' => true,
            'autocomplete' => 'off',
        ]) !!} --}}
        {{-- {{ Form::hidden('id', $id) }} --}}
        {{ html()->form('PUT', route($baseRouteName . 'update', [$event, $userChampionship]))->class('needs-validation')->novalidate()->attribute('autocomplete', 'off')->open() }}
        {{ html()->hidden('id', $id) }}
        <div class="card-body">

          @include('layouts.partials._notif')

          @include($baseViewPath . '_form')

        </div>
        <!-- /.box-body -->
        <div class="card-footer">
          {{-- @include('layouts.partials.form._edit-buttons', ['delete' => 'no']) --}}
          @include('layouts.partials.form._edit-buttons')
        </div>
        <!-- /.box-footer -->
        {{ html()->form()->close() }}
      </div>

    </div>
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
          <a href="{{ route($baseRouteName . 'index', [$event]) . getQueryHttpBuilder() }}" class="btn btn-warning">
            <i class="fa fa-undo"></i> Ya
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Confirm Delete --}}
  <div class="modal fade" id="modal-delete" tabIndex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Silakan Konfirmasi</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <p class="lead">
            <i class="fa fa-question-circle fa-lg"></i> Hapus {{ $moduleName ?? 'item' }} ini?
          </p>
        </div>
        <div class="modal-footer">
          <form action="{{ route($baseRouteName . 'destroy', [$event, $id]) . getQueryHttpBuilder() }}" method="post">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-outline-danger">
              <i class="fa fa-trash"></i> Ya
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  @include($baseViewPath . '_form-js')
@endpush
