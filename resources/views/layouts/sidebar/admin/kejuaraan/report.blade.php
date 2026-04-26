@php
  $isReportRequest = request()->is('*kejuaraan/report*');
@endphp
<li class="nav-item {{ $isReportRequest ? 'active' : '' }}">
  <a class="nav-link {{ $isReportRequest ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
    data-target="#collapseKejuaraanReport" aria-expanded="{{ $isReportRequest ? 'true' : 'false' }}"
    aria-controls="collapseKejuaraanReport">
    <i class="fas fa-fw fa-chart-line"></i>
    <span>Rapor Kejuaraan</span>
  </a>
  <div id="collapseKejuaraanReport" class="collapse {{ $isReportRequest ? 'show' : '' }}" aria-labelledby="headingPages"
    data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item {{ $isReportRequest ? 'active' : '' }}"
        href="{{ route('dashboard.admin.kejuaraan.report.index') }}"><i class="fas fa-download fa-fw"></i> Download
        Rapor</a>
    </div>
  </div>
</li>
