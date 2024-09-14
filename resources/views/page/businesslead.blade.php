<!doctype html>
<html lang="en" class="pxp-root">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

@include('layouts.htmlheader')

    <body>
   @foreach($errors->all() as $err)
   <p>{{$err}}</p>
   @endforeach
    @include('layouts.homeheader')
    @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}")</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}");</script> 
        @endif
        <section class="mt-60 pxp-no-hero">
            <div class="pxp-container">
                <h2 class="pxp-section-h2 text-center">Enroll Your Company</h2>
                <p class="pxp-text-light text-center">Get in touch with us</p>

                

                <div class="row mt-60 justify-content-center pxp-animate-in pxp-animate-in-top">
                    <div class="col-lg-6 col-xxl-6">
                        <div class="pxp-contact-us-form pxp-has-animation pxp-animate">
                             
                            <form class="mt-4" action="{{route('enrollCompanyStore')}}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact-us-name" class="form-label">Business Name * </label>
                                            <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Enter your business name" value="{{ old('business_name')}}" required>
                                            @if ($errors->has('business_name'))
                                                <label class="text-danger">{{ $errors->first('business_name') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label  class="form-label">Business Email *</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter business email " value="{{ old('email')}}" required>
                                            @if ($errors->has('email'))
                                                <label class="text-danger">{{ $errors->first('email') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label  class="form-label">Contact Person First Name *</label>
                                            <input type="text" class="form-control" id="owner_first_name" name="owner_first_name" placeholder="Enter your First Name" value="{{ old('owner_first_name')}}" required>
                                            @if ($errors->has('owner_first_name'))
                                                <label class="text-danger">{{ $errors->first('owner_first_name') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label  class="form-label">Contact Person Last Name *</label>
                                            <input type="text" class="form-control" id="owner_last_name" name="owner_last_name" placeholder="Enter your Last Name" value="{{ old('owner_last_name')}}" required>
                                            @if ($errors->has('owner_last_name'))
                                                <label class="text-danger">{{ $errors->first('owner_last_name') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label  class="form-label">Country *</label>
                                            <select name="country" id="country"  required>       
                                                                                              
                                                    @foreach($country as $c)
                                                    <option value="{{$c->id}}" @if (old('country')) {{ old('country')== $c->id ? 'selected' : ''}} @else  {{$c->id=='69' ? 'selected' : ''}} @endif >{{$c->name}} (+{{$c->calling_code}})</option>
                                                    @endforeach                                            
                                                </select>                                               
                                                
                                               
                                             
                                            @if ($errors->has('country'))
                                                <label class="text-danger">{{ $errors->first('country') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label  class="form-label">Mobile Number *</label>
                                            
                                                
                                                <input type="number" class="form-control" id="mobile_no" name="mobile_no" placeholder="Enter your Mobile Number" value="{{ old('mobile_no')}}" required>
                                             
                                            @if ($errors->has('mobile_no'))
                                                <label class="text-danger">{{ $errors->first('mobile_no') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                     
                                </div>
                                <div class="row">
                                     
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label  class="form-label">Number Of Employees *</label>
                                             
                                            <select id="no_of_employee" name="no_of_employee"   required>
                                                <option>Select</option>
                                                @foreach($allRange as $range)
                                                <option value="{{$range->id}}" {{ $range->id==old('no_of_employee')?"selected" :""}}>{{$range->range_start}} - {{$range->range_end}}</option>
                                                @endforeach
                                            </select>
                                        </div>     
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label  class="form-label">GSTIN No. *</label>       
                                            <input type="text" class="form-control gst" id="gst" name="gst" style="text-transform: uppercase"  value="{{ old('gst')}}">
                                        </div>
                                        <p class="gstErr"></p>
                                        @if ($errors->has('gst'))
                                                <label class="text-danger">{{ $errors->first('gst') }}</label>
                                            @endif
                                    </div>
                                    
                                   
                                </div>
                                <div class="row"> 
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label  class="form-label">PAN NO. *</label>
                                            <input type="text" class="form-control pan" id="pan" name="pan" style="text-transform: uppercase" value="{{ old('pan')}}">
                                        </div>
                                        <p class="panErr"></p>
                                        @if ($errors->has('pan'))
                                                <label class="text-danger">{{ $errors->first('pan') }}</label>
                                            @endif
                                    </div>                                   
                                 
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                        <label  class="form-label">Reference Code</label>                                            
                                                
                                            <input type="text" class="form-control" id="ref_code" name="ref_code"  value="{{$refCode ? $refCode : old('ref_code')}}">
                                    </div>
                                </div>
                                <div class="row">                                    
                                    {{--<div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="g-recaptcha mb-3" data-sitekey="6LcUOqMkAAAAALE6pxiz3fbQ1eHJQ2GRiY_a9vLo"></div>
                                            @if ($errors->has('g-recaptcha-response'))
                                                    <label class="text-danger">{{ $errors->first('g-recaptcha-response') }}</label>
                                                @endif
                                        </div>     
                                    </div>--}}

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <button type="submit" class="btn rounded-pill btn-block pxp-sign-hero-form-cta">Submit</button>
                                        </div>     
                                    </div>
                                </div>
                                
                                
                               
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
         
 

        @include('layouts.homefooter')


        @include('layouts.script')
<script>
    $(document).ready(function(){     
        
        $(".pan").change(function () {      
        var inputvalues = $(this).val();      
          var regex = /[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}$/;   
        //   var regex = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/;  
          if(!regex.test(inputvalues)){  
            // alert("invalid PAN no");   
        $('.panErr').html('<span style="color:red;">Please Enter Valid PAN Number.</span>');     
          $(".pan").val("");    
          $(".pan").focus(); 
         
        //   return regex.test(inputvalues);    
          }  
          else{
            return true;
          }  
        });      
            
        });    
    $(document).ready(function(){     
        
        $(".gst").change(function () {    
                var inputvalues = $(this).val();    
                var gstinformat = new RegExp('^[0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}[1-9A-Za-z]{1}[1-9A-Za-z]{1}[1-9A-Za-z]{1}$');    
                if (gstinformat.test(inputvalues)) {    
                    return true;    
                } else {    
                    // alert('Please Enter Valid GSTIN Number');    
                    // $(".gsterr").val('Please Enter Valid GSTIN Number');    
                    $('.gstErr').html('<span style="color:red;">Please Enter Valid GSTIN Number.</span>');
                    $(".gst").val('');    
                    $(".gst").focus();    
                }    
            });          

            
        });    
        
</script>
    </body>  
        

</html>