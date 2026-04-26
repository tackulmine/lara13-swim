@extends('layouts.app')

@section('content')
  <!-- Page Heading -->
  {{-- <h1 class="h3 mb-4 text-gray-800">Kompetisi</h1> --}}

  <!-- Tables -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 pt-1 font-weight-bold text-primary float-left">{{ $pageTitle }}</h5>
      @canrole(['coach'])
      <a href="{!! route($baseRouteName . 'create') !!}" title="Tambah Baru" class="btn btn-outline-primary btn-sm btn-create float-right">
        <i class="fas fa-plus"></i> Baru
      </a>
      @endcanrole
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @include('layouts.partials._notif')
        @canrole(['coach'])
        <form action="{{ route($baseRouteName . (request()->filled('trashed') ? 'restore-batch' : 'destroy-batch')) }}"
          method="post" class="mb-4" id="member-form">
          @csrf
          @endcanrole

          <div class="row mb-4">
            <div class="col-6">
              @canrole(['coach'])
              @if (request()->filled('trashed'))
                @method('put')
                <button type="submit" class="btn btn-success" data-toggle="tooltip" title="Aktifkan baris terpilih!"><i
                    class="fa fa-user-plus fa-fw"></i> Batch Aktivasi</button>
              @else
                @method('delete')
                <button type="submit" class="btn btn-danger" data-toggle="tooltip"
                  title="Non-aktifkan baris terpilih!"><i class="fa fa-user-slash fa-fw"></i> Batch Non-aktivasi</button>
              @endif
              @endcanrole
            </div>
            <div class="col-6 text-right">
              <a href="#!" class="btn btn-primary btn-dt-state-clear"><i class="fa fa-undo fa-fw"></i> Reset
                Filter</a>
            </div>
          </div>

          {{-- <table class="table table-striped table-bordered dt-responsive nowrap" --}}
          <table class="table table-striped table-bordered" id="dataTableCustom" width="100%" cellspacing="0"
            data-order="[[ 8, &quot;desc&quot; ]]">
            <thead>
              @include($baseViewPath . '_table-head')
            </thead>
            <tfoot>
              @include($baseViewPath . '_table-head')
            </tfoot>
            <tbody>
              @foreach ($members as $member)
                @php
                  $userProfile = optional($member->profile);
                  $userMember = optional($member->userMember);
                  $userEducation = optional(optional($member->educations)->first());
                @endphp
                <tr>
                  <td>{{ $member->id }}</td>
                  <td>{{ $loop->iteration }}</td>
                  {{-- <td><a href="#!" data-toggle="tooltip"
                          title="{{ $member->name }}">{{ $member->username }}</a></td> --}}
                  <td>{{ $member->name }} ({{ $member->username }})</td>
                  <td class="text-right">{{ $userProfile->age }}</td>
                  <td class="text-center">{{ $userProfile->gender == 'male' ? 'L' : 'P' }}</td>
                  {{-- <td class="text-center">
                    {{ $userMember->class ? optional($userMember->class)->name : '-' }}</td> --}}
                  <td class="text-right">{{ $userMember->nis ?? '-' }}</td>
                  {{-- <td class="text-center">
                    {{ optional($userMember->type)->name == 'Athlete' ? __('Atlet') : 'Non-atlit' }}</td> --}}
                  <td>{{ $userProfile->phone_number }}<br>{{ $member->email }}</td>
                  <td class="text-center">{!! $member->preview_tiny_fancy_photo !!}</td>
                  {{-- <td>{{ optional($userEducation->school)->name }}</td> --}}
                  {{-- <td>{{ $userProfile->address }}</td> --}}
                  {{-- <td>{{ $userProfile->location }}</td> --}}
                  <td data-order="{{ $member->created_at->timestamp }}">
                    {{ $member->created_at->format(config('general.manage_datetime_format')) }}
                  </td>
                  {{-- <td data-order="{{ $member->updated_at->timestamp }}">
                    {{ $member->updated_at->format(config('general.manage_datetime_format')) }}
                  </td> --}}
                  @if (auth()->user()->isCoach())
                    <td class="text-center">{!! empty($member->deleted_at)
                        ? '<span class="badge badge-success">Aktif</span>'
                        : '<span class="badge badge-danger">Non-aktif</span>' !!}</td>
                  @endif
                  {{-- <td><span title="{{ $member->birth_date->format('d/m/Y') }}">{{ $member->birth_date->toFormattedDateString() }}/{{ $member->birth_date->age }}</span></td> --}}
                  <td class="text-center">
                    @if (!request()->filled('trashed'))
                      <a href="{!! route($baseRouteName . 'edit', $member->id) !!}" title="Edit {{ $member->name }}"
                        class="btn btn-primary btn-sm btn-circle btn-edit"><i class="fas fa-edit"></i></a>
                      <a href="{!! route($baseRouteName . 'show', $member->id) !!}" title="View {{ $member->name }}"
                        class="btn btn-primary btn-sm btn-circle btn-show"><i class="fas fa-eye"></i></a>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

          @canrole(['coach'])
        </form>
        @endcanrole
      </div>
    </div>
  </div>
@endsection

@push('css')
@endpush

@push('js')
  <script src="/assets/back/js/fancybox-download.js"></script>
  @include($baseViewPath . '_table-js')
@endpush
