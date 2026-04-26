@php
  $isChampionshipEventRequest = request()->is('*kejuaraan/event*');
@endphp
{{-- <a class="nav-link" href="#!"> --}}
{{-- <li class="nav-item {{ $isChampionshipEventRequest ? 'active' : '' }}">
  <a class="nav-link" href="{{ route('dashboard.admin.kejuaraan.event.index') }}">
    <i class="far fa-fw fa-calendar-alt"></i>
    <span>Kejuaraan</span></a>
</li> --}}
<li class="nav-item {{ $isChampionshipEventRequest ? 'active' : '' }}">
  <a class="nav-link {{ $isChampionshipEventRequest ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
    data-target="#collapseEventKejuaraan" aria-expanded="{{ $isChampionshipEventRequest ? 'true' : 'false' }}"
    aria-controls="collapseEventKejuaraan">
    <i class="fas fa-fw fa-calendar-alt"></i>
    <span>{{ __('Kejuaraan') }}</span>
  </a>
  <div id="collapseEventKejuaraan" class="collapse {{ $isChampionshipEventRequest ? 'show' : '' }}"
    aria-labelledby="headingPages" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item {{ $isChampionshipEventRequest && !request()->filled('trashed') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.kejuaraan.event.index') }}" href="#!"><i class="fas fa-table"></i>
        {{ __('Kejuaraan') }} Aktif</a>
      <a class="collapse-item {{ $isChampionshipEventRequest && request()->filled('trashed') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.kejuaraan.event.index') }}?trashed=1" href="#!"><i
          class="fas fa-table"></i>
        {{ __('Kejuaraan') }} Non Aktif</a>
    </div>
  </div>
</li>
