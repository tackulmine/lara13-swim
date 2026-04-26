@extends('layouts.app')

@section('content')
  @include('layouts.partials._breadcrumbs')

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      {{-- <a href="{!! route($baseRouteName . 'edit', $event->id) !!}" title="Edit Tipe {{ $event->name }}" data-title="Edit Tipe {{ $event->name }}"
        data-action="{!! route($baseRouteName . 'update', $event->id) !!}" data-toggle="modal" data-target="#myEditModal"
        class="btn btn-outline-primary btn-sm float-right btn-edit">
        <i class="fas fa-edit"></i> Update Tipe
      </a> --}}
    </div>
    <div class="card-body">
      <div class="alert alert-info">Untuk mengurutkan baris, silakan drag baris (kolom no) yang dipilih dan drop ke posisi
        baris yang diinginkan.</div>
      <div class="table-responsive">
        @include('layouts.partials._notif')
        <table class="table table-striped table-bordered" id="dataTableReorder" width="100%" cellspacing="0"
          data-order="[[ 0, &quot;asc&quot; ]]">
          <thead>
            @include($baseViewPath . '_thead')
          </thead>
          <tfoot>
            @include($baseViewPath . '_thead')
          </tfoot>
          <tbody>
            @forelse ($eventNumbers as $eventNumber)
              <tr id="{{ $eventNumber->event_registration_number_id ?? $loop->iteration }}">
                <td style="cursor: grab;">
                  <i class="fas fa-sort"></i> {{ $loop->iteration }}
                </td>
                <td>{{ $eventNumber->type_name }}</td>
                <td>{{ $eventNumber->category_name }}</td>
                <td class="text-right">{{ $eventNumber->total }}</td>
                <td class="text-right">{{ ceil($eventNumber->total / $event->total_track) }}</td>
                <td class="text-right">{{ $eventNumber->order_number ?? $loop->iteration }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="100" align="center">Data kosong!</td>
              </tr>
            @endforelse
          </tbody>
        </table>
        {!! Form::open([
            'route' => [$baseRouteName . 'set-ordering', $event->id],
            'id' => 'reorder-form',
            // 'class' => 'form-horizontal',
            'class' => 'form-disabled-submit',
            // 'files' => true,
            'method' => 'put',
        ]) !!}
        {{ Form::hidden('items_id') }}
        <div class="text-center">
          <button type="submit" class="btn btn-primary mb-2 mb-md-0">
            <i class="fas fa-save"></i>
            Simpan Urutan
          </button>
          <a href="?reset_all=true" class="btn btn-outline-warning"
            onclick="return confirm('Yakin mereset ulang urutan?');">
            <i class="fas fa-undo"></i> Regenerate Ulang Urutan</a>
        </div>
        {{ html()->form()->close() }}

        <div class="text-center mt-2">
          <a href="{{ route($baseRouteName . 'download', $event) }}?view_only=true" target="_blank"
            class="btn btn-outline-info mb-2 mb-md-0">
            <i class="fas fa-eye"></i> Lihat Buku Acara
          </a>
          <a href="{{ route($baseRouteName . 'download', $event) }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel"></i> Download Buku Acara
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('css')
  {{-- <link rel="stylesheet" href="/assets/plugins/datatables/rowreorder-datatables/1.5.0/css/rowReorder.dataTables.min.css"> --}}
  <link rel="stylesheet" href="/assets/plugins/datatables/rowreorder-bootstrap4/1.5.0/css/rowReorder.bootstrap4.min.css">
@endpush

@push('js')
  <script src="/assets/plugins/datatables/rowreorder/1.5.0/js/dataTables.rowReorder.min.js"></script>

  @include($baseViewPath . '_table-js')
@endpush
