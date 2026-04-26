@extends('layouts.app')

@section('content')
  <!-- Page Heading -->
  {{-- <h1 class="h3 mb-4 text-gray-800">Kompetisi</h1> --}}

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      <a href="{!! route($baseRouteName . 'create') !!}" title="Tambah Baru" class="btn btn-outline-primary btn-sm btn-create float-right">
        <i class="fas fa-plus"></i> Baru
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @include('layouts.partials._notif')
        <form
          action="{{ route($baseRouteName . (request()->filled('trashed') ? 'restore-batch' : 'destroy-batch'), request()->filled('trashed') ? request()->only('trashed') : null) }}"
          method="post" class="mb-4" id="kejuaraan-form">
          @csrf
          @if (request()->filled('trashed'))
            @method('put')
            <button type="submit" class="btn btn-success mb-4" data-toggle="tooltip"
              title="Aktifkan baris terpilih!">Batch Aktivasi</button>
          @else
            @method('delete')
            <button type="submit" class="btn btn-danger mb-4" data-toggle="tooltip"
              title="Non-aktifkan baris terpilih!">Batch Non-aktivasi</button>
          @endif
          {{-- <table class="table table-striped table-bordered dt-responsive nowrap" --}}
          <table class="table table-striped table-bordered" id="dataTableCustom" width="100%" cellspacing="0"
            data-order="[[ 5, &quot;desc&quot; ]]">
            <thead>
              @include($baseViewPath . '_table-head')
            </thead>
            <tfoot>
              @include($baseViewPath . '_table-head')
            </tfoot>
            <tbody>
              @foreach ($events as $event)
                <tr>
                  <td>{{ $event->id }}</td>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $event->masterChampionship->name }}</td>
                  <td>{{ $event->address }}</td>
                  <td>{{ $event->location }}</td>
                  <td class="text-right" data-order="{{ $event->start_date->timestamp }}">
                    {{-- <span title="{{ $event->start_date->format('d/m/Y') }}">
                    {{ $event->start_date->diffForHumans() }}
                </span> --}}
                    {!! parseBetweenDate($event->start_date, $event->end_date) !!}
                  </td>
                  <td class="text-right">{{ $event->userChampionships->pluck('user_id')->unique()->count() }}</td>
                  <td class="text-right">{{ $event->user_championships_count }}</td>
                  <td>{!! empty($event->deleted_at)
                      ? '<span class="badge badge-success">Aktif</span>'
                      : '<span class="badge badge-danger">Non-aktif</span>' !!}</td>
                  <td>

                    @if (!request()->filled('trashed'))
                      <a href="{!! route($baseRouteName . 'edit', $event) !!}" title="Edit {{ $event->masterChampionship->name }}"
                        class="btn btn-primary btn-sm btn-circle btn-edit"><i class="fas fa-edit"></i></a>
                      <a href="{!! route($baseRouteName . 'participant.index', $event) !!}" title="Peserta {{ $event->masterChampionship->name }}"
                        class="btn btn-primary btn-sm btn-circle btn-edit"><i class="fas fa-users"></i></a>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('js')
  @include($baseViewPath . '_table-js')
@endpush
