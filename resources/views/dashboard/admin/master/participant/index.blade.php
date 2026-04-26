@extends('layouts.app')

@section('content')
  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      <div class="tools float-right">
        <a href="{!! route($baseRouteName . 'create') !!}" title="Tambah Baru" class="btn btn-outline-primary btn-sm btn-create">
          <i class="fas fa-plus"></i> Baru
        </a>
        {{-- <a href="{!! route($baseRouteName . 'create-batch') !!}" title="Tambah Baru Batch" class="btn btn-outline-primary btn-sm btn-create">
                    <i class="fas fa-plus"></i> Baru Batch
                </a> --}}
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @include('layouts.partials._notif')

        <form action="{{ route($baseRouteName . 'destroy-batch') }}" method="post" class="mb-4" id="school-form">
          @csrf
          @method('delete')

          <button type="submit" class="btn btn-danger mb-4" data-toggle="tooltip" title="Delete selected rows">
            <i class="fa fa-trash-alt"></i> Batch Delete
          </button>
          <a href="{!! route($baseRouteName . 'merger') !!}" title="Merge selected rows {{ $moduleName }}"
            data-title="Merge {{ $moduleName }}" data-action="{!! route($baseRouteName . 'update-merger') !!}" data-toggle="tooltip"
            data-target="#myEditModal" class="btn btn-warning btn-merger mb-4">
            <i class="fa fa-layer-group"></i> Gabungkan Data
          </a>
          <a href="#!" class="btn btn-primary btn-dt-state-clear mb-4"><i class="fa fa-undo"></i> Reset</a>

          <table class="table table-striped table-bordered" id="dataTableCustom" width="100%" cellspacing="0"
            data-order="[[ 2, &quot;asc&quot; ]]">
            <thead>
              @include($baseViewPath . '_table-head')
            </thead>
            <tfoot>
              @include($baseViewPath . '_table-head')
            </tfoot>
            <tbody>
              @foreach ($participants as $participant)
                <tr>
                  <td>{{ $participant->id }}</td>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $participant->name }}</td>
                  <td>{{ $participant->gender_text }}</td>
                  <td class="text-center">{{ $participant->birth_year }}</td>
                  <td>{{ optional($participant->masterSchool)->name ?? '-' }}</td>
                  {{-- <td>{{ $participant->address }}</td>
                                    <td>{{ $participant->location }}</td> --}}
                  <td class="text-right">{{ $participant->event_registrations_count }}</td>
                  <td class="text-right">{{ $participant->event_session_participants_count }}</td>
                  <td class="text-right">{{ $participant->styles_count }}</td>
                  <td class="text-right">{{ $participant->event_session_participant_details_count }}</td>
                  {{-- <td><span title="{{ $participant->birth_date->format('d/m/Y') }}">{{ $participant->birth_date->toFormattedDateString() }}/{{ $participant->birth_date->age }}</span></td> --}}
                  <td>
                    <a href="{!! route($baseRouteName . 'edit', $participant->id) !!}" title="Edit {{ $participant->name }}"
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
  @component('components.bs4.modal', [
      'modalId' => 'myEditModal',
      'modalClass' => '',
      'modalSize' => 'modal-lg',
      'modalTitle' => '',
      'modalFormUrl' => '#!',
      'modalFormAttributes' => ['method' => 'put', 'class' => 'form-disabled-submit'],
  ])
    @method('put')
  @endcomponent
@endsection

@push('js')
  @include($baseViewPath . '_index-js')
@endpush
