@extends('layouts.auth')

@section('content')
  <!-- Outer Row -->
  <div class="row justify-content-center">

    <div class="col-xl-10 col-lg-12 col-md-9">

      <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
          <!-- Nested Row within Card Body -->
          <div class="row">
            <div class="col-lg-6 d-none d-lg-block bg-login-image"
              style="background-image: url({{ asset('assets/front/images/arisa-chattasa-AZcNLJgO4XE-unsplash.jpg') }});">
            </div>
            <div class="col-lg-6">
              <div class="p-5">
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-4">{{ __('Login') }}</h1>
                </div>
                <form class="user" method="POST" action="{{ route('login') }}">
                  @csrf
                  <div class="form-group">
                    <input id="login" type="text"
                      class="form-control form-control-user{{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}"
                      name="login" placeholder="{{ __('Username or Email') }}"
                      value="{{ old('username') ?: old('email') }}" required autofocus>
                    @if ($errors->has('username') || $errors->has('email'))
                      <span class="invalid-feedback">
                        <strong>{{ $errors->first('username') ?: $errors->first('email') }}</strong>
                      </span>
                    @endif
                  </div>
                  <div class="form-group">
                    <input id="password" type="password"
                      class="form-control form-control-user @error('password') is-invalid @enderror" name="password"
                      placeholder="{{ __('Password') }}" required autocomplete="current-password">

                    @error('password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <div class="custom-control custom-checkbox small">
                      <input class="custom-control-input" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                      <label class="custom-control-label" for="remember">
                        {{ __('Remember Me') }}
                      </label>
                    </div>
                  </div>
                  {{-- <a href="index.html" class="btn btn-primary btn-user btn-block">
                Login
              </a> --}}
                  <button type="submit" class="btn btn-primary btn-user btn-block">
                    {{ __('Login') }}
                  </button>

                  {{-- <hr>
              <a href="index.html" class="btn btn-google btn-user btn-block">
                <i class="fab fa-google fa-fw"></i> Login with Google
              </a>
              <a href="index.html" class="btn btn-facebook btn-user btn-block">
                <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
              </a> --}}
                </form>

                @if (Route::has('password.request') or Route::has('register'))
                  <hr>
                  @if (Route::has('password.request'))
                    <div class="text-center">
                      <a class="small" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                      </a>
                    </div>
                  @endif
                  @if (Route::has('register'))
                    <div class="text-center">
                      <a class="small" href="{{ route('register') }}">
                        {{ __('Create an Account!') }}
                      </a>
                    </div>
                  @endif
                @endif

              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
@endsection
