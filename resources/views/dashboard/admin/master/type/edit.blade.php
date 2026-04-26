@extends('layouts.app')

@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 font-weight-bold text-primary">{{ $pageTitle }}</h5>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {{-- {!! Form::open(['route' => [$baseRouteName . 'update', $id], 'class' => 'form-horizontal', 'method' => 'put']) !!}
    {{ Form::hidden('id', $id) }} --}}
    {{ html()->form('PUT')->route($baseRouteName . 'update', $id)->attributes([
            'class' => 'form-horizontal',
            // 'enctype' => 'multipart/form-data',
        ])->open() }}
    {{ html()->hidden('id', $id) }}
    <div class="card-body">

      @include('layouts.partials._notif')

      @include($baseViewPath . '_form')

    </div>
    <!-- /.box-body -->
    <div class="card-footer">
      @include('layouts.partials.form._edit-buttons')
    </div>
    <!-- /.box-footer -->
    {{ html()->form()->close() }}
  </div>

  @include('layouts.partials.form._edit-modal')
@endsection
