@extends('auth.layouts.app')

@section('content')


<section class="pxp-hero vh-100" style="background-color: var(--pxpMainColorLight);">
    <div class="row align-items-center pxp-sign-hero-container">
        <div class="col-xl-6 pxp-column">
            <div class="pxp-sign-hero-fig text-center pb-80 pt-100">
                <img src="/new/images/signin-big-fig.png" alt="Sign In">
                <h1 class="mt-4">{{ __('Reset Password') }}</h1>
            </div>
        </div>
        <div class="col-xl-6 pxp-column pxp-is-light">
            <div class="pxp-sign-hero-form pb-80 pt-80">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-xl-7 col-xxl-5">
                        <div class="pxp-sign-hero-form-content">
                            <h5 class="text-center">{{ __('Reset Password') }}</h5>
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="form-floating mb-3">
                                    <input placeholder="{{ __('Email Address') }}" id="email" type="email" class="form-control @error('email')  @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                    <label for="email">{{ __('Email Address') }}</label>
                                    <span class="fa fa-envelope-o"></span>
                                </div>
                                @error('email')
                                    <p class="text-danger" role="alert">
                                        {{ $message }}</p>
                                    
                                @enderror
                                <div class="form-floating mb-3">
                                    <input placeholder="{{ __('Password') }}" id="password" type="password" class="form-control @error('password')  @enderror" name="password" required autocomplete="new-password">
                                    <label for="password">{{ __('Password') }}</label>
                                    <span class="fa fa-lock"></span>
                                </div>
                                @error('password')
                                    <p class="text-danger" role="alert">
                                       {{ $message }}</p>
                                    
                                @enderror
                                <div class="form-floating mb-3">
                                    <input placeholder="{{ __('Confirm Password') }}" id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                    <span class="fa fa-lock"></span>
                                </div>
                                

                                <button type="submit" class="btn rounded-pill btn-block pxp-sign-hero-form-cta"> {{ __('Reset Password') }}</button>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>





@endsection
