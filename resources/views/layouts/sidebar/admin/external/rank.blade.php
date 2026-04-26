@php
  $isExternalRankRequest = request()->is('*external/rank*');
@endphp
{{-- <a class="nav-link" href="#!"> --}}
<li class="nav-item {{ $isExternalRankRequest ? 'active' : '' }}">
  <a class="nav-link" href="{{ route('dashboard.admin.external.rank.index') }}">
    <i class="far fa-fw fa-calendar-alt"></i>
    <span>BestTime (Rank)</span></a>
</li>
