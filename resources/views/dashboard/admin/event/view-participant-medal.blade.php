@extends('layouts.app')

@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 font-weight-bold text-primary"><i class='fas fa-fw fa-medal'></i> {{ $pageTitle }}</h5>
    </div>
    <div class="card-body">

      {{-- @include('layouts.partials._notif') --}}

      {{-- include file --}}
      @include($baseViewPath . '_participant-medal-table')

    </div>
    {{-- <div class="card-footer">
            @include('layouts.partials.form._create-buttons')
        </div> --}}
  </div>

  {{-- @include('layouts.partials.form._create-modal') --}}
@endsection

{{-- @push('js')
    @include($baseViewPath . '_js')
@endpush --}}
