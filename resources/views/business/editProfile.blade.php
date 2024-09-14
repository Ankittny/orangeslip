@extends('admin.layouts.app')
@section('content')

        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif

<div class="pxp-dashboard-content-details">
    <h1>Edit Profile</h1>
    <p class="pxp-text-light">Update Details</p>
    
    <form action="{{url('edit_profile')}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">First Name *</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter Full Name" value="{{ $user->first_name }}" readonly>
                </div>
                @if($errors->has('first_name'))
                    <label class="text-danger">{{ $errors->first('first_name') }}</label>
                @endif
            </div>

             

            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Enter Full Name" value="{{ $user->last_name  }}" readonly>
                </div>
                @if($errors->has('last_name'))
                    <label class="text-danger">{{ $errors->first('last_name') }}</label>
                @endif
            </div>

            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Email *</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter Email" value="{{ $user->email  }}" readonly>
                </div>
                @if($errors->has('email'))
                    <label class="text-danger">{{ $errors->first('email') }}</label>
                @endif
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Mobile No *</label>
                    <input type="text" id="mobile_no" name="mobile_no" class="form-control" placeholder="Enter Full Name" value="{{ $user->profile->mobile_no }}" readonly>
                </div>
                @if($errors->has('mobile_no'))
                    <label class="text-danger">{{ $errors->first('mobile_no') }}</label>
                @endif
            </div>

        </div>

        <div class="row">
           

            

            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Gender </label>
                    <select  id="gender" name="gender"   >
                        <option value='' selected> Select</option>
                        <option value='male' {{$user->profile->gender=='male'? "selected":""}}> Male</option>
                        <option value='female' {{$user->profile->gender=='female'? "selected":""}}> Female</option>
                        <option value='other' {{$user->profile->gender=='other'? "selected":""}}> Other</option>                        
                    </select>
                </div>
                @if($errors->has('gender'))
                    <label class="text-danger">{{ $errors->first('gender') }}</label>
                @endif
            </div>

            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Marital Status </label>
                    <select  id="maritial_status" name="maritial_status"   >
                        <option value='' selected> Select</option>
                        <option value='Married' {{$user->profile->maritial_status=='Married'? "selected":""}}> Married</option>
                        <option value='Unmarried' {{$user->profile->maritial_status=='Unmarried'? "selected":""}}> Unmarried</option>                                            
                    </select>
                </div>
                @if($errors->has('maritial_status'))
                    <label class="text-danger">{{ $errors->first('maritial_status') }}</label>
                @endif
            </div>

            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Religion </label>
                    <select  id="religion" name="religion"   >
                        <option value='' selected> Select</option>
                        <option value='Hindu' {{$user->profile->religion=='Hindu'? "selected":""}}> Hindu</option>
                        <option value='Muslim' {{$user->profile->religion=='Muslim'? "selected":""}}> Muslim</option>                                            
                        <option value='Sikh' {{$user->profile->religion=='Sikh'? "selected":""}}> Sikh</option>                                            
                        <option value='Christian' {{$user->profile->religion=='Christian'? "selected":""}}> Christian</option>                                            
                                                                  
                    </select>
                </div>
                @if($errors->has('religion'))
                    <label class="text-danger">{{ $errors->first('religion') }}</label>
                @endif
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">DOB *</label>
                    <input type="date" id="dob" name="dob" class="form-control"  value="{{ $user->profile->dob }}" required>
                </div>
                @if($errors->has('dob'))
                    <label class="text-danger">{{ $errors->first('dob') }}</label>
                @endif
            </div>
        </div>

        <div class="row">
            
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Country </label>
                    <select   name="country" id="country"  required>                                                                    
                        @foreach($country as $c)
                        <option value="{{$c->id}}" @isset($user->profile->country){{$c->id==$user->profile->country ? "selected" : " " }}@else{{$c->id==old('country') ? "selected" : " " }}  @endif> {{$c->name}} (+{{$c->calling_code}})</option>
                        @endforeach                                            
                    </select>
                     
                </div>
                @if($errors->has('country'))
                    <label class="text-danger">{{ $errors->first('country') }}</label>
                @endif
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Address </label>
                    <input type="text" id="address" name="address" class="form-control" placeholder="Enter Full Address" value="{{$user->profile->address }}" >
                </div>
                @if($errors->has('address'))
                    <label class="text-danger">{{ $errors->first('address') }}</label>
                @endif
            </div>

            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Pin Code *</label>
                    <input type="text" id="pin_code" name="pin_code" class="form-control" placeholder="Enter Pin Code" value="{{ $user->profile->pin_code  }}" required>
                </div>
                @if($errors->has('pin_code'))
                    <label class="text-danger">{{ $errors->first('pin_code') }}</label>
                @endif
            </div>

            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Profile Image (jpg,jpeg) </label>
                    <div class="custom-file">
                        <input type="file" id="avatar" name="avatar" class="custom-file-input" >
                        <label class="custom-file-label" for="avatar">Choose file</label>
                    </div>
                     
                    <input type="hidden" name="old_avatar" value="{{$user->profile->avatar}}">
                </div>
                @if($errors->has('avatar'))
                    <label class="text-danger">{{ $errors->first('avatar') }}</label>
                @endif
                {{--<img src="{{ ($user->profile->avatar!='')?(url('images/'.$user->profile->avatar)):(url('/new/images/noimage.png')) }}" alt="No Image" style="width: 100px; height: 100px;"/>--}}
            </div>
            <div class="col-md-3">
                
                
            </div>
            
        </div>
