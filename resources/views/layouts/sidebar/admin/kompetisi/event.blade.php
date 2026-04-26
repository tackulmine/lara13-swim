@php
  $isCompetitionEventRequest = request()->is('*event*') && !request()->is('*kejuaraan/event*');
@endphp
{{-- <li class="nav-item {{ $isCompetitionEventRequest ? 'active' : '' }}">
  <a class="nav-link" href="{{ route('dashboard.admin.event.index') }}">
    <i class="far fa-fw fa-calendar-alt"></i>
    <span>Kompetisi</span></a>
</li> --}}
<li class="nav-item {{ $isCompetitionEventRequest ? 'active' : '' }}">
  <a class="nav-link {{ $isCompetitionEventRequest ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
    data-target="#collapseEventKompetisi" aria-expanded="{{ $isCompetitionEventRequest ? 'true' : 'false' }}"
    aria-controls="collapseEventKompetisi">
    <i class="fas fa-fw fa-calendar-alt"></i>
    <span>{{ __('Kompetisi') }}</span>
  </a>
  <div id="collapseEventKompetisi" class="collapse {{ $isCompetitionEventRequest ? 'show' : '' }}"
    aria-labelledby="headingPages" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item {{ $isCompetitionEventRequest && !request()->filled('trashed') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.event.index') }}" href="#!"><i class="fas fa-table"></i>
        {{ __('Kompetisi') }} Aktif</a>
      <a class="collapse-item {{ $isCompetitionEventRequest && request()->filled('trashed') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.event.index') }}?trashed=1" href="#!"><i class="fas fa-table"></i>
        {{ __('Kompetisi') }} Non Aktif</a>
    </div>
  </div>
</li>
