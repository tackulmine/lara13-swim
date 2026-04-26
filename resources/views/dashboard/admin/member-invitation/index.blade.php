@extends('layouts.app')

@section('content')
  <!-- Page Heading -->
  {{-- <h1 class="h3 mb-4 text-gray-800">{{ $pageTitle }}</h1> --}}

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      {{-- <a href="{!! route($baseRouteName . 'create') !!}" title="Tambah Baru" class="btn btn-outline-primary btn-sm btn-create float-right">
        <i class="fas fa-plus"></i> Baru
      </a> --}}
    </div>
    <div class="card-body">
      <div class="table-responsive">

        @include('layouts.partials._notif')

        <form action="{{ route($baseRouteName . 'destroy-batch') }}" method="post" class="mb-4" id="invitations-form">
          @csrf

          @method('delete')
          @if (!request()->filled('completed'))
            <button type="submit" class="btn btn-danger mb-4" data-toggle="tooltip"
              title="Hapus permanen semua baris terpilih"><i class="fa fa-trash-alt fa-fw"></i> Batch Delete</button>
          @endif

          <table class="table table-striped table-bordered" id="dataTableCustom" width="100%" cellspacing="0"
            data-order="[[ 4, &quot;desc&quot; ]]">
            <thead>
              @include($baseViewPath . '_table-head')
            </thead>
            <tfoot>
              @include($baseViewPath . '_table-head')
            </tfoot>
            <tbody>
              @foreach ($invitations as $invitation)
                <tr>
                  <td>{{ $invitation->id }}</td>
                  <td>{{ $loop->iteration }}</td>
                  <td><a href="mailto:{{ $invitation->email }}">{{ $invitation->email }}</a></td>
                  <td>
                    <kbd>{{ $invitation->getLink() }}</kbd>
                  </td>
                  <td data-order="{{ $invitation->created_at->timestamp }}">
                    <span href="javascript:;" data-toggle="tooltip"
                      title="{{ $invitation->created_at->diffForHumans() }}">
                      {{ $invitation->created_at->format(config('general.manage_datetime_format')) }}
                    </span>
                  </td>
                  <td data-order="{{ optional($invitation->registered_at)->timestamp }}">
                    <span href="javascript:;" data-toggle="tooltip"
                      title="{{ optional($invitation->registered_at)->diffForHumans() }}">
                      {{ optional($invitation->registered_at)->format(config('general.manage_datetime_format')) }}
                    </span>
                  </td>
                  <td>
                    <a href="{!! route($baseRouteName . 'edit', $invitation) . getQueryHttpBuilder() !!}" title="Edit {{ $invitation->emaail }}"
                      class="btn btn-primary btn-sm btn-circle btn-edit"><i class="fas fa-edit"></i></a>
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

@push('css')
@endpush

@push('js')
  @include($baseViewPath . '_index-js')
@endpush
