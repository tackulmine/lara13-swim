@php
  $isStaffRequest = request()->is('*staff*');
@endphp
<li class="nav-item {{ $isStaffRequest ? 'active' : '' }}">
  <a class="nav-link {{ $isStaffRequest ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
    data-target="#collapseStaff" aria-expanded="{{ $isStaffRequest ? 'true' : 'false' }}" aria-controls="collapseStaff">
    <i class="fas fa-fw fa-user-cog"></i>
    <span>Staff</span>
  </a>
  <div id="collapseStaff" class="collapse {{ $isStaffRequest ? 'show' : '' }}" aria-labelledby="headingPages"
    data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item {{ $isStaffRequest && !request()->is('*staff/create') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.staff.index') }}"><i class="fas fa-table"></i> Daftar Staff</a>
      <a class="collapse-item {{ request()->is('*staff/create') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.staff.create') }}"><i class="fas fa-plus"></i> Buat Staff</a>
    </div>
  </div>
</li>
