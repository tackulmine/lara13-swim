@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h5 class="m-0 font-weight-bold text-primary">{{ $pageTitle }}</h5>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {{-- <form class="form-horizontal" action="{{ route($baseRouteName.'store') }}">
        </form> --}}
        {!! Form::open([
            'route' => $baseRouteName . 'store-batch',
            'class' => 'needs-validation',
            'novalidate' => true,
        ]) !!}
        <div class="card-body">

          @include('layouts.partials._notif')

          {{-- include file --}}
          @include($baseViewPath . '_form-batch')

        </div>
        <!-- /.box-body -->
        <div class="card-footer text-center text-md-left">
          @include('layouts.partials.form._create-buttons')
        </div>
        <!-- /.box-footer -->
        {{ html()->form()->close() }}
      </div>
    </div>
  </div>

  @include('layouts.partials.form._create-modal')
@endsection

@push('css')
  @include($baseViewPath . '_css')
@endpush
@push('js')
  @include($baseViewPath . '_js')
@endpush
