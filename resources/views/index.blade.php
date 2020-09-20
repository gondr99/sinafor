@extends('layout/master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12 d-flex justify-content-center align-items-center ">
                    <div class="logo-div">
                        <img src="/image/logo.png" alt="logo" class="logo-image">
                    </div>
                </div>
            </div> {{-- row --}}

            <div class="row">
                <div class="col-sm-10 offset-sm-1 col-md-4 offset-md-4">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="/user/login">
                                @csrf
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('register.email') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('register.password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('register.remember') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('menu.login') }}
                                        </button>

                                        <a href="/user/register" class="btn btn-success">
                                            {{ __('menu.register') }}
                                        </a>

                                        <a class="btn btn-link" href="/user/forget">
                                            {{ __('register.forget') }}
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>{{-- row --}}

        </div>
    </div>
@endsection
