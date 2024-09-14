@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}");</script> 
        @endif
<div class="pxp-dashboard-content-details" >

    <div class="d-flex justify-content-between">
        <h4 class="text-themecolor">Personal Details ({{$candidate->candidate_code}})</h4>
        @if(Auth::user()->account_type=='candidate')
        <a href="{{url('candidate_profile')}}"><i class="fa fa-long-arrow-left"></i> Back</a>
        @else
        <a href="{{url('edit_candidate')}}/{{base64_encode($candidate->id)}}"><i class="fa fa-long-arrow-left"></i> Back</a>
        @endif
    </div>


       

        <form method="post" action="{{url('basicdetails')}}/{{base64_encode($candidate->id)}}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Name *</label>
                        <input type="text" id="cname" name="cname" class="form-control" value="{{old('cname',$candidate->name)}}" required>
                    </div>
                    @if($errors->has('cname'))
                        <label class="text-danger">{{ $errors->first('cname') }}</label>
                    @endif
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Gender * </label>
                        <select  id="gender" name="gender"  required>
                            <option value='' selected>Select</option>
                            <option value='male' {{old('gender',$candidate->gender)=='male'? "selected":""}}>MALE</option>
                            <option value='female' {{old('gender',$candidate->gender)=='female'? "selected":""}}>FEMALE</option>
                            <option value='other' {{old('gender',$candidate->gender)=='other'? "selected":""}}>OTHER</option>
                        </select>
                    </div>
                    @if($errors->has('gender'))
                        <label class="text-danger">{{ $errors->first('gender') }}</label>
                    @endif
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">DOB *</label>
                        <input type="date"  id="dob" name="dob" class="form-control"  value="{{old('dob',$candidate->dob)}}" required>
                    </div>
                    @if($errors->has('dob'))
                        <label class="text-danger">{{ $errors->first('dob') }}</label>
                    @endif
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Religion</label>
                        <input type="text" id="religion" name="religion" class="form-control" value="{{old('religion',$candidate->religion)}}">
                    </div>
                </div>
                
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Country *</label>
                        <select  id="country" name="country"  required>
                        @foreach($country as $c)
                            <option value='{{$c->id}}' {{$candidate->country==$c->id?"selected":""}}>{{$c->name}} (+{{$c->calling_code}})</option>       
                        @endforeach                              
                        </select>
                    </div>
                    @if($errors->has('country'))
                        <label class="text-danger">{{ $errors->first('country') }}</label>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">State </label>
                        <select  id="state" name="state"   onchange="getCity(this.value)" >
                            <option value='' selected>Select state</option>
                            @foreach($states as $s)
                            <option value='{{$s->state_id}}' {{$candidate->state==$s->state_id?"selected":""}}>{{$s->state_title}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($errors->has('state'))
                        <label class="text-danger">{{ $errors->first('state') }}</label>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">City </label>
                        <select  id="city" name="city" >
                            <option value='' selected>Select city</option>  
                            @foreach($cities as $city)
                            <option value='{{$city->id}}' {{$candidate->city==$city->id?"selected":""}}>{{$city->name}}</option>
                            @endforeach                                
                        </select>
                    </div>
                    @if($errors->has('city'))
                        <label class="text-danger">{{ $errors->first('city') }}</label>
                    @endif
                </div>
                
                
                
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Address</label>
                        <textarea  id="present_address" name="present_address" class="form-control" row="3" placeholder="Enter Address">{{old('present_address',$candidate->present_address)}}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Email *</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{old('email',$candidate->email)}}" required>
                    </div>
                    @if($errors->has('email'))
                        <label class="text-danger">{{ $errors->first('email') }}</label>
                    @endif
                </div>
            
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Phone *</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{old('phone',$candidate->phone)}}" required>
                    </div>
                    @if($errors->has('phone'))
                        <label class="text-danger">{{ $errors->first('phone') }}</label>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Alternate Phone </label>
                        <input type="text" id="phone2" name="phone2" class="form-control" value="{{old('phone2',$candidate->phone2)}}" >
                    </div>
                    @if($errors->has('phone2'))
                        <label class="text-danger">{{ $errors->first('phone2') }}</label>
                    @endif
                </div> 
                    
                
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label" >PAN No.</label>
                        <input type="text" id="pan_no" name="pan_no" class="form-control pan" value="{{old('pan_no',$candidate->pan_no)}}" placeholder="e.g.=ABCDE1234Z">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label" >PAN File (jpg/jpeg/png)</label>
                        <input type="file" id="pan_file" name="pan_file" class="form-control" >
                    </div>
                    @if($errors->has('pan_file'))
                        <label class="text-danger">{{ $errors->first('pan_file') }}</label>
                    @endif
                    <input type="hidden" id="pan_old" name="pan_old" class="form-control" value="{{$candidate->pan_file}}">

                   @if($candidate->pan_file!='')
                        <a href="{{ (url('images/'.$candidate->pan_file))}}" class="text-danger">Download</a>
                    @endif
                    
                     
                </div>             

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Aadhaar No.</label>
                        <input type="text" id="aadhaar_no" name="aadhaar_no" class="form-control aadhaar" value="{{old('aadhaar_no',$candidate->aadhaar_no)}}" placeholder="e.g.=123456789010">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label" >Aadhaar File (jpg/jpeg/png)</label>
                        <input type="file" id="aadhaar_file" name="aadhaar_file" class="form-control" value="{{$candidate->aadhaar_file}}">
                        <input type="hidden" id="aadhaar_old" name="aadhaar_old" class="form-control" value="{{$candidate->aadhaar_file}}">
                    </div>
                    @if($errors->has('aadhaar_file'))
                        <label class="text-danger">{{ $errors->first('aadhaar_file') }}</label>
                    @endif
                    @if($candidate->aadhaar_file!='')
                        <a href="{{ (url('images/'.$candidate->aadhaar_file))}}" class="text-danger">Download</a>                        
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Passport No.</label>
                        <input type="text" id="passport_no" name="passport_no" class="form-control passport" value="{{old('passport_no',$candidate->passport_no)}}" placeholder="e.g.=J12345678">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Passport Expiry Date.</label>
                        <input type="date" id="passport_exp_date" name="passport_exp_date" class="form-control" value="{{$candidate->passport_exp_date}}">
                    </div>
                    @if($errors->has('passport_exp_date'))
                        <label class="text-danger">{{ $errors->first('passport_exp_date') }}</label>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label" >Passport File (jpg/jpeg/png)</label>
                        <input type="file" id="passport_file" name="passport_file" class="form-control" >
                        <input type="hidden" id="passport_old" name="passport_old" class="form-control" value="{{$candidate->passport_file}}">
                    </div>
                    @if($errors->has('passport_file'))
                        <label class="text-danger">{{ $errors->first('passport_file') }}</label>
                    @endif
                    @if($candidate->passport_file!='')
                        <a href="{{ (url('images/'.$candidate->passport_file))}}" class="text-danger">Download</a>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Driving Licence No.</label>
                        <input type="text" id="dl_no" name="dl_no" class="form-control dl" value="{{old('dl_no',$candidate->dl_no)}}" placeholder="e.g.=DL12 20121234567">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Driving Licence Expiry Date.</label>
                        <input type="date" id="dl_exp_date" name="dl_exp_date" class="form-control" value="{{$candidate->dl_exp_date}}">
                    </div>
                    @if($errors->has('dl_exp_date'))
                        <label class="text-danger">{{ $errors->first('dl_exp_date') }}</label>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label" >Driving Licence File (jpg/jpeg/png)</label>
                        <input type="file" id="dl_file" name="dl_file" class="form-control" >
                        <input type="hidden" id="dl_old" name="dl_old" class="form-control" value="{{$candidate->dl_file}}">
                    </div>
                    @if($errors->has('dl_file'))
                        <label class="text-danger">{{ $errors->first('dl_file') }}</label>
                    @endif
                    @if($candidate->dl_file!='')
                        <a href="{{ (url('images/'.$candidate->dl_file))}}" class="text-danger">Download</a>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Upload Photo (jpg/jpeg/png)</label>
                        <div class="custom-file">
                            <input type="file" id="photo" name="photo" class="custom-file-input"  >
                            <label class="custom-file-label" for="photo">Choose file</label>
                        </div>                         
                        <input type="hidden" id="photo_old" name="photo_old" class="form-control" value="{{$candidate->photo}}">                       
                    </div>
                    @if($errors->has('photo'))
                        <label class="text-danger">{{ $errors->first('photo') }}</label>
                    @endif
                    @if($candidate->photo!='')
                        <a href="{{ (url('images/'.$candidate->photo))}}" class="text-danger">Download</a>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Upload Signature (jpg/jpeg)</label>
                        <div class="custom-file">
                            <input type="file" id="signature" name="signature" class="custom-file-input"  >
                            <label class="custom-file-label" for="signature">Choose file</label>
                        </div>
                         
                        <input type="hidden" id="signature_old" name="signature_old" class="form-control" value="{{$candidate->signature}}" >                       
                    </div>
                    @if($errors->has('signature'))
                        <label class="text-danger">{{ $errors->first('signature') }}</label>
                    @endif
                    @if($candidate->signature!='')
                        <a href="{{ (url('images/'.$candidate->signature))}}" class="text-danger">Download</a>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Father Name</label>
                        <input type="text" id="father_name" name="father_name" class="form-control" placeholder="Enter father name" value="{{old('father_name',$candidate->father_name)}}">
                    </div>
                    @if($errors->has('father_name'))
                        <label class="text-danger">{{ $errors->first('father_name') }}</label>
                    @endif
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Mother Name</label>
                        <input type="text" id="mother_name" name="mother_name" class="form-control" placeholder="Enter mother name" value="{{old('mother_name',$candidate->mother_name)}}">
                    </div>
                    @if($errors->has('mother_name'))
                        <label class="text-danger">{{ $errors->first('mother_name') }}</label>
                    @endif
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Marital Status</label>
                        <select type="text" id="marital_status" name="marital_status" >
                            <option value="">Select</option>
                            <option value="married" {{(old('marital_status',$candidate->marital_status)) == 'married' ?'selected' : ''}}>married</option>
                            <option value="unmarried" {{(old('marital_status',$candidate->marital_status))== 'unmarried' ? 'selected' : ''}}>unmarried</option>                            
                        </select>
                    </div>
                    @if($errors->has('marital_status'))
                        <label class="text-danger">{{ $errors->first('marital_status') }}</label>
                    @endif
                </div>
               
                <div class="col-md-3 spouse">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Spouse Name</label>
                        <input type="text" id="spouse_name" name="spouse_name" class="form-control" placeholder="Enter spouse name" value="{{old('spouse_name',$candidate->spouse_name)}}">
                    </div>
                    @if($errors->has('spouse_name'))
                        <label class="text-danger">{{ $errors->first('spouse_name') }}</label>
                    @endif
                </div>
               
            </div>

            
            {{--<div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                            <label  class="form-label">Job Role *</label>
                            <select  id="job_role" name="job_role" required>
                                <option value="">Select Job Role</option>
                                @foreach($job_role as $role)
                                <option  value="{{$role->id}}" {{$candidate->job_role==$role->id?"selected":""}}>{{$role->name}}</option>                                
                                @endforeach
                            </select>
                    </div>
                    @if($errors->has('job_role'))
                        <label class="text-danger">{{ $errors->first('job_role') }}</label>
                    @endif
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label  class="form-label">Total Experience (in Year) *</label>
                            <select  id="total_experience" name="total_experience"  required>                                     
                                @for($i=1;$i<=30;$i++)
                                <option value="{{$i}}" {{$candidate->total_experience==$i?"selected":""}}>{{$i}} Year</option>
                                @endfor
                            </select>
                    </div>
                    @if($errors->has('total_experience'))
                        <label class="text-danger">{{ $errors->first('total_experience') }}</label>
                    @endif
                </div>
            </div>--}}
            <div class="row">

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Resume Title</label>
                        <input type="text" id="resume_title" name="resume_title" class="form-control" placeholder="Enter Resume Title" value="{{old('resume_title',$candidate->resume_title)}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password-repeat" class="form-label">Upload CV (doc/pdf)</label>
                        <div class="custom-file">
                            <input type="file" id="cv_scan" name="cv_scan" class="custom-file-input">
                            <label class="custom-file-label" for="cv_scan">Choose file</label>
                        </div>                         
                        <input type="hidden" id="cv_scan_old" name="cv_scan_old" class="form-control" value="{{$candidate->cv_scan}}">                         
                        
                    </div>
                    @if($errors->has('cv_scan'))
                        <label class="text-danger">{{ $errors->first('cv_scan') }}</label>
                    @endif
                    @if($candidate->cv_scan!='')
                        <a href="{{(url('images/'.$candidate->cv_scan))}}" class="text-danger">Download</a>                           
                    @endif
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label  class="form-label">Total Experience (in Year) *</label>
                            <select  id="total_experience" name="total_experience"  required>                                     
                                @for($i=1;$i<=30;$i++)
                                <option value="{{$i}} year" {{old('total_experience',$candidate->total_experience)==$i.' year'?"selected":""}}>{{$i}} Year</option>
                                @endfor
                            </select>
                    </div>
                    @if($errors->has('total_experience'))
                        <label class="text-danger">{{ $errors->first('total_experience') }}</label>
                    @endif
                </div>
            </div>
            

            <div class="mt-3 mt-lg-3">
                <button class="btn rounded-pill pxp-section-cta">Save</button>
            </div>
        </form>
    </div>
    @endsection
    @push('js')
    <script>
    function getCity(state_id)
    {
        var state_id=state_id;
        console.log(state_id);
        var $select = $('#city');//$($('#city')).selectize();
        var selectize = $select[0].selectize;
        selectize.renderCache = {};
        selectize.clearOptions();
        selectize.clear();
        selectize.refreshOptions(true);

        $.ajax({
            type:'GET',
            url:"{{url('get_city')}}",
            data:'state_id='+state_id,
            success: function(response) {
                selectize.clearOptions();
                selectize.clear();
                $.each(response,function (i, city){
                    selectize.addOption({value: city.id, text: city.name });                  
                });
                selectize.refreshOptions(true);              
                console.log(response);
            }
        });
    }

    function getHr(business_id)
    {
        var business_id=business_id;
        //console.log(business_id);
        $.ajax({
            type:'GET',
            url:"{{url('get_hr')}}",
            data:'business_id='+business_id,
            success: function(response) {
                var trHTML = '<option value="" selected>Select HR</option>';
                $.each(response,function (i, hr){
                    trHTML +=
                                    '<option value='+hr.id+'>'
                                    + hr.first_name
                                    + '</option><td>';
                });
                $('#hr_id').html(trHTML);
               // console.log(response);
            }
        });
    }

