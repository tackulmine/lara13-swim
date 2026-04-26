@extends('layouts.app')

@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 font-weight-bold text-primary">{{ $pageTitle }}</h5>
    </div>
    <!-- /.box-header -->
    <div class="card-body">
      @include($baseViewPath . '_show')
    </div>
    <!-- /.box-body -->
    <div class="card-footer text-center">
      <a href="{!! route($baseRouteName . 'print', $member->id) !!}" class="btn btn-primary" target="_blank">
        <i class="fa fa-print"></i>
        Print
      </a>
    </div>
    <!-- /.box-footer -->
  </div>
@endsection

@push('css')
  @include($baseViewPath . '_css')
@endpush
@push('js')
  @include($baseViewPath . '_js')
@endpush
