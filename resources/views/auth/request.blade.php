@extends('layouts.auth')

@section('content')
  <div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
      <!-- Nested Row within Card Body -->
      <div class="row">
        <div class="col-lg-5 d-none d-lg-block bg-register-image"
          style="background-image: url({{ asset('assets/front/images/arisa-chattasa-AZcNLJgO4XE-unsplash.jpg') }});"></div>
        <div class="col-lg-7">
          <div class="p-5">

            @if (session('error'))
              <div class="alert alert-danger">
                <p>{{ session('error') }}</p>
              </div>
            @endif

            @if (session('success'))
              <div class="alert alert-success">
                <p>{{ session('success') }}</p>
              </div>
            @endif

            <div class="text-center">
              <h1 class="h4 text-gray-900 mb-4">{{ __('Requesting Invitation') }}</h1>
            </div>

            <p>{{ config('app.name') }}
              {{ __('is a closed community. You must have an invitation link to register. You can request your link below.') }}
            </p>

            <form class="user" method="POST" action="{{ route('storeInvitation') }}">
              {{ csrf_field() }}

              <div class="form-group">
                <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror"
                  id="email" placeholder="{{ __('E-Mail Address') }}" name="email"
                  value="{{ old('email', $email ?? '') }}" required autocomplete="email">

                @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>

              <div class="text-center">
                <button type="submit" class="btn btn-primary btn-user btn-block">
                  {{ __('Request an Invitation') }}
                </button>
              </div>
            </form>

            @if (Route::has('login'))
              <hr>

              <div class="text-center">
                <a class="small" href="{{ route('login') }}">
                  {{ __('Already have an Account? Login!') }}
                </a>
              </div>
            @endif

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
