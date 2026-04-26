    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center"
        href="{{ !auth()->user()->isExternalUser() ? '/dashboard/admin' : '/dashboard/user' }}">
        <div class="sidebar-brand-icon rotate-n-15">
          {{-- <i class="fas fa-laugh-wink"></i> --}}
          <img src="/android-icon-36x36.png" alt="{{ config('app.name') }}">
        </div>
        <div class="sidebar-brand-text mx-3">{{ config('app.name', 'Laravel') }}</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item {{ request()->is('*dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ !auth()->user()->isExternalUser() ? '/dashboard/admin' : '/dashboard/user' }}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Beranda</span></a>
      </li>

      {{-- @include('layouts/sidebar/superuser/master') --}}

      <!-- Divider -->
      <hr class="sidebar-divider">

      @canrole(['coach', 'jury', 'external'])
      <!-- Heading -->
      <div class="sidebar-heading">
        DATA KOMPETISI
      </div>
      @include('layouts/sidebar/admin/kompetisi/event')
      @include('layouts/sidebar/admin/kompetisi/master')
      <!-- Divider -->
      <hr class="sidebar-divider">
      @endcanrole


      @canrole(['coach', 'member'])
      <!-- Heading -->
      <div class="sidebar-heading">
        DATA KEJUARAAN
      </div>
      @endcanrole

      @canrole(['coach'])
      @include('layouts/sidebar/admin/kejuaraan/event')
      @include('layouts/sidebar/admin/kejuaraan/gaya')
      @endcanrole

      @canrole(['coach', 'member'])
      @include('layouts/sidebar/admin/kejuaraan/report')
      <!-- Divider -->
      <hr class="sidebar-divider">
      @endcanrole

      @canrole(['coach'])
      <!-- Heading -->
      <div class="sidebar-heading">
        DATA EXTERNAL
      </div>
      @include('layouts/sidebar/admin/external/rank')
      <!-- Divider -->
      <hr class="sidebar-divider">
      @endcanrole


      @canrole(['coach', 'member'])
      <!-- Heading -->
      <div class="sidebar-heading">
        DATA INTERNAL
      </div>
      @endcanrole

      @canrole(['coach'])
      @include('layouts/sidebar/admin/internal/staff')
      {{-- @include('layouts/sidebar/admin/internal/gaya') --}}
      {{-- @include('layouts/sidebar/admin/internal/class') --}}
      @endcanrole

      @canrole(['coach', 'member'])
      @include('layouts/sidebar/admin/internal/member')
      @endcanrole

      {{-- @canrole(['coach'])
      @include('layouts/sidebar/admin/internal/target')
      @include('layouts/sidebar/admin/internal/limit')
      @endcanrole

      @canrole(['coach', 'member'])
      @include('layouts/sidebar/admin/internal/report')
      @endcanrole --}}

      @canrole(['coach'])
      @include('layouts/sidebar/admin/internal/invitation')
      @endcanrole

      @canrole(['coach', 'member'])
      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">
      @endcanrole

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->
