@php
  $isGayaRequest =
      request()->is('*gaya*') && (!request()->is('*member-gaya-limit*') && !request()->is('*kejuaraan/gaya*'));
@endphp
<li class="nav-item {{ $isGayaRequest ? 'active' : '' }}">
  <a class="nav-link {{ $isGayaRequest ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
    data-target="#collapseGaya" aria-expanded="{{ $isGayaRequest ? 'true' : 'false' }}" aria-controls="collapseGaya">
    <i class="fas fa-fw fa-swimming-pool"></i>
    <span>{{ __('Gaya') }}</span>
  </a>
  <div id="collapseGaya" class="collapse {{ $isGayaRequest ? 'show' : '' }}" aria-labelledby="headingPages"
    data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item {{ $isGayaRequest && !request()->is('*gaya/create') && !request()->filled('trashed') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.gaya.index') }}"><i class="fas fa-table"></i> Daftar {{ __('Gaya') }}
        Aktif</a>
      <a class="collapse-item {{ $isGayaRequest && !request()->is('*gaya/create') && request()->filled('trashed') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.gaya.index') }}?trashed=1"><i class="fas fa-table"></i> Daftar
        {{ __('Gaya') }} Non Aktif</a>
      <a class="collapse-item {{ request()->is('*gaya/create') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.gaya.create') }}"><i class="fas fa-plus"></i> Buat {{ __('Gaya') }}</a>
    </div>
  </div>
</li>