</script>
<script >      
$(document).ready(function () {  
    var ms=$('#marital_status').val(); 
    if(ms=='married'){
        $(".spouse").show();
    } else{
        $(".spouse").hide();
    }
   
    $(".pan").change(function () {    
        var inputvalues = $(this).val();    
        var gstinformat = new RegExp('^[A-Z]{5}[0-9]{4}[A-Z]{1}$');    
        if (gstinformat.test(inputvalues)) {    
            return true;    
        } else {    
            alert('Please Enter Valid PAN Number');    
            $(".pan").val('');    
            $(".pan").focus();    
        }    
    });          

    $(".aadhaar").change(function () {    
        var inputvalues = $(this).val();    
        var aadharformat = new RegExp('^[0-9]{12}$');    
        if (aadharformat.test(inputvalues)) {    
            return true;    
        } else {    
            alert('Please Enter Valid AADHAAR Number');    
            $(".aadhaar").val('');    
            $(".aadhaar").focus();    
        }    
    });          
    $(".dl").change(function () {    
        var inputvalues = $(this).val();    
        var dlformat = new RegExp('^(([A-Z]{2}[0-9]{2})( )|([A-Z]{2}-[0-9]{2}))((19|20)[0-9][0-9])[0-9]{7}$');    
        if (dlformat.test(inputvalues)) {    
            return true;    
        } else {    
            alert('Please Enter Valid Driving Licence Number');    
            $(".dl").val('');    
            $(".dl").focus();    
        }    
    });          
    $(".passport").change(function () {    
        var inputvalues = $(this).val();    
        var passportformat = new RegExp('^[A-Z][0-9]{8}$');
        if (passportformat.test(inputvalues)) {    
            return true;    
        } else {    
            alert('Please Enter Valid Passport Number');    
            $(".passport").val('');    
            $(".passport").focus();    
        }    
    });   
    
    $("#marital_status").change(function () {    
        var inputvalues = $(this).val();    
         
        if (inputvalues=='married') {    
            $(".spouse").show();    
        } else {    
               
            $(".spouse").hide();    
        }    
    }); 

 });          
  </script>      

    @endpush
   