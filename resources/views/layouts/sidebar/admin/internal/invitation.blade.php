@php
  $isInvitationRequest = request()->is('*admin/member-invitation*');
@endphp
{{-- <a class="nav-link" href="#!"> --}}
{{-- <li class="nav-item {{ $isInvitationRequest ? 'active' : '' }}">
  <a class="nav-link" href="{{ route('dashboard.admin.member-invitation.index') }}">
    <i class="far fa-fw fa-envelope"></i>
    <span>{{ __('Requesting Invitation') }}</span>
    <small class="badge badge-light">{{ \App\Models\Invitation::where('registered_at', null)->count() }}</small>
  </a>
</li> --}}
<li class="nav-item {{ $isInvitationRequest ? 'active' : '' }}">
  <a class="nav-link {{ $isInvitationRequest ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
    data-target="#collapseInvitation" aria-expanded="{{ $isInvitationRequest ? 'true' : 'false' }}"
    aria-controls="collapseInvitation">
    <i class="fas fa-fw fa-envelope"></i>
    <span>{{ __('Requesting Invitation') }}</span>
    <small class="badge badge-light">{{ \App\Models\Invitation::where('registered_at', null)->count() }}</small>
  </a>
  <div id="collapseInvitation" class="collapse {{ $isInvitationRequest ? 'show' : '' }}" aria-labelledby="headingPages"
    data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item {{ $isInvitationRequest && !request()->filled('completed') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.member-invitation.index') }}"><i class="fas fa-table"></i>
        {{ __('Undangan') }}</a>
      <a class="collapse-item {{ $isInvitationRequest && request()->filled('completed') ? 'active' : '' }}"
        href="{{ route('dashboard.admin.member-invitation.index') }}?completed=1"><i class="fas fa-table"></i>
        {{ __('Undangan') }} (selesai)</a>
    </div>
  </div>
</li>
