@php
  $isLimitRequest = request()->is('*member-limit*');
@endphp
<li class="nav-item {{ $isLimitRequest ? 'active' : '' }}">
  <a class="nav-link {{ $isLimitRequest ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
    data-target="#collapse{{ __('Atlet') }}Limit" aria-expanded="{{ $isLimitRequest ? 'true' : 'false' }}"
    aria-controls="collapse{{ __('Atlet') }}Limit">
    <i class="fas fa-fw fa-user-clock"></i>
    <span>Limit (/{{ __('Gaya') }}/Minggu)</span>
  </a>
  <div id="collapse{{ __('Atlet') }}Limit" class="collapse {{ $isLimitRequest ? 'show' : '' }}"
    aria-labelledby="headingPages" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item {{ $isLimitRequest && !request()->is('*member-limit/create') && !request()->is('*member-limit/create-batch')
          ? 'active'
          : '' }}"
        href="{{ route('dashboard.admin.member-limit.index') }}"><i class="fas fa-table"></i> Daftar Limit</a>
      <a class="collapse-item {{ request()->is('*member-limit/create') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.member-limit.create') }}"><i class="fas fa-plus"></i> Buat Limit</a>
      <a class="collapse-item {{ request()->is('*member-limit/create-batch') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.member-limit.create-batch') }}" data-toggle="tooltip"
        title="Tambah Batch {{ __('Atlet') }} Limit (/{{ __('Gaya') }}/Minggu)"><i class="fas fa-plus"></i> Buat
        Batch Limit</a>
    </div>
  </div>
</li>
