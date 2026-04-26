@php
  $isReportRequest = request()->is('*member-report*');
@endphp
<li class="nav-item {{ $isReportRequest ? 'active' : '' }}">
  <a class="nav-link {{ $isReportRequest ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
    data-target="#collapse{{ __('Atlet') }}Report" aria-expanded="{{ $isReportRequest ? 'true' : 'false' }}"
    aria-controls="collapse{{ __('Atlet') }}Report">
    <i class="fas fa-fw fa-chart-line"></i>
    <span>Rapor {{ __('Atlet') }}</span>
  </a>
  <div id="collapse{{ __('Atlet') }}Report" class="collapse {{ $isReportRequest ? 'show' : '' }}"
    aria-labelledby="headingPages" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item {{ $isReportRequest ? 'active' : '' }}"
        href="{{ route('dashboard.admin.member-report.index') }}"><i class="fas fa-print"></i> Cetak Rapor</a>
    </div>
  </div>
</li>
