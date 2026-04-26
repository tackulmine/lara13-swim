@extends('layouts.app')

@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 font-weight-bold text-primary">{{ $pageTitle }}</h5>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {!! Form::open([
        'route' => [$baseRouteName . 'update', $id],
        'class' => 'needs-validation',
        'novalidate' => true,
        'method' => 'put',
        'files' => true,
        'autocomplete' => 'off',
    ]) !!}
    {{ Form::hidden('id', $id) }}
    <div class="card-body">

      @include('layouts.partials._notif')

      @include($baseViewPath . '_form')

    </div>
    <!-- /.box-body -->
    <div class="card-footer">
      {{-- @include('layouts.partials.form._edit-buttons', ['delete' => 'no']) --}}
      {{-- @include('layouts.partials.form._edit-buttons') --}}
      <button type="button" class="btn btn-outline-secondary mb-2 mb-lg-0" data-toggle="modal" data-target="#modal-discard">
        <i class="fa fa-undo"></i>
        Batal
      </button>
      @if (!request()->filled('completed'))
        <button type="button" class="btn btn-outline-danger mb-2 mb-lg-0" data-toggle="modal"
          data-target="#modal-delete">
          <i class="fa fa-trash"></i>
          Hapus
        </button>
      @endif
      @if (request()->filled('completed'))
        <button type="button" class="btn btn-outline-warning mb-2 mb-lg-0" data-toggle="modal"
          data-target="#modal-rollback">
          <i class="fa fa-envelope-open-text"></i>
          Registrasi ulang
        </button>
      @endif
      <div class="d-inline float-lg-right">
        <button type="submit" class="btn btn-outline-success mb-2 mb-lg-0" name="action" value="finished">
          <i class="fas fa-save"></i>
          Simpan dan kembali
        </button>
        <button type="submit" class="btn btn-outline-primary mb-2 mb-lg-0" name="action" value="continue">
          <i class="fas fa-save"></i>
          Simpan aja
        </button>
      </div>

    </div>
    <!-- /.box-footer -->
    {{ html()->form()->close() }}
  </div>

  @include('layouts.partials.form._edit-modal')

  @if (request()->filled('completed'))
    {{-- Confirm Rollback --}}
    <div class="modal fade" id="modal-rollback" tabIndex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Silakan Konfirmasi</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <p class="lead">
              <i class="fa fa-question-circle fa-lg"></i> Registrasi ulang {{ $moduleName ?? 'item' }} ini?
            </p>
            <div class="alert alert-warning">
              Hati-hati! Tindakan ini akan menghapus semua pendaftaran yang sudah pernah dibuat!
            </div>
          </div>
          <div class="modal-footer">
            <form action="{{ route($baseRouteName . 'rollback', $id) . getQueryHttpBuilder() }}" method="post">
              {{ csrf_field() }}
              {{ method_field('PUT') }}
              <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-outline-warning">
                <i class="fa fa-envelope-open-text"></i> Ya
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif
@endsection

{{-- @push('js')
  @include($baseViewPath . '_js')
@endpush --}}
