@extends('auth.layouts.app')

@section('content')


<section class="pxp-hero vh-100" style="background-color: var(--pxpMainColorLight);">
    <div class="row align-items-center pxp-sign-hero-container">
        <div class="col-xl-6 pxp-column">
            <div class="pxp-sign-hero-fig text-center pb-80 pt-100">
                <img src="/new/images/signin-big-fig.png" alt="">
                <h1 class="mt-4">{{ __('Reset Password') }}</h1>
            </div>
        </div>
        <div class="col-xl-6 pxp-column pxp-is-light">
            <div class="pxp-sign-hero-form pb-80 pt-80">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-xl-7 col-xxl-5">
                        <div class="pxp-sign-hero-form-content">
                            <h5 class="text-center">{{ __('Reset Password') }}</h5>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form class="mt-4" method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <div class="form-floating mb-3">
                                    <input id="email" placeholder="{{ __('Email Address') }}" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    <label for="email">{{ __('Email Address') }}</label>
                                    <span class="fa fa-envelope-o"></span>
                                </div>
                                @error('email')
                                     
                                    <label class="text-danger">{{ $message }}</label>
                                @enderror
                                
                                <button type="submit" class="btn rounded-pill btn-block pxp-sign-hero-form-cta">{{ __('Send Password Reset Link') }}</button>
                                
                                <div class="mt-3 text-center pxp-sign-hero-form-small">
                                    Go back to <a href="{{ route('login') }}"><strong>Login page</strong></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
