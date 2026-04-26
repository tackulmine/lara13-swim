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
      @include('layouts.partials.form._edit-buttons', ['delete' => 'no'])
      {{-- <button type="button" class="btn btn-outline-secondary mb-2 mb-lg-0"
              data-toggle="modal" data-target="#modal-discard">
              <i class="fa fa-undo"></i>
              Batal
            </button>
            @if ($staff->id > 2)
              <button type="button" class="btn btn-outline-danger mb-2 mb-lg-0"
                data-toggle="modal" data-target="#modal-delete">
                <i class="fa fa-trash"></i>
                Hapus
            </button>
            @endif
            <button type="submit" class="btn btn-outline-primary float-lg-right mb-2 mb-lg-0"
              name="action" value="continue">
              <i class="fas fa-save"></i>
              Simpan dan tetap edit
            </button>
            <button type="submit" class="btn btn-outline-success float-lg-right mr-lg-2"
              name="action" value="finished">
              <i class="fas fa-save"></i>
              Simpan dan kembali
            </button> --}}
    </div>
    <!-- /.box-footer -->
    {{ html()->form()->close() }}
  </div>

  @include('layouts.partials.form._edit-modal')
@endsection

@push('js')
  @include($baseViewPath . '_js')
@endpush
