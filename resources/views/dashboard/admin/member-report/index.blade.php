@extends('layouts.app')

@section('content')

  <!-- Page Heading -->
  {{-- <h1 class="h3 mb-4 text-gray-800">Kompetisi</h1> --}}

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
    </div>
    <div class="card-body">
      <div class="mb-4">
        {!! Form::open([
            'route' => $baseRouteName . 'index',
            'class' => 'needs-validation',
            'novalidate' => true,
            'method' => 'get',
        ]) !!}

        {{ Form::bs4HorSelect(
            'user_id',
            $memberOptions,
            request('user_id') ?? '',
            [
                'class' => 'form-control',
                'required' => 'required',
                'data-target-url' => route('dashboard.admin.member-limit.ajax-limit-gaya'),
                'data-target' => '#master_gaya_id',
            ],
            __('Nama Lengkap Atlet'),
        ) }}
        {{ Form::bs4HorSelect(
            'master_gaya_id',
            $masterGayaOptions,
            request('master_gaya_id') ?? '',
            [
                'id' => 'master_gaya_id',
                'class' => 'form-control',
                'required' => 'required',
            ],
            __('Gaya'),
        ) }}
        <div class="form-group row">
          <label for="" class="col-sm-3 col-form-label">Periode</label>
          <div class="col-lg-5">
            <div class="input-group input-daterange" data-provide="datepicker" data-date-format="mm-yyyy"
              data-date-view-mode="months" data-date-min-view-mode="months" data-date-end-date="0d"
              data-date-autoClose="true" data-date-language="id" autocomplete="false">
              {!! Form::text('periode_start', request('periode_start') ?? '', [
                  'class' => 'form-control',
                  'required' => 'required',
                  'autocomplete' => 'off',
                  'data-target-url' => route('dashboard.admin.member-limit.ajax-limit-gaya'),
                  'data-target' => '#master_gaya_id',
              ]) !!}
              <div class="input-group-append"><span class="input-group-text">sampai</span></div>
              {!! Form::text('periode_end', request('periode_end') ?? '', [
                  'class' => 'form-control',
                  'required' => 'required',
                  'autocomplete' => 'off',
                  'data-target-url' => route('dashboard.admin.member-limit.ajax-limit-gaya'),
                  'data-target' => '#master_gaya_id',
              ]) !!}
            </div>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-sm-10 offset-sm-3">
            <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
          </div>
        </div>

        {{ html()->form()->close() }}
      </div>
      @if (request()->filled('user_id'))
        <hr>
        <!-- Member Profile -->
        <h5>Profil {{ __('Atlet') }}</h5>
        @include($baseRouteName . '_result-atlit')

        <!-- Nav tabs -->
        {{-- <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="table-tab" data-toggle="tab" href="#table" role="tab" aria-controls="table" aria-selected="true">Table</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="grafik-tab" data-toggle="tab" href="#grafik" role="tab" aria-controls="grafik" aria-selected="false">Grafik</a>
        </li>
      </ul> --}}

        <!-- Tab panes -->
        {{-- <div class="tab-content">
        <div class="tab-pane active" id="table" role="tabpanel" aria-labelledby="table-tab"> --}}
        <h5>Tabel Limit</h4>
          @include($baseRouteName . '_result-table')
          {{-- </div>
        <div class="tab-pane" id="grafik" role="tabpanel" aria-labelledby="grafik-tab"> --}}
          <h5>Grafik Limit</h5>
          @include($baseRouteName . '_result-chart')
          {{-- </div>
      </div> --}}
          @if ($memberLimits->isNotEmpty())
            <hr>
            <div class="text-center">
              <a href="{{ route($baseRouteName . 'index') . '?print=true' . getQueryHttpBuilder('&') }}"
                class="btn btn-primary" target="_blank">
                <i class="fa fa-print"></i> Print
              </a>
            </div>
          @endif
      @endif
    </div>
  </div>

@endsection

@push('css')
  @include($baseRouteName . '_css')
@endpush
@push('js')
  <script>
    $(function() {
      $('[name="user_id"],[name="periode_start"],[name="periode_end"]').on('change', function() {
        var t = $(this);
        var target = t.attr('data-target');
        var urlx = t.attr('data-target-url');
        var params = {
          'user_id': $('[name="user_id"]').val(),
          'periode_start': $('[name="periode_start"]').val(),
          'periode_end': $('[name="periode_end"]').val(),
        };
        $.get(urlx, params)
          .done(function(data) {
            // alert( "Data Loaded: " + data );
            $(target).html($("<option></option>")
              .attr("value", '')
              .text('-- Pilih --'));

            // $.each(data, function(index, value) {
            //   $(target).append($("<option></option>")
            //     .attr("value", key)
            //     .text(value));
            // });

            // START sorting
            var temp = [];
            $.each(data, function(key, value) {
              temp.push({
                v: value,
                k: key
              });
            });
            temp.sort(function(a, b) {
              if (a.v > b.v) {
                return 1;
              }
              if (a.v < b.v) {
                return -1;
              }
              return 0;
            });
            // END sorting

            // print
            $.each(temp, function(key, obj) {
              $(target).append($("<option></option>")
                .attr("value", obj.k)
                .text(obj.v));
            });

            // trigger select2 change
            $(target).trigger('change');
          });
      });
    });
  </script>
  @include($baseRouteName . '_js')
@endpush
