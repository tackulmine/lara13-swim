@php
  $isTargetRequest = request()->is('*member-gaya-limit*');
@endphp
<li class="nav-item {{ $isTargetRequest ? 'active' : '' }}">
  <a class="nav-link {{ $isTargetRequest ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
    data-target="#collapse{{ __('Atlet') }}TargetLimit" aria-expanded="{{ $isTargetRequest ? 'true' : 'false' }}"
    aria-controls="collapse{{ __('Atlet') }}TargetLimit">
    <i class="fas fa-fw fa-clock"></i>
    <span>Target (/{{ __('Gaya') }}/Bulan)</span>
  </a>
  <div id="collapse{{ __('Atlet') }}TargetLimit" class="collapse {{ $isTargetRequest ? 'show' : '' }}"
    aria-labelledby="headingPages" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item {{ $isTargetRequest &&
      !request()->is('*member-gaya-limit/create') &&
      !request()->is('*member-gaya-limit/create-batch')
          ? 'active'
          : '' }}"
        href="{{ route('dashboard.admin.member-gaya-limit.index') }}"><i class="fas fa-table"></i> Daftar Target</a>
      <a class="collapse-item {{ request()->is('*member-gaya-limit/create') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.member-gaya-limit.create') }}"><i class="fas fa-plus"></i> Buat Target</a>
      <a class="collapse-item {{ request()->is('*member-gaya-limit/create-batch') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.member-gaya-limit.create-batch') }}" data-toggle="tooltip"
        title="Tambah Batch {{ __('Atlet') }} Target (/{{ __('Gaya') }}/Bulan)"><i class="fas fa-plus"></i> Buat
        Batch Target</a>
    </div>
  </div>
</li>
