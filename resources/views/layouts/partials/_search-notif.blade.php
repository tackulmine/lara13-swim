@if (session('search_error'))
  <div class="alert alert-danger">
    {{ session('search_error') }}
  </div>
@endif
@if (session('search_success'))
  <div class="alert alert-success">
    {{ session('search_success') }}
  </div>
@endif
