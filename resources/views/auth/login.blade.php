@extends('auth.layouts.app')

@section('content')
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>

                                    @if(session('success'))                                     
                                        <script type="text/javascript">toastr.success("{{session('success')}}")</script> 
                                    @endif
                                    @if(session('error'))                                
                                        <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
                                    @endif
    <section class="pxp-hero vh-100" style="background-color: var(--pxpMainColorLight);">
        <div class="row align-items-center pxp-sign-hero-container">
            <div class="col-xl-6 pxp-column">
                <div class="pxp-sign-hero-fig text-center pb-80 pt-100">
                    <img src="/new/images/signin-big-fig.png" alt="Sign In">
                    <h1 class="mt-4">Simply Get Hired!</h1>
                </div>
            </div>
            <div class="col-xl-6 pxp-column pxp-is-light">
                <div class="pxp-sign-hero-form pb-80 pt-80">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-xl-7 col-xxl-5">
                            <div class="pxp-sign-hero-form-content">
                                <h5 class="text-center">Log In</h5>
                                <form method="POST" class="mt-4" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-floating mb-3">
                                        <input id="email" placeholder="Email address" type="email" class="form-control @error('email')  @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        <label for="email">Email address</label>
                                        <span class="fa fa-envelope-o"></span>
                                    </div>
                                    @error('email')
                                        <p class="text-danger" role="alert">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                    <div class="form-floating mb-3">
                                    <input id="password" placeholder="Password" type="password" class="form-control @error('password')  @enderror" name="password" required autocomplete="current-password">
                                        <label for="password">Password</label>
                                        <span class="fa fa-lock"></span>
                                        <div toggle="#password" class="fa fa-fw fa-eye  toggle-password"></div>
                                    </div>
                                    @error('password')
                                        <p class="text-danger" role="alert">
                                            {{ $message }}</p>
                                        
                                    @enderror

                                    <button type="submit" class="btn rounded-pill btn-block pxp-sign-hero-form-cta" id="loginBtn">Continue</button>
                                    
                                    <div class="mt-4 text-center pxp-sign-hero-form-small">
                                        @if (Route::has('password.request'))
                                            <a class="pxp-modal-link" href="{{ route('password.request') }}">
                                            <strong>Forgot password</strong>
                                            </a>
                                        @endif
                                     </div>
                                    <div class="mt-3 text-center pxp-sign-hero-form-small">
                                        New to Recrueet? <a href="{{ route('register') }}"><strong>Create an account</strong></a>
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



