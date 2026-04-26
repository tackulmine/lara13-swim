@php
  $isChampionshipEventRequest = request()->is('*kompetisi/event*');
@endphp
{{-- <a class="nav-link" href="#!"> --}}
<li class="nav-item {{ $isChampionshipEventRequest ? 'active' : '' }}">
  <a class="nav-link" href="{{ route('dashboard.user.kompetisi.event.index') }}">
    <i class="far fa-fw fa-calendar-alt"></i>
    <span>Kompetisi</span></a>
</li>
