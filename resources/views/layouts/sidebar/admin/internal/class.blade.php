@php
  $isClassRequest = request()->is('*class*');
@endphp
<li class="nav-item {{ $isClassRequest ? 'active' : '' }}">
  <a class="nav-link {{ $isClassRequest ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
    data-target="#collapseClass" aria-expanded="{{ $isClassRequest ? 'true' : 'false' }}" aria-controls="collapseClass">
    <i class="fas fa-fw fa-building"></i>
    <span>Kelas</span>
  </a>
  <div id="collapseClass" class="collapse {{ $isClassRequest ? 'show' : '' }}" aria-labelledby="headingPages"
    data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item {{ $isClassRequest && !request()->is('*class/create') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.class.index') }}"><i class="fas fa-table"></i> Daftar Kelas</a>
      <a class="collapse-item {{ request()->is('*class/create') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.class.create') }}"><i class="fas fa-plus"></i> Buat Kelas</a>
    </div>
  </div>
</li>
