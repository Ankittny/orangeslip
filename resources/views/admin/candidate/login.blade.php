@extends('auth.layouts.app')

@section('content')


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
                                <form method="POST" class="mt-4" action="">
                                    @csrf
                                    <div class="form-floating mb-3">
                                        <input id="email" placeholder="Email address" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        <label for="email">Email address</label>
                                        <span class="fa fa-envelope-o"></span>
                                        <span id="email_error "></span>
                                    </div>
                                    <p class="error_email qerr"></p>
                                   {{-- 
                                    <!-- <div class="form-floating mb-3">
                                        <input id="phone" placeholder="Phone Number" type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('email') }}" required autocomplete="phone" autofocus>
                                        <label for="phone">Phone Number</label>
                                        
                                    </div>
                                    <p class="error_phone qerr"></p> -->
                                    
                                    --}}
                                    <button type="button" class="btn btn-success " id="btn1" onClick="showOtp()">Send OTP</button>
                                     
                                    <p class="statusMsg"></p>
                                    <div id="otpdiv"  style="display: none;">
                                    
                                    <div class="form-floating mb-3">
                                    
                                    
                                    <input id="otpnumber" placeholder="OTP" type="number" class="form-control @error('otp') is-invalid @enderror" name="otpnumber"  >
                                        <label for="otp">OTP</label>
                                        <p class="chkOtp qerr"></p>
                                        <p class="error_otpnumber qerr"></p>

                                    </div>
                                    

                                    <button type="button"  id="btn2" onClick="checkOtp()" class="btn rounded-pill btn-block pxp-sign-hero-form-cta">Continue</button>
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
  function showOtp(){
    $('.qerr').html('');
    $('.statusMsg').html('');
     
    var token='{{ csrf_token() }}';
    var email=$('#email').val();
    var phone=$('#phone').val();

    $.ajax({
        type:"POST",
        url:"{{url('send_otp')}}",
        data:'_token='+token+'&email='+email+'&phone='+phone,
        success:function(response)
        {
            console.log(response);
            if(response==1)
            {
                document.getElementById('otpdiv').style.display = 'block';
                document.getElementById('btn1').style.display = 'none';
                document.getElementById('email').disable = true;
                document.getElementById('phone').disable = true;
                 
                        $('.statusMsg').html('<span style="color:green;">OTP sent successfully to your Email .</p>');
                     
               
            }
            else if(response==33)
            {
                $('.statusMsg').html('<span style="color:green;">Already You Have an account! Please Login with Password.</span> Redirecting....');
                 
                    setTimeout(function() 
                    {
                        //location.reload();  //Refresh page
                        window.location.href='/login';
                    }, 5000);

            }
            else
            {
                $('.statusMsg').html('<span style="color:red;">Something was wrong .</p>');
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
     
  function checkOtp(){
    
    $('.qerr').html('');
    var token='{{ csrf_token() }}';
    var email=$('#email').val();
    var phone=$('#phone').val();
    var otpnumber=$('#otpnumber').val();

    $.ajax({
        type:"POST",
        url:"{{ route('candidate.LoginCheck') }}",
        data:'_token='+token+'&email='+email+'&phone='+phone+'&otpnumber='+otpnumber,
        success:function(response)
        {
            if(response==3)
            {
                
                window.location.href='home';
                     
                
            }
            if(response==4)
            {
                
                        $('.chkOtp').html('<span style="color:red;">Wrong OTP .</p>');
                     
                
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



 