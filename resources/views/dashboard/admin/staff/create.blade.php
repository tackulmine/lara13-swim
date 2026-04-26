@extends('layouts.app')

@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 font-weight-bold text-primary">{{ $pageTitle }}</h5>
    </div>
    <!-- /.box-header -->
    {!! Form::open([
        'route' => $baseRouteName . 'store',
        'class' => 'needs-validation',
        'novalidate' => true,
        'files' => true,
        'autocomplete' => 'off',
    ]) !!}
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

  @include('layouts.partials.form._create-modal')
@endsection

@push('js')
  @include($baseViewPath . '_js')
@endpush
