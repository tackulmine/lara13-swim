@php
  $isChampionshipGayaRequest = request()->is('*kejuaraan/gaya*');
@endphp
<li class="nav-item {{ $isChampionshipGayaRequest ? 'active' : '' }}">
  <a class="nav-link {{ $isChampionshipGayaRequest ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
    data-target="#collapseGayaKejuaraan" aria-expanded="{{ $isChampionshipGayaRequest ? 'true' : 'false' }}"
    aria-controls="collapseGayaKejuaraan">
    <i class="fas fa-fw fa-swimming-pool"></i>
    <span>{{ __('Gaya') }}</span>
  </a>
  <div id="collapseGayaKejuaraan" class="collapse {{ $isChampionshipGayaRequest ? 'show' : '' }}"
    aria-labelledby="headingPages" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item {{ $isChampionshipGayaRequest && !request()->filled('trashed') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.kejuaraan.gaya.index') }}" href="#!"><i class="fas fa-table"></i>
        {{ __('Gaya') }} Aktif</a>
      <a class="collapse-item {{ $isChampionshipGayaRequest && request()->filled('trashed') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.kejuaraan.gaya.index') }}?trashed=1" href="#!"><i class="fas fa-table"></i>
        {{ __('Gaya') }} Non Aktif</a>
    </div>
  </div>
</li>
