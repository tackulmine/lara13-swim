{{-- Page Heading & Breadcrumbs --}}
<div class="d-sm-flex align-items-center justify-content-between mb-2">
  <h1 class="h3 mb-0 text-gray-800">{{ $moduleName }}</h1>
  {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
      @if (!empty($breadcrumbs))
        @foreach ($breadcrumbs as $blink => $bname)
          @if (empty($blink))
            <li class="breadcrumb-item active" aria-current="page">{{ $bname }}</li>
          @else
            <li class="breadcrumb-item"><a href="{{ $blink }}">{{ $bname }}</a></li>
          @endif
        @endforeach
      @endif
    </ol>
  </nav>
</div>
