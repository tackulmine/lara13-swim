@php
  $isMemberRequest =
      request()->is('*member*') &&
      (!request()->is('*member-gaya-limit*') &&
          !request()->is('*member-limit*') &&
          !request()->is('*member-report*') &&
          !request()->is('*member-invitation*'));
@endphp
<li class="nav-item {{ $isMemberRequest ? 'active' : '' }}">
  <a class="nav-link {{ $isMemberRequest ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
    data-target="#collapse{{ __('Atlet') }}" aria-expanded="{{ $isMemberRequest ? 'true' : 'false' }}"
    aria-controls="collapse{{ __('Atlet') }}">
    <i class="fas fa-fw fa-swimmer"></i>
    <span>{{ __('Atlet') }}</span>
  </a>
  <div id="collapse{{ __('Atlet') }}" class="collapse {{ $isMemberRequest ? 'show' : '' }}"
    aria-labelledby="headingPages" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      {{-- <h6 class="collapse-header">{{ __('Atlet') }}:</h6> --}}
      <a class="collapse-item {{ $isMemberRequest && !request()->is('*member/create') && !request()->filled('trashed')
          ? // && !request()->is('*member-gaya-limit*')
          // && !request()->is('*member-limit*')
          // && !request()->is('*member-report*')
          'active'
          : '' }}"
        href="{{ route('dashboard.admin.member.index') }}"><i class="fas fa-table"></i> Daftar {{ __('Atlet') }}
        Aktif</a>
      @canrole(['coach'])
      <a class="collapse-item {{ $isMemberRequest && !request()->is('*member/create') && request()->filled('trashed') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.member.index') }}?trashed=1"><i class="fas fa-table"></i> Daftar
        {{ __('Atlet') }}
        Non Aktif</a>
      <a class="collapse-item {{ request()->is('*member/create') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.member.create') }}"><i class="fas fa-plus"></i> Buat {{ __('Atlet') }}</a>
      @endcanrole
      {{-- <div class="collapse-divider"></div>
              <h6 class="collapse-header">Limit:</h6>
              <a class="collapse-item {{ request()->is('*member-gaya-limit*') ? 'active' : '' }}"
                href="{{ route('dashboard.admin.member-gaya-limit.index') }}"
                >Target (/{{ __('Gaya') }}/Bulan)</a>
              <a class="collapse-item {{ request()->is('*member-limit*') ? 'active' : '' }}"
                href="{{ route('dashboard.admin.member-limit.index') }}"
                >Limit (/{{ __('Gaya') }}/Minggu)</a>
              <a class="collapse-item {{ request()->is('*member-report*') ? 'active' : '' }}"
                href="{{ route('dashboard.admin.member-report.index') }}"
                >Rapor</a> --}}
    </div>
  </div>
</li>
