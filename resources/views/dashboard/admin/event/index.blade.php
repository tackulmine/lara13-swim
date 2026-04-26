@extends('layouts.app')

@section('content')
  {{-- @include('layouts.partials._breadcrumbs') --}}

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
          method="post" class="mb-4" id="kompetisi-form">
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
          <table class="table table-striped table-bordered" id="dataTableFooterCustom" width="100%" cellspacing="0"
            data-order="[[ 5, &quot;desc&quot; ]]">
            <thead>
              @include($baseViewPath . '_thead')
            </thead>
            <tfoot>
              @include($baseViewPath . '_thead')
            </tfoot>
            <tbody>
              @foreach ($events as $event)
                <tr {!! now() <= $event->end_date->toDateString() ? 'style="font-weight: bold;"' : '' !!}>
                  <td>{{ $event->id }}</td>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $event->name }}</td>
                  {{-- <td>{{ $event->address }}</td> --}}
                  <td>{{ $event->location }}</td>
                  <td>{!! $event->preview_tiny_photo !!}</td>
                  <td data-order="{{ $event->start_date->timestamp }}">
                    {!! parseBetweenDate($event->start_date, $event->end_date) !!}
                  </td>
                  <td>{{ $event->is_reg ? 'Aktif' : 'Non aktif' }}/
                    {{ !empty($event->reg_end_date) ? $event->reg_end_date->format('j-M-Y') : '-' }}/
                    {{ $event->reg_quota ?? '-' }}</td>
                  @if (!auth()->user()->hasRole('external') || auth()->user()->isSuperuser())
                    <td data-order="{{ $event->is_external ? 'external' : 'internal' }}"
                      data-search="{{ $event->is_external ? 'external' : 'internal' }}">
                      {!! $event->is_external
                          ? '<span class="badge badge-danger">external</span>'
                          : '<span class="badge badge-success">internal</span>' !!}
                    </td>
                  @endif
                  <td class="text-right">
                    <a href="{{ route($baseRouteName . 'stage.index', $event->id) }}">
                      {{ $event->eventStages->count() }}
                    </a>
                  </td>
                  <td class="text-right">{{ $event->eventSessions->count() }}</td>
                  <td class="text-right">
                    <a href="{{ route($baseRouteName . 'participant.index', $event->id) }}">
                      {{ $event->eventParticipants->count() }}
                    </a>
                    ({{ $event->eventParticipants->pluck('master_participant_id')->unique()->count() }})
                  </td>
                  <td class="text-center" data-order="{{ $event->completed ? 'selesai' : 'belum' }}"
                    data-search="{{ $event->completed ? 'selesai' : 'belum' }}">
                    {!! $event->completed
                        ? '<span class="badge badge-success">selesai</span>'
                        : '<span class="badge badge-primary">belum</span>' !!}
                  </td>
                  <td>
                    <div class="dropdown">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownActionBtn"
                        data-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu" aria-labelledby="dropdownActionBtn">

                        @if (!request()->filled('trashed'))
                          @if (!$event->completed)
                            <a class="dropdown-item" href="{{ $event->qr_code_url }}" data-fancybox>
                              <i class="fas fa-fw fa-qrcode"></i> Show QR Code
                            </a>
                          @endif
                          <a class="dropdown-item" href="{!! route($baseRouteName . 'edit', $event->id) !!}">
                            <i class="far fa-fw fa-edit"></i> Edit Kompetisi
                          </a>

                          @if (!$event->completed)
                            @if (!$event->eventSessions->where('completed', true)->count())
                              <a class="dropdown-item" href="{!! route($baseRouteName . 'category.index', $event->id) !!}">
                                <i class="fas fa-swimmer fa-fw"></i> Edit {{ __('Kategori') }}
                              </a>
                              <a class="dropdown-item" href="{!! route($baseRouteName . 'type.index', $event->id) !!}">
                                <i class="fas fa-swimming-pool fa-fw"></i> Edit {{ __('Gaya') }}
                              </a>
                            @endif

                            @if ($event->is_has_mix_gender)
                              <a class="dropdown-item" href="{!! route($baseRouteName . 'estafet.edit', $event->id) !!}">
                                <i class="fas fa-user-friends fa-fw"></i> Edit {{ __('Peserta Estafet') }}
                              </a>
                            @endif
                            <a class="dropdown-item" href="{!! route($baseRouteName . 'book.index', $event->id) !!}">
                              <i class="fas fa-file-excel fa-fw"></i> Generate {{ __('Buku Acara') }}
                            </a>

                            @if (!$event->completed)
                              <a class="dropdown-item" href="{!! route($baseRouteName . 'registration.atlet', $event->id) !!}">
                                <i class="fas fa-users fa-fw"></i> Registrasi Peserta
                                ({{ $event->event_registrations_count }})
                              </a>
                            @endif

                            <a class="dropdown-item" href="{!! route($baseRouteName . 'import', $event->id) !!}">
                              <i class="fas fa-fw fa-upload"></i> Import/Reset Peserta</a>
                          @endif

                          <a class="dropdown-item" href="{!! route('competition.detail', $event->slug) !!}" target="_blank">
                            <i class="fas fa-fw fa-external-link-alt"></i> Isi Limit Kompetisi
                          </a>
                        @endif

                        <a class="dropdown-item" href="{!! route($baseRouteName . 'stage.index', $event->id) !!}">
                          <i class="far fa-fw fa-eye"></i> Lihat Semua Acara
                        </a>
                        <a class="dropdown-item" href="{!! route($baseRouteName . 'participant.index', $event->id) !!}">
                          <i class="far fa-fw fa-eye"></i> Lihat Semua Peserta
                        </a>
                        @if ($event->eventStages->count())
                          <a class="dropdown-item" href="{!! route($baseRouteName . 'download_event_book', $event->id) !!}?type=pdf">
                            <i class="far fa-fw fa-file-pdf"></i> Download Buku Acara (PDF)
                          </a>
                          <a class="dropdown-item" href="{!! route($baseRouteName . 'download_event_book', $event->id) !!}?view_only=1" target="_blank">
                            <i class="far fa-fw fa-file-pdf"></i> Download Buku Acara (Browser)
                          </a>
                        @endif
                        @if ($event->completed)
                          <a class="dropdown-item" href="{!! route($baseRouteName . 'download_report_book', $event->id) !!}?type=pdf">
                            <i class="far fa-fw fa-file-pdf"></i> Download Buku Hasil (PDF)
                          </a>
                          <a class="dropdown-item" href="{!! route($baseRouteName . 'download_report_book', $event->id) !!}?view_only=1" target="_blank">
                            <i class="far fa-fw fa-file-pdf"></i> Download Buku Hasil (Browser)
                          </a>
                          <a class="dropdown-item" href="{!! route($baseRouteName . 'view_medal_participant', $event->id) !!}">
                            <i class="fas fa-fw fa-medal"></i> Perolehan Medali Kompetisi
                          </a>
                        @endif
                      </div>
                    </div>
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
      'modalSize' => '',
      'modalTitle' => '',
      'modalFormUrl' => '#!',
      'modalFormAttributes' => ['method' => 'put', 'class' => 'form-disabled-submit'],
  ])
    @method('put')
  @endcomponent
@endsection

@push('js')
  @include($baseViewPath . '_form-js')
  @include($baseViewPath . '_table-js')
@endpush