@if(Auth::user()->account_type=='business')
<hr>
    <div class="row">
            

            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Business Name </label>
                    <input type="text" id="business_name" name="business_name" class="form-control" placeholder="Enter Business Name" value="{{ $user->business->business_name }}" readonly>
                </div>
                @if($errors->has('business_name'))
                    <label class="text-danger">{{ $errors->first('business_name') }}</label>
                @endif
            </div>

            <div class="col-md-3">
                <div class="mb-3">
                    <label for="business_address" class="form-label">Business Address </label>
                    <input type="text" id="business_address" name="business_address" class="form-control" placeholder="Enter Business Address" value="{{ $user->business->business_address }}" readonly>
                </div>
                @if($errors->has('business_address'))
                    <label class="text-danger">{{ $errors->first('business_address') }}</label>
                @endif
            </div>
         
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="registration_date" class="form-label">Registration Date </label>
                    <input type="date" id="registration_date" name="registration_date" class="form-control" placeholder="Enter Registration Date" value="{{$user->business->registration_date }}" readonly>
                </div>
                @if($errors->has('registration_date'))
                    <label class="text-danger">{{ $errors->first('registration_date') }}</label>
                @endif
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="registration_doc" class="form-label">No Of Employee</label>
                    
                    <input type="text" id="no_of_employee" name="no_of_employee" class="form-control" placeholder="Enter No Of Employee" value="{{$user->business->noOfEmp->range_start}} - {{$user->business->noOfEmp->range_end}}" readonly>
                </div>
                @if ($errors->has('no_of_employee'))
                        <label class="text-danger">{{ $errors->first('no_of_employee') }}</label>
                    @endif
            </div>
                       
        </div>

      
        <div class="row">            

            <div class="col-md-3">
                <div class="mb-3">
                    <label for="business_logo" class="form-label">Business Logo *</label>
                    <input type="file" id="business_logo" name="business_logo" class="form-control" >
                    <input type="hidden" id="old_logo" name="old_logo" value="{{$user->business->logo}}" >
                </div>
                @if($errors->has('business_logo'))
                    <label class="text-danger">{{ $errors->first('business_logo') }}</label>
                @endif
                @if($user->business->logo!='')
                <img class="dp" src="{{ (url('images/'.$user->business->logo))}}" alt="">
                @endif
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="gst" class="form-label {{ $errors->has('gst')?' has-error':' has-feedback' }}">GST No. *</label>
                    <input type="text" id="gst" name="gst" class="form-control gst" placeholder="Enter GST No." value="{{$user->business->gst}}" required readonly>
                    @if ($errors->has('gst'))
                        <label class="text-danger">{{ $errors->first('gst') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="pan {{ $errors->has('pan')?' has-error':' has-feedback' }}" class="form-label">PAN No. *</label>
                    <input type="text" id="pan" name="pan" class="form-control pan" placeholder="Enter PAN No." value="{{$user->business->pan}}" required readonly>
                    @if ($errors->has('pan'))
                        <label class="text-danger">{{ $errors->first('pan') }}</label>
                    @endif
                </div>
            </div>
            {{-- 
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="registration_date" class="form-label">Registration Doc *</label>
                    <input type="file" id="registration_doc" name="registration_doc" class="form-control"     required>
                </div>
                @if($errors->has('registration_doc'))
                    <label class="text-danger">{{ $errors->first('registration_doc') }}</label>
                @endif
            </div>  
            --}}  
        </div>  
        
    
           
    @endif
         
        
         
        <div class="mb-3">
                    <label  class="form-label">&nbsp;</label>
                    <button class="btn rounded-pill pxp-section-cta btn-block">Save</button>
                </div>
        
    </form>
</div>
@endsection