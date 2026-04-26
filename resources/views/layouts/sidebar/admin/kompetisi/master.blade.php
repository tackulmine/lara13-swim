<li class="nav-item {{ request()->is('*master*') ? 'active' : '' }}">
  <a class="nav-link {{ request()->is('*master*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
    data-target="#collapseMaster" aria-expanded="{{ request()->is('*master*') ? 'true' : 'false' }}"
    aria-controls="collapseMaster">
    <i class="fas fa-fw fa-cogs"></i>
    <span>Master</span>
  </a>
  <div id="collapseMaster" class="collapse {{ request()->is('*master*') ? 'show' : '' }}" aria-labelledby="headingPages"
    data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item {{ request()->is('*master/type*') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.master.type.index') }}">{{ __('Gaya') }}</a>
      <a class="collapse-item {{ request()->is('*master/category*') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.master.category.index') }}">{{ __('Kategori') }}</a>
      <a class="collapse-item {{ request()->is('*master/school*') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.master.school.index') }}">{{ __('Sekolah') }}</a>
      <a class="collapse-item {{ request()->is('*master/participant*') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.master.participant.index') }}">Peserta</a>
    </div>
  </div>
</li>
