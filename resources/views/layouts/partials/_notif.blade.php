@if ($errors->any())
  <div class="alert alert-danger">
    <p class="m-0"><strong>Oops!</strong> Tolong diperbaiki:</p>
    <ul class="m-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
@if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif
@if (session('message'))
  <div class="alert alert-info">
    {{ session('message') }}
  </div>
@endif
