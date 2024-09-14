@extends('auth.layouts.app')

@section('content')


<section class="mt-20 pxp-no-hero">
    <div class="row pxp-sign-hero-container">
        <div class="col-xl-6 pxp-column">
            <div class="pxp-sign-hero-fig text-center pb-40 pt-100">
                <img src="/new/images/signup-big-fig.png" alt="Sign Up">
                <h1 class="mt-3">Get Started Here!</h1>
                <div class="pxp-info-caption-cta mt-3">
                    <a href="{{route ('enrollCompanyView')}}" class="btn rounded-pill pxp-section-cta">Interest to join as Employer?<span class="fa fa-angle-right"></span></a>
                    
                </div>
            </div>
        </div>
        <div class="col-xl-6 pxp-column pxp-is-light">
            <div class="pxp-sign-hero-form pb-80 pt-80">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-xl-7 col-xxl-5">
                        <div class="pxp-sign-hero-form-content">
                            <h5 class="text-center">Candidate Sign Up</h5>
                            <form class="mt-4" method="POST" action="">
                                @csrf
                                <div class="form-floating mb-3">
                                <input id="name" placeholder="{{ __('Name') }}" type="text" class="form-control @error('name')  @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                    <label for="name">{{ __('Name') }}</label>
                                    <span class="fa fa-user"></span>
                                </div>
                                <p class="error_name qerr"></p>
                                @error('name')
                                    <p class="text-danger" role="alert">
                                         {{ $message }}</p>
                                     
                                @enderror

                                <div class="form-floating mb-3">
                                <input id="email" placeholder="{ __('Email Address') }}" type="email" class="form-control @error('email')  @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    <label for="email">{{ __('Email Address') }}</label>
                                    <span class="fa fa-envelope-o"></span>
                                </div>
                                <p class="error_email qerr"></p>
                                @error('email')
                                    <p class="text-danger" role="alert">
                                        {{ $message }}</p>
                                    
                                @enderror
                                <div class="form-floating mb-3">
                                    
                                <input id="phone" placeholder="{ __('Phone Number') }}" type="text" class="form-control @error('phone')  @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone">
                                    <label for="phone">{{ __('Phone Number') }}</label>
                                    <span class="fa fa-phone"></span>      
                                                                 
                                </div>
                                <p class="error_phone qerr"></p>

                                @error('phone')
                                    <p class="text-danger" role="alert">   {{ $message }}</p>
                                @enderror
                                <p class="otpStatus"></p>
                                <div class="form-floating mb-3" id="otpDiv" style="display:none;">
                                    
                                <input id="otp" placeholder="{ __('OTP') }}" type="text" class="form-control @error('otp')  @enderror" name="otp" value="{{ old('otp') }}" required autocomplete="otp">                                     
                                <label for="otp">{{ __('Enter OTP') }}</label>                 
                                </div>
                                <p class="error_otp qerr"></p>
                                @error('otp')
                                    <p class="text-danger" role="alert">   {{ $message }}</p>
                                @enderror
                                
                                <div class="form-floating mb-3">
                                <input id="password" placeholder="{{ __('Password') }}" type="password" class="form-control @error('password')  @enderror" name="password" required autocomplete="new-password">
                                    <label for="password">{{ __('Password') }}</label>
                                    <span class="fa fa-lock"></span>
                                    <div toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></div>
                                </div>
                                <p class="error_password qerr"></p>
                                @error('password')
                                    <p class="text-danger" role="alert">
                                         {{ $message }}</p>
                                     
                                @enderror

                                <div class="form-floating mb-3">
                                <input id="password_confirm" placeholder="{{ __('Confirm Password') }}" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                    <span class="fa fa-lock"></span>
                                </div>
                                <p id="cnf_pass_msg"></p>
{{--
                                <div class="form-floating mb-3">                                
                                    <button type="button"  id="sendOtp" class="btn rounded-pill pxp-nav-btn" onclick="showOtpDiv()">Send OTP</button>
                                     
                                </div>
                                --}}
                                <button type="button" class="btn rounded-pill btn-block pxp-sign-hero-form-cta" id="contBtn"  onclick="submitForm()" >Continue</button>
                                <p class="responseMsg"></p>
                                <div class="mt-3 text-center pxp-sign-hero-form-small">
                                    Already have an account? <a href="{{ route('login') }}"><strong>Log in</strong></a>
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
@push('script')
<script>
    function showOtpDiv(){
        $('.qerr').html('');
        $('.otpStatus').html('');
        var token='{{ csrf_token() }}';
        var phone=$('#phone').val();
         
        $.ajax({
        type:"POST",
        url:"{{ route('sendOtpPhone') }}",
        data:'_token='+token+'&phone='+phone,
            success:function(response)
            {
                if(response!='')
                {
                    document.getElementById('otpDiv').style.display="block";
                    document.getElementById('contBtn').style.display="block";
                    $('.otpStatus').html('<span style="color:green;">OTP Send Successfully to your phone Number('+response+') .</p>');
                }
                else
                { 
                    $('.otpStatus').html('<span style="color:red;">something was wrong! .</p>');
                }
                
            },
            error: function (reject) {
            
                    if( reject.status === 422 ) {
                        console.log(reject);
                        var resp = $.parseJSON(reject.responseText);
                        $.each(resp.errors, function (key, val) {
                            console.log(key,val);
                            $('.error_'+key).html(val[0]).css("color","red");
                            $( key ).text(val[0]);
                        });
                    }
                }
        });
    }

    function submitForm(){
        $('.qerr').html('');
        $('.otpStatus').html('');
        $('#cnf_pass_msg').html('');
        var token='{{ csrf_token() }}';
        var name=$('#name').val();
        var email=$('#email').val();
        var phone=$('#phone').val();
        var password=$('#password').val();         
        var password_confirmation=$('#password_confirm').val();         
        var otp=$('#otp').val();

         
     
  
 
        $.ajax({
        type:"POST",
        url:"{{ route('candidateSignup') }}",
        data:'_token='+token+'&name='+name+'&email='+email+'&phone='+phone+'&password='+password+'&otp='+otp+'&password_confirmation='+password_confirmation,
            success:function(response)
            {
                if(response==1)
                {
                    $('#sendOtp').hide();
                    $('#contBtn').hide();
                    $('.responseMsg').html('<span style="color:green;">Your account has been successfully registered. Please verify your email</span> Redirecting....');  
                        setTimeout(function() 
                        {
                            window.location = '/login';//Redirect page
                        }, 3000);    
                }
                else
                { 
                    $('.responseMsg').html('<span style="color:red;">Something was wrong!. </span> Redirecting....');  
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 3000);    
                }
                
            },
            error: function (reject) {
            
                    if( reject.status === 422 ) {
                        console.log(reject);
                        var resp = $.parseJSON(reject.responseText);
                        $.each(resp.errors, function (key, val) {
                            console.log(key,val);
                            $('.error_'+key).html(val[0]).css("color","red");
                            $( key ).text(val[0]);
                        });
                    }
                }
        });
         
    }



</script>
@endpush


