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
            'route' => [$baseRouteName . 'update', $event, $eventStage, $eventSession, $eventSessionParticipant],
            'class' => 'form-horizontal',
            'method' => 'put',
            'autocomplete' => 'off',
        ]) !!}
        {{-- {{ Form::hidden('id', $id) }} --}}
        <div class="card-body">

          @include('layouts.partials._notif')

          @include($baseViewPath . '_form')

        </div>
        <!-- /.box-body -->
        <div class="card-footer">
          @include('layouts.partials.form._edit-buttons', ['delete' => 'no'])
        </div>
        <!-- /.box-footer -->
        {{ html()->form()->close() }}
      </div>
    </div>
  </div>

  @include($parentViewPath . 'event.stage.session.participant._detail-participant')
@endsection

@push('js')
  {{-- @include($baseViewPath . '_js') --}}
@endpush
